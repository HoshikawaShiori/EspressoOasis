@extends('layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{ URL::to('src/css/shop-card.css') }}">
@endsection
@section('title')
    About us
@endsection

@section('content')
    <div class="container-fluid h-100 px-0">
        <div class="row row-fluid vh-25 p-0">
            <div class="col-12 centered p-5 hero" style="background: url('{{ asset('src/images/espressopull.jpg') }}');">

                <div class="header">
                    <h1 class="display-2 text-light">
                        About Us
                    </h1>
                </div>

            </div>


            {{-- row2 --}}
            <div class="col-md-6 pb-0 centered mt-5">
                <img src="{{ asset('src/images/brewing.jpg') }}" style="height: 400px" class="img-fluid"
                    alt="Responsive image">
            </div>
            <div class="col-md-6 centered p-5 pb-0 hero ps-0">

                <div class="header">
                    <h2 class="pb-3">
                        Espresso Oasis: Manila's Premier Coffee Sanctuary
                    </h2>
                    <p class="text-poppins lh-lg text-start">
                        "Located in the heart of vibrant Manila, Espresso Oasis stands as a premier haven for coffee
                        enthusiasts seeking an unforgettable experience. Nestled within the bustling cityscape, our café
                        offers more than just a delightful cup of coffee; it embodies a tranquil refuge where each sip
                        transports you to a realm of rich aromas and unparalleled flavors. At Espresso Oasis, we
                        meticulously source and craft our blends, ensuring every brew narrates a story of perfection and
                        passion. Our welcoming ambiance coupled with Manila's lively spirit creates an inviting space for
                        patrons to unwind, connect, and immerse themselves in the artistry of coffee, making every visit a
                        journey of pure indulgence."</p>
                </div>

            </div>
            {{-- row3 --}}

            <div class="col-md-6 centered p-5 pb-0 hero ps-6 mt-3">

                <div class="header text-end">
                    <h2 class="pb-3">
                        "Step into a World of Exceptional Taste
                    </h2>
                    <p class="text-poppins lh-lg">At Espresso Oasis, our commitment to excellence extends beyond the
                        inviting ambiance. Embrace a curated selection of globally sourced coffee beans, meticulously
                        handpicked to deliver a symphony of flavors with each cup. From the bold richness of single-origin
                        blends to the subtle complexities of artisanal brews, our diverse menu caters to every discerning
                        palate. Elevate your coffee experience and embark on a flavorful journey that reflects the passion
                        and expertise behind each finely brewed cup."

                    </p>
                </div>

            </div>

            <div class="col-md-6 pb-0 centered pe-5">
                <img src="{{ asset('src/images/latteart.jpg') }}" style="height: 400px" class="img-fluid"
                    alt="Responsive image">
            </div>

            @include('partials.footer')
        </div>
    </div>
@endsection
