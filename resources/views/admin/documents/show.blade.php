@extends('admin.layouts.master')
@section('title',$title)
@section('content')
<!--begin::Card-->
<div class="card card-custom">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-4">
                    <h1>{{$folder->name}} Folder Documents List</h1>
                </div>
                <div class="col-sm-8">
                    <ol class="breadcrumb float-sm-right">
                        <a class="btn btn-light-primary btn-sm" onclick="del_selected()" href="javascript:void(0)">
                            <i class="la la-trash-o"></i>
                            Delete All
                        </a> &nbsp;&nbsp;&nbsp;
                        <a class="btn btn-primary btn-sm ml-2" href="{{ route('folders.create') }}">
                            <i class="fas fa-plus">
                            </i>
                            Add Folder
                        </a>
                        <a class="btn btn-primary btn-sm  ml-2" href="{{ route('documents.create') }}">
                            <i class="fas fa-plus">
                            </i>
                            Add Document
                        </a>
                        <a class="btn btn-primary btn-sm  ml-2" href="{{ route('folders.index') }}">
                            <i class="fas fa-folder">
                            </i>
                            Manage Folders
                        </a>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <div class="card-body">
        @include('admin.partials._messages')
        <div class="">
            <form action="{{route('admin.delete-selected-folders')}}" method="post" id="user_form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <!--begin: Datatable-->
                <table class="table table-bordered table-hover table-checkable" id="users" style="margin-top: 13px !important">
                    <thead>
                        <tr>
                            <th>
                                <label class="checkbox checkbox-outline checkbox-success"><input type="checkbox"><span></span></label>

                            </th>

                            <th>Name</th>
                            <th>Description</th>
                            <th>Folder</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($folder->documents as $document)
                        <tr>
                            <td><label class="checkbox checkbox-outline checkbox-success"><input type="checkbox" name="documents[]" value="{{$document->id}}"><span></span></label></td>
                            <td>{{$document->name}}</td>
                            <td>{{$document->description}}</td>
                            <td>{{$folder->name}}</td>
                            <td>{{date('d-m-Y',strtotime($document->created_at))}}</td>
                            <td>
                                <a class="btn btn-sm btn-clean btn-icon" target="_blank" title="Show PDF " href="{{asset("uploads/$document->document")}}">
                                    <i class="icon-1x text-dark-50 fa fa-file-pdf"></i>
                                </a>
                                <a class=" view btn btn-sm btn-clean btn-icon" title="View " href="javascript:;" data-id="{{ $document->id }}">
                                    <i class="icon-1x text-dark-50 flaticon-eye"></i>
                                </a>
                                <a title="Edit " class="btn btn-sm btn-clean btn-icon" href="{{route("documents.edit",$document->id)}}">
                                    <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                </a>
                                <a class=" delete btn btn-sm btn-clean btn-icon"  title="Delete" href="javascript:;" data-id="{{ $document->id }}">
                                    <i class="icon-1x text-dark-50 flaticon-delete"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
            <!--end: Datatable-->
        </div>
    </div>
    <!-- Modal-->
    <div class="modal fade" id="userModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Document Detail</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
                                <label>name</label>
                                <input type="text" placeholder="Enter Name" autocomplete="off" id="name" name="name" class="form-control" value="" required autofocus>

                                <span class="text-danger" id="err_edit_name"> </span>

                            </div>


                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label>Description</label>
                                <input type="text" placeholder="Enter Email" autocomplete="off" id="description" name="description" class="form-control" value="{{old('email')}}" required autofocus>

                                <span class="text-danger" id="err_edit_email"> </span>

                            </div>

                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label>Folder</label>
                                <input type="text" placeholder="Enter Email" autocomplete="off" id="folder" name="folder" class="form-control" value="{{old('email')}}" required autofocus>

                                <span class="text-danger" id="err_edit_email"> </span>

                            </div>

                            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                                <label>Created at</label>
                                <input type="text" placeholder="Enter Email" autocomplete="off" id="created_at" name="created_at" class="form-control" value="{{old('email')}}" required autofocus>

                                <span class="text-danger" id="err_edit_email"> </span>

                            </div>





                        </div>
                    </div>
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
        var users = $('#users').DataTable();

        function viewInfo(id) {

            var CSRF_TOKEN = '{{ csrf_token() }}';
            $.get("{{ route('admin.getDocument') }}", {
                _token: CSRF_TOKEN,
                id: id
            }).done(function(response) {
                $('.modal-body').html(response);
                $('#userModel').modal('show');

            });
        }
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
        //                   "Your user has been deleted.",
        //                   "success"
        //               );
        //               var APP_URL = {!! json_encode(url('/')) !!}
        //               window.location.href = APP_URL+"/admin/user/document/"+id;
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
        //                   "Your document  has been deleted.",
        //                   "success"
        //               );
        //               $("#user_form").submit();
        //           }
        //       });
        //   }

        $(document).on('click', '.view', function() {
            $("#error_name").text("");
            var id = $(this).data('id');
            editing_row = $(this).closest('tr');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                method: 'GET',
                url: "{{url('admin/show-documents/')}}/" + id,
                // url:"{{url('user/{id}/edit')}}",
                beforeSend: function() {
                    $("#loading").show();
                },
            }).done(function(response) {
                console.log(response);
                $("#loading").hide();
                //  $("#edit_id").val(response.user.id);


                $("#name").val(response.user.name);
                 $("#description").val(response.user.description);
                 $("#folder").val(response.user.folder_id);
                 $("#created_at").val(response.user.created_at);



                $('#userModel').modal('toggle');

            }).fail(function(error) {
                $("#loading").hide();
                swal.fire("Cancelled", error.responseJSON.message, "error");
            });
        });

        $(document).on('click', '.delete', function(e) {
        var uid = $(this).data('id');
        tr = $(this).closest('tr');


        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    method: 'POST',
                    data: {
                        '_method': 'DELETE'
                    },
                    url: "{{url('admin/documents/')}}/" + uid,
                    beforeSend: function() {
                        $("#loading").show();
                    },
                }).done(function(response) {
                    $("#loading").hide();
                    Swal.fire("Deleted!", response.msg, "success");
                    location.reload();

                    var table = $('#datatable-buttons').DataTable();
                    table.row(tr).remove().draw();


                }).fail(function(response) {
                    $("#loading").hide();
                    swal.fire("Cancelled", response.statusText, "error");
                });
            }
        })
    });
    </script>
    @endsection
