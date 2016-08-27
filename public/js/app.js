var app = (function () {
    var map = {};

    var utils = new function() {
        this.search = function (arr, key, value) {
            for (var k in arr) {
                if(arr[k]['' + key] == value)
                    return arr[k];
            }

            return null; 
        }

        Number.prototype.formatMoney = function(c, d, t){
            var n = this, 
                c = isNaN(c = Math.abs(c)) ? 2 : c, 
                d = d == undefined ? "." : d, 
                t = t == undefined ? "," : t, 
                s = n < 0 ? "-" : "", 
                i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
                j = (j = i.length) > 3 ? j % 3 : 0;
               return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
         };

    }

    var resetLocal = function () {
        localStorage.removeItem('rate')
        localStorage.removeItem('teams');
    }

    var bindEvent = function () {
        resetLocal();
        
        var form = $('form[name="form-bet"]');
        if(form.length > 0) {
            $('form[name="form-bet"]').jqxValidator({
                     animation: 'none',
                     rules: [{
                         input: '#team-guess',
                         message: 'Team guess is required!',
                         action: 'blur',
                         rule: function (input){
                            var val = input.val();
                            if(!val)
                                return false
                            return true;
                         },
                        position : 'topcenter'
                     }, {
                        input: '#result',
                        message: 'result',
                        action: 'blur',
                        rule: function (input){
                            var val = input.val();
                            if(!val)
                                return false
                            return true;
                        },
                        position : 'topcenter'
                     }, {
                         input: '#price',
                         message: 'Price is required',
                         action: 'blur',
                         rule: function (input){
                            var val = input.val();
                            if(!val)
                                return false
                            return true;
                         },
                         position : 'topcenter'
                     }]
             });
        }

        $('.toggle').on('click', function() {
            $('.login-form').stop().addClass('active');
        });

        $('.close').on('click', function() {
            $('.login-form').stop().removeClass('active');
        });

        $('.ui.star.rating').rating({
            initialRating: 2,
            maxRating: 4
        });

        $('form[name="match-update-form"], form[name="form-bet"]').submit(function(e){
                e.preventDefault();
                return false;
        });   

        $('button[name="edit-match"]').on('click', function(){
            var events = $('#events_list').jqxGrid('getrows');
            if(events != null && events != undefined) {
                $('input[name="events_data"]').val(JSON.stringify(events));
            }
            var form = $('form[name="match-update-form"]');
            form.unbind('submit');
            form.submit();
        });

        $('#usermatchWindow').on('close', function(){
             $('form[name="form-bet"]').jqxValidator('hide');
        })

        $('#price').on('change', function(){
            var rate = localStorage.getItem('rate');
            var price = $('#price').val();
            var bonus = rate > 0 ? rate*price : price;
            $('.bonus').text(bonus.formatMoney(2, '.', ',') + ' d');
        })

        $('button[name="bet-match"]').on('click', function (){ 
            var flag = $('form[name="form-bet"]').jqxValidator('validate');
            if(!flag)
                return;
            
            $.ajax({
                type : 'GET',
                data : {
                    sendNotification : true,
                    teamGuess : $('#team-guess').val(),
                    result : $('#result').val(),
                    price : $('#price').val(),
                    matchId : $('#match_bet').attr('matchId')
                },
                beforeSend:function(){
                    $('form[name="form-bet"]').addClass('ui loading form');
                }
            }).done(function(res) {
                $('form[name="form-bet"]').removeClass('ui loading form');
                if(res.resultBet) {
                    $("#result-bet").jqxNotification({template : 'info'});
                    $("#result-bet").text('Bet Match Successfully!');
                    $("#result-bet").jqxNotification('open');
                } else {
                    $("#result-bet").jqxNotification({template : 'error'});
                    $("#result-bet").text('Bet Match Error!');
                    $("#result-bet").jqxNotification('open');
                }
            })
        });
      
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var socket = io.connect('http://localhost:8890');
        socket.on('message', function (data) {
            var msg = $('#message');
            try {
                data = $.parseJSON(data);
            } catch(e) {
                if(data.indexOf('admin')) {
                     $('.msg-user-content').text('Admin has close match!'); 
                     $('#messageToUser').jqxNotification('open');
                }
            }
            
            if(data.avatar) {
                $('#user-bet').attr('src', data.avatar);
            }

            $('.msg-content').text('User ' + (data.user_name ? data.user_name : '') + ' bet a match'); 
            var totalMsg = parseInt($('#_message').text());
            if(typeof totalMsg != NaN) {
                $('#_message').text(++totalMsg);
            }               
            msg.jqxNotification('open');
        });

        getTotalNotification();
    }

     var getTotalNotification = function () {
        $.ajax({
            url : 'getTotalNotification',
            type : 'POST', 
            beforeSend : function(){
                $('#_message').addClass('ui loading form');
            }
        }).done(function(res) {
            if(res.total) {
                $('#_message').removeClass('ui loading form');
                $('#_message').text(res.total);
            }
        });
    }

    var initMap = function() {
         map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 21.0104218, lng: 105.81846159999998},
          zoom: 13,
          mapTypeId: 'roadmap'
        });

        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              console.log("Returned place contains no geometry");
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            for(var k in markers) {
                markers[k].addListener('click', function() {
                    var pos = markers[k].getPosition();
                    var address = markers[k].title;
                    $('input[name="address"]').val(address);
                    $('input[name="location"]').val(address);
                    $('#window').jqxWindow('close');
                });
            }

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
      }

      var calculateRoute = function (from, to) {
            var directionsService = new google.maps.DirectionsService();
            var directionsRequest = {
              origin: from,
              destination: to,
              travelMode: google.maps.DirectionsTravelMode.DRIVING,
              unitSystem: google.maps.UnitSystem.METRIC
            };
            directionsService.route(
              directionsRequest,
              function(response, status)
              {
                if (status == google.maps.DirectionsStatus.OK)
                {
                    new google.maps.DirectionsRenderer({
                        map: map,
                        directions: response
                    });
                    setTimeout(function () {
                         google.maps.event.trigger(map, 'resize');
                    }, 1000);
                }
                else {
                    $('#map').append('<h3>Unable to retrieve your address<br /></h3>')
                } 

                $('#window').jqxWindow('open');
              }
            );
    }

    var getDirections = function (location) {
        var pos = null;
        if (typeof navigator.geolocation == "undefined" || location == null) {
            $("#error").text("Your browser doesn't support the Geolocation API");
            return;
        }
        navigator.geolocation.getCurrentPosition(function(position) {
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({
              "location": new google.maps.LatLng(position.coords.latitude, position.coords.longitude)
            },
            function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    pos = results[0].formatted_address;
                    calculateRoute(pos, location);
                    
                } else
                {
                    $('#map').empty();   
                    $('#map').html('<h3>Unable to retrieve your address<br /></h3>')
                }
            });
          },
          function(positionError){
            $("#error").append("Error: " + positionError.message + "<br />");
          },
          {
            enableHighAccuracy: true,
            timeout: 10 * 1000 // 10 seconds
          });

        return pos;
    }

    var initMapWindow = function(_grid, menu){
        var jqxWidget = _grid;
        var offset = jqxWidget.offset();    
        menu.jqxWindow({
            position: { x: offset.left + 50, y: offset.top + 50} ,
            theme : 'ui-redmond',
            showCollapseButton: true, maxHeight: 400, maxWidth: 700, minHeight: 200, minWidth: 200, height: 300, width: 500,
            initContent: function () {
                menu.jqxWindow('focus');
            }
        }); 
        menu.jqxWindow('open');
    }

    var viewLocation = function(_grid){
        var grid = $('#' + _grid);
        initMapWindow(grid, $('#window'));
        var index = grid.jqxGrid('getselectedrowindex');
        if(index != -1) {
            var row = grid.jqxGrid('getrowdata', index);
            var location = row.location;
            getDirections(location);
        }
    }

    var betMatch = function(_grid){
        var grid = $('#' + _grid);
        initMapWindow(grid, $('#usermatchWindow'));
    }

    return {
        bindEvent : bindEvent,
        initMap : initMap,
        viewLocation : viewLocation,
        betMatch : betMatch,
        getDirections : getDirections,
        initMapWindow : initMapWindow,
        utils : utils
    }
} ())

    
var gridBuilder = new function() {
    this.labels = [];
    this.setConfig = function (datafields, records) {
        this.datafields = datafields;
        this.records = records;
    }

    this.setDatafields = function (datafields) {
        this.datafields_source = datafields;
    }

    this.columns = function (cellsrenderer) {    
        var columns = [];
        for (var k in this.datafields) {
            var datafield = this.datafields[k];
            var column = {
                text : datafield, 
                datafield : k,
                align : 'center',
                cellsalign : 'center',
                cellsrenderer : cellsrenderer ? cellsrenderer : function(){}
            };
            k == 'rate' ? column.cellsformat = 'p' : void 0;
            if(k == 'rate') {
                column.cellsformat = 'p'
            }
            if(k == 'time' || k == 'created_at') {
                column.columntype = 'datetimeinput';
                column.cellsformat = 'dd-MMMM-yyyy hh:mm:ss';
            }
            if(k == 'message') {
                column.width = '30%';
            }
            columns.push(column);   
        }

        return columns;
    }

    this.setDropDownList = function(obj, objHidden, source, config) {
        var valDefault = $(objHidden).val();
        var _cf =  {   source : source, 
                    width : config && config.width ? config.width : '325px', 
                    height : config && config.height ? config.height : '35px',
                    renderer: function (index, label, value) 
                    {
                        var datarecord = source[index];
                        if(datarecord == null)
                            return;

                        if(datarecord.value && datarecord.description) {
                             return '<img src="' + datarecord.logo +'" width="30" height="30"/> ' + datarecord.description ;
                         } else if(datarecord.id && datarecord.name) {
                                return datarecord.name;
                         }
                       return value;
                    },
                    selectionRenderer: function (htmlString) 
                    {
                        var item = $(obj).jqxDropDownList('getSelectedItem');
                        if(item != null ) {
                            if (source[item.index] && source[item.index].description) {
                                return "<b>" + source[item.index].description + "</b>";
                            } else return '<b>' + item.label+ '</b>';
                        }

                        return "<b>Please Choose:</b>";
                    },
                    autoDropDownHeight : true,
                    theme : 'ui-redmond'
                }
                if(config && config.value != null ) {
                    _cf.valueMember = config.value;
                }
                
                try {
                    if(obj != null) {
                        $(obj).jqxDropDownList(_cf);
                        valDefault ? $(obj).jqxDropDownList('val', valDefault) : ''; 
                    }
                } catch(e) {
                    console.log(e);
                }
            
    }

    this.initFormBet = function (match) {
        var teams = localStorage.getItem('teams');
        teams = teams != 'undefined' ? $.parseJSON(teams) : [];
        localStorage.setItem('rate', match.rate);
        if(match != null && teams != null) {
            var home = app.utils.search(teams, 'id', match.home_id);
            var guest = app.utils.search(teams, 'id',match.guest_id);
            var data = [];
            if(guest != null && home != null) {
                data.push({value : home.id,description : home.name, logo : home.logo});
                data.push({value : guest.id,description : guest.name, logo : guest.logo});
                $('#match_bet').html(home.name + '-' + guest.name);
                $('#match_bet').attr('matchId', match.id);
            }
        }
        this.setDropDownList($('#result'), null, ['Win', 'Lose', 'Draw'], {width : '225px', height : '25px', value : null});
        this.setDropDownList($('#team-guess'), null, data, {width : '225px', height : '25px'});
        $('#price').jqxNumberInput({theme : 'ui-redmond', width: '225px', height: '25px', spinButtons: true });
        $("#result-bet").jqxNotification({
                width: 500, opacity: 0.9,appendContainer : '#result-bet-container',
                autoOpen: false, animationOpenDelay: 800, autoClose: true, 
                autoCloseDelay: 1000, template: "info",
        });
    }

    this.contextMenu = function (grid, config, callback) {    
        var menu = config.menu;
        if(!menu) {
            return;
        }

        var contextMenu = menu.jqxMenu({
            width : config.width ? config.width : 200,
            height : config.height ? config.height : 58,
            autoOpenPopup : config.autoOpenPopup ? config.autoOpenPopup : false,
            mode : config.mode ? config.mode : 'popup'
        });

        grid.on('contextmenu', function () {
            return false;
        });

        menu.on('itemclick', function (event) {
            if(typeof callback == "function") {
                callback(grid, event);
            }
        });

        $('li[type="excel"], li[type="csv"], li[type="pdf"]').on('click', function(){
            var type = $(this).attr('type');
            if(type == 'pdf')
            {
                grid.jqxGrid('exportdata', 'pdf', 'jqxGrid');    
            } else if(type == 'excel') {
                 grid.jqxGrid('exportdata', 'xls', 'jqxGrid');
            } else if(type == 'csv') {
                 grid.jqxGrid('exportdata', 'csv', 'jqxGrid');
            }   
        })

        $('li[type="add"]').addClass('mini ui teal button');
        $('li[type="edit"]').addClass('mini ui blue button');
        $('li[type="delete"]').addClass('mini ui red button');
        $('li[type="bet"]').addClass('mini ui teal button');
        $('li[type="show"]').addClass('mini ui grown button');
        $('li[type="map"]').addClass('mini ui yellow button');
        $('li[type="export"]').addClass('mini ui blue button');

        grid.on('rowclick', function (event) {
            if (event.args.rightclick) {
                grid.jqxGrid('selectrow', event.args.rowindex);
                var scrollTop = $(window).scrollTop();
                var scrollLeft = $(window).scrollLeft();
                contextMenu.jqxMenu('open', parseInt(event.args.originalEvent.clientX) + 5 + scrollLeft, parseInt(event.args.originalEvent.clientY) + 5 + scrollTop);
                return false;
            }
        });

        grid.on('mousedown', function (event) { 
            switch (event.which) {
                case 1:
                    break;
                case 2:
                    break;
                case 3:
                    contextMenu.jqxMenu('open', event.pageX, event.pageY);
                    break;
                default: break;
            }
        });
    }

    this.initGrid = function (grid, configMenu, callback) {
        if(grid === undefined || grid.length <= 0)
            return;

        var cellsrenderer = function (
            row, 
            columnfield, 
            value, 
            defaulthtml, 
            columnproperties,
            rowdata
         ) {
            var data = grid.jqxGrid('getrowdata', row);
            var teams = localStorage.getItem('teams');
            var teams = teams != 'undefined' ? $.parseJSON(teams) : [];
            if (data.end && columnproperties.datafield == 'result') {
                return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + '; color: #ff0000;">' + value + '</span>';
            } else if(columnproperties.datafield == 'logo') {
                return '<img src="' + data.logo + '" width="100%" height="100%" />'
            } else if(columnproperties.datafield == 'home_id') {
                var home = app.utils.search(teams, 'id', data.home_id);
                home = home ? home : {logo : '', name : 'test'}
                return '<span style="margin: 4px;"><img src="' + home.logo +'" width="20px" height="20px"/> ' + home.name + '</span>';
            } else if(columnproperties.datafield == 'guest_id') {
                var guest = app.utils.search(teams, 'id', data.guest_id);
                guest = guest ? guest : {logo : '', name : 'test'}
                return '<span style="margin: 4px;"><img src="' + guest.logo +'" width="20px" height="20px"/> ' + guest.name + '</span>';
            } else if(columnproperties.datafield == 'user_id') {
                return '<span style="margin: 4px;">' + data.user_name + '</span>';
            } else if(columnproperties.datafield == 'status') {
                if(data.status == 0)
                    return '<span style="margin: 4px;">' + 'Not Watch' + '</span>';
                else 
                    return '<span style="margin: 4px;">' + 'Watched' + '</span>';
            } else if(columnproperties.datafield == 'created_at') {
                return '<span style="margin: 4px;">' + data.created_at.time + '</span>';
            }

            return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + ';">' + value + '</span>';
        }

        var source = {
            localdata : this.records,
            datafields : this.datafields_source,
            datatype : 'json'
        }

        var data = new $.jqx.dataAdapter(source);
        var columns = this.columns();

        var config = {
            width : configMenu.width ? configMenu.width : $('.ui.segment.content').width() - 40,
            source: data, 
            theme : 'ui-redmond',               
            pageable: true,
            autoheight: true,
            autorowheight: true,   
            showfilterrow : true,
            sortable: true,
            editable : configMenu && configMenu.editable ? configMenu.editable : false,
            editmode : 'dblclick',
            altrows: true,
            filterable: true,
            enabletooltips: true,
            columns: this.columns(cellsrenderer),
        };

        if (configMenu == null) {
            config.rendered = function () {
                // select all grid cells.
                var gridCells = grid.find('.jqx-grid-cell');
                if (grid.jqxGrid('groups').length > 0) {
                    gridCells = grid.find('.jqx-grid-group-cell');
                }
                // initialize the jqxDragDrop plug-in. Set its drop target to the second Grid.
                gridCells.jqxDragDrop({
                    appendTo: 'body',  dragZIndex: 99999,
                    dropAction: 'none',
                    initFeedback: function (feedback) {
                        feedback.height(70);
                        feedback.width(220);
                    }
                });
                // initialize the dragged object.
                gridCells.off('dragStart');
                gridCells.on('dragStart', function (event) {
                    var value = $(this).text();
                    var position = $.jqx.position(event.args);
                    var cell = grid.jqxGrid('getcellatposition', position.left, position.top);
                    $(this).jqxDragDrop('data', grid.jqxGrid('getrowdata', cell.row));
                    var groupslength = grid.jqxGrid('groups').length;
                    // update feedback's display value.
                    var feedback = $(this).jqxDragDrop('feedback');
                    var feedbackContent = $(this).parent().clone();
                    var table = '<table>';
                    $.each(feedbackContent.children(), function (index) {
                        if (index < groupslength)
                            return true;
                            table += '<tr>';
                            table += '<td>';
                            table += columns[index - groupslength].text + ': ';
                            table += '</td>';
                            table += '<td>';
                            table += $(this).text();
                            table += '</td>';
                            table += '</tr>';                           
                    });
                    table += '</table>';
                    feedback.html(table);
                });
                gridCells.off('dragEnd');
                gridCells.on('dragEnd', function (event) {
                    var value = $(this).jqxDragDrop('data');
                    var position = $.jqx.position(event.args);
                    var pageX = position.left;
                    var pageY = position.top;
                    var $home = $(".home-text");
                    var $guest = $(".guest-text");
                    var targetX = $home.offset().left;
                    var targetY = $home.offset().top;
                    var guestX = $guest.offset().left;
                    var guestY = $guest.offset().top;
                    var width = $home.width();
                    var height = $home.height();
                    var guestW = $guest.width();
                    var guestH = $guest.height();

                    // fill the form if the user dropped the dragged item over it.
                    if (pageX >= targetX && pageX <= targetX + width) {
                        if (pageY >= targetY && pageY <= targetY + 40) {
                            $home.html('<img src="' + value.logo + '" width="25" height="25" /> ' + value.name);
                            $('input[name="home_id"]').val(value.id);
                            $home.css('color', 'red');
                        }
                    }

                    if (pageX >= guestX && pageX <= guestX + guestW) {
                        if (pageY >= guestY && pageY <= guestY + 40) {
                            $guest.html('<img src="' + value.logo + '" width="25" height="25"/> ' + value.name);
                            $('input[name="guest_id"]').val(value.id);
                            $guest.css('color', 'red');
                        }
                    }
                });
            }
        }

        grid.jqxGrid(config);
        try {
            if (configMenu != null) {
                this.contextMenu(grid, configMenu, callback);
            }
        } catch (e) {
            console.log(e);
        }
    }

}

var matches = (function () {
    var container = $('#Menu');
    var _teams = [];
    if(!container) {
        return;
    }
    var getList = function () {
        $.ajax({
            url : 'matches',
            type : 'GET',
            beforeSend : function(){
                container.addClass('ui loading form');
            }
        }).done(function (res) {
            container.removeClass('ui loading form');
            var matches = res.records;
            var datafields = res.datafields;
            var leagues = res.leagues;
            localStorage.setItem('teams', JSON.stringify(res.teams));
            this.setConfig(datafields, matches);
            this.setDatafields(
                [
                    { name: 'id', type: 'string' },
                    { name: 'home_id', type: 'int' },
                    { name: 'guest_id', type: 'int' },
                    { name: 'league_season_id', type: 'int' },
                    { name: 'result', type: 'string' },
                    { name: 'location', type: 'string' },
                    { name: 'rate', type: 'float' },
                    { name: 'start', type: 'datetime' },
                    { name: 'end', type: 'datetime' },
                ]
            );

            this.initGrid($('#jqxgrid'), {menu : $('#Menu')}, 
                function (grid, event) {
                    var args = event.args;
                    var rowindex = grid.jqxGrid('getselectedrowindex');
                    var row = grid.jqxGrid('getrowdata', rowindex);
                    var content = $.trim($(args).text());
                    if (content == "Edit Selected Row") {
                        localStorage.setItem('action_form', "edit");
                        window.location.href = "matches/" + row.id + "/edit";
                    }else if(content == "Add New Row") {
                        localStorage.setItem('action_form', 'create');
                        window.location.href = "matches/create";
                    } else if(content == "Bet a Match") {
                        $('#usermatchWindow').jqxWindow('open');
                        this.initFormBet(row);
                    }
                }.bind(this)    
            );
            var msg = $("#message");
            if(msg.length > 0) {
                $("#message").jqxNotification({
                    width: 250, position: "top-right", opacity: 0.9,
                    autoOpen: false, animationOpenDelay: 800, autoClose: false, 
                    autoCloseDelay: 5000, template: "info",
                });
            }
            
            msg = $("#messageToUser");
             if(msg.length > 0) {
                $("#messageToUser").jqxNotification({
                    width: 250, position: "top-right", opacity: 0.9,
                    autoOpen: false, animationOpenDelay: 800, autoClose: false, 
                    autoCloseDelay: 5000, template: "info",
                });
             }
        }.bind(this));
    }.bind(this);

    var initFormCreate  = function () {
        var grid = $('#team_list');
       /* if(!grid) {
            return false;
        }*/
        var action = localStorage.getItem('action_form');
        var url = action != null ? action : 'create';
        $.ajax({
            url : url,
            type : 'GET',
            beforeSend : function(){
                grid.addClass('ui loading form');
            }
        }).done(function (res) {
            grid.removeClass('ui loading form');
            var teams = res.records;
            var datafields = res.datafields;
            var leagues = res.leagues;
            this.setConfig(datafields, teams);
            this.setDropDownList($('#league-list'), $('input[name="league_season_id"]'), leagues);
            this.setDatafields(
                [
                    { name: 'id', type: 'int' },
                    { name: 'name', type: 'string' },
                    { name: 'logo', type: 'string' },
                    { name: 'country_id', type: 'string' },
                    { name: 'description', type: 'string' },
                ]
            );
            this.initGrid(grid, null, null);

            //init events
            var events = res.events;
            var datafields_events = res.datafields_events;
            this.setConfig(datafields_events, events);
            this.setDatafields(
                [
                    { name: 'id', type: 'int' },
                    { name: 'content', type: 'string' },
                    { name: 'time', type: 'date' },
                ]
            );

            this.initGrid($('#events_list'), {menu : $('#menu-events'), editable : true,height : '106px',width : '160px'}, 
                function (grid, event) {
                    var args = event.args;
                    var rowindex = grid.jqxGrid('getselectedrowindex');
                    var row = grid.jqxGrid('getrowdata', rowindex);
                    var content = $.trim($(args).text());
                    if (content == "Edit Selected Row") {

                    }else if(content == "Add New Row") {
                        grid.jqxGrid('addrow', null, {});
                    }else if(content == "Delete Selected Row") {
                        grid.jqxGrid('deleterow', rowindex);
                    }
                }        
            );

        }.bind(this));

        //create number input
        var rate =  $('#rate');
        var league_list = $('#league-list');
        rate.jqxNumberInput({theme : 'ui-redmond', width: '325px', height: '35px', spinButtons: true });
        var val = $('input[name="rate"]').val();
        val ? rate.jqxNumberInput('val', val) : '';

        rate.change(function(){
            $('input[name="rate"]').val(rate.val());
        })

        league_list.change(function(){
            $('input[name="league_season_id"]').val(league_list.val());
        })
    }.bind(this);

    var run = function () {
        getList();
        app.initMapWindow($('input[name="address"]'), $('#window'));
        initFormCreate();
    }

    return run();
}).bind(gridBuilder);


var notifications = new function () {
    this.initNotificationsList = function (data) {
        if(data) {
            this.setConfig(data.datafields, data.notifications);
            this.setDatafields(
                [
                    { name: 'id', type: 'int' },
                    { name: 'message', type: 'string' },
                    { name: 'status', type: 'string' },
                    { name: 'user_id', type: 'string' },
                    { name: 'user_name', type: 'string' },
                    { name: 'create_at', type: 'datetime' },
                ]
            );

            this.initGrid($('#notifications-list'), {}, null);
            var _window = $('#notifications-window');
            var offset = $('.ui.segment.content').offset();
            _window.jqxWindow({
                position: { x: offset.left + 50, y: offset.top + 50} ,
                theme : 'ui-redmond',
                showCollapseButton: true, maxHeight: 800, maxWidth: 1000, minHeight: 200, minWidth: 200, height: 500, width: 740,
                initContent: function () {
                    _window.jqxWindow('focus');
                }
            }); 
            _window.jqxWindow('open');
        }

    }.bind(gridBuilder);

    this.displayMessage = function(callback) {
        $.ajax({
            url : 'getListNotifications',
            type : 'POST', 
        }).done(function(res) {
            if(res.notifications) {
                this.initNotificationsList(res);
            }
        }.bind(this));
    }
}

var newsBuilder = new function() {
    this.showDetail = function (url) {
        window.location.href = url;
    }

    this.lazyload = function () {
        var obj = $('.image.lazy');
        if(obj.length > 0) {
            obj.lazyload({
                effect : "fadeIn",
                event : "scroll filter"
            });
        }
    }

    this.initMatchesGrid = function (data) {
        var grid = $('#matches-list');
        var dropdown = $("#matches-dropdown");
        this.setConfig(data.datafields, data.matches);
        this.setDatafields(
            [
                { name: 'id', type: 'int' },
                { name: 'home_id', type: 'string' },
                { name: 'guest_id', type: 'string' },
                { name: 'home_name', type: 'string' },
                { name: 'guest_name', type: 'string' },
            ]
        );

        this.initGrid(grid, {width : 500}, null);

        grid.on('rowselect', function (event) {
            var args = event.args;
            var row = grid.jqxGrid('getrowdata', args.rowindex);
            var dropDownContent = '<div style="position: relative; margin-left: 3px; margin-top: 5px;">' + row['home_name'] + ' - ' + row['guest_name'] + '</div>';
            dropdown.jqxDropDownButton('setContent', dropDownContent);
            dropdown.attr('value', row.id);
            dropdown.jqxDropDownButton('close');
        });
    }.bind(gridBuilder);

    var parent = this;
    this.dropDownMatches = function () {

        $.ajax({
            url : 'news',
            type : 'GET'   
        }).done(function(res){
            console.log(res);
            $("#matches-dropdown").jqxDropDownButton({
                width: 250, height: 25, theme : 'ui-redmond'
            });
            this.initMatchesGrid(res);
        }.bind(parent));
        
    }

    this.editor = function () {
        /*var editor = $('#content');
        editor.jqxEditor({
            height: 350,
            width: 550,
            theme: 'ui-redmond',
        });*/
    }

    this.bindEvent = function () {

        $('.add-news').click(function () {
        })
    }

    this.init = function () {
        this.lazyload();
        this.dropDownMatches();
        this.editor();
    }
}

$(document).on('ready page:load', function(){
    app.bindEvent();
    newsBuilder.init();
    matches();
})
