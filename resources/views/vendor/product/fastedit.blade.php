
<div class="content-area p-0">
	<div class="add-product-content">
		<div class="row">
			<div class="col-lg-12">
				<div class="product-description">
					<div class="body-area p-0">
						@include('includes.admin.form-error')
						<form id="geniusformdata" action="{{route('vendor-prod-fastedit',$data->id)}}" method="POST"
							enctype="multipart/form-data">
							@csrf
							<div class="row">
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