@extends('admin.layouts.master')
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
                <a href="" class="text-muted">Manage Plan</a>
              </li>
              <li class="breadcrumb-item text-muted">
                Edit Plan
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
              <h3 class="card-label">Plan Edit Form
                <i class="mr-2"></i>
              </h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('plans.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href=""  onclick="event.preventDefault(); document.getElementById('country_update_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>update</a>
              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::model($plan, [ 'method' => 'PATCH','route' => ['plans.update', $plan->id],'class'=>'form' ,"id"=>"country_update_form"])}}
              @csrf
              <div class="row">
                <div class="col-xl-2"></div>
                <div class="col-xl-8">
                  <div class="my-5">
                    <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                      <label class="col-3 col-form-label text-right">Name:</label>
                      <div class="col-6">
                        {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter name','required'=>'true']) }}
                        <span class="text-danger">{{ $errors->first('name') }}</span>
                      </div>
                    </div>
                    <div class="form-group row {{ $errors->has('amount') ? 'has-error' : '' }}">
                      <label class="col-3 col-form-label text-right">Amount:</label>
                      <div class="col-6">
                        <input type="text" id="amount" name="amount" required placeholder="Enter amount" readonly class="form-control form-control-solid" value="{{$plan->amount}}">
                        <div id="float-error" class="error" style="display: none;">Please enter a valid float number</div>
                        <span class="text-danger">{{ $errors->first('amount') }}</span>
                      </div>
                    </div>
                    <div class="form-group row {{ $errors->has('interval') ? 'has-error' : '' }}">
                      <label class="col-3 col-form-label text-right">Interval:</label>
                      <div class="col-6">
                      <select class="form-control" name="interval" id="interval" required data-plugin="select2">
                          <option value="day" @if($plan->interval == 'day') selected @endif>Day</option>
                          <option value="week" @if($plan->interval == 'week') selected @endif>Week</option>
                          <option value="month" @if($plan->interval == 'month') selected @endif>Month</option>
                          <option value="year" @if($plan->interval == 'year') selected @endif>Year</option>
                        </select>
                        <span class="text-danger">{{ $errors->first('interval') }}</span>
                      </div>
                    </div>
                    <div class="form-group row {{ $errors->has('currency') ? 'has-error' : '' }}">
                      <label class="col-3 col-form-label text-right">Currency:</label>
                      <div class="col-6">
                      <select class="form-control" name="currency" id="currency" required data-plugin="select2">
                        @foreach($currencies as $currency)
                          <option value="{{ $currency['name'] }}" @if($plan->currency == $currency['name']) selected @endif>{{ $currency['name'] }}</option>
                        @endforeach
                        </select>
                        <span class="text-danger">{{ $errors->first('currency') }}</span>
                      </div>
                    </div>
                    <div class="form-group row {{ $errors->has('nickname') ? 'has-error' : '' }}">
                      <label class="col-3 col-form-label text-right">Nick Name:</label>
                      <div class="col-6">
                        <input type="text" id="nickname" name="nickname" required placeholder="Enter nickname" class="form-control form-control-solid"  value="{{ $plan->nickname}}">
                        <span class="text-danger">{{ $errors->first('nickname') }}</span>
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
