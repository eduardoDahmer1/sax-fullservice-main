        <div class="col-lg-3 col-md-6">
          <div id="filtermobile" class="left-area">
          <div class="bg-filter">
            <div class="filter-result-area">
            <div class="header-area">
              <h4 class="title">
                {{ __("Filter Results By") }}
              </h4>
              <div id="close-filter">
                <i class="fas fa-times"></i>
              </div>
            </div>
            <div class="body-area">
              <form id="catalogForm" action="{{ route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}" method="GET">
                @if (!empty(request()->input('search')))
                  <input type="hidden" name="search" value="{{ request()->input('search') }}">
                @endif
                @if (!empty(request()->input('sort')))
                  <input type="hidden" name="sort" value="{{ request()->input('sort') }}">
                @endif

                <div class="price-range-block">
                  <p>{{ __("Price") }}</p>
                  <div id="slider-range" class="price-filter-range" name="rangeInput"></div>
                  <div class="livecount">
                    <input type="number" min=0  name="min"  id="min_price" class="price-range-field" />
                    <span>{{ __("To") }}</span>
                    <input type="number" min=0  name="max" id="max_price" class="price-range-field" />
                  </div>
                </div>

                <ul class="filter-list">
                  <div class="header-area">
                    <a data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">
                      <h4 class="title categories">
                        {{ __("Categories") }}
                        <i class="fas fa-plus"></i>
                        <i class="fas fa-minus"></i>
                      </h4>
                    </a>
                  </div>

                  <div class="collapse multi-collapse" id="multiCollapseExample1">
                    @foreach ($categories as $element)
                    <li>
                      <div class="content">
                          <a href="{{route('front.category', [$element->slug, request()->input('searchHttp')])}}" class="category-link">
                            <i class="fas fa-angle-right"></i> {{$element->name}}
                          </a>
                          @if(!empty($cat) && $cat->id == $element->id && !empty($cat->subs))
                              @foreach ($cat->subs as $key => $subelement)
                              <div class="sub-content open">
                                <a href="{{route('front.category', [$cat->slug, $subelement->slug, request()->input('searchHttp')])}}" class="subcategory-link"><i class="fas fa-angle-right"></i>{{$subelement->name}}</a>
                                @if(!empty($subcat) && $subcat->id == $subelement->id && !empty($subcat->childs))
                                  @foreach ($subcat->childs as $key => $childcat)
                                  <div class="child-content open">
                                    <a href="{{route('front.category', [$cat->slug, $subcat->slug, $childcat->slug, request()->input('searchHttp')])}}" class="subcategory-link"><i class="fas fa-caret-right"></i> {{$childcat->name}}</a>
                                  </div>
                                  @endforeach
                                @endif
                              </div>
                              @endforeach

                              @endif
                            </div>


                    </li>
                    @endforeach
                  </div>

                  @if ($brands && $brands->count())
                    <div class="header-area">
                      <a data-toggle="collapse" href="#multiCollapseExample2" role="button" aria-expanded="false" aria-controls="multiCollapseExample2">
                        <h4 class="title">
                          {{ __("Brands") }}
                          <i class="fas fa-plus"></i>
                          <i class="fas fa-minus"></i>
                        </h4>
                      </a>
                    </div>

                    <div class="collapse multi-collapse" id="multiCollapseExample2">
                      <li>
                        <div class="content">
                            <a href="{{route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory'), 'searchHttp' => request()->input('searchHttp')])}}"
                              class="category-link"> <i class="fas fa-angle-right"></i> {{ __("All Brands")}}</a>
                      </li>
                      @foreach ($brands as $element)
                        <li>
                          <div class="content">
                              <a href="{{route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory'), 'searchHttp' => request()->input('searchHttp'), 'brand' => $element->slug])}}"
                                class="category-link"> <i class="fas fa-angle-right"></i> {{$element->name}}</a>
                        </li>
                      @endforeach
                    </div>

                  @endif

                </ul>

                  <button class="btn btn-style-1 filter-style-btn" type="submit">{{ __("Search") }}</button>
              </form>
            </div>
            </div>

            @if(env("ENABLE_OLD_ATTR_STYLE"))
            @if ((!empty($cat)) || (!empty($subcat)) || (!empty($childcat)))
              <div class="tags-area">
                <div class="header-area">
                  <h4 class="title">
                      {{__('Filters')}}
                  </h4>
                </div>
                <div class="body-area">
                  <form id="attrForm" action="{{route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')])}}" method="post">
                    <ul class="filter">
                      <div class="single-filter">
                        @if (!empty($cat) && !empty(json_decode($cat->attributes, true)))
                          @foreach ($cat->attributes as $key => $attr)
                            
                            <div class="my-2 sub-title">
                              <span>- {{$attr->name}}</span>
                            </div>
                            @if (!empty($attr->attribute_options))
                              @foreach ($attr->attribute_options as $key => $option)
                                <div class="form-check ml-0 pl-0">
                                  <input name="{{$attr->input_name}}[]" class="form-check-input attribute-input" type="checkbox" id="{{$attr->input_name}}{{$option->id}}" value="{{$option->id}}">
                                  <label class="form-check-label" for="{{$attr->input_name}}{{$option->id}}">{{$option->name}}</label>
                                </div>
                              @endforeach
                            @endif
                          @endforeach
                        @endif

                        <!-- Getting sub-category relationship with category attributes -->
                        @if (!empty($cat->subs))
                          @foreach ($cat->subs as $key => $attr)
                            @foreach($attr->attributes as $key => $att)
                          <div class="my-2 sub-title">
                            <span>- {{$att->name}}</span>
                          </div>
                            @if (!empty($att->attribute_options))
                              @foreach ($att->attribute_options as $key => $option)
                                <div class="form-check  ml-0 pl-0">
                                  <input name="{{$att->input_name}}[]" class="form-check-input attribute-input" type="checkbox" id="{{$att->input_name}}{{$option->id}}" value="{{$option->id}}">
                                  <label class="form-check-label" for="{{$att->input_name}}{{$option->id}}">{{$option->name}}</label>
                                </div>
                              @endforeach
                            @endif
                          @endforeach
                          @endforeach
                        @endif

                        <!-- Getting child-category relationship with category attributes -->
                        
                        @if (!empty($cat->childs))
                          @foreach ($cat->childs as $key => $attr)
                            @foreach($attr->attributes as $key => $att)
                          <div class="my-2 sub-title">
                            <span>- {{$att->name}}</span>
                          </div>
                            @if (!empty($att->attribute_options))
                              @foreach ($att->attribute_options as $key => $option)
                                <div class="form-check  ml-0 pl-0">
                                  <input name="{{$att->input_name}}[]" class="form-check-input attribute-input" type="checkbox" id="{{$att->input_name}}{{$option->id}}" value="{{$option->id}}">
                                  <label class="form-check-label" for="{{$att->input_name}}{{$option->id}}">{{$option->name}}</label>
                                </div>
                              @endforeach
                            @endif
                          @endforeach
                          @endforeach
                        @endif
                      </div>
                    </ul>
                  </form>
                </div>
              </div>
            @endif

            @else

            @if ((!empty($cat) && empty($subcat) && !empty(json_decode($cat->attributes, true)))
              || (!empty($subcat) && empty($childcategory) && !empty(json_decode($subcat->attributes, true)))
              || (!empty($childcategory) && !empty(json_decode($childcategory->attributes, true)))
            )
              <div class="tags-area">
                <div class="header-area">
                  <h4 class="title">
                      {{__('Filters')}}
                  </h4>
                </div>
                <div class="body-area">
                  <form id="attrForm" action="{{route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')])}}" method="post">
                    <ul class="filter">
                      <div class="single-filter">

                        <!-- Category slug, appears only when are in the first slug-->
                        @if (!empty($cat) && empty($subcat) && !empty(json_decode($cat->attributes, true)))
                          @foreach ($cat->attributes as $key => $attr)
                            <div class="my-2 sub-title">
                              <span>- {{$attr->name}}</span>
                            </div>
                            @if (!empty($attr->attribute_options))
                              @foreach ($attr->attribute_options as $key => $option)
                                <div class="form-check ml-0 pl-0">
                                  <input name="{{$attr->input_name}}[]" class="form-check-input attribute-input" type="checkbox" id="{{$attr->input_name}}{{$option->id}}" value="{{$option->id}}">
                                  <label class="form-check-label" for="{{$attr->input_name}}{{$option->id}}">{{$option->name}}</label>
                                </div>
                              @endforeach
                            @endif
                          @endforeach
                        @endif
                          
                          <!-- Slug subcategory -->
                        @if (!empty($subcat) && empty($childcategory))
                          @foreach ($cat->attributes as $key => $attr)
                          <div class="my-2 sub-title">
                            <span>- {{$attr->name}}</span>
                          </div>
                            @if (!empty($attr->attribute_options))
                              @foreach ($attr->attribute_options as $key => $option)
                                <div class="form-check  ml-0 pl-0">
                                  <input name="{{$attr->input_name}}[]" class="form-check-input attribute-input" type="checkbox" id="{{$attr->input_name}}{{$option->id}}" value="{{$option->id}}">
                                  <label class="form-check-label" for="{{$attr->input_name}}{{$option->id}}">{{$option->name}}</label>
                                </div>
                              @endforeach
                            @endif
                          @endforeach

                          @foreach ($subcat->attributes as $key => $attr)
                          <div class="my-2 sub-title">
                            <span>- {{$attr->name}}</span>
                          </div>
                            @if (!empty($attr->attribute_options))
                              @foreach ($attr->attribute_options as $key => $option)
                                <div class="form-check  ml-0 pl-0">
                                  <input name="{{$attr->input_name}}[]" class="form-check-input attribute-input" type="checkbox" id="{{$attr->input_name}}{{$option->id}}" value="{{$option->id}}">
                                  <label class="form-check-label" for="{{$attr->input_name}}{{$option->id}}">{{$option->name}}</label>
                                </div>
                              @endforeach
                            @endif
                          @endforeach
                        @endif

                         <!-- Slug childcategory -->
                         @if (!empty($childcategory) && !empty(json_decode($childcategory->attributes, true)))
                         @foreach ($cat->attributes as $key => $attr)
                          <div class="my-2 sub-title">
                            <span>- {{$attr->name}}</span>
                          </div>
                            @if (!empty($attr->attribute_options))
                              @foreach ($attr->attribute_options as $key => $option)
                                <div class="form-check  ml-0 pl-0">
                                  <input name="{{$attr->input_name}}[]" class="form-check-input attribute-input" type="checkbox" id="{{$attr->input_name}}{{$option->id}}" value="{{$option->id}}">
                                  <label class="form-check-label" for="{{$attr->input_name}}{{$option->id}}">{{$option->name}}</label>
                                </div>
                              @endforeach
                            @endif
                          @endforeach

                          @foreach ($subcat->attributes as $key => $attr)
                          <div class="my-2 sub-title">
                            <span>- {{$attr->name}}</span>
                          </div>
                            @if (!empty($attr->attribute_options))
                              @foreach ($attr->attribute_options as $key => $option)
                                <div class="form-check  ml-0 pl-0">
                                  <input name="{{$attr->input_name}}[]" class="form-check-input attribute-input" type="checkbox" id="{{$attr->input_name}}{{$option->id}}" value="{{$option->id}}">
                                  <label class="form-check-label" for="{{$attr->input_name}}{{$option->id}}">{{$option->name}}</label>
                                </div>
                              @endforeach
                            @endif
                          @endforeach

                          @foreach ($childcategory->attributes as $key => $attr)
                          <div class="my-2 sub-title">
                            <span>- {{$attr->name}}</span>
                          </div>
                            @if (!empty($attr->attribute_options))
                              @foreach ($attr->attribute_options as $key => $option)
                                <div class="form-check  ml-0 pl-0">
                                  <input name="{{$attr->input_name}}[]" class="form-check-input attribute-input" type="checkbox" id="{{$attr->input_name}}{{$option->id}}" value="{{$option->id}}">
                                  <label class="form-check-label" for="{{$attr->input_name}}{{$option->id}}">{{$option->name}}</label>
                                </div>
                              @endforeach
                            @endif
                          @endforeach
                        @endif
                      </div>
                    </ul>
                  </form>
                </div>
              </div>
            @endif

            @endif

            @if(!isset($vendor))

            {{-- <div class="tags-area">
                <div class="header-area">
                    <h4 class="title">
                        {{__("Popular Tags")}}
                    </h4>
                  </div>
                  <div class="body-area">
                    <ul class="taglist">
                      @foreach(App\Models\Product::showTags() as $tag)
                      @if(!empty($tag))
                      <li>
                        <a class="{{ isset($tags) ? ($tag == $tags ? 'active' : '') : ''}}" href="{{ route('front.tag',$tag) }}">
                            {{ $tag }}
                        </a>
                      </li>
                      @endif
                      @endforeach
                    </ul>
                  </div>
            </div> --}}


            @else

            <div class="service-center">
              <div class="header-area">
                <h4 class="title">
                    {{ __("Service Center") }}
                </h4>
              </div>
              <div class="body-area">
                <ul class="list">
                  <li>
                      <a href="javascript:;" data-toggle="modal" data-target="{{ Auth::guard('web')->check() ? '#vendorform1' : '#comment-log-reg' }}">
                          <i class="icofont-email"></i> <span class="service-text">{{ __("Contact Now") }}</span>
                      </a>
                  </li>
                  <li>
                        <a href="tel:+{{$vendor->shop_number}}">
                          <i class="icofont-phone"></i> <span class="service-text">{{$vendor->shop_number}}</span>
                        </a>
                  </li>
                </ul>
              <!-- Modal -->
              </div>

              <div class="footer-area">
                <p class="title">
                  {{ __("Follow Us") }}
                </p>
                <ul class="list">


              @if($vendor->f_check != 0)
              <li><a href="{{$vendor->f_url}}" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
              @endif
              @if($vendor->g_check != 0)
              <li><a href="{{$vendor->g_url}}" target="_blank"><i class="fab google"></i></a></li>
              @endif
              @if($vendor->t_check != 0)
              <li><a href="{{$vendor->t_url}}" target="_blank"><i class="fab fa-twitter"></i></a></li>
              @endif
              @if($vendor->l_check != 0)
              <li><a href="{{$vendor->l_url}}" target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
              @endif


                </ul>
              </div>
            </div>


            @endif


          </div>
        </div>
        </div>

        <div id="filter-icon" class="icon-filter">
          <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
            viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
            <g>
              <g>
                <path d="M256,0C140.949,0,18.667,31.787,18.667,90.667c0,16.405,9.728,30.613,25.899,42.709c0.341,0.576,0.384,1.28,0.853,1.792
                  l167.915,185.579v180.587c0,4.309,2.603,8.213,6.592,9.856c1.323,0.555,2.709,0.811,4.075,0.811c2.773,0,5.504-1.088,7.552-3.115
                  l64-64c2.005-2.005,3.115-4.715,3.115-7.552V320.747l167.915-185.579c0.469-0.512,0.512-1.216,0.853-1.792
                  c16.171-12.096,25.899-26.304,25.899-42.709C493.333,31.787,371.051,0,256,0z M450.795,119.019
                  c-0.107,0.064-0.235,0.043-0.32,0.107c-9.259,6.187-20.779,11.755-34.005,16.704c-0.448,0.171-0.875,0.341-1.344,0.512
                  c-6.443,2.368-13.269,4.565-20.48,6.592c-0.853,0.235-1.792,0.469-2.667,0.704c-6.187,1.707-12.651,3.264-19.328,4.715
                  c-1.685,0.363-3.349,0.725-5.056,1.088c-15.552,3.179-32.256,5.696-49.771,7.467c-1.835,0.192-3.712,0.341-5.568,0.512
                  c-7.104,0.64-14.315,1.152-21.632,1.557c-2.24,0.128-4.459,0.256-6.741,0.363c-9.173,0.405-18.475,0.661-27.904,0.661
                  c-9.429,0-18.731-0.256-27.904-0.661c-2.283-0.107-4.48-0.235-6.741-0.363c-7.317-0.405-14.549-0.917-21.653-1.557
                  c-1.856-0.171-3.733-0.32-5.547-0.512c-17.515-1.771-34.197-4.288-49.771-7.467c-1.707-0.341-3.371-0.725-5.056-1.088
                  c-6.677-1.451-13.141-3.008-19.328-4.715c-0.875-0.235-1.813-0.448-2.667-0.704c-7.211-2.048-14.059-4.245-20.48-6.592
                  c-0.448-0.171-0.875-0.341-1.323-0.491c-13.248-4.949-24.768-10.517-34.005-16.704c-0.085-0.064-0.213-0.043-0.32-0.107
                  c-13.589-9.131-21.205-18.88-21.205-28.352c0-32.789,88.704-69.333,216-69.333s216,36.544,216,69.333
                  C472,100.139,464.384,109.909,450.795,119.019z"/>
              </g>
            </g>
          </svg>
        </div>
