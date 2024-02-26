@extends('layouts.load')
@section('content')

<div class="content-area">
	<div class="add-product-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="product-description">
					<div class="body-area">
						@include('includes.admin.form-error')
						<form id="geniusformdata" action="{{route('admin-cblog-update',$data->id)}}" method="POST"
							enctype="multipart/form-data">
							{{csrf_field()}}
							<div class="row">
								<div class="col-xl-12">
									<div class="input-form">
										<p><small>* {{ __("indicates a required field") }}</small></p>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-xl-12">
									<div class="input-form">
										@component('admin.components.input-localized',["required" => true, "from" => $data])
											@slot('name')
											name
											@endslot
											@slot('placeholder')
											{{ __('Name') }}
											@endslot
											@slot('value')
											name
											@endslot
											{{ __('Name') }} *
										@endcomponent
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-4">
									<div class="left-area">

									</div>
								</div>
								<div class="col-lg-7">
									<button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
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