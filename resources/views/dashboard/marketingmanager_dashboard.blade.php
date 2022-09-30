@extends('layout.admin.admin_master')
@section('title', 'Marketing Manager | Dashboard')
@section('content')

@section('content')

<div class="content flex-column-fluid" id="kt_content">
	<!--begin::Dashboard-->

	<!--Begin::Row-->
	<div class="row">
		<div class="col-xl-3">
			<a href="{{route(currentUser().'.allUserPackage')}}" class="btn btn-primary font-weight-bolder d-block">
				<span class="svg-icon svg-icon-2x svg-icon-success">
					<i class="icon-2x flaticon-list-3"></i>
				</span>
				Package List
			</a>
		</div>
		<div class="col-xl-3">
			<a href="{{route(currentUser().'.addNewUserPackageForm')}}"
				class="btn btn-primary font-weight-bolder d-block">
				<span class="svg-icon svg-icon-2x svg-icon-success">
					<i class="icon-2x flaticon2-add-square"></i>
				</span>
				New Package
			</a>
		</div>

		<div class="col-xl-3">

			<a href="{{route(currentUser().'.addNewUserForm')}}" class="btn btn-primary font-weight-bolder d-block">
				<span class="svg-icon svg-icon-2x svg-icon-success">
					<i class="icon-2x flaticon-user-add"></i>
				</span>
				New User
			</a>
		</div>
	</div>
	<!--End::Row-->

	<!--end::Dashboard-->
</div>


@endsection