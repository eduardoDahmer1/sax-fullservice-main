<script>
  // Default checkout scripts. Just extracted to its own file

  if ($("#v-pills-tab13-tab").hasClass("active")) {
    $('#teste').removeClass('mfp-hide');
  } else {
    $('#teste').addClass('mfp-hide');
  }
  $('#v-pills-tab13-tab').on('click', function () {
    $('#teste').removeClass('mfp-hide');
  })

  $('a.payment:first').addClass('active');
  $('.checkoutform').prop('action', $('a.payment:first').data('form'));
  $($('a.payment:first').attr('href')).load($('a.payment:first').data('href'));
  var show = $('a.payment:first').data('show');
  if (show != 'no') {
    $('.pay-area').removeClass('d-none');
  } else {
    $('.pay-area').addClass('d-none');
  }
  $($('a.payment:first').attr('href')).addClass('active').addClass('show');

  @if (isset($checked))
    $('#comment-log-reg1').modal('show');
  @endif

  var ck = 0;
  $('.checkoutform').on('submit', function (e) {
    if (ck == 0) {
      e.preventDefault();
      $('#buttons2').removeClass('none');
      $('#buttons1').addClass('none');
      $('#pills-step2-tab').removeClass('disabled');
      $('#pills-step2-tab').click();
    } else {
      $('#preloader').show();
    }
    $('#pills-step1-tab').addClass('active');
  });
  $('#step1-btn').on('click', function () {
    // Redirects to checkout if session is available, to restart the process
    if ($('#has_temporder').val() === 'true') {
      window.location = checkout_url;
      return;
    }

    $('#pills-step1-tab').removeClass('active');
    $('#pills-step2-tab').removeClass('active');
    $('#pills-step3-tab').removeClass('active');
    $('#pills-step2-tab').addClass('disabled');
    $('#pills-step3-tab').addClass('disabled');
    $('#pills-step1-tab').click();
  });

  function back1() {
    $('#buttons1').removeClass('none');
    $('#buttons2').addClass('none');
    $('#buttons3').addClass('none');
  }

  function back2() {
    $('#buttons2').removeClass('none');
    $('#buttons3').addClass('none');
    $('#buttons1').addClass('none');
    $('#buttons3').find('button').prop('disabled', false)
  }

  function scrolltotop() {
    if ($(window).width() < 768) {
      $('html, body').animate({ scrollTop: $('.breadcrumb-area').offset().top }, "slow");
    }
  }

  function continue2() {
    $('#buttons3').removeClass('none');
    $('#buttons2').addClass('none');
    $('#buttons1').addClass('none');
  }

  function disableButton() {
    $('#buttons3').find('button').prop('disabled', true)
  }
  // Step 2 btn DONE
  $('#step2-btn').on('click', function () {
    $('#pills-step3-tab').removeClass('active');
    $('#pills-step1-tab').removeClass('active');
    $('#pills-step2-tab').removeClass('active');
    $('#pills-step3-tab').addClass('disabled');
    $('#pills-step2-tab').click();
    $('#pills-step1-tab').addClass('active');
    $('#final-btn').prop('id', 'myform');
    $('#step3-btn').prop('id', 'myform');
    $('.checkoutform').prop('id', 'myform');
  });
  $('#step3-btn').on('click', function () {
    $('.checkoutform').prop('id', 'myform');
    if ($('a.payment:first').data('val') == 'bancard') {
      $('#final-btn').prop('id', 'bancard-form');
      $('.checkoutform').prop('id', 'bancard-form');
    }
    if ($('a.payment:first').data('val') == 'pagarme') {
      $('#step3-btn').prop('id', 'pagarme-form');
      $('.checkoutform').prop('id', 'pagarme-form');
    }
    if ($('a.payment:first').data('val') == 'paystack') {
      $('#final-btn').prop('id', 'pagarme-form');
      $('.checkoutform').prop('id', 'step1-form');
    }
    $('#pills-step3-tab').removeClass('disabled');
    $('#pills-step3-tab').click();
    var shipping_user = !$('input[name="shipping_name"]').val() ? $('input[name="name"]').val() : $(
      'input[name="shipping_name"]').val();
    var shipping_location = !$('input[name="shipping_address"]').val() ? $('input[name="address"]').val() :
      $('input[name="shipping_address"]').val();
    var shipping_number = !$('input[name="shipping_address_number"]').val() ? $(
      'input[name="address_number"]').val() : $('input[name="shipping_address_number"]').val();
    var shipping_zip = !$('input[name="shipping_zip"]').val() ? $('input[name="zip"]').val() : $(
      'input[name="shipping_zip"]').val();
    var shipping_phone = !$('input[name="shipping_phone"]').val() ? $('input[name="phone"]').val() : $(
      'input[name="shipping_phone"]').val();
    $('#final_shipping_user').html('<i class="fas fa-user"></i>' + shipping_user);
    $('#final_shipping_location').html('<i class="fas fa-map-marked-alt"></i>' + shipping_location + ', ' +
      shipping_number);
    $('#final_shipping_zip').html('<i class="fas fa-map-marker-alt"></i>' + shipping_zip);
    $('#final_shipping_phone').html('<i class="fas fa-phone"></i>' + shipping_phone);
    $('#pills-step1-tab').addClass('active');
    $('#pills-step2-tab').addClass('active');
  });
  $('#final-btn').on('click', function () {
    ck = 1;
    $('.checkoutform').submit();
  })
  $('.payment').on('click', function () {
    $('.checkoutform').prop('id', 'myform');
    if ($(this).data('val') == 'bancard') {
      $('#final-btn').prop('id', 'bancard-form');
      $('.checkoutform').prop('id', 'bancard-form');
    }
    if ($(this).data('val') == 'pagarme') {
      $('#final-btn').prop('id', 'pagarme-form');
      $('.checkoutform').prop('id', 'pagarme-form');
    }

    if ($(this).data('val') == 'paystack') {
      $('#final-btn').prop('id', 'pagarme-form');
      $('.checkoutform').prop('id', 'step1-form');
    }
    if ($(this).data('val') == 'bankDeposit') {
      $('#teste').removeClass('mfp-hide');
    } else {
      $('#teste').addClass('mfp-hide');
    }
    $('.checkoutform').prop('action', $(this).data('form'));
    $('.pay-area #v-pills-tabContent .tab-pane.fade').not($(this).attr('href')).html('');
    var show = $(this).data('show');
    if (show != 'no') {
      $('.pay-area').removeClass('d-none');
    } else {
      $('.pay-area').addClass('d-none');
    }
    $($(this).attr('href')).load($(this).data('href'));
  });
  $(document).on('submit', '#myform', function (e) {
    console.log("ashudashduash")

    // Criar um objeto FormData
    var formData = new FormData();

    // Adicionar os campos ao FormData
    formData.append('_token', "M3Q4yN7CZLIDR5AOUfifOTjcEzuf6atHmWJasm2B");
    formData.append('name', "Matteo Carminato");
    formData.append('customer_document', 123456789);
    formData.append('birthday', '1995-06-18');
    formData.append('customer_gender', 'M');
    formData.append('phone', 33333333333333);
    formData.append('email', 'mcarminato95@gmail.com');
    formData.append('shipping', 'shipto');
    formData.append('pickup_location', 'SAX CIUDAD DEL ESTE|1');
    formData.append('zip', "85856-550");
    formData.append('address', "xxxxxxxxxxx");
    formData.append('address_number', "123");
    formData.append('complement', "");
    formData.append('district', "");
    formData.append('country', 173);
    formData.append('state', 11);
    formData.append('city', 27);
    formData.append('diff_address', "");
    formData.append('shipping_name', '');
    formData.append('shipping_zip', '');
    formData.append('shipping_phone', '');
    formData.append('shipping_address', '');
    formData.append('shipping_address_number', '');
    formData.append('shipping_complement', '');
    formData.append('shipping_district', '');
    formData.append('shipping_country', '');
    formData.append('shipping_state', '');
    formData.append('shipping_city', '');
    formData.append('order_note', "");
    formData.append('zimple_phone', '');
    formData.append('puntoentrega', '');
    formData.append('puntoidvalue', '');
    formData.append('aex_city', '0');
    formData.append('shipping_cost', 3);
    formData.append('packing_cost', 1);
    formData.append('dp', '0');
    formData.append('tax', '0');
    formData.append('totalQty', '1');
    formData.append('vendor_shipping_id', '0');
    formData.append('vendor_packing_id', '0');
    formData.append('total', '6657200');
    formData.append('coupon_code', '');
    formData.append('coupon_discount', '');
    formData.append('coupon_id', '');
    formData.append('user_id', '1329');

    console.log("1", formData);

    e.preventDefault();
    $.ajax({
      method: "POST",
      url: "//localhost:8000/new-checkout",
      data: formData,
      contentType: false,
      processData: false,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      cache: false,
      processData: false,
      success: function (data) {
        console.log(data);
        $('.alert-ajax').hide();
        $('.alert-ajax').find('p').text('');
        if (data.gateway === 'bancard') {
          if (data.is_zimple) {
            Bancard.Zimple.createForm('iframe-container', data.process_id);
          } else {
            Bancard.Checkout.createForm('iframe-container', data.process_id);
          }
          $("#iframe-modal").modal('show');
        }
      },
      error: function (response) {
        if (response.responseJSON.unsuccess) {
          $('.alert-ajax').find('p').text(response.responseJSON.unsuccess);
          $('.alert-ajax').show('fade');
        }
        console.log(response);
      },
      complete: function () {
        $('#preloader').hide();
      }
    });
  });
  $(document).on('submit', '#pagarme-form', function (e) {
    e.preventDefault();
    $.ajax({
      method: "POST",
      url: $(this).prop('action'),
      data: new FormData(this),
      dataType: 'JSON',
      contentType: false,
      cache: false,
      processData: false,
      success: function (data) {
        console.log(data);
        var originalOrderData = data;
        $('.alert-ajax').hide();
        $('.alert-ajax').find('p').text('');
        var checkout = new PagarMeCheckout.Checkout({
          encryption_key: data.orderData.encryption_key,
          success: function (data) {
            console.log(data);
            $('.alert-ajax').hide();
            $('.alert-ajax').find('p').text('');
            $.ajax({
              method: 'POST',
              url: '{{route("pagarme.notify")}}',
              data: {
                checkout: data,
                originalOrder: originalOrderData
              },
              success: function (data) {
                console.log(data);
                window.location.href = '{{route("payment.return")}}';
              },
              error: function (err) {
                console.log(err);
              }
            })
          },
          error: function (err) {
            console.log(err);
          },
          close: function () {
            console.log('The modal was closed');
          }
        });
        checkout.open({
          amount: data.orderData.amount,
          maxInstallments: data.orderData.installments,
          customerData: 'false',
          createToken: 'true',
          customer: {
            external_id: data.customerData.id,
            name: data.customerData.name,
            type: data.customerData.type,
            country: data.customerData.country,
            email: data.customerData.email,
            documents: [
              {
                type: data.customerData.document_type,
                number: data.customerData.document
              }
            ],
            phone_numbers: [data.customerData.phone]
          },
          billing: {
            name: data.customerData.name,
            address: {
              street: data.customerData.address,
              street_number: data.customerData.address_number,
              zipcode: data.customerData.zip,
              country: data.customerData.country,
              state: data.customerData.state,
              city: data.customerData.city,
              neighborhood: data.customerData.district,
              complementary: data.customerData.complement
            }
          },
          items: [
            {
              id: data.orderData.order_id,
              title: data.orderData.order_number,
              unit_price: data.orderData.amount,
              quantity: 1,
              tangible: 'false'
            }
          ]
        });
      },
      error: function (response) {
        console.log(response);
        if (response.responseJSON.unsuccess) {
          $('.alert-ajax').find('p').text(response.responseJSON.unsuccess);
          $('.alert-ajax').show('fade');
        }
      },
      complete: function () {
        $('#preloader').hide();
      }
    });
  });
  $(document).on('submit', '#step1-form', function () {
    $('#preloader').hide();
    var val = $('#sub').val();
    var total = $('#grandtotal').val();
    total = Math.round(total);
    if (val == 0) {
      var handler = PaystackPop.setup({
        key: '{{$gs->paystack_key}}',
        email: $('input[name=email]').val(),
        amount: total * 100,
        currency: "{{$curr_checkout->name}}",
        ref: '' + Math.floor((Math.random() * 1000000000) + 1),
        callback: function (response) {
          $('#ref_id').val(response.reference);
          $('#sub').val('1');
          $('#final-btn').click();
        },
        onClose: function () {
          window.location.reload();
        }
      });
      handler.openIframe();
      return false;
    } else {
      $('#preloader').show();
      return true;
    }
  });

</script>