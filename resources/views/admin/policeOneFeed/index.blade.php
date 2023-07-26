@extends('admin.layouts.master')
@section('title',$title)
@section('content')
    <!--begin::Card-->
    <div class="card card-custom ">
        <div class="card-header">
            <div class="card-title">
                <h2 class="box-title"><span class="fa fa-rss-square"></span> PoliceOne News Feed</h2>
                <div class="d-flex align-items-center ">
                </div>
            </div>
            <div class="card-toolbar">

                <!--begin::Button-->

                <!--end::Button-->
            </div>
        </div>
        <div class="card-body">
            <?php
            function is_connected()
            {
            $connected = @fsockopen("www.lf-designs.com", 80);
            //website, port  (try 80 or 443)
            if ($connected){
            $is_conn = true; //action when connected
            //echo "You are connected";
            fclose($connected);

            ?>

                    <SCRIPT  language=JavaScript src="http://www.policeone.com/syndicate-js.asp?vid=1&cnt=20" style="font-size: 120px;"></SCRIPT>
            <center><a href="http://www.policeone.com"><img border=0
                                                            src="http://policeone.com/policeone/data/p1power2.gif"></a>
            </center>



            <?php
            }else {
                $is_conn = false; //action in connection failure
                echo "You are not connected to the internet and cannot view these feeds!";
            }
            return $is_conn;

            }

            is_connected();

            ?>

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
        var articles = $('#articles').DataTable({
            "order": [
                [1, 'asc']
            ],
            "processing": true,
            "serverSide": true,
            "searchDelay": 500,
            "responsive": true,
            "ajax": {
                "url": "{{ route('admin.getArticles') }}",
                "dataType": "json",
                "type": "POST",
                "data": {"_token": "<?php echo csrf_token() ?>"}
            },
            "columns": [
                {"data": "id", "searchable": false, "orderable": false},
                {"data": "title"},
                {"data": "author"},
                {"data": "priority"},
                {"data": "category"},
                {"data": "created_at"},
                {"data": "action", "searchable": false, "orderable": false}
            ]
        });

        function viewInfo(id) {

            var CSRF_TOKEN = '{{ csrf_token() }}';
            $.post("{{ route('admin.getArticle') }}", {_token: CSRF_TOKEN, id: id}).done(function (response) {
                $('.modal-body').html(response);
                $('#articleModel').modal('show');

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
                        "Your Article has been deleted.",
                        "success"
                    );
                    var APP_URL = {!! json_encode(url('/')) !!}
                        window.location.href = APP_URL + "/admin/article/delete/" + id;
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
                        "Your Articles has been deleted.",
                        "success"
                    );
                    $("#article_form").submit();
                }
            });
        }

    </script>
@endsection


