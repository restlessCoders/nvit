@extends('layout.admin.admin_master')
@section('title', 'Sales Man | Dashboard')
@section('content')
<?php
	$montharr=array('Jan','Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul','Aug','Sept','Oct','nov','Dec');
	/* customer data init */
	$cust_count="";
	$cust_month="";
	$tcust_count=0;
	if($customer){
		foreach($customer as $cust){
			$tcust_count+=$cust->ccount;
			$cust_count.=$cust->ccount.', ';
			$cust_month.='"'.$montharr[$cust->cmonth].'", ';
		}
		$cust_count=rtrim($cust_count,', ');
		$cust_month=rtrim($cust_month,', ');
	}
	
	/* supplier data init */
	$sup_count="";
	$sup_month="";
	$tsup_count=0;
	if($suppliers){
		foreach($suppliers as $sup){
			$tsup_count+=$sup->ccount;
			$sup_count.=$sup->ccount.', ';
			$sup_month.='"'.$montharr[$sup->cmonth].'", ';
		}
		$sup_count=rtrim($sup_count,', ');
		$sup_month=rtrim($sup_month,', ');
	}

	$Revenue="";
	$tRevenue=0;
	$ttax=0;
	$tdis=0;
	$month="";
	$rcount=0;
	if($rev_date){
		foreach($rev_date as $rd){
			$tRevenue+=$rd->tm;
			$ttax+=$rd->tax;
			$tdis+=$rd->dis;
			$rcount++;
		}
		foreach($rev_date as $rd){
			$Revenue.=round(($rd->tm*100)/$tRevenue).', ';
			$month.='"'.$montharr[$rd->bd].' '.$company->currency_symble.$rd->tm.'", ';
		}
		$Revenue=rtrim($Revenue,', ');
		$month=rtrim($month,', ');
	}
?>

<div class="content flex-column-fluid" id="kt_content">
	<!--begin::Dashboard-->
	
  <!--Begin::Row-->
  <div class="row">
    <div class="col-xl-3 py-3">
        <a href="{{route(currentUser().'.addNewBillForm')}}" class="btn btn-primary font-weight-bolder d-block">
            <span class="svg-icon svg-icon-2x svg-icon-success">
                <i class="icon-2x flaticon2-add-square"></i>
            </span>
            New Bill
        </a>
    </div>
    <div class="col-xl-3 py-3">
        <a href="{{route(currentUser().'.addNewPurchaseForm')}}" class="btn btn-primary font-weight-bolder d-block">
            <span class="svg-icon svg-icon-2x svg-icon-success">
                <i class="icon-2x flaticon2-add-square"></i>
            </span>
            New Purchese
        </a>
    </div>
    <div class="col-xl-3 py-3">
        <a href="{{route(currentUser().'.addNewAppointmentForm')}}"
            class="btn btn-primary font-weight-bolder d-block">
            <span class="svg-icon svg-icon-2x svg-icon-success">
                <i class="icon-2x flaticon-calendar-with-a-clock-time-tools"></i>
            </span>
            Book Appointment
        </a>
    </div>
    <div class="col-xl-3 py-3">
        <a href="{{route(currentUser().'.addNewCustomerForm')}}" class="btn btn-primary font-weight-bolder d-block">
            <span class="svg-icon svg-icon-2x svg-icon-success">
                <i class="icon-2x flaticon-user-add"></i>
            </span>
            New Customer
        </a>
    </div>
</div>
<!--End::Row-->


<!--Begin::Row-->
<div class="row">
    <div class="col-xl-3 py-3">

        <a href="{{route(currentUser().'.allBill')}}" class="btn btn-primary font-weight-bolder d-block">
            <span class="svg-icon svg-icon-2x svg-icon-success">
                <i class="icon-2x flaticon2-list"></i>
            </span>
            Bill List
        </a>
    </div>
    <div class="col-xl-3 py-3">

        <a href="{{route(currentUser().'.allPurchase')}}" class="btn btn-primary font-weight-bolder d-block">
            <span class="svg-icon svg-icon-2x svg-icon-success">
                <i class="icon-2x flaticon2-list"></i>
            </span>
            Purchese List
        </a>
    </div>
    <div class="col-xl-3 py-3">

        <a href="{{route(currentUser().'.allAppointment')}}" class="btn btn-primary font-weight-bolder d-block">
            <span class="svg-icon svg-icon-2x">
                <i class="icon-2x flaticon-list-3"></i>
            </span>
            Appointment List
        </a>
    </div>
    <div class="col-xl-3 py-3">

        <a href="{{route(currentUser().'.addNewSupplierForm')}}" class="btn btn-primary font-weight-bolder d-block">
            <span class="svg-icon svg-icon-2x svg-icon-success">
                <i class="icon-2x flaticon-user-add"></i>
            </span>
            New Supplier
        </a>
    </div>
</div>
<!--End::Row-->


<!--begin::Row-->
<div class="row mt-5">
    <div class="col-xl-4">
        <div class="card card-custom bgi-no-repeat bgi-size-cover gutter-b h-100"
        style="background-image: url(assets/media/stock-600x400/img-28.jpg)">
        <!--begin::Body-->
        <div class="card-body">
            <a href="#" class="text-dark-75 text-hover-primary font-weight-bolder font-size-h3">Today
                Sales</a>
            <div class="text-dark-50 font-weight-bold font-size-lg pt-2">
                Total Sell Amount
                <h3 style="margin:10px 0;">৳
                    {{$todaySellSummary[0]->todayTotalSellAmount == null ? 0 : $todaySellSummary[0]->todayTotalSellAmount}}
                </h3>
            </div>
            <div class="text-dark-50 font-weight-bold font-size-lg pt-2">
                Total Product Purchase Amount
                <h3 style="margin:10px 0;">৳
                    {{$todaySellSummary[0]->todayTotalProductAmount == null ? 0 : $todaySellSummary[0]->todayTotalProductAmount}}
                </h3>
            </div>
            <div class="text-dark-50 font-weight-bold font-size-lg pt-2">
                Total Sell Profit
                <h3 style="margin:10px 0; color:#4CAF50">
                    ৳
                    {{$todaySellSummary[0]->todayTotalSellAmount - $todaySellSummary[0]->todayTotalProductAmount}}
                </h3>
            </div>
        </div>
        <!--end::Body-->
    </div>
    </div>
    <div class="col-xl-8">
        <div class="row">
            <div class="col-xl-6">
                <!--begin::Tiles Widget 21-->
                <div class="card card-custom gutter-b" style="height: 180px">
                    <!--begin::Body-->
                    <div class="card-body d-flex flex-column p-0">
                        <!--begin::Stats-->
                        <div class="flex-grow-1 card-spacer pb-0">
                            <span class="svg-icon svg-icon-2x svg-icon-info">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <div class="font-weight-boldest font-size-h3 pt-2">{{ $tcust_count }}</div>
                            <div class="text-muted font-weight-bold">Total Customers</div>
                        </div>
                        <!--end::Stats-->
                        <!--begin::Chart-->
                        <div id="kt_tiles_widget_21_chart" class="card-rounded-bottom" data-color="info" style="height: 100px"></div>
                        <!--end::Chart-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Tiles Widget 21-->
            </div>
            <div class="col-xl-6">
                <!--begin::Tiles Widget 21-->
                <div class="card card-custom gutter-b" style="height: 180px">
                    <!--begin::Body-->
                    <div class="card-body d-flex flex-column p-0">
                        <!--begin::Stats-->
                        <div class="flex-grow-1 card-spacer pb-0">
                            <span class="svg-icon svg-icon-2x svg-icon-info">
                                <!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Group.svg-->
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <polygon points="0 0 24 0 24 24 0 24" />
                                        <path d="M18,14 C16.3431458,14 15,12.6568542 15,11 C15,9.34314575 16.3431458,8 18,8 C19.6568542,8 21,9.34314575 21,11 C21,12.6568542 19.6568542,14 18,14 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                        <path d="M17.6011961,15.0006174 C21.0077043,15.0378534 23.7891749,16.7601418 23.9984937,20.4 C24.0069246,20.5466056 23.9984937,21 23.4559499,21 L19.6,21 C19.6,18.7490654 18.8562935,16.6718327 17.6011961,15.0006174 Z M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                                    </g>
                                </svg>
                                <!--end::Svg Icon-->
                            </span>
                            <div class="font-weight-boldest font-size-h3 pt-2">{{ $tsup_count }}</div>
                            <div class="text-muted font-weight-bold">Total Supplier</div>
                        </div>
                        <!--end::Stats-->
                        <!--begin::Chart-->
                        <div id="kt_tiles_widget_22_chart" class="card-rounded-bottom" data-color="info" style="height: 100px"></div>
                        <!--end::Chart-->
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Tiles Widget 21-->
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <!--begin::Tiles Widget 24-->
                <div class="card card-custom bgi-no-repeat bgi-size-cover gutter-b h-100" style="background-image: url(assets/media/stock-600x400/img-28.jpg)">
                    <!--begin::Body-->
                    <div class="card-body">
                        <a href='#' class="text-dark-75 text-hover-primary font-weight-bolder font-size-h3">This Month Sales</a>
                        <div class="text-dark-50 font-weight-bold font-size-lg pt-2">
                            @if($billMonth)
                                {{ $billMonth[0]->cid }} Sales
                            @else
                                0 Sales
                            @endif
                        </div>
                        <div class="text-dark-50 font-weight-bold font-size-lg pt-2">
                            @if($billMonth)
                                {{$company->currency_symble}} {{ $billMonth[0]->am }}
                            @else
                                {{$company->currency_symble}} 0
                            @endif
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Tiles Widget 24-->
            </div>
            <div class="col-xl-4">
                <!--begin::Tiles Widget 24-->
                <div class="card card-custom bgi-no-repeat bgi-size-cover gutter-b h-100" style="background-image: url(assets/media/stock-600x400/img-28.jpg)">
                    <!--begin::Body-->
                    <div class="card-body">
                        <a href='#' class="text-dark-75 text-hover-primary font-weight-bolder font-size-h3">This Week Sales</a>
                        <div class="text-dark-50 font-weight-bold font-size-lg pt-2">
                            @if($billWeek)
                                {{ $billWeek[0]->cid }} Sales
                            @else
                                0 Sales
                            @endif
                        </div>
                        <div class="text-dark-50 font-weight-bold font-size-lg pt-2">
                            @if($billWeek)
                                {{$company->currency_symble}} {{ $billWeek[0]->am }}
                            @else
                                {{$company->currency_symble}} 0
                            @endif
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Tiles Widget 24-->
            </div>
            <div class="col-xl-4">
                <!--begin::Tiles Widget 24-->
                <div class="card card-custom bgi-no-repeat bgi-size-cover gutter-b h-100" style="background-image: url(assets/media/stock-600x400/img-28.jpg)">
                    <!--begin::Body-->
                    <div class="card-body">
                        <a href='#' class="text-dark-75 text-hover-primary font-weight-bolder font-size-h3">Today Sales</a>
                        <div class="text-dark-50 font-weight-bold font-size-lg pt-2">
                            @if($billToday)
                                {{ $billToday[0]->cid }} Sales
                            @else
                                0 Sales
                            @endif
                        </div>
                        <div class="text-dark-50 font-weight-bold font-size-lg pt-2">
                            @if($billToday)
                                {{$company->currency_symble}} {{ $billToday[0]->am }}
                            @else
                                {{$company->currency_symble}} 0
                            @endif
                        </div>
                    </div>
                    <!--end::Body-->
                </div>
                <!--end::Tiles Widget 24-->
            </div>
        </div>
    </div>
</div>
<!--end::Row-->
	
	<!--end::Dashboard-->
</div>
<!--end::Content-->
@endsection

@push('scripts')

<script>
"use strict";

// Class definition
var KTWidgets = function() {

    // Charts widgets
    var _initMixedWidget6 = function() {
        var element = document.getElementById("kt_mixed_widget_6_chart");
        var height = parseInt(KTUtil.css(element, 'height'));

        if (!element) {
            return;
        }

        var options = {
            series: [{
                name: 'Revenue',
                data: [{{ $Revenue }}]
            }],
            chart: {
                type: 'bar',
                height: height,
                toolbar: {
                    show: false
                },
                sparkline: {
                    enabled: true
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: ['30%'],
                    endingShape: 'rounded'
                },
            },
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 1,
                colors: ['transparent']
            },
            xaxis: {
                categories: [<?=$month?>],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            yaxis: {
                min: 0,
                max: 100,
                labels: {
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            fill: {
                type: ['solid'],
                opacity: [0.8]
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTApp.getSettings()['font-family']
                },
                y: {
                    formatter: function(val) {
                        return  val + "%"
                    }
                },
                marker: {
                    show: false
                }
            },
            colors: ['#ffffff'],
            grid: {
                borderColor: KTApp.getSettings()['colors']['gray']['gray-200'],
                strokeDashArray: 4,
                yaxis: {
                    lines: {
                        show: true
                    }
                },
                padding: {
                    left: 20,
                    right: 20
                }
            }
        };

        var chart = new ApexCharts(element, options);
        chart.render();
    }
    // Mixed widgets

    var _initMixedWidget21 = function() {
        var element = document.getElementById("kt_tiles_widget_21_chart");
        var height = parseInt(KTUtil.css(element, 'height'));
        var color = KTUtil.hasAttr(element, 'data-color') ? KTUtil.attr(element, 'data-color') : 'info';

        if (!element) {
            return;
        }

        var options = {
            series: [{
                name: 'Customer',
                data: [{{ $cust_count }}]
            }],
            chart: {
                type: 'area',
                height: height,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                sparkline: {
                	enabled: true
                }
            },
            plotOptions: {},
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'solid',
                opacity: 1
            },
            stroke: {
                curve: 'smooth',
                show: true,
                width: 3,
                colors: [KTApp.getSettings()['colors']['theme']['base'][color]]
            },
            xaxis: {
                categories: [<?= $cust_month ?>],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false,
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                },
                crosshairs: {
                    show: false,
                    position: 'front',
                    stroke: {
                        color: KTApp.getSettings()['colors']['gray']['gray-300'],
                        width: 1,
                        dashArray: 3
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: undefined,
                    offsetY: 0,
                    style: {
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            yaxis: {
                min: 0,
                max: 32,
                labels: {
                    show: false,
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTApp.getSettings()['font-family']
                },
                y: {
                    formatter: function(val) {
                        return val + " Customers"
                    }
                }
            },
            colors: [KTApp.getSettings()['colors']['theme']['light'][color]],
            markers: {
                colors: [KTApp.getSettings()['colors']['theme']['light'][color]],
                strokeColor: [KTApp.getSettings()['colors']['theme']['base'][color]],
                strokeWidth: 3
            }
        };

        var chart = new ApexCharts(element, options);
        chart.render();
    }

    var _initMixedWidget22 = function() {
        var element = document.getElementById("kt_tiles_widget_22_chart");
        var height = parseInt(KTUtil.css(element, 'height'));
        var color = KTUtil.hasAttr(element, 'data-color') ? KTUtil.attr(element, 'data-color') : 'info';

        if (!element) {
            return;
        }

        var options = {
            series: [{
                name: 'Supplier',
                data: [{{ $sup_count }}]
            }],
            chart: {
                type: 'area',
                height: height,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                sparkline: {
                	enabled: true
                }
            },
            plotOptions: {},
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'solid',
                opacity: 1
            },
            stroke: {
                curve: 'smooth',
                show: true,
                width: 3,
                colors: [KTApp.getSettings()['colors']['theme']['base'][color]]
            },
            xaxis: {
                categories: [<?= $sup_month ?>],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false,
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                },
                crosshairs: {
                    show: false,
                    position: 'front',
                    stroke: {
                        color: KTApp.getSettings()['colors']['gray']['gray-300'],
                        width: 1,
                        dashArray: 3
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: undefined,
                    offsetY: 0,
                    style: {
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            yaxis: {
                min: 0,
                max: 32,
                labels: {
                    show: false,
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTApp.getSettings()['font-family']
                },
                y: {
                    formatter: function(val) {
                        return val + " Supplier"
                    }
                }
            },
            colors: [KTApp.getSettings()['colors']['theme']['light'][color]],
            markers: {
                colors: [KTApp.getSettings()['colors']['theme']['light'][color]],
                strokeColor: [KTApp.getSettings()['colors']['theme']['base'][color]],
                strokeWidth: 3
            }
        };

        var chart = new ApexCharts(element, options);
        chart.render();
    }

    /*var _initMixedWidget23 = function() {
        var element = document.getElementById("kt_tiles_widget_23_chart");
        var height = parseInt(KTUtil.css(element, 'height'));
        var color = KTUtil.hasAttr(element, 'data-color') ? KTUtil.attr(element, 'data-color') : 'primary';

        if (!element) {
            return;
        }

        var options = {
            series: [{
                name: 'Net Profit',
                data: [15, 25, 15, 40, 20, 50]
            }],
            chart: {
                type: 'area',
                height: 125,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                sparkline: {
                	enabled: true
                }
            },
            plotOptions: {},
            legend: {
                show: false
            },
            dataLabels: {
                enabled: false
            },
            fill: {
                type: 'solid',
                opacity: 1
            },
            stroke: {
                curve: 'smooth',
                show: true,
                width: 3,
                colors: [KTApp.getSettings()['colors']['theme']['base'][color]]
            },
            xaxis: {
                categories: ['Jan, 2020', 'Feb, 2020', 'Mar, 2020', 'Apr, 2020', 'May, 2020', 'Jun, 2020'],
                axisBorder: {
                    show: false,
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false,
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                },
                crosshairs: {
                    show: false,
                    position: 'front',
                    stroke: {
                        color: KTApp.getSettings()['colors']['gray']['gray-300'],
                        width: 1,
                        dashArray: 3
                    }
                },
                tooltip: {
                    enabled: true,
                    formatter: undefined,
                    offsetY: 0,
                    style: {
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            yaxis: {
                min: 0,
                max: 55,
                labels: {
                    show: false,
                    style: {
                        colors: KTApp.getSettings()['colors']['gray']['gray-500'],
                        fontSize: '12px',
                        fontFamily: KTApp.getSettings()['font-family']
                    }
                }
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                        value: 0
                    }
                },
                active: {
                    allowMultipleDataPointsSelection: false,
                    filter: {
                        type: 'none',
                        value: 0
                    }
                }
            },
            tooltip: {
                style: {
                    fontSize: '12px',
                    fontFamily: KTApp.getSettings()['font-family']
                },
                y: {
                    formatter: function(val) {
                        return "$" + val + " thousands"
                    }
                }
            },
            colors: [KTApp.getSettings()['colors']['theme']['light'][color]],
            markers: {
                colors: [KTApp.getSettings()['colors']['theme']['light'][color]],
                strokeColor: [KTApp.getSettings()['colors']['theme']['base'][color]],
                strokeWidth: 3
            }
        };

        var chart = new ApexCharts(element, options);
        chart.render();
    }
*/

    var _initAdvancedTableGroupSelection = function(element) {
        var table = KTUtil.getById(element);

        KTUtil.on(table, 'thead th .checkbox > input', 'change', function(e) {
            var checkboxes = KTUtil.findAll(table, 'tbody td .checkbox > input');

            for (var i = 0, len = checkboxes.length; i < len; i++) {
                checkboxes[i].checked = this.checked;
            }
        });
    }

    // Public methods
    return {
        init: function() {
            // Charts Widgets
            _initMixedWidget6();
            _initMixedWidget21();
            _initMixedWidget22();
            //_initMixedWidget23();

            // Table Widgets
            _initAdvancedTableGroupSelection('kt_advance_table_widget_1');
            _initAdvancedTableGroupSelection('kt_advance_table_widget_2');
            _initAdvancedTableGroupSelection('kt_advance_table_widget_3');
            _initAdvancedTableGroupSelection('kt_advance_table_widget_4');
        }
    }
}();

// Webpack support
if (typeof module !== 'undefined') {
    module.exports = KTWidgets;
}

jQuery(document).ready(function() {
    KTWidgets.init();
});
</script>
@endpush
