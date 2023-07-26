@extends('admin.layouts.master')
@section('title',$title)
@section('stylesheet')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
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
                <a href="" class="text-muted">Whatsapp Template</a>
              </li>
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Add Whatsapp Template</a>
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
              <h3 class="card-label">Add Whatsapp Template
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('whatsapp.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href="{{ route('whatsapp.store') }}"  onclick="event.preventDefault(); document.getElementById('templates_add_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save</a>
              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::open([ 'route' => 'whatsapp.store','class'=>'form' ,"id"=>"templates_add_form", 'enctype'=>'multipart/form-data']) }}
              @csrf
              <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                  <div class="my-5">
                    <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                      <label class="col-3">Name</label>
                      <div class="col-9">
                        {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'title','placeholder'=>'Enter Name','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('title') }}</span>
                      </div>
                    </div>

                    <div class="form-group row {{ $errors->has('sms_template') ? 'has-error' : '' }}">
                        <label class="col-3">Whatsapp Template</label>
                        <div class="col-9">
                          {{ Form::textarea('sms_template', null, ['class' => 'form-control form-control-solid','id'=>'sms_template','placeholder'=>'Enter Sms Template','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('sms_template') }}</span>
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('type') ? 'has-error' : '' }}">
                        <label class="col-3">Whatsapp Service For</label>
                        <div class="col-9">
                            <select class="form-control  select-2-multiple" name="type" id="type" >
                                <option value="1">Invoice</option>
                                <option value="2">Wel Come Client </option>
                                <option value="3">Enquiries</option>
                            </select>

                            <span class="text-danger">{{ $errors->first('type') }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 col-form-label">Shared</label>
                        <div class="col-3">
                        <span class="switch switch-outline switch-icon switch-success">
                        <label><input type="checkbox" checked="checked" id="shared" name="shared" value="1">
                          <span></span>
                        </label>
                        </span>
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label class="col-3">Available Tags</label>
                        <div class="col-9">
                            <span class="">{customer_name}, {inovice_no}, {status},  {brand}, {device}, {device_model}
                                <br>For Repair Invoice :: {jobsheet_id} ,{invoice_amount_paid} ,{pdf}
                              <br>  For Enquiries:: {enquired_products},{enquired_product},{product_available},{pdf},
                               <br> For General Invoice :: {genral_invoice_amount},{invoice_amount_paid},{genral_invoice_pending},{pdf}
                            </span>
                        </div>
                    </div>
                  </div>

                </div>
                <div class="col-xl-2"></div>
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
<script>

$("#type").select2();
</script>

@endsection
