@extends('admin.layouts.master')
@section('stylesheets')
    <link href="{{asset('assets/css/summernote/summernote.css')}}" rel="stylesheet">
    <link href="{{asset('assets/css/summernote/summernote-bs3.css')}}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.min.css') }}">
    <!-- <link href="{{asset('assets/css/select2.min.css')}}" rel="stylesheet"> -->
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
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12" id="accordion">
                <div class="card card-primary    card-outline">
                    <div class="d-block w-100" data-toggle="">
                        <div class="card-header">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>Update Ticket</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class=" float-sm-right">
                                        <a class="btn btn-light-primary btn-sm"
                                           href="{{ route('tickets.index') }}">
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
                                {{ Form::model($ticket, ['method' => 'PATCH','route' => ['tickets.update', $ticket->id],'class'=>'form form-horizontal','role'=>'form',"id"=>"ticket_update_form" ,'enctype'=>'multipart/form-data']) }}


                                <div class="my-5">
                                    <div
                                        class="form-group row {{ $errors->has('vehicle') ? 'has-error' : '' }}">
                                        <label class="col-3 my-aut required">Vehicle #</label>
                                        <div class="col-9">
                                            {{--{{ Form::select('categories[]',$categories, null, ['class' => 'no-padding select-category ','multiple'=>'multiple']) }}--}}
                                            <select class="form-control select2" name="vehicle_id" id="vehicle_id"  required>
                                                <option selected disabled value="">Select a Vehicle</option>
                                                @foreach ($vehicles as $vehicle)
                                                    <option
                                                        value="{{ $vehicle->id }}"
                                                        @if($vehicle->id == $ticket->vehicle_id) selected @endif {{ $vehicle->id === old('vehicle_id') ? 'selected' : ''}}>{{ $vehicle->vehicle_no }}  </option>
                                                @endforeach
                                            </select>
                                            {{--{{ Form::select('categories[]', $categories, ['class' => 'demoInputBox form-control select-category','id'=>'category'])}}--}}
                                            <span id="vehicle-error" class="text-danger">{{ $errors->first('vehicle') }}</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row {{ $errors->has('complaint') ? 'has-error' : '' }}">
                                        <label class="col-3 my-aut required">Fleet Ticket Complaint </label>
                                        <div class="col-9">
                                            {{--{{ Form::select('categories[]',$categories, null, ['class' => 'no-padding select-category ','multiple'=>'multiple']) }}--}}
                                            <select class="form-control select2" name="complaint" id="complaint"  >
                                                <option selected  value="">Select a Complaint</option>
                                                <option value="Damage" @if($ticket->complaint == "Damage") selected @endif>Damage</option>
                                                <option value="Defect" @if($ticket->complaint == "Defect") selected @endif>Defect</option>
                                                <option value="Maintenance" @if($ticket->complaint == "Maintenance") selected @endif>Maintenance</option>
                                            </select>
                                            {{--{{ Form::select('categories[]', $categories, ['class' => 'demoInputBox form-control select-category','id'=>'category'])}}--}}
                                            <span id="complaint" class="text-danger">{{ $errors->first('complaint') }}</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row {{ $errors->has('Remarks') ? 'has-error' : '' }}">
                                        <label class="col-3 required">Remarks</label>
                                        <div class="col-9">
                                            {{ Form::textarea('remarks', null, ['class' => 'form-control form-control-solid summernote','id'=>'remarks','placeholder'=>'Enter Remarks','required' => true]) }}
                                            <span id="remarks-error"
                                                class="text-danger">{{ $errors->first('Remarks') }}</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row {{ $errors->has('Vehicle_mileage') ? 'has-error' : '' }}">
                                        <label class="col-3 my-aut">Vehicle Mileage</label>
                                        <div class="col-9">
                                            {{--{{ Form::dropzone('image', 'Featured Image: *', ['for'=>'Featured Image','class' => 'col-sm-3 control-label']) }}--}}
                                            {{ Form::number('vehicle_mileage', null, ['class' => 'form-control form-control-solid','id'=>'Vehicle_mileage','placeholder'=>'Enter Mileage']) }}
                                            <span
                                                class="text-danger">{{ $errors->first('Vehicle_mileage') }}</span>
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row {{ $errors->has('status') ? 'has-error' : '' }}">
                                        <label class="col-3">Vehicle In-Service/Out-of-Service Status:</label>
                                        <div>
                                            <label class="col">Out Of Service</label>
                                            <input type="radio" value="0" name="status" class="" @if($ticket->status == 0) checked @endif id="">

                                        </div>
                                        <div>
                                            <label class="col">In-Service</label>
                                            <input type="radio" value="1" name="status" class="" @if($ticket->status == 1) checked @endif id="">


                                        </div>
                                    </div>
                                    <div class="files"></div>
                                    {{Form::close()}}
                                    <div class="form-group row ">
                                        <label class="col-3 my-auto">Images</label>
                                        <div class="col-9">
                                            {{--{{ Form::dropzone('image', 'Featured Image: *', ['for'=>'Featured Image','class' => 'col-sm-3 control-label']) }}--}}
                                            @php $images = \App\Models\TicketImage::where([["type",1],["ticket_id",$ticket->id]])->get();  @endphp
                                            @foreach($images as $image)
                                                <div class="col-sm-6">
                                                    <img src="{{asset('uploads/'.$image->image)}}" style="width:100%;"
                                                         alt="Image is not found."/>
                                                    <?php $serviceId = $ticket->id; $imageId = $image->id?>
                                                    <a href='{{ route('admin.delete-ticket-image',array($serviceId,$imageId)) }}'
                                                       class='btn btn-danger btn-xs'>Remove</a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('image') ? 'has-error' : '' }}">
                                    <label class="col-3 my-auto">Upload Images</label>
                                    <div class="col-9">
                                        {{--{{ Form::dropzone('image', 'Featured Image: *', ['for'=>'Featured Image','class' => 'col-sm-3 control-label']) }}--}}
                                        <form action="{{ route('admin.saveTicketImage') }}" file="true"
                                              enctype='multipart/form-data' class='dropzone ' id='imageUpload'>
                                            {{ csrf_field() }}
                                            <input hidden name="image" id="image" typr="text">
                                        </form>
                                        <span
                                            class="text-danger">{{ $errors->first('image') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="form-group row ">
                            <label class="col-3"></label>
                            <div class="col-9">
                                <a class="btn btn-primary btn-lg " href="javascript:void(0);"
                                   onclick="validated();"
                                   id="kt_btn">
                                    <i class="ki ki-check icon-sm"></i>Update</a>
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

    $("#ticket_update_form").validate({

                errorClass: "error fail-alert",
                validClass: "valid success-alert",
                rules: {
                    vehicle_id: {
                        required: true

                    },
                    // complaint_id: {
                    //     required: true
                    //
                    // },
                    remarks: {
                        required: true

                    },
                    vehicle_mileage: {
                        required: true,


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
    if ($('#ticket_update_form').valid()) // check if form is valid
    {
        $("#ticket_update_form").submit();
    }
    else
    {
        return;
    }
}






</script>

    <script>

function testing() {
        document.getElementById('vehicle_id-error').style.display = 'none';
        // $('#category_id-error').removeAttr('required');
    }

    function complaint() {
        document.getElementById('complaint_id-error').style.display = 'none';
        // $('#category_id-error').removeAttr('required');
    }
        $(document).ready(function () {

            $(".select2").select2({

       });

       $('select').select2({}).on("change", function (e) {
         $(this).valid()
        });


            $('.summernote').summernote();
            $(".select-category").select2({
                    placeholder: "Select Categories",
                    allowClear: true
                }
            );
        });
        Dropzone.options.imageUpload = {
            uploadMultiple: false,
            renameFile: function (file) {
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
            removedfile: function (file, done) {
                var name = file.name;
                if (name) {
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('input[name="_token"]').val()
                        }, //passes the current token of the page to image url
                        type: 'GET',
                        url: "remove/" + name,  //passes the image name to  the method handling this url to //remove file
                        dataType: 'json',
                        success: function (data) {
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
            success: function (file, done) {
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
