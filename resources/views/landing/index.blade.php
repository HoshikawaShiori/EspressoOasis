@extends('layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{ URL::to('src/css/shop-card.css') }}">
@endsection
@section('title')
    Home
@endsection

@section('content')
    <div class="container-fluid h-100">
        <div class="row row-fluid vh-100">
            <div class="col-md-6 centered p-5 hero" style="background: url('{{ asset('src/images/pattern.png') }}');">

                <div class="header">
                    <h1 class="display-2">
                        Indulge in Every Sip: <strong style="color: #D19B6C">Espresso Oasis</strong>, Where Your Coffee
                        Dreams Come True!
                    </h1>
                    <a class="btn p-3" href="{{ route('coffee.shop') }}" role="button"><strong>Shop now!</strong></a>
                </div>

            </div>
            <div class="col-md-6 centered"
                style="background: url('{{ asset('src/images/hero.jpg') }}'); background-size: cover;  background-position: center">

            </div>

            {{-- row2 --}}
            <div class="col-md-6 pb-0 centered">
                <img src="{{ asset('src/images/hero3.jpg') }}" style="height: 400px" class="img-fluid"
                    alt="Responsive image">
            </div>
            <div class="col-md-6 centered p-5 pb-0 hero ps-0">

                <div class="header">
                    <h2 class="pb-3">
                        Taste the Essence of Pure Coffee Joy
                    </h2>
                    <p class="text-poppins lh-lg text-start">Espresso Oasis, your sanctuary of rich aromas and exquisite
                        flavors, invites you to discover a haven
                        where each cup is a journey. Delight in our meticulously crafted brews that capture the essence of
                        pure coffee joy. From the first sip to the last, immerse yourself in the sublime experience curated
                        just for you. Indulge in the magic of Espresso Oasis and awaken your senses to a world of
                        unparalleled coffee bliss.</p>
                </div>

            </div>
            {{-- row3 --}}

            <div class="row-fluid pb-2">
                <div class="container d-flex justify-content-center align-items-center text-center">
                    <div class="header">
                        <h2 class="display-5 pb-5">
                            Explore Our Range of Coffees
                        </h2>
                        <div class="container-fluid">
                            <div class="row row-fluid row-cols-1 row-cols-md-2 g-4">

                                @foreach ($coffees->chunk(1) as $coffeeChunk)
                                    @foreach ($coffeeChunk as $coffee)
                                        <div class="col-lg-4 mb-3 text-poppins">
                                            <div class="card border-0 m-5 my-6" style="min-width: 300px;">
                                                <div class="card-header border-0 py-5">
                                                    <!-- Image -->
                                                    <img src="{{ $coffee->imagePath }}" alt="Profile Image"
                                                        class="profile-img">
                                                </div>
                                                <div class="card-body border-0 p-5 px-0">
                                                    <!-- Coffee title -->
                                                    <h3 class="name">{{ $coffee->title }}</h3>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        <a class="btn p-3 px-4" href="{{ route('coffee.shop') }}" role="button"><strong>View
                                More</strong></a>
                    </div>
                </div>
            </div>

            {{-- row4 --}}
            <div class="col-md-6 centered p-5 pb-0 hero ps-6">

                <div class="header text-end">
                    <h2 class="pb-3">
                        Join the Espresso Oasis Family for Exclusive Coffee Delights!
                    </h2>
                    <p class="text-poppins lh-lg">Sign up today to become a part of our inner circle and unlock a
                        world of exclusive offers, early access to new blends, and insider updates on coffee events. Embrace
                        the true essence of our coffee culture. Your journey to unparalleled taste experiences begins here.
                        Don't miss out; subscribe now and elevate your coffee moments with Espresso Oasis.</p>

                        <a class="btn p-3" href="{{ route('user.signup') }}" role="button"><strong>Sign up now!</strong></a>
                </div>

            </div>

            <div class="col-md-6 pb-0 centered pe-5">
                <img src="{{ asset('src/images/hero3.jpg') }}" style="height: 400px" class="img-fluid"
                    alt="Responsive image">
            </div>


        </div>
    </div>
@endsection
