@extends('layouts.load')
@section('content')

<div class="content-area">
	<div class="add-product-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="product-description">
					<div class="body-area">
						@include('includes.admin.form-error')
						<form id="geniusformdata" action="{{route('admin-vendor-verify-submit',$data->id)}}"
							method="POST" enctype="multipart/form-data">
							{{csrf_field()}}

							<div class="row">
								<div class="col-xl-12">
									<div class="input-form">
										@component('admin.components.input-localized',["required" => true, "type" => "textarea"])
											@slot('name')
												details
											@endslot
											@slot('placeholder')
											{{ __('Enter Verification Details') }}
											@endslot
											{{ __('Details') }} *
										@endcomponent
									</div>
								</div>
							</div>

							<input type="hidden" name="user_id" value="{{ $data->id }}">

							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">

									</div>
								</div>
								<div class="col-lg-7">
									<button class="addProductSubmit-btn" type="submit">{{ __('Send') }}</button>
								</div>
							</div>
						</form>


					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection