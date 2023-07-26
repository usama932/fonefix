@extends('admin.layouts.master')
@section('title',$title)
<style>

    .btn-text-right{
        text-align:left !important;
    }
    </style>
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
                                <a href="" class="text-muted">CMS Setting</a>
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
                            <h3 class="card-label">CMS Setting
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
                                        $add = $user->permission->setting_basic_edit;
                                    }
                                @endphp
                                @if($add)
                                    <a href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('setting_form').submit();" id="kt_btn" class="btn btn-primary font-weight-bolder">
                                        <i class="ki ki-check icon-sm"></i>Save</a>
                                @endif



                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    @include('admin.partials._messages')
                    <!--begin::Form-->
                        <form class="form" id="setting_form" method="POST" action="{{ route('cms-setting.store') }}" enctype='multipart/form-data'>
                            @csrf
                            <input type="hidden" name="id" value="@if($settings){{$settings->id}}@else{{0}}@endif" />
                            <input type="hidden" id="symbol" name="symbol" value="@if($settings){{$settings->symbol}}@endif" />
                            <div class="row">
                                <h3>Sliders</h3>
                                <div class="btn-text-right">
                                    <button type="button" class="btn btn-primary btn-sm mx-3 mb-3" data-toggle="modal" data-target="#exampleModalCenter">
                                        Add Slider
                                    </button>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Button Text</th>
                                            <th>Button Url</th>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($sliders))
                                            @foreach ($sliders as $slider)
                                                <tr>
                                                    <td>{{  $slider->title }}</td>
                                                    <td>{{  $slider->button_text ?? 'NAN' }}</td>
                                                    <td>{{  $slider->button_url ?? 'NAN' }}</td>

                                                    <td> <img src="{{asset("uploads/$slider->image")}}" class="d-block  " alt="..." style="height:100px !important; with:120px !important;"></td>
                                                    <td>
                                                        <a href="" data-toggle="modal" data-target="#edit{{ $slider->id }}">
                                                            <i class="icon-1x text-dark-50 flaticon-edit"></i>
                                                        </a>
                                                        <a href="{{route('delete.slide',$slider->id) }}" >
                                                            <i class="icon-1x text-dark-50 flaticon-delete"></i>
                                                        </a>

                                                    </td>

                                                </tr>
                                            @endforeach

                                        @else
                                            <tr>
                                                <td>No Found Data</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                            </div>
                            <div class="row">
                                <hr>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('feature_text1') ? 'has-error' : '' }}">
                                        <label class="">
                                            Feature1 Text</label>
                                        <div class="">
                                            {{ Form::text('feature_text1', ($settings)?$settings->feature_text1:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('feature_text1') }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('feature_image1') ? 'has-error' : '' }}">
                                        <label class="">Feature1 Image</label>
                                        <div class="">
                                            {{ Form::file('feature_image1', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            @if($settings)
                                                @if($settings->feature_image1)
                                                    <img src="{{asset("uploads/$settings->feature_image1")}}" width="200" alt="">
                                                @endif
                                            @endif
                                            <span class="text-danger">{{ $errors->first('feature_image1') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('feature_text2') ? 'has-error' : '' }}">
                                        <label class="">
                                            Feature2 Text</label>
                                        <div class="">
                                            {{ Form::text('feature_text2', ($settings)?$settings->feature_text2:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('feature_text2') }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('feature_image2') ? 'has-error' : '' }}">
                                        <label class="">Feature2 Image</label>
                                        <div class="">
                                            {{ Form::file('feature_image2', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            @if($settings)
                                                @if($settings->feature_image2)
                                                    <img src="{{asset("uploads/$settings->feature_image2")}}" width="200" alt="">
                                                @endif
                                            @endif
                                            <span class="text-danger">{{ $errors->first('feature_image2') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('feature_text3') ? 'has-error' : '' }}">
                                        <label class="">
                                            Feature3 Text</label>
                                        <div class="">
                                            {{ Form::text('feature_text3', ($settings)?$settings->feature_text3:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('feature_text3') }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('feature_image3') ? 'has-error' : '' }}">
                                        <label class="">Feature3 Image</label>
                                        <div class="">
                                            {{ Form::file('feature_image3', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            @if($settings)
                                                @if($settings->feature_image3)
                                                    <img src="{{asset("uploads/$settings->feature_image3")}}" width="200" alt="">
                                                @endif
                                            @endif
                                            <span class="text-danger">{{ $errors->first('feature_image3') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('feature_text4') ? 'has-error' : '' }}">
                                        <label class="">
                                            Feature4 Text</label>
                                        <div class="">
                                            {{ Form::text('feature_text4', ($settings)?$settings->feature_text4:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('feature_text4') }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('feature_image4') ? 'has-error' : '' }}">
                                        <label class="">Feature4 Image</label>
                                        <div class="">
                                            {{ Form::file('feature_image4', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            @if($settings)
                                                @if($settings->feature_image4)
                                                    <img src="{{asset("uploads/$settings->feature_image4")}}" width="200" alt="">
                                                @endif
                                            @endif
                                            <span class="text-danger">{{ $errors->first('feature_image4') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('about_title') ? 'has-error' : '' }}">
                                        <label class="">
                                            About Title</label>
                                        <div class="">
                                            {{ Form::text('about_title', ($settings)?$settings->about_title:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('about_title') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('about_video') ? 'has-error' : '' }}">
                                        <label class="">
                                            About Video Url</label>
                                        <div class="">
                                            {{ Form::text('about_video', ($settings)?$settings->about_video:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('about_video') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('description') ? 'has-error' : '' }}">
                                        <label class="">
                                            About Description</label>
                                        <div class="">
                                            {{ Form::textarea('description', ($settings)?$settings->description:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('description') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('guarantee') ? 'has-error' : '' }}">
                                        <label class="">
                                            Guarantee & Maintenance</label>
                                        <div class="">
                                            {{ Form::textarea('guarantee', ($settings)?$settings->guarantee:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('guarantee') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('quality') ? 'has-error' : '' }}">
                                        <label class="">
                                            Quality Services</label>
                                        <div class="">
                                            {{ Form::textarea('quality', ($settings)?$settings->quality:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('quality') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('repairs') ? 'has-error' : '' }}">
                                        <label class="">
                                            Repairs</label>
                                        <div class="">
                                            {{ Form::number('repairs', ($settings)?$settings->repairs:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('repairs') }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('about_image') ? 'has-error' : '' }}">
                                        <label class="">About Image</label>
                                        <div class="">
                                            {{ Form::file('about_image', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            @if($settings)
                                                @if($settings->about_image)
                                                    <img src="{{asset("uploads/$settings->about_image")}}" width="200" alt="">
                                                @endif
                                            @endif
                                            <span class="text-danger">{{ $errors->first('about_image') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('service_text1') ? 'has-error' : '' }}">
                                        <label class="">
                                            Service1 Text</label>
                                        <div class="">
                                            {{ Form::text('service_text1', ($settings)?$settings->service_text1:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('service_text1') }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('service_image1') ? 'has-error' : '' }}">
                                        <label class="">Service1 Image</label>
                                        <div class="">
                                            {{ Form::file('service_image1', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            @if($settings)
                                                @if($settings->service_image1)
                                                    <img src="{{asset("uploads/$settings->service_image1")}}" width="200" alt="">
                                                @endif
                                            @endif
                                            <span class="text-danger">{{ $errors->first('service_image1') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('service_text2') ? 'has-error' : '' }}">
                                        <label class="">
                                            Service2 Text</label>
                                        <div class="">
                                            {{ Form::text('service_text2', ($settings)?$settings->service_text2:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('service_text2') }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('service_image2') ? 'has-error' : '' }}">
                                        <label class="">Service2 Image</label>
                                        <div class="">
                                            {{ Form::file('service_image2', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            @if($settings)
                                                @if($settings->service_image2)
                                                    <img src="{{asset("uploads/$settings->service_image2")}}" width="200" alt="">
                                                @endif
                                            @endif
                                            <span class="text-danger">{{ $errors->first('service_image2') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('service_text3') ? 'has-error' : '' }}">
                                        <label class="">
                                            Service3 Text</label>
                                        <div class="">
                                            {{ Form::text('service_text3', ($settings)?$settings->service_text3:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('service_text3') }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('service_image3') ? 'has-error' : '' }}">
                                        <label class="">Service3 Image</label>
                                        <div class="">
                                            {{ Form::file('service_image3', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            @if($settings)
                                                @if($settings->service_image3)
                                                    <img src="{{asset("uploads/$settings->service_image3")}}" width="200" alt="">
                                                @endif
                                            @endif
                                            <span class="text-danger">{{ $errors->first('service_image3') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('project_text1') ? 'has-error' : '' }}">
                                        <label class="">
                                            Project1 Text</label>
                                        <div class="">
                                            {{ Form::text('project_text1', ($settings)?$settings->project_text1:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('project_text1') }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('project_image1') ? 'has-error' : '' }}">
                                        <label class="">Project1 Image</label>
                                        <div class="">
                                            {{ Form::file('project_image1', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            @if($settings)
                                                @if($settings->project_image1)
                                                    <img src="{{asset("uploads/$settings->project_image1")}}" width="200" alt="">
                                                @endif
                                            @endif
                                            <span class="text-danger">{{ $errors->first('project_image1') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('project_text2') ? 'has-error' : '' }}">
                                        <label class="">
                                            Project2 Text</label>
                                        <div class="">
                                            {{ Form::text('project_text2', ($settings)?$settings->project_text2:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('project_text2') }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('project_image2') ? 'has-error' : '' }}">
                                        <label class="">Project2 Image</label>
                                        <div class="">
                                            {{ Form::file('project_image2', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            @if($settings)
                                                @if($settings->project_image2)
                                                    <img src="{{asset("uploads/$settings->project_image2")}}" width="200" alt="">
                                                @endif
                                            @endif
                                            <span class="text-danger">{{ $errors->first('project_image2') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('project_text3') ? 'has-error' : '' }}">
                                        <label class="">
                                            Project3 Text</label>
                                        <div class="">
                                            {{ Form::text('project_text3', ($settings)?$settings->project_text3:null, ['class' => 'form-control req form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            <span class="text-danger">{{ $errors->first('project_text3') }}</span>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-6 form-group ">
                                    <div class="form-group  {{ $errors->has('project_image3') ? 'has-error' : '' }}">
                                        <label class="">Project3 Image</label>
                                        <div class="">
                                            {{ Form::file('project_image3', ['class' => 'form-control  form-control-solid','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                            @if($settings)
                                                @if($settings->project_image3)
                                                    <img src="{{asset("uploads/$settings->project_image3")}}" width="200" alt="">
                                                @endif
                                            @endif
                                            <span class="text-danger">{{ $errors->first('project_image3') }}</span>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
                <!--end::Card-->

            </div>
            <!--end::Container-->
        </div>
        <!--end::Entry-->
        {{-- start Modal create  --}}
        <div class="modal fade" id="exampleModalCenter" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body">
                        <form action="{{ route('slides.store') }}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group  {{ $errors->has('title') ? 'has-error' : '' }}">
                                <label class="">
                                    Title</label>
                                <div class="">
                                    <input type="text" class="form-control  form-control-solid" name="title" required>
                                    <span class="text-danger">{{ $errors->first('title') }}</span>
                                </div>

                            </div>
                            <div class="form-group  {{ $errors->has('button_text') ? 'has-error' : '' }}">
                                <label class="">
                                    Button Text</label>
                                <div class="">
                                    <input type="text" class="form-control  form-control-solid" name="button_text" >
                                    <span class="text-danger">{{ $errors->first('button_text') }}</span>
                                </div>

                            </div>
                            <div class="form-group  {{ $errors->has('url') ? 'has-error' : '' }}">
                                <label class="">
                                    Button Url</label>
                                <div class="">
                                    <input type="url" class="form-control  form-control-solid" name="button_url" >
                                    <span class="text-danger">{{ $errors->first('url') }}</span>
                                </div>

                            </div>
                            <div class="form-group  {{ $errors->has('image') ? 'has-error' : '' }}">
                                <label class="">
                                    Image</label>
                                <div class="">
                                    <input type="file" class="form-control  form-control-solid" name="image" required>
                                    <span class="text-danger">{{ $errors->first('image') }}</span>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                      </div>

                    </div>


                </div>
            </div>
        </div>
        {{--  End Modal create  --}}
        {{-- start Modal edit  --}}
        @foreach($sliders as $slider)
        <div class="modal fade" id="edit{{ $slider->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalCenterTitle">Edit Modal</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('slides.update',$slider->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('put')
                        <div class="form-group  {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label class="">
                                Title</label>
                            <div class="">
                                <input type="text" class="form-control  form-control-solid" name="title"  value="{{ $slider->title }}" required>
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            </div>

                        </div>
                        <div class="form-group  {{ $errors->has('button_text') ? 'has-error' : '' }}">
                            <label class="">
                                Button Text</label>
                            <div class="">
                                <input type="text" class="form-control  form-control-solid" name="button_text" value="{{ $slider->button_text }}" >
                                <span class="text-danger">{{ $errors->first('button_text') }}</span>
                            </div>

                        </div>
                        <div class="form-group  {{ $errors->has('url') ? 'has-error' : '' }}">
                            <label class="">
                                Button Url</label>
                            <div class="">
                                <input type="url" class="form-control  form-control-solid" name="button_url" value="{{ $slider->button_url }}" >
                                <span class="text-danger">{{ $errors->first('url') }}</span>
                            </div>

                        </div>
                        <div class="form-group  {{ $errors->has('image') ? 'has-error' : '' }}">
                            <label class="">
                                Image</label>
                            <div class="">
                                <input type="file" class="form-control  form-control-solid" name="image" >
                                <span class="text-danger">{{ $errors->first('image') }}</span>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>

              </div>
            </div>
        </div>
        @endforeach

        {{-- End Modal edit  --}}

@endsection
@section('scripts')
    <script !src="">
        $(".summernote").summernote();
        $("#currency").change(function() {
            updateSymbol();
        });
        $('document').ready(function() {
            updateSymbol();
        });
        function updateSymbol(){
            var option = $('#currency option:selected').attr('symbol');
            $("#symbol").val(option);
        }
    </script>
@endsection
