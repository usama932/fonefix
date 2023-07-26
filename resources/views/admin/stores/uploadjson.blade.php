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
                <a href="" class="text-muted">Manage Stores</a>
              </li>
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Upload JSON File</a>
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
              <h3 class="card-label">Upload JSON File
                <i class="mr-2"></i>
            </div>
            <div class="card-toolbar">

              <a href="javascript:void(0)" onclick="window.history.back();" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>
                <a href="{{ asset('assets/sample/stores.json') }}" class="btn btn-info font-weight-bolder mr-2" download>
                <i class="ki ki-file icon-sm"></i>Download Sample File</a>
              <div class="btn-group">
                <a href="javascript:void(0);"  onclick="return validated();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save</a>



              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::open([ 'route' => 'stores.upload-json-data','class'=>'form' ,"id"=>"upload_json_file", 'enctype'=>'multipart/form-data']) }}
              @csrf
              <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                  <div class="my-5">
                    <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                      <label class="col-3 col-form-label text-right">JSON File:</label>
                      <div class="col-6">
                        {{ Form::file('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter name','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('name') }}</span>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<style>
  .error{
    color: red !important;
    margin-top: 15px !important;
  }
</style>
<script>
function validated() {

  $("#upload_json_file").validate({

    errorClass: "error fail-alert",
    validClass: "valid success-alert",
    rules: {
      name:{
        required:true
      } 
    },
    messages: {
      name: {
        required: "Please select json file",
      },
  
    },
    errorPlacement: function(error, element) {
      error.insertAfter(element);
    }

  });
  if ($('#upload_json_file').valid()) // check if form is valid
  {

    $("#upload_json_file").submit();
  } else {
    return false;
  }
}
</script>
@endsection