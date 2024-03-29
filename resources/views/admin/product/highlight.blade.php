@extends('layouts.load')

@section('styles')
    <link href="{{ asset('assets/admin/css/jquery-ui.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="content-area">
        <div class="social-links-area">
            <div class="add-product-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-warning" role="alert">
                            <p> {{ __('Only the last 10 products are displayed.') }} </p>
                        </div>
                        <div class="product-description">
                            <div class="body-area">

                                @include('includes.admin.form-error')
                                <form id="geniusformdata" action="{{ route('admin-prod-feature', $data->id) }}"
                                    method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}

                                    <div class="row">

                                        <div class="col-xl-6">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">{{ __('Highlight in') }} {{ __('Featured') }} *</h4>
                                                <label class="switch">
                                                    <input type="checkbox" name="featured" value="1"
                                                        {{ $data->featured == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">{{ __('Highlight in') }} {{ __('Best Seller') }} *</h4>
                                                <label class="switch">
                                                    <input type="checkbox" name="best" value="1"
                                                        {{ $data->best == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">{{ __('Highlight in') }} {{ __('Top Rated') }} *</h4>
                                                <label class="switch">
                                                    <input type="checkbox" name="top" value="1"
                                                        {{ $data->top == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">{{ __('Highlight in') }} {{ __('Big Save') }} *</h4>
                                                <label class="switch">
                                                    <input type="checkbox" name="big" value="1"
                                                        {{ $data->big == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">{{ __('Highlight in') }} {{ __('Hot') }} *</h4>
                                                <label class="switch">
                                                    <input type="checkbox" name="hot" value="1"
                                                        {{ $data->hot == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">{{ __('Highlight in') }} {{ __('New') }} *</h4>
                                                <label class="switch">
                                                    <input type="checkbox" name="latest" value="1"
                                                        {{ $data->latest == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">{{ __('Highlight in') }} {{ __('Trending') }} *</h4>
                                                <label class="switch">
                                                    <input type="checkbox" name="trending" value="1"
                                                        {{ $data->trending == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">{{ __('Highlight in') }} {{ __('Sale') }} *</h4>
                                                <label class="switch">
                                                    <input type="checkbox" name="sale" value="1"
                                                        {{ $data->sale == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-xl-6">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">{{ __('Highlight in') }} {{ __('Flash Deal') }} *</h4>
                                                <label class="switch">
                                                    <input type="checkbox" name="is_discount" id="is_discount"
                                                        value="1" {{ $data->is_discount == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>

                                                <div class="{{ $data->is_discount == 0 ? 'showbox' : '' }}">
                                                    <h4 class="heading">{{ __('Discount Date') }} *</h4>
                                                    <input type="text" class="input-field" name="discount_date"
                                                        placeholder="{{ __('Enter Date') }}" id="discount_date"
                                                        value="{{ $data->discount_date }}">
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-xl-6">
                                            <div class="input-form input-form-center">
                                                <h4 class="heading">{{ __('Highlight in') }} {{ __('Navbar') }} *</h4>
                                                <label class="switch">
                                                    <input type="checkbox" name="show_in_navbar" value="1"
                                                        {{ $data->show_in_navbar == 1 ? 'checked' : '' }}>
                                                    <span class="slider round"></span>
                                                </label>
                                            </div>
                                        </div>

                                    </div>
                                    <!--FECHAMENTO TAG ROW-->

                                    <div class="row justify-content-center">
                                        <button class="addProductSubmit-btn btn btn-secondary" type="submit"
                                            id="highlightSubmitBtn">{{ __('Submit') }}</button>
                                    </div>


                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/javascript">
        $('#is_discount').on('change', function() {

            if (this.checked) {
                $(this).parent().next().removeClass('showbox');
                $('#discount').prop('required', true);
                $('#discount_date').prop('required', true);
            } else {
                $(this).parent().next().addClass('showbox');
                $('#discount').prop('required', false);
                $('#discount_date').prop('required', false);
            }

        });

        var dateToday = new Date();
        var dates = $("#discount_date").datepicker({
            changeMonth: true,
            changeYear: true,
            minDate: dateToday,
        });
    </script>
@endsection
