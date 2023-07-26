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
                <a href="" class="text-muted">Manage Enquiries</a>
              </li>
              <li class="breadcrumb-item text-muted">
                Edit Enquiry
              </li>
              <li class="breadcrumb-item text-muted">
               {{ $user->name }}
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
              <h3 class="card-label">Enquiry Edit Form
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('enquiries.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href=""  onclick="event.preventDefault(); document.getElementById('client_update_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>update</a>



              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::model($user, [ 'method' => 'PATCH','route' => ['enquiries.update', $user->id],'class'=>'form' ,"id"=>"client_update_form", 'enctype'=>'multipart/form-data'])}}
              <input type="hidden" name="dev_count" id="devCount" value="1">
              <div class="row">
                  <div class="col-xl-2"></div>
                  <div class="col-xl-8">
                      <div class="my-5">
                          <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                              <label class="col-3">Name</label>
                              <div class="col-9">
                                  {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Name','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('name') }}</span>
                              </div>
                          </div>

                            @if(auth()->user()->role == 1)
                                <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label class="col-3">Shops</label>
                                <div class="col-9">
                                    <select name="shop" id="user" class="form-control select-2">
                                        <option value=" ">--Select Shops---</option>
                                        @foreach($shops as  $value)
                                        <option value="{{$value->id}}" @if($value->id == $user->user_id) selected @endif>{{$value->name}} </option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('shop') }}</span>
                                </div>
                                </div>
                            @endif
                          <div class="form-group row {{ $errors->has('contact_number') ? 'has-error' : '' }}">
                              <label class="col-3">Contact Number</label>
                              <div class="col-9">
                                  {{ Form::text('contact_number', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('contact_number') }}</span>
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('email_address') ? 'has-error' : '' }}">
                              <label class="col-3">Email Address</label>
                              <div class="col-9">
                                  {{ Form::email('email_address', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('email_address') }}</span>
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('estimate_date') ? 'has-error' : '' }}">
                              <label class="col-3">Estimate Date</label>
                              <div class="col-9">
                                  {{ Form::date('estimate_date', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('estimate_date') }}</span>
                              </div>
                          </div>

                          <div class="form-group row {{ $errors->has('device') ? 'has-error' : '' }}">
                              <label class="col-3">Devices</label>
                              <div class="col-9">
                                  <div class="dev">
                                      <div class="form-group re-row">
                                          <div class="radio-inline">
                                              <label class="radio">
                                                  <input type="radio" class="device device_var" value="1" name="device0"/>
                                                  <span></span>
                                                  Mobile Phone
                                              </label>
                                              <label class="radio">
                                                  <input type="radio" class="device device_var" value="2" name="device0"/>
                                                  <span></span>
                                                  Computer
                                              </label>
                                          </div>
                                          <div class="form-group mt-5 row {{ $errors->has('brand') ? 'has-error' : '' }}">
                                              <label class="col-3">Brand</label>
                                              <div class="col-9">
                                                  {{ Form::select('brand0',$brands, null, ['class' => 'no-padding brad device_var form-control col-lg-12','id'=>'brand']) }}
                                                  <span class="text-danger">{{ $errors->first('brand') }}</span>
                                              </div>
                                          </div>
                                          <div class="form-group row {{ $errors->has('device') ? 'has-error' : '' }}">
                                              <label class="col-3">Device</label>
                                              <div class="col-9">
                                                  <div class="device_model"></div>
                                                  <span class="text-danger">{{ $errors->first('device') }}</span>
                                              </div>
                                          </div>
                                          <div class="form-group row {{ $errors->has('enquiry') ? 'has-error' : '' }}">
                                              <label class="col-3">Enquiry</label>
                                              <div class="col-9">
                                                  {{ Form::textarea('enquiry0', null, ['class' => 'form-control enquiry form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                  <span class="text-danger">{{ $errors->first('enquiry') }}</span>
                                              </div>
                                          </div>
                                      </div>
                                      <hr>
                                  </div>

                                  <button type="button" class="btn btn-primary add-dev btn-sm">Add More</button>
                                  <table class="table table-striped mt-5">
                                      <tr class="bg-light-info">
                                          <td>Device</td>
                                          <td>Brand</td>
                                          <td>Models</td>
                                          <td>Enquiry</td>
                                          <td>Action</td>
                                      </tr>
                                      @foreach($user->brands as $brand)
                                          <tr>
                                              <td>
                                                  @if($brand->device == 1)
                                                      Mobile
                                                  @else
                                                      Laptop
                                                  @endif
                                              </td>
                                              <td>
                                                  {{$brand->brand->name ?? ''}}
                                              </td>
                                              <td>
                                                  {{$brand->deviceModel->name ?? ''}}
                                              </td>
                                              <td>
                                                  {{$brand->enquiry ?? ''}}
                                              </td>
                                              <td>
                                                  <a href="{{route("delete-enquiry-brand",$brand->id)}}"><i class="fa fa-trash-alt  btn btn-outline-danger float-right btn-sm mb-2"></i></a>
                                              </td>
                                          </tr>
                                      @endforeach
                                  </table>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-3 col-form-label">Complete </label>
                              <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" {{ ($user->status) ?'checked':'' }} id="status" name="status" value="1">
                            <span></span>
                          </label>
                        </span>
                              </div>
                          </div>
                          <div class="form-group row {{ $errors->has('message') ? 'has-error' : '' }}" id="message">
                              <label class="col-3">Message</label>
                              <div class="col-9">
                                  {{ Form::textarea('message', null, ['class' => 'form-control enquiry form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('message') }}</span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-3 col-form-label">Send notification SMS </label>
                              <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" {{ ($user->sms) ?'checked':'' }}  name="sms" value="1">
                            <span></span>
                          </label>
                        </span>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-3 col-form-label">Send notification Email </label>
                              <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" {{ ($user->email) ?'checked':'' }} name="email" value="1">
                            <span></span>
                          </label>
                        </span>
                              </div>
                          </div>
                      </div>

                  </div>
                  <div class="col-xl-2"></div>
              </div>
{{--              <div class="row form-group">--}}
{{--                  <div class="col-12">--}}
{{--                      <button type="button" class="btn btn-sm btn-icon btn-circle btn-success float-right add-question" title="Add Question"><i class="fa fa-plus"></i></button>--}}
{{--                  </div>--}}
{{--              </div>--}}
{{--              <div class="questions">--}}

{{--                  @foreach($user->preRepairs as $key => $pre)--}}
{{--                      <div class="form-group question row mt-3">--}}
{{--                          <div class="col-8 form-group">--}}
{{--                              <label for="">Pre Repair</label>--}}
{{--                              <input type="text" name="pre_repair[]" value="{{$pre->name}}" class="form-control form-control-solid " required>--}}
{{--                              <input type="hidden" name="pre_id[]" value="{{$pre->id}}" class="form-control form-control-solid " required>--}}
{{--                          </div>--}}


{{--                          <div class="col-4">--}}
{{--                              <a href="{{route("pre-repair-delete",$pre->id)}}" class="btn btn-sm btn-icon btn-circle btn-danger float-right remove-question" title="Remove Question "><i class="fa fa-times"></i></a>--}}
{{--                          </div>--}}

{{--                      </div>--}}
{{--                  @endforeach--}}
{{--              </div>--}}
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
  <div class="dev-h" style="display: none">
      <div class="form-group re-row">
          <div class="radio-inline">
              <label class="radio">
                  <input type="radio" class="device device_var" value="1" name="device0"/>
                  <span></span>
                  Mobile Phone
              </label>
              <label class="radio">
                  <input type="radio" class="device device_var" value="2" name="device0"/>
                  <span></span>
                  Computer
              </label>
          </div>
          <div class="form-group mt-5 row {{ $errors->has('brand') ? 'has-error' : '' }}">
              <label class="col-3">Brand</label>
              <div class="col-9">
                  {{ Form::select('brand0',$brands, null, ['class' => 'no-padding brad device_var form-control col-lg-12','id'=>'brand']) }}
                  <span class="text-danger">{{ $errors->first('brand') }}</span>
              </div>
          </div>
          <div class="form-group row {{ $errors->has('device') ? 'has-error' : '' }}">
              <label class="col-3">Device</label>
              <div class="col-9">
                  <div class="device_model"></div>
                  <span class="text-danger">{{ $errors->first('device') }}</span>
              </div>
          </div>
          <div class="form-group row {{ $errors->has('enquiry') ? 'has-error' : '' }}">
              <label class="col-3">Enquiry</label>
              <div class="col-9">
                  {{ Form::textarea('enquiry0', null, ['class' => 'form-control enquiry form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                  <span class="text-danger">{{ $errors->first('enquiry') }}</span>
              </div>
          </div>
          <div class="form-group row">
              <div class="col-md-12">
                  <i class="fa fa-trash-alt removeDev btn btn-outline-danger float-right btn-sm mb-2"></i>
              </div>

          </div>
          <hr>
      </div>
  </div>
@endsection
@section("scripts")
    <script !src="">
        // $("#status").change(function() {
        //     $("#message").toggle();
        // });
        $("body").on("click", ".add-dev", function() {
            var append = $(".dev-h").html();
            $(".dev").append(append);
            var devCount =  parseInt($("#devCount").val());
            devCount = devCount +1;
            $("#devCount").val(devCount);
            naming();
        });
        $("body").on("click", ".removeDev", function() {
            $(this).parent().parent().parent().remove();
            var devCount =  parseInt($("#devCount").val());
            devCount = devCount -1;
            $("#devCount").val(devCount);
            naming();
        });
        function naming() {
            var sn = 0;
            $(".re-row").each(function() {
                var brand_name = "brand"+sn;
                var device_name = "device"+sn;
                var enquiry_name = "enquiry"+sn;
                var model_name = "model"+sn;
                var model_id = "model"+sn;
                $(this).find(".brad").attr("name", brand_name);
                $(this).find(".device").attr("name", device_name);
                $(this).find(".dev_model").attr("name", model_name);
                $(this).find(".enquiry").attr("name", enquiry_name);
                $(this).find(".dev_model").attr("id", model_id);
                $(this).find(".brad").attr("id", brand_name);
                $(this).find('.dev_model').select2();
                // $(this).find('.brad').select2();
                sn = sn+1;
            });
        }

        $("body").on('change','.device_var', function () {
            var ths = $(this);
            var brand = $(this).parent().parent().parent().find(".brad").val();
            var device = $(this).parent().parent().parent().find('.device:checked').val();
            var CSRF_TOKEN = '{{ csrf_token() }}';
            $.post("{{ route('admin.getDeviceModels') }}", {_token: CSRF_TOKEN, brand: brand, device: device}).done(function (response) {
                $(ths).parent().parent().parent().find('.device_model').html(response);
                // $(".device_model").find('select').attr("multiple", true);
                $(".device_model").find('select option')
                    .filter(function() {
                        return !this.value || $.trim(this.value).length == 0 || $.trim(this.text).length == 0;
                    })
                    .remove();

                naming();

            });
        });
        $("body").on("click",".add-question",function () {
            $(".questions").append("<div class=\"form-group question row mt-3\">\n" +
                "                      <div class=\"col-8 form-group\">\n" +
                "                          <label for=\"\">Pre Repair</label>\n" +
                "                          <input type=\"text\" name=\"pre_repair[]\"  class=\"form-control form-control-solid \" required>\n" +
                "                          <input type=\"hidden\" value=\"0\" name=\"pre_id[]\"  class=\"form-control form-control-solid \" required>\n" +
                "                      </div>\n" +

                "                      <div class=\"col-4\">\n" +
                "                          <button type=\"button\" class=\"btn btn-sm btn-icon btn-circle btn-danger float-right remove-question\" title=\"Remove Question \"><i class=\"fa fa-times\"></i></button>\n" +
                "\n" +
                "                      </div>\n" +
                "                  </div>");
        });

        $("body").on("click",".remove-question",function () {
            $(this).parent().parent().remove();
            naming();
        });
        $("body").on("click",".submit",function () {
            var found = false;
            $('input').each(function(){
                var vl = $(this).val();
                if(vl == ""){
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

    </script>
@endsection
