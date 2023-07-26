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
                                    <h1>Update User : {{ $user->name }}</h1>
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
                    {{ Form::model($user, [ 'method' => 'PATCH','route' => ['users.update', $user->id],'class'=>'form' ,"id"=>"user_update_form", 'enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="row">
                        <div class="col-xl-2"></div>
                        <div class="col-xl-8">
                            <div class="my-5">
                                <h3 class="text-dark font-weight-bold mb-10">user Info: </h3>
                                <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Name</label>
                                    <div class="col-9">
                                        {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Name','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('cas_id') ? 'has-error' : '' }}">
                                    <label class="col-3 ">CAS ID</label>
                                    <div class="col-9">
                                        {{ Form::text('cas_id', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Name','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('cas_id') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Email</label>
                                    <div class="col-9">
                                        {{ Form::email('email', null, ['class' => 'form-control form-control-solid','id'=>'email','placeholder'=>'Email Address','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Password</label>
                                    <div class="col-9">
                                        {{ Form::text('password','', ['placeholder'=>"If you won't change Password then leave it blank as it as.", 'class' => 'form-control form-control-solid','id'=>'password','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label required">Status</label>
                                    <div class="col-3">
                                         <span class="switch switch-outline switch-icon switch-success">
                                          <label><input type="checkbox" name="status_id" value="1"   {{  ( $user->status_id == 1 ? 'checked' : '') }}>
                                            <span></span>
                                          </label>
                                        </span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('role') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Role</label>
                                    <div class="col-9">
                                        {{--{{ Form::select('categories[]',$categories, null, ['class' => 'no-padding select-category ','multiple'=>'multiple']) }}--}}
                                        <select class="form-control" name="role" id="role" required>
                                            <option value="">Select a Role</option>

                                            <option value="1"  {{  ( $user->role == 1 ? ' selected' : '') }}>Admin</option>
                                            <option value="2"  {{  ( $user->role == 2 ? ' selected' : '') }}>User</option>
                                            <option value="3"  {{  ( $user->role == 3 ? ' selected' : '') }}>Supervisor</option>
                                            <option value="4"  {{  ( $user->role == 4 ? ' selected' : '') }}>Mechanic</option>

                                        </select>
                                        {{--{{ Form::select('categories[]', $categories, ['class' => 'demoInputBox form-control select-category','id'=>'category'])}}--}}
                                        <span class="text-danger">{{ $errors->first('role') }}</span>
                                    </div>
                                </div>


                                <div
                                    class="form-group row {{ $errors->has('fleet_email') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Fleet Email</label>
                                    <div class="col-9">
                                        <select class="form-control" name="fleet_email" required>
                                            <option value="1" @if($user->fleet_email == 1) selected @endif>Yes - Get Notification</option>
                                            <option value="0" @if($user->fleet_email == 0) selected @endif>No</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('fleet_email') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('mechanic') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Mechanic</label>
                                    <div class="col-9">
                                        <select class="form-control" name="mechanic" required>
                                            <option value="1" @if($user->mechanic == 1) selected @endif>Yes</option>
                                            <option value="0" @if($user->mechanic == 0) selected @endif>No</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('mechanic') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('articles') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Articles</label>
                                    <div class="col-9">
                                        <select class="form-control" name="articles" required>
                                        <option value="1" @if($user->articles == 1) selected @endif>Yes</option>
                                            <option value="0" @if($user->articles == 0) selected @endif>No</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('articles') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('categories') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Categories</label>
                                    <div class="col-9">
                                        <select class="form-control" name="categories" required>
                                            <option value="0" @if($user->categories == 0) selected @endif>No</option>
                                            <option value="1" @if($user->categories == 1) selected @endif>Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('categories') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('event_calendar') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Event Calendar</label>
                                    <div class="col-9">
                                        <select class="form-control" name="event_calendar" required>
                                            <option value="0" @if($user->event_calendar == 0) selected @endif>No</option>
                                            <option value="1" @if($user->event_calendar == 1) selected @endif>Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('event_calendar') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('fleet_managment') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Fleet Management</label>
                                    <div class="col-9">
                                        <select class="form-control" name="fleet_managment" required>
                                            <option value="0" @if($user->fleet_management == 0) selected @endif>No</option>
                                            <option value="1" @if($user->fleet_management == 1) selected @endif>Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('fleet_managment') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('mechanic') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Document managment</label>
                                    <div class="col-9">
                                        <select class="form-control" name="document_managment" required>
                                            <option value="0" @if($user->document_management == 0) selected @endif>No</option>
                                            <option value="1" @if($user->document_management == 1) selected @endif>Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('document_managment') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('department_links') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Department Links</label>
                                    <div class="col-9">
                                        <select class="form-control" name="department_links" required>
                                            <option value="0" @if($user->department_links == 0) selected @endif>No</option>
                                            <option value="1" @if($user->department_links == 1) selected @endif>Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('department_links') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('support') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Support</label>
                                    <div class="col-9">
                                        <select class="form-control" name="support" required>
                                            <option value="0" @if($user->support == 0) selected @endif >No</option>
                                            <option value="1" @if($user->support == 1) selected @endif>Yes</option>
                                        </select>
                                        <span class="text-danger">{{ $errors->first('support') }}</span>
                                    </div>
                                </div>
                                <div
                                    class="form-group row {{ $errors->has('configuration') ? 'has-error' : '' }}">
                                    <label class="col-3 required">Configuration</label>
                                    <div class="col-9">
                                        <select class="form-control" name="configuration" required>
                                            <option value="0" @if($user->configuration == 0) selected @endif>No</option>
                                            <option value="1" @if($user->configuration == 1) selected @endif>Yes</option>
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
@endsection

@section('scripts')
<script >

    // $(document).ready(function() {
    //   $("#article_add_form").validate();
    // });
    function validated(){
        $("#content").click(function(){      
        validate();         
        return false;
    })
        $("#user_update_form").validate({
         
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
                        role:{
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
        if ($('#user_update_form').valid()) // check if form is valid
        {
            $("#user_update_form").submit();
        }
        else 
        {
            return;
        }
    }
   





</script>

@endsection