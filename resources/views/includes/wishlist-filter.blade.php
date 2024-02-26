<div class="row">
	<div class="col-lg-12">
		<div class="item-filter">
			<ul class="filter-list">
				<li class="item-short-area">
						<p>{{__("Sort By")}} :</p>
						<select id="sortby" name="sort" class="short-item">
							<option value="date_desc" {{$sort === 'date_desc' ? 'selected' : ''}}>{{__("Latest Product")}}</option>
							<option value="date_asc" {{$sort === 'date_asc' ? 'selected' : ''}}>{{__("Oldest Product")}}</option>
							<option value="price_asc" {{$sort === 'price_asc' ? 'selected' : ''}}>{{__("Lowest Price")}}</option>
							<option value="price_desc" {{$sort === 'price_desc' ? 'selected' : ''}}>{{__("Highest Price")}}</option>
							<option value="availability" {{$sort === 'availability' ? 'selected' : ''}}>{{__("Availability")}}</option>
						</select>
				</li>
			</ul>
		</div>
	</div>
</div>
