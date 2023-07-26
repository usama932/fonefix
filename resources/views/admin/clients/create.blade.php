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
                <a href="" class="text-muted">Manage Clients</a>
              </li>
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Add Client</a>
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
              <h3 class="card-label">Client Add Form
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('clients.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href="{{ route('clients.store') }}"  onclick="event.preventDefault(); document.getElementById('client_add_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save</a>



              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::open([ 'route' => 'clients.store','class'=>'form' ,"id"=>"client_add_form", 'enctype'=>'multipart/form-data']) }}
              @csrf
              <input type="hidden" name="dev_count" id="devCount" value="1">
              <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                  <div class="my-5">
                    <h3 class="text-dark font-weight-bold mb-10">Client Info: </h3>
                    <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                      <label class="col-3">Name</label>
                      <div class="col-9">
                        {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Name','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                      </div>
                    </div>
                      <div class="form-group row {{ $errors->has('phone') ? 'has-error' : '' }}">
                          <label class="col-3">Phone</label>
                          <div class="col-9">
                              {{ Form::number('phone', null, ['class' => 'form-control form-control-solid','id'=>'phone','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger phone-check d-none">This Mobile Number already Registered</span>
                              <span class="text-danger">{{ $errors->first('phone') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('alternative_phone') ? 'has-error' : '' }}">
                          <label class="col-3">Alternative Phone</label>
                          <div class="col-9">
                              {{ Form::number('alternative_phone', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('alternative_phone') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('line1') ? 'has-error' : '' }}">
                          <label class="col-3">Address Line 1</label>
                          <div class="col-9">
                              {{ Form::text('line1', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('line1') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('line2') ? 'has-error' : '' }}">
                          <label class="col-3">Address Line 2</label>
                          <div class="col-9">
                              {{ Form::text('line2', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('line2') }}</span>
                          </div>
                      </div>

                      <div class="form-group row {{ $errors->has('city') ? 'has-error' : '' }}">
                          <label class="col-3">City</label>
                          <div class="col-9">
                              {{ Form::text('city', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('city') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('province') ? 'has-error' : '' }}">
                          <label class="col-3">Province</label>
                          <div class="col-9">
                              <div class="provinceDiv"></div>
                              <span class="text-danger">{{ $errors->first('province') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('postal_code') ? 'has-error' : '' }}">
                          <label class="col-3">PostalCode</label>
                          <div class="col-9">
                              {{ Form::text('postal_code', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('postal_code') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('country') ? 'has-error' : '' }}">
                          <label class="col-3">Country</label>
                          <div class="col-9">
                              <select name="country" class="form-control country" id="">
                                  @foreach($countries as $country)
                                      <option value="{{ $country->id }}">{{ $country->name }}</option>
                                  @endforeach
                              </select>
                              <span class="text-danger">{{ $errors->first('country') }}</span>
                          </div>
                      </div>

                      <div class="form-group row {{ $errors->has('location') ? 'has-error' : '' }}">
                          <label class="col-3">Location</label>
                          <div class="col-9">
                              {{ Form::text('location', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('location') }}</span>
                          </div>
                      </div>
                    <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                      <label class="col-3">Email</label>
                      <div class="col-9">
                        {{ Form::email('email', null, ['class' => 'form-control form-control-solid','id'=>'email','placeholder'=>'Email Address','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('email') }}</span>
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
                                  </div>
                                  <hr>
                              </div>

                              <button type="button" class="btn btn-primary add-dev btn-sm">Add More</button>
                          </div>
                      </div>
                    <div class="form-group row {{ $errors->has('password') ? 'has-error' : '' }}">
                      <label class="col-3">Password</label>
                      <div class="col-9">
                        {{ Form::text('password', null, ['class' => 'form-control form-control-solid','id'=>'password','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('password') }}</span>
                      </div>
                    </div>

                      <div class="form-group row">
                          <label class="col-3 col-form-label">Active</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" checked="checked" id="active" name="active" value="1">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="active" style="display: none">
                          <div class="form-group row {{ $errors->has('disable_reason') ? 'has-error' : '' }}">
                              <label class="col-3">Disable reason</label>
                              <div class="col-9">
                                  {{ Form::text('disable_reason', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('disable_reason') }}</span>
                              </div>
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
        $(".country").select2();
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
               var model_name = "model"+sn+"[]";
               var model_id = "model"+sn;
               $(this).find(".brad").attr("name", brand_name);
               $(this).find(".device").attr("name", device_name);
               $(this).find(".dev_model").attr("name", model_name);
               $(this).find(".dev_model").attr("id", model_id);
               $(this).find(".brad").attr("id", brand_name);
                $(this).find('.dev_model').select2();
                // $(this).find('.brad').select2();
               sn = sn+1;
            });
        }
        $(document).ready(function() {
            var CSRF_TOKEN = '{{ csrf_token() }}';
            var id = $(".country").val();
            $.post("{{ route('country-provinces') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
                $('.provinceDiv').html(response);
            });
        });

        $("body").on('change','.device_var', function () {
            var ths = $(this);
            var brand = $(this).parent().parent().parent().find(".brad").val();
            var device = $(this).parent().parent().parent().find('.device:checked').val();
            var CSRF_TOKEN = '{{ csrf_token() }}';
            $.post("{{ route('admin.getDeviceModels') }}", {_token: CSRF_TOKEN, brand: brand, device: device}).done(function (response) {
                $(ths).parent().parent().parent().find('.device_model').html(response);
                $(".device_model").find('select').attr("multiple", true);
                $(".device_model").find('select option')
                    .filter(function() {
                        return !this.value || $.trim(this.value).length == 0 || $.trim(this.text).length == 0;
                    })
                    .remove();

                naming();

            });
        });
        $(".country").change(function() {
            var CSRF_TOKEN = '{{ csrf_token() }}';
            var id = $(this).val();
            $.post("{{ route('country-provinces') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
                $('.provinceDiv').html(response);
            });
        });
        $(".province").select2();
        $("#active").change(function(){
            if($(this).prop("checked") == true){
                $(".active").hide();
            }else{
                $(".active").show();

            }
        });
    </script>
@endsection
