@extends('layout.admin.admin_master')

@section('title', 'owner List')

@section('content')
<div class="page-wrapper">


    <!-- Page Content-->
    <div class="page-content">

        <div class="container-fluid">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    @if( Session::has('response') )
                    <div class="my-3 alert d-flex align-items-center justify-content-between alert-{{Session::get('response')['class']}}" role="alert">
                        {{Session::get('response')['message']}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    @endif
                    <div class="page-title-box">
                        <div class="float-right">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="javascript:void(0);" class="text-capitalize">{{ currentUser() }}</a></li>
                                <li class="breadcrumb-item"><a href="javascript:void(0);">User</a></li>
                                <li class="breadcrumb-item active">All</li>
                            </ol>
                            <!--end breadcrumb-->
                        </div>
                        <!--end /div-->
                        <h4 class="page-title">owner List</h4>
                    </div>
                    <!--end page-title-box-->
                </div>
                <!--end col-->
            </div>
            <!--end row-->
            <!-- end page title end breadcrumb -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">

                            <div class="table-responsive">
                                <table class="table table-striped mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Contact No</th>
                                            <th>Email</th>
                                            <th>Telemarketer</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(count($allUser))
                                        @foreach($allUser as $user)
                                        <tr>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->mobileNumber}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>{{$user->tm}}</td>
                                            <td>
								
									<div class="btn-group">
									@if($user->tm)
										<?php $class="success"; ?>
									@else
										<?php $class="danger"; ?>
									@endif
									
									  <button type="button" class="btn btn-{{$class}} dropdown-toggle" id="btn{{$user->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										@if($user->tm)
											{{$user->tm}}
										@else
											Select Telemarketer
										@endif
									  </button>
									  <div class="dropdown-menu">
									  @if($allTm)
										@foreach($allTm as $tms)
										<a href="{{route(currentUser().'.modAssign',[encryptor('encrypt', $user->id), encryptor('encrypt', $tms->id)])}}" class="dropdown-item <?= $tms->name==$user->tm?"active":"" ?>" href="#">{{$tms->name}}</a>
										@endforeach
									@endif
									  </div>
									</div>
								</td>
                                        </tr>
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                                <!--end /table-->
                            </div>
                            <!--end /tableresponsive-->
                            <div class="d-flex align-items-center justify-content-between">
                                {{$allUser->links()}}
                                <h4 class="my-3 header-title text-right"><a class="btn btn-warning" href="{{route(currentUser().'.addNewUserForm')}}">Add new user</a> </h4>
                            </div>


                        </div>
                        <!--end card-body-->
                    </div>
                    <!--end card-->
                </div> <!-- end col -->
            </div>
            <!--end row-->

        </div><!-- container -->
    </div>

</div>
@endsection