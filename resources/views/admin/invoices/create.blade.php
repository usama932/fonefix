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
                     <a href="" class="text-muted">Manage Invoice</a>
                  </li>
                  <li class="breadcrumb-item text-muted">
                     <a href="" class="text-muted">Add Invoice</a>
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
                  <h3 class="card-label">Add Invoice
                     <i class="mr-2"></i>
                     <small class="">try to scroll the page</small>
                  </h3>
               </div>
               <div class="card-toolbar">
                  <a href="{{ route('invoices.index') }}" class="btn btn-light-primary
                     font-weight-bolder mr-2">
                  <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>
                  <div class="btn-group">
                     <a href="#"   id="kt_btn" page="1" class="btn btn-success submit font-weight-bolder">
                     <i class="fa fa-credit-card icon-sm"></i>Card</a>
                     <a href="#"   id="kt_btn" page="2" class="btn btn-danger submit font-weight-bolder">
                     <i class="fa fa-money-bill icon-sm"></i>Cash</a>
                     <a href="#"   id="kt_btn" page="3" class="btn btn-warning submit font-weight-bolder">
                     <i class="fa fa-address-card icon-sm"></i>Credit</a>
                  </div>
               </div>
            </div>
            <div class="card-body">
               @include('admin.partials._messages')
               <!--begin::Form-->
               {{ Form::open([ 'route' => 'invoices.store','class'=>'form' ,"id"=>"client_add_form", 'enctype'=>'multipart/form-data']) }}
               @csrf
               <div class="row">
                  <input type="hidden" name="" value="1" class="check-quantity">
                  <div class="col-md-4 form-group ">
                     <div class="form-group  {{ $errors->has('user') ? 'has-error' : '' }}">
                        <label class="">User </label>
                        <i class="fa fa-plus-square addUser btn btn-outline-primary float-right btn-sm mb-2"></i>
                        <select name="user" id="user" class="form-control select-2">
                           {{--
                           <option value="">Select User</option>
                           --}}
                           @foreach($users as $key => $value)
                           <option value="{{$value->id}}">{{$value->name}} ({{$value->phone}})</option>
                           @endforeach
                        </select>
                        <span class="text-danger">{{ $errors->first('user') }}</span>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-4 {{ $errors->has('product') ? 'has-error' : '' }}">
                     <label class="">Products</label>
                     <div class="col-md-12">
                        <select class="form-control" id="product">
                           <option value="">Select Product</option>
                           @foreach($products as $product)
                           <option value="{{$product->id}}" amount="{{$product->sale_price}}">{{$product->name}}</option>
                           @endforeach
                        </select>
                        {{--                                    {{ Form::select('product',$products, null, ['class' => 'no-padding product form-control col-lg-12','id'=>'product']) }}--}}
                        <span class="text-danger">{{ $errors->first('product') }}</span>
                     </div>
                  </div>
                  <div class="form-group col-md-4  {{ $errors->has('quantity') ? 'has-error' : '' }}">
                     <label class="">Quantity</label>
                     <div class="col-md-12">
                        {{ Form::number('quantity', null, ['class' => 'form-control quantity form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('quantity') }}</span>
                     </div>
                  </div>
                  <div class="col-md-4 form-group ">
                     <div class="form-group  ">
                        <button type="button" class="btn add btn-primary mt-8 btn-sm">Add</button>
                     </div>
                  </div>
               </div>
               <div class="form-group row">
                <label class="col-3 col-form-label">Send notification SMS </label>
                <div class="col-3">
                   <span class="switch switch-outline switch-icon switch-success">
                   <label><input type="checkbox"  name="sms" value="1">
                   <span></span>
                   </label>
                   </span>
                </div>
             </div>
             {{--  <div class="form-group row">
                <label class="col-3 col-form-label">Send notification Email </label>
                <div class="col-3">
                   <span class="switch switch-outline switch-icon switch-success">
                   <label><input type="checkbox"  name="email" value="1">
                   <span></span>
                   </label>
                   </span>
                </div>
             </div>  --}}
               <table class="table" >
                  <tr class="bg-light-info">
                     <th>Product</th>
                     <th>Quantity</th>
                     <th>Amount</th>
                     <th>Sub Total</th>
                     <th>Action</th>
                  </tr>
                  <tbody id="product_table">
                  </tbody>
               </table>
               <table class="table">
                  <tr>
                     <th>Total</th>
                     <input type="hidden" name="total" id="total" value="0">
                     <input type="hidden" name="discount" id="discount" value="0">
                     <input type="hidden" name="discount_type" id="discount_type" value="1">
                     <input type="hidden" name="payment_type" id="payment_type" value="1">
                     <input type="hidden" name="sn" id="products" value="0">
                     <td >$<span class="ov_total_html">0.00</span></td>
                  </tr>
                  <tr>
                     <th>Discount <i class="fa fa-edit addDiscount btn btn-outline-primary  btn-sm mb-2"></i></th>
                     <td><span class="discount-html">0.00</span> </td>
                  </tr>
                  <tr>
                     <th class="text-success">Payable Amount</th>
                     <td>$<span class="text-green payable-amount-html">0.00</span></td>
                  </tr>
               </table>
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
<div class="modal fade" id="clientModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel">Discount</h4>
         </div>
         <div class="modal-body">
            <div class="form-group row">
               <label for="">Discount Type</label>
               <select name="" class="form-control" id="pop-discount-type">
                  <option value="1">Fixed</option>
                  <option value="2">Percentage</option>
               </select>
            </div>
            <div class="form-group row">
               <label for="">Discount Amount</label>
               <input type="number" name="" id="pop-discount-amount" class="form-control" value="0">
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" id="update-discount-amount" class="btn btn-primary font-weight-bold" data-dismiss="modal">Update</button>
            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
         </div>
      </div>
   </div>
</div>
<!-- Modal-->
<div class="modal fade" id="userModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h4 class="modal-title" id="myModalLabel">Add Customer</h4>
         </div>
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
   $(".addUser").click(function(){
       $('#userModel').modal('show');
   });
   $("body").on("click",".submit",function () {
       var page = $(this).attr("page");
       $("#payment_type").val(page);

       if($("#products").val() == 0){
           Swal.fire(
               "Sorry!",
               "There is no Product",
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
   $("#update-discount-amount").click(function(){
       var total  = parseInt($("#total").val());
       var type  = $("#pop-discount-type").val();
       var dis_value = parseInt($("#pop-discount-amount").val());
       $("#discount").val(dis_value);
       $("#discount_type").val(type);
       if (type == 1){
           $(".discount-html").html(dis_value.toFixed(2));
           var result = parseInt($("#total").val()) - dis_value;
           $(".payable-amount-html").html(result.toFixed(2));

       }else{
           var result_dis = ( total * dis_value / 100 );
           $(".discount-html").html(result_dis.toFixed(2));
           var result = parseInt($("#total").val()) - result_dis;
           $(".payable-amount-html").html(result.toFixed(2));
       }

   });
   $("#status").change(function () {
       $("#client_update_form").submit();
   });

   $(".addDiscount").click(function () {
       $('#clientModel').modal('show');
   });

   $("body").on("click",".removeProduct",function () {
       $(this).parent().parent().remove();
       var old_sn = $("#products").val();
       old_sn = parseInt(old_sn) - 1;
       $("#products").val(old_sn);
       calculate();
   });

   $("body").on("click",".add",function () {
       var quantity = $(".quantity").val();
       var product = $("#product").val();
       var check_quantity = parseInt($(".check-quantity").val());
       if(product == ""){
           Swal.fire(
               "Sorry!",
               "Please Select Product",
               "error"
           );
       }else if(quantity == ""){
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
               var description = $("#product option:selected").html();
               var product_id = $("#product").val();
               var amount = $("#product option:selected").attr('amount');
               var result = quantity * amount;
               result = result.toFixed(2);
               $("#product_table").append(" <tr>\n" +
                   "                                <td>\n" +
                   "                                    <input type=\"text\" readonly name=\"description[]\" value=\""+description+"\" class=\"form-control products \">\n" +
                   "                                    <input type=\"hidden\"  name=\"product_ids[]\" value=\""+product_id+"\" class=\"form-control product_ids \">\n" +
                   "                                </td>\n" +
                   "                                <td>\n" +
                   "                                    <input type=\"number\" readonly name=\"qty[]\" value=\""+quantity+"\" class=\"form-control qty \">\n" +
                   "                                </td>\n" +
                   "                                <td>\n" +
                   "                                    <input type=\"number\" readonly name=\"amount[]\" value=\""+amount+"\" class=\"form-control amount \">\n" +
                   "                                </td>\n" +
                   "                                <td>\n" +
                   "                                    <input type=\"number\" readonly name=\"sub_total[]\" value=\""+result+"\" class=\"form-control sub_total \">\n" +
                   "                                </td>\n" +
                   "                                <td><a href=\"#\" class=\"btn btn-danger btn-sm removeProduct\"><i class=\"fa fa-times\"></i></a></td>\n" +
                   "\n" +
                   "                            </tr>")
               var old_sn = $("#products").val();
               old_sn = parseInt(old_sn) + 1;
               $("#products").val(old_sn);
               calculate();
           }
       }
   });
   function calculate(){
       var total = 0;
       $("#product_table tr").each(function(){
           var sub_total = $(this).find(".sub_total").val();
           total = parseInt(total) + parseInt(sub_total);
       });
       $("#total").val(total.toFixed(2));
       $(".ov_total_html").html(total.toFixed(2));
       $("#update-discount-amount").trigger("click");
   }
   $("#product").change(function() {
      checkQty();
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
   $("#product").select2();
   $("#user").select2();

</script>
@endsection
