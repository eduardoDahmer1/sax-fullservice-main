<div class="row">
	<div class="col-lg-5">
		<div class="item-filter">
			<ul class="filter-list">
				<li class="item-short-area">
						<p>{{__("Sort By")}} :</p>
						<select id="sortby" name="sort" class="form-control">
							<option value="date_desc" {{$sort === 'date_desc' ? 'selected' : ''}}>{{__("Latest Product")}}</option>
							<option value="date_asc" {{$sort === 'date_asc' ? 'selected' : ''}}>{{__("Oldest Product")}}</option>
							<option value="name_asc" {{$sort === 'name_asc' ? 'selected' : ''}}>{{__("Name")}} (A-Z)</option>
							<option value="name_desc" {{$sort === 'name_desc' ? 'selected' : ''}}>{{__("Name")}} (Z-A)</option>
							<option value="price_asc" {{$sort === 'price_asc' ? 'selected' : ''}}>{{__("Lowest Price")}}</option>
							<option value="price_desc" {{$sort === 'price_desc' ? 'selected' : ''}}>{{__("Highest Price")}}</option>
							<option value="availability" {{$sort === 'availability' ? 'selected' : ''}}>{{__("Availability")}}</option>
						</select>
				</li>
			</ul>
		</div>
	</div>
	<div class="col-lg-3">
		<div class="item-filter">
			<ul class="filter-list">
				<li class="item-short-area">
						<p>{{__("Show")}} :</p>
						<select id="qty" name="qty" class="form-control">
							<option value="25" {{$qty === '25' ? 'selected' : ''}}>25</option>
							<option value="50" {{$qty === '50' ? 'selected' : ''}}>50</option>
							<option value="75" {{$qty === '75' ? 'selected' : ''}}>75</option>
							<option value="100" {{$qty === '100' ? 'selected' : ''}}>100</option>
						</select>
				</li>
			</ul>
		</div>
	</div>

</div>
