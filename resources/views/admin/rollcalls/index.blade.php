@extends('admin.layouts.master')
@section('title',$title)
@section('content')
  <!--begin::Card-->
  <div class="card card-custom">
      <section class="content-header">
          <div class="container-fluid">
              <div class="row mb-2">
                  <div class="col-sm-6">
                      <h1>RollCall  List</h1>
                  </div>
                  <!-- <div class="col-sm-6">
                      <ol class="breadcrumb float-sm-right">
                          <a class="btn btn-light-primary btn-sm" onclick="del_selected()" href="javascript:void(0)">
                              <i class="la la-trash-o"></i>
                              Delete All
                          </a> &nbsp;&nbsp;&nbsp;
                          <a class="btn btn-info btn-sm" href="{{ route('users.create') }}">
                              <i class="fas fa-pencil-alt">
                              </i>
                              New Record
                          </a>
                      </ol>
                  </div> -->
              </div>
          </div><!-- /.container-fluid -->
      </section>
    <div class="card-body">
	    @include('admin.partials._messages')
      <div class="table-responsive">
	      <form action="{{route('admin.delete-selected-users')}}" method="post" id="user_form">
	      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <!--begin: Datatable-->
      <table class="table table-bordered table-hover table-checkable" id="users" style="margin-top: 13px !important">
        <thead>
        <tr>
          <th>Name</th>
        

        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
            <a href="{{ route('72-hours-cad') }}" >
            72 Hour CAD
            </a>  
            </td>
        
        </tr>
        <tr>
            <td> 
             <a href="{{ route('30-days-arrest-log') }}" >
              30 Day Arrest Log
            </a>
            </td>
         
        </tr>
        <tr>
            <td> 
                <a href="{{ route('cad_dashboard.index') }}" >
                   CAD Dashboard
                </a>
            </td>
        
        </tr>    
        </tbody>
      </table>
	      </form>
      <!--end: Datatable-->
    </div>
    </div>
	  <!-- Modal-->
	  <!-- <div class="modal fade" id="userModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
			  <div class="modal-content">
				  <div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					  <h4 class="modal-title" id="myModalLabel">RollCall Detail</h4> </div>
				  <div class="modal-body"></div>
				  <div class="modal-footer">
					  <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
				  </div>
			  </div>
		  </div>
	  </div> -->
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
  <!-- <script> -->
   <!-- $(document).on('click', 'th input:checkbox', function () {

    //       var that = this;
    //       $(this).closest('table').find('tr > td:first-child input:checkbox')
    //           .each(function () {
    //               this.checked = that.checked;
    //               $(this).closest('tr').toggleClass('selected');
    //           });
    //   });
    //   var users = $('#users').DataTable( {
    //       "order": [
    //           [1, 'asc']
    //       ],
    //       "processing": true,
    //       "serverSide": true,
    //       "searchDelay": 500,
    //       "responsive": true,
    //       "ajax": {
    //           "url":"",
    //           "dataType":"json",
    //           "type":"POST",
    //           "data":{"_token":"<?php echo csrf_token() ?>"}
    //       },
    //       "columns":[
    //           {"data":"id","searchable":false,"orderable":false},
    //           {"data":"name"},
    //           {"data":"action","searchable":false,"orderable":false}
    //       ]
    //   } );
    
    //   function viewInfo(id) {

    //       var CSRF_TOKEN = '{{ csrf_token() }}';
    //       $.post("{{ route('admin.getUser') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
    //           $('.modal-body').html(response);
    //           $('#userModel').modal('show');

    //       });
    //   }
    //   function del(id){
    //       Swal.fire({
    //           title: "Are you sure?",
    //           text: "You won't be able to revert this!",
    //       icon: "warning",
    //           showCancelButton: true,
    //           confirmButtonText: "Yes, delete it!"
    //   }).then(function(result) {
    //           if (result.value) {
    //               Swal.fire(
    //                   "Deleted!",
    //                   "Your Rollcall has been deleted.",
    //                   "success"
    //               );
    //               var APP_URL = {!! json_encode(url('/')) !!}
    //               window.location.href = APP_URL+"/admin/user/delete/"+id;
    //           }
    //       });
    //   }
    //   function del_selected(){
    //       Swal.fire({
    //           title: "Are you sure?",
    //           text: "You won't be able to revert this!",
    //           icon: "warning",
    //           showCancelButton: true,
    //           confirmButtonText: "Yes, delete it!"
    //       }).then(function(result) {
    //           if (result.value) {
    //               Swal.fire(
    //                   "Deleted!",
    //                   "Your RollCall has been deleted.",
    //                   "success"
    //               );
    //               $("#user_form").submit();
    //           }
    //       });
    //   } -->

  <!-- </script> -->
@endsection
