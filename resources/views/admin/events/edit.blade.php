@extends('admin.layouts.master')
@section('stylesheets')
<link href="{{asset('assets/css/summernote/summernote.css')}}" rel="stylesheet">
<link href="{{asset('assets/css/summernote/summernote-bs3.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.min.css') }}">

<!-- <link href="{{asset('assets/css/select2.css')}}" rel="stylesheet"> -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->

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
        /* border: 1px dashed red; */
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
                                <h1>Update Event</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class=" float-sm-right">
                                    <a class="btn btn-light-primary btn-sm" href="{{ route('events.index') }}">
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
                            <div class="my-5">
                                {{ Form::model($events, ['method' => 'PATCH','route' => ['events.update', $events->id],'class'=>'form form-horizontal','role'=>'form',"id"=>"event_update_form" ,'enctype'=>'multipart/form-data']) }}
                                @csrf

                                <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Title</label>
                                    <div class="col-9">
                                        {{ Form::text('title', null, ['class' => 'form-control form-control-solid','id'=>'title','placeholder'=>'Enter Title','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('title') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('User') ? 'has-error' : '' }}">
                                    <label class="col-3  ">Notify User</label>
                                    <div class="col-9">
                                        {{--{{ Form::select('categories[]',$categories, null, ['class' => 'no-padding select-category ','multiple'=>'multiple']) }}--}}
                                        <select class="form-control form-control-solid" name="user_id">
                                            <option selected disabled value="">Select User</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}" @if($user->id == $events->user_id) selected @endif>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        {{--{{ Form::select('categories[]', $categories, ['class' => 'demoInputBox form-control select-category','id'=>'category'])}}--}}
                                        <span class="text-danger">{{ $errors->first('User') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('category') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required ">Category</label>
                                    <div class="col-9">
                                        {{--{{ Form::select('categories[]',$categories, null, ['class' => 'no-padding select-category ','multiple'=>'multiple']) }}--}}
                                        <select class="form-control select2 form-control-solid" name="event_category_id" id="event_category_id" required>
                                            <option selected diabled value="">Select a Category</option>
                                            @foreach ($event_categories as $event_category)
                                            <option value="{{ $event_category->id }}" {{ $event_category->id === old('event_category_id') ? 'selected' : ''}} @if($event_category->id == $events->event_category_id) selected @endif>{{ $event_category->name }}</option>
                                            @endforeach
                                        </select>
                                        {{--{{ Form::select('categories[]', $categories, ['class' => 'demoInputBox form-control select-category','id'=>'category'])}}--}}
                                        <span id="category-error" class="text-danger">{{ $errors->first('category') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('narrative') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required ">narrative</label>
                                    <div class="col-9">
                                        {{--{{ Form::textarea('content', null, ['class' => 'summernote','id'=>'Slug','placeholder'=>'Enter content of Article.']) }}--}}
                                        {{ Form::textarea('narrative', null, ['class' => 'form-control form-control-solid summernote','id'=>'narrative','placeholder'=>'Enter content of Narrative','required' =>true]) }}
                                        <span id="narrative-error" class="text-danger">{{ $errors->first('narrative') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 form-label required ">Time Duration </label>
                                    <div class="col-9 files">
                                        {{--{{ Form::checkbox('priority', null, ['class' => 'radio','id'=>'Slug']) }}--}}
                                        <div class="row">

                                            <div class="col-xl-6 col-lg-4 col-md-9 col-sm-12  ">
                                                <div class=" input-group date  {{ $errors->has('start_date') ? 'has-error' : '' }}" id="kt_datetimepicker_7_1" data-target-input="nearest">

                                                    <!-- {{ Form::text('start_time', null, ['class' => 'form-control datetimepicker-input ','data-target'=>'#kt_datetimepicker_6_1' ,'placeholder'=>'Start Date & Time']) }} -->

                                                    <input type="text" name="start_date" value="{{date('m/d/Y',strtotime($events->start_time)) }}" class="form-control datetimepicker-input" placeholder="Select date &amp; time"  data-target="#kt_datetimepicker_7_1" data-toggle="datetimepicker">
                                                    <div class="input-group-append " data-target="#kt_datetimepicker_7_1" data-toggle="datetimepicker">
                                                        <span class="input-group-text">
                                                            <i class="ki ki-calendar"></i>
                                                        </span>
                                                    </div>




                                                </div>
                                                <span class="text-danger">{{ $errors->first('start_date') }}</span>


                                            </div>


                                            <div class="  col-lg-6 col-md-9 col-sm-12 {{ $errors->has('start_time') ? 'has-error' : '' }}  ">


                                                <!-- <input class="form-control " type="text" id="start_time" step="900" name="start_time" required /> -->
                                                <select class="form-control " name="start_time" id="start_time">

                                                    <option value="{{date('h:i A',strtotime($events->start_time)) }}">{{date('h:i A',strtotime($events->start_time)) }}</option>

                                                    <option value="5:00">5:00 AM</option>
                                                    <option value="5:15">5:15 AM</option>
                                                    <option value="5:30">5:30 AM</option>
                                                    <option value="5:45">5:45 AM</option>

                                                    <option value="6:00">6:00 AM</option>
                                                    <option value="6:15">6:15 AM</option>
                                                    <option value="6:30 ">6:30 AM</option>
                                                    <option value="6:45 ">6:45 AM</option>

                                                    <option value="7:00">7:00 AM</option>
                                                    <option value="7:15 ">7:15 AM</option>
                                                    <option value="7:30 ">7:30 AM</option>
                                                    <option value="7:45 ">7:45 AM</option>

                                                    <option value="8:00 ">8:00 AM</option>
                                                    <option value="8:15 ">8:15 AM</option>
                                                    <option value="8:30 ">8:30 AM</option>
                                                    <option value="8:45 ">8:45 AM</option>

                                                    <option value="9:00 ">9:00 AM</option>
                                                    <option value="9:15 ">9:15 AM</option>
                                                    <option value="9:30 ">9:30 AM</option>
                                                    <option value="9:45 ">9:45 AM</option>

                                                    <option value="10:00 ">10:00 AM</option>
                                                    <option value="10:15 ">10:15 AM</option>
                                                    <option value="10:30">10:30 AM</option>
                                                    <option value="10:45 ">10:45 AM</option>

                                                    <option value="11:00 ">11:00 AM</option>
                                                    <option value="11:15 ">11:15 AM</option>
                                                    <option value="11:30 ">11:30 AM</option>
                                                    <option value="11:45 ">11:45 AM</option>

                                                    <option value="12:00 ">12:00 PM</option>
                                                    <option value="12:15 ">12:15 PM</option>
                                                    <option value="12:30 ">12:30 PM</option>
                                                    <option value="12:45 ">12:45 PM</option>

                                                    <option value="1:00 ">1:00 PM</option>
                                                    <option value="1:15 ">1:15 PM</option>
                                                    <option value="1:30 ">1:30 PM</option>
                                                    <option value="1:45 ">1:45 PM</option>

                                                    <option value="2:00 ">2:00 PM</option>
                                                    <option value="2:15 ">2:15 PM</option>
                                                    <option value="2:30 ">2:30 PM</option>
                                                    <option value="2:45 ">2:45 PM</option>

                                                    <option value="3:00 ">3:00 PM</option>
                                                    <option value="3:15">3:15 PM</option>
                                                    <option value="3:30">3:30 PM</option>
                                                    <option value="3:45 ">3:45 PM</option>

                                                    <option value="4:00">4:00 PM</option>
                                                    <option value="4:15 ">4:15 PM</option>
                                                    <option value="4:30">4:30 PM</option>
                                                    <option value="4:45 ">4:45 PM</option>

                                                    <option value="5:00 ">5:00 PM</option>
                                                    <option value="5:15 ">5:15 PM</option>
                                                    <option value="5:30 ">5:30 PM</option>
                                                    <option value="5:45 ">5:45 PM</option>

                                                    <option value="6:00 ">6:00 PM</option>
                                                    <option value="6:15 ">6:15 PM</option>
                                                    <option value="6:30 ">6:30 PM</option>
                                                    <option value="6:45 ">6:45 PM</option>

                                                    <option value="7:00 ">7:00 PM</option>
                                                    <option value="7:15 ">7:15 PM</option>
                                                    <option value="7:30 ">7:30 PM</option>
                                                    <option value="7:45 ">7:45 PM</option>

                                                    <option value="8:00 ">8:00 PM</option>
                                                    <option value="8:15 ">8:15 PM</option>
                                                    <option value="8:30 ">8:30 PM</option>
                                                    <option value="8:45 ">8:45 PM</option>

                                                    <option value="9:00 ">9:00 PM</option>
                                                    <option value="9:15 ">9:15 PM</option>
                                                    <option value="9:30 ">9:30 PM</option>
                                                    <option value="9:45 ">9:45 PM</option>

                                                    <option value="10:00 ">10:00 PM</option>
                                                    <option value="10:15 ">10:15 PM</option>
                                                    <option value="10:30 ">10:30 PM</option>
                                                    <option value="10:45 ">10:45 PM</option>

                                                    <option value="11:00 ">11:00 PM</option>
                                                    <option value="11:15 ">11:15 PM</option>
                                                    <option value="11:30 ">11:30 PM</option>
                                                    <option value="11:45 ">11:45 PM</option>
                                                </select>

                                                <span id="start_time_error" class="text-danger">{{ $errors->first('start_time') }}</span>




                                            </div>

                                            <div class="col-xl-6 col-lg-4 col-md-9 col-sm-12">
                                                <div class="input-group date {{ $errors->has('end_date') ? 'has-error' : '' }}" id="kt_datetimepicker_7_2" data-target-input="nearest">
                                                    <!-- {{ Form::text('end_time', null, ['class' => 'form-control  datetimepicker-input','data-target'=>'#kt_datetimepicker_6_2', 'placeholder'=>'End Date & Time']) }} -->

                                                    <input type="text" name="end_date" id="end_date" class="form-control datetimepicker-input" placeholder="Select date &amp; time" value="{{date('m/d/Y ',strtotime($events->end_time)) }}" data-target="#kt_datetimepicker_7_2" data-toggle="datetimepicker">
                                                    <div class="input-group-append" data-target="#kt_datetimepicker_7_2" data-toggle="datetimepicker">
                                                        <span class="input-group-text">
                                                            <i class="ki ki-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="text-danger">{{ $errors->first('end_date') }}</span>

                                            </div>


                                            <div class="  col-lg-6 col-md-9 col-sm-12 {{ $errors->has('start_time') ? 'has-error' : '' }}  ">


                                                <!-- <input class="form-control " type="text" id="start_time" step="900" name="start_time" required /> -->
                                                <select class="form-control " name="end_time" id="end_time">

                                                    <option value="{{date('h:i A',strtotime($events->end_time)) }}">{{date('h:i A',strtotime($events->end_time)) }}</option>

                                                    <option value="5:00">5:00 AM</option>
                                                    <option value="5:15">5:15 AM</option>
                                                    <option value="5:30">5:30 AM</option>
                                                    <option value="5:45">5:45 AM</option>

                                                    <option value="6:00">6:00 AM</option>
                                                    <option value="6:15">6:15 AM</option>
                                                    <option value="6:30 ">6:30 AM</option>
                                                    <option value="6:45 ">6:45 AM</option>

                                                    <option value="7:00">7:00 AM</option>
                                                    <option value="7:15 ">7:15 AM</option>
                                                    <option value="7:30 ">7:30 AM</option>
                                                    <option value="7:45 ">7:45 AM</option>

                                                    <option value="8:00 ">8:00 AM</option>
                                                    <option value="8:15 ">8:15 AM</option>
                                                    <option value="8:30 ">8:30 AM</option>
                                                    <option value="8:45 ">8:45 AM</option>

                                                    <option value="9:00 ">9:00 AM</option>
                                                    <option value="9:15 ">9:15 AM</option>
                                                    <option value="9:30 ">9:30 AM</option>
                                                    <option value="9:45 ">9:45 AM</option>

                                                    <option value="10:00 ">10:00 AM</option>
                                                    <option value="10:15 ">10:15 AM</option>
                                                    <option value="10:30">10:30 AM</option>
                                                    <option value="10:45 ">10:45 AM</option>

                                                    <option value="11:00 ">11:00 AM</option>
                                                    <option value="11:15 ">11:15 AM</option>
                                                    <option value="11:30 ">11:30 AM</option>
                                                    <option value="11:45 ">11:45 AM</option>

                                                    <option value="12:00 ">12:00 PM</option>
                                                    <option value="12:15 ">12:15 PM</option>
                                                    <option value="12:30 ">12:30 PM</option>
                                                    <option value="12:45 ">12:45 PM</option>

                                                    <option value="1:00 ">1:00 PM</option>
                                                    <option value="1:15 ">1:15 PM</option>
                                                    <option value="1:30 ">1:30 PM</option>
                                                    <option value="1:45 ">1:45 PM</option>

                                                    <option value="2:00 ">2:00 PM</option>
                                                    <option value="2:15 ">2:15 PM</option>
                                                    <option value="2:30 ">2:30 PM</option>
                                                    <option value="2:45 ">2:45 PM</option>

                                                    <option value="3:00 ">3:00 PM</option>
                                                    <option value="3:15">3:15 PM</option>
                                                    <option value="3:30">3:30 PM</option>
                                                    <option value="3:45 ">3:45 PM</option>

                                                    <option value="4:00">4:00 PM</option>
                                                    <option value="4:15 ">4:15 PM</option>
                                                    <option value="4:30">4:30 PM</option>
                                                    <option value="4:45 ">4:45 PM</option>

                                                    <option value="5:00 ">5:00 PM</option>
                                                    <option value="5:15 ">5:15 PM</option>
                                                    <option value="5:30 ">5:30 PM</option>
                                                    <option value="5:45 ">5:45 PM</option>

                                                    <option value="6:00 ">6:00 PM</option>
                                                    <option value="6:15 ">6:15 PM</option>
                                                    <option value="6:30 ">6:30 PM</option>
                                                    <option value="6:45 ">6:45 PM</option>

                                                    <option value="7:00 ">7:00 PM</option>
                                                    <option value="7:15 ">7:15 PM</option>
                                                    <option value="7:30 ">7:30 PM</option>
                                                    <option value="7:45 ">7:45 PM</option>

                                                    <option value="8:00 ">8:00 PM</option>
                                                    <option value="8:15 ">8:15 PM</option>
                                                    <option value="8:30 ">8:30 PM</option>
                                                    <option value="8:45 ">8:45 PM</option>

                                                    <option value="9:00 ">9:00 PM</option>
                                                    <option value="9:15 ">9:15 PM</option>
                                                    <option value="9:30 ">9:30 PM</option>
                                                    <option value="9:45 ">9:45 PM</option>

                                                    <option value="10:00 ">10:00 PM</option>
                                                    <option value="10:15 ">10:15 PM</option>
                                                    <option value="10:30 ">10:30 PM</option>
                                                    <option value="10:45 ">10:45 PM</option>

                                                    <option value="11:00 ">11:00 PM</option>
                                                    <option value="11:15 ">11:15 PM</option>
                                                    <option value="11:30 ">11:30 PM</option>
                                                    <option value="11:45 ">11:45 PM</option>
                                                </select>

                                                <span id="end_time_error" class="text-danger">{{ $errors->first('end_time') }}</span>




                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <div class="event_file"></div>
                                {{Form::close()}}
                                <div class="form-group row {{ $errors->has('File') ? 'has-error' : '' }}">
                                    <label class="col-3 my-auto">Upload File</label>
                                    <div class="col-9">
                                        {{--{{ Form::select('categories[]',$categories, null, ['class' => 'no-padding select-category ','multiple'=>'multiple']) }}--}}
                                        <form action="{{ route('admin.saveEventFile') }}" file="true" enctype='multipart/form-data' class='dropzone' id='clean'>
                                            {{ csrf_field() }}
                                        </form>
                                        {{--{{ Form::select('categories[]', $categories, ['class' => 'demoInputBox form-control select-category','id'=>'category'])}}--}}
                                        <span class="text-danger">{{ $errors->first('File') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('File') ? 'has-error' : '' }}">
                                    <label class="col-3 my-auto"> File</label>
                                    <div class="col-3">

                                        @if(strpos($events->event_file,'.png') !== false or strpos($events->event_file,'.jpg') !== false or strpos($events->event_file,'.jpeg') !== false)
                                        <img src="{{asset('uploads/'.$events->event_file)}}" style="width:100%;" alt="Image is not found." />
                                        @else
                                        <a href="{{asset('uploads/'.$events->event_file)}}" target="_blank">{{$events->event_file}}</a>
                                        @endif

                                        <span class="text-danger">{{ $errors->first(' File') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                        <label class="col-3"></label>
                        <div class="col-9">
                            <a class="btn btn-primary btn-lg " href="javascript:void(0)" onclick="test();">
                                <i class="ki ki-check icon-sm">
                                </i>
                                Update
                            </a>
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
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> -->

{{--<script src="{{asset('assets/js/pages/crud/forms/editors/summernote.js')}}"></script>--}}
<!-- Select2 -->
<!-- <script src="{{asset('assets/js/select2.min.js')}}"></script> -->
<script src="{{asset('assets/js/pages/crud/forms/widgets/bootstrap-datetimepicker.js')}}"></script>
<script src="{{asset('assets/js/pages/crud/forms/widgets/bootstrap-datetimepicker.js?v=7.2.9')}}"></script>
{{--<script src={{asset('assets/js/pages/crud/file-upload/dropzonejs.js')}}></script>--}}

<script>
    // $(document).ready(function() {
    //   $("#article_add_form").validate();
    // });
    function test() {
        $("#content").click(function() {
            validate();
            return false;
        })
        $("#event_update_form").validate({
            ignore: ".note-editor *",
            errorClass: "error fail-alert",
            validClass: "valid success-alert",
            rules: {
                title: {
                    required: true,
                    minlength: '5'
                },
                event_category_id: {
                    required: true

                },
                narrative: {
                    required: true

                },
                start_time: {
                    required: true


                },
                start_date: {
                    required: true


                },
                end_date: {
                    required: true


                },
                end_time: {
                    required: true


                }

            },
            messages: {
                title: {

                    required: "Please enter event title",
                    minlength: "Title should be at least 5 characters"
                },
                event_category_id: {
                    required: "Please  select category ",

                },
                narrative: {
                    required: "Please enter content",
                    minlength: "Content should be at least 5 characters"
                },
                start_date: {
                    required: "Please enter start date"

                },
                start_time: {
                    required: "Please select start time"

                },
                end_date: {
                    required: "Please enter end date"

                },
                end_time: {
                    required: "Please select end time"

                }
            },
            errorPlacement: function(error, element) {
                if (element.attr("name") == "start_date") {
                    error.insertAfter("#kt_datetimepicker_7_1");
                } else if (element.attr("name") == "end_date") {
                    error.insertAfter("#kt_datetimepicker_7_2");
                } else if (element.attr("name") == "event_category_id") {
                    error.insertAfter("#test123");
                } else if (element.attr("name") == "narrative") {
                    error.insertAfter("#narrative-error");
                }
                else if (element.attr("name") == "start_time") {
                    error.insertAfter("#start_time_error");
                }else if (element.attr("name") == "end_time") {
                    error.insertAfter("#end_time_error");
                }

                else {
                    error.insertAfter(element);
                }
            }
        });
        if ($('#event_update_form').valid()) // check if form is valid
        {
            $("#event_update_form").submit();
        } else {
            return;
        }
    }
</script>
<script>
    $(document).ready(function() {

        $(".select2").select2({
            allowClear: true
        });

        $('select').select2({}).on("change", function(e) {
            $(this).valid()
        });

        $('.summernote').summernote();
        $(".select-category").select2({
            placeholder: "Select Categories",
            allowClear: true
        });
    });
    Dropzone.options.clean = {
        uploadMultiple: false,
        maxFiles: 1,
        maxFilesize: 2000,
        addRemoveLinks: true,
        dictRemoveFile: 'Remove',
        acceptedFiles: ".jpeg,.jpg,.png,.gif,.csv,.txt,.ics,.xlx,.xls,.pdf",
        init: function() {
            this.on("maxfilesexceeded", function(file) {
                this.removeFile(file);
                Swal.fire({
                    title: "File Limit Exceeded",


                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Ok!"
                })
                // alert("File Limit exceeded!","error");
            });
        },
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
            data += "<input type='hidden' value='" + value + "' name='event_file' id='" + done.id + "'>";
            $(".event_file").html(data);
            //localStorage.setItem("file", done.success);

        }
    };
</script>
@endsection
