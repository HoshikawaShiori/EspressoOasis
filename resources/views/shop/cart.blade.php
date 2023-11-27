@extends('layouts.master')
@section('title')
    Cart
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::to('src/css/shop-card.css') }}">
@endsection
@section('content')
<div class="container">
    @if (Session::has('cart'))
        <div class="row">
            <div class="col-sm-8 offset-sm-2">
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>Product Name</th>
                            <th>Unit Price</th>
                            <th>Quantity</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coffees as $coffee)
                            <tr>
                                <td><strong>{{ json_decode($coffee['item'], true)['sizes'][$coffee['size']]['label'] }}-</strong>{{$coffee['brew']}} {{ $coffee['item']['title'] }}</td>
                                <td>{{ json_decode($coffee['item'], true)['sizes'][$coffee['size']]['price'] }}</td>
                                <td>
                                    <div class="input-group ">
                                        <a href="{{ route('coffee.reduce', ['combinedKey' => "{$coffee['oID']}"]) }}" class="btn btn-secondary btn-sm" type="button">-</a>
                                        <input type="text" class="form-control  text-center"
                                            value="{{ $coffee['qty'] }}" readonly>
                                            <a href="{{ route('coffee.increase', ['combinedKey' => "{$coffee['oID']}"]) }}" class="btn btn-secondary btn-sm" type="button">+</a>

                                    </div>
                                </td>
                                <td>PHP {{ $coffee['price'] }}</td>
                                <td>
                                    <a href= "{{route('coffee.remove', ['combinedKey' => "{$coffee['oID']}"]) }}" class="btn btn-danger btn-sm">Remove</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="text-center">
                    <strong>Total: PHP {{ $totalPrice }}</strong>
                    <form action="{{ route('coffee.checkoutForm') }}" method="POST">
                        @csrf
                        <!-- Other form fields -->
                        <button type="submit" class="btn btn-primary">Checkout</button>
                    </form>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-sm-6 offset-sm-3 text-center">
                <h2>Cart is Empty</h2>
            </div>
        </div>
    @endif
</div>


@endsection
