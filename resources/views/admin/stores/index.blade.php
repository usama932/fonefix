@extends('admin.layouts.master')
@section('title', $title)
@section('content')
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="flaticon-users text-primary"></i>
            </span>
            <h3 class="card-label">Stores List</h3>
            <div class="d-flex align-items-center ">

            </div>
        </div>
        <div class="card-toolbar">

            <!--begin::Button-->

            <!--end::Button-->
        </div>
    </div>
    <div class="card-body">
        @include('admin.partials._messages')
        <div>
            <form action="#" method="post" id="category_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <!--begin: Datatable-->
                <table class="table table-bordered table-hover table-checkable" id="categories" style="margin-top: 13px !important">
                    <thead>
                        <tr>

                            <th>Name</th>
                            <th>Phone number#</th>
                            <th>Address</th>
                            <th>Actions</th>


                        </tr>
                    </thead>
                </table>
            </form>
            <!--end: Datatable-->
        </div>
    </div>
    <!-- Modal-->
   
</div>
<!--end::Card-->
@endsection
@section('stylesheets')
<!--begin::Page Vendors Styles(used by this page)-->
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--end::Page Vendors Styles-->
@endsection
@section('scripts')
<!--begin::Page Vendors(used by this page)-->
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<!--end::Page Vendors-->
<script>
    $(document).on('click', 'th input:checkbox', function() {

        var that = this;
        $(this).closest('table').find('tr > td:first-child input:checkbox')
            .each(function() {
                this.checked = that.checked;
                $(this).closest('tr').toggleClass('selected');
            });
    });
    var id = '{{ $id }}';
    var category = $('#categories').DataTable({
        "order": [
            [0, 'asc']
        ],
        "processing": true,
        "serverSide": true,
        "searchDelay": 500,
        "responsive": true,
        "ajax": {
            "url": "{{ url('admin/getStores/') }}/" + id,
            // "dataType": "json",
            "type": "POST",
            "data": {
                "_token": "<?php echo csrf_token(); ?>"
            }
        },
        "columns": [{
                "data": "name"
            },
            {
                "data": "phone_number"
            },
            {
                "data": "address"
            },
            {
                "data": "action",
                "searchable": false,
                "orderable": false
            }

        ]
    });

    
</script>
@endsection