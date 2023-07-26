@extends('admin.layouts.master')
@section('stylesheet')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('title',$title)

@section('content')
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
		<!--begin::Subheader-->
		<div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader" kt-hidden-height="54" style="">
			<div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
				<!--begin::Info-->
				<div class="d-flex align-items-center flex-wrap mr-1">
					<!--begin::Page Heading-->
					<div class="d-flex align-items-baseline flex-wrap mr-5">
						<!--begin::Page Title-->
						<h5 class="text-dark font-weight-bold my-1 mr-5">Dashboard</h5>
						<!--end::Page Title-->
						<!--begin::Breadcrumb-->
						<ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
							<li class="breadcrumb-item text-muted">
								<a href="" class="text-muted">Sms Setting</a>
							</li>
						</ul>
						<!--end::Breadcrumb-->
					</div>
					<!--end::Page Heading-->
				</div>
				<!--end::Info-->

			</div>
		</div>
		<!--end::Subheader-->
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<!--begin::Card-->
				<div class="card card-custom card-sticky" id="kt_page_sticky_card">
					<div class="card-header" style="">
						<div class="card-title">
							<h3 class="card-label">SMS Setting
								<i class="mr-2"></i>
								<small class="">try to scroll the page</small></h3>

						</div>
						<div class="card-toolbar">

							<a href="{{ route('admin.dashboard') }}" class="btn btn-light-primary font-weight-bolder mr-2">
								<i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

							<div class="btn-group">
                                @php
                                    $user = Auth::user();
                                    if($user->role == 1){
                                        $add = 1;
                                    }elseif($user->role == 2){
                                        $add = 1;
                                    }elseif($user->role == 3){
                                        $add = $user->permission->setting_sms_edit;
                                    }
                                @endphp
                                @if($add)
                                <a href=""  onclick="event.preventDefault(); document.getElementById('setting_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                                <i class="ki ki-check icon-sm"></i>Save</a>
                                @endif

							</div>
						</div>
					</div>
					<div class="card-body">
					    @include('admin.partials._messages')
						<!--begin::Form-->
						<form class="form" id="setting_form" method="POST" action="{{ route('sms-setting.store') }}" enctype='multipart/form-data'>
							@csrf
                            <input type="hidden" name='id' value="{{ $settings->id ?? ''}}">
                            <div class="row">
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('type') ? 'has-error' : '' }}">
                                        <label class="">SMS Service</label>
                                        <div class="">
                                            <select class="form-control  select-2-multiple" name="type" id="type" >
                                                <option value="1" @if($settings){{$settings->type == 1}} selected @endif>Pearl Sms</option>
                                                <option value="2"@if($settings){{$settings->type == 2}} selected @endif>Twilio </option>
                                                <option value="3" @if($settings){{$settings->type == 3}} selected @endif>Bulk Sms</option>
                                            </select>

                                            <span class="text-danger">{{ $errors->first('type') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('template') ? 'has-error' : '' }}">
                                        <label class="">SMS Template</label>
                                        <div class="">
                                            <select class="form-control select-2" name="template[]"  id="user" multiple>
                                                @foreach ($templates as  $template)

                                                    <option value="{{ $template->id }}"
                                                        @if($settings)
                                                        @php
                                                            $array = json_decode($settings->template_id, true);
                                                        @endphp
                                                        @if(!empty($template->id) && !empty( $array))
                                                            @if(in_array($template->id,$array))
                                                                selected
                                                            @endif
                                                        @endif
                                                        @endif>{{ $template->name }}</option>
                                                @endforeach


                                            </select>

                                            <span class="text-danger">{{ $errors->first('type') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group ">

                                </div>
                                <div id="pearl" class="base row col-md-12">
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('pearlsms_sender') ? 'has-error' : '' }}">
                                            <label class="">
                                                Pearl Sms Sender</label>
                                            <div class="">
                                                {{ Form::text('pearlsms_sender', ($settings)?$settings->pearlsms_sender:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('pearlsms_sender') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('pearlsms_api_key') ? 'has-error' : '' }}">
                                            <label class="">
                                                Pearl Sms Api key</label>
                                            <div class="">
                                                {{ Form::text('pearlsms_api_key', ($settings)?$settings->pearlsms_api_key:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('pearlsms_api_key') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('pearlsms_header') ? 'has-error' : '' }}">
                                            <label class="">
                                                Pearl Sms Header</label>
                                            <div class="">
                                                {{ Form::text('pearlsms_header', ($settings)?$settings->pearlsms_header:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('pearlsms_header') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('pearlsms_footer') ? 'has-error' : '' }}">
                                            <label class="">
                                                Pearl Sms Footer</label>
                                            <div class="">
                                                {{ Form::text('pearlsms_footer', ($settings)?$settings->pearlsms_footer:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('pearlsms_footer') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('pearlsms_username') ? 'has-error' : '' }}">
                                            <label class="">
                                                Pearl Sms Username</label>
                                            <div class="">
                                                {{ Form::text('pearlsms_username', ($settings)?$settings->pearlsms_username:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('pearlsms_username') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div id="twilio" class="base row col-md-12">
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('twilio_number') ? 'has-error' : '' }}">
                                            <label class="">
                                                Twilio Number</label>
                                            <div class="">
                                                {{ Form::text('twilio_number', ($settings)?$settings->twilio_number:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('twilio_number') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('twilio_account_sid') ? 'has-error' : '' }}">
                                            <label class="">
                                                Twilio Account SID</label>
                                            <div class="">
                                                {{ Form::text('twilio_account_sid', ($settings)?$settings->twilio_account_sid:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('twilio_account_sid') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('twilio_auth_token') ? 'has-error' : '' }}">
                                            <label class="">
                                                Twilio Auth Token</label>
                                            <div class="">
                                                {{ Form::text('twilio_auth_token', ($settings)?$settings->twilio_auth_token:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('twilio_auth_token') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="msg91" class="base row col-md-12">
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('bulksms_apikey') ? 'has-error' : '' }}">
                                            <label class="">
                                                Bulk SMS Api key</label>
                                            <div class="">
                                                {{ Form::text('bulksms_apikey', ($settings)?$settings->bulksms_apikey:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('bulksms_apikey') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('bulksms_username') ? 'has-error' : '' }}">
                                            <label class="">
                                                Bulk SMS Username</label>
                                            <div class="">
                                                {{ Form::text('bulksms_username', ($settings)?$settings->bulksms_username:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('bulksms_username') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 form-group ">
                                        <div class="form-group  {{ $errors->has('bulksms_sendername') ? 'has-error' : '' }}">
                                            <label class="">
                                                Bulk SMS Sendername</label>
                                            <div class="">
                                                {{ Form::text('bulksms_sendername', ($settings)?$settings->bulksms_sendername:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('bulksms_sendername') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="card-footer" style="">




                                </div>
                            </div>
						</form>
						<!--end::Form-->
                    </div>
                </div>
                {{--  <div class="card card-custom card-sticky mt-3"  id="kt_page_sticky_card">
                    <div class="card-body">
                        <div class="table-responsive">
                           <form action="{{route('admin.delete-selected-brands')}}" method="post" id="client_form">
                              <input type="hidden" name="_token" value="{{ csrf_token() }}">
                              <!--begin: Datatable-->
                              <table class="table table-bordered table-hover table-checkable" id="clients" style="margin-top: 13px !important">
                                 <thead>
                                    <tr>
                                       <th>
                                          <label class="checkbox checkbox-outline checkbox-success"><input type="checkbox"><span></span></label>
                                       </th>
                                       <th>Type</th>
                                       <th>Template</th>
                                       <th>Created At</th>
                                       <th>Actions</th>
                                    </tr>
                                 </thead>
                              </table>
                           </form>
                           <!--end: Datatable-->
                        </div>
					</div>
				</div>
				<!--end::Card-->  --}}

			</div>
			<!--end::Container-->
		</div>
        {{--  <div class="modal fade" id="smsModel" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                     <h4 class="modal-title" id="myModalLabel">SMS Setting  Details</h4>
                  </div>
                  <div class="modal-body"></div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                  </div>
               </div>
            </div>
        </div>  --}}
	</div>
@endsection
@section('scripts')
    <script !src="">
        $(".summernote").summernote();
        $(document).ready(function() {
            $(".base").hide();
            var value = parseInt($("#type").val());
            if (value == 1){
                $("#pearl").show();
            }else if (value == 2){
                $("#twilio").show();
            }else if (value == 3){
                $("#msg91").show();
            }

        })
        $("#type").change(function() {
           $(".base").hide();
           var value = $(this).val();
           if (value == 1){
               $("#pearl").show();
           }else if (value == 2){
               $("#twilio").show();
           }else if (value == 3){
               $("#msg91").show();
           }
        });
    </script>


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
        var clients = $('#clients').DataTable({
            "order": [
                [1, 'asc']
            ],
            "processing": true,
            "serverSide": true,
            "searchDelay": 500,
            "responsive": true,
            "ajax": {
                "url": "{{ route('admin.getSettings') }}",
                "dataType": "json",
                "type": "POST",
                "data": {
                    "_token": "<?php echo csrf_token(); ?>"
                }
            },
            "columns": [{
                    "data": "id",
                    "searchable": false,
                    "orderable": false
                },
                {
                    "data": "type"
                },
                {
                    "data": "template_id"
                },

                {
                    "data": "created_at"
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
            $.post("{{ route('admin.getSetting') }}", {
                _token: CSRF_TOKEN,
                id: id
            }).done(function(response) {
                $('.modal-body').html(response);
                $('#smsModel').modal('show');

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
                        "Your Template Setting has been deleted.",
                        "success"
                    );
                    var APP_URL = {!! json_encode(url('/')) !!}
                    window.location.href = APP_URL + "/admin/sms/delete/" + id;
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
            }).then(function(result) {
                if (result.value) {
                    Swal.fire(
                        "Deleted!",
                        "Your clients has been deleted.",
                        "success"
                    );
                    $("#client_form").submit();
                }
            });
        }
    </script>
    <script>

        $("#user").select2({
            multiple: true,

        });
        $("#type").select2();

    </script>
@endsection
