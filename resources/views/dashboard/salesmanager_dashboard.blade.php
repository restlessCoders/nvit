@extends('layout.master')
@section('title', 'Sales Manager | Dashboard')
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
    <div class="col-md-6 col-xl-3">
        <div class="card-box tilebox-one">
            <i class="icon-layers float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Orders</h6>
            <h3 class="my-3" data-plugin="counterup">1,587</h3>
            <span class="badge badge-success mr-1"> +11% </span> <span class="text-muted">From previous period</span>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card-box tilebox-one">
            <i class="icon-paypal float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Revenue</h6>
            <h3 class="my-3">$<span data-plugin="counterup">46,782</span></h3>
            <span class="badge badge-danger mr-1"> -29% </span> <span class="text-muted">From previous period</span>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card-box tilebox-one">
            <i class="icon-chart float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Average Price</h6>
            <h3 class="my-3">$<span data-plugin="counterup">15.9</span></h3>
            <span class="badge badge-pink mr-1"> 0% </span> <span class="text-muted">From previous period</span>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card-box tilebox-one">
            <i class="icon-rocket float-right m-0 h2 text-muted"></i>
            <h6 class="text-muted text-uppercase mt-0">Product Sold</h6>
            <h3 class="my-3" data-plugin="counterup">1,890</h3>
            <span class="badge badge-warning mr-1"> +89% </span> <span class="text-muted">Last year</span>
        </div>
    </div>
</div>
<!-- end row -->


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

            <div id="morris-bar-stacked" class="morris-chart" style="height: 320px; position: relative; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);"><svg height="320" version="1.1" width="731" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative; left: -0.25px; top: -0.59375px;">
                    <desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.3.0</desc>
                    <defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs><text x="33.84765625" y="281" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">0</tspan>
                    </text>
                    <path fill="none" stroke="#6c7897" d="M46.34765625,281H706" stroke-opacity="0.1" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="33.84765625" y="217" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">100</tspan>
                    </text>
                    <path fill="none" stroke="#6c7897" d="M46.34765625,217H706" stroke-opacity="0.1" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="33.84765625" y="153" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">200</tspan>
                    </text>
                    <path fill="none" stroke="#6c7897" d="M46.34765625,153H706" stroke-opacity="0.1" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="33.84765625" y="89" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">300</tspan>
                    </text>
                    <path fill="none" stroke="#6c7897" d="M46.34765625,89H706" stroke-opacity="0.1" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="33.84765625" y="25" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">400</tspan>
                    </text>
                    <path fill="none" stroke="#6c7897" d="M46.34765625,25H706" stroke-opacity="0.1" stroke-width="0.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="676.0158025568181" y="293.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,7)">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2015</tspan>
                    </text><text x="556.079012784091" y="293.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,7)">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2013</tspan>
                    </text><text x="436.1422230113636" y="293.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,7)">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2011</tspan>
                    </text><text x="316.2054332386364" y="293.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,7)">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2009</tspan>
                    </text><text x="196.2686434659091" y="293.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,7)">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2007</tspan>
                    </text><text x="76.33185369318181" y="293.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" font-weight="normal" transform="matrix(1,0,0,1,0,7)">
                        <tspan dy="4" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">2005</tspan>
                    </text>
                    <rect x="64.33817471590909" y="252.2" width="23.987357954545455" height="28.80000000000001" rx="0" ry="0" fill="#3db9dc" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="64.33817471590909" y="137" width="23.987357954545455" height="115.19999999999999" rx="0" ry="0" fill="#1bb99a" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="64.33817471590909" y="73" width="23.987357954545455" height="64" rx="0" ry="0" fill="#ebeff2" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="124.30656960227272" y="233" width="23.987357954545455" height="48" rx="0" ry="0" fill="#3db9dc" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="124.30656960227272" y="191.4" width="23.987357954545455" height="41.599999999999994" rx="0" ry="0" fill="#1bb99a" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="124.30656960227272" y="140.20000000000002" width="23.987357954545455" height="51.19999999999999" rx="0" ry="0" fill="#ebeff2" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="184.27496448863633" y="217" width="23.987357954545455" height="64" rx="0" ry="0" fill="#3db9dc" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="184.27496448863633" y="159.4" width="23.987357954545455" height="57.599999999999994" rx="0" ry="0" fill="#1bb99a" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="184.27496448863633" y="123.56" width="23.987357954545455" height="35.84" rx="0" ry="0" fill="#ebeff2" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="244.243359375" y="233" width="23.987357954545455" height="48" rx="0" ry="0" fill="#3db9dc" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="244.243359375" y="191.4" width="23.987357954545455" height="41.599999999999994" rx="0" ry="0" fill="#1bb99a" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="244.243359375" y="134.44" width="23.987357954545455" height="56.96000000000001" rx="0" ry="0" fill="#ebeff2" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="304.2117542613636" y="217" width="23.987357954545455" height="64" rx="0" ry="0" fill="#3db9dc" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="304.2117542613636" y="159.4" width="23.987357954545455" height="57.599999999999994" rx="0" ry="0" fill="#1bb99a" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="304.2117542613636" y="82.6" width="23.987357954545455" height="76.80000000000001" rx="0" ry="0" fill="#ebeff2" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="364.18014914772726" y="233" width="23.987357954545455" height="48" rx="0" ry="0" fill="#3db9dc" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="364.18014914772726" y="191.4" width="23.987357954545455" height="41.599999999999994" rx="0" ry="0" fill="#1bb99a" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="364.18014914772726" y="121" width="23.987357954545455" height="70.4" rx="0" ry="0" fill="#ebeff2" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="424.1485440340909" y="249" width="23.987357954545455" height="32" rx="0" ry="0" fill="#3db9dc" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="424.1485440340909" y="223.4" width="23.987357954545455" height="25.599999999999994" rx="0" ry="0" fill="#1bb99a" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="424.1485440340909" y="169" width="23.987357954545455" height="54.400000000000006" rx="0" ry="0" fill="#ebeff2" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="484.1169389204545" y="233" width="23.987357954545455" height="48" rx="0" ry="0" fill="#3db9dc" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="484.1169389204545" y="191.4" width="23.987357954545455" height="41.599999999999994" rx="0" ry="0" fill="#1bb99a" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="484.1169389204545" y="158.12" width="23.987357954545455" height="33.28" rx="0" ry="0" fill="#ebeff2" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="544.0853338068181" y="249" width="23.987357954545455" height="32" rx="0" ry="0" fill="#3db9dc" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="544.0853338068181" y="223.4" width="23.987357954545455" height="25.599999999999994" rx="0" ry="0" fill="#1bb99a" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="544.0853338068181" y="174.12" width="23.987357954545455" height="49.28" rx="0" ry="0" fill="#ebeff2" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="604.0537286931818" y="233" width="23.987357954545455" height="48" rx="0" ry="0" fill="#3db9dc" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="604.0537286931818" y="191.4" width="23.987357954545455" height="41.599999999999994" rx="0" ry="0" fill="#1bb99a" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="604.0537286931818" y="133.8" width="23.987357954545455" height="57.599999999999994" rx="0" ry="0" fill="#ebeff2" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="664.0221235795455" y="217" width="23.987357954545455" height="64" rx="0" ry="0" fill="#3db9dc" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="664.0221235795455" y="159.4" width="23.987357954545455" height="57.599999999999994" rx="0" ry="0" fill="#1bb99a" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                    <rect x="664.0221235795455" y="76.20000000000002" width="23.987357954545455" height="83.19999999999999" rx="0" ry="0" fill="#ebeff2" stroke="none" fill-opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); fill-opacity: 1;"></rect>
                </svg>
                <div class="morris-hover morris-default-style" style="left: 28.7772px; top: 107px; display: none;">
                    <div class="morris-hover-row-label">2005</div>
                    <div class="morris-hover-point" style="color: #3db9dc">
                        Series A:
                        45
                    </div>
                    <div class="morris-hover-point" style="color: #1bb99a">
                        Series B:
                        180
                    </div>
                    <div class="morris-hover-point" style="color: #ebeff2">
                        Series C:
                        100
                    </div>
                </div>
            </div>

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

            <div id="morris-donut-example" class="morris-chart" style="height: 268px;"><svg height="268" version="1.1" width="333.484" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative; left: -0.25px; top: -0.203125px;">
                    <desc style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Created with Raphaël 2.3.0</desc>
                    <defs style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></defs>
                    <path fill="none" stroke="#3db9dc" d="M166.742,216.66666666666669A82.66666666666667,82.66666666666667,0,0,0,244.96442484829822,160.74004541189322" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path>
                    <path fill="#3db9dc" stroke="#ffffff" d="M166.742,219.66666666666669A85.66666666666667,85.66666666666667,0,0,0,247.80314187908326,161.71045028571194L279.344442221139,172.49272666147533A119,119,0,0,1,166.742,253Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path>
                    <path fill="none" stroke="#1bb99a" d="M244.96442484829822,160.74004541189322A82.66666666666667,82.66666666666667,0,0,0,92.60244423163182,97.43356664580473" stroke-width="2" opacity="1" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 1;"></path>
                    <path fill="#1bb99a" stroke="#ffffff" d="M247.80314187908326,161.71045028571194A85.66666666666667,85.66666666666667,0,0,0,89.91189583681201,96.10655898375732L55.53266634744773,79.1503499687071A124,124,0,0,1,284.07563727244735,174.11006811783983Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path>
                    <path fill="none" stroke="#ebeff2" d="M92.60244423163182,97.43356664580473A82.66666666666667,82.66666666666667,0,0,0,166.71602950115752,216.66666258723023" stroke-width="2" opacity="0" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); opacity: 0;"></path>
                    <path fill="#ebeff2" stroke="#ffffff" d="M89.91189583681201,96.10655898375732A85.66666666666667,85.66666666666667,0,0,0,166.71508702337695,219.66666243918615L166.70461504803725,252.99999412758544A119,119,0,0,1,60.016913672147425,81.36202940545279Z" stroke-width="3" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);"></path><text x="166.742" y="124" text-anchor="middle" font-family="&quot;Arial&quot;" font-size="15px" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: Arial; font-size: 15px; font-weight: 800;" font-weight="800" transform="matrix(1.1172,0,0,1.1172,-19.5661,-15.5883)" stroke-width="0.8950904107862903">
                        <tspan dy="5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">Italian Language 02</tspan>
                    </text><text x="166.742" y="144" text-anchor="middle" font-family="&quot;Arial&quot;" font-size="14px" stroke="none" fill="#000000" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0); text-anchor: middle; font-family: Arial; font-size: 14px;" transform="matrix(1.6209,0,0,1.6209,-103.5326,-84.134)" stroke-width="0.6169354838709677">
                        <tspan dy="4.5" style="-webkit-tap-highlight-color: rgba(0, 0, 0, 0);">30</tspan>
                    </text>
                </svg></div>

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


<div class="row">
    <div class="col-xl-7">
        <div class="row">
            <div class="col-md-6">
                <div class="card-box">
                    <h4 class="header-title mb-3">Inbox</h4>

                    <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 404.297px;">
                        <div class="inbox-widget slimscroll" style="max-height: 324px; overflow: hidden; width: auto; height: 404.297px;">
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-1.jpg" class="rounded-circle" alt=""></div>
                                    <p class="inbox-item-author">Chadengle</p>
                                    <p class="inbox-item-text">Hey! there I'm available...</p>
                                    <p class="inbox-item-date">13:40 PM</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-2.jpg" class="rounded-circle" alt=""></div>
                                    <p class="inbox-item-author">Tomaslau</p>
                                    <p class="inbox-item-text text-truncate">I've finished it! See you so...</p>
                                    <p class="inbox-item-date">13:34 PM</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-3.jpg" class="rounded-circle" alt=""></div>
                                    <p class="inbox-item-author">Stillnotdavid</p>
                                    <p class="inbox-item-text">This theme is awesome!</p>
                                    <p class="inbox-item-date">13:17 PM</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-4.jpg" class="rounded-circle" alt=""></div>
                                    <p class="inbox-item-author">Kurafire</p>
                                    <p class="inbox-item-text">Nice to meet you</p>
                                    <p class="inbox-item-date">12:20 PM</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-5.jpg" class="rounded-circle" alt=""></div>
                                    <p class="inbox-item-author">Shahedk</p>
                                    <p class="inbox-item-text">Hey! there I'm available...</p>
                                    <p class="inbox-item-date">10:15 AM</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-6.jpg" class="rounded-circle" alt=""></div>
                                    <p class="inbox-item-author">Adhamdannaway</p>
                                    <p class="inbox-item-text">This theme is awesome!</p>
                                    <p class="inbox-item-date">9:56 AM</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-8.jpg" class="rounded-circle" alt=""></div>
                                    <p class="inbox-item-author">Arashasghari</p>
                                    <p class="inbox-item-text">Hey! there I'm available...</p>
                                    <p class="inbox-item-date">10:15 AM</p>
                                </div>
                            </a>
                            <a href="#">
                                <div class="inbox-item">
                                    <div class="inbox-item-img"><img src="assets/images/users/avatar-9.jpg" class="rounded-circle" alt=""></div>
                                    <p class="inbox-item-author">Joshaustin</p>
                                    <p class="inbox-item-text">I've finished it! See you so...</p>
                                    <p class="inbox-item-date">9:56 AM</p>
                                </div>
                            </a>
                        </div>
                        <div class="slimScrollBar" style="background: rgb(158, 165, 171); width: 5px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px; height: 195.123px;"></div>
                        <div class="slimScrollRail" style="width: 5px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div>
                    </div>

                </div>
            </div>

            <div class="col-md-6">
                <div class="card-box">
                    <h4 class="header-title mb-3">Sales Statistics</h4>

                    <p class="font-weight-semibold mb-3">iMacs <span class="text-danger float-right"><b>78%</b></span></p>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 78%" aria-valuenow="78" aria-valuemin="0" aria-valuemax="78"></div>
                    </div>
                </div>

                <div class="card-box">
                    <h4 class="header-title mb-3">Monthly Sales</h4>

                    <p class="font-weight-semibold mb-2">Macbooks <span class="text-success float-right"><b>25%</b></span></p>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

                <div class="card-box">
                    <h4 class="header-title mb-3">Daily Sales</h4>

                    <p class="font-weight-semibold mb-2">Mobiles <span class="text-warning float-right"><b>75%</b></span></p>
                    <div class="progress" style="height: 10px;">
                        <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>

            </div>

        </div>
    </div><!-- end col-->

    <div class="col-xl-5">
        <div class="card-box">

            <h4 class="header-title mb-3">Top Contracts</h4>

            <div class="table-responsive">
                <table class="table table-bordered table-nowrap mb-0">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th class="text-muted">Apple Technology</th>
                            <td>20/02/2014</td>
                            <td>19/02/2020</td>
                            <td><span class="badge badge-success">Paid</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Envato Pty Ltd.</th>
                            <td>20/02/2014</td>
                            <td>19/02/2020</td>
                            <td><span class="badge badge-danger">Unpaid</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Dribbble LLC.</th>
                            <td>20/02/2014</td>
                            <td>19/02/2020</td>
                            <td><span class="badge badge-success">Paid</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Adobe Family</th>
                            <td>20/02/2014</td>
                            <td>19/02/2020</td>
                            <td><span class="badge badge-success">Paid</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Apple Technology</th>
                            <td>20/02/2014</td>
                            <td>19/02/2020</td>
                            <td><span class="badge badge-danger">Unpaid</span></td>
                        </tr>
                        <tr>
                            <th class="text-muted">Envato Pty Ltd.</th>
                            <td>20/02/2014</td>
                            <td>19/02/2020</td>
                            <td><span class="badge badge-success">Paid</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div><!-- end col-->
</div>
<!-- end row -->
@endsection


