@extends('admin.layouts.master')
@section('title',$title)
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
                                    <h1>Update Supervisor {{ $user->name }}</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class="breadcrumb float-sm-right">
                                        <a class="btn btn-light-primary btn-sm"
                                           href="{{ route('supervisors.index') }}">
                                            <i class="ki ki-long-arrow-back icon-sm"></i>
                                            Back
                                        </a> &nbsp;&nbsp;&nbsp;
                                        <a class="btn btn-info btn-sm"
                                           href=""
                                           onclick="event.preventDefault(); document.getElementById('supervisor_update_form').submit();"
                                           id="kt_btn">
                                            <i class="ki ki-check icon-sm">
                                            </i>
                                            update
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
                    {{ Form::model($user, [ 'method' => 'PATCH','route' => ['supervisors.update', $user->id],'class'=>'form' ,"id"=>"supervisor_update_form", 'enctype'=>'multipart/form-data'])}}
                    @csrf
                    <div class="row">
                        <div class="col-xl-2"></div>
                        <div class="col-xl-8">
                            <div class="my-5">
                                <h3 class="text-dark font-weight-bold mb-10">Supervisor Info: </h3>
                                <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label class="col-3">Name</label>
                                    <div class="col-9">
                                        {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Name','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                                    <label class="col-3">Email</label>
                                    <div class="col-9">
                                        {{ Form::email('email', null, ['class' => 'form-control form-control-solid','id'=>'email','placeholder'=>'Email Address','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('password') ? 'has-error' : '' }}">
                                    <label class="col-3">Password</label>
                                    <div class="col-9">
                                        {{ Form::text('password','', ['placeholder'=>"If you won't change Password then leave it blank as it is.", 'class' => 'form-control form-control-solid','id'=>'password','required'=>'true']) }}
                                        <span class="text-danger">{{ $errors->first('password') }}</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-3 col-form-label">Active</label>
                                    <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" {{ ($user->active) ?'checked':'' }} name="active" value="1">
                            <span></span>
                          </label>
                        </span>
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
