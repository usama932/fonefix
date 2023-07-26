@extends('admin.layouts.master')
@section('title',$title)
@section('stylesheets')

@endsection
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
                <a href="" class="text-muted">Manage Roles</a>
              </li>
              <li class="breadcrumb-item text-muted">
                <a href="" class="text-muted">Add Role</a>
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
              <h3 class="card-label">Role Add Form
                <i class="mr-2"></i>
                <small class="">try to scroll the page</small></h3>

            </div>
            <div class="card-toolbar">

              <a href="{{ route('roles.index') }}" class="btn btn-light-primary
              font-weight-bolder mr-2">
                <i class="ki ki-long-arrow-back icon-sm"></i>Back</a>

              <div class="btn-group">
                <a href="{{ route('roles.store') }}"  onclick="event.preventDefault(); document.getElementById('client_add_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                  <i class="ki ki-check icon-sm"></i>Save</a>



              </div>
            </div>
          </div>
          <div class="card-body">
          @include('admin.partials._messages')
          <!--begin::Form-->
            {{ Form::open([ 'route' => 'roles.store','class'=>'form' ,"id"=>"client_add_form", 'enctype'=>'multipart/form-data']) }}
              @csrf
              <div class="row">
                  <div class="col-md-4">
                      <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                          <label>Name</label>
                          {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name','placeholder'=>'Enter Name','required'=>'true']) }}
                          <span class="text-danger">{{ $errors->first('name') }}</span>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <hr>
              </div>
              <div class="row">
                <div class="col-md-3">
                    <h3 class="text-dark font-weight-bold mb-10">Client  </h3>

                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label class="col-4 col-form-label">Select All</label>
                        <div class="col-6">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" class="selectAll"  name="user_all" value="0">
                            <span></span>
                          </label>
                        </span>
                        </div>
                    </div>
                </div>
                  <div class="col-md-5">
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Add Client</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="user_add" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Client</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="user_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Client</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="user_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Delete Client</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="user_delete" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Repair History</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="user_history" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Enable/Disable  Client</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="user_enable" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <hr style="">
              </div>
              <div class="row">
                  <div class="col-md-3">
                      <h3 class="text-dark font-weight-bold mb-10">Brand  </h3>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Select All</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" class="selectAll"  name="brand_all" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-5">
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Add Brand</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="brand_add" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Brand</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="brand_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Brand</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="brand_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Delete Brand</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="brand_delete" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <hr style="">
              </div>
              <div class="row">
                  <div class="col-md-3">
                      <h3 class="text-dark font-weight-bold mb-10">Devices  </h3>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Select All</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" class="selectAll" name="device_all" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-5">
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Add Device</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="device_add" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Device</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="device_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Device</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="device_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Delete Device</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="device_delete" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <hr style="">
              </div>
              <div class="row">
                  <div class="col-md-3">
                      <h3 class="text-dark font-weight-bold mb-10">Product  </h3>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Select All</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" class="selectAll"  name="product_all" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-5">

                      <div class="form-group row">
                          <label class="col-4 col-form-label">Add Product</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="product_add" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Product</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="product_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Product</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="product_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Delete Product</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="product_delete" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Manage Stock</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="product_manage_stock" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Purchase Price</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="product_purchase_price" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Minimum Sell Price</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="product_sell_price" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Minimum Discount Value</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="product_discount" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <hr style="">
              </div>
              <div class="row">
                  <div class="col-md-3">
                      <h3 class="text-dark font-weight-bold mb-10">Job Sheet  </h3>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Select All</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" class="selectAll"  name="job_all" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-5">

                      <div class="form-group row">
                          <label class="col-4 col-form-label">Add Jobs</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="job_add" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Jobs</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="job_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Jobs</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="job_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Delete Job</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="job_delete" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Only Assigned Job</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="job_assigned" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Change Status Job</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="job_change_status" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Add part to Job</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="job_add_parts" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Select All Invoices</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" class="selectAll" name="invoice_all" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Add Invoice</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="invoice_add" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Invoice</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="invoice_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Invoice</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="invoice_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Delete Invoice</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="invoice_delete" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Change Status Invoice</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="invoice_change_status" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <hr style="">
              </div>
              <div class="row">
                  <div class="col-md-3">
                      <h3 class="text-dark font-weight-bold mb-10">Enquiries </h3>
                  </div>
                  <div class="col-md-4">
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Select All</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" class="selectAll" name="enquiries_all" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-5">


                      <div class="form-group row">
                          <label class="col-4 col-form-label">Add Enquiry</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="enquiries_add" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Enquiry</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="enquiries_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Enquiry</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="enquiries_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Delete Enquiry</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="enquiries_delete" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Send Enquiry TO Customer</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="enquiries_send" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="row">
                  <hr style="">
              </div>
              <div class="row">
                  <div class="col-md-3">
                      <h3 class="text-dark font-weight-bold mb-10">Setting </h3>

                  </div>
                  <div class="col-md-4">
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Select All</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox" class="selectAll" name="setting_all" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
                  <div class="col-md-5">


                      <div class="form-group row">
                          <label class="col-4 col-form-label">View All Settings</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_view_all" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>

                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Basic Setting</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_basic_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Basic Setting </label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_basic_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Sms Setting</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_sms_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Sms Setting</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_sms_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Job Sheet Setting</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_job_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Job Sheet Setting</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_job_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Email Setting</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_email_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Email Setting</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_email_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Other Setting</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_other_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Other Setting</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_other_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>

                      <div class="form-group row">
                          <label class="col-4 col-form-label">Edit Cms Setting</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_cms_edit" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                      <div class="form-group row">
                          <label class="col-4 col-form-label">View Cms Setting</label>
                          <div class="col-3">
                         <span class="switch switch-outline switch-icon switch-success">
                          <label><input type="checkbox"  name="setting_cms_view" value="0">
                            <span></span>
                          </label>
                        </span>
                          </div>
                      </div>
                  </div>
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
        $(".selectAll").change(function () {
            if($(this).prop("checked") == true){
                $(this).parent().parent().parent().parent().parent().parent().find(':checkbox').each(function() {
                    this.checked = true;
                });
            }
            else if($(this).prop("checked") == false){
                $(this).parent().parent().parent().parent().parent().parent().find(':checkbox').each(function() {
                    this.checked = false;
                });
            }
        });
    </script>
@endsection
