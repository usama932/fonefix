@extends('admin.layouts.master')
@section('title',$title)
@section('content')
    <!--begin::Card-->
    <div class="card card-custom">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>30 Days Arrest Log</h1>
                    </div>
                    <div class="col-sm-6">
                        {{-- <ol class="breadcrumb float-sm-right">
                             <a class="btn btn-light-primary btn-sm"
                                onclick="del_selected()" href="javascript:void(0)">
                                 <i class="la la-trash-o"></i>
                                 Delete All
                             </a> &nbsp;&nbsp;&nbsp;
                             <a class="btn btn-info btn-sm" href="{{ route('tickets.create') }}">
                                 <i class="fas fa-pencil-alt">
                                 </i>
                                 New Record
                             </a>
                         </ol>--}}
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="row">
            <!-- /.col -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">30 Days Arrest Log</h3>

                        {{--<div class="card-tools">
                            <ul class="pagination pagination-sm float-right">
                                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                            </ul>
                        </div>--}}
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Date</th>
                                <th>Vehicle No</th>
                                <th style="width: 40px">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tickets as $ticket)
                                @if($ticket->status != 1)
                                    <tr>
                                        <td>{{$ticket->id}}</td>
                                        <td>{{$ticket->updated_at}}</td>
                                        <td>{{$ticket->vehicles['vehicle_no']}}</td>
                                        <td><span class="badge bg-danger">Out of Service</span></td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>No</td>
                                        <td>Out</td>
                                        <td>of Service</td>
                                        <td>Vehicle</td>
                                    </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
    </div>
    <!--end::Card-->
@endsection
@section('stylesheets')
    <!--begin::Page Vendors Styles(used by this page)-->
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
          type="text/css"/>
    <!--end::Page Vendors Styles-->
@endsection
@section('scripts')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script type="text/javascript">
        function autoRefreshPage()
        {
            window.location = window.location.href;
        }
        setInterval('autoRefreshPage()', 10000);
    </script>
@endsection
