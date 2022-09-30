@extends('layout.admin.admin_master')
@section('title', 'Admin | Dashboard')
@section('content')

<div class="content flex-column-fluid" id="kt_content">
	<!--begin::Dashboard-->
	
	<!--Begin::Row-->
	<div class="row">
		<div class="col-xl-3">
			<!--begin::Stats Widget 25-->
			<div class="card card-custom bg-light-success card-stretch gutter-b">
				<!--begin::Body-->
				<div class="card-body">
				    <a href="{{route(currentUser().'.allUser')}}">
					<span class="svg-icon svg-icon-2x svg-icon-success">
						<i class="icon-2x text-dark-50 flaticon2-user"></i>
					</span>
					<span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-2 d-block">All User</span></a>
				</div>
				<!--end::Body-->
			</div>
			<!--end::Stats Widget 25-->
		</div>
		<div class="col-xl-3">
			<!--begin::Stats Widget 26-->
			<div class="card card-custom bg-light-danger card-stretch gutter-b">
				<!--begin::ody-->
				<div class="card-body">
				    <a href="{{route(currentUser().'.addNewUserForm')}}">
					<span class="svg-icon svg-icon-2x svg-icon-success">
						<i class="icon-2x text-dark-50 flaticon-user-add"></i>
					</span>
					<span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-2 d-block">New User</span></a>
				</div>
				<!--end::Body-->
			</div>
			<!--end::Stats Widget 26-->
		</div>
		<div class="col-xl-3">
			<!--begin::Stats Widget 27-->
			<div class="card card-custom bg-light-info card-stretch gutter-b">
				<!--begin::Body-->
				<div class="card-body">
				    <a href="{{route(currentUser().'.allUserPackage')}}">
					<span class="svg-icon svg-icon-2x svg-icon-success">
						<i class="icon-2x text-dark-50 flaticon-calendar-with-a-clock-time-tools"></i>
					</span>
					<span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-2 d-block">Package List</span></a>
				</div>
				<!--end::Body-->
			</div>
			<!--end::Stats Widget 27-->
		</div>
		<div class="col-xl-3">
			<!--begin::Stats Widget 28-->
			<div class="card card-custom bg-light-warning card-stretch gutter-b">
				<!--begin::Body-->
				<div class="card-body">
				    <a href="{{route(currentUser().'.addNewUserPackageForm')}}">
					<span class="svg-icon svg-icon-2x svg-icon-success">
						<i class="icon-2x text-dark-50 flaticon-user-add"></i>
					</span>
					<span class="card-title font-weight-bolder text-dark-75 font-size-h2 mb-0 mt-2 d-block">New Package</span></a>
				</div>
				<!--end::Body-->
			</div>
			<!--end::Stat: Widget 28-->
		</div>
	</div>
	<!--End::Row-->
	
	
	<!--end::Dashboard-->
</div>


@endsection
