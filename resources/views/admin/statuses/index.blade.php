@extends('admin.layouts.master')
@section('title',$title)
@section('content')
<!--begin::Card-->
<div class="card card-custom">
   <div class="card-header">
      <div class="card-title">
         <span class="card-icon">
         <i class="flaticon-users text-primary"></i>
         </span>
         <h3 class="card-label">Statuses List</h3>
         @if(auth()->user()->role == '1')
         <div class="form-group my-auto">

         <select id='approved' class="form-control" style="width: 200px">

             <option value="1">Admin</option>
             @foreach($shops as $shop)
             <option value="{{ $shop->id }}">{{ $shop->name }}</option>
             @endforeach
         </select>
         </div>
         @endif

         @if(auth()->user()->custom_status == 1 || auth()->user()->role == 1)
         <div class="d-flex align-items-center ">
            <a class="btn btn-danger font-weight-bolder" onclick="del_selected()" href="javascript:void(0)"> <i class="la la-trash-o"></i>Delete All</a>
         </div>
         @endif

      </div>
      <div class="card-toolbar">
         <!--begin::Button-->
         @php
         $user = Auth::user();
         if($user->role == 1){
         $add = 1;
         }elseif($user->role == 2){
         $add = 1;
         }elseif($user->role == 3){
         $add = $user->permission->setting_job_edit;
         }
         @endphp
         @if(auth()->user()->custom_status == 1 || auth()->user()->role == 1 )

         <a href="{{ route('statuses.create') }}" class="btn btn-primary font-weight-bolder mr-2">
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
         <div class="btn-group">
            <a href="{{ route('status-import') }}"  class="btn btn-success font-weight-bolder">
            <i class="fa fa-file-import icon-sm"></i>Import</a>
            <a href="{{ route('status-export') }}"  id="kt_btn" class="btn btn-primary font-weight-bolder">
            <i class="fa fa-file-excel icon-sm"></i>Export</a>
         </div>
         @endif

         <!--end::Button-->
      </div>
   </div>
   <div class="card-body">
      @include('admin.partials._messages')
      <div class="table-responsive">
         <form action="{{route('admin.delete-selected-statuses')}}" method="post" id="client_form">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <!--begin: Datatable-->
            <table class="table table-bordered table-hover table-checkable" id="clients" style="margin-top: 13px !important">
               <thead>
                  <tr>
                     <th>
                        <label class="checkbox checkbox-outline checkbox-success"><input type="checkbox"><span></span></label>
                     </th>
                     <th>Name</th>
                     @if(auth()->user()->role == 1)
                     <th>Shop</th>
                     @endif
                     <th>Created At</th>

                     <th>Actions</th>

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
                <h4 class="modal-title" id="myModalLabel">Status Detail</h4>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="payModel" data-backdrop="static" tabindex="-1" role="dialog"
        aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Status Used</h4>
                </div>
                <div class="modal-body"></div>

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
           [1, 'asc']
       ],
       "processing": true,
       "serverSide": true,
       "searchDelay": 500,
       "responsive": true,
       "ajax": {
           "url":"{{ route('admin.getStatuses') }}",
           "dataType":"json",
           "type":"POST",
           "data":{"_token":"<?php echo csrf_token() ?>"}
       },
       "columns":[
           {"data":"id","searchable":false,"orderable":false},
           {"data":"name"},
           @if(auth()->user()->role == 1)
           {"data":"shop"},
           @endif
           {"data":"created_at"},

           {"data":"action","searchable":false,"orderable":false}
         
       ]
   } );
    function viewInfo(id)
    {
       var CSRF_TOKEN = '{{ csrf_token() }}';
       $.post("{{ route('admin.getStatus') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
           $('.modal-body').html(response);
           $('#clientModel').modal('show');

       });
    }
    function view_used(id) {
        var CSRF_TOKEN = '{{ csrf_token() }}';
        $.post("{{ route('admin.getUsedStatus') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
            $('.modal-body').html(response);
            $('#payModel').modal('show');

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
                   "Your Status has been deleted.",
                   "success"
               );
               var APP_URL = {!! json_encode(url('/')) !!}
               window.location.href = APP_URL+"/admin/status/delete/"+id;
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
                   "Your Status has been deleted.",
                   "success"
               );
               $("#client_form").submit();
           }
       });
   }
   $("#approved").change(function () {
    var table = $('#clients').DataTable();
    table.destroy();
    var clients = $('#clients').DataTable( {
        "pageLength": 100,
        "aLengthMenu": [[100, 250, 500,1000], [100, 250, 500,1000]],
        "order": [
            [1, 'asc']
        ],
        "processing": true,
        "serverSide": true,
        "searchDelay": 500,
        "responsive": true,
        "ajax": {
            "url":"{{ route('admin.getStatuses') }}",
            "dataType":"json",
            "type":"POST",
            "data":{"_token":"<?php echo csrf_token() ?>",'shop_id':$(this).val()}

        },
        "columns":[
            {"data":"id","searchable":false,"orderable":false},
            {"data":"name"},
            @if(auth()->user()->role == 1)
            {"data":"shop"},
            @endif
            {"data":"created_at"},
            {"data":"action","searchable":false,"orderable":false}

        ]
    } );
});
$("#approved").select2();
</script>
@endsection
