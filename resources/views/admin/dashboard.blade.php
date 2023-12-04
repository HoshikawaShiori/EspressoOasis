@extends('layouts.layoutAdmin')
@section('title')
    Dashboard
@endsection
@section('styles')
    <link rel="stylesheet" href="{{ URL::to('src/css/yearpicker.css') }}">
@endsection
@section('content')
@include('partials.admintopNav')
    <main class="content vh-100">

        <div class="container-fluid p-0">

            <h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

            <div class="row">
                <div class="w-100">
                    <div class="row p-3">
                        <div class="col-md-4">
                            <div class="card text-white" style="background-color: slateblue">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Sales</h5>
                                        </div>

                                        <div class="col-auto">
                                            <div class="stat">
                                                <i class="fa-solid fa-peso-sign align-middle fa-2xl"></i>
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                        $sum = 0;
                                    @endphp

                                    @foreach ($orders as $order)
                                        @php
                                            $sum += $order->cart->totalPrice;
                                        @endphp
                                    @endforeach

                                    <h1 class="mt-1 mb-3">
                                        &#8369 {{ $sum }}
                                    </h1>
                                    <div class="mb-0">
                                        <span>Total Sales</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Completed Orders</h5>
                                        </div>

                                    </div>
                                    <h1 class="mt-1 mb-3">
                                        {{ $orders->where('orderStatus', 'Order Complete')->count() }}
                                    </h1>

                                    <div class="mb-0">
                                        <span>Total of completed orders</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="card bg-warning text-white px-2">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Ongoing Orders</h5>
                                        </div>

                                    </div>
                                    <h1 class="mt-1 mb-3">
                                        {{ $orders->where('orderStatus', 'Processing')->count() }}
                                    </h1>

                                    <div class="mb-0">
                                        <span>Total of ongoing orders</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 px-2">
                            <div class="card bg-warning text-white pt-1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Orders to be Served</h5>
                                        </div>

                                    </div>
                                    <h1 class="mt-1 mb-3">
                                        {{ $orders->where('orderStatus', 'Serving')->count() }}
                                    </h1>

                                    <div class="mb-0">
                                        <span>Total of Orders to be Served</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="card bg-danger text-white px-2 pb-1">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col mt-0">
                                            <h5 class="card-title">Cancelled Orders</h5>
                                        </div>

                                    </div>
                                    <h1 class="mt-1 mb-3">
                                        {{ $orders->where('orderStatus', 'Cancelled')->count() }}
                                    </h1>

                                    <div class="mb-0">
                                        <span>Total of Cancelled Orders</span>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>
                </div>
            </div>

            <div class="row p-2">

                <div class="col-12 col-md-12 col-xxl-6 d-flex order-3 order-xxl-1">
                    <div class="card flex-fill w-100">
                        <div class="card-header">
                            <div class="row d-flex">
                                <div class="col-auto">
                                    <h5 class="card-title mb-0">Monthly Sales</h5>
                                </div>
                                <div class="col col-auto">
                                    <input type="text" class="yearpicker" id="yearInput" placeholder="Select a year"
                                        value="2023">
                                    <div class="text-danger" id="outputContainer"></div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-4">
                            <div id="salesChart">
                                <canvas id="lineChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-12 col-xxl-6 d-flex order-3 order-xxl-1">
                    <div class="card flex-fill w-100">
                        <div class="card-header">

                            <h5 class="card-title mb-0">Number of Sales Per Product</h5>
                        </div>
                        <div class="card-body px-4">
                            <div id="sProduct" style="height:350px;">
                                <div id="productBar" style="height:350px;">
                                    <canvas id="barProduct"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"
        integrity="sha512-SIMGYRUjwY8+gKg7nn9EItdD8LCADSDfJNutF9TPrvEo86sQmFMh6MyralfIyhADlajSxqc7G0gs7+MwWF/ogQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src='{{ URL::to('src/js/yearpicker.js') }}'></script>
    <script>
        $(document).ready(function() {
            $('#yearInput').yearpicker({
                year: 2023,
                startYear: 2000,
                onChange: function(year) {
                    $('#yearInput').val(year);
                },
            });
        });
    </script>
    <script>
        let lineChart = document.getElementById('lineChart').getContext('2d')
        @php
            $monthlySales = [];

            foreach ($orders as $order) {
                $year = \Carbon\Carbon::parse($order->created_at)->format('Y');
                $month = \Carbon\Carbon::parse($order->created_at)->format('F');

                // If the year is not initialized in $monthlySales, create a new array for that year
                if (!isset($monthlySales[$year])) {
                    $monthlySales[$year] = [];
                }

                // If the month is not initialized in the year's data, set the sales for that month to 0
                if (!isset($monthlySales[$year][$month])) {
                    $monthlySales[$year][$month] = 0;
                }

                // Accumulate the sales for the specific month and year
                $monthlySales[$year][$month] += $order->cart->totalPrice;
            }
        @endphp

        $(document).ready(function() {

            const lineChart = document.getElementById('lineChart').getContext('2d');
            selectedYear=  $('#yearInput').val();
            var chart;

            function updateChartForYear(selectedYear) {
                console.log(selectedYear);
                var salesData = <?php echo json_encode($monthlySales); ?>;
                $("#outputContainer").text("");

                try {
                    var salesDataForYear = salesData[selectedYear];
                    var months = Object.keys(salesDataForYear);
                    var salesValues = Object.values(salesDataForYear);
                    chart = new Chart(lineChart, {
                        type: 'line',
                        data: {
                            labels: months,
                            datasets: [{
                                label: 'Sales',
                                data: salesValues,
                            }]
                        },
                    });
                    
                } catch (err) {
                    console.log('Year does not exist or data is unavailable.');
                    $("#outputContainer").text("No data for that year");
                }
            }
            updateChartForYear(selectedYear);
            $("#outputContainer").text("");
            $('#yearInput').on('change', function() {
                var selectedYear = $(this).val();
                chart.destroy();
                updateChartForYear(selectedYear);
            });
        });


        let barChart = document.getElementById('barProduct').getContext('2d')
        const bar = new Chart(barChart, {
            type: 'bar',
            data: {
                labels: [
                    @php
                        $uniqueTitles = [];
                    @endphp

                    @foreach ($orders as $order)
                        @foreach ($order->cart->items as $item)
                            @php
                                $title = $item['item']['title'];
                                if (!in_array($title, $uniqueTitles)) {
                                    $uniqueTitles[] = $title;
                                }
                            @endphp
                        @endforeach
                    @endforeach

                    @foreach ($uniqueTitles as $title)
                        '{{ $title }}',
                    @endforeach
                ],

                datasets: [{
                    label: 'Product',
                    data: [
                        @foreach ($uniqueTitles as $title)
                            @php
                                $count = 0;
                            @endphp

                            @foreach ($orders as $order)
                                @foreach ($order->cart->items as $item)
                                    @if ($item['item']['title'] === $title)
                                        @php
                                            $count++;
                                        @endphp
                                    @endif
                                @endforeach
                            @endforeach

                            {{ $count }},
                        @endforeach
                    ]
                }]
            },
        });
    </script>
@endsection
