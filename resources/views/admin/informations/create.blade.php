@extends('admin.layouts.master')
@section('title',$title)
@section('stylesheets')
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
                <a href="" class="text-muted"> Information</a>
              </li>
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Add Information</a>
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
              <h3 class="card-label">Informations Add Form
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('informations.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href="{{ route('informations.store') }}"  onclick="event.preventDefault(); document.getElementById('client_add_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save</a>



              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::open([ 'route' => 'informations.store','class'=>'form' ,"id"=>"client_add_form", 'enctype'=>'multipart/form-data']) }}
              @csrf
              <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                  <div class="my-5">
                    <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                      <label class="col-3">Title</label>
                      <div class="col-9">
                        {{ Form::text('title', null, ['class' => 'form-control form-control-solid','id'=>'title','placeholder'=>'Enter title','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('title') }}</span>
                      </div>
                    </div>
                    <div class="form-group row {{ $errors->has('shops') ? 'has-error' : '' }}">
                        <label class="col-3">Shops</label>
                        <div class="col-9">
                            <select class="form-control form-control-solid" id="myDropdown" multiple  name="shops[]" >

                                @foreach ($shops as $shop)
                                    <option  class="form-control form-control-solid" value="{{ $shop->id }}">{{ $shop->name }}</option>
                                @endforeach
                            </select>
                          <span class="text-danger">{{ $errors->first('shops') }}</span>
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('image_1') ? 'has-error' : '' }}">
                        <label class="col-3">Images.# 1</label>
                        <div class="col-9">
                            <input type="file" name="image_1" id="image_1"  class="form-control form-control-solid">
                          <span class="text-danger">{{ $errors->first('image_1') }}</span>
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('image_2') ? 'has-error' : '' }}">
                        <label class="col-3">Images.# 2</label>
                        <div class="col-9">
                            <input type="file" name="image_2" id="image_2"  class="form-control form-control-solid">
                          <span class="text-danger">{{ $errors->first('image_2') }}</span>
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('image_3') ? 'has-error' : '' }}">
                        <label class="col-3">Images.# 3</label>
                        <div class="col-9">
                            <input type="file" name="image_3" id="image_3"  class="form-control form-control-solid">
                          <span class="text-danger">{{ $errors->first('image_3') }}</span>
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('image_4') ? 'has-error' : '' }}">
                        <label class="col-3">Images.# 4</label>
                        <div class="col-9">
                            <input type="file" name="image_4" id="image_4"  class="form-control form-control-solid">
                          <span class="text-danger">{{ $errors->first('image_4') }}</span>
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#myDropdown').select2();
      });
      (function($) {
        $(document).ready(function() {
          $('#myDropdown').select2();
        });
      })(jQuery);
      $(document).ready(function() {
        $('#myDropdown').select2({
          placeholder: 'Select options', // Placeholder text
          width: '100%', // Width of the dropdown
          height: '100%', // Width of the dropdown
          // More options...
        });
      });
  </script>
@endsection
