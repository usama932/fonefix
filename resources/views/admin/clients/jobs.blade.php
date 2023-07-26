@extends('admin.layouts.master')
@section('title',$title)
@section('content')
  <!--begin::Card-->
  <div class="card card-custom ">
    <div class="card-header">
      <div class="card-title">
											<span class="card-icon">
												<i class="flaticon-users text-primary"></i>
											</span>
        <h3 class="card-label">Jobs List</h3>
	      <div class="d-flex align-items-center ">
		      <a class="btn btn-danger font-weight-bolder" onclick="del_selected()" href="javascript:void(0)"> <i class="la la-trash-o"></i>Delete All</a>
	      </div>
      </div>
      <div class="card-toolbar">

        <!--begin::Button-->
{{--        <a href="{{ route('jobs.create') }}" class="btn btn-primary font-weight-bolder">--}}
{{--											<span class="svg-icon svg-icon-md">--}}
{{--												<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->--}}
{{--												<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">--}}
{{--													<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">--}}
{{--														<rect x="0" y="0" width="24" height="24" />--}}
{{--														<circle fill="#000000" cx="9" cy="15" r="6" />--}}
{{--														<path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />--}}
{{--													</g>--}}
{{--												</svg>--}}
{{--                        <!--end::Svg Icon-->--}}
{{--											</span>New Record</a>--}}
{{--        <a href="{{ route('product-import') }}" class="btn btn-info font-weight-bolder ml-5">--}}
{{--											<span class="svg-icon svg-icon-md">--}}
{{--												<!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->--}}
{{--												<i class="fa fa-file-excel"></i>--}}
{{--                        <!--end::Svg Icon-->--}}
{{--											</span>Import Products</a>--}}

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
            @if(Auth::user()->role == 1)
          <th>Shop</th>
            @endif
          <th>Brand Name</th>
          <th>Device</th>
          <th>Device Model</th>
          <th>Serial Number</th>
          <th>Estimate Cost</th>
          <th>Payment</th>
          <th>Created At</th>
        </tr>
        </thead>
          <tbody>
            @foreach ($jobs as $job)
              <tr>
                  <td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="Jobs[]" value="{{$job->id}}"><span></span></label></td>
                  <td>

                      <a title="Edit " class="btn btn-sm btn-clean btn-icon"
                         href="{{ route('jobs.edit',$job->id)}}">
                          <i class="icon-1x text-dark-50 flaticon-edit"></i>
                      </a>
                      <a title="Show Invoice " class="btn btn-sm btn-clean btn-icon"
                         href="{{route('job-invoice',$job->id)}}">
                          <i class="icon-1x text-dark-50 flaticon-file-1"></i>
                      </a>
                      <a title="Add parts" class="btn btn-sm btn-clean btn-icon"
                         href="{{route('job-parts',$job->id)}}">
                          <i class="icon-1x text-dark-50 flaticon-plus"></i>
                      </a>
                      <a title="Show" class="btn btn-sm btn-clean btn-icon"
                         href="{{route('jobs.show',$job->id)}}">
                          <i class="icon-1x text-dark-50 flaticon-eye"></i>
                      </a>
                      <a class="btn btn-sm btn-clean btn-icon" onclick="event.preventDefault();del('.$r->id.');" title="Delete " href="javascript:void(0)">
                          <i class="icon-1x text-dark-50 flaticon-delete"></i>
                      </a>
                  </td>
                  <td>
                      @if($job->service_type == 1)
                          Carry In
                      @elseif($job->service_type == 2)
                          Pick Up
                      @elseif($job->service_type == 3)
                          On Site
                      @else
                          Courier
                      @endif
                  </td>
                  <td>{{ $job->expected_delivery}}</td>
                  <td>{{ $job->job_sheet_number}}</td>

                  <td>
                      @if($job->invoice)
                      {{ $job->invoice->number}}
                          @endif
                  </td>
                  <td>
                      @if($job->status_id)
                          {{$job->stat->name}}

                      @else
                          Nil
                      @endif
                  </td>
                  <td>{{ $job->customer->name }}</td>
                  @if(Auth::user()->role == 1)
                  <td>{{ $job->shop->name }}</td>
                  @endif
                  <td>{{ $job->brand->name }}</td>
                  <td>
                      @if($job->device->type == 1)
                          Mobile
                      @elseif($job->device->type == 2)
                          Laptop
                      @endif
                  </td>
                  <td>{{ $job->device->name }}</td>
                  <td>{{ $job->serial_number}}</td>
                  <td>{{ $job->cost}}</td>
                  <td>
                      @if($job->invoice)
                          @if(!$job->credit)
                              @if(!$job->notPaid)
                                  Paid
                                @else
                                  Unpaid
                              @endif
                          @else
                              Unpaid
                          @endif
                      @else
                          Unpaid
                      @endif
                  </td>
                  <td>{{ date('d-m-Y',strtotime($job->created_at)) }}</td>
              </tr>
            @endforeach
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
					  <h4 class="modal-title" id="myModalLabel">Job Detail</h4> </div>
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
      var clients = $('#clients').DataTable(  );
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
@endsection
