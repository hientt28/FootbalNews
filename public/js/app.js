var app = (function () {
    var map = {};
    var bindEvent = function () {
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

        $('form[name="match-update-form"]').submit(function(e){
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
      
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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

     var initMapWindow = function(_grid){
        var jqxWidget = _grid;
        var offset = jqxWidget.offset();    
        $('#window').jqxWindow({
            position: { x: offset.left + 50, y: offset.top + 50} ,
            theme : 'ui-redmond',
            showCollapseButton: true, maxHeight: 400, maxWidth: 700, minHeight: 200, minWidth: 200, height: 300, width: 500,
            initContent: function () {
                $('#window').jqxWindow('focus');
            }
        });
    }

    var viewLocation = function(_grid){
        var grid = $('#' + _grid);
        initMapWindow(grid);
        var index = grid.jqxGrid('getselectedrowindex');
        if(index != -1) {
            var row = grid.jqxGrid('getrowdata', index);
            var location = row.location;
            getDirections(location);
        }
    }

    return {
        bindEvent : bindEvent,
        initMap : initMap,
        viewLocation : viewLocation,
        getDirections : getDirections
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
            if(k == 'time') {
                column.columntype = 'datetimeinput';
                column.cellsformat = 'dd-MMMM-yyyy hh:mm:ss';
            }
            columns.push(column);   
        }

        return columns;
    }

    this.setDropDownList = function(obj, objHidden, source) {
        var valDefault = $(objHidden).val();
        $(obj).jqxDropDownList(
            {   source : source, 
                width : '325px', 
                height :'35px',
                renderer: function (index, label, value) 
                {
                    var datarecord = source[index];
                    return '<img src="' + datarecord.logo +'" width="30" height="30"/> ' + datarecord.description ;
                },
                selectionRenderer: function (htmlString) 
                {
                    var item = $(obj).jqxDropDownList('getSelectedItem');
                    if (item) {
                        return "<b>" + source[item.index].description + "</b>";
                    }
                    return "<b>Please Choose:</b>";
                },
                valueMember : 'id',
                autoDropDownHeight : true,
                theme : 'ui-redmond'
            });
            try {
                valDefault ? $(obj).jqxDropDownList('val', valDefault) : ''; 
            } catch(e) {
                console.log(e);
            }
            
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
            if (data.end && columnproperties.datafield == 'result') {
                return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + '; color: #ff0000;">' + value + '</span>';
            } else if(columnproperties.datafield == 'logo') {
                return '<img src="' + data.logo + '" width="100%" height="100%" />'
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
                        width : $('.ui.segment.content').width() - 40,
                        source: data, 
                        theme : 'ui-redmond',               
                        pageable: true,
                        autoheight: true,
                        autorowheight: true,   
                        showfilterrow : true,
                        sortable: true,
                        editable : false,
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
                    }
                }    
            );
        }.bind(this));
    }.bind(this);

    var initFormCreate  = function () {
        var grid = $('#team_list');
        if(!grid) {
            return;
        }
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

            this.initGrid($('#events_list'), {menu : $('#menu-events'),height : '106px',width : '160px'}, 
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

    var initMapWindow = function(){
        var jqxWidget = $('input[name="address"]');
        var offset = jqxWidget.offset();    
        $('#window').jqxWindow({
            position: { x: offset.left + 50, y: offset.top + 50} ,
            theme : 'ui-redmond',
            showCollapseButton: true, maxHeight: 400, maxWidth: 700, minHeight: 200, minWidth: 200, height: 300, width: 500,
            initContent: function () {
                $('#window').jqxWindow('focus');
            }
        });
    }

    var run = function () {
        getList();
        initFormCreate();
        initMapWindow();
    }

    return run();
}).bind(gridBuilder);

$(document).ready(function(){
	app.bindEvent();
    matches();
});