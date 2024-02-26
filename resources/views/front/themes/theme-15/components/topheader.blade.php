<section class="top-header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content">
                    <div class="left-content">
                        <div class="list">
                            @php
                                if($slocale->id == '1'){
                                    $top_first_curr = $curr;
                                    $top_curr = App\Models\Currency::where('sign', 'R$')->first();
                                }else{
                                    $top_first_curr = $curr;
                                    $top_curr = App\Models\Currency::where('sign', 'GS$')->first();
                                }
                            @endphp
                            <ul>
                                @if (config('features.lang_switcher') && $gs->is_language == 1)
                                    <li class="separador-right">
                                        <div class="language-selector">
                                            <i class="fas fa-globe-americas"></i>
                                            <select id="changeLanguage" name="language" class="language selectors nice">
                                                @foreach ($locales as $language)
                                                    <option value="{{ route('front.language', [$language->id, $top_curr->id]) }}"
                                                        {{ $slocale->id == $language->id ? 'selected' : '' }}>
                                                        {{ $language->language }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                @endif

                                @if ($gs->show_currency_values == 1)
                                    @php
                                        if($slocale->id == '1'){
                                            $top_first_curr = $curr;
                                            $top_curr = App\Models\Currency::where('sign', 'R$')->first();
                                        }else{
                                            $top_first_curr = $curr;
                                            $top_curr = App\Models\Currency::where('sign', 'GS$')->first();
                                        }
                                    @endphp

                                    @if ($top_curr->id != 1)
                                        <li>
                                            <div class="currency-selector">
                                                <span><i class="fas fa-coins"></i>
                                                    {{ __('Currency Rate') }}:
                                                    {{ $top_first_curr->sign . number_format($top_first_curr->value, $top_first_curr->decimal_digits, $top_first_curr->decimal_separator, $top_first_curr->thousands_separator) }}
                                                    =
                                                    {{ $top_curr->sign . ' ' . number_format($top_curr->value, $top_curr->decimal_digits, $top_curr->decimal_separator, $top_curr->thousands_separator) }}
                                                </span>
                                            </div>
                                        </li>
                                    @endif
                                @endif

                                <!-- @if (Auth::guard('admin')->check())
                                    <li>
                                        <div class="mybadge1">
                                            <a href="{{ route('admin.logout') }}">
                                                {{ __('Viewing as Admin') }}
                                                <i class="fas fa-power-off"></i>
                                                {{ __('Logout') }}
                                            </a>
                                        </div>
                                    </li>
                                @endif -->

                            </ul>
                        </div>
                    </div>
                    <div class="right-content">
                        <div class="list">
                            <ul>
                                <!--MOEDA-->
                                @if (config('features.currency_switcher') && $gs->is_currency == 1)
                                    <li>
                                        <div hidden class="currency-selector"style="padding-right:12px;">
                                            <select id="changeCurrency" name="currency" class="currency selectors nice">
                                                @foreach ($currencies as $currency)
                                                    <option value="{{ route('front.currency', $currency->id) }}"
                                                        @selected($top_curr->sign == $currency->sign )>
                                                        {{ $currency->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </li>
                                @endif
                                <!--FINAL DA MOEDA-->
                                @if (config('features.productsListPdf'))
                                    <li class="login ml-0 separador-left">
                                        <a target="_blank" href="{{ route('download-list-pdf') }}">
                                            <div class="links">
                                                {{ __('Products list - PDF') }}
                                                <i class="fas fa-download"></i>
                                            </div>
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const selectLanguage = document.getElementById('changeLanguage');
    const selectCurrency = document.getElementById('changeCurrency');
    window.addEventListener('load', function() {

    });

</script>
<!-- Top Header Area End -->