@extends('layouts.layoutAdmin')
@section('title')
    Orders
@endsection
@section('content')
@include('partials.admintopNav')
    <main class="content">
        <div class="container round bg-white p-2 p-0">
            @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @elseif (session('errors'))
                <div class="alert alert-danger">
                    {{ session('errors') }}
                </div>
        @endif
            <div class="card`body p-4">
                @foreach ($orders as $order)
                    <div class="card shadow border mb-4">
                        <div class="card-body">
                            <div class="row d-flex align-items-center border-bottom">
                                <div class="col">
                                    <div class="row p-3">
                                        <p><strong>Order ID: {{ $order['id'] }} </strong></p>
                                        <p class="medium mb-0"><strong>Date: {{ \Carbon\Carbon::parse($order['created_at'])->format('Y-m-d H:i') }}</strong> </p>
                                        <p class="small mb-0">Name: {{ $order['name'] }} </p>
                                        <p class="small mb-0">Email: {{ $order['email'] }}</p>
                                        <p class="small mb-0">Phone: {{ $order['phone'] }}</p>
                                        <p class="small mb-0">Address: {{ $order['address'] }}</p>
                                    </div>
                                </div>
                                <div class="col-md-auto">
                                </div>
                                <div class="col col-lg-2 text-align-right">
                                    @if($order['orderStatus'] != 'Cancelled')
                                    <button class="btn btn-danger mb-2"  data-bs-toggle="modal" data-bs-target="#modal{{ $order['id'] }}" >Cancel Order</button>
                                    @endif
                                    <div class="col col-lg-2 text-align-right">
                                        @if($order['orderStatus'] == 'Order Complete')
                                            <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">{{ $order['orderStatus'] }} </button>
                                        @elseif($order['orderStatus'] == 'Processing' || $order['orderStatus'] == 'Serving')
                                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">{{ $order['orderStatus'] }}</button>
                                        @else
                                            <button type="button" class="btn btn-danger" data-bs-toggle="dropdown" aria-expanded="false" disabled>{{ $order['orderStatus'] }}</button>
                                        @endif
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('order.update', ['id' => $order->id, 'status' => 'Order Complete']) }}">Order Complete</a></li>
                                            <li><a class="dropdown-item" href="{{ route('order.update', ['id' => $order->id, 'status' => 'Processing']) }}">Processing</a></li>
                                            <li><a class="dropdown-item" href="{{ route('order.update', ['id' => $order->id, 'status' => 'Serving']) }}" >Serving</a></li>
                                          </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-">
                                <p class="lead fw-normal mb-0">Receipt</p>
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

                                <div class="modal fade" id="modal{{ $order['id'] }}" tabindex="-1">
                                    <div class="modal-dialog">
                                      <div class="modal-content">
                                        <div class="modal-header">
                                          <h5 class="modal-title">Cancel Order</h5>
                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                          <p>Are you sure you want to cancel order. With order no. {{ $order['id'] }}?</p>
                                          <p class="text-danger">This order will be fully refunded</p>
                                        </div>
                                        <div class="modal-footer">
                                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                          <a type="button" class="btn btn-danger" href="{{ route('order.cancel', ['id' => $order->id,'checkout_id'=> $order->checkout_id]) }}">Cancel Order</a>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
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
                            <div class="row d-flex align-items-center border-top">
                                <div class="col col-fluid">
                                    <div class="row p-3">
                                        <p><strong>Notes: </strong></p>
                                        <p class="small mb-0">{{ $order['moreInfo'] }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                @endforeach

            </div>
        </div>
    </main>
@endsection
