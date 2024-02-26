@extends('layouts.load')
@section('content')

<div class="content-area">
	<div class="add-product-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="product-description">
					<div class="body-area">
						@include('includes.admin.form-error')
						<form id="geniusformdata" action="{{route('admin-pick-update',$data->id)}}" method="POST"
							enctype="multipart/form-data">
							{{csrf_field()}}
							<div class="row">
								<div class="col-xl-12">
									<div class="input-form">
										<p><small>* {{ __("indicates a required field") }}</small></p>
									</div>
								</div>
							</div>
							
							@component('admin.components.input-localized',["required" => true, "from" => $data])
								@slot('name')
									location
								@endslot
								@slot('placeholder')
									{{ __('Location') }}
								@endslot
								@slot('value')
									location
								@endslot
									{{ __('Location') }} * <span><i class="icofont-question-circle" data-toggle="tooltip" data-placement="top" title="{{__('Enter the product pick-up address if the buyer chooses to pick it up.')}}" style="margin-top: -90px; margin-right:50px;"></i></span>
							@endcomponent
				
							<div class="row justify-content-center">
						
									<button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
						
							</div>
						</form>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection