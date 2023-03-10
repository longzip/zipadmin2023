<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	
	<!-- <base href="{{asset('')}}"> -->
	<title>ZIP</title>
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500&amp;subset=vietnamese" rel="stylesheet">

	<link rel="stylesheet" href="{{asset('css/app.css')}}">
	<style type="text/css">
		body {
		  font-family: 'Roboto', sans-serif !important;
		}
		.tabs-component{
			margin: 0 !important;
		}
	</style>
</head>
<body class="hold-transition sidebar-mini">
	<div class="wrapper" id="app">

		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom" style="height: 37px;">
			<!-- Left navbar links -->
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
				</li>
			</ul>

			<!-- SEARCH FORM -->
			<div class="form-inline ml-3">
				<div class="input-group input-group-sm">
					<input class="form-control form-control-navbar" @keyup="searchit" v-model="search" type="search" placeholder="Search" aria-label="Search">
					<div class="input-group-append">
						<a href="#" class="btn btn-navbar">
							<i class="fa fa-search"></i>
						</a>
					</div>
				</div>
			</div>
		</nav>
		<!-- /.navbar -->

		<!-- Main Sidebar Container -->
		<aside class="main-sidebar sidebar-dark-primary elevation-4">
			<!-- Brand Logo -->
			<a href="/" class="brand-link">
				<img src="{{asset('img/logozip-new.png')}}" alt="ZIP Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
				<span class="brand-text font-weight-light green">ZIP</span>
			</a>

			<!-- Sidebar -->
			<div class="sidebar">
				<!-- Sidebar user panel (optional) -->
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<img src="{{asset('img/man.png')}}" class="img-circle elevation-2" alt="User Image">
					</div>
					<div class="info">
						<a href="#" class="d-block">{{ Auth::user()->name }}</a>
					</div>
				</div>

				<!-- Sidebar Menu -->
				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
						@can('thicong')
						<li class="nav-item">
							<a href="#" class="nav-link">
								<i class="fa fa-user green nav-icon cyan"></i>
								<p>
									Thi C??ng
									<i class="right fa fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<router-link to="/lich-giao" class="nav-link">
										<i class="nav-icon fa fa-exclamation-triangle green"></i>
										<p>
											L???ch Giao
										</p>
									</router-link>
								</li>
								<li class="nav-item">
									<router-link to="/bao-cao-thi-cong" class="nav-link">
										<i class="nav-icon fa fa-cogs green"></i>
										<p>
											B??o C??o Thi C??ng
										</p>
									</router-link>
								</li>
							</ul>
						</li>

						@endcan
						@if(Auth::user()->id == 269 || Auth::user()->id == 9318 || Auth::user()->id == 9320  || Auth::user()->id == 9367  || Auth::user()->id == 9368)
							<li class="nav-item">
								<router-link to="/dashboard" class="nav-link">
									<i class="nav-icon fa fa-dashboard green"></i>
									<p>
										Dashboard
									</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/bang-luong-sales" class="nav-link">
									<i class="fa fa-bar-chart-o nav-icon"></i>
									<p>L????ng</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/khach" class="nav-link">
									<i class="fa fa-circle-o nav-icon white"></i>
									<p>Ti???m n??ng doanh s???</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/bang-gia" class="nav-link">
									<i class="fa fa-file-image-o green nav-icon cyan"></i>
									<p>
										B???ng G??a
									</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/bang-target-showroom" class="nav-link">
									<i class="fa fa-bar-chart-o nav-icon"></i>
									<p>B???ng m???c ti??u Sales</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/bao-cao-khtn" class="nav-link">
									<i class="fa fa-line-chart nav-icon"></i>
									<p>Ch??m S??c KH</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/khach-online" class="nav-link">
									<i class="fa fa-circle-o nav-icon white"></i>
									<p>Marketing Online</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/bao-cao-ngay" class="nav-link">
									<i class="fa fa-bar-chart-o nav-icon"></i>
									<p>B??o C??o Ng??y</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/don-hang" class="nav-link">
									<i class="fa fa-circle-o nav-icon red"></i>
									<p>????n h??ng</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/kh15" class="nav-link">
									<i class="fa fa-circle-o nav-icon cyan"></i>
									<p>KH15</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/thong-tin-khach" class="nav-link">
									<i class="fa fa-circle-o nav-icon cyan"></i>
									<p>Tra c???u th??ng tin</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/khach-tiem-nang" class="nav-link">
									<i class="fa fa-circle-o nav-icon white"></i>
									<p>Kh??ch h??ng ti???m n??ng</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/telepro" class="nav-link">
									<i class="fa fa-circle-o nav-icon cyan"></i>
									<p>Telepro</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/tai-lieu" class="nav-link">
									<i class="fa fa-file-image-o green nav-icon cyan"></i>
									<p>
										T??i Li???u
									</p>
								</router-link>
							</li>
						@endif
						@if(Auth::user()->id != 269 && Auth::user()->id != 9318 && Auth::user()->id != 9320 || Auth::user()->id != 9367  || Auth::user()->id != 9368)
							@can('ctv')
							<li class="nav-item">
								<router-link to="/cv" class="nav-link">
									<i class="fa fa-circle-o nav-icon white"></i>
									<p>CV</p>
								</router-link>
							</li>
							@endcan
							@if(Auth::user()->id == 9264)
							<li class="nav-item">
								<router-link to="/du-chi" class="nav-link">
									<i class="fa fa-circle-o nav-icon cyan"></i>
									<p>D??? Ki???n Chi</p>
								</router-link>
							</li>
							@endif
							@if(Auth::user()->id == 3 || Auth::user()->id == 9261 )
							<li class="nav-item">
								<router-link to="/dai-ly" class="nav-link">
									<i class="fa fa-circle-o nav-icon white"></i>
									<p>?????i L??</p>
								</router-link>
							</li>
							@endif
							@can('marketing')
							<li class="nav-item">
								<router-link to="/khach" class="nav-link">
									<i class="fa fa-circle-o nav-icon white"></i>
									<p>Ti???m n??ng doanh s???</p>
								</router-link>
							</li>
							@endcan
							@canany(['sale','import'])
							<li class="nav-item">
								<router-link to="/dashboard" class="nav-link">
									<i class="nav-icon fa fa-dashboard green"></i>
									<p>
										Dashboard
									</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/tai-lieu" class="nav-link">
									<i class="fa fa-file-image-o green nav-icon cyan"></i>
									<p>
										T??i Li???u
									</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/bang-gia" class="nav-link">
									<i class="fa fa-file-image-o green nav-icon cyan"></i>
									<p>
										B???ng G??a
									</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/san-xuat" class="nav-link">
									<i class="fa fa-circle-o nav-icon cyan"></i>
									<p>Nguy??n V???t Li???u</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/thong-tin-khach" class="nav-link">
									<i class="fa fa-circle-o nav-icon cyan"></i>
									<p>Tra c???u th??ng tin</p>
								</router-link>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="fa fa-user green nav-icon cyan"></i>
									<p>
										Nh??n S???
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/cham-cong" class="nav-link">
											<i class="nav-icon fa fa-exclamation-triangle green"></i>
											<p>
												Ch???m C??ng
											</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/baocao-cham-cong" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>B??o C??o Ch???m C??ng</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bang-luong-sales" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>L????ng</p>
										</router-link>
									</li>
									@if(Auth::user()->id == 9074 || Auth::user()->id == 9003 || Auth::user()->id == 9334)
									<li class="nav-item">
										<router-link to="/bang-luong-khac" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>L????ng c?? b???n</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/luong-phu-cap" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>L????ng ph??? c???p</p>
										</router-link>
									</li>
									@endif
									<li class="nav-item">
										<router-link to="/thong-bao" class="nav-link">
											<i class="nav-icon fa fa-exclamation-triangle green"></i>
											<p>
												Quy ?????nh
											</p>
										</router-link>
									</li>
								</ul>
								
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="fa fa-user green nav-icon cyan"></i>
									<p>
										Thi C??ng
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/lich-giao" class="nav-link">
											<i class="nav-icon fa fa-exclamation-triangle green"></i>
											<p>
												L???ch Giao
											</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bao-cao-thi-cong" class="nav-link">
											<i class="nav-icon fa fa-cogs green"></i>
											<p>
												B??o C??o Thi C??ng
											</p>
										</router-link>
									</li>
								</ul>
							</li>
							<li class="nav-item">
								<router-link to="/csvc" class="nav-link">
									<i class="nav-icon fa fa-fire-extinguisher green"></i>
									<p>
										CSVC
									</p>
								</router-link>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="fa fa-newspaper-o green nav-icon cyan"></i>
									<p>
										Tin T???c
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/tin-tong-hop" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Tin T???ng H???p <span class="right badge badge-danger">{{$tth}}</span></p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/teambuilding" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Tuy???n D???ng <span class="right badge badge-danger">{{$tb}}</span></p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/khuyen-mai" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Khuy???n M???i <span class="right badge badge-danger">{{$km}}</span></p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/thanh-tich" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Th??nh T??ch <span class="right badge badge-danger">{{$tt}}</span></p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/du-an" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>D??? ??n <span class="right badge badge-danger">{{$da}}</span></p>
										</router-link>
									</li>
									
									<li class="nav-item">
										<router-link to="/danh-gia-khach-hang" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>????nh Gi?? Kh??ch H??ng <span class="right badge badge-danger">{{$dg}}</span></p>
										</router-link>
									</li>
									
								</ul>
							</li>
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon fa fa-address-card-o yellow"></i>
									<p>
										Kh??ch H??ng
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/xem-khu-vuc" class="nav-link">
											<i class="fa fa-circle-o nav-icon green"></i>
											<p>Khu v???c</p>
										</router-link>
									</li>
									@if(Auth::user()->id == 9074 || Auth::user()->id == 9197 || Auth::user()->id == 9015 || Auth::user()->id == 9003 || Auth::user()->id == 9134)
									<li class="nav-item">
										<router-link to="/data" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>DATA</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/blackfriday" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>BlackFriday 2020</p>
										</router-link>
									</li>
									@endif
									@if(Auth::user()->id == 9074 || Auth::user()->id == 268 || Auth::user()->id == 269 || Auth::user()->id == 9015 || Auth::user()->id == 9003)
									<li class="nav-item">
										<router-link to="/telepro" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Telepro</p>
										</router-link>
									</li>
									@endif
									<li class="nav-item">
										<router-link to="/khach-online" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Marketing Online</p>
										</router-link>
									</li>

									<li class="nav-item">
										<router-link to="/bao-cao-khtn" class="nav-link">
											<i class="fa fa-line-chart nav-icon"></i>
											<p>Ch??m S??c KH</p>
										</router-link>
									</li>
									
									<li class="nav-item">
										<router-link to="/kh15" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>KH15</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/khach-tiem-nang" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Kh??ch h??ng ti???m n??ng</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/project" class="nav-link">
											<i class="fa fa-circle-o nav-icon yellow"></i>
											<p>D??? ??n</p>
										</router-link>
									</li>
									@if(Auth::user()->id == 9074 || Auth::user()->id == 9003 )
									<li class="nav-item">
										<router-link to="/dai-ly" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>?????i L??</p>
										</router-link>
									</li>
									@endif
									<li class="nav-item">
										<router-link to="/bang-target-showroom" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>B???ng m???c ti??u Sales</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bao-cao-ngay" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>B??o C??o Ng??y</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/nghi-phep" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Ngh??? ph??p</p>
										</router-link>
									</li>
								</ul>
							</li>
							
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon fa fa-money orange"></i>
									<p>
										Sale
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/don-hang" class="nav-link">
											<i class="fa fa-circle-o nav-icon red"></i>
											<p>????n h??ng</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/report-product" class="nav-link">
											<i class="fa fa-circle-o nav-icon red"></i>
											<p>S???n Ph???m B??n</p>
										</router-link>
									</li>
									@can('import')
									<li class="nav-item">
										<router-link to="/khach-hang" class="nav-link">
											<i class="fa fa-address-book nav-icon yellow"></i>
											<p>Kh??ch h??ng</p>
										</router-link>
									</li>
									@endcan
									<li class="nav-item">
										<router-link to="/san-pham" class="nav-link">
											<i class="fa fa-bookmark nav-icon blue"></i>
											<p>S???n ph???m</p>
										</router-link>
									</li>
								</ul>
							</li>
							
							@endcanany
							@can('import')
							@if(Auth::user()->id != 269)
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon fa fa-gift green"></i>
									<p>
										Target Import
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/sales-target" class="nav-link">
											<i class="fa fa-file-excel-o nav-icon"></i>
											<p>Sales Target</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/costcenters-target" class="nav-link">
											<i class="fa fa-file-excel-o nav-icon green"></i>
											<p>Costcenters Target</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/chien-dich" class="nav-link">
											<i class="fa fa-file-excel-o nav-icon green"></i>
											<p>Chi???n D???ch</p>
										</router-link>
									</li>
								</ul>
							</li>
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon fa fa-cog"></i>
									<p>
										Management
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/users" class="nav-link">
											<i class="fa fa-users nav-icon"></i>
											<p>User</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/costcenter" class="nav-link">
											<i class="fa fa-info nav-icon"></i>
											<p>Showroom</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/khu-vuc" class="nav-link">
											<i class="fa fa-info nav-icon cyan"></i>
											<p>Khu v???c</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/kho-khu-vuc" class="nav-link">
											<i class="fa fa-info nav-icon"></i>
											<p>Warehouse</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/activity-types" class="nav-link">
											<i class="fa fa-info nav-icon"></i>
											<p>Lo???i ho???t ?????ng</p>
										</router-link>
									</li>
								</ul>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<a target="_blank" href="/import" class="nav-link">
											<i class="fa fa-cloud-upload nav-icon green"></i>
											<p>Import</p>
										</a>
									</li>
								</ul>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="fa fa-newspaper-o green nav-icon cyan"></i>
									<p>
										HC-NS
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/quyet-dinh" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Quy ?????nh</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/che-tai" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Ch??? T??i</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/quy-trinh" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Quy Tr??nh</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/vi-pham" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Vi Ph???m</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/nghi-phep" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Ngh??? ph??p</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/thuc-trang" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Th???c Tr???ng</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/tuyen-dung" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Tuy???n D???ng</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/cv" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>CV</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/baocaouv" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>B??o C??o ??V</p>
										</router-link>
									</li>
								</ul>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="fa fa-newspaper-o green nav-icon cyan"></i>
									<p>
										Chi Ph??
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<!-- <li class="nav-item">
										<router-link to="/loai-chi-phi" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Lo???i Chi Ph??</p>
										</router-link>
									</li> -->
									<li class="nav-item">
										<router-link to="/du-chi" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>D??? Ki???n Chi</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/chi-phi" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Chi Ph??</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/loai-chi-phi" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Lo???i Chi Ph??</p>
										</router-link>
									</li>
									<!-- <li class="nav-item">
										<router-link to="/chi-phi" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Chi Ph?? All</p>
										</router-link>
									</li> -->
									<!-- <li class="nav-item">
										<router-link to="/thu" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Thu</p>
										</router-link>
									</li> -->
								</ul>
							</li>
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon fa fa-bar-chart-o"></i>
									<p>
										B??o c??o
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/giao-hang" class="nav-link">
											<i class="fa fa-line-chart nav-icon"></i>
											<p>Admin</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bao-cao-lich-giao" class="nav-link">
											<i class="fa fa-line-chart nav-icon"></i>
											<p>B??o C??o L???ch Giao</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bao-cao" class="nav-link">
											<i class="fa fa-line-chart nav-icon"></i>
											<p>C?? h???i b??n h??ng</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bang-xep-hang" class="nav-link">
											<i class="fa fa-sort-amount-desc nav-icon"></i>
											<p>B???ng x???p h???ng SR</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bang-xep-hang-user" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>B???ng x???p h???ng Sales</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bang-target-showroom" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>B???ng m???c ti??u Sales</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bang-target-sales" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>B???ng m???c ti??u SR</p>
										</router-link>
									</li>
									
									<li class="nav-item">
										@if(\Auth::user()->id == 9003 || \Auth::user()->id == 9074 || \Auth::user()->id == 9015)
										<router-link to="/bao-cao-nam" class="nav-link">
											<i class="fa  fa-area-chart nav-icon"></i>
											<p>B???ng B??o C??o N??m</p>
										</router-link>
										@endif
									</li>
									<li class="nav-item">
										<router-link to="/bao-cao-khtn" class="nav-link">
											<i class="fa fa-line-chart nav-icon"></i>
											<p>KHTN</p>
										</router-link>
									</li>
									<!-- <li class="nav-item">
										<router-link to="/online" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>B??o C??o Online</p>
										</router-link>
									</li> -->
								</ul>
							</li>
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon fa fa-circle-o"></i>
									<p>
										B???ng Tin N???i B???
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/list-bang-tin" class="nav-link">
											<i class="fa fa-file-text-o nav-icon green"></i>
											<p>Nh???p N???i Dung</p>
										</router-link>
									</li>
								</ul>
							</li>
							@endif
							@endcan
						@endif

						@can('sale')
						<li class="nav-header">DOCS</li>
						<li class="nav-item has-treeview">
							<a href="/docs" target="_blank" class="nav-link">
								<i class="fa fa-book nav-icon red"></i>
								<p>
									T??i li???u h?????ng ?????n
								</p>
							</a>
						</li>

						<li class="nav-item">
							<router-link to="/profile" class="nav-link">
								<i class="nav-icon fa fa-user blue"></i>
								<p>
									Profile
								</p>
							</router-link>
						</li>

						@endcan
						<li class="nav-item">
							<a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
								<i class="nav-icon fa fa-power-off red"></i>
								<p>
									Logout
								</p>
							</a>

							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
								@csrf
							</form>
						</li>
					</ul>
				</nav>
				<!-- /.sidebar-menu -->
			</div>
			<!-- /.sidebar -->
		</aside>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Main content -->
			<div class="content">
				<div class="container-fluid">
					<router-view></router-view>

					<vue-progress-bar></vue-progress-bar>
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->

		<!-- Main Footer -->
		<footer class="main-footer">
			<!-- To the right -->
			<div class="float-right d-none d-sm-inline">
				Anything you want
			</div>
			<!-- Default to the left -->
			<strong>Copyright &copy; 2019 <a href="">Lo Van Long</a>.</strong> Nhan vien IT - ZIP.
		</footer>
	</div>
	<!-- ./wrapper -->

	@auth
		<script>
		    window.user = @json(auth()->user())
		</script>
	@endauth


	<script src="{{asset('js/app.js?v=2225')}}"></script>
</body>
</html>
	