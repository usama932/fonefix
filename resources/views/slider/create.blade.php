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
                <a href="" class="text-muted">Manage Slider Images</a>
              </li>
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Add Slider Image</a>
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
              <h3 class="card-label">Slider Add Form
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('sliderImages.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href="{{ route('sliderImages.store') }}"  onclick="event.preventDefault(); document.getElementById('slider_add_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save</a>



              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::open([ 'route' => 'sliderImages.store','class'=>'form' ,"id"=>"slider_add_form", 'enctype'=>'multipart/form-data']) }}
              @csrf
              <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                  <div class="my-5">
                    <h3 class="text-dark font-weight-bold mb-10">Slider  Info: </h3>



                      <div class="form-group row {{ $errors->has('type') ? 'has-error' : '' }}">
                          <label class="col-3 col-form-label text-right">Select Type:</label>
                          <div class="col-6">

                              <select  class="form-control selectpicker"   data-size="7" data-live-search="true" name="type" name="papers"   id="papers_id" required >
                                  <option value="">Select Type</option>

                                  <option  value="1">Image</option>
                                  <option  value="2">Video</option>

                              </select>
                              <span class="text-danger">{{ $errors->first('type') }}</span>
                          </div>
                      </div>

                      <div id="image"  class="form-group row {{ $errors->has('image') ? 'has-error' : '' }}">
                          <label class="col-3 col-form-label text-right">File:</label>
                          <div class="col-6">
                              {{ Form::file('image', null, ['class' => 'form-control form-control-solid','id'=>'title','required'=>'true']) }}
                              <br/>
                              <span class="text-danger">{{ $errors->first('image') }}</span>
                          </div>
                      </div>


{{--                      <div class="form-group row {{ $errors->has('image') ? 'has-error' : '' }}">--}}
{{--                          <label class="col-3 col-form-label text-right">Video:</label>--}}
{{--                          <div class="col-6">--}}
{{--                              {{ Form::file('video', null, ['class' => 'form-control form-control-solid','id'=>'title','required'=>'true']) }}--}}
{{--                              <span class="text-danger">{{ $errors->first('image') }}</span>--}}
{{--                          </div>--}}
{{--                      </div>--}}



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
        function myFunction() {
            var x = document.getElementById("image");
            if (x.style.display === "none") {
                x.style.display = "block";
            } else {
                x.style.display = "none";
            }
        }
    </script>
@endsection
