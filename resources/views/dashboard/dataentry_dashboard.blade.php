@extends('layout.admin.admin_master')
@section('title', 'Admin | Dashboard')
@section('content')
<!--begin::Container-->
<div class="d-flex flex-row flex-column-fluid container">
	<!--begin::Content Wrapper-->
	<div class="main d-flex flex-column flex-row-fluid">
		<div class="content flex-column-fluid" id="kt_content">
			<!--begin::Dashboard-->
			<!--begin::Notice-->
            @if( Session::has('response') )
            <div class="alert alert-custom alert-{{Session::get('response')['class']}} alert-shadow gutter-b" role="alert">
            	<div class="alert-icon">
            		<i class="flaticon2-bell-4"></i>
            	</div>
            	<div class="alert-text">
            		{{Session::get('response')['message']}}
            		<button type="button" class="close" data-dismiss="alert" aria-label="Close">
            			<span aria-hidden="true">&times;</span>
            		</button>
            	</div>
            </div>
            @endif
            <!--end::Notice-->
			<!--begin::Row-->
			<div class="row">
				<div class="col-xl-4">
					<!--begin::Stats Widget 1-->
					<div class="card card-custom bgi-no-repeat card-border gutter-b card-stretch" style="background-position: right top; background-size: 30% auto; background-image: url(assets/media/svg/shapes/abstract-4.svg)">
						<!--begin::Body-->
						<div class="card-body">
							<a href="#" class="card-title font-weight-bold text-muted text-hover-primary font-size-h5">Meeting Schedule</a>
							<div class="font-weight-bold text-success mt-9 mb-5">3:30PM - 4:20PM</div>
							<p class="text-dark-75 font-weight-bolder font-size-h5 m-0">Craft a headline that is informative
							<br />and will capture readers</p>
						</div>
						<!--end::Body-->
					</div>
					<!--end::Stats Widget 1-->
				</div>
				<div class="col-xl-4">
					<!--begin::Stats Widget 2-->
					<div class="card card-custom bgi-no-repeat card-border gutter-b card-stretch" style="background-position: right top; background-size: 30% auto; background-image: url(assets/media/svg/shapes/abstract-2.svg)">
						<!--begin::Body-->
						<div class="card-body">
							<a href="#" class="card-title font-weight-bold text-muted text-hover-primary font-size-h5">Announcement</a>
							<div class="font-weight-bold text-success mt-9 mb-5">03 May 2020</div>
							<p class="text-dark-75 font-weight-bolder font-size-h5 m-0">Great blog posts don’t just happen
							<br />Even the best bloggers need it</p>
						</div>
						<!--end::Body-->
					</div>
					<!--end::Stats Widget 2-->
				</div>
				<div class="col-xl-4">
					<!--begin::Stats Widget 3-->
					<div class="card card-custom bgi-no-repeat card-border gutter-b card-stretch" style="background-position: right top; background-size: 30% auto; background-image: url(assets/media/svg/shapes/abstract-1.svg)">
						<!--begin::body-->
						<div class="card-body">
							<a href="#" class="card-title font-weight-bold text-muted text-hover-primary font-size-h5">New Release</a>
							<div class="font-weight-bold text-success mt-9 mb-5">ReactJS</div>
							<p class="text-dark-75 font-weight-bolder font-size-h5 m-0">AirWays - A Front-end solution for
							<br />airlines build with ReactJS</p>
						</div>
						<!--end::Body-->
					</div>
					<!--end::Stats Widget 3-->
				</div>
			</div>
			<!--end::Row-->
			<!--end::Dashboard-->
		</div>
		<!--end::Content-->
	</div>
	<!--begin::Content Wrapper-->
</div>
<!--end::Container-->
@endsection
@push('scripts')

	<script src="{{asset('/')}}assets/js/pages/widgets.js?v=7.0.4"></script>

@endpush