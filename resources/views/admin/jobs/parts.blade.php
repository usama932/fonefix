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
                <a href="" class="text-muted">Manage Jobs</a>
              </li>
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Add Parts</a>
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
              <h3 class="card-label">Parts Add (Job #{{$user->id}})
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('jobs.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href="#"   id="kt_btn" class="btn btn-primary submit font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save</a>



              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::open([ 'route' => 'add-job-parts','class'=>'form' ,"id"=>"client_add_form", 'enctype'=>'multipart/form-data']) }}
              @csrf
              <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                  <div class="my-5">

                  <div class="form-group row {{ $errors->has('product') ? 'has-error' : '' }}">
                      <label class="col-3">Products</label>
                      <div class="col-9">
                          <input type="hidden" name="id" value="{{$user->id}}">
                          <select name="product" id="product" class="form-control ">
                              <option value="">Select Product</option>
                              @foreach($products as $key => $value)
                                  <option value="{{$key}}" >{{$value}}</option>
                              @endforeach
                          </select>
{{--                          {{ Form::select('product',$products, null, ['class' => 'no-padding product form-control col-lg-12','id'=>'product']) }}--}}
                          <span class="text-danger">{{ $errors->first('product') }}</span>
                      </div>
                  </div>

                  <div class="form-group row {{ $errors->has('quantity') ? 'has-error' : '' }}">
                      <label class="col-3">Quantity</label>
                      <div class="col-6">
                          {{ Form::number('quantity', null, ['class' => 'form-control quantity form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                          <input type="hidden" name="" value="1" class="check-quantity">
                          <span class="text-danger">{{ $errors->first('quantity') }}</span>
                      </div>
                      <div class="col-3">
                          <a href="#"   id="kt_btn" class="btn btn-success btn-sm submit font-weight-bolder">
                              <i class="ki ki-plus icon-sm"></i>Add</a>
                      </div>
                  </div>
                  </div>

                </div>
                <div class="col-xl-2"></div>

              </div>
              <table class="table">
                  <tr class="bg-light-info">
                      <th>Product</th>

                      <th>Quantity</th>

                      <th>Action</th>
                  </tr>
                  @foreach($user->parts as $part)
                      <tr>
                          <td>{{$part->description}}</td>
                          @if( $part->product->is_regular == 1)
                            <td></td>
                          @else
                            <td>{{$part->quantity}}</td>
                          @endif
                          <td><a href="{{route("job-parts-delete",$part->id)}}" class="btn btn-danger btn-sm "><i class="fa fa-times"></i></a></td>
                      </tr>
                  @endforeach
              </table>
{{--              <div class="row form-group">--}}
{{--                  <div class="col-12">--}}
{{--                      <button type="button" class="btn btn-sm btn-icon btn-circle btn-success float-right add-question" title="Add Question"><i class="fa fa-plus"></i></button>--}}
{{--                  </div>--}}
{{--              </div>--}}
{{--              <div class="questions">--}}
{{--                  <div class="form-group question row mt-3">--}}
{{--                      <div class="col-8 form-group">--}}
{{--                          <label for="">Pre Repair</label>--}}
{{--                          <input type="text" name="pre_repair[]" class="form-control form-control-solid " required>--}}
{{--                      </div>--}}

{{--                      <div class="col-4 form-group">--}}

{{--                      </div>--}}

{{--                  </div>--}}
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
@endsection

      @section("scripts")

          <script !src="">
              $("body").on("click",".submit",function () {
                  var quantity = $(".quantity").val();
                  var check_quantity = parseInt($(".check-quantity").val());
                  if(quantity == ""){
                      Swal.fire(
                          "Sorry!",
                          "Please Enter Quantity",
                          "error"
                      );
                  }else{
                      if(quantity > check_quantity){
                          Swal.fire(
                              "Sorry!",
                              "You Can not Add more than "+check_quantity+" Quantity",
                              "error"
                          );
                      }else{
                          $("#client_add_form").submit();
                      }
                  }
              });
              $( document ).ready(function() {

                  checkQty();
              });
              function checkQty(){
                  var id = $("#product").val();

                  var CSRF_TOKEN = '{{ csrf_token() }}';
                  $.post("{{ route('admin.checkQty') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
                      $('.check-quantity').val(response);
                  });

              }
              $("#product").change(function() {
                  checkQty();
              });
              $("#product").select2();

          </script>
@endsection
