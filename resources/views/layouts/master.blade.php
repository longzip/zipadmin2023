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
									Thi Công
									<i class="right fa fa-angle-left"></i>
								</p>
							</a>
							<ul class="nav nav-treeview">
								<li class="nav-item">
									<router-link to="/lich-giao" class="nav-link">
										<i class="nav-icon fa fa-exclamation-triangle green"></i>
										<p>
											Lịch Giao
										</p>
									</router-link>
								</li>
								<li class="nav-item">
									<router-link to="/bao-cao-thi-cong" class="nav-link">
										<i class="nav-icon fa fa-cogs green"></i>
										<p>
											Báo Cáo Thi Công
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
									<p>Lương</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/khach" class="nav-link">
									<i class="fa fa-circle-o nav-icon white"></i>
									<p>Tiềm năng doanh số</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/bang-gia" class="nav-link">
									<i class="fa fa-file-image-o green nav-icon cyan"></i>
									<p>
										Bảng Gía
									</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/bang-target-showroom" class="nav-link">
									<i class="fa fa-bar-chart-o nav-icon"></i>
									<p>Bảng mục tiêu Sales</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/bao-cao-khtn" class="nav-link">
									<i class="fa fa-line-chart nav-icon"></i>
									<p>Chăm Sóc KH</p>
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
									<p>Báo Cáo Ngày</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/don-hang" class="nav-link">
									<i class="fa fa-circle-o nav-icon red"></i>
									<p>Đơn hàng</p>
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
									<p>Tra cứu thông tin</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/khach-tiem-nang" class="nav-link">
									<i class="fa fa-circle-o nav-icon white"></i>
									<p>Khách hàng tiềm năng</p>
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
										Tài Liệu
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
									<p>Dự Kiến Chi</p>
								</router-link>
							</li>
							@endif
							@if(Auth::user()->id == 3 || Auth::user()->id == 9261 )
							<li class="nav-item">
								<router-link to="/dai-ly" class="nav-link">
									<i class="fa fa-circle-o nav-icon white"></i>
									<p>Đại Lý</p>
								</router-link>
							</li>
							@endif
							@can('marketing')
							<li class="nav-item">
								<router-link to="/khach" class="nav-link">
									<i class="fa fa-circle-o nav-icon white"></i>
									<p>Tiềm năng doanh số</p>
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
										Tài Liệu
									</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/bang-gia" class="nav-link">
									<i class="fa fa-file-image-o green nav-icon cyan"></i>
									<p>
										Bảng Gía
									</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/san-xuat" class="nav-link">
									<i class="fa fa-circle-o nav-icon cyan"></i>
									<p>Nguyên Vật Liệu</p>
								</router-link>
							</li>
							<li class="nav-item">
								<router-link to="/thong-tin-khach" class="nav-link">
									<i class="fa fa-circle-o nav-icon cyan"></i>
									<p>Tra cứu thông tin</p>
								</router-link>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="fa fa-user green nav-icon cyan"></i>
									<p>
										Nhân Sự
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/cham-cong" class="nav-link">
											<i class="nav-icon fa fa-exclamation-triangle green"></i>
											<p>
												Chấm Công
											</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/baocao-cham-cong" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>Báo Cáo Chấm Công</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bang-luong-sales" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>Lương</p>
										</router-link>
									</li>
									@if(Auth::user()->id == 9074 || Auth::user()->id == 9003 || Auth::user()->id == 9334)
									<li class="nav-item">
										<router-link to="/bang-luong-khac" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>Lương cơ bản</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/luong-phu-cap" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>Lương phụ cấp</p>
										</router-link>
									</li>
									@endif
									<li class="nav-item">
										<router-link to="/thong-bao" class="nav-link">
											<i class="nav-icon fa fa-exclamation-triangle green"></i>
											<p>
												Quy Định
											</p>
										</router-link>
									</li>
								</ul>
								
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="fa fa-user green nav-icon cyan"></i>
									<p>
										Thi Công
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/lich-giao" class="nav-link">
											<i class="nav-icon fa fa-exclamation-triangle green"></i>
											<p>
												Lịch Giao
											</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bao-cao-thi-cong" class="nav-link">
											<i class="nav-icon fa fa-cogs green"></i>
											<p>
												Báo Cáo Thi Công
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
										Tin Tức
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/tin-tong-hop" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Tin Tổng Hợp <span class="right badge badge-danger">{{$tth}}</span></p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/teambuilding" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Tuyển Dụng <span class="right badge badge-danger">{{$tb}}</span></p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/khuyen-mai" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Khuyến Mại <span class="right badge badge-danger">{{$km}}</span></p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/thanh-tich" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Thành Tích <span class="right badge badge-danger">{{$tt}}</span></p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/du-an" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Dự Án <span class="right badge badge-danger">{{$da}}</span></p>
										</router-link>
									</li>
									
									<li class="nav-item">
										<router-link to="/danh-gia-khach-hang" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Đánh Giá Khách Hàng <span class="right badge badge-danger">{{$dg}}</span></p>
										</router-link>
									</li>
									
								</ul>
							</li>
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon fa fa-address-card-o yellow"></i>
									<p>
										Khách Hàng
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/xem-khu-vuc" class="nav-link">
											<i class="fa fa-circle-o nav-icon green"></i>
											<p>Khu vực</p>
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
											<p>Chăm Sóc KH</p>
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
											<p>Khách hàng tiềm năng</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/project" class="nav-link">
											<i class="fa fa-circle-o nav-icon yellow"></i>
											<p>Dự án</p>
										</router-link>
									</li>
									@if(Auth::user()->id == 9074 || Auth::user()->id == 9003 )
									<li class="nav-item">
										<router-link to="/dai-ly" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Đại Lý</p>
										</router-link>
									</li>
									@endif
									<li class="nav-item">
										<router-link to="/bang-target-showroom" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>Bảng mục tiêu Sales</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bao-cao-ngay" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>Báo Cáo Ngày</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/nghi-phep" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Nghỉ phép</p>
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
											<p>Đơn hàng</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/report-product" class="nav-link">
											<i class="fa fa-circle-o nav-icon red"></i>
											<p>Sản Phẩm Bán</p>
										</router-link>
									</li>
									@can('import')
									<li class="nav-item">
										<router-link to="/khach-hang" class="nav-link">
											<i class="fa fa-address-book nav-icon yellow"></i>
											<p>Khách hàng</p>
										</router-link>
									</li>
									@endcan
									<li class="nav-item">
										<router-link to="/san-pham" class="nav-link">
											<i class="fa fa-bookmark nav-icon blue"></i>
											<p>Sản phẩm</p>
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
											<p>Chiến Dịch</p>
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
											<p>Khu vực</p>
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
											<p>Loại hoạt động</p>
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
											<p>Quy Định</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/che-tai" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Chế Tài</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/quy-trinh" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Quy Trình</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/vi-pham" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Vi Phạm</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/nghi-phep" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Nghỉ phép</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/thuc-trang" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Thực Trạng</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/tuyen-dung" class="nav-link">
											<i class="fa fa-circle-o nav-icon white"></i>
											<p>Tuyển Dụng</p>
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
											<p>Báo Cáo ƯV</p>
										</router-link>
									</li>
								</ul>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="fa fa-newspaper-o green nav-icon cyan"></i>
									<p>
										Chi Phí
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<!-- <li class="nav-item">
										<router-link to="/loai-chi-phi" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Loại Chi Phí</p>
										</router-link>
									</li> -->
									<li class="nav-item">
										<router-link to="/du-chi" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Dự Kiến Chi</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/chi-phi" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Chi Phí</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/loai-chi-phi" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Loại Chi Phí</p>
										</router-link>
									</li>
									<!-- <li class="nav-item">
										<router-link to="/chi-phi" class="nav-link">
											<i class="fa fa-circle-o nav-icon cyan"></i>
											<p>Chi Phí All</p>
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
										Báo cáo
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
											<p>Báo Cáo Lịch Giao</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bao-cao" class="nav-link">
											<i class="fa fa-line-chart nav-icon"></i>
											<p>Cơ hội bán hàng</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bang-xep-hang" class="nav-link">
											<i class="fa fa-sort-amount-desc nav-icon"></i>
											<p>Bảng xếp hạng SR</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bang-xep-hang-user" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>Bảng xếp hạng Sales</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bang-target-showroom" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>Bảng mục tiêu Sales</p>
										</router-link>
									</li>
									<li class="nav-item">
										<router-link to="/bang-target-sales" class="nav-link">
											<i class="fa fa-bar-chart-o nav-icon"></i>
											<p>Bảng mục tiêu SR</p>
										</router-link>
									</li>
									
									<li class="nav-item">
										@if(\Auth::user()->id == 9003 || \Auth::user()->id == 9074 || \Auth::user()->id == 9015)
										<router-link to="/bao-cao-nam" class="nav-link">
											<i class="fa  fa-area-chart nav-icon"></i>
											<p>Bảng Báo Cáo Năm</p>
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
											<p>Báo Cáo Online</p>
										</router-link>
									</li> -->
								</ul>
							</li>
							<li class="nav-item has-treeview">
								<a href="#" class="nav-link">
									<i class="nav-icon fa fa-circle-o"></i>
									<p>
										Bảng Tin Nội Bộ
										<i class="right fa fa-angle-left"></i>
									</p>
								</a>
								<ul class="nav nav-treeview">
									<li class="nav-item">
										<router-link to="/list-bang-tin" class="nav-link">
											<i class="fa fa-file-text-o nav-icon green"></i>
											<p>Nhập Nội Dung</p>
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
									Tài liệu hướng đẫn
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
	