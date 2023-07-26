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

  
</style>
@endsection
@section('content')

    <section class="content">
        <div class="row">
            <div class="col-lg-12 create_article_box" id="accordion">
                <div class="card card-primary    card-outline">
                    <div class="d-block w-100" data-toggle="">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-sm-6">
                                    <h1 class="create_article_box_title">Add Document</h1>
                                </div>
                                <div class="col-sm-6">
                                    <div class="float-sm-right mb-0">
                                        <a class="btn btn-light-primary btn-sm"
                                           href="{{ route('documents.index') }}">
                                            <i class="ki ki-long-arrow-back icon-sm"></i>
                                            Back
                                        </a> &nbsp;&nbsp;&nbsp;

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="collapseNine" class="" data-parent="#">
                        <div class="card-body">
                            <div class="info">
                                @include('admin.partials._messages')
                                {{ Form::open([ 'route' => 'documents.store','class'=>'form' ,"id"=>"article_add_form", 'enctype'=>'multipart/form-data']) }}
                                @csrf
                                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label class=" form-label required">Name</label>
                                    {{ Form::text('name', null, ['class' => 'form-control','id'=>'name','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                                <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                    <label class=" form-label required">Description</label>
                                    {{ Form::textarea('description', null, ['class' => 'form-control','id'=>'description','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <span class="text-danger">{{ $errors->first('description') }}</span>
                                </div>
                                <div class="form-group {{ $errors->has('folder') ? 'has-error' : '' }}">
                                    <label class=" form-label required">Folder</label>
                                    <select name="folder" id="folder" class="form-control">
                                    <option value="" >Select folder</option>

                                        @foreach($folders as $folder)
                                        <option value="{{$folder->id}}">{{$folder->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger">{{ $errors->first('folder') }}</span>
                                </div>
                                <div class="form-group {{ $errors->has('document') ? 'has-error' : '' }}">
                                    <label class=" form-label ">Document</label>
                                    {{ Form::file('document', null, ['class' => 'form-control','id'=>'title','placeholder'=>'Enter Here','required'=>'true']) }}
                                    <!-- <span class="text-danger">{{ $errors->first('document') }}</span> -->
                                </div>
                                {{Form::close()}}

                            </div>
                            <div class="wrap-vid">


                            </div>
                            <div>

                            </div>

                            <div class="form-group card-body-footer">
                                <a class="btn btn-primary btn-lg" href="javascript:void(0)" onclick="test();">
                                    Save
                                </a>
                            </div>

                        </div>

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
    function test(){
       
        $("#article_add_form").validate({
         
                    errorClass: "error fail-alert",
                    validClass: "valid success-alert",
                    rules: {
                        name: {
                            required: true
                          
                        },
                        description: {
                            required: true

                        },
                        folder: {
                            required: true

                        }
                       
                    },
                    messages: {
                        name: {

                            required: "Please enter document name",
                            minlength: "Name should be at least 5 characters"
                        },
                        description: {
                            required: "Please enter description"

                        },
                        folder: {
                            required: "Please select folder"
                        
                        }
                    }
                    });
        if ($('#article_add_form').valid()) // check if form is valid
        {
            $("#article_add_form").submit();
        }
        else 
        {
            return;
        }
    }
   





</script>
<script>

    $(document).ready(function() {

        $(".select2").select2({
            allowClear: true
        });
    });
 </script>

@endsection