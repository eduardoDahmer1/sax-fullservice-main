@extends('front.themes.' . env('THEME', 'theme-01') . '.layout')

@section('content')

<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
    @if($banner)
    <section>
      <img src="{{ $banner }}" class="img-fluid w-100">
    </section>
    @else
    <section>
			<img src="{{ asset('assets/front/themes/theme-15/assets/images/Banner_Sax.png') }}" class="img-fluid w-100">
		</section>
    @endif
    <div class="container">
        <div class="row pt-3">
            <div class="col-lg-3">
                <ul class="pages">
                    <li>
                        <a href="{{route('front.index')}}">{{ __("Home") }}</a>
                    </li>
                    @if (!empty($cat))
                    <li>
                        <a href="{{route('front.category', $cat->slug)}}">{{ $cat->name }}</a>
                    </li>
                    @endif
                    @if (!empty($subcat))
                    <li>
                        <a href="{{route('front.category', [$cat->slug, $subcat->slug])}}">{{ $subcat->name }}</a>
                    </li>
                    @endif
                    @if (!empty($childcat))
                    <li>
                        <a href="{{route('front.category', [$cat->slug, $subcat->slug, $childcat->slug])}}">{{
                            $childcat->name }}</a>
                    </li>
                    @endif
                    @if (empty($childcat) && empty($subcat) && empty($cat))
                    <li>
                        <a href="{{route('front.category')}}">{{ __("Search") }}</a>
                    </li>
                    @endif

                </ul>
            </div>
            <div class="col-lg-9">
              @if(!config("features.marketplace"))
                @include('includes.filter')
              @endif
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->
<!-- SubCategori Area Start -->
<section class="sub-categori">
    <div class="container">
        <div class="row">
            @include('includes.catalog')
            <div class="col-lg-9 ajax-loader-parent">
                <div class="right-area" id="app">
                    <div class="categori-item-area">
                        <div class="row p-2" id="ajaxContent">
                            @if(!config("features.marketplace"))
                            @include('includes.product.filtered-products')
                            @else
                            @include('includes.product.aggregated-products')
                            @endif
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
    $(".attribute-input, #sortby").on('change', function() {
      $("#ajaxLoader").show();
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

    if ($("#prod_name").val() != '') {
      if (filterlink == '') {
        filterlink += '{{route("front.category", [Request::route("category"), Request::route("subcategory"), Request::route("childcategory")])}}' + '?searchHttp='+$("#prod_name").val();
      } else {
        filterlink += '&searchHttp='+$("#prod_name").val();
      }
    }

    $(".attribute-input").each(function() {
      if ($(this).is(':checked')) {
        if (filterlink == '') {
          filterlink += '{{route("front.category", [Request::route("category"), Request::route("subcategory"), Request::route("childcategory")])}}' + '?'+$(this).attr('name')+'='+$(this).val();
        } else {
          filterlink += '&'+$(this).attr('name')+'='+$(this).val();
        }
      }
    });

    if( $("#qty").val() != ''){
      if (filterlink == '') {
        filterlink += '{{route("front.category", [Request::route("category"), Request::route("subcategory"), Request::route("childcategory")])}}' + '?'+$("#qty").attr('name')+'='+$("#qty").val();
      } else {
        filterlink += '&'+$("#qty").attr('name')+'='+$("#qty").val();
      }
    }

    if ($("#sortby").val() != '') {
      if (filterlink == '') {
        filterlink += '{{route("front.category", [Request::route("category"), Request::route("subcategory"), Request::route("childcategory")])}}' + '?'+$("#sortby").attr('name')+'='+$("#sortby").val();
      } else {
        filterlink += '&'+$("#sortby").attr('name')+'='+$("#sortby").val();
      }
    }

    if ($("#min_price").val() != '') {
      if (filterlink == '') {
        filterlink += '{{route("front.category", [Request::route("category"), Request::route("subcategory"), Request::route("childcategory")])}}' + '?'+$("#min_price").attr('name')+'='+$("#min_price").val();
      } else {
        filterlink += '&'+$("#min_price").attr('name')+'='+$("#min_price").val();
      }
    }

    if ($("#max_price").val() != '') {
      if (filterlink == '') {
        filterlink += '{{route("front.category", [Request::route("category"), Request::route("subcategory"), Request::route("childcategory")])}}' + '?'+$("#max_price").attr('name')+'='+$("#max_price").val();
      } else {
        filterlink += '&'+$("#max_price").attr('name')+'='+$("#max_price").val();
      }
    }

    $("#ajaxContent").load(encodeURI(filterlink), function(data) {
        console.log('ajax content');
      // add query string to pagination
      addToPagination();
      $("#ajaxLoader").fadeOut(1000);
    });

  }

  // append parameters to pagination links
  function addToPagination() {
    // add to attributes in pagination links
    $('ul.pagination li a').each(function() {
      let url = $(this).attr('href');
      let queryString = '?' + url.split('?')[1]; // "?page=1234...."

      let urlParams = new URLSearchParams(queryString);
      let page = urlParams.get('page'); // value of 'page' parameter

      let fullUrl = '{{route("front.category", [Request::route("category"),Request::route("subcategory"),Request::route("childcategory")])}}?page='+page+'&searchHttp='+'{{request()->input("searchHttp")}}';

      $(".attribute-input").each(function() {
        if ($(this).is(':checked')) {
          fullUrl += '&'+encodeURI($(this).attr('name'))+'='+encodeURI($(this).val());
        }
      });

      if($("#qty").val() != ''){
        fullUrl += '&qty='+encodeURI($("#qty").val());
      }

      if ($("#sortby").val() != '') {
        fullUrl += '&sort='+encodeURI($("#sortby").val());
      }

      if ($("#min_price").val() != '') {
        fullUrl += '&min='+encodeURI($("#min_price").val());
      }

      if ($("#max_price").val() != '') {
        fullUrl += '&max='+encodeURI($("#max_price").val());
      }

      $(this).attr('href', fullUrl);
    });
  }

</script>

<script type="text/javascript">
    $(function () {

    $("#slider-range").slider({
      range: true,
      orientation: "horizontal",
      min: 0,
      max: {{ $range_max }},
      values: [{{ isset($_GET['min']) ? $_GET['min'] : '0' }}, {{ isset($_GET['max']) ? $_GET['max'] : $range_max }}],
      step: 5,

      slide: function (event, ui) {
        if (ui.values[0] == ui.values[1]) {
          return false;
        }

        $("#min_price").val(ui.values[0]);
        $("#max_price").val(ui.values[1]);
      }
    });

    $("#min_price").val($("#slider-range").slider("values", 0));
    $("#max_price").val($("#slider-range").slider("values", 1));

  });

</script>



@endsection
