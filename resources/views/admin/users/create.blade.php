@extends('admin.layouts.master')
@section('title',$title)
@section('stylesheets')
    <style>
        .form .required:after {
            content: " *";
            color: red;
            font-weight: 80%;
        }


        p.note {
            font-size: 1rem;
            color: red;
        }


        label {
            width: 300px;
            font-weight: bold;
            display: inline-block;
            margin-top: 20px;
        }

        label span {
            font-size: 1rem;
        }

        label.error {
            color: red;
            font-size: 1rem;
            display: block;
            margin-top: 5px;
        }

        input.error {
            border: 1px dashed red;
            font-weight: 300;
            color: red;
        }
    </style>
@endsection
@section('content')

    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <!--begin::Container-->
            <div class="container">
                <!--begin::Card-->
                <div class="card card-custom card-sticky" id="kt_page_sticky_card">

                    <section class="content-header">
                        <div class="container-fluid">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>Add User</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <a class="btn btn-light-primary btn-sm"
                                           href="{{ route('users.index') }}">
                                            <i class="ki ki-long-arrow-back icon-sm"></i>
                                            Back
                                        </a> &nbsp;&nbsp;&nbsp;
                                        <a class="btn btn-info btn-sm"
                                           href="javascript:void(0);"
                                           onclick="validated();"
                                           id="kt_btn">
                                            <i class="ki ki-check icon-sm">
                                            </i>
                                            Save
                                        </a>
                                    </ol>
                                </div>
                            </div>
                        </div><!-- /.container-fluid -->
                    </section>

                </div>
                <div class="card-body" style="background-color: ghostwhite">
                @include('admin.partials._messages')
                <!--begin::Form-->
                    {{ Form::open([ 'route' => 'users.store','class'=>'form' ,"id"=>"user_add_form", 'enctype'=>'multipart/form-data']) }}
                    @csrf
                    <div class="row">
                        <div class="col-xl-2"></div>
                        <div class="col-xl-8">
                            <div class="my-5">
                                <h3 class="text-dark font-weight-bold mb-10">user Info: </h3>
                                <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Name</label>
                                    <div class="col-9">
                                        {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Name','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('cas_id') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label ">CAS ID</label>
                                    <div class="col-9">
                                        {{ Form::text('cas_id', null, ['class' => 'form-control form-control-solid','id'=>'cas_id','placeholder'=>'Enter Name']) }}
                                        <span class="text-danger">{{ $errors->first('cas_id') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Email</label>
                                    <div class="col-9">
                                        {{ Form::email('email', null, ['class' => 'form-control form-control-solid','id'=>'email','placeholder'=>'Email Address','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Password</label>
                                    <div class="col-9">
                                        {{ Form::text('password', null, ['class' => 'form-control form-control-solid','id'=>'password','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label form-label required">Status</label>
                                    <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" checked="checked" name="status_id" value="1">
                            <span></span>
                          </label>
                        </span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('Role') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Role</label>
                                    <div class="col-9">
                                        {{--{{ Form::select('categories[]',$categories, null, ['class' => 'no-padding select-category ','multiple'=>'multiple']) }}--}}
                                        <select class="form-control" name="role" id="role" required>
                                            <option value="">Select a Role</option>
                                            @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{$role->name}}</option>
                                            @endforeach
                                        </select>
                                        {{--{{ Form::select('categories[]', $categories, ['class' => 'demoInputBox form-control select-category','id'=>'category'])}}--}}
                                        <span class="text-danger">{{ $errors->first('Role') }}</span>
                                    </div>
                                </div>

                                <div
                                    class="form-group row {{ $errors->has('fleet_email') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Fleet Email</label>
                                    <div class="col-9">
                                        <select class="form-control" name="fleet_email" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes - Get Notification</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('fleet_email') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('articles') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Articles</label>
                                    <div class="col-9">
                                        <select class="form-control" name="articles" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('articles') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('categories') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Categories</label>
                                    <div class="col-9">
                                        <select class="form-control" name="categories" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('categories') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('event_calendar') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Event Calendar</label>
                                    <div class="col-9">
                                        <select class="form-control" name="event_calendar" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('event_calendar') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('mechanic') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Mechanic</label>
                                    <div class="col-9">
                                        <select class="form-control" name="mechanic" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('mechanic') }}</span>
                                    </div>
                                </div>

                                <div
                                    class="form-group row {{ $errors->has('fleet_managment') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Fleet Management</label>
                                    <div class="col-9">
                                        <select class="form-control" name="fleet_managment" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('fleet_managment') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('mechanic') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Document managment</label>
                                    <div class="col-9">
                                        <select class="form-control" name="document_managment" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('document_managment') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('department_links') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Department Links</label>
                                    <div class="col-9">
                                        <select class="form-control" name="department_links" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('department_links') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('support') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Support</label>
                                    <div class="col-9">
                                        <select class="form-control" name="support" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('support') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('configuration') ? 'has-error' : '' }}">
                                    <label class="col-3 form-label required">Configuration</label>
                                    <div class="col-9">
                                        <select class="form-control" name="configuration" required>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('configuration') }}</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-xl-2"></div>
                    </div>
                {{Form::close()}}
                <!--end::Form-->
                </div>
            </div>
            <!--end::Card-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
    </div>
@endsection

@section('scripts')
    <script>

        // $(document).ready(function() {
        //   $("#article_add_form").validate();
        // });
        function validated() {
            $("#content").click(function () {
                validate();
                return false;
            })
            $("#user_add_form").validate({

                errorClass: "error fail-alert",
                validClass: "valid success-alert",
                rules: {
                    name: {
                        required: true

                    },
                    email: {
                        required: true

                    },
                    password: {
                        required: true

                    },
                    status: {
                        required: true

                    },
                    role: {
                        required: true
                    }

                },
                messages: {
                    name: {

                        required: "Please enter name"

                    },
                    email: {
                        required: "Please enter email"

                    },
                    password: {
                        required: "Please enter password"

                    },


                    role: {
                        required: "Please select role"

                    },

                }
            });
            if ($('#user_add_form').valid()) // check if form is valid
            {
                $("#user_add_form").submit();
            } else {
                return;
            }
        }


    </script>

@endsection
