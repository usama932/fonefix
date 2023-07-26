@extends('admin.layouts.master')
@section('title',$title)
@section("stylesheets")
    <link rel="stylesheet" href="{{asset("pattern/patternlock.css")}}">

    <style>
        #wrapper
        {
            text-align:center;
            margin:0 auto;
            padding:0px;

        }
        .pattern_container
        {
            margin-top:20px;
        }
        .pattern_container p
        {
            margin:0px;
            color:#0B2161;
            font-size:25px;
            font-weight:bold;
        }
        #pattern1,#pattern2
        {
            margin-left:70px;
        }
        #pattern1_container input[type="button"]
        {
            background:none;
            border:none;
            margin-top:10px;
            border:1px solid #0B2161;
            color:#0B2161;
            width:310px;
            margin-left:-5px;
            height:45px;
            font-size:17px;
        }
        #pattern2_container
        {
            display:none;
        }
        .is-invalid {
            border-color: red !important;
        }
    </style>
    <style>

        .custom_radio_container {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            border: 1px solid #7e7f84;
            border-radius: 3px;
            overflow: hidden;
        }
        .custom_radio_group {
            width: 100%;
            text-align: center;
        }
        .custom_radio_input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }
        .custom_radio_label {
            background-color: #53565d;
            color: #ffffff !important;
            padding: 0;
            margin: 0;
            width: 100%;
            height: 100%;
            line-height: 22px;
            text-align: center;
            display: inline-block;
        }
        .custom_radio_input:checked~.custom_radio_label{
            background: rgb(162,212,151);
            background: linear-gradient(180deg, rgba(162,212,151,1) 0%, rgba(145,205,133,1) 100%);
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
                <a href="" class="text-muted">Manage Jobs</a>
              </li>
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Add Job</a>
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
              <h3 class="card-label">Job Add Form
                <i class="mr-2"></i>
                </h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('jobs.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href="#"   id="kt_btn" page="1" class="btn btn-success submit font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save and add parts</a>


                <a href="#"   id="kt_btn" page="2" class="btn btn-primary submit font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save</a>


                <a href="#"   id="kt_btn" page="3" class="btn btn-info submit font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save and upload docs</a>



              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::open([ 'route' => 'jobs.store','class'=>'form' ,"id"=>"client_add_form", 'enctype'=>'multipart/form-data']) }}
              @csrf
              <input type="hidden" name="pattern" id="patternVal" value="">
              <input type="hidden" name="page" id="pageVal" value="2">
              <div class="row">
                  @if(Auth::user()->role != 2)
                  <div class="col-md-3 form-group pl-6">
                      <div class="form-group  {{ $errors->has('shop') ? 'has-error' : '' }}">
                          <label class="">Business/Shop</label>
                          {{ Form::select('shop',$shops, null, ['class' => 'no-padding select-2 form-control col-lg-12','id'=>'shop']) }}
                          <span class="text-danger">{{ $errors->first('shop') }}</span>
                      </div>
                  </div>
                  @endif
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('user') ? 'has-error' : '' }}">

                          <label class="">User </label>
                          <i class="fa fa-plus-square addUser btn btn-outline-primary float-right btn-sm mb-2"></i>
                          <select name="user" id="user" class="form-control select-2 required">
                              <option value="">Select User</option>
                              @foreach($users as $key => $value)
                                  <option value="{{$value->id}}">{{$value->name}} ({{$value->phone}})</option>
                              @endforeach
                          </select>
{{--                          {{ Form::select('user',$users, null, ['class' => 'no-padding select-2 form-control col-lg-12','id'=>'shop']) }}--}}
                          <span class="text-danger">{{ $errors->first('user') }}</span>
                      </div>
                  </div>
                  <div class="col-md-5 form-group ">
                      <div class="form-group">
                          <label>Service Type</label>
                          <div class="radio-inline">
                              <label class="radio">
                                  <input type="radio" value="1" checked name="type"/>
                                  <span></span>
                                  Carry In
                              </label>
                              <label class="radio">
                                  <input type="radio" value="2" name="type"/>
                                  <span></span>
                                  Pick Up
                              </label>
                              <label class="radio">
                                  <input type="radio" value="3" name="type"/>
                                  <span></span>
                                  On Site
                              </label>
                              <label class="radio">
                                  <input type="radio" value="4" name="type"/>
                                  <span></span>
                                  Courier
                              </label>
                          </div>
                      </div>
{{--                      <div class="form-group  {{ $errors->has('type') ? 'has-error' : '' }}">--}}
{{--                          <label class="">Service Type</label>--}}
{{--                          <select name="type" class="form-control" id="type">--}}
{{--                              <option value="1">Carry In</option>--}}
{{--                              <option value="2">Pick Up</option>--}}
{{--                              <option value="3">On Site</option>--}}
{{--                              <option value="4">Courier</option>--}}
{{--                          </select>--}}
{{--                      </div>--}}
                  </div>
              </div>
              <div class="row" id="courier" style="display: none">
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('courier') ? 'has-error' : '' }}">
                          <label class="">Courier</label>
                          <div class="">
                              {{ Form::select('courier',$couriers, null, ['class' => 'no-padding form-control col-lg-12',]) }}
                              <span class="text-danger">{{ $errors->first('courier') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="form-group col-md-4">
                      <label for="" class="">Tracking Id</label>
                      <div class="">
                          <input type="text" name="tracking_id" class="form-control" id="tracking_id" value="" >
                      </div>
                  </div>
              </div>
              <div class="row">
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('device') ? 'has-error' : '' }}">
                          <label class="">Device</label>
                          <div class="">
                              <select name="device" id="device" class="form-control">
                                  <option value="">Select Device</option>
                                  <option value="1">Mobile</option>
                                  <option value="2">Laptop</option>
                              </select>
                              <span class="text-danger">{{ $errors->first('device') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('brand') ? 'has-error' : '' }}">
                          <label class="">Brand</label>
                          <div class="">
                              <select name="brand" id="brand" class="form-control select-2">
                                  <option value="">Select Brand</option>
                                  @foreach($brands as $key => $value)
                                      <option value="{{$key}}">{{$value}}</option>
                                  @endforeach
                              </select>
                              <span class="text-danger">{{ $errors->first('brand') }}</span>
                          </div>
                      </div>
                  </div>

                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('device_model') ? 'has-error' : '' }}">
                          <label class="">Device Model</label>
                          <div class="device_model">

                          </div>
                          <span class="text-danger">{{ $errors->first('device_model') }}</span>
                      </div>
                  </div>
                  <div id="pre_repair_div" class="col-md-12">

                  </div>
                  <div class="col-md-6 form-group ">
                      <div class="form-group  {{ $errors->has('serial_number') ? 'has-error' : '' }}">
                          <label class="">Serial No</label>
                          <div class="">
                              {{ Form::text('serial_number', null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('serial_number') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
                          <label class="">Password</label>
                          <div class="">
                              {{ Form::text('password', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('password') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-2 form-group ">
                      <div class="form-group  {{ $errors->has('password') ? 'has-error' : '' }}">
                          <label></label>
                          <button type="button" class="btn patternAdd btn-primary mt-8">Pattern</button>
                      </div>
                  </div>
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('product_configuration') ? 'has-error' : '' }}">
                          <label class="">Product Configuration</label>
                          <div class="">
                              <select name="product_configuration[]" multiple="multiple" id="product_configuration" class="form-control product-config">
                                  @php $jobSetting = Auth::user()->jobSetting;@endphp
                                    @if($jobSetting)
                                        @if($jobSetting->product_configuration)
                                          @php $configs = explode(',',$jobSetting->product_configuration); @endphp
                                            @foreach($configs as $config)
                                              <option value="{{ $config }}">{{ $config }}</option>
                                            @endforeach
                                        @endif
                                    @endif
                              </select>
                              <span class="text-danger">{{ $errors->first('product_configuration') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('problem_by_customer') ? 'has-error' : '' }}">
                          <label class="">Problem Reported By The Customer</label>
                          <div class="">
                              <select name="problem_by_customer[]" multiple="multiple" id="problem_by_customer" class="form-control problem_by_customer">
                                  @if($jobSetting)
                                      @if($jobSetting->problem_by_customer)
                                          @php $configs = explode(',',$jobSetting->problem_by_customer); @endphp
                                          @foreach($configs as $config)
                                              <option value="{{ $config }}">{{ $config }}</option>
                                          @endforeach
                                      @endif
                                  @endif
                              </select>
                              <span class="text-danger">{{ $errors->first('problem_by_customer') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('condition_of_product') ? 'has-error' : '' }}">
                          <label class="">
                              Condition Of The Product</label>
                          <div class="">
                              <select name="condition_of_product[]" multiple="multiple" id="condition_of_product" class="form-control condition_of_product">
                                  @if($jobSetting)
                                      @if($jobSetting->condition_of_product)
                                          @php $configs = explode(',',$jobSetting->condition_of_product); @endphp
                                          @foreach($configs as $config)
                                              <option value="{{ $config }}">{{ $config }}</option>
                                          @endforeach
                                      @endif
                                  @endif
                              </select>
                              <span class="text-danger">{{ $errors->first('condition_of_product') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-12 form-group ">
                      <div class="form-group  {{ $errors->has('comment') ? 'has-error' : '' }}">
                          <label class="">
                              Comment</label>
                          <div class="">
                              {{ Form::textarea('comment', null, ['class' => 'form-control   form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('comment') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('cost') ? 'has-error' : '' }}">
                          <label class="">Estimate Cost</label>
                          <div class="">
                              {{ Form::number('cost', null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('cost') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('status') ? 'has-error' : '' }}">
                          <label class="">Status</label>
                          <div class="">
                            
                              {{ Form::select('status',$statuses, $job_setting->status_id ?? '', ['class' => 'no-padding form-control col-lg-12','id'=>'status']) }}

                              <span class="text-danger">{{ $errors->first('status') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('expected_delivery') ? 'has-error' : '' }}">
                          <label class="">
                              Expected Delivery Date</label>
                          <div class="">
                              {{ Form::date('expected_delivery', null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('expected_delivery') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('document') ? 'has-error' : '' }}">
                          <label class="">
                              Document</label>
                          <div class="">
                              {{ Form::file('document', null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('document') }}</span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('sms') ? 'has-error' : '' }}">
                          <label class="">
                              Send notification SMS</label>
                          <div class="">
                             <span class="switch switch-outline switch-icon switch-success">
                              <label><input type="checkbox" name="sms" value="1">
                                <span></span>
                              </label>
                            </span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-4 form-group ">
                      <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                          <label class="">
                              Send notification Email</label>
                          <div class="">
                             <span class="switch switch-outline switch-icon switch-success">
                              <label><input type="checkbox"  name="email" value="1">
                                <span></span>
                              </label>
                            </span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-6 form-group ">
                      <div class="form-group  {{ $errors->has('id_card')? 'has-error' : '' }}">
                          <label class="">Id Card</label>
                          <div class="">
                              {{ Form::select('id_card',$idCards, null, ['class' => 'no-padding form-control col-lg-12',]) }}
                              <span class="text-danger">{{ $errors->first('id_card') }}</span>
                          </div>
                      </div>
                      <div class="old_card">

                      </div>
                      <div id="idCards">
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-6">
                                      <label for="fronr">Front</label>
                                      <input type="file" name="idCards[]" id="" class="form-control" placeholder="Front"/>
                                  </div>
                                  <div class="col-md-6">
                                      <label for="fronr">Add To JobSheet</label>
                                      <label class="checkbox  checkbox-success checkbox-lg">
                                          <input type="checkbox" class="addJob" value="1" name="addJob1"/>
                                          <span></span>
                                      </label>
                                  </div>

                              </div>

                          </div>
                          <div class="form-group">
                              <div class="row">
                                  <div class="col-md-6">
                                      <label for="back">Back</label>
                                      <input type="file" name="idCards[]" id="" class="form-control" placeholder="Front"/>
                                  </div>
                                  <div class="col-md-6">
                                      <label for="">Add To JobSheet</label>
                                      <label class="checkbox checkbox-success checkbox-lg">
                                          <input type="checkbox" class="addJob" value="1" name="addJob2"/>
                                          <span></span>
                                      </label>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="row" >
                          <div class="form-group col-md-4">
                              <button type="button" class="btn btn-primary mt-5" id="addIdCard">Add More</button>
                          </div>
                          <div class="form-group   col-md-6">
                              <label class="">
                                  Add To Profile</label>
                              <div class="">
                             <span class="switch switch-outline switch-icon switch-success">
                              <label><input type="checkbox"  name="profile" value="1">
                                <span></span>
                              </label>
                            </span>
                              </div>
                          </div>
                      </div>

                  </div>

                  <div class="col-md-12 form-group ">
                      <div class="form-group  {{ $errors->has('description') ? 'has-error' : '' }}">
                          <label class="">
                              Description</label>
                          <div class="">
                              {{ Form::textarea('description', null, ['class' => 'form-control summernote form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('description') }}</span>
                          </div>
                      </div>
                  </div>

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
      <!-- Modal-->
      <div class="modal fade" id="clientModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h4 class="modal-title" id="myModalLabel">Add Customer</h4> </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-md-12">
                              <form action="{{ route("admin.popup-add")}}" method="post">
                                  @csrf
                                  <div class="form-group">
                                      <label for="name">Name</label>
                                      <input type="text" name="name" id="name" class="form-control" placeholder="Name" required>
                                  </div>
                                  <div class="form-group">
                                      <label for="email">Email</label>
                                      <input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
                                  </div>
                                  <div class="form-group">
                                      <label for="phone">Phone</label>
                                      <input type="number" name="phone" id="phone" class="form-control" placeholder="Phone" required>
                                      <span class="text-danger phone-check d-none">This Mobile Number already Registered</span>
                                  </div>
                                  <div class="form-group">
                                      <input type="submit" value="Save" class="btn btn-primary">
                                  </div>
                              </form>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>
      <div class="modal fade" id="patternModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                      <h4 class="modal-title" id="myModalLabel">Make Pattern</h4> </div>
                  <div class="modal-body">
                      <div class="row">
                          <div class="col-md-12">
                              <div id="wrapper">

                                  <div id="pattern1_container" class="pattern_container">
                                      <p>Set Pattern Lock</p>
                                      <div id="pattern1">
                                      </div>
                                      <input type="button" value="Save Pattern Lock" onclick="hide_show_pattern();">
                                  </div>

                                  <div id="pattern2_container" class="pattern_container">
                                      <p>Check Pattern Lock</p>
                                      <div id="pattern2">
                                      </div>
                                  </div>

                                  <input type="hidden" value="" id="pattern_val">

                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" id="closePattern" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                  </div>
              </div>
          </div>
      </div>
  </div>
@endsection

@section("scripts")
    <script src="{{asset("pattern/patternlock.js")}}"></script>
    <script !src="">
        $("#phone").change(function() {
            var phone = $(this).val();
            if(phone != ""){
                var CSRF_TOKEN = '{{ csrf_token() }}';
                $.post("{{ route('admin.phone-check') }}", {_token: CSRF_TOKEN, phone: phone}).done(function (response) {
                    if(response == 1){
                        $(".phone-check").removeClass("d-none");
                    }else{
                        $(".phone-check").addClass("d-none");
                    }

                });
            }

        });
        $("#user").change(function() {
            var id = $(this).val();
            if(id == ""){
                $('.old_card').html("");
            }else{
                var CSRF_TOKEN = '{{ csrf_token() }}';
                $.post("{{ route('get-user-cards') }}", {_token: CSRF_TOKEN, id: id,}).done(function (response) {
                    $('.old_card').html(response);

                });
            }

        });
        $(".product-config").select2({
            tags: true,
            tokenSeparators: [',', '    '],
        });
        $(".problem_by_customer").select2({
            tags: true,
            tokenSeparators: [',', '    '],
        });
        $(".condition_of_product").select2({
            tags: true,
            tokenSeparators: [',', '    '],
        });
        document.addEventListener("DOMContentLoaded", function() { display_pattern();});

        function display_pattern()
        {
            var lock= new PatternLock('#pattern1',{
                onDraw:function(pattern){
                    document.getElementById("pattern_val").value=lock.getPattern();
                    display_pattern2();
                }
            });
        }

        function hide_show_pattern()
        {
            if(document.getElementById("pattern_val").value!="")
            {
                document.getElementById("patternVal").value = document.getElementById("pattern_val").value;
                Swal.fire(
                    "Great!",
                    "Pattern Set Successfully.",
                    "success"
                );
                $("#closePattern").click()
                // document.getElementById("pattern1_container").style.display="none";
                // document.getElementById("pattern2_container").style.display="block";
            }
            else
            {
                Swal.fire(
                    "Warning!",
                    "Please Set Pattern Lock.",
                    "error"
                );
                // alert("Please Set Pattern Lock");
            }
        }

        function display_pattern2()
        {
            var pattern_value=document.getElementById("pattern_val").value;
            var lock= new PatternLock('#pattern2',{
                onDraw:function(pattern)
                {
                    lock.checkForPattern(pattern_value,function()
                        {
                            alert("Pattern Lock is Right");
                        },
                        function()
                        {
                            alert("Pattern Lock Is Wrong");
                        });
                }
            });
        }
        $(".patternAdd").click(function(){
            $('#patternModel').modal('show');
        });
        function name() {
            var sn = 0;
            $(".addJob").each(function(){
                sn = sn + 1;
                var new_name = "addJob" + sn;
                $(this).attr('name', new_name);

            });
        }

        $("body").on("click",".removeIdCard",function(){
            $(this).parent().parent().parent().remove();
            name();
        });
        $("#addIdCard").click(function(){
           $("#idCards").append("  <div class=\"form-group\">\n" +
               "                              <div class=\"row\">\n" +
               "                                  <div class=\"col-md-6\">\n" +
               "                                      <label for=\"\"></label>\n" +
               "                                      <input type=\"file\" name=\"idCards[]\" id=\"\" class=\"form-control\" placeholder=\"Front\"/>\n" +
               "                                  </div>\n" +
               "                                  <div class=\"col-md-5\">\n" +
               "                                      <label for=\"\">Add To JobSheet</label>\n" +
               "                                      <label class=\"checkbox  checkbox-success checkbox-lg\">\n" +
               "                                          <input type=\"checkbox\" class=\"addJob\" value=\"1\" name=\"addJob1\"/>\n" +
               "                                          <span></span>\n" +
               "                                      </label>\n" +
               "                                  </div>\n" +
               "                                  <div class=\"col-md-1\">\n" +
               "                                      <label for=\"\"></label>\n" +
               "                                      <button type=\"button\" class=\"removeIdCard btn btn-danger btn-sm\"><i class=\"fa fa-times\"></i></button>\n" +
               "                                  </div>\n" +
               "                              </div>\n" +
               "\n" +
               "                          </div>");
                name();

        });
        $('input[type=radio][name=type]').change(function() {
            var value = $(this).val();
            if(value == 4){
                $("#courier").show()
            }else{
                $("#courier").hide();
            }
        });
        $("#type").change(function() {
           var value = $(this).val();
           if(value == 4){
               $("#courier").show()
           }else{
               $("#courier").hide();
           }
        });
        $(".select-2").select2();
        $(".addUser").click(function(){
            $('#clientModel').modal('show');
        });

        $("body").on("click",".submit",function () {
            var page = $(this).attr("page");
            $("#pageVal").val(page);
            var found = false;
            $(".req").removeClass("is-invalid");
            $('.req').each(function(){
                var vl = $(this).val();
                if(vl == ""){
                    $(this).addClass("is-invalid");
                    found = true;
                }
            });
            if(found == true){
                Swal.fire(
                    "Deleted!",
                    "Plz Fill All Field Correctrly",
                    "error"
                );
            }else{
                // Swal.fire(
                //     "Deleted!",
                //     "Your Form has been submitted.",
                //     "success"
                // );
                $("#client_add_form").submit();
            }
        });
        $( document ).ready(function() {
            getDevice();
        });
        function getDevice(){
            $("#pre_repair_div").html("");
            var brand = $("#brand").val();
            var device = $("#device").val();
            var CSRF_TOKEN = '{{ csrf_token() }}';
            $.post("{{ route('admin.getDeviceModels') }}", {_token: CSRF_TOKEN, brand: brand, device: device}).done(function (response) {
                $('.device_model').html(response);
                $('.device_model').find('select').select2({placeholder: "Select Devices"});
                $(".device_model").find('select option')
                    .filter(function() {
                        return !this.value || $.trim(this.value).length == 0 || $.trim(this.text).length == 0;
                    })
                    .remove();
                $(".device_model").find('select').val("");
            });
        }
        function getPreRepair(){
            $("#pre_repair_div").html("");
            var id = $("body #device_model").val();
            var CSRF_TOKEN = '{{ csrf_token() }}';
            $.post("{{ route('admin.getPreRepair') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
                $('#pre_repair_div').html(response);

            });
        }
        $("body").on("change",".device_model",function () {
            getPreRepair();
        });
        $("#brand").change(function () {
            getDevice();
        });
        $("#device").change(function () {
            getDevice();
        });
        $(".summernote").summernote();
    </script>
@endsection
