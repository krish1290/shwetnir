$(document).ready(function () {
    //For edit pos form
    if ($('form#sell_return_form').length > 0) {
        pos_form_obj = $('form#sell_return_form');
    } else {
        pos_form_obj = $('form#add_pos_sell_form');
    }
    if ($('form#sell_return_form').length > 0 || $('form#add_pos_sell_form').length > 0) {
        initialize_printer();
    }
    
    var sell_reference = $('#sell_reference').val();
    if(sell_reference){
        setTimeout(function () {
            $('#sell_reference').change();
        }, 700);
    }
    //Validate form
    $('form#create_sell_return_form').validate();
    //Validate form
    $(document).on('click', 'button#submit_create_sell_return_form', function (e) {
        e.preventDefault();
        //Check if reason of return.
        //alert($('#additional_note').val());


        //Check if product is present or not.
        if ($('table#sell_return_product_table tbody tr').length <= 0) {
            toastr.warning(LANG.no_products_added);
            $('input#search_product_for_sell_return').select();
            return false;
        }
        //Check if  reason of return is present or not.
        if (!$('#additional_note').val()) {
            toastr.warning('Please give the reason of return');
            return false;
        }

        if ($('form#create_sell_return_form').valid()) {
            $('form#create_sell_return_form').submit();
        }
    });

    //Date picker
    $('#transaction_date').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
        sideBySide: true
    });

    pos_form_validator = pos_form_obj.validate({
        submitHandler: function (form) {
            var cnf = true;

            if (cnf) {
                var data = $(form).serialize();
                var url = $(form).attr('action');
                $.ajax({
                    method: 'POST',
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function (result) {
                        if (result.success == 1 || 1) {
                            var reloadForm = $('.reloadForm').val();
                            if(reloadForm == 1){
                                window.location.reload();
                            }
                            toastr.success(result.msg);
                            //Check if enabled or not
                            if (result.receipt.is_enabled) {
                                pos_print(result.receipt);
                            }
                        } else {
                            toastr.error(result.msg);
                        }
                    },
                });
            }
            return false;
        },
    });
    //get customer
    $('select#customer_id').select2({
        ajax: {
            url: '/contacts/customers',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // search term
                    page: params.page,
                };
            },
            processResults: function (data) {
                return {
                    results: data,
                };
            },
        },
        templateResult: function (data) {
            var template = '';
            if (data.supplier_business_name) {
                template += data.supplier_business_name + "<br>";
            }
            template += data.text + "<br>" + LANG.mobile + ": " + data.mobile;

            if (typeof (data.total_rp) != "undefined") {
                var rp = data.total_rp ? data.total_rp : 0;
                template += "<br><i class='fa fa-gift text-success'></i> " + rp;
            }

            return template;
        },
        minimumInputLength: 1,
        language: {
            noResults: function () {
                var name = $('#customer_id')
                    .data('select2')
                    .dropdown.$search.val();
                return (
                    '<button type="button" data-name="' +
                    name +
                    '" class="btn btn-link add_new_customer"><i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>&nbsp; ' +
                    __translate('add_name_as_new_customer', { name: name }) +
                    '</button>'
                );
            },
        },
        escapeMarkup: function (markup) {
            return markup;
        },
    });

    //Add products
    if ($('#search_product_for_sell_return').length > 0) {
        //Add Product
        $('#search_product_for_sell_return')
            .autocomplete({
                source: function (request, response) {
                    $.getJSON(
                        '/products/list',
                        { location_id: $('#select_location_id').val(), term: request.term, },
                        response
                    );
                },
                minLength: 2,
                response: function (event, ui) {
                    if (ui.content.length == 1) {
                        ui.item = ui.content[0];
                        if (ui.item.qty_available > 0 && ui.item.enable_stock == 1) {
                            $(this)
                                .data('ui-autocomplete')
                                ._trigger('select', 'autocompleteselect', ui);
                            $(this).autocomplete('close');
                        }
                    } else if (ui.content.length == 0) {
                        swal(LANG.no_products_found);
                    }
                },
                focus: function (event, ui) {
                    if (ui.item.qty_available <= 0) {
                        return false;
                    }
                },
                select: function (event, ui) {
                    if (ui.item.qty_available > 0) {
                        $(this).val(null);
                        sell_return_product_row(ui.item.variation_id);
                    } else {
                        alert(LANG.out_of_stock);
                    }
                },
            })
            .autocomplete('instance')._renderItem = function (ul, item) {
                if (item.qty_available <= 0) {
                    var string = '<li class="ui-state-disabled">' + item.name;
                    if (item.type == 'variable') {
                        string += '-' + item.variation;
                    }
                    string += ' (' + item.sub_sku + ') (Out of stock) </li>';
                    return $(string).appendTo(ul);
                } else if (item.enable_stock != 1) {
                    return ul;
                } else {
                    var string = '<div>' + item.name;
                    if (item.type == 'variable') {
                        string += '-' + item.variation;
                    }
                    string += ' (' + item.sub_sku + ') </div>';
                    return $('<li>')
                        .append(string)
                        .appendTo(ul);
                }
            };
    }
    //Get customer purchase
    $('#customer_id').change(function () {

        let supplier_id = $(this).val();

        let location_id = $('#select_location_id').val();
        $.ajax({
            method: 'POST',
            url: '/get_customer_sell',
            data: { customer_id: supplier_id, location_id: location_id },
            dataType: 'json',
            success: function (result) {
                $('#sell_reference').html('<option value="">Please Select</option>');
                $.each(result.sells, function (key, value) {
                    $("#sell_reference").append('<option value="' + key + '">' + value + '</option>');
                });
            },
        });
    });
    // Get product against purchase ref
    $('#sell_reference').change(function () {
        let transaction_id = $(this).val();
        if ($(this).val()) {
            $('#search_product_for_sell_return').attr('disabled', 'disabled');
        } else {
            $('#search_product_for_sell_return').removeAttr('disabled');
        }
        $.ajax({
            method: 'POST',
            url: '/get_selled_product',
            data: { transaction_id: transaction_id },
            dataType: 'json',
            success: function (result) {
                $('table#sell_return_product_table tbody').html('');
                var i = 0;
                $.each(result, function (key, value) {
                    sell_return_product_row(key, value, i);
                    i++;
                });
            },
        });
    });

    //Enable Product search
    $('select#select_location_id').change(function () {
        if ($(this).val()) {
            $('#location_id').val($(this).val());
            $('#search_product_for_sell_return').removeAttr('disabled');
        } else {
            $('#search_product_for_sell_return').attr('disabled', 'disabled');
        }
        $('table#stock_adjustment_product_table tbody').html('');
        $('#product_row_index').val(0);
    });
    //Update product quantity
    $(document).on('change', 'input.product_quantity', function () {
        update_table_row($(this).closest('tr'));
    });
    //Update product unit price
    $(document).on('change', 'input.product_unit_price', function () {
        update_table_row($(this).closest('tr'));
    });
    //Remove product row
    $(document).on('click', '.remove_product_row', function () {
        swal({
            title: LANG.sure,
            icon: 'warning',
            buttons: true,
            dangerMode: true,
        }).then(willDelete => {
            if (willDelete) {
                $(this)
                    .closest('tr')
                    .remove();
                update_table_total();
            }
        });
    });

});

function initialize_printer() {
    if ($('input#location_id').data('receipt_printer_type') == 'printer') {
        initializeSocket();
    }
}

function pos_print(receipt) {
    //If printer type then connect with websocket
    if (receipt.print_type == 'printer') {
        var content = receipt;
        content.type = 'print-receipt';

        //Check if ready or not, then print.
        if (socket.readyState != 1) {
            initializeSocket();
            setTimeout(function () {
                socket.send(JSON.stringify(content));
            }, 700);
        } else {
            socket.send(JSON.stringify(content));
        }
    } else if (receipt.html_content != '') {
        var title = document.title;
        if (typeof receipt.print_title != 'undefined') {
            document.title = receipt.print_title;
        }

        //If printer type browser then print content
        $('#receipt_section').html(receipt.html_content);
        __currency_convert_recursively($('#receipt_section'));
        setTimeout(function () {
            window.print();
            document.title = title;
        }, 1000);
    }
}


function sell_return_product_row(variation_id, transaction_id = 0, row_number = 0) {
    var row_index = parseInt($('#product_row_index').val());
    var location_id = $('#select_location_id').val();
    $.ajax({
        method: 'POST',
        url: '/sell-return/get_product_row',
        data: { row_index: row_number, variation_id: variation_id, location_id: location_id, transaction_id: transaction_id, sell_return: true },
        dataType: 'html',
        success: function (result) {
            $('table#sell_return_product_table tbody').append(result);

            $('table#sell_return_product_table tbody tr:last').find('.expiry_datepicker').datepicker({
                autoclose: true,
                format: datepicker_date_format,
            });

            update_table_total();
            $('#product_row_index').val(row_index + 1);
        },
    });
}

function update_table_total() {
    var table_total = 0;
    $('table#sell_return_product_table tbody tr').each(function () {
        var this_total = parseFloat(__read_number($(this).find('input.product_line_total')));
        if (this_total) {
            table_total += this_total;
        }
    });
    var tax_rate = parseFloat($('option:selected', $('#tax_id')).data('tax_amount'));
    var tax = __calculate_amount('percentage', tax_rate, table_total);
    __write_number($('input#tax_amount'), tax);
    var final_total = table_total + tax;
    $('input#total_amount').val(final_total);
    $('span#total_return').text(__number_f(final_total));
}

function update_table_row(tr) {
    var quantity = parseFloat(__read_number(tr.find('input.product_quantity')));
    var unit_price = parseFloat(__read_number(tr.find('input.product_unit_price')));
    var row_total = 0;
    if (quantity && unit_price) {
        row_total = quantity * unit_price;
    }
    tr.find('input.product_line_total').val(__number_f(row_total));
    update_table_total();
}

function get_stock_adjustment_details(rowData) {
    var div = $('<div/>')
        .addClass('loading')
        .text('Loading...');
    $.ajax({
        url: '/wastage-management/' + rowData.DT_RowId,
        dataType: 'html',
        success: function (data) {
            div.html(data).removeClass('loading');
        },
    });

    return div;
}

// //Set the location and initialize printer
// function set_location(){
// 	if($('input#location_id').length == 1){
// 	       $('input#location_id').val($('select#select_location_id').val());
// 	       //$('input#location_id').data('receipt_printer_type', $('select#select_location_id').find(':selected').data('receipt_printer_ty
// 	}

// 	if($('input#location_id').val()){
// 	       $('input#search_product').prop( "disabled", false ).focus();
// 	} else {
// 	       $('input#search_product').prop( "disabled", true );
// 	}

// 	initialize_printer();
// }
