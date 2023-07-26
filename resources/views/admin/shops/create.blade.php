@extends('admin.layouts.master')
@section('title',$title)
@section('content')
  <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader" kt-hidden-height="54" style="">
      <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
          <!--begin::Page Heading-->
          <div class="d-flex align-items-baseline flex-wrap mr-5">
            <!--begin::Page Title-->
            <h5 class="text-dark font-weight-bold my-1 mr-5">Dashboard</h5>
            <!--end::Page Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Manage Shops</a>
              </li>
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Add Shop</a>
              </li>
            </ul>
            <!--end::Breadcrumb-->
          </div>
          <!--end::Page Heading-->
        </div>
        <!--end::Info-->
      </div>
    </div>
    <!--end::Subheader-->
    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
      <!--begin::Container-->
      <div class="container">
        <!--begin::Card-->
        <div class="card card-custom card-sticky" id="kt_page_sticky_card">
          <div class="card-header" style="">
            <div class="card-title">
              <h3 class="card-label">Shop Add Form
                <i class="mr-2"></i>
                </h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('shops.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href="{{ route('shops.store') }}"  onclick="event.preventDefault(); document.getElementById('client_add_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save</a>



              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::open([ 'route' => 'shops.store','class'=>'form' ,"id"=>"client_add_form", 'enctype'=>'multipart/form-data']) }}
              @csrf
              <div class="row">

                <div class="col-xl-8">
                  <div class="my-5">
                    <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                      <label class="col-3">Name</label>
                      <div class="col-9">
                        {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Name','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                      </div>
                    </div>
                    <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                      <label class="col-3">Email</label>
                      <div class="col-9">
                        {{ Form::email('email', null, ['class' => 'form-control form-control-solid','id'=>'email','placeholder'=>'Email Address','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('email') }}</span>
                      </div>
                    </div>
                    <div class="form-group row {{ $errors->has('password') ? 'has-error' : '' }}">
                      <label class="col-3">Password</label>
                      <div class="col-9">
                        {{ Form::text('password', null, ['class' => 'form-control form-control-solid','id'=>'password','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                      </div>
                    </div>

                      <div class="form-group row {{ $errors->has('phone') ? 'has-error' : '' }}">
                          <label class="col-3">Phone</label>
                          <div class="col-9">
                              {{ Form::number('phone', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('phone') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('number_of_whatsapp') ? 'has-error' : '' }}">
                        <label class="col-3">Number of Whatsapp</label>
                        <div class="col-9">
                            {{ Form::number('number_of_whatsapp', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                            <span class="text-danger">{{ $errors->first('number_of_whatsapp') }}</span>
                        </div>
                    </div>
                      <div class="form-group row {{ $errors->has('line1') ? 'has-error' : '' }}">
                          <label class="col-3">Address Line 1</label>
                          <div class="col-9">
                              {{ Form::text('line1', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('line1') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('line2') ? 'has-error' : '' }}">
                          <label class="col-3">Address Line 2</label>
                          <div class="col-9">
                              {{ Form::text('line2', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('line2') }}</span>
                          </div>
                      </div>

                      <div class="form-group row {{ $errors->has('city') ? 'has-error' : '' }}">
                          <label class="col-3">City</label>
                          <div class="col-9">
                              {{ Form::text('city', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('city') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('province') ? 'has-error' : '' }}">
                          <label class="col-3">Province</label>
                          <div class="col-9">
                              <div class="provinceDiv"></div>
                              <span class="text-danger">{{ $errors->first('province') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('postal_code') ? 'has-error' : '' }}">
                          <label class="col-3">PostalCode</label>
                          <div class="col-9">
                              {{ Form::text('postal_code', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('postal_code') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('country') ? 'has-error' : '' }}">
                          <label class="col-3">Country</label>
                          <div class="col-9">
                              {{ Form::select('country',$countries, null, ['class' => 'no-padding country form-control col-lg-12','id'=>'device']) }}
                              <span class="text-danger">{{ $errors->first('country') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('image') ? 'has-error' : '' }}">
                          <label class="col-3">Image</label>
                          <div class="col-9">
                              {{ Form::file('image', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('image') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('location') ? 'has-error' : '' }}">
                          <label class="col-3">Location</label>
                          <div class="col-9">
                              {{ Form::text('location', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('location') }}</span>
                          </div>
                      </div>


                  </div>
                </div>

                <hr>
                <div class="row">
                    <div class="form-group col-md-12">
                        <h3>
                            Shop Settings
                        </h3>
                    </div>

                  <div class="col-md-8">
                      <div class="form-group row {{ $errors->has('expiry_date') ? 'has-error' : '' }}">
                          <label class="col-3">Expiry Date</label>
                          <div class="col-9">
                              {{ Form::date('expiry_date', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('expiry_date') }}</span>
                          </div>
                      </div>

                      <div class="form-group row {{ $errors->has('number_of_jobs') ? 'has-error' : '' }}">
                          <label class="col-3">Number of Jobs</label>
                          <div class="col-9">
                              {{ Form::number('number_of_jobs', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('number_of_jobs') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('number_of_emails') ? 'has-error' : '' }}">
                          <label class="col-3">Number of Emails</label>
                          <div class="col-9">
                              {{ Form::number('number_of_emails', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('number_of_emails') }}</span>
                          </div>
                      </div>

                      <div class="form-group row {{ $errors->has('number_of_sms') ? 'has-error' : '' }}">
                          <label class="col-3">Number of sms</label>
                          <div class="col-9">
                              {{ Form::number('number_of_sms', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('number_of_sms') }}</span>
                          </div>
                      </div>

                    <div class="form-group row">
                      <label class="col-3 col-form-label">Active</label>
                      <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" checked="checked" id="active" name="active" value="1">
                            <span></span>
                          </label>
                        </span>
                      </div>
                    </div>
                      <div class="active" style="display: none">
                          <div class="form-group row {{ $errors->has('disable_reason') ? 'has-error' : '' }}">
                              <label class="col-3">Disable reason</label>
                              <div class="col-9">
                                  {{ Form::text('disable_reason', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('disable_reason') }}</span>
                              </div>
                          </div>
                      </div>
                        <div class="form-group row {{ $errors->has('disable_reason') ? 'has-error' : '' }}">
                            <label class="col-3 col-form-label">Custom Status</label>
                            <div class="col-9">
                                <span class="switch switch-outline switch-icon switch-success">
                                <label><input type="checkbox" checked="checked" id="custom_status" name="custom_status" value="1">
                                    <span></span>
                                </label>
                                </span>
                            </div>
                        </div>
                      <div class="form-group row {{ $errors->has('whatsapp_number') ? 'has-error' : '' }}">
                          <label class="col-3">Whatsapp Number</label>
                          <div class="col-9">
                              {{ Form::text('whatsapp_number', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('whatsapp_number') }}</span>
                          </div>
                      </div>

                  </div>
                </div>

                  <hr>
                  <div class="row">
                      <div class="form-group col-md-12">
                          <h3>
                              Custom SMS Settings
                          </h3>
                      </div>

                      <div class="col-md-8">
                      <div class="form-group row">
                      <label class="col-3 col-form-label">Custom SMS</label>
                      <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" checked="checked" id="custom_sms" name="custom_sms" value="1">
                            <span></span>
                          </label>
                        </span>
                      </div>
                    </div>
                      <div class="custom_sms">
                          <div class="form-group row {{ $errors->has('account_sid') ? 'has-error' : '' }}">
                              <label class="col-3">TWILIO Account SID</label>
                              <div class="col-9">
                                  {{ Form::text('account_sid', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('account_sid') }}</span>
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('auth_token') ? 'has-error' : '' }}">
                              <label class="col-3">TWILIO Auth Token</label>
                              <div class="col-9">
                                  {{ Form::text('auth_token', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('auth_token') }}</span>
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('twilio_number') ? 'has-error' : '' }}">
                              <label class="col-3">TWILIO Number</label>
                              <div class="col-9">
                                  {{ Form::text('twilio_number', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('twilio_number') }}</span>
                              </div>
                          </div>
                      </div>


                      </div>
                  </div>

                  <hr>
                  <div class="row">
                      <div class="form-group col-md-12">
                          <h3>
                              Custom Mail Settings
                          </h3>
                      </div>

                      <div class="col-md-8">
                    <div class="form-group row">
                      <label class="col-3 col-form-label">Custom Mail</label>
                      <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" checked="checked" id="custom_mail" name="custom_mail" value="1">
                            <span></span>
                          </label>
                        </span>
                      </div>

                    </div>
                      <div class="custom_mail">
                          <div class="form-group row {{ $errors->has('mail_host') ? 'has-error' : '' }}">
                              <label class="col-3">Mail Host</label>
                              <div class="col-9">
                                  {{ Form::text('mail_host', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('mail_host') }}</span>
                              </div>
                          </div>

                          <div class="form-group row {{ $errors->has('mail_username') ? 'has-error' : '' }}">
                              <label class="col-3">Mail Username</label>
                              <div class="col-9">
                                  {{ Form::text('mail_username', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('mail_username') }}</span>
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('mail_password') ? 'has-error' : '' }}">
                              <label class="col-3">Mail Password</label>
                              <div class="col-9">
                                  {{ Form::text('mail_password', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('mail_password') }}</span>
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('mail_from_address') ? 'has-error' : '' }}">
                              <label class="col-3">Mail From Address</label>
                              <div class="col-9">
                                  {{ Form::text('mail_from_address', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('mail_from_address') }}</span>
                              </div>
                          </div>
                      </div>
                  </div>

                </div>

              </div>
          {{Form::close()}}
            <!--end::Form-->
          </div>
        </div>
        <!--end::Card-->

      </div>
      <!--end::Container-->
    </div>
    <!--end::Entry-->
  </div>
@endsection
@section("scripts")
    <script !src="">
        $(".country").select2();
        $(document).ready(function() {
            var CSRF_TOKEN = '{{ csrf_token() }}';
            var id = $(".country").val();
            $.post("{{ route('country-provinces') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
                $('.provinceDiv').html(response);
            });
        });
        $(".country").change(function() {
            var CSRF_TOKEN = '{{ csrf_token() }}';
            var id = $(this).val();
            $.post("{{ route('country-provinces') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
                $('.provinceDiv').html(response);
            });
        });
        $(".province").select2();
        $("#active").change(function(){
            if($(this).prop("checked") == true){
               $(".active").hide();
            }else{
                $(".active").show();

            }
        });
        $("#custom_mail").change(function(){
            if($(this).prop("checked") == true){
                $(".custom_mail").show();
            }else{
                $(".custom_mail").hide();
            }
        });
        $("#custom_sms").change(function(){
           if($(this).prop("checked") == true){
               $(".custom_sms").show();
           }else{
               $(".custom_sms").hide();
           }
        });
    </script>
@endsection
