@extends('front.themes.'.env('THEME', 'theme-01').'.layout')

@section('content')


<section class="user-dashbord">
    <div class="container">
        <div class="row">
            @include('includes.user-dashboard-sidebar')
            <div class="col-lg-8 text-center">

                @if($order->method == "Bank Deposit")
                <div id="receipt_card" class="account-info">
                    <div class="header-area">
                        <h4 class="titlereceipt">
                            {{ __("Upload Receipt") }}
                        </h4>
                    </div>
                    @if($order->receipt != null)
                    <img id="preview" width="350px" src="{{asset('storage/images/receipts/'.$order->receipt)}}" alt="">
                    @else
                    <img id="preview" width="350px" src="" alt="">
                    @endif
                    <h5 class="titlereceipt">{{ __("Receipt for Order Number")}}: <b>{{$order->order_number}}</b></h5>
                    <form id="uploadReceipt" action="{{ route('user-upload-receipt', $order->id) }}" method="POST"
                        enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="box-insert-img">
                            <label for="receipt">{{ __('Choose File') }}</label>
                            <input type="file" name="receipt" id="receipt" onchange="readURL(this)" required>
                            <button type="submit" id="btnUpload" class="btn btn-success btn-ok">{{ __('Send Receipt')
                                }}</button>
                        </div>
                    </form>
                </div>
                @else

                <div class="header-area">
                    <h4 class="titlereceipt">
                        {{ __("This order should not contain a receipt.") }}
                    </h4>
                </div>
                @endif
            </div>
        </div>
    </div>
    <section>
        @endsection
        @section('scripts')
        <script type="text/javascript">
            $(document).ready(function(){
   if($("#preview").attr("src") != ""){
        toastr.warning(["Este pedido já tem um comprovante enviado, mas você pode atualizá-lo."]);
   }
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
            $("#uploadReceipt").trigger("reset");
            $("#preview").attr("src", "");
        },
    });
});
        </script>
        @endsection
