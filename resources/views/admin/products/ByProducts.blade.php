@extends('admin.layouts.products')
@section('title', $title)
@section('content')
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="flaticon-users text-primary"></i>
            </span>
            <h3 class="card-label">Products List</h3>
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
                            <th>Price</th>
                            <th>Size</th>
                            <th>Actions</th>


                        </tr>
                    </thead>
                </table>
            </form>
            <!--end: Datatable-->
        </div>
    </div>
    <!-- Modal-->
    <div class="modal fade" id="productModel" data-backdrop="static" tabindex="-1" role="dialog"
            aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Product Detail</h4>
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
            "url": "{{ url('admin/getByProducts/') }}/" + id,
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
                "data": "price"
            },
            {
                "data": "size"
            },
            {
                "data": "action",
                "searchable": false,
                "orderable": false
            }

        ]
    });

    function viewInfo(id) {

var CSRF_TOKEN = '{{ csrf_token() }}';
$.post("{{ route('admin.getProduct') }}", {
    _token: CSRF_TOKEN,
    id: id
}).done(function(response) {
    $('.modal-body').html(response);
    $('#productModel').modal('show');

});
}

function del(id) {
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
                        "Your product has been deleted.",
                        "success"
                    );
                    var APP_URL = {!! json_encode(url('/')) !!}
                    window.location.href = APP_URL + "/admin/product/delete/" + id;
                }
            });
        }

    
</script>
@endsection