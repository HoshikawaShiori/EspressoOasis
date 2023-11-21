@extends('layouts.master')
@section('title')
Shop
@endsection
@section('styles')
<link rel="stylesheet" href="{{ URL::to('src/css/shop-card.css') }}">
@endsection
@section('content')
    <div class="container">
        <h1 class=" display-4 text-center pt-5 pe-0 shop-head">Brewery</h1>
        <div class="row row-cols-1 row-cols-md-2 g-4">

            @foreach ($coffees->chunk(1) as $coffeeChunk)
                @foreach($coffeeChunk as $coffee)
                <div class="col-md-4">
                    <div class="card border-0">
                        <div class="card-header border-0">
                            <img src="{{$coffee->imagePath}}" alt="Profile Image" class="profile-img">
                        </div>
                        <div class="card-body border-0 pt-5 px-0" >
                            <h4 class="name">{{$coffee->title}}</h4>
                            <p class="fs-6 pt-0 pb-0 text-secondary">Brew</p>
                            <div class="row align-items-center pt-0">
                                <div class="col pe-0 pb-3">
                                    <input type="radio" class="btn-check btn-sm" name="{{$coffee->title}}-options" id="{{$coffee->title}}-Hot"
                                        autocomplete="off" value="Hot">
                                    <label class="btn" for="{{$coffee->title}}-Hot">Hot</label>
                                    <input type="radio" class="btn-check btn-sm" name="{{$coffee->title}}-options" id="{{$coffee->title}}-Cold"
                                        autocomplete="off" value="Cold">
                                    <label class="btn" for="{{$coffee->title}}-Cold">Cold</label>
                                </div>
                            </div>
                            <div class="row align-items-center pt-0 mx-4">
                                <label for="customRange1" class="form-label pb-0">Sizes</label>
                                <input type="range" class="form-range" id="customRange1" min="1" max="3"
                                    step="1" list="sizes" value="1">
    
                                <datalist id="sizes">
                                    <option value="1" label="S" class="text-secondary"></option>
                                    <option value="2" label="M" class="text-secondary"></option>
                                    <option value="3" label="L" class="text-secondary"></option>
                                </datalist>
                            </div>
                        </div>
                        <div class="card-footer border-0">
                            <div class="row align-items-center pt-0">
                                <div class="col ps-0">
                                    <h2><span>&#8369;</span>{{$coffee->price}}</h2>
                                </div>
                                <div class="col ps-3 pe-2 ms-0">
                                    <button type="submit" class="btn btn-circle btn-xl border-0" onclick="location.href='{{route('coffee.addToCart',['id' => $coffee->id])}}'"><i class="fa-solid fa-plus"
                                            style="color: #ffffff;"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
    
    
                </div>
                @endforeach
            @endforeach
            


            
        </div>
    @endsection
