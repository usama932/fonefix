@extends('admin.layouts.master')
@section('title',$title)
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-12" id="accordion">
                <div class="card card-primary    card-outline">
                    <div class="d-block w-100" data-toggle="">
                        <div class="card-header">
                            <div class="row mb-2">
                                <div class="col-sm-6">
                                    <h1>Update Fleet Complaint</h1>
                                </div>
                                <div class="col-sm-6">
                                    <ol class=" float-sm-right">
                                        <a class="btn btn-light-primary btn-sm"
                                           href="{{ route('fleet-complaints.index') }}">
                                            <i class="ki ki-long-arrow-back icon-sm"></i>
                                            Back
                                        </a> &nbsp;&nbsp;&nbsp;
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="collapseNine" class="" data-parent="#">
                        <div class="card-body">
                            <div class="info">

                                {{ Form::model($fleet_complaint, [ 'method' => 'PATCH','route' => ['fleet-complaints.update', $fleet_complaint->id],'class'=>'form' ,"id"=>"fleet_complaints_update_form", 'enctype'=>'multipart/form-data'])}}
                                @csrf
                                <div class="row">
                                    <div class="col-xl-2"></div>
                                    <div class="col-xl-8">
                                        <div class="my-5">
                                            <h3 class="text-dark font-weight-bold mb-10">Fleet Complaint Info: </h3>
                                            <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                                                <label class="col-3">Name</label>
                                                <div class="col-9">
                                                    {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name', 'placeholder'=>'Enter Name','required'=>'true']) }}
                                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xl-2"></div>
                                </div>
                                {{Form::close()}}
                            </div>
                        </div>
                        <div
                            class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="col-3"></label>
                            <div class="col-9">
                                <a class="btn btn-primary btn-lg " href=""
                                   onclick="event.preventDefault(); document.getElementById('fleet_complaints_update_form').submit();"
                                   id="kt_btn">
                                    <i class="ki ki-check icon-sm">
                                    </i>
                                    update
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
