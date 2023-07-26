@extends('admin.layouts.master')
@section('title', 'Spider')
@section('content')
    <!--begin::Card-->
    <div class="card card-custom">
        <div class="card-header">
            <div class="card-title">
                <span class="card-icon">
                    <i class="flaticon-users text-primary"></i>
                </span>
                <h3 class="card-label">Slider List</h3>
                <div class="d-flex align-items-center ">
                   
                </div>
            </div>
            <div class="card-toolbar">

            </div>
        </div>
        <div class="card-body">
            @include('admin.partials._messages')
            <div >
                
                    <!--begin: Datatable-->
                    <table class="table table-bordered table-hover table-checkable" id="sliderImage"
                        style="margin-top: 13px !important">
                        <thead>
                            <tr>
                                <th>
                                    Store
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($all_stores as $store)
                            <tr>
                                <td>
                                    <ul style="list-style: none; padding:0px">
                                        <li><div class="alert alert-success"> {{$store['store']}}</div>
                                            @foreach($store['date'] as $date)
                                                    <ul>
                                                        <li>
                                                            {{$date['dates'] }}
                                                            <ul>
                                                                @foreach($date['codes'] as $code)
                                                                    <li>
                                                                        {{$code['zipcode']}}  
                                                                        <ul>
                                                                            @foreach($code['files'] as $file)
                                                                                <li>
                                                                                    {{$file}}
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </li> 
                                                            @endforeach
                                                            </ul>
                                                        </li>
                                                    </ul>
                                                
                                            @endforeach
                                        </li>
                                    </ul>
                                </td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="example">
  Your tree will be placed here
</div>
                <!--end: Datatable-->
            </div>
        </div>
        <!-- Modal-->
        <div class="modal fade" id="sliderImageModel" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Slider Image Detail</h4>
                    </div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold"
                            data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Card-->
@endsection
@section('stylesheets')
    <!--begin::Page Vendors Styles(used by this page)-->
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/acl-tree-view.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Page Vendors Styles-->
@endsection
@section('scripts')
    <!--begin::Page Vendors(used by this page)-->
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/acl-tree-view.js') }}"></script>
    <!--end::Page Vendors-->
    <script>
        const myData = [
      {
        // label text
        "label": "Mail",
        // icon class(es)
        "icon": "far fa-envelope-open",
        // define sub-items here
        "ul": [{
          "label": "Offers",
          "icon": "far fa-bell"
        }, {
          "label": "Contacts",
          "icon": "far fa-address-book"
        }, {
        "label": "Calendar",
        "icon": "far fa-calendar-alt",
        "ul": [{
          "label": "Deadlines",
          "icon": "far fa-clock"
        }, {
          "label": "Meetings",
          "icon": "fas fa-users"
        }, {
          "label": "Workouts",
          "icon": "fas fa-basketball-ball"
        }]
        }]
      },
      // more items here
];
const treeView = $('.example').aclTreeView({ 
      // options here
      }, myData);
    </script>
@endsection
