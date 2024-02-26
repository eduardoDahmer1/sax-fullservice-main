@extends('layouts.load')

@section('content')

            <div class="content-area">
              <div class="add-product-content">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                      @include('includes.admin.form-error') 

                                      

                      <form id="geniusformdata" action="{{route('admin-mail-update',$data->id)}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="row">
                          <div class="col-xl-12">
                              <div class="input-form">
                                  <p><small>* {{ __("indicates a required field") }}</small></p>
                              </div>
                          </div>
                      </div>

                        <div class="row" >
                                        <div class="col-xl-6">
                                          <div class="input-form">
                                            <h5 style="text-align:center;padding-bottom:10px;">{{ __('Use the BB codes, it show the data dynamically in your emails.') }}</h5>
                                            <table class="table table-pers-email">
                                                <thead>
                                                <tr style="background-color:#3c3c3c7d;font-size:1.2rem;">
                                                    <th>{{ __('Meaning') }}</th>
                                                    <th>{{ __('BB Code') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td>{{ __('Customer Name') }}</td>
                                                    <td>{customer_name}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Order Amount') }}</td>
                                                    <td>{order_amount}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ ('Admin Name') }}</td>
                                                    <td>{admin_name}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Admin Email') }}</td>
                                                    <td>{admin_email}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Website Title') }}</td>
                                                    <td>{website_title}</td>
                                                </tr>
                                                <tr>
                                                    <td>{{ __('Order Number') }}</td>
                                                    <td>{order_number}</td>
                                                </tr>
                                                @if($gs->is_back_in_stock)
                                                <tr>
                                                  <td>{{ __('Product') }} - Link</td>
                                                  <td>{product}</td>
                                                </tr>
                                                @endif

                                                </tbody>
                                            </table>
                                          </div>
                                        </div>
                          
                          <div class="col-xl-6">

                            <div class="input-form">
                                <h4 class="heading">{{ __('Email Type') }} *</h4>
                                <input type="text" class="input-field" placeholder="{{ __('Email Type') }}" required="" value="{{$data->email_type}}" disabled="">
                            </div>
                        
                            <div class="input-form">
                              @component('admin.components.input-localized',["required" => true, "from" => $data])
                                  @slot('name')
                                    email_subject
                                  @endslot
                                  @slot('placeholder')
                                    {{ __('Email Subject') }}
                                  @endslot
                                  @slot('value')
                                    email_subject
                                  @endslot
                                  {{ __('Email Subject') }} *
                              @endcomponent
                            </div>
                            
                          </div>

                          <div class="col-xl-12">
                            <div class="input-form">
                              @component('admin.components.input-localized',["required" => true, "from" => $data, "type" => "richtext"])
                                  @slot('name')
                                    email_body
                                  @endslot
                                  @slot('placeholder')
                                    {{ __('Email Body') }}
                                  @endslot
                                  @slot('value')
                                    email_body
                                  @endslot
                                  {{ __('Email Body') }} *
                              @endcomponent
                            </div>
                          </div>
                                        
                        </div> <!--FECHAMENTO TAG ROW-->

                        <div class="row">
                          
                          <div class="col-lg-7">
                            
                          </div>
                        </div>

                        

                        

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
                          </div>
                        </div>
                      </form>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

@endsection