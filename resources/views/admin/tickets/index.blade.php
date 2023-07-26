@extends('admin.layouts.master')
@section('title',$title)
@section('content')
    <!--begin::Card-->
        <div class="card card-custom">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Fleet Tickets List</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
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
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
          {{--       <!-- Main content -->

            <!-- /.content -->--}}
        <div class="card-body">
            @include('admin.partials._messages')
            <div class="table-responsive">

                <form action="{{route('admin.delete-selected-tickets')}}" method="post" id="ticket_form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <!--begin: Datatable-->
                    <table class="table table-bordered table-hover table-checkable" id="tickets" style="margin-top: 13px !important">
                        <thead>
                        <tr>
                            <th>
                                <label class="checkbox checkbox-outline checkbox-success"><input type="checkbox"><span></span></label>

                            </th>

                            <th>Vehicle #</th>
                            <th>Fleet Ticket Complaint</th>
                            <th>Remarks</th>
                            <th>Vehicle Mileage </th>
                            <th>Complaint </th>
                            <th>Service Status</th>
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
        <div class="modal fade" id="ticketModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel">Ticket Detail</h4> </div>
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
        var tickets = $('#tickets').DataTable( {
            "order": [
                [6, 'desc']
            ],
            "processing": true,
            "serverSide": true,
            "searchDelay": 500,
            "responsive": true,
            "ajax": {
                "url":"{{ route('admin.getTickets') }}",
                "dataType":"json",
                "type":"POST",
                "data":{"_token":"<?php echo csrf_token() ?>"}
            },
            "columns":[
                {"data":"id","searchable":false,"orderable":false},
                {"data":"vehicle_id"},
                {"data":"complaint_id"},
                {"data":"remarks"},
                {"data":"vehicle_mileage"},
                {"data":"complaint"},
                {"data":"status"},
                {"data":"created_at"},
                {"data":"action","searchable":false,"orderable":false}
            ]
        } );
        function viewInfo(id) {
            var CSRF_TOKEN = '{{ csrf_token() }}';
            $.post("{{ route('admin.getTicket') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
                $('.modal-body').html(response);
                $('#ticketModel').modal('show');

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
                        "Your Ticket has been deleted.",
                        "success"
                    );
                    var APP_URL = {!! json_encode(url('/')) !!}
                        window.location.href = APP_URL+"/admin/ticket/delete/"+id;
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
                        "Your Ticket has been deleted.",
                        "success"
                    );
                    $("#ticket_form").submit();
                }
            });
        }
        $(document).ready(function () {

            $('.summernote').summernote();
            $(".select-category").select2({
                    placeholder: "Select Categories",
                    allowClear: true
                }
            );
        });
        $(function() {
            $("#printable").find('#print').on('click', function() {
                $.print("#printable");
            });
        });
        function printDiv() {
            var divContents = document.getElementById("tpt").innerHTML;
            var a = window.open('', '', 'height=500, width=500');
            a.document.write('<div>');
            a.document.write('<body > <h1>Ticket</h1> <br>');
            a.document.write(divContents);
            a.document.write('<style> img {max-width : 300px; padding: 5px; white-space: nowrap;  height : auto; flex: 33.33%;} ');
            a.document.write('</body></div>');
            a.document.write('');
            setTimeout(function () {
                a.document.close();
                a.print();
            }, 650);
        }
    </script>
@endsection


