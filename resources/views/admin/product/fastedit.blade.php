
<div class="content-area p-0">
	<div class="add-product-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="product-description">
					<div class="body-area p-0">
						@include('includes.admin.form-error')
						<form id="geniusformdata" action="{{route('admin-prod-fastedit',$data->id)}}" method="POST"
							enctype="multipart/form-data">

							@if(!empty($data->external_name))
							<div class="row alert alert-info">
								<div class="col-lg-4">
									<div class="left-area">
										<h4 class="heading">{{ __('External Name') }}</h4>
										<p class="sub-heading">{{ __('This name comes from an external source') }}</p>
									</div>
								</div>
								<div class="col-lg-7">
									<p>
										{{$data->external_name}}
									</p>
								</div>
							</div>
							@endif

							<div class="row">
								<div class="col-xl-12">
									<div class="input-form">
										{{csrf_field()}}
										@component('admin.components.input-localized',["required" => true, "from" => $data])
										@slot('name')
										name
										@endslot
										@slot('placeholder')
										{{ __('Enter Product Name') }}
										@endslot
										@slot('value')
										name
										@endslot
										{{ __('Product Name') }}*
										@endcomponent
									</div>
								</div>

								<div class="col-xl-12">
									<div class="input-form">
										<h4 class="heading">
											{{ __('Product Current Price') }}*
											<span>({{ __('In') }} {{$sign->name}})</span>
										</h4>
										<input name="price" type="number" class="input-field" placeholder="e.g 20"
										step="0.01" min="0" value="{{round($data->price * $sign->value , 2)}}"
										required="">
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col d-flex justify-content-center">
									<button class="addProductSubmit-btn" type="submit">{{ __("Save") }}</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>