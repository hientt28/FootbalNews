
$(document).ready(function(){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	$('.toggle').on('click', function() {
	  $('.login-form').stop().addClass('active');
	});

	$('.close').on('click', function() {
	  $('.login-form').stop().removeClass('active');
	});

	$('.ui.star.rating')
	  .rating({
	    initialRating: 2,
	    maxRating: 4
	  });

			var data = [{ "empName": "test", "age": "67", "department": { "id": "1234", "name": "Sales" }, "author": "ravi"}];
			var source =
			{
			    datatype: "json",
			    datafields: [
			        { name: 'empName' },
			        { name: 'age' },
			        { name: 'id', map: 'department&gt;id' },
			        { name: 'name', map: 'department&gt;name' },
			        { name: 'author' }
			    ],
			    localdata: data
			};

            var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
                if (value < 20) {
                    return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + '; color: #ff0000;">' + value + '</span>';
                }
                else {
                    return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + '; color: #008000;">' + value + '</span>';
                }
            }
            var dataAdapter = new $.jqx.dataAdapter(source, {
                downloadComplete: function (data, status, xhr) { },
                loadComplete: function (data) { },
                loadError: function (xhr, status, error) { }
            });
            // initialize jqxGrid
            $("#jqxgrid").jqxGrid(
            {
                width: 850,
                source: dataAdapter,
                theme : 'ui-redmond',
                pageable: true,
                autoheight: true,
                sortable: true,
                altrows: true,
                enabletooltips: true,
                editable: true,
                selectionmode: 'multiplecellsadvanced',
                columns: [
                  { text: 'empName', columngroup: 'ProductDetails', datafield: 'empName', width: 250 },
                  { text: 'age', columngroup: 'ProductDetails', datafield: 'age', cellsalign: 'right', align: 'right', width: 200 },
                  { text: 'author', columngroup: 'ProductDetails', datafield: 'author', align: 'right', cellsalign: 'right', cellsformat: 'c2', width: 200 },
                  { text: 'id', datafield: 'id', cellsalign: 'right', cellsrenderer: cellsrenderer, width: 100 },
                  { text: 'name', columntype: 'checkbox', datafield: 'name' }
                ],
                columngroups: [
                    { text: 'Product Details', align: 'center', name: 'ProductDetails' }
                ]
            });

});

// select multi
//     $("#data_grid").on('click', '#checkAll', function () {
    $("#checkAll").click(function () {
        $('.case').prop('checked', this.checked);
    });

    $(".case").click(function () {
        if ($(".case").length == $(".case:checked").length) {
            $("#checkAll").prop("checked", "checked");
        } else {
            $("#checkAll").removeAttr("checked");
        }
    });

//  Delete multi awards
    $('#btn_del_award').click(function () {
        var ids = [];
        $('.case:checked').each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) { //tell you if the array is empty
            alert("No awards were selected?");
        } else {
            if (!confirm("Are you sure you want to delete this?")) {
                return false;
            } else {
                $.ajax({
                    url: 'awards/delete_multi',
                    type: 'POST',
                    data: {id: ids},
                    dateType: 'json',
                    success: function (response) {
                        $('#data_grid').html(response['view']);
                        alert("Delete multi success!");
                    }
                });

                return true;
            }
        }
    });

// Dellete multi seasons
    $('#btn_del_season').click(function () {
        var ids = [];
        $('.case:checked').each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) { //tell you if the array is empty
            alert("No seasons were selected?");
        } else {
            if (!confirm("Are you sure you want to delete this?")) {
                return false;
            } else {
                $.ajax({
                    url: 'seasons/delete_multi',
                    type: 'POST',
                    data: {id: ids},
                    dateType: 'json',
                    success: function (response) {
                        $('#data_grid').html(response['view']);
                        alert("Delete multi success!");
                    }
                });

                return true;
            }
        }
    });

// Dellete multi leagues
    $('#btn_del_league').click(function () {
        var ids = [];
        $('.case:checked').each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) { //tell you if the array is empty
            alert("No leagues were selected?");
        } else {
            if (!confirm("Are you sure you want to delete this?")) {
                return false;
            } else {
                $.ajax({
                    url: 'leagues/delete_multi',
                    type: 'POST',
                    data: {id: ids},
                    dateType: 'json',
                    success: function (response) {
                        $('#data_grid').html(response['view']);
                        alert("Delete multi success!");
                    }
                });

                return true;
            }
        }
    });

// validate form Subject-Task
    $('#formDialog').formValidation({
        framework: 'bootstrap',
        excluded: ':disabled',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The name is required'
                    }
                }
            },
            description: {
                validators: {
                    notEmpty: {
                        message: 'The description is required'
                    }
                }
            },
            country_id: {
                validators: {
                    notEmpty: {
                        message: 'The country is required'
                    }
                }
            }
        }
    });
