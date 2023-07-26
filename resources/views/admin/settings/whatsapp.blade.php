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
								<a href="" class="text-muted">Whatsapp Setting</a>
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
							<h3 class="card-label">Whatsapp Setting
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
                                        $add = $user->permission->setting_sms_edit;
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
						<form class="form" id="setting_form" method="POST" action="{{ route('whatsapp-setting.store') }}" enctype='multipart/form-data'>
							@csrf
                            <input type="hidden" name="id" value="@if($settings){{$settings->id}}@else{{0}}@endif" />
                            <div class="row">
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('type') ? 'has-error' : '' }}">
                                        <label class="">Whatsapp Service</label>
                                        <div class="">
                                            <select class="form-control" name="type" id="type">
                                                <option value="1" @if($settings) @if($settings->type == 1) selected="selected" @endif @endif>Cloud Whatsapp</option>
                                                <option value="2" @if($settings) @if($settings->type == 2) selected="selected" @endif @endif>Vonage </option>
                                            </select>

                                            <span class="text-danger">{{ $errors->first('type') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('template') ? 'has-error' : '' }}">
                                        <label class="">Whatsapp Template</label>
                                        <div class="">
                                            <select class="form-control select-2" name="template[]"  id="user" multiple>
                                                @foreach ($templates as  $template)

                                                    <option value="{{ $template->id }}"
                                                        @if($settings)
                                                        @php
                                                            $array = json_decode($settings->template_id, true);
                                                        @endphp
                                                        @if(!empty($template->id) && !empty( $array))
                                                            @if(in_array($template->id,$array))
                                                                selected
                                                            @endif
                                                        @endif
                                                        @endif>{{ $template->name }}</option>
                                                @endforeach


                                            </select>

                                            <span class="text-danger">{{ $errors->first('type') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div id="pearl" class="base row col-md-12">

                                    <div class="col-md-6 form-group ">
                                        <div class="form-group  {{ $errors->has('cloudwhatsapp_api_key') ? 'has-error' : '' }}">
                                            <label class="">
                                                Cloud Whatsapp Api key</label>
                                            <div class="">
                                                {{ Form::text('cloudwhatsapp_api_key', ($settings)?$settings->cloudwhatsapp_api_key:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('cloudwhatsapp_api_key') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="twilio" class="base row col-md-12">
                                    <div class="form-group  {{ $errors->has('whatsapp_vonage_from') ? 'has-error' : '' }}">
                                        <label class="">
                                            Vonage (Whatsapp From Number)</label>
                                        <div class="">
                                            {{ Form::text('whatsapp_vonage_from', ($settings)?$settings->whatsapp_vonage_from:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('whatsapp_vonage_from') }}</span>
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
        $("#user").select2({
            multiple: true,

        });
        $("#type").select2();
    </script>
@endsection
