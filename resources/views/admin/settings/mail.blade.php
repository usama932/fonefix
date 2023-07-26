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
                                <a href="" class="text-muted">JOb Setting</a>
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
                            <h3 class="card-label">Mail Setting
                                <i class="mr-2"></i>
                                <small class="">try to scroll the page</small></h3>

                        </div>
                        <div class="card-toolbar">

                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light-primary font-weight-bolder mr-2">
                                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

                            <div class="btn-group">
                                @php
                                    $user = Auth::user();
                                    if($user->role == 1){
                                        $add = 1;
                                    }elseif($user->role == 2){
                                        $add = 1;
                                    }elseif($user->role == 3){
                                        $add = $user->permission->setting_email_edit;
                                    }
                                @endphp
                                @if($add)
                                <a href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('setting_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                                    <i class="ki ki-check icon-sm"></i>Save</a>
                                @endif


                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    @include('admin.partials._messages')
                    <!--begin::Form-->
                        <form class="form" id="setting_form" method="POST" action="{{ route('email-setting.store') }}" enctype='multipart/form-data'>
                            @csrf
                            <input type="hidden" name="id" value="@if($settings){{$settings->id}}@else{{0}}@endif" />
                            <div class="row">
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('type') ? 'has-error' : '' }}">
                                        <label class="">Mail Service</label>
                                        <div class="">
                                            <select class="form-control" name="type" id="type">
                                                <option value="1" @if($settings) @if($settings->type == 1) selected="selected" @endif @endif>Smtp</option>
                                                <option value="2" @if($settings) @if($settings->type == 2) selected="selected" @endif @endif>Mailchimp </option>
                                            </select>

                                            <span class="text-danger">{{ $errors->first('type') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group ">

                                </div>
                                <div id="smtp" class="base row col-md-12">
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('smtp_host') ? 'has-error' : '' }}">
                                            <label class="">
                                                Smtp Host</label>
                                            <div class="">
                                                {{ Form::text('smtp_host', ($settings)?$settings->smtp_host:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('smtp_host') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('smtp_port') ? 'has-error' : '' }}">
                                            <label class="">
                                                Smtp Port</label>
                                            <div class="">
                                                {{ Form::text('smtp_port', ($settings)?$settings->smtp_port:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('smtp_port') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('smtp_username') ? 'has-error' : '' }}">
                                            <label class="">
                                                Smtp Username</label>
                                            <div class="">
                                                {{ Form::text('smtp_username', ($settings)?$settings->smtp_username:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('smtp_username') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('smtp_password') ? 'has-error' : '' }}">
                                            <label class="">
                                                Smtp Password</label>
                                            <div class="">
                                                {{ Form::text('smtp_password', ($settings)?$settings->smtp_password:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('smtp_password') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('smtp_encryption') ? 'has-error' : '' }}">
                                            <label class="">
                                                Smtp Encryption</label>
                                            <div class="">
                                                {{ Form::text('smtp_encryption', ($settings)?$settings->smtp_encryption:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('smtp_encryption') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('from_email') ? 'has-error' : '' }}">
                                            <label class="">
                                                 From Email</label>
                                            <div class="">
                                                {{ Form::text('from_email', ($settings)?$settings->from_email:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('from_email') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('from_name') ? 'has-error' : '' }}">
                                            <label class="">
                                                 From Name</label>
                                            <div class="">
                                                {{ Form::text('from_name', ($settings)?$settings->from_name:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('from_name') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="mailchimp" class="base row col-md-12">
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('mailchimp_apikey') ? 'has-error' : '' }}">
                                            <label class="">
                                                Mailchimp ApiKey</label>
                                            <div class="">
                                                {{ Form::text('mailchimp_apikey', ($settings)?$settings->mailchimp_apikey:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('mailchimp_apikey') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </form>
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
                $("#smtp").show();
            }else if (value == 2){
                $("#mailchimp").show();
            }else if (value == 3){
                $("#msg91").show();
            }

        })
        $("#type").change(function() {
            $(".base").hide();
            var value = $(this).val();
            if (value == 1){
                $("#smtp").show();
            }else if (value == 2){
                $("#mailchimp").show();
            }else if (value == 3){
                $("#msg91").show();
            }
        });
    </script>
@endsection
