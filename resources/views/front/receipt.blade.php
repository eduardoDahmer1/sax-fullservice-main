@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')

@section('content')
<section class="user-dashbord">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="header-area">
                            <h4 class="titlereceipt">
                                {{ __("Upload Receipt") }}
                            </h4>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-xl-8">
                        <div class="receipt-content">
                            <form id="r-form" class="receipt-form">
                                {{ csrf_field() }}
                                <div class="box-form">
                                    <label for="code">{{ __("Enter the order number") }}</label>
                                    <input type="text" id="code" placeholder="Ex: 6x7X1655555589" required=""
                                        value="{{ isset($order_number) ? $order_number : null }}">
                                    <i class="icofont-search-1"></i>
                                </div>
                                <button type="submit" class="mybtn1">{{ __("Search") }}</button>
                                <button type="button" id="btnClear" class="mybtn1">{{ __("Clear") }}</button>
                            </form>
                        </div>
                        <div class="modal-body" id="order-receipt"></div>
                    </div>

                    <div id="hiddenForm" class="col-xl-5">
                        <img id="preview" width="350px" src="" alt="">

                        <h5 class="titlereceipt">{{ __("Receipt for Order Number")}}: <b id="order_number"></b></h5>
                        <form id="uploadReceipt" action="" method="POST" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <div class="box-insert-img">
                                <label for="receipt">{{ __('Choose File') }}</label>
                                <input type="file" name="receipt" id="receipt" onchange="readURL(this)" required>
                                <button type="submit" id="btnUpload" class="btn btn-success btn-ok">{{ __('Send
                                    Receipt') }}</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <section>
        @endsection
        @section('scripts')
        <script type="text/javascript">
            $(document).ready(function(){
    $("#hiddenForm").attr("hidden", true);
    if($("#code").val() != "") {
        $("#r-form").submit();
    }
});
$("#btnClear").click(function(){
    $('#code').val("");
    $("#uploadReceipt").attr("action", "");
    $("#preview").attr("src", "");
    $("#hiddenForm").attr("hidden", true);
});
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#preview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$('#r-form').on('submit',function(e){
    e.preventDefault();
    var code = $('#code').val();
    $.ajax({
        method: "GET",
        url: '{{ url("receipt/search") }}/'+code,
        success:function(data)
        {
            if ((data.errors)) {
                toastr.error(data['error']);
            }
            else {
                if(data.success == false) {
                    toastr.error(data['msg']);
                } else{
                    $("#order_number").html($("#code").val());
                    console.log(data.order_id);
                    $("#hiddenForm").attr("hidden", false);
                    var action = '{{ route("front.upload-receipt")}}/'+data.order_id;
                    $("#uploadReceipt").attr("action", action);
                    if(data.has_receipt){
                        toastr.warning(data['msg']);
                        var path = '{{ asset("storage/images/receipts/") }}'+'/'+data.receipt;
                        $("#preview").attr("src", path);
                    } else {
                        toastr.success(data['msg']);
                        $("#preview").attr("src", "");
                    }
                }
            }
            $("#code").val("");
        },
    });

});
$("#uploadReceipt").submit(function(e){
    e.preventDefault();
    $.ajax({
        method:"POST",
        url: $(this).attr('action'),
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success:function(data)
        {
            if ((data.errors)) {
                toastr.error(data.error);
            }
            else if(data.success == true){
                toastr.success(data.msg);
            } else if(data.success == false){
                toastr.error(data.msg);
            }
            $("#preview").attr("src", "");
            $("#uploadReceipt").trigger("reset");
            $("#hiddenForm").attr("hidden", true);
        },
    });
});
        </script>
        @endsection
