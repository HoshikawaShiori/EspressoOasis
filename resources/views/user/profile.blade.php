@extends('layouts.master')
@section('title')
    Shop
@endsection

@section('content')
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-lg-10 col-xl-9">
                <div class="card" style="border-radius: 10px;">
                    <div class="card-header px-4 py-5">
                        <h5 class="text-muted mb-0">Thanks for your Order, <span style="color: #a8729a;">
                                @if (Auth::check())
                                    {{ Auth::user()->username }}
                                @else
                                    User
                                @endif
                            </span>!</h5>



                    </div>
                    <div class="card`body p-4">
                        @foreach ($orders as $order)
                            <div class="card shadow-0 border mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-">
                                        <p class="lead fw-normal mb-0">Receipt</p>
                                        <p class="small text-muted mb-0">Order ID: {{ $order['id'] }} </p>

                                    </div>
                                    @foreach ($order->cart->items as $item)
                                        <div class="row">
                                            <div class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                <img src="{{ asset($item['item']['imagePath']) }}" class="img-fluid"
                                                    style="height: 100px; width: 120px" alt="Phone">
                                            </div>
                                            <div
                                                class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                <p class="text-muted mb-0">{{ $item['item']['title'] }}</p>
                                            </div>
                                            <div
                                                class="col-md-2 text-center d-flex justify-content-center align-items-center">
                                                <p class="text-muted mb-0 small">
                                                    {{ json_decode($item['item'], true)['sizes'][$item['size']]['label'] }}
                                                </p>

                                            </div>
                                            <div
                                                class="col-md-3 text-center d-flex justify-content-center align-items-center">
                                                <p class="text-muted mb-0 small">{{ $item['brew'] }}</p>
                                            </div>
                                            <div
                                                class="col-md-1 text-center d-flex justify-content-center align-items-center">
                                                <p class="text-muted mb-0 small">
                                                    &#8369;{{ json_decode($item['item'], true)['sizes'][$item['size']]['price'] }}
                                                </p>
                                            </div>
                                            <div
                                                class="col-md-1 text-center d-flex justify-content-center align-items-center">
                                                <p class="text-muted mb-0 small">Qty: {{ $item['qty'] }}</p>
                                            </div>
                                            <div
                                                class="col-md-1 text-center d-flex justify-content-center align-items-center">
                                                <p class="text-muted mb-0 small">&#8369;{{ $item['price'] }}</p>
                                            </div>
                                        </div>
                                        <hr class="mb-4" opacity: 1;>
                                    @endforeach


                                    <div class="row d-flex align-items-center border-top">
                                        <div class="col">
                                            <p class="text-muted mt-1 mb-0 small ms-xl-5">Checkout ID:
                                                {{ $order['checkout_id'] }} </p>
                                        </div>
                                        <div class="col-md-auto">
                                        </div>
                                        <div class="col col-lg-3 text-align-right">
                                            <p><strong>Total Price &#8369; {{ $order->cart->totalPrice }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    @endsection
