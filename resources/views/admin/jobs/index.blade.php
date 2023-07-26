@extends('admin.layouts.master')
@section('title',$title)
@section('content')
<!--begin::Card-->
<div class="container text-center my-3">
@if(auth()->user()->role == 2)
    @if(!empty($informations))
        @foreach ($informations->images as $image)
            <div class="row">
                <div class="col-md-6 my-3">
                    <img src="{{ asset('uploads') }}/{{ $image->image_1 ?? ''}}" class="rounded " style="height: 100px !important; width:500px !important;">
                </div>

                <div class="col-md-6 my-3">
                    <img src="{{ asset('uploads') }}/{{ $image->image_2 ?? ''}}" class="rounded " style="height: 100px !important; width:500px !important;">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 my-3">
                    <img src="{{ asset('uploads') }}/{{ $image->image_3 ?? ''}}" class="rounded " style="height: 100px !important ; width:500px !important;">
                </div>

                <div class="col-md-6 my-3">
                    <img src="{{ asset('uploads') }}/{{ $image->image_4 ?? ''}}" class="rounded " style="height: 100px !important; width:500px !important;">
                </div>
            </div>
        @endforeach
    @endif
@endif
</div>
<div class="card card-custom ">
   <div class="card-header">
      <div class="card-title">
         <span class="card-icon">
         <i class="flaticon-users text-primary"></i>
         </span>
         <h3 class="card-label">Jobs List</h3>
         <div class="d-flex align-items-center mx-auto">
            <a class="btn btn-danger font-weight-bolder" onclick="del_selected()" href="javascript:void(0)"> <i class="la la-trash-o"></i>Delete All</a>
         </div>

      </div>
      @if(auth()->user()->role == '1')
        <div class="form-group my-auto float-right">
            <select id='approved' class="form-control" style="width: 200px">
                <option value="">--Select Shop-</option>
                @foreach($shops as $shop)
                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                @endforeach
            </select>
        </div>
      @endif
      <div class="card-toolbar">
         @php
         $user = Auth::user();
         if($user->role == 1){
         $add = 1;
         }elseif($user->role == 2){
         $add = 1;
         }elseif($user->role == 3){
         $add = $user->permission->job_add;
         }
         @endphp
         @if($add)
         <!--begin::Button-->
         <a href="{{ route('jobs.create') }}" class="btn btn-primary mr-2 font-weight-bolder">
            <span class="svg-icon svg-icon-md">
               <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
               <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                     <rect x="0" y="0" width="24" height="24" />
                     <circle fill="#000000" cx="9" cy="15" r="6" />
                     <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                  </g>
               </svg>
               <!--end::Svg Icon-->
            </span>
            New Record
         </a>
         @endif

         <div class="btn-group">
            <a href="{{ route('job-import') }}"  class="btn btn-success font-weight-bolder">
            <i class="fa fa-file-import icon-sm"></i>Import</a>
            <a href="{{ route('job-export') }}"  id="kt_btn" class="btn btn-primary font-weight-bolder">
            <i class="fa fa-file-excel icon-sm"></i>Export</a>
         </div>
         <!--end::Button-->
      </div>
   </div>
   <div class="card-body">
      @include('admin.partials._messages')
      <div class="table-responsive">
         <form action="{{route('admin.delete-selected-jobs')}}" method="post" id="client_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <!--begin: Datatable-->
            <table class="table table-bordered table-hover " id="clients" style="margin-top: 13px !important">
               <thead>
                  <tr>
                     <th>
                        <label class="checkbox checkbox-outline checkbox-success"><input type="checkbox"><span></span></label>
                     </th>
                     <th>Action</th>
                     <th>Service Type</th>
                     <th>Expected Delivery Date</th>
                     <th>Job Sheet Number</th>
                     <th>Invoice Number</th>
                     <th>Status</th>
                     <th>Customer</th>
                     <th>Estimate Cost</th>
                     <th>Part Price</th>
                     <th>Invoice Price</th>
                     <th>Brand Name</th>
                     <th>Device</th>
                     <th>Device Model</th>
                     <th>Serial Number</th>
                     @if(Auth::user()->role == 1)
                     <th>Shop</th>
                     @endif
                     <th>Payment</th>
                     <th>Created At</th>
                  </tr>
               </thead>
            </table>
         </form>
         <!--end: Datatable-->
      </div>
   </div>
   <!-- Modal-->
   <div class="modal fade" id="clientModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
               <h4 class="modal-title" id="myModalLabel">Edit Status</h4>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
               <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
</div>
<!--end::Card-->
@endsection
@section('stylesheets')
<!--begin::Page Vendors Styles(used by this page)-->
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
   type="text/css" />
<!--end::Page Vendors Styles-->
@endsection
@section('scripts')
<!--begin::Page Vendors(used by this page)-->
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<!--end::Page Vendors-->
<script>
   $(document).on('click', 'th input:checkbox', function () {

       var that = this;
       $(this).closest('table').find('tr > td:first-child input:checkbox')
           .each(function () {
               this.checked = that.checked;
               $(this).closest('tr').toggleClass('selected');
           });
   });
   var clients = $('#clients').DataTable( {
       "pageLength": 100,
       "aLengthMenu": [[100, 250, 500,1000], [100, 250, 500,1000]],
       "order": [
           [14, 'desc']
       ],
       "processing": true,
       "serverSide": true,
       "searchDelay": 500,
       // "responsive": true,
       "ajax": {
           "url":"{{ route('admin.getJobs') }}",
           "dataType":"json",
           "type":"POST",
           "data":{"_token":"<?php echo csrf_token() ?>"}
       },
       "columns":[
           {"data":"check","searchable":false,"orderable":false},
           {"data":"action","searchable":false,"orderable":false},

           {"data":"service_type","searchable":false,"orderable":false},
           {"data":"expected_delivery"},
           {"data":"job_sheet_number"},
           {"data":"invoice_number"},
           {"data":"status"},
           {"data":"customer_name"},
           {"data":"cost"},
           {"data":"parts_price","searchable":false,"orderable":false},
           {"data":"invoiceprice","searchable":false,"orderable":false},
           {"data":"brand_name"},
           {"data":"device","searchable":false,"orderable":false},
           {"data":"device_name"},
           {"data":"serial_number"},
           @if(Auth::user()->role == 1)
             {"data":"shop_name","searchable":false,"orderable":false},
           @endif
           {"data":"paid","searchable":false,"orderable":false},
           {"data":"created_at","searchable":false,"orderable":false},
       ]
   } );
   function viewInfo(id) {

       var CSRF_TOKEN = '{{ csrf_token() }}';
       $.post("{{ route('admin.getJob') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
           $('.modal-body').html(response);
           $('#clientModel').modal('show');

       });
   }
   function del(id){
       Swal.fire({
           title: "Are you sure?",
           text: "You won't be able to revert this!",
       icon: "warning",
           showCancelButton: true,
           confirmButtonText: "Yes, delete it!"
   }).then(function(result) {
           if (result.value) {
               Swal.fire(
                   "Deleted!",
                   "Your Product has been deleted.",
                   "success"
               );
               var APP_URL = {!! json_encode(url('/')) !!}
               window.location.href = APP_URL+"/admin/job/delete/"+id;
           }
       });
   }
   function del_selected(){
       Swal.fire({
           title: "Are you sure?",
           text: "You won't be able to revert this!",
           icon: "warning",
           showCancelButton: true,
           confirmButtonText: "Yes, delete it!"
       }).then(function(result) {
           if (result.value) {
               Swal.fire(
                   "Deleted!",
                   "Your Products has been deleted.",
                   "success"
               );
               $("#client_form").submit();
           }
       });
   }

</script>

<script>
    $("#approved").change(function () {
        var table = $('#clients').DataTable();
        table.destroy();
       //alert($(this).val());

        var clients = $('#clients').DataTable( {
            "pageLength": 100,
            "aLengthMenu": [[100, 250, 500,1000], [100, 250, 500,1000]],
            "order": [
                [14, 'desc']
            ],
            "processing": true,
            "serverSide": true,
            "searchDelay": 500,
            // "responsive": true,
            "ajax": {
                "url":"{{ route('admin.getJobs') }}",
                "dataType":"json",
                "type":"POST",
                "data":{"_token":"<?php echo csrf_token() ?>",'shop_id':$(this).val()}
            },
            "columns":[
                {"data":"check","searchable":false,"orderable":false},
                {"data":"action","searchable":false,"orderable":false},

                {"data":"service_type","searchable":false,"orderable":false},
                {"data":"expected_delivery"},
                {"data":"job_sheet_number"},
                {"data":"invoice_number","searchable":false,"orderable":false},
                {"data":"status"},
                {"data":"customer_name"},
                {"data":"cost"},
                {"data":"parts_price","searchable":false,"orderable":false},
                {"data":"invoiceprice","searchable":false,"orderable":false},
                {"data":"brand_name"},
                {"data":"device","searchable":false,"orderable":false},
                {"data":"device_name"},
                {"data":"serial_number"},
                @if(Auth::user()->role == 1)
                  {"data":"shop_name","searchable":false,"orderable":false},
                @endif
                {"data":"paid","searchable":false,"orderable":false},
                {"data":"created_at","searchable":false,"orderable":false},
            ]
        } );
    });
</script>
@endsection
