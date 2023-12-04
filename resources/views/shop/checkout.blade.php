@extends('layouts.master')
@section('title')
    Checkout
@endsection

@section('content')
    @if (Session::has('cart'))
        <div class="container">
         
            <div class="row justify-content-center align-items-center vh-100">
                <div class="col-md-8 mb-4">
                  <h3 >Customer Detail</h3>
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('coffee.checkout') }}" method="POST">
                                @csrf

                                <!-- Text input (required) -->
                                <div class="row mb-4">
                                    <div class="col">
                                        <div class="form-outline">
                                            <input type="text" id="form7Example1" class="form-control" name="fname" />
                                            <label class="form-label" for="form7Example1">First name</label>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-outline">
                                            <input type="text" id="form7Example2" class="form-control" name="lname"
                                                required />
                                            <label class="form-label" for="form7Example2">Last name</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Text input (required) -->
                                <div class="form-outline mb-4">
                                    <input type="text" id="form7Example4" class="form-control" name="address" required />
                                    <label class="form-label" for="form7Example4">Address</label>
                                </div>

                                <!-- Email input (optional) -->
                                <div class="form-outline mb-4">
                                    <input type="email" id="form7Example5" class="form-control" name="email" />
                                    <label class="form-label" for="form7Example5">Email</label>
                                </div>

                                <!-- Number input (required) -->
                                <div class="form-outline mb-4">
                                    <input type="number" id="form7Example6" class="form-control" name="phone" required />
                                    <label class="form-label" for="form7Example6">Phone</label>
                                </div>

                                <!-- Message input (optional) -->
                                <div class="form-outline mb-4">
                                    <textarea class="form-control" id="form7Example7" rows="4" name="moreInfo"></textarea>
                                    <label class="form-label" for="form7Example7">Additional information</label>
                                </div>

                                <!-- Checkbox -->
                                <!-- ... (other form fields) ... -->

                                <button type="submit" class="btn btn-primary btn-lg btn-block">
                                    Make purchase
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card mb-4">
                        <div class="card-header py-3">
                            <h5 class="mb-0">Summary</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 pb-0">
                                    Products
                                    <span>
                                        @foreach ($coffees as $coffee)
                                            <strong>
                                                {{ json_decode($coffee['item'], true)['sizes'][$coffee['size']]['label'] }}-</strong>
                                            {{ $coffee['brew'] }} {{ $coffee['item']['title'] }}<strong>
                                                <span>PHP{{ json_decode($coffee['item'], true)['sizes'][$coffee['size']]['price'] }}</span></strong><br>
                                        @endforeach
                                    </span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">

                                </li>
                                <li
                                    class="list-group-item d-flex justify-content-between align-items-center border-0 px-0 mb-3">
                                    <div>
                                        <strong>Total amount</strong>
                                    </div>
                                    <span><strong>PHP{{ $totalPrice }}</strong></span>
                                </li>
                            </ul>


                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('partials.footer')  
    @else
        <div class="row">
            <div class="col-sm-6 offset-sm-3 text-center">
                <h2>Cart is Empty</h2>
            </div>
        </div>
    @endif


@endsection
