@extends('admin.layouts.master')
@section('title',$title)
@section('content')
    <div class="card card-custom">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Event Categories List</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <a class="btn btn-light-primary btn-sm"
                               onclick="del_selected()" href="javascript:void(0)">
                                <i class="la la-trash-o"></i>
                                Delete All
                            </a> &nbsp;&nbsp;&nbsp;
                            <a class="btn btn-info btn-sm" href="{{ route('event-categories.create') }}">
                                <i class="fas fa-pencil-alt">
                                </i>
                                New Record
                            </a>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <div class="card-body">
            @include('admin.partials._messages')
            <div class="table-responsive">
                <form action="{{route('admin.delete-selected-event-categories')}}" method="post" id="event_category_form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <!--begin: Datatable-->
                    <table class="table table-bordered table-hover table-checkable" id="event_categories"
                           style="margin-top: 13px !important">
                        <thead>
                        <tr>
                            <th>
                                <label class="checkbox checkbox-outline checkbox-success"><input
                                        type="checkbox"><span></span></label>
                            </th>
                            <th>Name</th>
                            <th>slug</th>
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
        <div class="modal fade" id="event_categoryModel" data-backdrop="static" tabindex="-1" role="dialog"
             aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Event Category Detail</h4></div>
                    <div class="modal-body"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">
                            Close
                        </button>
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
          type="text/css"/>
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
        var event_categories = $('#event_categories').DataTable({
            "order": [
                [1, 'asc']
            ],
            "processing": true,
            "serverSide": true,
            "searchDelay": 500,
            "responsive": true,
            "ajax": {
                "url": "{{ route('admin.getEventCategories') }}",
                "dataType": "json",
                "type": "POST",
                "data": {"_token": "<?php echo csrf_token() ?>"}
            },
            "columns": [
                {"data": "id", "searchable": false, "orderable": false},
                {"data": "name"},
                {"data": "slug"},
                {"data": "created_at"},
                {"data": "action", "searchable": false, "orderable": false}
            ]
        });

        function viewInfo(id) {

            var CSRF_TOKEN = '{{ csrf_token() }}';
            $.post("{{ route('admin.getEventCategory') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
                $('.modal-body').html(response);
                $('#event_categoryModel').modal('show');

            });
        }

        function del(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(function (result) {
                if (result.value) {
                    Swal.fire(
                        "Deleted!",
                        "Your Event Category has been deleted.",
                        "success"
                    );
                    var APP_URL = {!! json_encode(url('/')) !!}
                        window.location.href = APP_URL + "/admin/event-category/delete/" + id;
                }
            });
        }

        function del_selected() {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!"
            }).then(function (result) {
                if (result.value) {
                    Swal.fire(
                        "Deleted!",
                        "Your Event Category has been deleted.",
                        "success"
                    );
                    $("#event_category_form").submit();
                }
            });
        }

    </script>
@endsection
