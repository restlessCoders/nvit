@extends('layout.master')
@section('title', 'Superadmin | Dashboard')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box">
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">NVIT</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
            <h4 class="page-title">Dashboard</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-md-12 d-flex justify-content-end">

        <div class="text-center mb-3">
            <div class="btn-group" role="group" aria-label="Basic example">
                <button type="button" class="btn btn-sm btn-secondary">Today</button>
                <button type="button" class="btn btn-sm btn-secondary">This Month</button>
                <button type="button" class="btn btn-sm btn-secondary">This Year</button>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-6 d-none d-md-block">
        <img src="{{ asset('backend/images/dashboard_bg_image.jpg') }}" class="img-fluid">
    </div>


    <!-- Financial Summary Cards -->
    <div class="col-xl-3 col-md-6">
        <div class="card-box tilebox-one bg-primary text-white">
            <i class="fas fa-wallet float-right m-0 h2 text-white-50"></i>
            <h6 class="text-white text-uppercase mt-0">Today's Collection</h6>
            <h3 class="my-3 text-white">Tk <span data-plugin="counterup">{{ number_format($collections['today']) }}</span></h3>
        </div>

        <div class="card-box tilebox-one bg-success text-white">
            <i class="fas fa-calendar-week float-right m-0 h2 text-white-50"></i>
            <h6 class="text-white text-uppercase mt-0">Weekly Collection</h6>
            <h3 class="my-3 text-white">Tk <span data-plugin="counterup">{{ number_format($collections['week']) }}</span></h3>
        </div>

        <div class="card-box tilebox-one bg-info text-white">
            <i class="fas fa-calendar-alt float-right m-0 h2 text-white-50"></i>
            <h6 class="text-white text-uppercase mt-0">Monthly Collection</h6>
            <h3 class="my-3 text-white">Tk <span data-plugin="counterup">{{ number_format($collections['month']) }}</span></h3>
        </div>

        <div class="card-box tilebox-one bg-warning text-dark">
            <i class="fas fa-file-invoice-dollar float-right m-0 h2 text-dark-50"></i>
            <h6 class="text-dark text-uppercase mt-0">Outstanding Dues</h6>
            <h3 class="my-3 text-dark">Tk <span data-plugin="counterup">{{ number_format($totalDues) }}</span></h3>
        </div>
    </div>


</div>


<div class="row">
    <!-- Charts Section -->
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Collection Trends (Last 7 Days)</h4>
                <div class="mt-3 chartjs-chart">
                    <canvas id="collection-trend-chart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Payment Methods</h4>
                <div class="mt-3 chartjs-chart">
                    <canvas id="payment-method-chart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <!-- Today's Collections by Executive -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Today's Collections by Executive</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0">
                        <thead>
                            <tr>
                                <th>Executive</th>
                                <th class="text-right">Cash</th>
                                <th class="text-right">Bkash</th>
                                <th class="text-right">Card</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todaysCollectionsByExecutive as $executive)
                            <tr>
                                <td>{{ $executive->name }}</td>
                                <td class="text-right">Tk {{ number_format($executive->cash) }}</td>
                                <td class="text-right">Tk {{ number_format($executive->bkash) }}</td>
                                <td class="text-right">Tk {{ number_format($executive->card) }}</td>
                                <td class="text-right font-weight-bold">Tk {{ number_format($executive->total) }}</td>
                            </tr>
                            @endforeach
                            <tr class="font-weight-bold bg-light">
                                <td>Total</td>
                                <td class="text-right">Tk {{ number_format($todaysCollectionsByExecutive->sum('cash')) }}</td>
                                <td class="text-right">Tk {{ number_format($todaysCollectionsByExecutive->sum('bkash')) }}</td>
                                <td class="text-right">Tk {{ number_format($todaysCollectionsByExecutive->sum('card')) }}</td>
                                <td class="text-right">Tk {{ number_format($todaysCollectionsByExecutive->sum('total')) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Dues -->
    <div class="col-xl-6">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Top 10 Outstanding Dues</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Course</th>
                                <th class="text-right">Total Due</th>
                                <th class="text-right">Overdue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dues as $due)
                            <tr>
                                <td>{{ $due->student_name }}</td>
                                <td>{{ $due->courseName }}</td>
                                <td class="text-right">Tk {{ number_format($due->due_amount) }}</td>
                                <td class="text-right">
                                    @if($due->is_overdue)
                                    <span class="badge badge-danger">Yes</span>
                                    @else
                                    <span class="badge badge-success">No</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Payments -->
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title mb-3">Recent Payments</h4>
                <div class="table-responsive">
                    <table class="table table-hover table-centered mb-0">
                        <thead>
                            <tr>
                                <th>MR No</th>
                                <th>Date</th>
                                <th>Student</th>
                                <th>Course</th>
                                <th>Payment Mode</th>
                                <th class="text-right">Amount</th>
                                <th>Executive</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentPayments as $payment)
                            <tr>
                                <td>{{ $payment->mrNo }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->paymentDate)->format('d M Y') }}</td>
                                <td>{{ $payment->student->name }}</td>
                                <td></td>
                                <td>
                                    @if($payment->payment_mode == 1)
                                    <span class="badge badge-primary">Cash</span>
                                    @elseif($payment->payment_mode == 2)
                                    <span class="badge badge-info">Bkash</span>
                                    @else
                                    <span class="badge badge-success">Card</span>
                                    @endif
                                </td>
                                <td class="text-right">Tk {{ number_format($payment->cpaidAmount) }}</td>
                                <td>{{ $payment->executive->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Initialize charts
        initCollectionTrendChart(@json($collectionTrend));
        initPaymentMethodChart(@json($paymentMethods));
    });

    function initCollectionTrendChart(data) {
        const ctx = document.getElementById('collection-trend-chart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Collection Amount',
                    data: data.values,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 3,
                    tension: 0.2,
                    fill: true,
                    pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(54, 162, 235, 1)'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Tk ' + context.raw.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        },
                        ticks: {
                            callback: function(value) {
                                return 'Tk ' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    function initPaymentMethodChart(data) {
        const ctx = document.getElementById('payment-method-chart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.values,
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.8)',
                        'rgba(23, 162, 184, 0.8)',
                        'rgba(102, 16, 242, 0.8)'
                    ],
                    borderWidth: 1,
                    hoverOffset: 15
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: {
                                size: 13
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw;
                                const total = context.chart.getDatasetMeta(0).total;
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: Tk ${value.toLocaleString()} (${percentage}%)`;
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }
</script>
@endpush