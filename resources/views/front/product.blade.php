@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')

@section('styles')
@parent
<style>
    .active {
        display: block;
    }

    .hidden {
        display: none;
    }
</style>
@endsection

@section('content')
@include('front._product-header')
@include('front._product-details')
@include('front._product-custom-gallery-modal')


@if(config('features.marketplace'))
@include('front._product-features-marketplace')
@endif

@if($gs->is_report)
@include('front._product-report-modal')
@endif

@endsection
@section('scripts')
<script>
    let is_color_gallery = "{{ !is_null($color_gallery) ? $color_gallery[0] : false }}";
    if(is_color_gallery){
        let colors = "{{ $productt->color ? implode(',', $productt->color) : "" }}";
    }

    $(document).on('click', '.product-size .siz-list .box', function() {
        var show_stock = "{{ $gs->show_stock }}";
        var size_qty = $(this).find('.size_qty').val();
        if(show_stock == 1){
            $("#stock").val(size_qty);
            $("#stock_qty").html(size_qty);
        }
    });
    $(document).on('click', '.product-color .color-list .box', function() {
        var show_stock = "{{ $gs->show_stock }}";
        var color_qty = $(this).find('.color_qty').val();
        var color = $(this).find('.color').val();
        if(show_stock == 1){
            $("#stock_qty").html(color_qty);
            $("#stock").val(color_qty);
        }
        var selectedColor = ".color-" + color.replace("#", "");
        if(is_color_gallery){
            /* Hide all gallery */
            $(".owl-item.active").addClass("hidden");
            $(".color_gallery").addClass("hidden");
            $(".owl-item.active").removeClass("active");
            $(".owl-item").addClass("hidden");

            /* Show selected gallery */
            $(".owl-item "+selectedColor+"").parent().removeClass("hidden");
            $(".owl-item "+selectedColor+"").parent().addClass("active");

            $(".owl-item.active "+selectedColor+"").parent().removeClass("hidden");
            $(".owl-item.active "+selectedColor+"").parent().addClass("active");

            $(selectedColor).removeClass("hidden");
            $(".owl-item.active "+selectedColor+"").addClass("active");

            $(selectedColor).removeClass("hidden");
            $(selectedColor).addClass("active");
            $(".owl-item.active "+selectedColor+"").trigger("click");
        }
    });

    $(document).on('change', '#select-materials', function() {

        var material = $(this).val();
        var material_price = $(this).find("option:selected").attr('data-material-price');
        var material_key = $(this).find("option:selected").attr('data-material-key');
        var material_name = $(this).find("option:selected").attr('data-material-name');
        var selectedMaterial = ".material-" + material;
        var show_stock = "{{ $gs->show_stock }}";
        var material_qty = $(this).find("option:selected").attr('data-material-qty');
        if(show_stock == 1){
            $("#stock").val(material_qty);
            $("#stock_qty").html(material_qty);
        }
        $(".material").val(material_name);
        $(".material_qty").val(material_qty);
        $(".material_price").val(material_price);
        $(".material_key").val(material_key);

        /* Hide all gallery */

        $(".owl-item.active").addClass("hidden");
        $(".color_gallery").addClass("hidden");
        $(".owl-item.active").removeClass("active");
        $(".owl-item").addClass("hidden");

        /* Show selected gallery */
        $(".owl-item "+selectedMaterial+"").parent().removeClass("hidden");
        $(".owl-item "+selectedMaterial+"").parent().addClass("active");

        $(".owl-item.active "+selectedMaterial+"").parent().removeClass("hidden");
        $(".owl-item.active "+selectedMaterial+"").parent().addClass("active");

        $(selectedMaterial).removeClass("hidden");
        $(".owl-item.active "+selectedMaterial+"").addClass("active");

        $(selectedMaterial).removeClass("hidden");
        $(selectedMaterial).addClass("active");
        $(".owl-item.active "+selectedMaterial+"").trigger("click");
    });

    $(document).on('click', '#select-materials', function() {

        var material = $(this).val();
        var selectedMaterial = ".material-" + material;

        /* Hide all gallery */

        $(".owl-item.active").addClass("hidden");
        $(".color_gallery").addClass("hidden");
        $(".owl-item.active").removeClass("active");
        $(".owl-item").addClass("hidden");

        /* Show selected gallery */
        $(".owl-item "+selectedMaterial+"").parent().removeClass("hidden");
        $(".owl-item "+selectedMaterial+"").parent().addClass("active");

        $(".owl-item.active "+selectedMaterial+"").parent().removeClass("hidden");
        $(".owl-item.active "+selectedMaterial+"").parent().addClass("active");

        $(selectedMaterial).removeClass("hidden");
        $(".owl-item.active "+selectedMaterial+"").addClass("active");

        $(selectedMaterial).removeClass("hidden");
        $(selectedMaterial).addClass("active");
        $(".owl-item.active "+selectedMaterial+"").trigger("click");
    });

    $(document).ready(function(){
        size_qty = "{{ isset($size_qty) ? $size_qty : "" }}";
        $('.size_qty').each(function(){
            if($(this).val() > 0){
                $("#stock_qty").html(size_qty);
                $("#stock").val(size_qty);
                return true;
            }
        });

        color_qty = "{{ isset($color_qty) ? $color_qty : "" }}";
        $('.color_qty').each(function(){
            if($(this).val() > 0){
                $("#stock_qty").html(color_qty);
                $("#stock").val(color_qty);
                return true;
            }
        });

        material_qty = "{{ isset($material_qty) ? $material_qty : "" }}";
        $('.material_qty').each(function(){
            if($(this).val() > 0){
                $("#stock_qty").html(material_qty);
                $("#stock").val(material_qty);
                return true;
            }
        });

        var id = "{{ $productt->id }}";
        var name = "{{ $productt->name }}";
        var category = "{{ $productt->category->name }}";
        var price = "{{ $productt->price }}";
        var currency = "{{ $product_curr->name }}";
        if(typeof fbq != 'undefined'){
            fbq('track', 'ViewContent', {
                content_name: name,
                content_category: category,
                content_ids: id,
                content_type: 'Product',
                value: price,
                currency: currency
            });
        }
    });
</script>
<script>
    $(document).on('keydown', '#customizable_number', function(){
        // is custom number length greater than 1? if it is, just substrings the last char
        $(this).val().length > 1 ? $(this).val($(this).val().substring(0, $(this).val().length -1)) : $(this).val();
    });
</script>
<script type="text/javascript">
    $(document).on("submit", "#emailreply1", function() {
        var token = $(this).find('input[name=_token]').val();
        var subject = $(this).find('input[name=subject]').val();
        var message = $(this).find('textarea[name=message]').val();
        var $type = $(this).find('input[name=type]').val();
        $('#subj1').prop('disabled', true);
        $('#msg1').prop('disabled', true);
        $('#emlsub').prop('disabled', true);
        $.ajax({
            type: 'post',
            url: "{{URL::to('/user/admin/user/send/message')}}",
            data: {
                '_token': token,
                'subject': subject,
                'message': message,
                'type': $type
            },
            success: function(data) {
                $('#subj1').prop('disabled', false);
                $('#msg1').prop('disabled', false);
                $('#subj1').val('');
                $('#msg1').val('');
                $('#emlsub').prop('disabled', false);
                if (data == 0)
                    toastr.error("Oops Something Goes Wrong !!");
                else
                    toastr.success("Message Sent !!");
                $('.close').click();
            }
        });
        return false;
    });
</script>
<script type="text/javascript">
    $(document).on("submit", "#emailreply", function() {
        var token = $(this).find('input[name=_token]').val();
        var subject = $(this).find('input[name=subject]').val();
        var message = $(this).find('textarea[name=message]').val();
        var email = $(this).find('input[name=email]').val();
        var name = $(this).find('input[name=name]').val();
        var user_id = $(this).find('input[name=user_id]').val();
        var vendor_id = $(this).find('input[name=vendor_id]').val();
        $('#subj').prop('disabled', true);
        $('#msg').prop('disabled', true);
        $('#emlsub').prop('disabled', true);
        $.ajax({
            type: 'post',
            url: "{{URL::to('/vendor/contact')}}",
            data: {
                '_token': token,
                'subject': subject,
                'message': message,
                'email': email,
                'name': name,
                'user_id': user_id,
                'vendor_id': vendor_id
            },
            success: function() {
                $('#subj').prop('disabled', false);
                $('#msg').prop('disabled', false);
                $('#subj').val('');
                $('#msg').val('');
                $('#emlsub').prop('disabled', false);
                toastr.success("{{ __('Message Sent !!') }}");
                $('.ti-close').click();
            }
        });
        return false;
    });
</script>
<script type="text/javascript">
    $.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
    });

 $(document).on('submit','#logoUpload', (function(e) {
    e.preventDefault();
    $.ajax({
    url: "{{url('admin/customprod/store')}}",
    type:"POST",
    data: new FormData(this),
    dataType: 'JSON',
    cache:false,
    contentType: false,
    processData: false,
    success:function(data){
        if(data.success){
            toastr.success("{{ __('Logo Uploaded Successfully!!') }}");
        } else{
            toastr.error(data.message);
            $("#image-upload").val(null);
        }
    },

  error: function(data){
     console.log(data);
  }
    });
  }));

</script>
<script type="text/javascript">
    function showLogoField(){
   var checkBox = document.getElementById("customLogo");
  // Get the output text
  var logoField = document.getElementById("logoField");

  // If the checkbox is checked, display the output text
  if (checkBox.checked == true){
    logoField.style.display = "block";
  } else {
    logoField.style.display = "none";
  }
}

  var customName = $("#customizable_name").val();
  var customNumber = $("#customizable_number").val();
</script>
<script type="text/javascript">
    $("#openBtn").click(function(){
      $("#openOptions").modal("show");
  });

</script>
<script type="text/javascript">
    $(".checkclick").change(function(){
        $(this).val($(this).is(":checked") ? 1 : 0);
    });

</script>
<script type="text/javascript">
    $(".textureIcons, .textureIconsModal").click(function(){
        var imageSrc = $(this).find('img').attr('src');
        $('input[id=customizable_gallery]').val(imageSrc);
        if(imageSrc.indexOf("thumbnails") > -1){
            var replaced = imageSrc.replace("thumbnails", "galleries");
        } else replaced = imageSrc;
        $("#currentGallery, .overlayCurrentSpan, .textureCurrentOverlay").attr("src", replaced).css("display", "block");
        $("#openOptions").modal("hide");
        toastr.success("{{ __('Image checked!!') }}");
    });

</script>
@endsection
