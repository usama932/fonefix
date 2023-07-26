@extends('layouts.master')
@section('content')
    <main class="d-flex flex-column justify-content-start flex-grow-1 ">
        <!-- page header Start-->
        <div class="page_header section_space">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center">
                        <h2 class="page_header_title title_divider text-uppercase mb-0">About Us</h2>
                    </div>
                </div>
            </div>
        </div>
        <!-- page header End-->
        <!-- Start-->
        <div class="section_space p-0">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        {!! $about['about_detail'] !!}
                    </div>
                </div>
            </div>
        </div>
        <!-- End-->
        <!-- newsletter Start-->
        <!-- @include('partials.newsletter') -->
        <!-- newsletter End-->
    </main>
@endsection
<!-- End-->