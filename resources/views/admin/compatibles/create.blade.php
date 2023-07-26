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
                     <a href="" class="text-muted">Manage Compatible</a>
                  </li>
                  <li class="breadcrumb-item text-muted">
                     <a href="" class="text-muted">Add Compatible</a>
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
                  <h3 class="card-label">Compatible Add Form
                     <i class="mr-2"></i>
                     <small class="">try to scroll the page</small>
                  </h3>
               </div>
               <div class="card-toolbar">
                  <a href="{{ route('compatibles.index') }}" class="btn btn-light-primary
                     font-weight-bolder mr-2">
                  <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>
                  <div class="btn-group">
                     <a href="{{ route('compatibles.store') }}"  onclick="event.preventDefault(); document.getElementById('client_add_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                     <i class="ki ki-check icon-sm"></i>Save</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               @include('admin.partials._messages')
               <!--begin::Form-->
               {{ Form::open([ 'route' => 'compatibles.store','class'=>'form' ,"id"=>"client_add_form", 'enctype'=>'multipart/form-data']) }}
               @csrf
               <div class="row">
                  <div class="col-xl-2"></div>
                  <div class="col-xl-8">
                     <div class="my-5">
                        <div class="form-group row {{ $errors->has('type') ? 'has-error' : '' }}">
                           <label class="col-3">Item Type</label>
                           <div class="col-9">
                              {{ Form::select('type',$types, null, ['class' => 'no-padding form-control col-lg-12',]) }}
                              <span class="text-danger">{{ $errors->first('type') }}</span>
                           </div>
                        </div>
                        <div class="form-group row {{ $errors->has('shop') ? 'has-error' : '' }}">
                           <label class="col-3">Shops</label>
                           <div class="col-9">
                              {{ Form::select('shop',$shops, null, ['class' => 'no-padding form-control col-lg-12',]) }}
                              <span class="text-danger">{{ $errors->first('shop') }}</span>
                           </div>
                        </div>
                        <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                           <label class="col-3">Name</label>
                           <div class="col-9">
                              {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Name','required'=>'true']) }}
                              <span class="text-danger">{{ $errors->first('name') }}</span>
                           </div>
                        </div>
                        <div class="form-group row {{ $errors->has('compatible') ? 'has-error' : '' }}">
                           <label class="col-3">Compatible with</label>
                           <div class="col-9">
                              {{ Form::select('compatible[]',$devices, null, ['class' => 'no-padding form-control col-lg-12','id'=>'compatible','multiple'=>true]) }}
                              <span class="text-danger">{{ $errors->first('compatible') }}</span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-xl-2"></div>
               </div>
               {{--
               <div class="row form-group">
                  --}}
                  {{--
                  <div class="col-12">--}}
                     {{--                      <button type="button" class="btn btn-sm btn-icon btn-circle btn-success float-right add-question" title="Add Question"><i class="fa fa-plus"></i></button>--}}
                     {{--
                  </div>
                  --}}
                  {{--
               </div>
               --}}
               {{--
               <div class="questions">
                  --}}
                  {{--
                  <div class="form-group question row mt-3">
                     --}}
                     {{--
                     <div class="col-8 form-group">--}}
                        {{--                          <label for="">Pre Repair</label>--}}
                        {{--                          <input type="text" name="pre_repair[]" class="form-control form-control-solid " required>--}}
                        {{--
                     </div>
                     --}}
                     {{--
                     <div class="col-4 form-group">--}}
                        {{--
                     </div>
                     --}}
                     {{--
                  </div>
                  --}}
                  {{--
               </div>
               --}}
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
@section("scripts")
<script !src="">
   $("#device").select2();
   $("#compatible").select2();
   $("body").on("click",".add-question",function () {
     $(".questions").append("<div class=\"form-group question row mt-3\">\n" +
         "                      <div class=\"col-8 form-group\">\n" +
         "                          <label for=\"\">Pre Repair</label>\n" +
         "                          <input type=\"text\" name=\"pre_repair[]\"  class=\"form-control form-control-solid \" required>\n" +
         "                      </div>\n" +

         "                      <div class=\"col-3 form-group\">\n" +
         "                      </div>\n" +
         "                      <div class=\"col-1\">\n" +
         "                          <button type=\"button\" class=\"btn btn-sm btn-icon btn-circle btn-danger float-right remove-question\" title=\"Remove Question \"><i class=\"fa fa-times\"></i></button>\n" +
         "\n" +
         "                      </div>\n" +
         "                  </div>");
   });

   $("body").on("click",".remove-question",function () {
     $(this).parent().parent().remove();
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
