@if (Session::has('success'))
<div class="alert alert-success alert-dismissible" style="width:100%; background-color:#00852c; color:#fff;">
    <button type="button" class="close" data-dismiss="alert" style="color: #fff;">&times;</button>
    {{ Session::get('success') }}
</div>
@endif
@if (Session::has('unsuccess'))
<div class="alert alert-danger alert-dismissible" style="width:100%; background-color:#00852c; color:#fff;">
    <button type="button" class="close" data-dismiss="alert" style="color: #fff;">&times;</button>
    {{ Session::get('unsuccess') }}
</div>
@endif
@if($errors->any())
<div class="alert alert-danger alert-dismissible" style="width:100%; background-color:#D03633; color:#fff;">
    <button type="button" class="close" data-dismiss="alert" style="color: #fff;">&times;</button>
    @foreach($errors->all() as $error)
    {{ $error }}
    @endforeach
</div>
@endif
