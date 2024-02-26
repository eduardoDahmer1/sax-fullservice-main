@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')
@section('content')
<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <ul class="pages">
          <li>
            <a href="{{route('front.index')}}">{{ __("Home") }}</a>
          </li>
          <li>
            <a href="{{route('front.brands')}}">{{ __("Brands") }}</a>
          </li>
          <li>
            <a href="{{route('front.brand', $brand->slug)}}">{{ $brand->name }}</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
<!-- Breadcrumb Area End -->
<!-- SubCategori Area Start -->
@if($brand->banner)
<div class="row">
  <div class="col-lg-12 text-center">
    <div class="intro-content ">
      <img src="{{asset('storage/images/brands/banners/'.$brand->banner)}}">
    </div>
  </div>
</div>
@endif
<section class="sub-categori">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-6">
        <div class="left-area">
          <div class="filter-result-area">
            <div class="header-area">
              <h4 class="title">{{ $brand->name }}</h4>
            </div>
            <div class="body-area">
              <img
                src="{{$brand->image ? asset('storage/images/brands/'.$brand->image) : asset('assets/images/noimage.png') }}"
                alt="{{$brand->name}}">
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-9 order-first order-lg-last ajax-loader-parent">
        <div class="right-area" id="app">
          @include('includes.filter')
          <div class="categori-item-area">
            <div class="row" id="ajaxContent">
              @include('includes.product.filtered-products')
            </div>
            <div id="ajaxLoader" class="ajax-loader"
              style="background: url({{asset('storage/images/'.$gs->loader)}}) no-repeat scroll center center rgba(0,0,0,.6);">
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>
<!-- SubCategori Area End -->
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    addToPagination();

    $("#qty").on('change', function(){
        $("#ajaxLoader").show();
        filter();
      });

// when dynamic attribute changes
$("#sortby").on('change', function() {
  filter();
});

// when price changed & clicked in search button
$(".filter-btn").on('click', function(e) {
  e.preventDefault();
  $("#ajaxLoader").show();
  filter();
});
});

  function filter() {
    let filterlink = '';

    if( $("#qty").val() != ''){
      if (filterlink == '') {
        filterlink += '{{route('front.brand', [Request::route('brand')])}}' + '?'+$("#qty").attr('name')+'='+$("#qty").val();
      } else {
        filterlink += '&'+$("#qty").attr('name')+'='+$("#qty").val();
      }
    }

    if ($("#sortby").val() != '') {
      if (filterlink == '') {
        filterlink += '{{route('front.brand', $brand->slug)}}' + '?'+$("#sortby").attr('name')+'='+$("#sortby").val();
      } else {
        filterlink += '&'+$("#sortby").attr('name')+'='+$("#sortby").val();
      }
    }


    window.location.href = encodeURI(filterlink);
  }

  function addToPagination() {
    // add to attributes in pagination links
    $('ul.pagination li a').each(function() {
      let url = $(this).attr('href');
      let queryString = '?' + url.split('?')[1]; // "?page=1234...."

      let urlParams = new URLSearchParams(queryString);
      let page = urlParams.get('page'); // value of 'page' parameter

      let fullUrl = '{{route('front.brand', $brand->slug)}}?page='+page;

      if($("#qty").val() != ''){
        fullUrl += '&qty='+encodeURI($("#qty").val());
      }
      if ($("#sortby").val() != '') {
        fullUrl += '&sort='+encodeURI($("#sortby").val());
      }

      $(this).attr('href', fullUrl);
    });
  }
</script>
@endsection
