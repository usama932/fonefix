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
							<h3 class="card-label">Job Setting
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
                                        $add = $user->permission->setting_job_edit;
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
						<form class="form" id="setting_form" method="POST" action="{{ route('jobs-setting.store') }}" enctype='multipart/form-data'>
							@csrf
                            <input type="hidden" name="id" value="@if($settings){{$settings->id}}@else{{0}}@endif" />
                            <div class="row">
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('status') ? 'has-error' : '' }}">
                                        <label class="">Default Job Sheet Status</label>
                                        <div class="">
                                            {{ Form::select('status',$statuses, ($settings)?$settings->status_id:null, ['class' => 'no-padding form-control col-lg-12','id'=>'status']) }}

                                            <span class="text-danger">{{ $errors->first('status') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('jos_sheet_prefix') ? 'has-error' : '' }}">
                                        <label class="">
                                            Job sheet number prefix</label>
                                        <div class="">
                                            {{ Form::text('jos_sheet_prefix', ($settings)?$settings->jos_sheet_prefix:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('jos_sheet_prefix') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 form-group ">
                                    <div class="form-group  {{ $errors->has('product_configuration') ? 'has-error' : '' }}">
                                        <label class="">Product Configuration</label>
                                        <i class="fa fa-question-circle  btn btn-outline-primary float-right btn-sm mb-2" role="button" data-toggle="tooltip" data-trigger="click" title="Add comma (,) separated multiple product configurations, to be used in job sheet Ex: Item 1, Item 2, Item 3"></i>
                                        <div class="">
                                            {{ Form::textarea('product_configuration', ($settings)?$settings->product_configuration:null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('product_configuration') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 form-group ">
                                    <div class="form-group  {{ $errors->has('problem_by_customer') ? 'has-error' : '' }}">
                                        <label class="">Problem Reported By The Customer</label>
                                        <i class="fa fa-question-circle  btn btn-outline-primary float-right btn-sm mb-2" role="button" data-toggle="tooltip" data-trigger="click"
                                           title="Add comma (,) separated multiple Problem Reported By The Customer, to be used in job sheet Ex: Item 1, Item 2, Item 3"></i>

                                        <div class="">
                                            {{ Form::textarea('problem_by_customer', ($settings)?$settings->problem_by_customer:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('problem_by_customer') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 form-group ">
                                    <div class="form-group  {{ $errors->has('condition_of_product') ? 'has-error' : '' }}">
                                        <label class="">
                                            Condition Of The Product</label>
                                        <i class="fa fa-question-circle  btn btn-outline-primary float-right btn-sm mb-2" role="button" data-toggle="tooltip" data-trigger="click"
                                           title="Add comma (,) separated multiple Condition Of The Product, to be used in job sheet Ex: Item 1, Item 2, Item 3"></i>

                                        <div class="">
                                            {{ Form::textarea('condition_of_product', ($settings)?$settings->condition_of_product:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('condition_of_product') }}</span>
                                        </div>
                                    </div>
                                </div>




                                <div class="col-md-12 form-group ">
                                    <div class="form-group  {{ $errors->has('terms_conditions') ? 'has-error' : '' }}">
                                        <label class="">
                                            Repair terms & conditions</label>
                                        <div class="">
                                            {{ Form::textarea('terms_conditions', ($settings)?$settings->terms_conditions:null, ['class' => 'form-control summernote form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('terms_conditions') }}</span>
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
    </script>
@endsection
