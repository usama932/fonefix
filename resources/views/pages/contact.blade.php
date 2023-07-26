@extends('layouts.master')
@section('content')


<main class="d-flex flex-column justify-content-start flex-grow-1 ">


<!-- page header Start-->
<div class="page_header section_space">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h2 class="page_header_title title_divider text-uppercase mb-3">{{ $contact['contact_heading']}}</h2>
                <p>{!! $contact['contact_detail'] !!}</p>
            </div>
        </div>
    </div>
</div>
<!-- page header End-->

<!-- Start-->
<div class="section_space pt-0">
    <div class="container">
        <div class="row g-5 justify-content-center">
            <div class="col-md-5">
                <form  method="POST" action="{{route('contact')}}">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fName" placeholder="Name*" required>
                        <label for="fName">Name*</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" id="fEmail" placeholder="Email*" required>
                        <label for="fEmail">Email*</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="fPhone" placeholder="Phone*" required>
                        <label for="fPhone">Phone*</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea class="form-control" placeholder="Your Message*" id="fMessage" rows="5" required></textarea>
                        <label for="fMessage">Your Message</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
            <div class="col-md-4">
                <ul class="contact_blocks">
                    <!--<li class="d-flex align-items-center mb-5">-->
                    <!--    <a class="stretched-link" href="tel:{{$contact['phone_no']}}"></a>-->
                    <!--    <div class="contact_block_icon">-->
                    <!--        <i class="fas fa-phone-alt"></i>-->
                    <!--    </div>-->
                    <!--    <div class="contact_block_content">-->
                    <!--        <h5 class="mb-0">Phone #</h5>-->
                    <!--        <p class="mb-0">{{$contact['phone_no']}}</p>-->
                    <!--    </div>-->
                    <!--</li>-->

                    <li class="d-flex align-items-center  mb-5">
                        <a class="stretched-link" href="mailto:email@email.com"></a>
                        <div class="contact_block_icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact_block_content">
                            <h5 class="mb-0">Email</h5>
                            <p class="mb-0">{{$contact['email']}}</p>
                        </div>
                    </li>

                    <!--<li class="d-flex align-items-center">-->
                    <!--    <div class="contact_block_icon">-->
                    <!--        <i class="fas fa-map-marked-alt"></i>-->
                    <!--    </div>-->
                    <!--    <div class="contact_block_content">-->
                    <!--        <h5 class="mb-0">Address</h5>-->
                    <!--        <p class="mb-0">{!! $contact['address'] !!} </p>-->
                    <!--    </div>-->
                    <!--</li>-->
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End-->

<!-- Newsletter Start-->
<!-- @include('partials.newsletter')  -->
<!-- Newsletter End-->
</main>

    @endsection
    <!-- End-->



<!-- footer Start-->

<!-- footer End-->

<!-- Js -->




