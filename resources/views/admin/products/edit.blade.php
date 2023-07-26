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
                <a href="" class="text-muted">Manage Products</a>
              </li>
              <li class="breadcrumb-item text-muted">
                Edit Product
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
              <h3 class="card-label">Product Edit Form
                <i class="mr-2"></i>
                </h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('products.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

                <div class="btn-group">
                    <a href="#"   id="kt_btn" page="1" class="btn btn-success submit font-weight-bolder">
                        <i class="ki ki-check icon-sm"></i>Save and upload docs</a>


                    <a href="#"   id="kt_btn" page="2" class="btn btn-primary submit font-weight-bolder">
                        <i class="ki ki-check icon-sm"></i>Save</a>




                </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          @php
              $us = Auth::user();
              if($us->role == 1){
                  $product_manage_stock = 1;
                  $product_purchase_price = 1;
                  $product_sell_price = 1;
                  $product_discount = 1;
              }elseif($us->role == 2){
                  $product_manage_stock = 1;
                  $product_purchase_price = 1;
                  $product_sell_price = 1;
                  $product_discount = 1;
              }elseif($us->role == 3){
                  $product_sell_price = $us->permission->product_sell_price;
                  $product_discount = $us->permission->product_discount;
                  $product_purchase_price = $us->permission->product_purchase_price;
                  $product_manage_stock = $us->permission->product_manage_stock;
              }
          @endphp
          <!--begin::Form-->
            {{ Form::model($user, [ 'method' => 'PATCH','route' => ['products.update', $user->id],'class'=>'form' ,"id"=>"client_add_form", 'enctype'=>'multipart/form-data'])}}
              @csrf
              <input type="hidden" name="page" id="pageVal" value="2">
              <div class="row">
                  @if(Auth::user()->role != 2)
                      <div class="form-group col-md-4  {{ $errors->has('shop') ? 'has-error' : '' }}">
                          <div class="">
                              <label class="">Shops</label>

                              {{ Form::select('shop',$shops, $user->user_id, ['class' => 'no-padding form-control col-lg-12','id'=>'shop']) }}
                              <span class="text-danger">{{ $errors->first('shop') }}</span>
                          </div>
                      </div>
                  @endif
                  <div class="form-group col-md-4  {{ $errors->has('name') ? 'has-error' : '' }}">
                      <div class="">
                          <label class="">Name</label>

                          {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Name','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('name') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('sku') ? 'has-error' : '' }}">
                      <div class="">
                          <label class="">Sku</label>

                          {{ Form::text('sku', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('sku') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('brand') ? 'has-error' : '' }}">
                      <div class="">
                          <label class="">Brand</label>
                          <select name="brand" id="brand" class="form-control ">
                              <option value="">Select Brand</option>
                              @foreach($brands as $key => $value)
                                  <option value="{{$key}}" @if($key == $user->brand_id) selected @endif>{{$value}}</option>
                              @endforeach
                          </select>
{{--                          {{ Form::select('brand',$brands, $user->brand_id, ['class' => 'no-padding form-control col-lg-12','id'=>'brand']) }}--}}
                          <span class="text-danger">{{ $errors->first('brand') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('device') ? 'has-error' : '' }}">
                      <div class="">
                          <label class="">Device</label>
                          <div class="device_model">

                          </div>
                          <span class="text-danger">{{ $errors->first('device') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('category') ? 'has-error' : '' }}">
                      <div class="">
                          <label class="">Category</label>
                          <select name="category" id="category" class="form-control" required>
                              <option value="">Select Category</option>
                              @foreach($categories as $category)
                                @if($category->children)
                                  <option value="{{ $category->id }}" @if($user->category_id == $category->id) selected="selected" @endif>{{ $category->name }}</option>
                                  @foreach($category->children as $child)
                                      <option value="{{ $child->id }}" @if($user->category_id == $child->id) selected="selected" @endif>--{{ $child->name }}</option>
                                  @endforeach
                                @endif
                              @endforeach

                          </select>
                          <span class="text-danger">{{ $errors->first('category') }}</span>
                      </div>
                  </div>

                  <div class="form-group col-md-4  {{ $errors->has('purchase_price') ? 'has-error' : '' }}" @if(!$product_purchase_price) style="display: none" @endif>
                      <div class="">
                          <label class="">Purchase Price</label>

                          {{ Form::number('purchase_price', null, ['class' => 'form-control price purPrice form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('purchase_price') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('sale_price') ? 'has-error' : '' }}" @if(!$product_sell_price) style="display: none" @endif>
                      <div class="">
                          <label class="">Sale Price</label>

                          {{ Form::number('sale_price', null, ['class' => 'form-control price salePrice form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('sale_price') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('margin') ? 'has-error' : '' }}">
                      <div class="">
                          <label class="">Margin</label>

                          {{ Form::number('margin', null, ['class' => 'form-control margin form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('margin') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('minimum_sale_price') ? 'has-error' : '' }}">
                      <div class="">
                          <label class="">Minimum Sale Price</label>

                          {{ Form::number('minimum_sale_price', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('minimum_sale_price') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('maximum_discount') ? 'has-error' : '' }}" @if(!$product_discount) style="display: none" @endif>
                      <div class="">
                          <label class="">Maximum Discount in %</label>

                          {{ Form::number('maximum_discount', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('maximum_discount') }}</span>
                      </div>
                  </div>

                  <div class="form-group col-md-6  {{ $errors->has('short_description') ? 'has-error' : '' }}">
                      <div class="">
                          <label class="">Short Description</label>

                          {{ Form::textarea('short_description', null, ['class' => 'form-control summernote form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('short_description') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-6  {{ $errors->has('description') ? 'has-error' : '' }}">
                      <div class="">
                          <label class="">Description</label>

                          {{ Form::textarea('description', null, ['class' => 'form-control summernote form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('description') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('warranty') ? 'has-error' : '' }}">
                      <div class="">
                          <label class="">Warranty</label>

                          {{ Form::number('warranty', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('warranty') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('location') ? 'has-error' : '' }}">
                      <div class="">
                          <label class="">Location Rack</label>

                          {{ Form::text('location_rack', null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Rack','required'=>'true']) }}
                          <label class="mt-5">Location Row</label>
                          {{ Form::text('location_row', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Row','required'=>'true']) }}
                          <label class="mt-5">Location Position</label>
                          {{ Form::text('location_position', null, ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Position','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('location') }}</span>
                      </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('images') ? 'has-error' : '' }}">
                      <div class="images">
                          <label class="">Images</label>
                          <i class="fa fa-plus-square addImage btn btn-outline-primary float-right btn-sm mb-2"></i>
                          <div class="row mt-5">
                              <div class="col-md-10">
                                  <input type="file" name="images[]" class="form-control" id="">

                              </div>

                          </div>
                          <span class="text-danger">{{ $errors->first('images') }}</span>
                      </div>
                      <table class="table mt-5 table-striped table-bordered table-hover">
                          <tr class="bg-light-info">
                              <td>Image</td>
                              <td>Action</td>
                          </tr>
                          @foreach($user->images as $card)
                              <tr>
                                  <td><a href="{{ asset("uploads/$card->image")}}" target="_blank"><img src="{{asset("uploads/$card->image")}}" width="100" alt=""></a></td>
                                  <td>
                                      <a href="{{route("product-image-delete",$card->id)}}"><i class="fa fa-trash-alt  btn btn-outline-danger float-right btn-sm mb-2"></i></a>
                                      @if ($card->thumb == 1)
                                          <i class="fa fa-check-square  btn btn-success float-right btn-sm mb-2 mr-2" title="Default Image"></i>
                                          @else
                                          <a href="{{route("product-image-default",$card->id)}}" title="Make it Default" class="mr-2"><i class="fa fa-check-square mr-2 btn btn-outline-success float-right btn-sm mb-2"></i></a>

                                      @endif
                                  </td>

                              </tr>
                          @endforeach
                      </table>
                  </div>
                  <div class="form-group col-md-4 ">
                      <label class="col-3 col-form-label">Active</label>
                      <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  @if($user->active) checked @endif  id="active" name="active" value="1">
                            <span></span>
                          </label>
                        </span>
                      </div>
                      <div class="active" @if($user->active) style="display: none" @endif >
                          <div class="form-group mt-5  {{ $errors->has('disable_reason') ? 'has-error' : '' }}">
                              <label class="">Disable reason</label>
                              <div class="">
                                  {{ Form::text('disable_reason', $user->reason, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('disable_reason') }}</span>
                              </div>
                          </div>

                      </div>
                      <label class="col-3 col-form-label">Is Regular</label>
                      <div class="col-3">
                          <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" @if($user->is_regular) checked @endif id="is_regular"  name="is_regular" >
                          <span></span>
                          </label>
                          </span>
                      </div>
                  </div>
                  <div class="form-group col-md-4 ">
                      <label class="col-form-label">Not For Sale</label>
                      <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  @if($user->prduct_description) checked @endif   name="not_for_sale" value="1">
                            <span></span>
                          </label>
                        </span>
                      </div>
                  </div>
                  <div class="form-group col-md-4 " @if(!$product_manage_stock) style="display: none" @endif>
                      <label class="col-form-label">Manage Stock</label>
                      <div class="">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  @if($user->manage_stock) checked @endif   class="sale_check" name="stock" value="1">
                            <span></span>
                          </label>
                        </span>
                      </div>
                      <div class="sale_div  " @if(!$user->manage_stock) style="display: none" @endif >

                          <div class="form-group mt-5   {{ $errors->has('quantity') ? 'has-error' : '' }}">
                              <label class=>Quantity</label>
                              <div class="">
                                  {{ Form::number('quantity', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('quantity') }}</span>
                              </div>
                          </div>
                          <div class="form-group  {{ $errors->has('alert_quantity') ? 'has-error' : '' }}">
                              <label class=>Alert Quantity</label>
                              <div class="">
                                  {{ Form::number('alert_quantity', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('alert_quantity') }}</span>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="form-group col-md-4 ">
                      <label class="col-form-label">Product Description</label>
                      <div class="">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" @if($user->product_description) checked @endif  class="product_description" name="product_description" value="1">
                            <span></span>
                          </label>
                        </span>
                      </div>
                      <div class="product_description_dev  "  @if(!$user->product_description) style="display: none" @endif  >

                          <div class="form-group mt-5   {{ $errors->has('serial_number') ? 'has-error' : '' }}">
                              <label class=>Serial Number</label>
                              <div class="">
                                  {{ Form::text('serial_number', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('serial_number') }}</span>
                              </div>
                          </div>
                          <div class="form-group  {{ $errors->has('imei') ? 'has-error' : '' }}">
                              <label class=>IMEI</label>
                              <div class="">
                                  {{ Form::text('imei', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                  <span class="text-danger">{{ $errors->first('imei') }}</span>
                              </div>
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
  </div>
@endsection
@section("scripts")
    <script !src="">
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
        $(".addImage").click(function(){
            $(".images").append("   <div class=\"row mt-5\">\n" +
                "                                    <div class=\"col-md-10\">\n" +
                "                                        <input type=\"file\" name=\"images[]\" class=\"form-control\" id=\"\">\n" +
                "\n" +
                "                                    </div>\n" +
                "                                  <div class=\"col-md-2\">\n" +
                "                                      <i class=\"fa fa-trash-alt removeImage btn btn-outline-danger float-right btn-sm mb-2\"></i>\n" +
                "                                  </div>\n" +
                "                              </div>")
        });
        $("body").on("click",".removeImage",function(){
            $(this).parent().parent().remove();
        });
        $(".price").change(function() {
            var purPrice =  parseInt($(".purPrice").val());
            var salePrice =  parseInt($(".salePrice").val());
            result  = salePrice - purPrice;
            $(".margin").val(result);
        });
        $(".margin").change(function() {
            var margin = parseInt($(".margin").val());
            var purPrice = parseInt($(".purPrice").val());
            result = margin + purPrice;
            $(".salePrice").val(result);
        });
        $(".summernote").summernote();
        $(document).ready(function() {
            getDevice();
            setTimeout(alertFunc, 1000);
        });
        function alertFunc() {
            var s2 = $('.device_model').find('select').select2({placeholder: "Select Devices"});
            var vals = [
                @foreach($user->devices as $device)
                    "{{$device->id}}",
                @endforeach
                ];
            s2.val(vals).trigger("change");
        }
        $("#active").change(function(){
            if($(this).prop("checked") == true){
                $(".active").hide();
            }else{
                $(".active").show();

            }
        });
        function getDevice(){
            var brand = $("#brand").val();
            var CSRF_TOKEN = '{{ csrf_token() }}';
            $.post("{{ route('product.getDeviceModels') }}", {_token: CSRF_TOKEN, brand: brand}).done(function (response) {
                $('.device_model').html(response);
                $(".device_model").find('select').attr("multiple", true);
                $(".device_model").find('select').attr("name", "devices[]");
                $('.device_model').find('select').select2({placeholder: "Select Devices"});
                $(".device_model").find('select option')
                    .filter(function() {
                        return !this.value || $.trim(this.value).length == 0 || $.trim(this.text).length == 0;
                    })
                    .remove();
                $(".device_model").find('select').val("");

            });
        }
        $("#brand").change(function () {
            getDevice();
        });
        $(".sale_check").change(function () {
            $(".sale_div").toggle();
        });
        $(".product_description").change(function () {
            $(".product_description_dev").toggle();
        });
    </script>
@endsection
