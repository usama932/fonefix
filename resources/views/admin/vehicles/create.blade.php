@extends('admin.layouts.master')
@section('stylesheets')
<link href="{{asset('assets/css/summernote/summernote.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/summernote/summernote-bs3.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.min.css') }}">
<link href="{{asset('assets/css/select2.css')}}" rel="stylesheet">

<style>
    .form .required:after {
        content: " *";
        color: red;
        font-weight: 80%;
    }





    p.note {
        font-size: 1rem;
        color: red;
    }



    label {
        width: 300px;
        font-weight: bold;
        display: inline-block;
        margin-top: 20px;
    }

    label span {
        font-size: 1rem;
    }

    label.error {
        color: red;
        font-size: 1rem;
        display: block;
        margin-top: 5px;
    }

    input.error {
        border: 1px dashed red;
        font-weight: 300;
        color: red;
    }
</style>
@endsection
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
                            <a href="" class="text-muted">Manage Vehicle</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            <a href="" class="text-muted">Add Vehicle</a>
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
                <div class="card-header" >
                    <div class="card-title">
                        <h3 class="card-label">Vehicle Add Form
                            <i class="mr-2"></i>
                            <small class="">try to scroll the page</small>
                        </h3>

                    </div>
                    <div class="card-toolbar">

                        <a href="{{ route('vehicles.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                            <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

                        <div class="btn-group">
                            <a href="javascript:void(0);" onclick="validated();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                                <i class="ki ki-check icon-sm"></i>Save</a>


                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.partials._messages')
                    <!--begin::Form-->
                    <div class="row">
                        <div class="col-xl-2"></div>
                        <div class="col-xl-8">
                            <div class="my-5">
                                {{ Form::open([ 'route' => 'vehicles.store','class'=>'form' ,"id"=>"vehicle_add_form", 'enctype'=>'multipart/form-data']) }}
                                @csrf
                                <div class="form-group row {{ $errors->has('vehicle_no') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Vehicle #</label>
                                    <div class="col-9">
                                        {{ Form::text('vehicle_no', null, ['class' => 'form-control form-control-solid','id'=>'vehicle_no','placeholder'=>'Enter Vehicle #','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('vehicle_no') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('Registration_no') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Registration</label>
                                    <div class="col-9">
                                        {{--{{ Form::select('categories[]',$categories, null, ['class' => 'no-padding select-category ','multiple'=>'multiple']) }}--}}
                                        {{ Form::text('Registration_no', null, ['class' => 'form-control form-control-solid','id'=>'registration_no','placeholder'=>'Enter Registration No','required'=>'true']) }}

                                        {{--{{ Form::select('categories[]', $categories, ['class' => 'demoInputBox form-control select-category','id'=>'category'])}}--}}
                                        <span class="text-danger">{{ $errors->first('Registration_no') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('year') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Year</label>
                                    <div class="col-9">
                                        {{--<div class="input-group date">
                                                {{ Form::text('year', null, ['class' => 'form-control form-control-solid','id'=>' year kt_datepicker_3','placeholder'=>'Enter Year','required'=>'true']) }}
                                        <div class="input-group-append">
                                            <span class="input-group-text">
                                                <i class="la la-calendar"></i>
                                            </span>
                                        </div>
                                    </div>--}}
                                    {{ Form::number('year', null, ['class' => 'form-control form-control-solid','id'=>'year','placeholder'=>'Enter Year','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('year') }}</span>
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('make') ? 'has-error' : '' }}">
                                <label class="col-3 required">Make</label>
                                <div class="col-9">
                                    {{ Form::text('make', null, ['class' => 'form-control form-control-solid','id'=>'make','placeholder'=>'Enter Make','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('make') }}</span>
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('model') ? 'has-error' : '' }}">
                                <label class="col-3 required">Model</label>
                                <div class="col-9">
                                    {{ Form::text('model', null, ['class' => 'form-control form-control-solid','id'=>'model','placeholder'=>'Enter Model','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('model') }}</span>
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('notes') ? 'has-error' : '' }}">
                                <label class="col-3 required">Notes</label>
                                <div class="col-9">
                                    {{--{{ Form::textarea('content', null, ['class' => 'summernote','id'=>'content','placeholder'=>'Enter content of Article.']) }}--}}
                                    {{ Form::textarea('notes', null, ['class' => 'form-control form-control-solid summernote','id'=>'notes','placeholder'=>'Enter Notes', 'required'=> 'true']) }}
                                    <span id="notes-error" class="text-danger">{{ $errors->first('notes') }}</span>
                                </div>
                            </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label form-label required">Active</label>
                                    <div class="col-3">
                                         <span class="switch switch-outline switch-icon switch-success">
                                          <label><input type="checkbox" checked="checked" name="active" value="1">
                                            <span></span>
                                          </label>
                                        </span>
                                    </div>
                                </div>
                            <div class="event_file">
                                {{Form::close()}}
                                <div class="form-group row {{ $errors->has('Vehicle Image') ? 'has-error' : '' }}">
                                    <label class="col-3 my-auto">Vehicle Image</label>
                                    <div class="col-9">
                                        <form action="{{ route('admin.saveVehicleImage') }}" file="true" enctype='multipart/form-data' class='dropzone' id='clean'>
                                            {{ csrf_field() }}
                                        </form>
                                        <span class="text-danger">{{ $errors->first('Vehicle Image') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
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
<script type="text/javascript" charset="utf8" src="{{ asset('assets/js/dropzone.min.js') }}"></script>
<script src="{{asset('assets/js/summernote/summernote.min.js')}}"></script>

<!-- Select2 -->
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script>
    // $(document).ready(function() {
    //   $("#article_add_form").validate();
    // });
    function validated() {

        $("#vehicle_add_form").validate({
            ignore: ".note-editor *",
            errorClass: "error fail-alert",
            validClass: "valid success-alert",
            rules: {
                vehicle_no: {
                    required: true

                },
                registration_no: {
                    required: true

                },
                year: {
                    required: true

                },
                make: {
                    required: true


                },
                model: {
                    required: true
                },
                notes: {
                    required:true
                }

            },
            messages: {
                vehicle_no: {

                    required: "Please enter vehicle number"

                },
                registration_no: {
                    required: "Please select registration number"

                },
                year: {
                    required: "Please enter year"

                },
                make: {
                    required: "Please enter make"

                },
                model: {
                    required: "Please enter model number"

                },
                notes: {
                    required: "Please enter notes"

                }

            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "notes") {
                    error.insertAfter("#notes-error");
                }else {
                    error.insertAfter(element);
                }
            }

        });
        if ($('#vehicle_add_form').valid()) // check if form is valid
        {
            $("#vehicle_add_form").submit();
        } else {
            return;
        }
    }
</script>
<script>
    $(document).ready(function() {

        $('.summernote').summernote();

    });
    Dropzone.options.clean = {
        uploadMultiple: false,
        maxFiles: 1,
        addRemoveLinks: true,
        dictRemoveFile: 'Remove',
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        removedfile: function(file, done) {
            var name = file.name;
            if (name) {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('input[name="_token"]').val()
                    }, //passes the current token of the page to image url
                    type: 'GET',
                    url: "remove/" + name, //passes the image name to  the method handling this url to //remove file
                    dataType: 'json',
                    success: function(data) {
                        var id = "#";
                        id += data.id;
                        console.log(id);
                        $(id).remove();


                    }
                });
            }

            var _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file.previewElement) : void 0;
        },
        success: function(file, done) {
            var data = "";
            var value = "";
            value += done.imageName;

            data += "<input type='hidden' value='" + value + "' name='image' id='" + done.id + "'>";

            $(".event_file").append(data);

            //localStorage.setItem("file", done.success);

        }
    };
</script>
@endsection
