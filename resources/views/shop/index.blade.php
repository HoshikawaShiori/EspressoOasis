@extends('layouts.master')
@section('title')
    Shop
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::to('src/css/shop-card.css') }}">
@endsection
@section('content')
    <div class="container">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif



        <h1 class=" display-4 text-center pt-5 pe-0 shop-head">Brewery</h1>
        <div class="row row-cols-1 row-cols-md-2 g-4">

            @foreach ($coffees->chunk(1) as $coffeeChunk)
                @foreach ($coffeeChunk as $coffee)
                    <div class="col-md-4">
                        <div class="card border-0">
                            <div class="card-header border-0">
                                <!-- Image -->
                                <img src="{{ $coffee->imagePath }}" alt="Profile Image" class="profile-img">
                            </div>
                            <div class="card-body border-0 pt-5 px-0">
                                <!-- Coffee title -->
                                <h4 class="name">{{ $coffee->title }}</h4>
                                <!-- Brew -->
                                <p class="fs-6 pt-0 pb-0 text-secondary">Brew</p>
                                <div class="row align-items-center pt-0">
                                    <!-- Form for adding to cart -->
                                    <form id="addToCartForm{{ $coffee->id }}"
                                        action='{{ route('coffee.addToCart', ['id' => $coffee->id, 'sizeIndex' => $coffee->sizes[0]['label'], 'brew' => 'option']) }}'>
                                        <div class="col pe-0 pb-3">
                                            <!-- Radio buttons for selecting options -->
                                            <input type="radio" class="btn-check btn-sm"
                                                name="{{ $coffee->title }}-options" id="{{ $coffee->title }}-Hot"
                                                autocomplete="off" value="Hot" required>
                                            <label class="btn" for="{{ $coffee->title }}-Hot">Hot</label>
                                            <input type="radio" class="btn-check btn-sm"
                                                name="{{ $coffee->title }}-options" id="{{ $coffee->title }}-Iced"
                                                autocomplete="off" value="Iced" required>
                                            <label class="btn" for="{{ $coffee->title }}-Iced">Iced</label>
                                        </div>
                                        <div class="row align-items-center pt-0 mx-4">
                                            <!-- Range input for selecting sizes -->
                                            <label for="customRange1" class="form-label pb-0">Sizes</label>
                                            <input type="range" class="form-range" id="customRange{{ $coffee->id }}"
                                                min="0" max="{{ count($coffee->sizes) - 1 }}" step="1"
                                                list="sizes{{ $coffee->id }}" value="0">
                                            <datalist id="sizes{{ $coffee->id }}">
                                                <!-- Options for the range input -->
                                                @foreach ($coffee->sizes as $index => $size)
                                                    <option value="{{ $index }}" label="{{ $size['label'] }}"
                                                        class="text-secondary"></option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                        <!-- Button for submitting form -->
                                        <div class="card-footer border-0">
                                            <div class="row align-items-center pt-0">
                                                <div class="col ps-0">
                                                    <!-- Price display -->
                                                    <h2><span>&#8369;</span><span
                                                            id="price{{ $coffee->id }}">{{ $coffee->sizes[0]['price'] }}</span>
                                                    </h2>
                                                </div>
                                                <div class="col ps-3 pe-2 ms-0">
                                                    <button type="submit" class="btn btn-circle btn-xl border-0"><i
                                                            class="fa-solid fa-plus" style="color: #ffffff;"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforeach
        </div>
        
    </div>
    @include('partials.footer')  

@section('scripts')
    @foreach ($coffees->chunk(1) as $coffeeChunk)
        @foreach ($coffeeChunk as $coffee)
            <script>
                $(document).ready(function() {
                    $('input[name="{{ $coffee->title }}-options"]').change(function() {
                        let selectedValue = $('input[name="{{ $coffee->title }}-options"]:checked').val();

                        if (selectedValue) {
                            console.log(
                                selectedValue); // Log the value of the selected radio button ("Hot" or "Iced")
                        } else {
                            console.log('No option selected'); // If no option is selected
                        }
                    });
                });
            </script>

            <script>
                $(document).ready(function() {
                    var sizes = JSON.parse('{!! addslashes(json_encode($coffee->sizes)) !!}'); // Parse sizes array from PHP to JavaScript

                    $('#customRange{{ $coffee->id }}').on('input', function() {
                        var selectedSize = $(this).val();
                        var price = sizes[0]['price']; // Default price for the first size

                        if (selectedSize >= 0 && selectedSize < sizes.length) {
                            price = sizes[selectedSize]['price']; // Get price based on selected size index
                        }
                        console.log($('#customRange{{ $coffee->id }}').val());

                        $('#price{{ $coffee->id }}').text(price);
                    });
                });
            </script>


            <script>
                $(document).ready(function() {
                    $('#addToCartForm{{ $coffee->id }}').on('submit', function(event) {
                        event.preventDefault();

                        var sizeRange = document.getElementById('customRange{{ $coffee->id }}');
                        var selectedSize = sizeRange.value;

                        var selectedOptionValue = $('input[name="{{ $coffee->title }}-options"]:checked').val();

                        if (selectedSize >= 0 && selectedSize < {{ count($coffee->sizes) }}) {
                            var newAction =
                                '{{ route('coffee.addToCart', ['id' => $coffee->id, 'sizeIndex' => ':sizeIndex', 'brew' => ':brew']) }}';
                            newAction = newAction.replace(':sizeIndex', selectedSize);
                            newAction = newAction.replace(':brew', selectedOptionValue);

                            $(this).attr('action', newAction);
                        }

                        this.submit();
                    });
                });

            </script>

            <script>
                $(document).ready(function () {
                    // Remove 'fixed-top' class onload
                    $('#navbar').removeClass('fixed-top');

                    $(window).scroll(function () {
                        // Add 'fixed-top' class on scroll
                        if ($(this).scrollTop() > 50) {
                            $('#navbar').addClass('fixed-top');
                        } else {
                            $('#navbar').removeClass('fixed-top');
                        }
                    });
                });
            </script>


            <script>
                $(document).ready(function() {

                    $(window).scroll(function() {
                        var navbar = $("#navbar");
                        if ($(window).scrollTop() > 50) {
                            navbar.removeClass("navbar-transparent").addClass("bg-light");
                        } else {
                            navbar.removeClass("bg-light").addClass("navbar-transparent");
                        }
                    });
                });
            </script>

            
        @endforeach
    @endforeach
@endsection

@endsection
