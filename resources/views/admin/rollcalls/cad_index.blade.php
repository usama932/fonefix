@extends('admin.layouts.master')
@section('title',$title)
@section('content')
    <!--begin::Card-->
    <div class="card card-custom">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>CAD Dashboard</h1>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Active Calls For Service</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th >Case #</th>
                                <th>Type</th>
                                <th>Address</th>
                                <th >Units</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1.</td>
                                <td>Demo</td>
                                <td>Demo</td>
                                <td><span class="badge bg-danger">Demo</span></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    {{-- <div class="card-footer clearfix">
                         <ul class="pagination pagination-sm m-0 float-right">
                             <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                             <li class="page-item"><a class="page-link" href="#">1</a></li>
                             <li class="page-item"><a class="page-link" href="#">2</a></li>
                             <li class="page-item"><a class="page-link" href="#">3</a></li>
                             <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                         </ul>
                     </div>--}}
                </div>
                <!-- /.card -->

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Units On Duty</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table  table-bordered">
                            <thead>
                            <tr>
                                <th >Id</th>
                                <th>Vehicle</th>
                                <th>User</th>
                                <th style="width: 40px">Status</th>
                                <th style="width: 40px">Reason</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1.</td>
                                <td>Demo</td>
                                <td>Demo</td>
                                <td><span class="badge bg-danger">Demo</span></td>
                                <td><span class="badge bg-primary">Demo</span></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Vehicle Service Board</h3>

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

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Recent PIN Article <small>Last 2 Days</small></h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Posted By</th>
                                <th>Classification</th>
                                <th>Title</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($articles as $article)
                                <tr>
                                    @if(isset($article->user->name))
                                    <td>{{$article->user->name}}</td>
                                    @endif
                                    @if(isset($article->categories->name))
                                    <td>{{$article->categories->name}}</td>
                                    @endif
                                    <td>{{$article->title}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
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
