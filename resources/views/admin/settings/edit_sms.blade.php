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
                <a href="" class="text-muted">Edit SMS Setting</a>
              </li>
              <li class="breadcrumb-item text-muted">
                Edit SMS Setting
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
              <h3 class="card-label">Brand Edit Form
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('sms-setting.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href=""  onclick="event.preventDefault(); document.getElementById('client_update_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>update</a>



              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::model($settings, [ 'method' => 'PATCH','route' => ['sms-setting.update', $settings->id],'class'=>'form' ,"id"=>"client_update_form", 'enctype'=>'multipart/form-data'])}}
              @csrf
                <div class="row">

                    <div class="col-md-6 form-group ">
                        <div class="form-group  {{ $errors->has('type') ? 'has-error' : '' }}">
                            <label class="">SMS Service</label>
                            <div class="">
                                <select class="form-control" name="type" id="type">
                                    <option value="1" @if($settings) @if($settings->type == 1) selected="selected" @endif @endif>Pearl Sms</option>
                                    <option value="2" @if($settings) @if($settings->type == 2) selected="selected" @endif @endif>Twilio </option>
                                    <option value="3" @if($settings) @if($settings->type == 3) selected="selected" @endif @endif>Bulk Sms</option>
                                </select>

                                <span class="text-danger">{{ $errors->first('type') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 form-group ">
                        <div class="form-group  {{ $errors->has('template') ? 'has-error' : '' }}">
                            <label class="">SMS Template</label>
                            <div class="">
                                <select class="form-control" name="template" id="template">
                                    @foreach ($templates as $template)
                                        <option value="{{ $template->id }}" @if($settings) @if($settings->template_id == $template->id) selected="selected" @endif @endif>{{ $template->name }}</option>
                                    @endforeach


                                </select>

                                <span class="text-danger">{{ $errors->first('type') }}</span>
                            </div>
                        </div>
                    </div>

                    <div id="pearl" class="base row col-md-12">
                        <div class="col-md-4 form-group ">
                            <div class="form-group  {{ $errors->has('pearlsms_sender') ? 'has-error' : '' }}">
                                <label class="">
                                    Pearl Sms Sender</label>
                                <div class="">
                                    {{ Form::text('pearlsms_sender', ($settings)?$settings->pearlsms_sender:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('pearlsms_sender') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group ">
                            <div class="form-group  {{ $errors->has('pearlsms_api_key') ? 'has-error' : '' }}">
                                <label class="">
                                    Pearl Sms Api key</label>
                                <div class="">
                                    {{ Form::text('pearlsms_api_key', ($settings)?$settings->pearlsms_api_key:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('pearlsms_api_key') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group ">
                            <div class="form-group  {{ $errors->has('pearlsms_header') ? 'has-error' : '' }}">
                                <label class="">
                                    Pearl Sms Header</label>
                                <div class="">
                                    {{ Form::text('pearlsms_header', ($settings)?$settings->pearlsms_header:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('pearlsms_header') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group ">
                            <div class="form-group  {{ $errors->has('pearlsms_footer') ? 'has-error' : '' }}">
                                <label class="">
                                    Pearl Sms Footer</label>
                                <div class="">
                                    {{ Form::text('pearlsms_footer', ($settings)?$settings->pearlsms_footer:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('pearlsms_footer') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group ">
                            <div class="form-group  {{ $errors->has('pearlsms_username') ? 'has-error' : '' }}">
                                <label class="">
                                    Pearl Sms Username</label>
                                <div class="">
                                    {{ Form::text('pearlsms_username', ($settings)?$settings->pearlsms_username:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('pearlsms_username') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="twilio" class="base row col-md-12">
                        <div class="col-md-4 form-group ">
                            <div class="form-group  {{ $errors->has('twilio_number') ? 'has-error' : '' }}">
                                <label class="">
                                    Twilio Number</label>
                                <div class="">
                                    {{ Form::text('twilio_number', ($settings)?$settings->twilio_number:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('twilio_number') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group ">
                            <div class="form-group  {{ $errors->has('twilio_account_sid') ? 'has-error' : '' }}">
                                <label class="">
                                    Twilio Account SID</label>
                                <div class="">
                                    {{ Form::text('twilio_account_sid', ($settings)?$settings->twilio_account_sid:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('twilio_account_sid') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group ">
                            <div class="form-group  {{ $errors->has('twilio_auth_token') ? 'has-error' : '' }}">
                                <label class="">
                                    Twilio Auth Token</label>
                                <div class="">
                                    {{ Form::text('twilio_auth_token', ($settings)?$settings->twilio_auth_token:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('twilio_auth_token') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="msg91" class="base row col-md-12">
                        <div class="col-md-4 form-group ">
                            <div class="form-group  {{ $errors->has('bulksms_apikey') ? 'has-error' : '' }}">
                                <label class="">
                                    Bulk SMS Api key</label>
                                <div class="">
                                    {{ Form::text('bulksms_apikey', ($settings)?$settings->bulksms_apikey:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('bulksms_apikey') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group ">
                            <div class="form-group  {{ $errors->has('bulksms_username') ? 'has-error' : '' }}">
                                <label class="">
                                    Bulk SMS Username</label>
                                <div class="">
                                    {{ Form::text('bulksms_username', ($settings)?$settings->bulksms_username:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('bulksms_username') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 form-group ">
                            <div class="form-group  {{ $errors->has('bulksms_sendername') ? 'has-error' : '' }}">
                                <label class="">
                                    Bulk SMS Sendername</label>
                                <div class="">
                                    {{ Form::text('bulksms_sendername', ($settings)?$settings->bulksms_sendername:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('bulksms_sendername') }}</span>
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
@section('scripts')
    <script !src="">
        $(".summernote").summernote();
        $(document).ready(function() {
            $(".base").hide();
            var value = parseInt($("#type").val());
            if (value == 1){
                $("#pearl").show();
            }else if (value == 2){
                $("#twilio").show();
            }else if (value == 3){
                $("#msg91").show();
            }

        })
        $("#type").change(function() {
           $(".base").hide();
           var value = $(this).val();
           if (value == 1){
               $("#pearl").show();
           }else if (value == 2){
               $("#twilio").show();
           }else if (value == 3){
               $("#msg91").show();
           }
        });
    </script>
@endsection
