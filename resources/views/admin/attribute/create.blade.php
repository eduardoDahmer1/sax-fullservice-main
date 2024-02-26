@extends('layouts.load')

@section('content')

  <div class="content-area" id="app">

    <div class="add-product-content">
      <div class="row">
        <div class="col-lg-12">
          <div class="product-description">
            <div class="body-area">
              @include('includes.admin.form-error')
              <form id="geniusformdata" action="{{route('admin-attr-store')}}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="row">
                  <div class="col-xl-12">
                      <div class="input-form">
                          <p><small>* {{ __("indicates a required field") }}</small></p>
                      </div>
                  </div>
              </div>

                <input type="hidden" name="type" value="{{ $type }}">
                <input type="hidden" name="category_id" value="{{ $data->id }}">

                <div class="row">
                  <div class="col-xl-12">
                      <div class="input-form">
                        @component('admin.components.input-localized',["required" => true])
                          @slot('name')
                            name
                          @endslot
                          @slot('placeholder')
                            {{ __('Enter Name') }}
                          @endslot
                          {{ __('Name') }} *
                        @endcomponent
                      </div>
                </div>

                  <div class="col-xl-6" v-if="counter > 0" id="optionarea">
                    <div class="input-form">
                         <div class="mb-3 counterrow " v-for="n in counter" :id="'counterrow'+n">
                           <div class="col-11">
                             <div class="">
                                 <h4 class="heading">{{ __('Option') }} *</h4>
                                  <div class="panel panel-lang">
                                    <div class="panel-body">
                                      <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" :id="'{{$lang->locale}}-optionfield'+n" style="position:relative;">
                                          <input type="text" class="input-field" name="{{$lang->locale}}[options][]" value="" placeholder="{{__('Option label')}}" required>
                                          <button type="button" class="btn btn-danger text-white" @click="removeOption(n)"  style="position:absolute;margin:8px 5px;"><i class="fa fa-times"></i></button>
                                        </div>
                                        @foreach ($locales as $loc)
                                          @if ($loc->locale === $lang->locale)
                                              @continue
                                          @endif
                                          <div role="tabpanel" class="tab-pane" :id="'{{$loc->locale}}-optionfield'+n" style="position:relative;">
                                            <input type="text" class="input-field" name="{{$loc->locale}}[options][]" value="" placeholder="{{__('Option label')}}">
                                            <button type="button" class="btn btn-danger text-white" @click="removeOption(n)" style="position:absolute;margin:8px; 5px"><i class="fa fa-times"></i></button>
                                          </div>
                                        @endforeach
                                      </div>
                                    </div>
                                    <div class="panel-footer">
                                      <ul class="nav nav-pills" role="tablist">
                                        <li role="presentation" class="active">
                                            <a :href="'#{{$lang->locale}}-optionfield'+n" class="active" :aria-controls="'{{$lang->locale}}-optionfield'+n"
                                                role="tab" data-toggle="tab">
                                                {{$lang->language}}
                                            </a>
                                        </li>
                                        @foreach ($locales as $loc)
                                            @if($loc->locale === $lang->locale)
                                                @continue
                                            @endif
                                            <li role="presentation">
                                                <a :href="'#{{$loc->locale}}-optionfield'+n" :aria-controls="'{{$loc->locale}}-optionfield'+n"
                                                    role="tab" data-toggle="tab">
                                                    {{$loc->language}}
                                                </a>
                                            </li>
                                        @endforeach
                                      </ul>
                                    </div>
                                  </div>
                                  @if($gs->is_attr_cards)
                                  <div class="panel panel-lang">
                                    <div class="panel-body">
                                      <div class="tab-content">
                                        <div role="tabpanel" class="tab-pane active" :id="'{{$lang->locale}}-descriptionfield'+n" style="position:relative;">
                                          <input type="text" class="input-field" name="{{$lang->locale}}[description][]" value="" placeholder="{{__('Description')}}">
                                        </div>
                                        @foreach ($locales as $loc)
                                          @if ($loc->locale === $lang->locale)
                                              @continue
                                          @endif
                                          <div role="tabpanel" class="tab-pane" :id="'{{$loc->locale}}-descriptionfield'+n" style="position:relative;">
                                            <input type="text" class="input-field" name="{{$loc->locale}}[description][]" value="" placeholder="{{__('Description')}}">
                                          </div>
                                        @endforeach
                                      </div>
                                    </div>
                                    <div class="panel-footer">
                                      <ul class="nav nav-pills" role="tablist">
                                        <li role="presentation" class="active">
                                            <a :href="'#{{$lang->locale}}-descriptionfield'+n" class="active" :aria-controls="'{{$lang->locale}}-descriptionfield'+n"
                                                role="tab" data-toggle="tab">
                                                {{$lang->language}}
                                            </a>
                                        </li>
                                        @foreach ($locales as $loc)
                                            @if($loc->locale === $lang->locale)
                                                @continue
                                            @endif
                                            <li role="presentation">
                                                <a :href="'#{{$loc->locale}}-descriptionfield'+n" :aria-controls="'{{$loc->locale}}-descriptionfield'+n"
                                                    role="tab" data-toggle="tab">
                                                    {{$loc->language}}
                                                </a>
                                            </li>
                                        @endforeach
                                      </ul>
                                    </div>
                                  </div>
                                  @endif
                             </div>
                           </div>
                         </div>
                         <div class="row justify-content-center">
                             <button type="button" class="btn btn-success text-white" @click="addOption()"><i class="fa fa-plus"></i> {{__('Add Option')}}</button>
                         </div>

                    </div>
                  </div> <!--FECHAMENTO COL-XL-12-->

                  <div class="col-xl-6">
                    <div class="input-form">
                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" id="priceStatus1" name="price_status" class="custom-control-input" checked value="1">
                        <label class="custom-control-label" for="priceStatus1">{{__('Allow Price Field')}}</label>
                        <br>
                        <span class="tooltiptext">{{ __('Show price field when creating a Product.')}}</span>
                      </div>
                    </div>
                    
                    <div class="input-form">

                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" id="showPrice1" name="show_price" class="custom-control-input" checked value="1">
                        <label class="custom-control-label" for="showPrice1">{{__('Show Price')}}</label>
                        <br>
                        <span class="tooltiptext">{{ __('Show price on product details at the store.')}}</span>
                      </div>

                    </div>
                    
                    <div class="input-form">

                      <div class="custom-control custom-checkbox">
                        <input type="checkbox" id="detailsStatus1" name="details_status" class="custom-control-input" checked value="1">
                        <label class="custom-control-label" for="detailsStatus1">{{__('Show on Details Page')}}</label>
                        <br>
                        <span class="tooltiptext">{{ __('Show the attribute itself on product details at the store.')}}</span>
                      </div>

                    </div>
                

                  </div> <!--FECHAMENTO TAG COL-XL-6-->

                </div> <!--FECHAMENTO TAG ROW PRINCIPAL-->

                <div class="row" style="justify-content:center;">
                    <button class="addProductSubmit-btn" type="submit">{{ __('Create Attribute') }}</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('scripts')
  <script>
    var app = new Vue({
      el: '#app',
      data: {
        counter: 1
      },
      methods: {
        addOption() {
          $("#optionarea").addClass('d-block');
          this.counter++;
        },
        removeOption(n) {
          if($(".counterrow").length >= 2){
            $("#counterrow"+n).remove();
          } 
          if ($(".counterrow").length == 0) {
            this.counter = 0;
          }
        }
      }
    })
  </script>
@endsection
