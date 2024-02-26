@extends('layouts.load')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="product-description">
                <div class="body-area">
                    <div>
                        <div class="gocover"
                            style="background: url({{ $gs->adminLoaderUrl }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <form id="geniusform" action="{{ route('admin-attr-update', $attr->id) }}" method="post"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-xl-12">
                                    <div class="input-form">
                                        <p><small>* {{ __('indicates a required field') }}</small></p>
                                    </div>
                                </div>
                            </div>

                            @include('includes.admin.form-both')

                            <div class="row" id="optionarea">
                                <div class="col-xl-12">
                                    <div class="input-form">
                                        @component('admin.components.input-localized', ['required' => true, 'from' => $attr])
                                            @slot('name')
                                                name
                                            @endslot
                                            @slot('placeholder')
                                                {{ __('Enter Name') }}
                                            @endslot
                                            @slot('value')
                                                name
                                            @endslot
                                            {{ __('Name') }} *
                                        @endcomponent
                                    </div>
                                </div>

                                <div class="col-xl-12">
                                    <h3 class="text-center" style="padding:1rem 0;"><strong>{{ __('Options') }}</strong>
                                    </h3>
                                </div>

                                <div class="col-xl-6">
                                    <div class="input-form">

                                        <div id="app">
                                            <div class="row mb-2 counterrow" v-for="option in options"
                                                :key="option.id">
                                                <div class="col-md-11">
                                                    <h4 class="heading">{{ __('Option') }} *</h4>
                                                    <div class="panel panel-lang">
                                                        <div class="panel-body">
                                                            <div class="tab-content">
                                                                <div role="tabpanel" class="tab-pane active"
                                                                    :id="'{{ $lang->locale }}-optionfield' + option.id">
                                                                    <input class="input-field optionin" type="text"
                                                                        name="{{ $lang->locale }}[options][name][]"
                                                                        :value="option.name"
                                                                        placeholder="{{ __('Option label') }}" required>
                                                                    <input type="hidden"
                                                                        name="{{ $lang->locale }}[options][id][]"
                                                                        :value="option.id" required>
                                                                </div>
                                                                <template v-for="loc in locales">
                                                                    <div role="tabpanel" class="tab-pane"
                                                                        :id="loc + '-optionfield' + option.id"
                                                                        v-if="option.translations_fixed[loc]">
                                                                        <input class="input-field optionin" type="text"
                                                                            :name="loc + '[options][name][]'"
                                                                            :value="option.translations_fixed[loc].name"
                                                                            placeholder="{{ __('Option label') }}">
                                                                    </div>
                                                                    <div role="tabpanel" class="tab-pane"
                                                                        :id="loc + '-optionfield' + option.id" v-else>
                                                                        <input class="input-field optionin" type="text"
                                                                            :name="loc + '[options][name][]'" value=""
                                                                            placeholder="{{ __('Option label') }}">
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                        <div class="panel-footer">
                                                            <ul class="nav nav-pills" role="tablist">
                                                                <li role="presentation" class="active">
                                                                    <a :href="'#{{ $lang->locale }}-optionfield' + option.id"
                                                                        class="active"
                                                                        :aria-controls="'{{ $lang->locale }}-optionfield' + option.id"
                                                                        role="tab" data-toggle="tab">
                                                                        {{ $lang->language }}
                                                                    </a>
                                                                </li>
                                                                @foreach ($locales as $loc)
                                                                    @if ($loc->locale === $lang->locale)
                                                                        @continue
                                                                    @endif
                                                                    <li role="presentation">
                                                                        <a :href="'#{{ $loc->locale }}-optionfield' + option.id"
                                                                            :aria-controls="'{{ $loc->locale }}-optionfield' + option
                                                                                .id"
                                                                            role="tab" data-toggle="tab">
                                                                            {{ $loc->language }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    @if ($gs->is_attr_cards)
                                                        <div class="panel panel-lang">
                                                            <div class="panel-body">
                                                                <div class="tab-content">
                                                                    <div role="tabpanel" class="tab-pane active"
                                                                        :id="'{{ $lang->locale }}-descriptionfield' + option
                                                                            .id">
                                                                        <input class="input-field optionin" type="text"
                                                                            name="{{ $lang->locale }}[description][]"
                                                                            :value="option.description"
                                                                            placeholder="{{ __('Description') }}">
                                                                    </div>
                                                                    <template v-for="loc in locales">
                                                                        <div role="tabpanel" class="tab-pane"
                                                                            :id="loc + '-descriptionfield' + option.id"
                                                                            v-if="option.translations_fixed[loc]">
                                                                            <input class="input-field optionin"
                                                                                type="text"
                                                                                :name="loc + '[description][]'"
                                                                                :value="option.translations_fixed[loc]
                                                                                    .description"
                                                                                placeholder="{{ __('Description') }}">
                                                                        </div>
                                                                        <div role="tabpanel" class="tab-pane"
                                                                            :id="loc + '-descriptionfield' + option.id" v-else>
                                                                            <input class="input-field optionin"
                                                                                type="text"
                                                                                :name="loc + '[description][]'"
                                                                                value=""
                                                                                placeholder="{{ __('Description') }}">
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                            <div class="panel-footer">
                                                                <ul class="nav nav-pills" role="tablist">
                                                                    <li role="presentation" class="active">
                                                                        <a :href="'#{{ $lang->locale }}-descriptionfield' +
                                                                        option.id"
                                                                            class="active"
                                                                            :aria-controls="'{{ $lang->locale }}-descriptionfield' +
                                                                            option.id"
                                                                            role="tab" data-toggle="tab">
                                                                            {{ $lang->language }}
                                                                        </a>
                                                                    </li>
                                                                    @foreach ($locales as $loc)
                                                                        @if ($loc->locale === $lang->locale)
                                                                            @continue
                                                                        @endif
                                                                        <li role="presentation">
                                                                            <a :href="'#{{ $loc->locale }}-descriptionfield' +
                                                                            option.id"
                                                                                :aria-controls="'{{ $loc->locale }}-descriptionfield' +
                                                                                option.id"
                                                                                role="tab" data-toggle="tab">
                                                                                {{ $loc->language }}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    {{-- <input class="input-field optionin" type="text" name="{{$slocale->locale}}[options][]"
                          :value="option.name" placeholder="{{__('Option label')}}" required> --}}
                                                    <button style="position: absolute;right: -30px;top: 7px;" type="button"
                                                        class="btn btn-danger text-white"
                                                        @click="removeExistingOption(option.id)"><i
                                                            class="fa fa-times"></i></button>
                                                </div>


                                            </div>

                                        </div>
                                        <div id="app2">
                                            <div class="row mb-2 counterrow" v-for="n in counter" :id="'newOption' + n">
                                                <div class="col-md-11">
                                                    <h4 class="heading">{{ __('Option') }} *</h4>
                                                    <div class="panel panel-lang">
                                                        <div class="panel-body">
                                                            <div class="tab-content">
                                                                <div role="tabpanel" class="tab-pane active"
                                                                    :id="'{{ $lang->locale }}-newOption' + n">
                                                                    <input type="text" class="input-field"
                                                                        name="{{ $lang->locale }}[options][name][]"
                                                                        value=""
                                                                        placeholder="{{ __('Option label') }}" required>
                                                                </div>
                                                                @foreach ($locales as $loc)
                                                                    @if ($loc->locale === $lang->locale)
                                                                        @continue
                                                                    @endif
                                                                    <div role="tabpanel" class="tab-pane"
                                                                        :id="'{{ $loc->locale }}-newOption' + n">
                                                                        <input type="text" class="input-field"
                                                                            name="{{ $loc->locale }}[options][name][]"
                                                                            value=""
                                                                            placeholder="{{ __('Option label') }}">
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        <div class="panel-footer">
                                                            <ul class="nav nav-pills" role="tablist">
                                                                <li role="presentation" class="active">
                                                                    <a :href="'#{{ $lang->locale }}-newOption' + n"
                                                                        class="active"
                                                                        :aria-controls="'{{ $lang->locale }}-newOption' + n"
                                                                        role="tab" data-toggle="tab">
                                                                        {{ $lang->language }}
                                                                    </a>
                                                                </li>
                                                                @foreach ($locales as $loc)
                                                                    @if ($loc->locale === $lang->locale)
                                                                        @continue
                                                                    @endif
                                                                    <li role="presentation">
                                                                        <a :href="'#{{ $loc->locale }}-newOption' + n"
                                                                            :aria-controls="'{{ $loc->locale }}-newOption' + n"
                                                                            role="tab" data-toggle="tab">
                                                                            {{ $loc->language }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    @if ($gs->is_attr_cards)
                                                        <div class="panel panel-lang">
                                                            <div class="panel-body">
                                                                <div class="tab-content">
                                                                    <div role="tabpanel" class="tab-pane active"
                                                                        :id="'{{ $lang->locale }}-newDescription' + n">
                                                                        <input type="text" class="input-field"
                                                                            name="{{ $lang->locale }}[description][]"
                                                                            value=""
                                                                            placeholder="{{ __('Description') }}">
                                                                    </div>
                                                                    @foreach ($locales as $loc)
                                                                        @if ($loc->locale === $lang->locale)
                                                                            @continue
                                                                        @endif
                                                                        <div role="tabpanel" class="tab-pane"
                                                                            :id="'{{ $loc->locale }}-newDescription' + n">
                                                                            <input type="text" class="input-field"
                                                                                name="{{ $loc->locale }}[description][]"
                                                                                value=""
                                                                                placeholder="{{ __('Description') }}">
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="panel-footer">
                                                                <ul class="nav nav-pills" role="tablist">
                                                                    <li role="presentation" class="active">
                                                                        <a :href="'#{{ $lang->locale }}-newDescription' + n"
                                                                            class="active"
                                                                            :aria-controls="'{{ $lang->locale }}-newDescription' + n"
                                                                            role="tab" data-toggle="tab">
                                                                            {{ $lang->language }}
                                                                        </a>
                                                                    </li>
                                                                    @foreach ($locales as $loc)
                                                                        @if ($loc->locale === $lang->locale)
                                                                            @continue
                                                                        @endif
                                                                        <li role="presentation">
                                                                            <a :href="'#{{ $loc->locale }}-newDescription' + n"
                                                                                :aria-controls="'{{ $loc->locale }}-newDescription' +
                                                                                n"
                                                                                role="tab" data-toggle="tab">
                                                                                {{ $loc->language }}
                                                                            </a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <button style="position: absolute;right: -30px;top: 7px;"
                                                        type="button" class="btn btn-danger text-white"
                                                        style="margin-bottom:43px;align-self:center;"
                                                        @click="removeOption(n)"><i class="fa fa-times"></i></button>

                                                </div>


                                            </div>
                                            <button type="button" class="btn btn-success text-white"
                                                @click="addOption()" style="display:block;margin:auto;"><i
                                                    class="fa fa-plus"></i> {{ __('Add Option') }}</button>
                                            @if ($errors->has('options.*') || $errors->has('options'))
                                                <p class="text-danger mb-0">{{ $errors->first('options.*') }}</p>
                                                <p class="text-danger mb-0">{{ $errors->first('options') }}</p>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                <div class="col-xl-6">
                                    <div class="input-form">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" id="priceStatus1" name="price_status"
                                                class="custom-control-input"
                                                {{ $attr->price_status == 1 ? 'checked' : '' }} value="1">
                                            <label class="custom-control-label"
                                                for="priceStatus1">{{ __('Allow Price Field') }}</label>
                                            <br>
                                            <span
                                                class="tooltiptext">{{ __('Show price field when creating a Product.') }}</span>
                                        </div>
                                    </div>

                                    <div class="input-form">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" id="showPrice1" name="show_price"
                                                class="custom-control-input" {{ $attr->show_price == 1 ? 'checked' : '' }}
                                                value="1">
                                            <label class="custom-control-label"
                                                for="showPrice1">{{ __('Show Price') }}</label>
                                            <br>
                                            <span
                                                class="tooltiptext">{{ __('Show price on product details at the store.') }}</span>
                                        </div>
                                    </div>

                                    <div class="input-form">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" id="detailsStatus1" name="details_status"
                                                class="custom-control-input"
                                                {{ $attr->details_status == 1 ? 'checked' : '' }} value="1">
                                            <label class="custom-control-label"
                                                for="detailsStatus1">{{ __('Show on Details Page') }}</label>
                                            <br>
                                            <span
                                                class="tooltiptext">{{ __('Show the attribute itself on product details at the
                                                                      store.') }}</span>
                                        </div>
                                    </div>

                                </div>

                            </div>
                            <!--fechamento tag row-->



                            <div class="row justify-content-center">

                                <button type="submit"
                                    class="add-btn addProductSubmit-btn">{{ __('UPDATE FIELD') }}</button>

                            </div>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // First app is to handle existing data
        var app = new Vue({
            el: '#app',
            data: {
                options: [],
                locales: [],
            },
            created() {

                @foreach ($locales as $loc)
                    @if ($loc->locale === $lang->locale)
                        @continue
                    @endif
                    this.locales.push('{{ $loc->locale }}');
                @endforeach

                $.get("{{ route('admin-attr-options', $attr->id) }}", (data) => {
                    for (var i = 0; i < data.length; i++) {
                        this.options.push(data[i]);
                    }
                    // setup translations by locale
                    for (option in this.options) {
                        translations = this.options[option].translations.filter(function(value, index,
                        arr) {
                            return value.locale !== '{{ $lang->locale }}'
                        });
                        this.options[option].translations_fixed = {};
                        for (trans in translations) {
                            var translated = {
                                "name": translations[trans].name,
                                "description": translations[trans].description
                            };
                            this.options[option].translations_fixed[translations[trans].locale] =
                            translated;
                        }
                    };
                });
            },
            methods: {
                removeExistingOption(optionid) {
                    for (var i = 0; i < this.options.length; i++) {
                        if (this.options.length == 1) {
                            return;
                        }
                        if (this.options[i].id == optionid) {
                            this.options.splice(i, 1);
                        }
                    }
                    console.log(optionid);
                    $.ajax({
                        url: mainurl + '/admin/attribute/deleteattropt/' + optionid,
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },

                    });
                }
            }
        });
        // Second app is to handle new data and inputs
        var app2 = new Vue({
            el: '#app2',
            data: {
                counter: 0
            },
            methods: {
                addOption() {
                    this.counter++;
                },
                removeOption(n) {
                    $("#newOption" + n).remove();
                }
            }
        });
    </script>
@endsection
