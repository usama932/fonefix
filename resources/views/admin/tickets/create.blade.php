@extends('admin.layouts.master')
@section('stylesheets')
<link href="{{asset('assets/css/summernote/summernote.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/summernote/summernote-bs3.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.min.css') }}">
<!-- <link href="{{asset('assets/css/select2.css')}}" rel="stylesheet"> -->

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
<section class="content">
    <div class="row">
        <div class="col-12" id="accordion">
            <div class="card card-primary    card-outline">
                <div class="d-block w-100" data-toggle="">
                    <div class="card-header">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Add Ticket</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class=" float-sm-right">
                                    <a class="btn btn-light-primary btn-sm" href="{{ route('tickets.index') }}">
                                        <i class="ki ki-long-arrow-back icon-sm"></i>
                                        Back
                                    </a> &nbsp;&nbsp;&nbsp;
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="collapseNine" class="" data-parent="#">
                    <div class="card-body">
                        <div class="info">
                            @include("admin.partials._messages")
                            {{ Form::open([ 'route' => 'tickets.store','class'=>'form' ,"id"=>"ticket_add_form", 'enctype'=>'multipart/form-data']) }}
                            @csrf
                            <div class="my-5">
                                <div class="form-group row {{ $errors->has('vehicle') ? 'has-error' : '' }}">
                                    <label class="col-3 my-aut form-label required">Vehicle #</label>
                                    <div class="col-9">
                                        {{--{{ Form::select('categories[]',$categories, null, ['class' => 'form-control ','multiple'=>'multiple']) }}--}}
                                        <select class="form-control select2" name="vehicle_id" id="vehicle_id" required>
                                            <option selected disabled value="">Select a Vehicle</option>
                                            @foreach ($vehicles as $vehicle)
                                            <option value="{{ $vehicle->id }}" {{ $vehicle->id === old('vehicle_id') ? 'selected' : '' }}>{{ $vehicle->vehicle_no }}</option>
                                            @endforeach
                                        </select>
                                        <br>
                                        {{--{{ Form::select('categories[]', $categories, ['class' => 'demoInputBox form-control select-category','id'=>'category'])}}--}}
                                        <span id="vehicle_id" class="text-danger">{{ $errors->first('vehicle_id') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('complaint') ? 'has-error' : '' }}">
                                    <label class="col-3 my-aut form-label required">Fleet Ticket Complaint</label>
                                    <div class="col-9">
                                        {{--{{ Form::select('categories[]',$categories, null, ['class' => 'no-padding select-category ','multiple'=>'multiple']) }}--}}
                                        <select class="form-control select2" name="complaint" id="complaint" required>
                                            <option selected disabled value="">Select a Complaint</option>
                                            <option value="Damage">Damage</option>
                                            <option value="Defect">Defect</option>
                                            <option value="Maintenance">Maintenance</option>
                                        </select>
                                        {{--{{ Form::select('categories[]', $categories, ['class' => 'demoInputBox form-control select-category','id'=>'category'])}}--}}
                                        <span id="complaint-error" class="text-danger">{{ $errors->first('complaint_id') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('remarks') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Remarks</label>
                                    <div class="col-9">
                                        {{--{{ Form::textarea('content', null, ['class' => 'summernote','id'=>'remarks_id','placeholder'=>'Enter content of Article.']) }}--}}
                                        {{ Form::textarea('remarks', null, ['class' => 'form-control form-control-solid summernote','id'=>'remarks_id','placeholder'=>'Enter Notes']) }}
                                        <span id ="remarks" class="text-danger">{{ $errors->first('remarks') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('Vehicle_mileage') ? 'has-error' : '' }}">
                                    <label class="col-3 my-aut form-label required">Vehicle Mileage</label>
                                    <div class="col-9">
                                        {{--{{ Form::dropzone('image', 'Featured Image: *', ['for'=>'Featured Image','class' => 'col-sm-3 control-label']) }}--}}
                                        {{ Form::number('vehicle_mileage', null, ['class' => 'form-control form-control-solid','id'=>'vehicle_mileage','placeholder'=>'Enter Mileage']) }}
                                        <span class="text-danger">{{ $errors->first('Vehicle_mileage') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('status') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Vehicle In-Service/Out-of-Service Status:</label>
                                    <div>
                                        <label class="col">Out Of Service</label>
                                        {{ Form::radio('status', 0, ['class' => 'form-control form-control-solid','id'=>'status', 'value'=>0]) }}
                                    </div>
                                    <div>
                                        <label class="col">In-Service</label>
                                        {{ Form::radio('status', 1, ['class' => 'form-control form-control-solid','id'=>'status', 'value'=>1]) }}

                                    </div>
                                </div>
                                <div class="files">
                                    {{Form::close()}}
                                    <div class="form-group row {{ $errors->has('image') ? 'has-error' : '' }}">
                                        <label class="col-3 my-auto ">Images</label>
                                        <div class="col-9">
                                            {{--{{ Form::dropzone('image', 'Featured Image: *', ['for'=>'Featured Image','class' => 'col-sm-3 control-label']) }}--}}
                                            <form action="{{ route('admin.saveTicketImage') }}" file="true" enctype='multipart/form-data' class='dropzone ' id='imageUpload'>
                                                {{ csrf_field() }}
                                                <input hidden name="image" id="image" typr="text">
                                            </form>
                                            <span class="text-danger">{{ $errors->first('image') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label class="col-3"></label>
                            <div class="col-9">
                                <a class="btn btn-primary btn-lg " href="javascript:void(0);" onclick="validated();" id="kt_btn">
                                    <i class="ki ki-check icon-sm"></i>Save</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>
@endsection
@section('scripts')
<script type="text/javascript" charset="utf8" src="{{ asset('assets/js/dropzone.min.js') }}"></script>

<script src="{{asset('assets/js/summernote/summernote.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('assets/js/select2.min.js')}}"></script>
<script >

    // $(document).ready(function() {
    //   $("#article_add_form").validate();
    // });
    function validated(){
        $("#content").click(function(){
        validate();
        return false;
    })
        $("#ticket_add_form").validate({
                    ignore: ".note-editor *",
                    errorClass: "error fail-alert",
                    validClass: "valid success-alert",
                    rules: {
                        vehicle_id: {
                            required: true

                        },
                        complaint_id: {
                            required: true

                        },
                        remarks: {
                            required: true

                        },
                        vehicle_mileage: {
                            required: true,
                            minlength: '5'

                        }

                    },
                    messages: {
                        vehicle_id: {

                            required: "Please select vehicle"

                        },
                        complaint_id: {
                            required: "Please select complaint"

                        },
                        remarks: {
                            required: "Please enter remarks"

                        },
                        vehicle_mileage: {
                            required: "Please enter vehicle mileage"

                        }
                    },

                    errorPlacement: function(error, element) {
                if (element.attr("name") == "vehicle_id") {
                    error.insertAfter("#vehicle-error");
                } else if (element.attr("name") == "complaint_id") {
                    error.insertAfter("#complaint-error");
                } else if (element.attr("name") == "remarks") {
                    error.insertAfter("#remarks-error");
                }
                else {
                    error.insertAfter(element);
                }
            }

                    });
        if ($('#ticket_add_form').valid()) // check if form is valid
        {
            $("#ticket_add_form").submit();
        }
        else
        {
            return;
        }
    }






</script>
<script>


    $(document).ready(function() {
        $(".select2").select2({

           allowClear: true
       });


       $('select').select2({}).on("change", function (e) {
         $(this).valid()
        });


        $('.summernote').summernote();
        $(".select-category").select2({
            placeholder: "Select Categories",
            allowClear: true
        });
        $(".select-tag").select2({
            placeholder: "Select Tags",
            allowClear: true
        });

    });
    Dropzone.options.imageUpload = {
        uploadMultiple: false,
        renameFile: function(file) {
            var dt = new Date();
            var time = dt.getTime();
            return time + file.name;
        },
        parallelUploads: 5,
        maxFilesize: 2,
        maxFiles: 5,
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
            data += "<input type='hidden' value='" + value + "' name='uploadedImages[]' id='" + done.id + "'>";
            $(".files").append(data);
            //localStorage.setItem("file", done.success);

        }
    };
</script>
@endsection
