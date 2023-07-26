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
                            <a href="" class="text-muted">Manage pinVersion</a>
                        </li>
                        <li class="breadcrumb-item text-muted">
                            Edit pinVersion
                        </li>
                        <li class="breadcrumb-item text-muted">
                            {{--{{ $category->name }}--}}
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
                        <h3 class="card-label">pinVersion Edit Form
                            <i class="mr-2"></i>
                            <small class="">try to scroll the page</small>
                        </h3>

                    </div>
                    <div class="card-toolbar">

                        <a href="{{ route('pinVersionInfo.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                            <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

                        <div class="btn-group">
                            <a href="javascript:void(0);" onclick="validated();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                                <i class="ki ki-check icon-sm"></i>Update</a>


                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.partials._messages')
                    <!--begin::Form-->
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="my-5">
                                {{ Form::model($pinVersion, ['method' => 'PATCH','route' => ['pinVersionInfo.update', $pinVersion->id],'class'=>'form form-horizontal','role'=>'form',"id"=>"pinVersion_update_form" ,'enctype'=>'multipart/form-data']) }}
                                @csrf

                                <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Title</label>
                                    <div class="col-9">
                                        {{--{{ Form::select('categories[]',$categories, null, ['class' => 'no-padding select-category ','multiple'=>'multiple']) }}--}}
                                        {{ Form::text('title', null, ['class' => 'form-control form-control-solid','id'=>'title','placeholder'=>'Enter Title','required'=>'true']) }}

                                        {{--{{ Form::select('categories[]', $categories, ['class' => 'demoInputBox form-control select-category','id'=>'category'])}}--}}
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    </div>
                                </div>
                               
                            
                                <div class="form-group row {{ $errors->has('description') ? 'has-error' : '' }}">
                                    <label class="col-3 required">description</label>
                                    <div class="col-9">
                                        {{ Form::text('description', null, ['class' => 'form-control form-control-solid','id'=>'description','placeholder'=>'Enter description','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    </div>
                                </div>


                           
                            <div class="event_file">
                                {{Form::close()}}


                                
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
       
        $("#pinVersion_update_form").validate({
            
            errorClass: "error fail-alert",
            validClass: "valid success-alert",
            rules: {
                title: {
                    required: true

                },
                description: {
                    required: true

                }
               
              
            },
            messages: {
                title: {
                 required: "Please enter title"
                },
                description: {
                    required: "Please select description"
                }
               

            }
        });
        if ($('#pinVersion_update_form').valid()) // check if form is valid
        {
            $("#pinVersion_update_form").submit();
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
            $(".event_file").html(data);
            //localStorage.setItem("file", done.success);

        }
    };
</script>
@endsection