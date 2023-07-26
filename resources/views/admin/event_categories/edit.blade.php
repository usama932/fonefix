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

  

    /*label {*/
    /*    width: 300px;*/
    /*    font-weight: bold;*/
    /*    display: inline-block;*/
    /*    margin-top: 20px;*/
    /*}*/

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
<section class="content">
    <div class="row">
        <div class="col-12" id="accordion">
            <div class="card card-primary    card-outline">
                <div class="d-block w-100" data-toggle="">
                    <div class="card-header">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <h1>Update Event Category</h1>
                            </div>
                            <div class="col-sm-6">
                                <ol class="float-md-right">
                                  <a class="btn btn-primary btn-lg " href="javascript:void(0);" onclick="validated();" id="kt_btn">
                                        <i class="ki ki-check icon-sm">
                                        </i>
                                        update
                                    </a>


                                    <a class="btn btn-light-primary btn-sm" href="{{ route('event-categories.index') }}">
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
                            @include("admin.partials._messages")

                            {{ Form::model($event_category, [ 'method' => 'PATCH','route' => ['event-categories.update', $event_category->id],'class'=>'form' ,"id"=>"event_category_update_form", 'enctype'=>'multipart/form-data'])}}
                            @csrf
                            <div class="row">
                                <div class="col-xl-2"></div>
                                <div class="col-xl-8">
                                    <div class="my-5">
                                        <h3 class="text-dark font-weight-bold mb-10">EventCategory Info: </h3>
                                        <div class="form-group row {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <label class="col-3 required text-right">Name:</label>
                                            <div class="col-6">
                                                {{ Form::text('name', null, ['class' => 'form-control form-control-solid','id'=>'name', 'placeholder'=>'Enter Name','required'=>'true']) }}
                                                <span class="text-danger">{{ $errors->first('name') }}</span>
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
                    <!-- <div class="form-group row">
                        <label class="col-3"></label>
                        <div class="col-6">
                            <a class="btn btn-primary btn-lg " href="" onclick="event.preventDefault(); document.getElementById('event_category_update_form').submit();" id="kt_btn">
                                <i class="ki ki-check icon-sm">
                                </i>
                                update
                            </a>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')

 <script >

    // $(document).ready(function() {
    //   $("#article_add_form").validate();
    // });
    function validated(){
       
        $("#event_category_update_form").validate({
         
                    errorClass: "error fail-alert",
                    validClass: "valid success-alert",
                    rules: {
                        
                        name: {
                            required: true

                        }
                       

                    },
                    messages: {
                    
                        name: {
                            required: "Please enter name",

                        }
                      
                    }
                    });
        if ($('#event_category_update_form').valid()) // check if form is valid
        {
            $("#event_category_update_form").submit();
        }
        else 
        {
            return;
        }
    }
   





</script>
           
@endsection