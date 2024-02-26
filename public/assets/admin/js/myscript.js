(function($) {
    "use strict";

    $(document).ready(function() {
        $('.cookie-alert').fadeOut();

        function disablekey() {
            document.onkeydown = function(e) {
                return false;
            }
        }

        function enablekey() {
            document.onkeydown = function(e) {
                return true;
            }
        }



        // **************************************  AJAX REQUESTS SECTION *****************************************


        $(document).on('click', '.sendAbandonmentEmail', function(e){
            var link = $(this).attr('data-href');
            $('.submit-loader').show();
            $.ajax({
                type: 'GET',
                url: link,
                success: function(data) {
                    if ((data.errors)) {
                        for (var error in data.errors) {
                            $.notify(data.errors[error], "error");
                        }

                    } else {
                        $.notify(data, "success");
                    }
                    $('.submit-loader').hide();

                }
            });
        });

        // Status Start
        $(document).on('click', '.status', function() {
            var link = $(this).attr('data-href');
            $.get(link, function(data) {}).done(function(data) {
                if (link.indexOf("/currency/status/") != -1) {
                    $('#geniustableBase').DataTable().ajax.reload();
                };
                table.ajax.reload(null, false);
                $.notify(data, "success");
            })
        });

        $(document).on('click', '.base', function() {
            var link = $(this).attr('data-href');
            $.get(link, function(data) {}).done(function(data) {
                $('#geniustableBase').DataTable().ajax.reload();
            })
        });
        // Status Ends


        // Display Subcategories & attributes
        $(document).on('change', '#cat', function() {
            var link = $(this).find(':selected').attr('data-href');
            if (link != "") {
                $('#subcat').load(link);
                $('#subcat').prop('disabled', false);
            }
            $.get(getattrUrl + '?id=' + this.value + '&type=category', function(data) {
                console.log(data);
                let attrHtml = '';
                for (var i = 0; i < data.length; i++) {
                    attrHtml += '' +
                        '<div class="input-form">' +
                        '<div class="row">' +
                        '<div class="col-xl-12">' +
                        '<h3 class="heading">' + categoryAttr + '*</h3>';

                    attrHtml += `
                  <h4 class="heading" style="font-weight:bold;padding-top:1rem;">${data[i].attribute.name}</h4>
                </div>
                `;

                    for (var j = 0; j < data[i].options.length; j++) {
                        let priceClass = '';
                        if (data[i].attribute.price_status == 0) {
                            priceClass = 'd-none';
                        }
                        attrHtml += `
                <div class="col-xl-12">
                  <div class="option-row" style="display:flex;justify-content:start;align-items:center;">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" id="${data[i].attribute.input_name}${data[i].options[j].id}" name="attr_${data[i].attribute.input_name}[]" value="${data[i].options[j].id}" class="custom-control-input attr-checkbox">
                      <label class="custom-control-label" for="${data[i].attribute.input_name}${data[i].options[j].id}">${data[i].options[j].name}</label>
                    </div>
                    <div class="${priceClass}" style="display:flex;justify-content:center;align-items:center;min-width:220px;">
                      <span style="padding:0 1rem;" >+</span>
                      <div class="price-container">
                        <span class="price-curr">${curr.sign}</span>
                        <input type="number" step="0.01" class="input-field price-input" id="${data[i].attribute.input_name}${data[i].options[j].id}_price" data-name="attr_${data[i].attribute.input_name}_price[]" placeholder="0.00 (Preço Adicional)" value="">
                      </div>
                    </div>
                  </div>
                </div>
                `;
                    }

                    attrHtml += `
              </div>
            </div>
            `;
                }

                $("#catAttributes").html(attrHtml);
                $("#subcatAttributes").html('');
                $("#childcatAttributes").html('');
            });
        });
        // Display Subcategories Ends

        // Display Childcategories & Attributes
        $(document).on('change', '#subcat', function() {
            var link = $(this).find(':selected').attr('data-href');
            if (link != "") {
                $('#childcat').load(link);
                $('#childcat').prop('disabled', false);
            }

            $.get(getattrUrl + '?id=' + this.value + '&type=subcategory', function(data) {
                console.log(data);
                let attrHtml = '';
                for (var i = 0; i < data.length; i++) {
                    attrHtml += '' +
                        '<div class="input-form">' +
                        '<div class="row">' +
                        '<div class="col-xl-12">' +
                        '<h3 class="heading">' + categoryAttr + '*</h3>';

                    attrHtml += `
                  <h4 class="heading" style="font-weight:bold;padding-top:1rem;">${data[i].attribute.name}</h4>
                </div>
                `;

                    for (var j = 0; j < data[i].options.length; j++) {
                        let priceClass = '';
                        if (data[i].attribute.price_status == 0) {
                            priceClass = 'd-none';
                        }
                        attrHtml += `
                <div class="col-xl-12">
                  <div class="option-row" style="display:flex;justify-content:start;align-items:center;">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" id="${data[i].attribute.input_name}${data[i].options[j].id}" name="attr_${data[i].attribute.input_name}[]" value="${data[i].options[j].id}" class="custom-control-input attr-checkbox">
                      <label class="custom-control-label" for="${data[i].attribute.input_name}${data[i].options[j].id}">${data[i].options[j].name}</label>
                    </div>
                    <div class="${priceClass}" style="display:flex;justify-content:center;align-items:center;min-width:220px;">
                      <span style="padding:0 1rem;" >+</span>
                      <div class="price-container">
                        <span class="price-curr">${curr.sign}</span>
                        <input type="number" step="0.01" class="input-field price-input" id="${data[i].attribute.input_name}${data[i].options[j].id}_price" data-name="attr_${data[i].attribute.input_name}_price[]" placeholder="0.00 (Preço Adicional)" value="">
                      </div>
                    </div>
                  </div>
                </div>
                `;
                    }

                    attrHtml += `
              </div>
            </div>
            `;
                }

                $("#subcatAttributes").html(attrHtml);
                $("#childcatAttributes").html('');
            });
        });
        // Display Childcateogries & Attributes Ends


        // Display Attributes for Selected Childcategory Starts
        $(document).on('change', '#childcat', function() {

            $.get(getattrUrl + '?id=' + this.value + '&type=childcategory', function(data) {
                console.log(data);
                let attrHtml = '';
                for (var i = 0; i < data.length; i++) {
                    attrHtml += '' +
                        '<div class="input-form">' +
                        '<div class="row">' +
                        '<div class="col-xl-12">' +
                        '<h3 class="heading">' + categoryAttr + '*</h3>';

                    attrHtml += `
                  <h4 class="heading" style="font-weight:bold;padding-top:1rem;">${data[i].attribute.name}</h4>
                </div>
                `;

                    for (var j = 0; j < data[i].options.length; j++) {
                        let priceClass = '';
                        if (data[i].attribute.price_status == 0) {
                            priceClass = 'd-none';
                        }
                        attrHtml += `
                <div class="col-xl-12">
                  <div class="option-row" style="display:flex;justify-content:start;align-items:center;">
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" id="${data[i].attribute.input_name}${data[i].options[j].id}" name="attr_${data[i].attribute.input_name}[]" value="${data[i].options[j].id}" class="custom-control-input attr-checkbox">
                      <label class="custom-control-label" for="${data[i].attribute.input_name}${data[i].options[j].id}">${data[i].options[j].name}</label>
                    </div>
                    <div class="${priceClass}" style="display:flex;justify-content:center;align-items:center;min-width:220px;">
                      <span style="padding:0 1rem;" >+</span>
                      <div class="price-container">
                        <span class="price-curr">${curr.sign}</span>
                        <input type="number" step="0.01" class="input-field price-input" id="${data[i].attribute.input_name}${data[i].options[j].id}_price" data-name="attr_${data[i].attribute.input_name}_price[]" placeholder="0.00 (Preço Adicional)" value="">
                      </div>
                    </div>
                  </div>
                </div>
                `;
                    }

                    attrHtml += `
              </div>
            </div>
            `;
                }

                $("#childcatAttributes").html(attrHtml);
            });
        });
        // Display Attributes for Selected Childcategory Ends



        // Droplinks Start
        $(document).on('change', '.droplinks', function() {

            var link = $(this).val();
            var data = $(this).find(':selected').attr('data-val');
            if (data == 0) {
                $(this).next(".nice-select.process.select.droplinks").removeClass("drop-success").addClass("drop-danger");
            } else {
                $(this).next(".nice-select.process.select.droplinks").removeClass("drop-danger").addClass("drop-success");
            }
            $.get(link);
            $.notify("Status Atualizado.", "success");
        });


        $(document).on('change', '.vdroplinks', function() {

            var link = $(this).val();
            var data = $(this).find(':selected').attr('data-val');
            if (data == 0) {
                $(this).next(".nice-select.process.select1.vdroplinks").removeClass("drop-success").addClass("drop-danger");
            } else {
                $(this).next(".nice-select.process.select1.vdroplinks").removeClass("drop-danger").addClass("drop-success");
            }
            $.get(link);
            $.notify("Status Atualizado.", "success");
        });

        $(document).on('change', '.data-droplinks', function(e) {
            $('#confirm-delete1').modal('show');
            $('#confirm-delete1').find('.btn-ok').attr('href', $(this).val());
            table.ajax.reload(null, false);
            var data = $(this).children("option:selected").html();
            if (data == 'Pending') {
                $('#t-txt').addClass('d-none');
                $('#t-txt').val('');
            } else {
                $('#t-txt').removeClass('d-none');
            }
            $('#t-id').val($(this).data('id'));
            $('#t-title').val(data);
        });

        $(document).on('change', '.vendor-droplinks', function(e) {
            $('#confirm-delete1').modal('show');
            $('#confirm-delete1').find('.btn-ok').attr('href', $(this).val());
            table.ajax.reload(null, false);
        });

        $(document).on('change', '.order-droplinks', function(e) {
            $('#confirm-delete2').modal('show');
            $('#confirm-delete2').find('.btn-ok').attr('href', $(this).val());
        });


        // Droplinks Ends



        // ADD OPERATION

        $(document).on('click', '.remove-banner', function() {
            let type = $(this).attr('tipo');

            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: mainurl + '/admin/page-settings/unlink',
                data: {
                    type: type
                },
                success: function(data) {
                    if ((data.errors)) {
                        for (var error in data.errors) {
                            $.notify(data.errors[error], "error");
                        }

                    } else {
                        $.notify(data, "success");
                    }

                    $('#confirm-delete').modal('toggle');

                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }

                    window.location.reload();

                }
            });
        });

        $(document).on('click', '#add-data', function() {
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $('#modal1').find('.modal-title').html($(this).attr('data-header'));
            $('#modal1 .modal-content .modal-body').html('').load($(this).attr('data-href'), function(response, status, xhr) {
                if (status == "success") {
                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }
                }

            });
        });

        // ADD OPERATION END


        // Attribute Modal

        $(document).on('click', '.attribute', function() {
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $('#attribute').find('.modal-title').html($(this).attr('data-header'));
            $('#attribute .modal-content .modal-body').html('').load($(this).attr('data-href'), function(response, status, xhr) {
                if (status == "success") {
                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }
                }

            });
        });



        // Attribute Modal Ends

        // Gallery Modal
        $(document).on('click', '.set-gallery', function() {
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }

            $('#setgallery').find('.modal-title').html($(this).attr('data-header'));
            $('#setgallery .modal-content .modal-body').html('').load($(this).attr('data-href'), function(response, status, xhr) {
                if (status == "success") {
                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }
                }

            });
        });
        // Gallery Modal Ends


        // EDIT OPERATION

        $(document).on('click', '.edit', function() {
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $('#modal1').find('.modal-title').html($(this).attr('data-header'));
            $('#modal1 .modal-content .modal-body').html('').load($(this).attr('data-href'), function(response, status, xhr) {
                if (status == "success") {
                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }
                }
            });
        });


        // EDIT OPERATION END


        // FEATURE OPERATION

        $(document).on('click', '.feature', function() {
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $('#modal2').find('.modal-title').html($(this).attr('data-header'));
            $('#modal2 .modal-content .modal-body').html('').load($(this).attr('data-href'), function(response, status, xhr) {
                if (status == "success") {
                    $("#submit-button").html($("#highlightSubmitBtn"));
                    $('.submit-loader').hide();
                }
            });
        });

        $(document).on("click", "#highlightSubmitBtn", function() {
            $("#geniusformdata").submit();
        });


        // EDIT OPERATION END


        // SHOW OPERATION

        $(document).on('click', '.view', function() {
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $('#modal1').find('.modal-title').html($(this).attr('data-header'));
            $('#modal1 .modal-content .modal-body').html('').load($(this).attr('data-href'), function(response, status, xhr) {
                if (status == "success") {
                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }
                }

            });
        });


        // SHOW OPERATION END


        // TRACK OPERATION

        $(document).on('click', '.track', function() {
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $('#modal1').find('.modal-title').html($(this).attr('data-header'));
            $('#modal1 .modal-content .modal-body').html('').load($(this).attr('data-href'), function(response, status, xhr) {
                if (status == "success") {
                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }
                }

            });
        });


        // TRACK OPERATION END


        // DELIVERY OPERATION

        $(document).on('click', '.delivery', function() {
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $('#modal1').find('.modal-title').html($(this).attr('data-header'));
            $('#modal1 .modal-content .modal-body').html('').load($(this).attr('data-href'), function(response, status, xhr) {
                if (status == "success") {
                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }
                }

            });
        });


        // DELIVERY OPERATION END



        // ADD / EDIT FORM SUBMIT FOR DATA TABLE


        $(document).on('submit', '#geniusformdata', function(e) {
            e.preventDefault();
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $('button.addProductSubmit-btn').prop('disabled', true);
            disablekey();
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    if ((data.errors)) {
                        
                        for (var error in data.errors) {
                            $.notify(data.errors[error], "error");
                        }
                        if (admin_loader == 1) {
                            $('.submit-loader').hide();
                        }
                        $('button.addProductSubmit-btn').prop('disabled', false);
                        $('#geniusformdata input , #geniusformdata select , #geniusformdata textarea').eq(1).focus();
                    } else {
                        table.ajax.reload(null, false);
                        $.notify(data, "success");
                        if (admin_loader == 1) {
                            $('.submit-loader').hide();
                        }
                        $('button.addProductSubmit-btn').prop('disabled', false);
                        $('#modal1,#modal2,#verify-modal,#attribute,#fast_edit_modal,#bulk_edit_modal').modal('hide');
                    }
                    enablekey();
                }

            });

        });


        // ADD / EDIT FORM SUBMIT FOR DATA TABLE ENDS

        // CATALOG OPTION

        $('#catalog-modal').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });

        $('#catalog-modal .btn-ok').on('click', function(e) {

            if (admin_loader == 1) {
                $('.submit-loader').show();
            }

            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function(data) {
                    $('#catalog-modal').modal('toggle');
                    table.ajax.reload(null, false);
                    $.notify(data, "success");


                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }


                }
            });
            return false;
        });

        // CATALOG OPTION ENDS
        $('#confirm-copy').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('data-href', $(e.relatedTarget).data('href'));
        });

        $('.copy-ajax').on('click', function(){
            $('#confirm-copy').modal('hide');
            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $.ajax({
                type: "GET",
                url: $(this).attr('data-href'),
                success: function(data) {
                    table.ajax.reload(null, false);
                    $.notify(data, "success");
                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }
                }
            });
        });

        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });

        $('#confirm-delete .btn-ok').on('click', function(e) {

            if (admin_loader == 1) {
                $('.submit-loader').show();
            }
            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function(data) {
                    if ((data.errors)) {
                        for (var error in data.errors) {
                            $.notify(data.erros[error], "success");
                        }
                    } else {
                        $.notify(data, "success");
                    }

                    $('#confirm-delete').modal('toggle');
                    table.ajax.reload(null, false);

                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }


                }
            });
            return false;
        });

        $('#confirm-delete1 .btn-ok').on('click', function(e) {

            if (admin_loader == 1) {
                $('.submit-loader').show();
            }

            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function(data) {
                    $('#confirm-delete1').modal('toggle');
                    table.ajax.reload(null, false);
                    $.notify(data[0], "success");

                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }


                }
            });

            if ($('#t-txt').length > 0) {

                var tdata = $('#t-txt').val();

                if (tdata.length > 0) {

                    var id = $('#t-id').val();
                    var title = $('#t-title').val();
                    var text = $('#t-txt').val();
                    $.ajax({
                        url: $('#t-add').val(),
                        method: "GET",
                        data: { id: id, title: title, text: text }
                    });

                }

            }




            return false;
        });


        $('#confirm-delete2 .btn-ok').on('click', function(e) {

            if (admin_loader == 1) {
                $('.submit-loader').show();
            }

            $.ajax({
                type: "GET",
                url: $(this).attr('href'),
                success: function(data) {

                    if (admin_loader == 1) {
                        $('.submit-loader').hide();
                    }

                    $('#confirm-delete2').modal('toggle');
                    $.notify(data[0], "success");
                    $(".nice-select.process.select.order-droplinks").attr('class', 'nice-select process select order-droplinks ' + data[1]);
                }
            });

            return false;
        });

        // DELETE OPERATION END

    });



    // NORMAL FORM

    $(document).on('submit', '#geniusform', function(e) {
        e.preventDefault();
        if (admin_loader == 1) {
            $('.gocover').show();
        }

        var fd = new FormData(this);

        if ($('.attr-checkbox').length > 0) {
            $('.attr-checkbox').each(function() {

                // if checkbox checked then take the value of corresponsig price input (if price input exists)
                if ($(this).prop('checked') == true) {

                    if ($("#" + $(this).attr('id') + '_price').val().length > 0) {
                        // if price value is given
                        fd.append($("#" + $(this).attr('id') + '_price').data('name'), $("#" + $(this).attr('id') + '_price').val());
                    } else {
                        // if price value is not given then take 0
                        fd.append($("#" + $(this).attr('id') + '_price').data('name'), 0.00);
                    }

                    // $("#"+$(this).attr('id')+'_price').val(0.00);
                }
            });
        }

        var geniusform = $(this);
        $('button.addProductSubmit-btn').prop('disabled', true);
        $.ajax({
            method: "POST",
            url: $(this).prop('action'),
            data: fd,
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data['redirect']) {
                    window.location.href = data['redirect'];
                    return
                }

                if ((data.errors)) {
                    for (var error in data.errors) {
                        $.notify(data.errors[error], "error");
                    }
                } else {
                    $.notify(data, "success");
                    setTimeout(function(){

                        window.location.reload();
                    }, 1000);
                }
                if (admin_loader == 1) {
                    $('.gocover').hide();
                }
                $("html, body").stop().animate({scrollTop:0}, 500, 'swing');


                $('button.addProductSubmit-btn').prop('disabled', false);
            }

        });

    });
    // NORMAL FORM ENDS



    // NORMAL FORM

    $(document).on('submit', '#trackform', function(e) {
        e.preventDefault();
        if (admin_loader == 1) {
            $('.gocover').show();
        }

        $('button.addProductSubmit-btn').prop('disabled', true);
        $.ajax({
            method: "POST",
            url: $(this).prop('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if ((data.errors)) {
                    for (var error in data.errors) {
                        $.notify(data.errors[error], "error");
                    }
                } else {
                    $('#track-load').load($('#track-load').data('href'));
                    $.notify(data, "success");

                }
                if (admin_loader == 1) {
                    $('.gocover').hide();
                }

                $('button.addProductSubmit-btn').prop('disabled', false);
            }

        });

    });

    // NORMAL FORM ENDS

    // MESSAGE FORM

    $(document).on('submit', '#messageform', function(e) {
        e.preventDefault();
        var href = $(this).data('href');
        if (admin_loader == 1) {
            $('.gocover').show();
        }
        $('button.mybtn1').prop('disabled', true);
        $.ajax({
            method: "POST",
            url: $(this).prop('action'),
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if ((data.errors)) {
                    for (var error in data.errors) {
                        $.notify(data.erros[error], "error");
                    }
                    $('#messageform textarea').val('');
                } else {
                    $.notify(data, "success");
                    $('#messageform textarea').val('');
                    $('#messages').load(href);
                }
                if (admin_loader == 1) {
                    $('.gocover').hide();
                }
                $('button.mybtn1').prop('disabled', false);
            }
        });
    });

    // MESSAGE FORM ENDS


    // LOGIN FORM

    $("#loginform").on('submit', function(e) {
        $('.submit-loader').show();
        e.preventDefault();
        $('button.submit-btn').prop('disabled', true);
        $.ajax({
            method: "POST",
            url: $(this).prop('action'),
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if ((data.errors)) {
                    for (var error in data.errors) {
                        $.notify(data.errors[error], "error");
                    }
                } else {
                    $.notify("Sucesso! Redirecionando...", "success");
                    setTimeout(function(){
                        window.location = data;
                    }, 500);
                    
                }
                $('button.submit-btn').prop('disabled', false);
                $('.submit-loader').hide();
            }

        });

    });


    // LOGIN FORM ENDS


    // FORGOT FORM

    $("#forgotform").on('submit', function(e) {
        $('.submit-loader').show();
        e.preventDefault();
        $('button.submit-btn').prop('disabled', true);
        $.ajax({
            method: "POST",
            url: $(this).prop('action'),
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if ((data.errors)) {
                    for (var error in data.errors) {
                        $.notify(data.errors[error], "error");
                    }
                } else {
                    $('input[type=email]').val('');
                    $.notify(data, "success");
                }
                $('button.submit-btn').prop('disabled', false);
                $('.submit-loader').hide();
            }

        });

    });

    //MOSTRA NOTIFICAÇÃO APENAS QUANDO TIVER
    function showNotification(){
        $('.bell-area a > span').each(function() {

        var conteudoNot = $(this).html();

            if ( conteudoNot == '0' ){
                $(this).hide(1000);
            } else {
                $(this).show(1000);
            }

        });
    }


    // FORGOT FORM ENDS

    // NOTIFICATIONS

    $(document).ready(function() {

        showNotification();

        setInterval(function() {
            $.ajax({
                type: "GET",
                url: $("#notifications-count").data('href'),
                success: function(data) {
                    $("#notifications-count").html(data);
                    showNotification();
                }
            });
        }, 15000);

    });

    $(document).on('click', '#notifications', function() {
        $("#notifications-count").html(null);
        $('#notifications-show').load($("#notifications-show").data('href'));
    });

    $(document).on('click', '#notifications-clear', function(e) {
        e.preventDefault();
        $.get($('#notifications-clear').data('href'));
    });

    $(document).on('click', '.clear-notf', function(e) {
        e.preventDefault();
        $.get($(this).attr('href'));
    });

    // NOTIFICATION ENDS


    // SEND MESSAGE SECTION
    $(document).on('click', '.send', function() {
        $('.eml-val').val($(this).data('email'));
    });

    $(document).on("submit", "#emailreply1", function() {
        var token = $(this).find('input[name=_token]').val();
        var subject = $(this).find('input[name=subject]').val();
        var message = $(this).find('textarea[name=message]').val();
        var to = $(this).find('input[name=to]').val();
        $('#eml1').prop('disabled', true);
        $('#subj1').prop('disabled', true);
        $('#msg1').prop('disabled', true);
        $('#emlsub1').prop('disabled', true);
        $.ajax({
            type: 'post',
            url: mainurl + '/admin/user/send/message',
            data: {
                '_token': token,
                'subject': subject,
                'message': message,
                'to': to
            },
            success: function(data) {
                $('#eml1').prop('disabled', false);
                $('#subj1').prop('disabled', false);
                $('#msg1').prop('disabled', false);
                $('#subj1').val('');
                $('#msg1').val('');
                $('#emlsub1').prop('disabled', false);
                if (data == 0)
                    $.notify("Oops Algo deu Errado !!", "error");
                else
                    $.notify("Mensagem Enviada !!", "success");
                $('.close').click();
            }
        });
        return false;
    });

    // SEND MESSAGE SECTION ENDS



    // SEND EMAIL SECTION

    $(document).on("submit", "#emailreply", function() {
        var token = $(this).find('input[name=_token]').val();
        var subject = $(this).find('input[name=subject]').val();
        var message = $(this).find('textarea[name=message]').val();
        var to = $(this).find('input[name=to]').val();
        $('#eml').prop('disabled', true);
        $('#subj').prop('disabled', true);
        $('#msg').prop('disabled', true);
        $('#emlsub').prop('disabled', true);
        $.ajax({
            type: 'post',
            url: mainurl + '/admin/order/email',
            data: {
                '_token': token,
                'subject': subject,
                'message': message,
                'to': to
            },
            success: function(data) {
                $('#eml').prop('disabled', false);
                $('#subj').prop('disabled', false);
                $('#msg').prop('disabled', false);
                $('#subj').val('');
                $('#msg').val('');
                $('#emlsub').prop('disabled', false);
                if (data == 0)
                    $.notify("Oops Algo deu Errado !!", "error");
                else
                    $.notify("Email Enviado !!", "success");
                $('.close').click();
            }

        });
        return false;
    });
    // SEND EMAIL SECTION ENDS

    $(document).on('click', '.godropdown .go-dropdown-toggle', function() {
        var $this = $(this);
        $this.next('.action-list').toggle();
    });


    $(document).on('click', function(e) {
        var container = $(".godropdown");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.find('.action-list').hide();
        }
    });

    $("#generateProductThumbnails").on('click', function(e) {
        e.preventDefault();
        $('#generateProductThumbnails').prop('disabled', true);
        if (admin_loader == 1) {
            $('.submit-loader').show();
        }
        $.ajax({
            type: 'GET',
            url: $(this).attr('href'),
            success: function(data) {
                $('#generateProductThumbnails').prop('disabled', false);
                if (admin_loader == 1) {
                    $('.submit-loader').hide();

                }
                if (data.status) {
                    $.notify(data.message, 'success');
                } else {
                    $.notify(data.message, 'error');
                }
                if ((data.errors)) {
                    for (var error in data.errors) {
                        $.notify(data.errors[error], 'error');
                    }
                }
            }
        });
    });

    // **************************************  AJAX REQUESTS SECTION ENDS *****************************************


})(jQuery);