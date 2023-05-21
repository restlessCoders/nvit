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
    <div class="col-xl-8">
        <div class="row">
            <div class="col-md-6 d-none d-md-block">
                <img src="../backend/images/dashboard_bg_image.jpg" class="img-fluid">
            </div>
            <div class="col-md-3">
                <div class="card-box tilebox-one">
                    <i class="icon-layers float-right m-0 h2 text-muted"></i>
                    <h6 class="text-muted text-uppercase mt-0">Clients</h6>
                    <h3 class="my-3" data-plugin="counterup">1,587</h3>
                </div>
                <div class="card-box tilebox-one">
                    <i class="icon-paypal float-right m-0 h2 text-muted"></i>
                    <h6 class="text-muted text-uppercase mt-0">Enrollment</h6>
                    <h3 class="my-3">$<span data-plugin="counterup">46,782</span></h3>
                </div>
                <div class="card-box tilebox-one">
                    <i class="icon-rocket float-right m-0 h2 text-muted"></i>
                    <h6 class="text-muted text-uppercase mt-0">Total Batch</h6>
                    <h3 class="my-3" data-plugin="counterup">1,890</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-box tilebox-one">
                    <i class="icon-layers float-right m-0 h2 text-muted"></i>
                    <h6 class="text-muted text-uppercase mt-0">Revenue</h6>
                    <h3 class="my-3">$<span data-plugin="counterup">46,782</span></h3>
                </div>
                <div class="card-box tilebox-one">
                    <i class="icon-paypal float-right m-0 h2 text-muted"></i>
                    <h6 class="text-muted text-uppercase mt-0">Knocking</h6>
                    <h3 class="my-3">$<span data-plugin="counterup">46,782</span></h3>
                </div>
                <div class="card-box tilebox-one">
                    <i class="icon-rocket float-right m-0 h2 text-muted"></i>
                    <h6 class="text-muted text-uppercase mt-0">New Batch</h6>
                    <h3 class="my-3" data-plugin="counterup">1,890</h3>
                </div>
            </div>
        </div>
    </div><!-- end col-->
    <div class="col-xl-2">
        <div class="card-box tilebox-one">
            <i class="icon-rocket float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">IDB</h6>
            <h3 class="my-3" data-plugin="counterup">1,890</h3>
        </div>
        <div class="card-box tilebox-one">
            <i class="icon-rocket float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Evaluation</h6>
            <h3 class="my-3" data-plugin="counterup">1,890</h3>
        </div>
        <div class="card-box tilebox-one">
            <i class="icon-rocket float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">B.Complete</h6>
            <h3 class="my-3" data-plugin="counterup">1,890</h3>
        </div>
    </div><!-- end col-->
    <div class="col-xl-2">
        <div class="card-box tilebox-one">
            <i class="icon-rocket float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Collection</h6>
            <h3 class="my-3" data-plugin="counterup">1,890</h3>
        </div>
        <div class="card-box tilebox-one">
            <i class="icon-rocket float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Uncomplete</h6>
            <h3 class="my-3" data-plugin="counterup">1,890</h3>
        </div>
        <div class="card-box tilebox-one">
            <i class="icon-rocket float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Certificate</h6>
            <h3 class="my-3" data-plugin="counterup">1,890</h3>
        </div>
    </div><!-- end col-->
</div>

<!-- end row -->

<canvas id="admissions-chart"></canvas>
<div class="row">
    <div class="col-lg-6 col-xl-8">
        <div class="card-box">
            <h4 class="header-title mb-3">Sales Statistics</h4>

            <div class="text-center">
                <ul class="list-inline chart-detail-list mb-0">
                    <li class="list-inline-item">
                        <h6 class="text-info"><i class="mdi mdi-circle-outline mr-1"></i>Series A</h6>
                    </li>
                    <li class="list-inline-item">
                        <h6 class="text-success"><i class="mdi mdi-triangle-outline mr-1"></i>Series B</h6>
                    </li>
                    <li class="list-inline-item">
                        <h6 class="text-muted"><i class="mdi mdi-square-outline mr-1"></i>Series C</h6>
                    </li>
                </ul>
            </div>

            <div id="morris-bar-stacked" class="morris-chart" style="height: 320px;"></div>

        </div>
    </div><!-- end col-->

    <div class="col-lg-6 col-xl-4">
        <div class="card-box">
            <h4 class="header-title mb-3">Trends Monthly</h4>

            <div class="text-center mb-3">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-sm btn-secondary">Today</button>
                    <button type="button" class="btn btn-sm btn-secondary">This Week</button>
                    <button type="button" class="btn btn-sm btn-secondary">Last Week</button>
                </div>
            </div>

            <div id="morris-donut-example" class="morris-chart" style="height: 268px;"></div>

            <div class="text-center">
                <ul class="list-inline chart-detail-list mb-0 mt-2">
                    <li class="list-inline-item">
                        <h6 class="text-info"><i class="mdi mdi-circle-outline mr-1"></i>English</h6>
                    </li>
                    <li class="list-inline-item">
                        <h6 class="text-success"><i class="mdi mdi-triangle-outline mr-1"></i>Italian</h6>
                    </li>
                    <li class="list-inline-item">
                        <h6 class="text-muted"><i class="mdi mdi-square-outline mr-1"></i>French</h6>
                    </li>
                </ul>
            </div>

        </div>
    </div><!-- end col-->
</div>
<!-- end row -->


@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
       
    </script>
@endpush