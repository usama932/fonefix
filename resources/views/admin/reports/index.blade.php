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
        <h3 class="card-label">Product Report </h3>
	      <div class="d-flex align-items-center ">
	      </div>
      </div>
      <div class="card-toolbar">


        <!--end::Button-->
      </div>
    </div>
    <div class="card-body">
	    @include('admin.partials._messages')
        <form action="{{ route('reports.search')}}">

        <div class="row">
                <div class="col-md-3 form-group ">
                    <div class="form-group  ">
                        <label class="">From Date </label>
                        {{ Form::date('from', null, ['class' => 'no-padding  form-control col-lg-12','required'=>'true']) }}
                    </div>
                </div>
                <div class="col-md-3 form-group ">
                    <div class="form-group  ">
                        <label class="">To Date </label>
                        {{ Form::date('to', null, ['class' => 'no-padding  form-control col-lg-12','required'=>'true']) }}
                    </div>
                </div>
                <div class="col-md-3 form-group ">
                    <div class="form-group  ">
                        <label class="">Product </label>
                        <select name="product" id="product" class="form-control ">
                            <option value="">Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id}}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-3 form-group ">
                    <div class="form-group  ">
                        <button type="submit" class="btn btn-primary mt-7 btn-sm">Search</button>
                    </div>
                </div>

        </div>
        </form>

        <div class="table-responsive">
	      <form action="{{route('admin.delete-selected-statuses')}}" method="post" id="client_form">
	      <input type="hidden" name="_token" value="{{ csrf_token() }}">
      <!--begin: Datatable-->
      <table class="table table-bordered table-hover table-checkable"  style="margin-top: 13px !important">
        <thead>
            <tr>
              <th>Product</th>
              <th>Quantity</th>
              <th>Date</th>
            </tr>
        </thead>
          <tbody>
                @if($data)
                        @if($data->isNotEmpty())
                      @foreach($data as $datum)
                        <tr>
                            <td>{{$datum->product->name}}</td>
                            <td>{{$datum->quantity}}</td>
                            <td>{{date('d-m-Y',strtotime($datum->created_at))}}</td>
                        </tr>
                      @endforeach
                            @else
                             <tr>
                                 <td colspan="3"><span class="text-center text-danger ">No Data found</span></td>
                             </tr>
                            @endif
                @endif
          </tbody>
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
					  <h4 class="modal-title" id="myModalLabel">Status Detail</h4> </div>
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
      $("#product").select2();
      $(document).on('click', 'th input:checkbox', function () {

          var that = this;
          $(this).closest('table').find('tr > td:first-child input:checkbox')
              .each(function () {
                  this.checked = that.checked;
                  $(this).closest('tr').toggleClass('selected');
              });
      });
      var clients = $('#clients').DataTable( {
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
              {"data":"created_at"},
              {"data":"action","searchable":false,"orderable":false}
          ]
      } );
      function viewInfo(id) {

          var CSRF_TOKEN = '{{ csrf_token() }}';
          $.post("{{ route('admin.getStatus') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
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

  </script>
@endsection
