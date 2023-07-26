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
                <a href="" class="text-muted">Manage Status</a>
              </li>
              <li class="breadcrumb-item text-muted">
                Edit Status
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
              <h3 class="card-label">Status Edit Form
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('statuses.index') }}" class="btn btn-light-primary
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
            {{ Form::model($user, [ 'method' => 'PATCH','route' => ['statuses.update', $user->id],'class'=>'form' ,"id"=>"client_update_form", 'enctype'=>'multipart/form-data'])}}
              @csrf
              <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                  <div class="my-5">
                      <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                          <label class="col-3">Name</label>
                          <div class="col-9">
                              {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('name') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('color') ? 'has-error' : '' }}">
                          <label class="col-3">Color</label>
                          <div class="col-9">
                              {{ Form::text('color', null, ['class' => 'form-control form-control-solid','id'=>'cp1','placeholder'=>'Enter Here','required'=>'true']) }}
                              <button class="btn btn-sm" style="background-color: {{$user->color}}"></button>
                              <span class="text-danger">{{ $errors->first('color') }}</span>
                          </div>
                      </div>
                      @if(auth()->user()->role == 1)
                        {{--  <div class="form-group row {{ $errors->has('shops') ? 'has-error' : '' }}">
                        <label class="col-3">Assign to Shop</label>
                        <div class="col-9">
                            <select class="form-control  select-2-multiple" name="shops[]" id="type" multiple>

                                @foreach ($shops as $shop)
                                <option value="{{ $shop->id }}"
                                    @if($user->shop_ids)
                                    @php
                                        $array = json_decode($user->shop_ids, true);
                                    @endphp
                                    @if(!empty($shop->id) && !empty( $array))
                                        @if(in_array($shop->id,$array))
                                            selected
                                        @endif
                                    @endif
                                    @endif>{{ $shop->name }}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">{{ $errors->first('shops') }}</span>
                        </div>
                        </div>  --}}
                        <div class="form-group row">
                            <label class="col-3 col-form-label">Shared</label>
                            <div class="col-3">
                                   <span class="switch switch-outline switch-icon switch-success">
                                    <label><input type="checkbox" @if($user->shared)  checked="checked" @endif id="shared" name="shared" value="1">
                                      <span></span>
                                    </label>
                                  </span>
                            </div>
                        </div>
                    @endif
                      <div class="form-group row {{ $errors->has('sort_order') ? 'has-error' : '' }}">
                          <label class="col-3">Sort Order</label>
                          <div class="col-9">
                              {{ Form::number('sort_order', $user->sort_order, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('sort_order') }}</span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-6 col-form-label">Mark this status as complete </label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  id="active" @if($user->complete) checked @endif name="complete" value="1">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('sms_type') ? 'has-error' : '' }}">
                          <label class="col-3">SMS Type</label>
                          <div class="col-9">
                              {{ Form::text('sms_type', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('sms_type') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('sms_peid') ? 'has-error' : '' }}">
                          <label class="col-3">SMS PEID</label>
                          <div class="col-9">
                              {{ Form::text('sms_peid', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('sms_peid') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('sms_template_id') ? 'has-error' : '' }}">
                          <label class="col-3">SMS Template ID</label>
                          <div class="col-9">
                              {{ Form::text('sms_template_id', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('sms_template_id') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('sms_template') ? 'has-error' : '' }}">
                          <label class="col-3">SMS Template</label>
                          <div class="col-9">
                              {{ Form::textarea('sms_template', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('sms_template') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('whatsapp_template') ? 'has-error' : '' }}">
                          <label class="col-3">Whatsapp Template</label>
                          <div class="col-9">
                              {{ Form::textarea('whatsapp_template', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('whatsapp_template') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('email_subject') ? 'has-error' : '' }}">
                          <label class="col-3">Email Subject</label>
                          <div class="col-9">
                              {{ Form::text('email_subject', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('email_subject') }}</span>
                          </div>
                      </div>
                      <div class="form-group row {{ $errors->has('email_body') ? 'has-error' : '' }}">
                          <label class="col-3">Email Body</label>
                          <div class="col-9">
                              {{ Form::textarea('email_body', null, ['class' => 'form-control summernote form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('email_body') }}</span>
                          </div>
                      </div>
                      <div class="form-group row ">
                          <label class="col-3">Available Tags</label>
                          <div class="col-9">
                              <span class="">{customer_name}, {job_sheet_no}, {status}, {serial_number}, {delivery_date},  {brand}, {device}, {device_model}, {business_name}, {pdf} </span>
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
@section("stylesheets")
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/css/bootstrap-colorpicker.css" integrity="sha512-HcfKB3Y0Dvf+k1XOwAD6d0LXRFpCnwsapllBQIvvLtO2KMTa0nI5MtuTv3DuawpsiA0ztTeu690DnMux/SuXJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
@section("scripts")
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/3.4.0/js/bootstrap-colorpicker.min.js" integrity="sha512-94dgCw8xWrVcgkmOc2fwKjO4dqy/X3q7IjFru6MHJKeaAzCvhkVtOS6S+co+RbcZvvPBngLzuVMApmxkuWZGwQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(function () {
            $('#cp1').colorpicker();
        });
    </script>
    <script !src="">
        $(".summernote").summernote();
    </script>

    <script>

        $("#type").select2();
        </script>

@endsection
