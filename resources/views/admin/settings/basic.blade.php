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
                     <a href="" class="text-muted">Basic Setting</a>
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
                  <h3 class="card-label">Basic Setting
                     <i class="mr-2"></i>
                     <small class="">try to scroll the page</small>
                  </h3>
               </div>
               <div class="card-toolbar">
                  <a href="{{ route('admin.dashboard') }}" class="btn btn-light-primary font-weight-bolder mr-2">
                  <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>
                  <div class="btn-group">
                     @php
                     $user = Auth::user();
                     if($user->role == 1){
                     $add = 1;
                     }elseif($user->role == 2){
                     $add = 1;
                     }elseif($user->role == 3){
                     $add = $user->permission->setting_basic_edit;
                     }
                     @endphp
                     @if($add)
                     <a href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('setting_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                     <i class="ki ki-check icon-sm"></i>Save</a>
                     @endif
                  </div>
               </div>
            </div>
            <div class="card-body">
               @include('admin.partials._messages')
               <!--begin::Form-->
               <form class="form" id="setting_form" method="POST" action="{{ route('basic-setting.store') }}" enctype='multipart/form-data'>
                  @csrf
                  <input type="hidden" name="id" value="@if($settings){{$settings->id}}@else{{0}}@endif" />
                  <input type="hidden" id="symbol" name="symbol" value="@if($settings){{$settings->symbol}}@endif" />
                  <div class="row">
                     @if($shop->role != 1)
                     <div class="col-md-4 form-group ">
                        <div class="form-group  ">
                           <label class="">
                           Url</label>
                           <div class="">
                              <a href="{{route("shop",$shop->slug)}}" target="_blank">{{route("shop",$shop->slug)}}</a>
                           </div>
                        </div>
                     </div>
                     @endif
                     <div class="col-md-4 form-group ">
                        <div class="form-group  {{ $errors->has('name') ? 'has-error' : '' }}">
                           <label class="">
                           Name</label>
                           <div class="">
                              {{ Form::text('name', ($settings)?$settings->name:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('name') }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 form-group ">
                        <div class="form-group  {{ $errors->has('phone') ? 'has-error' : '' }}">
                           <label class="">Phone</label>
                           <div class="">
                              {{ Form::text('phone', ($settings)?$settings->phone:null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('phone') }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 form-group ">
                        <div class="form-group  {{ $errors->has('email') ? 'has-error' : '' }}">
                           <label class="">Email</label>
                           <div class="">
                              {{ Form::text('email', ($settings)?$settings->email:null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('email') }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-12 form-group ">
                        <div class="form-group  {{ $errors->has('address') ? 'has-error' : '' }}">
                           <label class="">Address</label>
                           <div class="">
                              {{ Form::textarea('address', ($settings)?$settings->address:null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('address') }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 form-group ">
                        <div class="form-group  {{ $errors->has('open_hours') ? 'has-error' : '' }}">
                           <label class="">Open Hours</label>
                           <div class="">
                              {{ Form::text('open_hours', ($settings)?$settings->open_hours:null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('open_hours') }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 form-group ">
                        <div class="form-group  {{ $errors->has('facebook') ? 'has-error' : '' }}">
                           <label class="">Facebook</label>
                           <div class="">
                              {{ Form::text('facebook', ($settings)?$settings->facebook:null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('facebook') }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 form-group ">
                        <div class="form-group  {{ $errors->has('twitter') ? 'has-error' : '' }}">
                           <label class="">Twitter</label>
                           <div class="">
                              {{ Form::text('twitter', ($settings)?$settings->twitter:null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('twitter') }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 form-group ">
                        <div class="form-group  {{ $errors->has('twitter') ? 'has-error' : '' }}">
                           <label class="">Twitter</label>
                           <div class="">
                              {{ Form::text('twitter', ($settings)?$settings->twitter:null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('twitter') }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 form-group ">
                        <div class="form-group  {{ $errors->has('instagram') ? 'has-error' : '' }}">
                           <label class="">Instagram</label>
                           <div class="">
                              {{ Form::text('instagram', ($settings)?$settings->instagram:null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('instagram') }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 form-group ">
                        <div class="form-group  {{ $errors->has('pinterest') ? 'has-error' : '' }}">
                           <label class="">Pinterest</label>
                           <div class="">
                              {{ Form::text('pinterest', ($settings)?$settings->pinterest:null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('pinterest') }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-4 form-group ">
                        <div class="form-group  {{ $errors->has('timezone') ? 'has-error' : '' }}">
                           <label class="">Timezones</label>
                           <div class="">
                              @php
                              echo Timezonelist::toSelectBox('timezone',  $settings->timezone , 'id="timezone" class="styled form-control select-2"');
                              @endphp
                              <span class="text-danger">{{ $errors->first('timezone') }}</span>
                           </div>
                        </div>
                     </div>
                     <div class="col-md-12 form-group ">
                        <div class="form-group  {{ $errors->has('image') ? 'has-error' : '' }}">
                           <label class="">Image (logo)</label>
                           <div class="">
                              {{ Form::file('image', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                              @if($settings)
                              @if($settings->image)
                              <img src="{{asset("uploads/$settings->image")}}" width="200" alt="">
                              @endif
                              @endif
                              <span class="text-danger">{{ $errors->first('image') }}</span>
                           </div>
                        </div>
                     </div>

                     @if(auth()->user()->role == '2' && !empty($statuses))
                        @foreach($statuses as $status)
                            @php
                            $array = json_decode($settings->status, true);
                            @endphp
                            <div class="col-md-4 form-group ">
                                <div class="form-group  {{ $errors->has('work_recieved') ? 'has-error' : '' }}">
                                <label class="">{{ $status->name }}</label>
                                <div class="">
                                    <input type="checkbox" @if($array ) @if((in_array($status->name, $array )))  checked="checked" @endif  @endif id="work_recieved" name="status[]" value="{{ $status->name }}">
                                </div>
                                </div>
                            </div>
                        @endforeach
                     @endif
                  </div>
               </form>
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
<script !src="">
   $(".summernote").summernote();
   $("#currency").change(function() {
       updateSymbol();
   });
   $('document').ready(function() {
      updateSymbol();
   });
   function updateSymbol(){
       var option = $('#currency option:selected').attr('symbol');
       $("#symbol").val(option);
   }
</script>
<script>
   $("#user").select2({
       multiple: true,

   });
   $("#type").select2();

</script>
@endsection
