$(function ($) {
    "use strict";



    $(document).ready(function () {
        $('.cookie-alert').hide();
        setTimeout(function () {
            $('.cookie-alert').fadeIn("slow");
        }, 2000);
        $('.cookie-alert i.fa-times').click(function () {
            $('.cookie-alert').fadeOut();
        });
        $('.cookie-alert .btn-accept-cookies').click(function () {
            $('.cookie-alert').fadeOut();
            $.ajax({
                method: "GET",
                url: $('.cookie-alert a.accept-cookies-link').attr('data-href')
            });
        });

        //HELPFULL LINKS COUNT HIDE

        if ($('.cart-quantity').html() == 0) {
            $('.cart-quantity').hide();
        }
        if ($('#wishlist-count').html() == 0) {
            $('#wishlist-count').hide();
        }
        if ($('#compare-count').html() == 0) {
            $('#compare-count').hide();
        }


        //**************************** SHARED JS SECTION ****************************************

        // LOADER
        if (loader == 1) {
            $(window).on("load", function (e) {
                setTimeout(function () {
                    $('#preloader').fadeOut(500);
                }, 100)
            });
        }

        // LOADER ENDS

        //  Alert Close
        $("button.alert-close").on('click', function () {
            $(this).parent().hide();
        });


        //More Categories
        $('.rx-parent').on('click', function () {
            $('.rx-child').toggle();
            $(this).toggleClass('rx-change');
        });



        //  FORM SUBMIT SECTION

        $(document).on('submit', '#contactform', function (e) {
            e.preventDefault();
            $('.gocover').show();
            $('button.submit-btn').prop('disabled', true);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {
                        $('.alert-success').hide();
                        $('.alert-danger').show();
                        $('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>')
                        }
                        $('#contactform input[type=text], #contactform input[type=email], #contactform textarea').eq(0).focus();
                        $('#contactform .refresh_code').trigger('click');

                    } else {
                        $('.alert-danger').hide();
                        $('.alert-success').show();
                        $('.alert-success p').html(data);
                        $('#contactform')[0].reset();
                        $('#contactform .refresh_code').trigger('click');
                        if (typeof fbq != 'undefined') {
                            fbq('track', 'Contact');
                        }
                    }
                    $('.gocover').hide();
                    $('button.submit-btn').prop('disabled', false);
                }

            });

        });
        //  FORM SUBMIT SECTION ENDS


        //  SUBSCRIBE FORM SUBMIT SECTION

        $(document).on('submit', '#subscribeform', function (e) {
            e.preventDefault();
            $('#sub-btn').prop('disabled', true);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {
                        toastr.error(data['error']);
                    } else {
                        toastr.success(data['success']);
                        $('.preload-close').click()
                    }

                    $('#sub-btn').prop('disabled', false);


                }

            });

        });

        //  SUBSCRIBE FORM SUBMIT SECTION ENDS


        // LOGIN FORM
        $("#loginform").on('submit', function (e) {
            var $this = $(this).parent();
            e.preventDefault();
            $this.find('button.submit-btn').prop('disabled', true);
            $this.find('.alert-info').show();
            $this.find('.alert-info p').html($('#authdata').val());
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {
                        $this.find('.alert-success').hide();
                        $this.find('.alert-info').hide();
                        $this.find('.alert-danger').show();
                        $this.find('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $this.find('.alert-danger p').html(data.errors[error]);
                        }
                    } else {
                        $this.find('.alert-info').hide();
                        $this.find('.alert-danger').hide();
                        $this.find('.alert-success').show();
                        $this.find('.alert-success p').html('Sucesso !');
                        if (data == 1) {
                            location.reload();
                        } else {
                            window.location = data;
                        }

                    }
                    $this.find('button.submit-btn').prop('disabled', false);
                }

            });

        });
        // LOGIN FORM ENDS


        // MODAL LOGIN FORM
        $(".mloginform").on('submit', function (e) {
            var $this = $(this).parent();
            e.preventDefault();
            $this.find('button.submit-btn').prop('disabled', true);
            $this.find('.alert-info').show();
            var authdata = $this.find('.mauthdata').val();
            $('.signin-form .alert-info p').html(authdata);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {
                        $this.find('.alert-success').hide();
                        $this.find('.alert-info').hide();
                        $this.find('.alert-danger').show();
                        $this.find('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $('.signin-form .alert-danger p').html(data.errors[error]);
                        }
                    } else {
                        $this.find('.alert-info').hide();
                        $this.find('.alert-danger').hide();
                        $this.find('.alert-success').show();
                        $this.find('.alert-success p').html('Sucesso !');
                        if (data == 1) {
                            location.reload();
                        } else {
                            window.location = data;
                        }

                    }
                    $this.find('button.submit-btn').prop('disabled', false);
                }

            });

        });
        // MODAL LOGIN FORM ENDS

        // REGISTER FORM
        $("#registerform").on('submit', function (e) {
            var $this = $(this).parent();
            e.preventDefault();
            $this.find('button.submit-btn').prop('disabled', true);
            $this.find('.alert-info').show();
            $this.find('.alert-info p').html($('#processdata').val());
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {

                    if (data == 1) {
                        // window.location = mainurl + '/user/dashboard';
                        $('#comment-log-reg1').modal('hide');
                        location.reload();
                    } else {

                        if ((data.errors)) {
                            $this.find('.alert-success').hide();
                            $this.find('.alert-info').hide();
                            $this.find('.alert-danger').show();
                            $this.find('.alert-danger ul').html('');
                            for (var error in data.errors) {
                                $this.find('.alert-danger p').html(data.errors[error]);
                            }
                            $this.find('button.submit-btn').prop('disabled', false);
                        } else {
                            $this.find('.alert-info').hide();
                            $this.find('.alert-danger').hide();
                            $this.find('.alert-success').show();
                            $this.find('.alert-success p').html(data);
                            $this.find('button.submit-btn').prop('disabled', false);
                        }

                    }
                    $('.refresh_code').click();

                }

            });

        });
        // REGISTER FORM ENDS


        // MODAL REGISTER FORM
        $(".mregisterform").on('submit', function (e) {
            e.preventDefault();
            var $this = $(this).parent();
            $this.find('button.submit-btn').prop('disabled', true);
            $this.find('.alert-info').show();
            var processdata = $this.find('.mprocessdata').val();
            $this.find('.alert-info p').html(processdata);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data == 1) {
                        if (typeof fbq != 'undefined') {
                            fbq('track', 'CompleteRegistration');
                        }
                        setTimeout(function () {
                            // window.location = mainurl + '/user/dashboard';
                            $('#comment-log-reg1').modal('hide');
                            location.reload();
                        }, 500);
                    } else {

                        if ((data.errors)) {
                            $this.find('.alert-success').hide();
                            $this.find('.alert-info').hide();
                            $this.find('.alert-danger').show();
                            $this.find('.alert-danger ul').html('');
                            for (var error in data.errors) {
                                $this.find('.alert-danger p').html(data.errors[error]);
                            }
                            $this.find('button.submit-btn').prop('disabled', false);
                        } else {
                            $this.find('.alert-info').hide();
                            $this.find('.alert-danger').hide();
                            $this.find('.alert-success').show();
                            $this.find('.alert-success p').html(data);
                            $this.find('button.submit-btn').prop('disabled', false);
                        }
                    }

                    $('.refresh_code').click();

                }
            });

        });
        // MODAL REGISTER FORM ENDS

        // MODAL REGISTER FORM
        $(".vendor-registerform").on('submit', function (e) {
            e.preventDefault();
            var $this = $(this).parent();
            $this.find('button.submit-btn').prop('disabled', true);
            $this.find('.alert-info').show();
            var processdata = $this.find('.mprocessdata').val();
            $this.find('.alert-info p').html(processdata);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data == 1) {
                        if (typeof fbq != 'undefined') {
                            fbq('track', 'CompleteRegistration');
                        }
                        setTimeout(function () {
                            window.location = mainurl + '/user/package';
                        }, 500);
                    } else {

                        if ((data.errors)) {
                            $this.find('.alert-success').hide();
                            $this.find('.alert-info').hide();
                            $this.find('.alert-danger').show();
                            $this.find('.alert-danger ul').html('');
                            for (var error in data.errors) {
                                $this.find('.alert-danger p').html(data.errors[error]);
                            }
                            $this.find('button.submit-btn').prop('disabled', false);
                        } else {
                            $this.find('.alert-info').hide();
                            $this.find('.alert-danger').hide();
                            $this.find('.alert-success').show();
                            $this.find('.alert-success p').html(data);
                            $this.find('button.submit-btn').prop('disabled', false);
                        }
                    }

                    $('.refresh_code').click();

                }
            });

        });
        // MODAL REGISTER FORM ENDS


        // FORGOT FORM

        $("#forgotform").on('submit', function (e) {
            e.preventDefault();
            var $this = $(this).parent();
            $this.find('button.submit-btn').prop('disabled', true);
            $this.find('.alert-info').show();
            $this.find('.alert-info p').html($('.authdata').val());
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {
                        $this.find('.alert-success').hide();
                        $this.find('.alert-info').hide();
                        $this.find('.alert-danger').show();
                        $this.find('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $this.find('.alert-danger p').html(data.errors[error]);
                        }
                    } else {
                        $this.find('.alert-info').hide();
                        $this.find('.alert-danger').hide();
                        $this.find('.alert-success').show();
                        $this.find('.alert-success p').html(data);
                        $this.find('input[type=email]').val('');
                    }
                    $this.find('button.submit-btn').prop('disabled', false);
                }

            });

        });




        $("#mforgotform").on('submit', function (e) {
            e.preventDefault();
            var $this = $(this).parent();
            $this.find('button.submit-btn').prop('disabled', true);
            $this.find('.alert-info').show();
            $this.find('.alert-info p').html($('.fauthdata').val());
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {
                        $this.find('.alert-success').hide();
                        $this.find('.alert-info').hide();
                        $this.find('.alert-danger').show();
                        $this.find('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $this.find('.alert-danger p').html(data.errors[error]);
                        }
                    } else {
                        $this.find('.alert-info').hide();
                        $this.find('.alert-danger').hide();
                        $this.find('.alert-success').show();
                        $this.find('.alert-success p').html(data);
                        $this.find('input[type=email]').val('');
                    }
                    $this.find('button.submit-btn').prop('disabled', false);
                }

            });

        });

        // FORGOT FORM ENDS

        // REPORT FORM


        $("#reportform").on('submit', function (e) {
            e.preventDefault();
            $('.gocover').show();
            var $reportform = $(this);
            $reportform.find('button.submit-btn').prop('disabled', true);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {

                        for (var error in data.errors) {
                            $reportform.find('.alert-danger').show();
                            $reportform.find('.alert-danger p').html(data.errors[error]);
                        }
                    } else {

                        $reportform.find('input[type=text],textarea').val('');

                        $('#report-modal').modal('hide');
                        toastr.success('Relato Enviado com Sucesso.');

                    }

                    $('.gocover').hide();
                    $reportform.find('button.submit-btn').prop('disabled', false);

                }

            });

        });


        // REPORT FORM ENDS



        //  USER FORM SUBMIT SECTION

        $(document).on('submit', '#userform', function (e) {
            e.preventDefault();
            $('.gocover').show();
            $('button.submit-btn').prop('disabled', true);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {
                        $('.alert-success').hide();
                        $('.alert-danger').show();
                        $('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>')
                        }
                        $('#userform input[type=text], #userform input[type=email], #userform textarea').eq(0).focus();
                    } else {
                        $('.alert-danger').hide();
                        $('.alert-success').show();
                        $('.alert-success p').html(data);
                        $('#userform input[type=text], #userform input[type=email], #userform textarea').eq(0).focus();
                    }
                    $('.gocover').hide();
                    $('button.submit-btn').prop('disabled', false);
                }

            });

        });

        // USER FORM SUBMIT SECTION ENDS

        // Pagination Starts

        // $(document).on('click', '.pagination li', function (event) {
        //   event.preventDefault();
        //   if ($(this).find('a').attr('href') != '#') {
        //     $('#preloader').show();
        //     $('#ajaxContent').load($(this).find('a').attr('href'), function (response, status, xhr) {
        //       if (status == "success") {
        //         $('#preloader').hide();
        //         $("html,body").animate({
        //           scrollTop: 0
        //         }, 1);
        //       }
        //     });
        //   }
        // });

        // Pagination Ends

        // IMAGE UPLOADING :)

        $(".upload").on("change", function () {
            var imgpath = $(this).parent().parent().prev().find('img');
            var file = $(this);
            readURL(this, imgpath);
        });

        function readURL(input, imgpath) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    imgpath.attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        // IMAGE UPLOADING ENDS :)

        // MODAL SHOW

        $("#show-forgot").on('click', function () {
            $("#comment-log-reg").modal("hide");
            $("#forgot-modal").modal("show");
        });

        $("#show-forgot1").on('click', function () {
            $("#forgot-modal").modal("show");
        });

        $("#show-login").on('click', function () {
            $("#forgot-modal").modal("hide");
            $("#comment-log-reg").modal("show");
        });

        // MODAL SHOW ENDS

        // Catalog Search Options

        // $('.check-cat').on('change',function(){
        //   var len = $('input.check-cat').filter(':checked').length;
        //   if(len == 0){
        //     $("#catalogform").attr('action','');
        //     $('.check-cat').removeAttr("name");
        //   }
        //   else{
        //     var search = $("#searchform").val();
        //     $("#catalogform").attr('action',search);
        //     $('.check-cat').attr('name','cat_id[]');
        //   }
        //
        // });

        $('#category_select').on('change', function () {
            var val = $(this).val();
            $('#category_id').val(val);
            $('#searchForm').attr('action', mainurl + '/category/' + $(this).val());
        });

        // Catalog Search Options Ends


        // Auto Complete Section
        $('#prod_name').on('keyup', function () {
            var search = encodeURIComponent($(this).val());
            if (search == "") {
                $(".autocomplete").hide();
            } else {
                $("#myInputautocomplete-list").load(mainurl + '/autosearch/product/' + search);
                $(".autocomplete").show();
            }
        });
        // Auto Complete Section Ends

        // Quick View Section

        $(document).on('click', '.quick-view', function () {
            var $this = $("#quickview");
            $this.find('.modal-header').hide();
            $this.find('.modal-body').hide();
            $this.find('.modal-content').css('border', 'none');
            $('.submit-loader').show();
            $(".quick-view-modal").load($(this).data('href'), function (response, status, xhr) {
                if (status == "success")
                    $('.quick-zoom').on('load', function () {
                        $('.submit-loader').hide();
                        $this.find('.modal-header').show();
                        $this.find('.modal-body').show();
                        $this.find('.modal-content').css('border', '1px solid #00000033');
                        $('.quick-all-slider').owlCarousel({
                            loop: true,
                            dots: false,
                            nav: true,
                            navText: ["<i class='fa fa-angle-left'></i>", "<i class='fa fa-angle-right'></i>"],
                            margin: 0,
                            autoplay: false,
                            items: 4,
                            autoplayTimeout: 6000,
                            smartSpeed: 1000,
                            responsive: {
                                0: {
                                    items: 4
                                },
                                768: {
                                    items: 4
                                }
                            }
                        });
                    });
            });

            return false;

        });
        // Quick View Section Ends

        // Currency and Language Section

        $(".selectors").on('change', function () {
            var url = $(this).val();
            window.location = url;
        });

        // Currency and Language Section Ends

        // Wedding Section

        $(document).on('click', '.add-to-wedding', function () {
            $.get($(this).data('href'), function (data) {
                if (typeof data['success'] !== 'undefined') {
                    toastr.success(data['success']);
                    if (typeof fbq != 'undefined') {
                        fbq('track', 'AddToWedding');
                    }
                } else {
                    toastr.error(data['error']);
                }
            });

            return false;
        });

        // Wedding Section End

        // Wishlist Section

        $(document).on('click', '.add-to-wish', function () {
            $('#add-to-wish').attr("data-href", $(this).data('href'));
            $('#addToWishlist').modal();

            return false;
        });

        $(document).on('click', '#add-to-wish', function () {
            $('#addToWishlist').modal('hide');
            $.get($(this).data('href') + '/' + $('#group').val(), function (data) {

                if (data[0] == 1) {
                    toastr.success(data['success']);
                    $('#wishlist-count').html(data[1]);
                    $('#wishlist-count').show();
                    if (typeof fbq != 'undefined') {
                        fbq('track', 'AddToWishlist');
                    }


                } else {

                    toastr.error(data['error']);
                }

            });

            return false;
        });

        $(document).on('click', '#wish-btn', function () {
            return false;

        });


        $(document).on('click', '.wishlist-remove', function () {
            $(this).parent().parent().parent().parent().remove();

            $.get($(this).data('href'), function (data) {
                if (data[1] == 0) {
                    $('#wishlist-count').hide();
                } else {
                    $('#wishlist-count').html(data[1]);
                    toastr.success(data['success']);
                }


            });
        });

        // Wishlist Section Ends




        // Compare Section

        $(document).on('click', '.add-to-compare', function () {
            $.get($(this).data('href'), function (data) {
                $("#compare-count").html(data[1]);
                $("#compare-count").show();
                if (data[0] == 0) {
                    toastr.success(data['success']);
                } else {
                    toastr.error(data['error']);
                }

            });
            return false;
        });


        $(document).on('click', '.compare-remove', function () {
            var class_name = $(this).attr('data-class');
            $.get($(this).data('href'), function (data) {
                if (data[1] != 0) {
                    $("#compare-count").html(data[1]);
                } else {
                    $("#compare-count").hide();
                }
                if (data[0] == 0) {
                    $('.' + class_name).remove();
                    toastr.success(data['success']);
                } else {
                    $('h2.title').html(data['title']);
                    $('.compare-page-content-wrap').remove();
                    $('.' + class_name).remove();
                    toastr.success(data['success']);
                }


            });
        });

        // Compare Section Ends



        // Cart Section




        $(document).on('click', '.add-to-cart', function () {

            $.get($(this).data('href'), function (data) {

                if (data.digital) {
                    toastr.error(data['digital']);
                } else if (data.out_stock) {
                    toastr.error(data['out_stock']);
                } else {
                    $("#cart-count").html(data[0]);
                    $('.cart-quantity').show();
                    $("#cart-items").load(mainurl + '/carts/view');
                    toastr.success(data['success']);
                    if (typeof fbq != 'undefined') {
                        fbq('track', 'AddToCart', {
                            content_name: data['pixel_name'],
                            content_category: data['pixel_category'],
                            content_ids: data['pixel_id'],
                            content_type: data['pixel_type'],
                            value: data['pixel_price'],
                            currency: data['pixel_currency']
                        });
                    }
                }
            });

            return false;
        });



        $(document).on('click', '.cart-remove', function () {
            var selector = $(this).data('class');
            document.querySelectorAll('.' + selector).forEach(function (prod) {
                prod.style.display = 'none';
            });

            $.get($(this).data('href'), function (data) {
                if (data == 0) {
                    $("#cart-count").html(data);
                    $('.cart-table').html('<h3 class="mt-1 pl-3 text-left">Carrinho Vazio.</h3>');
                    $('#cart-items').html('<p class="mt-1 pl-3 text-left">Carrinho Vazio.</p>');
                    $('.cartpage .col-lg-4').html('');
                    $('.cart-quantity').hide();

                } else {
                    $('.cart-quantity').html(data[1]);
                    $('.cart-total').html(data[0]);
                    $('.coupon-total').val(data[0]);
                    $('.main-total').html(data[3]);
                    $('.main-total2').html(data[4]);

                }

            });
        });



        // Adding Muliple Quantity Starts

        var sizes = $('.product-size .siz-list li.active').find('.size').val();
        var size_qty = $('.product-size .siz-list li.active').find('size_qty').val();
        var size_price = $('.product-size .siz-list li.active').find('.size_price').val();
        var size_key = $('.product-size .siz-list li.active').find('.size_key').val();
        var colors = $('.product-color .color-list li.active').find('.color').val();
        var color_price = $('.product-color .color-list li.active').find('.color_price').val();
        var color_qty = $('.product-color .color-list li.active').find('.color_qty').val();
        var color_key = $('.product-color .color-list li.active').find('.color_key').val();
        var materials = $('#material-option').attr('data-material-name');
        var material_price = $('#material-option').attr('data-material-price');
        var material_qty = $('#material-option').attr('data-material-qty');
        var material_key = $('#material-option').attr('data-material-key');
        $("#material_product").val(materials);
        $("#material_qty_product").val(material_qty);
        if (material_price > 1) {
            $("#stock").val(material_qty);
        }
        $("#material_price_product").val(material_price);
        $("#material_key_product").val(material_key);
        var total = "";
        var stock = $("#stock").val();
        var keys = "";
        var values = "";
        var prices = "";

        if (sizes == null) {
            sizes = "";
        }

        if (colors == null) {
            colors = "";
        }
        if (materials == null) {
            materials = "";
        }
        if (sizes == null) {
            sizes = "";
        }
        if (size_price == null) {
            size_price = "";
        }
        if (size_qty == null) {
            size_qty = "";
        }
        if (size_key == null) {
            size_key = "";
        }
        if (color_price == null) {
            color_price = "";
        }
        if (color_qty == null) {
            color_qty = "";
        }
        if (color_key == null) {
            color_key = "";
        }
        if (material_price == null) {
            material_price = "";
        }
        if (material_qty == null) {
            material_qty = "";
        }
        if (material_key == null) {
            material_key = "";
        }

        // Product Details Product Size Active Js Code
        $(document).on('click', '.product-size .siz-list .box', function () {
            $('.qttotal').html('1');
            var parent = $(this).parent();
            size_price = $(this).find('.size_price').val();
            size_qty = $(this).find('.size_qty').val();
            size_key = $(this).find('.size_key').val();
            sizes = $(this).find('.size').val();
            $('.product-size .siz-list li').removeClass('active');
            parent.addClass('active');
            total = getAmount() + parseFloat(size_price);
            var previous_price = parseFloat($('#previous_price_value').val());
            if (total >= previous_price) {
                $('#previousprice').hide();
            } else {
                $('#previousprice').show();
            }
            total = total.toFixed(2);
            stock = size_qty;
            var value = $("#curr_value").val();
            value = total / value;
            var dec_sep = $("#dec_sep").val();
            var tho_sep = $("#tho_sep").val();
            var dec_dig = $("#dec_dig").val();
            var dec_sep2 = $("#dec_sep2").val();
            var tho_sep2 = $("#tho_sep2").val();
            var dec_dig2 = $("#dec_dig2").val();
            value = $.number(value, dec_dig2, dec_sep2, tho_sep2);
            total = $.number(total, dec_dig, dec_sep, tho_sep);
            var first_sign = $("#first_sign").val();
            var pos = $('#curr_pos').val();
            var sign = $('#curr_sign').val();

            if (pos == '0') {
                $('#sizeprice').html(sign + total);
                $("#originalprice").html(first_sign + value);
            } else {
                $('#sizeprice').html(total + sign);
                $("#originalprice").html(value + first_sign);
            }

        });

        // Product Details Attribute Code

        $(document).on('change', '.product-attr', function () {
            var total = 0;
            total = getAmount() + getSizePrice() + getColorPrice();
            var previous_price = parseFloat($('#previous_price_value').val());
            if (total >= previous_price) {
                $('#previousprice').hide();
            } else {
                $('#previousprice').show();
            }
            total = total.toFixed(2);
            var value = $("#curr_value").val();
            value = total / value;
            var dec_sep = $("#dec_sep").val();
            var tho_sep = $("#tho_sep").val();
            var dec_dig = $("#dec_dig").val();
            var dec_sep2 = $("#dec_sep2").val();
            var tho_sep2 = $("#tho_sep2").val();
            var dec_dig2 = $("#dec_dig2").val();
            value = $.number(value, dec_dig2, dec_sep2, tho_sep2);
            total = $.number(total, dec_dig, dec_sep, tho_sep);
            var first_sign = $("#first_sign").val();
            var pos = $('#curr_pos').val();
            var sign = $('#curr_sign').val();
            if (pos == '0') {
                $('#sizeprice').html(sign + total);
                $("#originalprice").html(first_sign + value);
            } else {
                $('#sizeprice').html(total + sign);
                $("#originalprice").html(value + first_sign);
            }
        });

        $(document).on('change', '#select-materials', function () {
            var total = 0;
            var material_price = $(this).find("option:selected").attr('data-material-price');
            material_price = parseInt(material_price);
            total = getAmount() + material_price;
            var previous_price = parseFloat($('#previous_price_value').val());
            if (total >= previous_price) {
                $('#previousprice').hide();
            } else {
                $('#previousprice').show();
            }
            var value = $("#curr_value").val();
            value = total / value;
            var dec_sep = $("#dec_sep").val();
            var tho_sep = $("#tho_sep").val();
            var dec_dig = $("#dec_dig").val();
            var dec_sep2 = $("#dec_sep2").val();
            var tho_sep2 = $("#tho_sep2").val();
            var dec_dig2 = $("#dec_dig2").val();
            value = $.number(value, dec_dig2, dec_sep2, tho_sep2);
            total = $.number(total, dec_dig, dec_sep, tho_sep);
            console.log(value);
            var first_sign = $("#first_sign").val();
            var pos = $('#curr_pos').val();
            var sign = $('#curr_sign').val();
            if (pos == '0') {
                $('#sizeprice').html(sign + total);
                $("#originalprice").html(first_sign + value);
            } else {
                $('#sizeprice').html(total + sign);
                $("#originalprice").html(value + first_sign);
            }
        });


        function getSizePrice() {

            var total = 0;
            if ($('.product-size .siz-list li').length > 0) {
                total = parseFloat($('.product-size .siz-list li.active').find('.size_price').val());
            }

            return total;
        }

        function getColorPrice() {

            var total = 0;
            if ($('.product-color .color-list li').length > 0) {
                total = parseFloat($('.product-color .color-list li.active').find('.color_price').val());
            }

            return total;
        }


        function getAmount() {
            var total = 0;
            var value = parseFloat($('#product_price').val());
            var datas = $(".product-attr:checked").map(function () {
                return $(this).data('price');
            }).get();

            var data;
            for (data in datas) {
                total += parseFloat(datas[data]);
            }
            total += value;
            return total;
        }

        // Product Details Product Color Active Js Code
        $(document).on('click', '.product-color .color-list .box', function () {
            $('.qttotal').html('1');
            var parent = $(this).parent();
            colors = $(this).data('color');
            color_price = $(this).find('.color_price').val();
            color_qty = $(this).find('.color_qty').val();
            color_key = $(this).find('.color_key').val();
            $('.product-color .color-list li').removeClass('active');
            parent.addClass('active');
            total = getAmount() + parseFloat(color_price);
            var previous_price = parseFloat($('#previous_price_value').val());
            if (total >= previous_price) {
                $('#previousprice').hide();
            } else {
                $('#previousprice').show();
            }
            total = total.toFixed(2);
            stock = color_qty;
            var value = $("#curr_value").val();
            value = total / value;
            var dec_sep = $("#dec_sep").val();
            var tho_sep = $("#tho_sep").val();
            var dec_dig = $("#dec_dig").val();
            var dec_sep2 = $("#dec_sep2").val();
            var tho_sep2 = $("#tho_sep2").val();
            var dec_dig2 = $("#dec_dig2").val();
            value = $.number(value, dec_dig2, dec_sep2, tho_sep2);
            total = $.number(total, dec_dig, dec_sep, tho_sep);
            var first_sign = $("#first_sign").val();
            var pos = $('#curr_pos').val();
            var sign = $('#curr_sign').val();

            if (pos == '0') {
                $('#sizeprice').html(sign + total);
                $("#originalprice").html(first_sign + value);
            } else {
                $('#sizeprice').html(total + sign);
                $("#originalprice").html(value + first_sign);
            }
        });

        $(document).on('change', '#select-materials', function () {
            $('.qttotal').html('1');
            var parent = $(this).parent();
            materials = $(this).find("option:selected").attr('data-material-name');
            material_price = $(this).find("option:selected").attr('data-material-price');
            material_qty = $(this).find("option:selected").attr('data-material-qty');
            material_key = $(this).find("option:selected").attr('data-material-key');
            total = getAmount() + parseFloat(material_price);
            var previous_price = parseFloat($('#previous_price_value').val());
            if (total >= previous_price) {
                $('#previousprice').hide();
            } else {
                $('#previousprice').show();
            }
            total = parseFloat(total).toFixed(2);
            stock = material_qty;
            var value = $("#curr_value").val();
            value = total / value;
            var dec_sep = $("#dec_sep").val();
            var tho_sep = $("#tho_sep").val();
            var dec_dig = $("#dec_dig").val();
            var dec_sep2 = $("#dec_sep2").val();
            var tho_sep2 = $("#tho_sep2").val();
            var dec_dig2 = $("#dec_dig2").val();
            value = $.number(value, dec_dig2, dec_sep2, tho_sep2);
            total = $.number(total, dec_dig, dec_sep, tho_sep);
            var first_sign = $("#first_sign").val();
            var pos = $('#curr_pos').val();
            var sign = $('#curr_sign').val();

            if (pos == '0') {
                $('#sizeprice').html(sign + total);
                $("#originalprice").html(first_sign + value);
            } else {
                $('#sizeprice').html(total + sign);
                $("#originalprice").html(value + first_sign);
            }
        });

        // COMMENT FORM

        $(document).on('submit', '#comment-form', function (e) {
            e.preventDefault();
            $('#comment-form button.submit-btn').prop('disabled', true);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $("#comment_count").html(data[4]);
                    $('#comment-form textarea').val('');
                    $('.all-comment').prepend('<li>' +
                        '<div class="single-comment comment-section">' +
                        '<div class="left-area">' +
                        '<img src="' + data[0] + '" alt="">' +
                        '</div>' +
                        '<div class="right-area">' +
                        '<h5 class="name">' + data[1] + '</h5>' +
                        '<p class="date">' + data[2] + '</p>' +
                        '<div class="comment-body">' +
                        '<p>' + data[3] + '</p>' +
                        '</div>' +
                        '<div class="comment-footer">' +
                        '<div class="links">' +
                        '<a href="javascript:;" class="comment-link reply mr-2"><i class="fas fa-reply "></i>' + data['reply'] + '</a>' +
                        '<a href="javascript:;" class="comment-link edit mr-2"><i class="fas fa-edit "></i>' + data['edit'] + '</a>' +
                        '<a href="javascript:;" data-href="' + data[5] + '" class="comment-link comment-delete mr-2">' +
                        '<i class="fas fa-trash"></i>' + data['delete'] + '</a>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="replay-area edit-area">' +
                        '<form class="update" action="' + data[6] + '" method="POST">' +
                        '<input type="hidden" name="_token" value="' + $('input[name=_token]').val() + '">' +
                        '<textarea placeholder="' + data['edit_comment'] + '" name="text" required=""></textarea>' +
                        '<button type="submit">' + data['submit'] + '</button>' +
                        '<a href="javascript:;" class="remove">' + data['cancel'] + '</a>' +
                        '</form>' +
                        '</div>' +
                        '<div class="replay-area reply-reply-area">' +
                        '<form class="reply-form" action="' + data[7] + '" method="POST">' +
                        '<input type="hidden" name="user_id" value="' + data[8] + '">' +
                        '<input type="hidden" name="_token" value="' + $('input[name=_token]').val() + '">' +
                        '<textarea placeholder="' + data['write_reply'] + '" name="text" required=""></textarea>' +
                        '<button type="submit">' + data['submit'] + '</button>' +
                        '<a href="javascript:;" class="remove">' + data['cancel'] + '</a>' +
                        '</form>' +
                        '</div>' +
                        '</li>');

                    $('#comment-form button.submit-btn').prop('disabled', false);
                }

            });
        });

        // COMMENT FORM ENDS

        // REPLY FORM

        $(document).on('submit', '.reply-form', function (e) {
            e.preventDefault();
            var btn = $(this).find('button[type=submit]');
            btn.prop('disabled', true);
            var $this = $(this).parent();
            var text = $(this).find('textarea');
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $('#comment-form textarea').val('');
                    $('button.submit-btn').prop('disabled', false);
                    $this.before('<div class="single-comment replay-review">' +
                        '<div class="left-area">' +
                        '<img src="' + data[0] + '" alt="">' +
                        '</div>' +
                        '<div class="right-area">' +
                        '<h5 class="name">' + data[1] + '</h5>' +
                        '<p class="date">' + data[2] + '</p>' +
                        '<div class="comment-body">' +
                        '<p>' + data[3] + '</p>' +
                        '</div>' +
                        '<div class="comment-footer">' +
                        '<div class="links">' +
                        '<a href="javascript:;" class="comment-link reply mr-2"><i class="fas fa-reply "></i>' + data['reply'] + '</a>' +
                        '<a href="javascript:;" class="comment-link edit mr-2"><i class="fas fa-edit "></i>' + data['edit'] + '</a>' +
                        '<a href="javascript:;" data-href="' + data[4] + '" class="comment-link reply-delete mr-2">' +
                        '<i class="fas fa-trash"></i>' + data['delete'] + '</a>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '<div class="replay-area edit-area">' +
                        '<form class="update" action="' + data[5] + '" method="POST">' +
                        '<input type="hidden" name="_token" value="' + $('input[name=_token]').val() + '">' +
                        '<textarea placeholder="' + data['edit_reply'] + '" name="text" required=""></textarea>' +
                        '<button type="submit">' + data['submit'] + '</button>' +
                        '<a href="javascript:;" class="remove">' + data['cancel'] + '</a>' +
                        '</form>' +
                        '</div>');
                    $this.toggle();
                    text.val('');
                    btn.prop('disabled', false);
                }

            });
        });

        // REPLY FORM ENDS

        // EDIT
        $(document).on('click', '.edit', function () {
            var text = $(this).parent().parent().prev().find('p').html();
            text = $.trim(text);
            $(this).parent().parent().parent().parent().next('.edit-area').find('textarea').val(text);
            $(this).parent().parent().parent().parent().next('.edit-area').toggle();
        });
        // EDIT ENDS

        // UPDATE
        $(document).on('submit', '.update', function (e) {
            e.preventDefault();
            var btn = $(this).find('button[type=submit]');
            var text = $(this).parent().prev().find('.right-area .comment-body p');
            var $this = $(this).parent();
            btn.prop('disabled', true);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    text.html(data);
                    $this.toggle();
                    btn.prop('disabled', false);
                }
            });
        });
        // UPDATE ENDS

        // COMMENT DELETE
        $(document).on('click', '.comment-delete', function () {
            var count = parseInt($("#comment_count").html());
            count--;
            $("#comment_count").html(count);
            $(this).parent().parent().parent().parent().parent().remove();
            $.get($(this).data('href'));
        });
        // COMMENT DELETE ENDS


        // COMMENT REPLY
        $(document).on('click', '.reply', function () {
            $(this).parent().parent().parent().parent().parent().show().find('.reply-reply-area').show();
            $(this).parent().parent().parent().parent().parent().show().find('.reply-reply-area .reply-form textarea').focus();

        });
        // COMMENT REPLY ENDS

        // REPLY DELETE
        $(document).on('click', '.reply-delete', function () {
            $(this).parent().parent().parent().parent().remove();
            $.get($(this).data('href'));
        });
        // REPLY DELETE ENDS

        // View Replies
        $(document).on('click', '.view-reply', function () {
            $(this).parent().parent().parent().parent().siblings('.replay-review').removeClass('hidden');

        });
        // View Replies ENDS

        // CANCEL CLICK

        $(document).on('click', '#comment-area .remove', function () {
            $(this).parent().parent().hide();
        });

        // CANCEL CLICK ENDS



        /*-----------------------------
            Product Page Quantity
        -----------------------------*/
        $(document).on('click', '.qtminus', function () {
            var el = $(this);
            var $tselector = el.parent().parent().find('.qttotal');
            total = $($tselector).text();
            if (total > 1) {
                total--;
            }
            $($tselector).text(total);
        });

        $(document).on('click', '.qtplus', function () {
            var el = $(this);
            var $tselector = el.parent().parent().find('.qttotal');
            var max_qty = $('.max_quantity').val();
            total = $($tselector).text();
            var stk = 0;
            var max = 0;
            if (stock != "") {
                stk = parseInt(stock);
            }

            if (max_qty != "") {
                max = parseInt(max_qty);
            }

            if (stk != 0 && max == 0) {
                if (total < stk) {
                    total++;
                    $($tselector).text(total);
                }
            } else if (stk == 0 && max != 0) {
                if (total < max) {
                    total++;
                    $($tselector).text(total);
                }
            }
            if (stk < max) {
                if (total < stk) {
                    total++;
                    $($tselector).text(total);
                }
            } else {
                if (total < max) {
                    total++;
                    $($tselector).text(total);
                }
            }
            //if both values are 0, the product has no limit and stock, so keep iterating
            if (stk == 0 && max == 0) {
                total++;
                $($tselector).text(total);
            }


        });

        $(document).on("click", "#addcrt", function () {
            var qty = $('.qttotal').html();
            var pid = $("[name='associatedProductsBySize']:checked").attr('data-product-id') ? $("[name='associatedProductsBySize']:checked").attr('data-product-id') : $("#product_id").val();
            var customizable_gallery = $("#customizable_gallery").val();
            var customizable_name = $("#customizable_name").val();
            var customizable_number = $("#customizable_number").val();
            var customizable_gallery_count = ($("#customizable_gallery_count").val() === undefined) ? "": $("#customizable_gallery_count").val();
            var customizable_logo = $("#image-upload").val();
            var agree_terms = $("#agreeCustomTerms").val();
            var is_customizable_number = $("#is_customizable_number").val();

            if (customizable_gallery == null) {
                customizable_gallery = null;
            }

            if (customizable_name == null) {
                customizable_name = null;
            }

            if (customizable_number == null) {
                customizable_number = null;
            }

            if (customizable_logo == null) {
                customizable_logo = null;
            }
            if (agree_terms == null) {
                agree_terms = 0;
            }
            if (is_customizable_number == null) {
                is_customizable_number = 0;
            }

            if ($('.product-attr').length > 0) {
                values = $(".product-attr:checked").map(function () {
                    return $(this).val();
                }).get();

                keys = $(".product-attr:checked").map(function () {
                    return $(this).data('key');
                }).get();

                prices = $(".product-attr:checked").map(function () {
                    return $(this).data('price');
                }).get();

            }
            $.ajax({
                type: "GET",
                url: mainurl + "/addnumcart",
                data: { id: pid, qty: qty, customizable_gallery: customizable_gallery, customizable_name: customizable_name, customizable_number: customizable_number, customizable_gallery_count: customizable_gallery_count, customizable_logo: customizable_logo, agree_terms: agree_terms, size: sizes, color: colors, material: materials, size_qty: size_qty, size_price: size_price, size_key: size_key, color_qty: color_qty, color_price: color_price, color_key: color_key, material_qty: material_qty, material_price: material_price, material_key: material_key, keys: keys, values: values, prices: prices, is_customizable_number: is_customizable_number },
                success: function (data) {

                    if (data.digital) {
                        toastr.error(data['digital']);
                    } else if (data.out_stock) {
                        toastr.error(data['out_stock']);
                    } else if (data.agree_terms) {
                        toastr.error(data['agree_terms']);
                    } else if (data.empty_data) {
                        toastr.error(data['empty_data']);
                    } else if (data.cart_error) {
                        toastr.error(data['cart_error']);
                    } else if (data.no_texture_selected) {
                        toastr.error(data['no_texture_selected']);
                    } else {
                        $("#cart-count").html(data[0]);
                        $("#cart-count").show();
                        $("#cart-items").load(mainurl + '/carts/view');
                        if (typeof fbq != 'undefined') {
                            if (customizable_gallery != null) {
                                fbq('track', 'CustomizeProduct');
                            }
                            fbq('track', 'AddToCart', {
                                content_name: data['pixel_name'],
                                content_category: data['pixel_category'],
                                content_ids: data['pixel_id'],
                                content_type: data['pixel_type'],
                                value: data['pixel_price'],
                                currency: data['pixel_currency']
                            });
                        }
                        toastr.success(data['success']);
                    }
                }
            });

        });

        let clickCount = 0

        $(document).on("click", "#qaddcrt", function () {
            clickCount++
            console.log(clickCount)

            if(clickCount === 1) {
                var qty = $('.qttotal').html();
                var pid = $("[name='associatedProductsBySize']:checked").attr('data-product-id') ? $("[name='associatedProductsBySize']:checked").attr('data-product-id') : $("#product_id").val();
                var customizable_gallery = $("#customizable_gallery").val();
                var customizable_name = $("#customizable_name").val();
                var customizable_number = $("#customizable_number").val();
                var customizable_gallery_count = ($("#customizable_gallery_count").val() === undefined) ? "": $("#customizable_gallery_count").val();
                var customizable_logo = $("#image-upload").val();
                var agree_terms = $("#agreeCustomTerms").val();
                var is_customizable_number = $("#is_customizable_number").val();
    
                if (customizable_gallery == null) {
                    customizable_gallery = '';
                }
    
                if (customizable_name == null) {
                    customizable_name = '';
                }
    
                if (customizable_number == null) {
                    customizable_number = '';
                }
    
                if (customizable_logo == null) {
                    customizable_logo = '';
                }
                if (agree_terms == null) {
                    agree_terms = 0;
                }
                if (is_customizable_number == null) {
                    is_customizable_number = 0;
                }
    
                if ($('.product-attr').length > 0) {
                    values = $(".product-attr:checked").map(function () {
                        return $(this).val();
                    }).get();
    
                    keys = $(".product-attr:checked").map(function () {
                        return $(this).data('key');
                    }).get();
    
                    prices = $(".product-attr:checked").map(function () {
                        return $(this).data('price');
                    }).get();
    
                }
                if (typeof fbq != 'undefined') {
                    $('#preloader').fadeIn();
                    setTimeout(function () {
                        window.location = mainurl + "/addtonumcart?id=" + pid + "&qty=" + qty + "&customizable_logo=" + customizable_logo + "&agree_terms=" + agree_terms + "&customizable_name=" + customizable_name + "&customizable_number=" + customizable_number + "&customizable_gallery=" + customizable_gallery + "&customizable_gallery_count=" + customizable_gallery_count + "&size=" + sizes + "&color=" + colors.substring(1, colors.length) + "&material=" + materials + "&size_qty=" + size_qty + "&size_price=" + size_price + "&size_key=" + size_key + "&color_qty=" + color_qty + "&color_price=" + color_price + "&color_key=" + color_key + "&material_qty=" + material_qty + "&material_price=" + material_price + "&material_key=" + material_key + "&keys=" + keys + "&values=" + values + "&prices=" + prices + "&is_customizable_number=" + is_customizable_number;
                    }, 1000);
                } else {
                    window.location = mainurl + "/addtonumcart?id=" + pid + "&qty=" + qty + "&customizable_logo=" + customizable_logo + "&agree_terms=" + agree_terms + "&customizable_name=" + customizable_name + "&customizable_number=" + customizable_number + "&customizable_gallery=" + customizable_gallery + "&customizable_gallery_count=" + customizable_gallery_count + "&size=" + sizes + "&color=" + colors.substring(1, colors.length) + "&material=" + materials + "&size_qty=" + size_qty + "&size_price=" + size_price + "&size_key=" + size_key + "&color_qty=" + color_qty + "&color_price=" + color_price + "&color_key=" + color_key + "&keys=" + keys + "&material_qty=" + material_qty + "&material_price=" + material_price + "&material_key=" + material_key + "&values=" + values + "&prices=" + prices + "&is_customizable_number=" + is_customizable_number;
                }
            }

        });

        // Adding Muliple Quantity Ends

        // Add By ONE

        $(document).on("click", ".adding", function () {
            $('#preloader').show();
            var pid = $(this).parent().parent().find('.prodid').val();
            var itemid = $(this).parent().parent().find('.itemid').val();
            var size_qty = $(this).parent().parent().find('.size_qty').val();
            var size_price = $(this).parent().parent().find('.size_price').val();
            var max_qty = $(this).parent().parent().find('.max_quantity').val();
            var color_qty = $(this).parent().parent().find('.color_qty').val();
            var color_price = $(this).parent().parent().find('.color_price').val();
            var material_qty = $(this).parent().parent().find('.material_qty').val();
            var material_price = $(this).parent().parent().find('.material_price').val();
            var stck = document.getElementById('stock' + itemid).value;
            var qtyField = document.getElementById('qty' + itemid);
            var quantity = qtyField.innerHTML;
            var stk = 0;
            var max = 0;

            //parsing string value to int
            var qty = parseInt(quantity);
            if (stck != "") {
                stk = parseInt(stck);
                stk = stk + 1;
            }

            if (max_qty != "") {
                max = parseInt(max_qty);
            }

            if (stk != 0 && max == 0) {
                if (qty < stk) {
                    qty++;
                    qtyField.innerHTML = qty;
                }
            }
            if (stk == 0 && max != 0) {
                if (qty < max) {
                    qty++;
                    qtyField.innerHTML = qty;
                }
            }
            if (stk < max) {
                if (qty < stk) {
                    qty++;
                    qtyField.innerHTML = qty;
                }
            } else {
                if (qty < max) {
                    qty++;
                    qtyField.innerHTML = qty;
                }
            }

            //if both values are 0, the product has no limit and stock, so keep iterating
            if (stk == 0 && max == 0) {
                qty++;
                qtyField.innerHTML = qty;
            }
            $.ajax({
                type: "GET",
                url: mainurl + "/addbyone",
                data: { id: pid, itemid: itemid, size_qty: size_qty, color_qty: color_qty, color_price: color_price, material_qty: material_qty, material_price: material_price, size_price: size_price },
                success: function (data) {
                    if (data == 0) { } else {
                        $(".discount").html($("#d-val").val());
                        $(".cart-total").html(data[0]);
                        $(".main-total").html(data[3]);
                        $(".main-total2").html(data[4]);
                        $(".coupon-total").val(data[3]);
                        document.getElementById('prc' + itemid).innerHTML = data[2];
                        document.getElementById('prct' + itemid).innerHTML = data[2];
                        document.getElementById('cqt' + itemid).innerHTML = data[1];
                        qtyField.innerHTML = data[1];
                        $('#preloader').hide();
                    }
                },
                complete: function () {
                    $('#preloader').hide();
                }
            });
        });

        // Reduce By ONE

        $(document).on("click", ".reducing", function () {
            $('#preloader').show();
            var pid = $(this).parent().parent().find('.prodid').val();
            var itemid = $(this).parent().parent().find('.itemid').val();
            var size_qty = $(this).parent().parent().find('.size_qty').val();
            var size_price = $(this).parent().parent().find('.size_price').val();
            var color_qty = $(this).parent().parent().find('.color_qty').val();
            var color_price = $(this).parent().parent().find('.color_price').val();
            var material_qty = $(this).parent().parent().find('.material_qty').val();
            var material_price = $(this).parent().parent().find('.material_price').val();
            var stck = document.getElementById('stock' + itemid).value;
            var qtyField = document.getElementById('qty' + itemid);
            var qty = qtyField.innerHTML;

            qty = parseInt(qty);
            qty--;
            if (qty < 1) {
                qtyField.innerHtml = "1";
                $('#preloader').hide();
            } else {
                qtyField.innerHtml = qty;
                $.ajax({
                    type: "GET",
                    url: mainurl + "/reducebyone",
                    data: { id: pid, itemid: itemid, size_qty: size_qty, size_price: size_price, color_qty: color_qty, color_price: color_price, material_qty: material_qty, material_price: material_price },
                    success: function (data) {
                        $(".discount").html($("#d-val").val());
                        $(".cart-total").html(data[0]);
                        $(".main-total").html(data[3]);
                        $(".main-total2").html(data[4]);
                        $(".coupon-total").val(data[3]);
                        document.getElementById('prc' + itemid).innerHTML = data[2];
                        document.getElementById('prct' + itemid).innerHTML = data[2];
                        document.getElementById('cqt' + itemid).innerHTML = data[1];
                        qtyField.innerHTML = data[1];
                        $('#preloader').hide();
                    }
                });
            }
        });

        // Coupon Form

        $("#coupon-form").on('submit', function () {
            var val = $("#code").val();
            var total = $("#grandtotal").val();
            $.ajax({
                type: "GET",
                url: mainurl + "/carts/coupon",
                data: { code: val, total: total },
                success: function (data) {
                    if (data.not_found) {
                        toastr.error(data['not_found']);
                        $("#code").val("");
                    } else if (data.already) {
                        toastr.error(data['already']);
                        $("#code").val("");
                    } else {
                        $("#coupon_form").toggle();
                        $(".main-total").html(data[0]);
                        $(".main-total2").html(data[6]);
                        $(".discount").html(data[4]);
                        toastr.success(data['success']);
                        $("#code").val("");
                    }
                }
            });
            return false;
        });



        // Cart Section Ends

        // Cart Page Section

        $(document).on("change", ".color", function () {
            var id = $(this).parent().find('input[type=hidden]').val();
            var colors = $(this).val();
            $(this).css('background', colors);
            $.ajax({
                type: "GET",
                url: mainurl + "/upcolor",
                data: { id: id, color: colors },
                success: function (data) {
                    toastr.success(data['success']);
                }
            });
        });


        // Cart Page Section Ends

        // Review Section

        $(document).on('click', '.stars', function () {
            $('.stars').removeClass('active');
            $(this).addClass('active');
            $('#rating').val($(this).data('val'));

        });

        $(document).on('submit', '#reviewform', function (e) {
            var $this = $(this);
            e.preventDefault();
            $('.gocover').show();
            $('button.submit-btn').prop('disabled', true);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {
                        $('.alert-success').hide();
                        $('.alert-danger').show();
                        $('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>')
                        }
                        $('#reviewform textarea').eq(0).focus();

                    } else {
                        $('.alert-danger').hide();
                        $('.alert-success').show();
                        $('.alert-success p').html(data[0]);
                        $('#star-rating').html(data[1]);
                        $('#reviewform textarea').eq(0).focus();
                        $('#reviewform textarea').val('');
                        $('#reviews-section').load($this.data('href'));
                    }
                    $('.gocover').hide();
                    $('button.submit-btn').prop('disabled', false);
                }

            });
        });

        // Review Section Ends


        // MESSAGE FORM

        $(document).on('submit', '#messageform', function (e) {
            e.preventDefault();
            var href = $(this).data('href');
            $('.gocover').show();
            $('button.mybtn1').prop('disabled', true);
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if ((data.errors)) {
                        $('.alert-success').hide();
                        $('.alert-danger').show();
                        $('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>')
                        }
                        $('#messageform textarea').val('');
                    } else {
                        $('.alert-danger').hide();
                        $('.alert-success').show();
                        $('.alert-success p').html(data);
                        $('#messageform textarea').val('');
                        $('#messages').load(href);
                    }
                    $('.gocover').hide();
                    $('button.mybtn1').prop('disabled', false);
                }
            });
        });

        // MESSAGE FORM ENDS

        //**************************** CUSTOM JS SECTION ENDS****************************************

        $(document).on("click", ".favorite-prod", function () {
            var $this = $(this);
            $.get($(this).data('href'));
            $this.html('<i class="icofont-check"></i> Favorito');
            $this.prop('class', '');

        });


        //**************************** GLOBAL CAPCHA****************************************

        $('.refresh_code').on("click", function () {
            $.get(mainurl + '/contact/refresh_code', function (data, status) {
                $('.codeimg1').attr("src", mainurl + "/storage/images/capcha_code.png?time=" + Math.random());
            });
        })

        //**************************** GLOBAL CAPCHA ENDS****************************************

        //**************************** VENDOR MODAL ENDS****************************************

        $(document).on('click', '.affilate-btn', function (e) {
            e.preventDefault();
            window.open($(this).data('href'), '_blank');

        });

        $(document).on('click', '.add-to-cart-quick', function (e) {
            e.preventDefault();
            var href = $(this).data('href');
            if (typeof fbq != 'undefined') {
                $('#preloader').fadeIn(500);
                setTimeout(function () {
                    window.location = href;
                }, 1000);
            } else {
                window.location = href;
            }

        });


        // TRACK ORDER

        $('#track-form').on('submit', function (e) {
            e.preventDefault();
            var code = $('#track-code').val();
            $('.submit-loader').removeClass('d-none');
            $('#track-order').load(mainurl + '/order/track/' + code, function (response, status, xhr) {
                if (status == "success") {
                    $('.submit-loader').addClass('d-none');
                }
            });
        });

        // TRACK ORDER ENDS

    });

    //menu-mobile

    $('.categories_title').on('click', function () {

        var wwindow = window.innerWidth;
        var windowHeight = window.innerHeight;
        var htotaltop = ($('.top-header').outerHeight() + $('.logo-header').outerHeight() + $('.mainmenu-bb').outerHeight());
        var alturaRealmenumobile = $('.categories_menu_inner').outerHeight();
        var menualturaMobile = windowHeight - htotaltop;

        if (wwindow < 992) {
            if (alturaRealmenumobile > menualturaMobile) {
                $('.categories_menu_inner').css('height', menualturaMobile);
                $('.categories_menu_inner').css('overflow', 'auto');
                $('.categories_mega_menu').css('width', '97%');

            }
        }

    });

    $('#filter-icon').click(function () {
        $('#filtermobile').addClass('filter-mobile');
    });

    $('#close-filter').click(function () {
        $('#filtermobile').removeClass('filter-mobile');
    });

});
