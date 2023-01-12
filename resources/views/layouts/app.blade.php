<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>ZIP</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
    <script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
</head>
<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
              </li>
            </ul>
            <!-- SEARCH FORM -->
            <form class="form-inline ml-3">
              <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-navbar" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </div>
              </div>
            </form>
        </nav>
        <!-- Main Sidebar Container -->
          <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="/docs" class="brand-link">
                <img src="{{asset('img/logozip-new.png')}}" alt="ZIP Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
              <span class="brand-text font-weight-light">TÀI LIỆU ZIP</span>
            </a>

             <!-- Sidebar -->
            <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{asset('img/man.png')}}" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                      @auth
                        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
                      @endauth
                    </div>
                </div>
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item has-treeview menu-open">
                        <a href="/docs/1qN7O3OKV3iOfzVEKyThfMe11nlEroaDf" class="nav-link">
                          <i class="nav-icon fa fa-book"></i>
                          <p>
                            HƯỚNG ĐẪN APP
                          </p>
                        </a>
                      </li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-database red"></i>
                          <p>
                            DATA
                            <i class="right fa fa-angle-left"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="/docs/1Cn5ENCDp_6FMEHu5uqzLhLSAHuD3ojWd" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Ảnh</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="http://chien.noithatzip.cf/zip13a/Video/" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Video</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/11Txj36j21UbXjxWeDD2iMDO5zQ2XgVjK" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Tài liệu hướng dẫn</p>
                            </a>
                          </li>
                        </ul>
                      </li>
                      <li class="nav-header">QUY TRÌNH, QUY ĐỊNH CÁC BỘ PHẬN</li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book text-info"></i>
                          <p>
                            Khối hỗ trợ kinh doanh
                            <i class="right fa fa-angle-left"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="/docs/1euVTvbpVlOMZWeGGroWEBKRQPwvuR3wT" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Admin</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1-Vz8SbtDVhAAWMCgLSoxEopXzbk8RGHb" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Nhân sự</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1Kk7K9w0UIg-Fz4p8ZL1OSuOIdvPZ3TTH" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>IT</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1Bh0TNu7cU5cKbOko_9ipiPiBQAi_giPo" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Thiết Kế</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1hAugv2diM8QXDSVtMOT8EPKI6hdlb43e" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Cơ sở vật chất</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1x_Q5pLgHu6G6q6d-6tc91tEtZWeAq8U8" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Kế Toán</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1YPDSWtqXlWzN8c3KHLYI6XX6JeSSINSd" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Giám sát</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1WNBWfhYW2mtbXxoikSPt5HEDKSWr7b-z" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Kiểm soát nội bộ</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1jKuZmE1gbH3obDiwNxXbZhTzMTtI5_Va" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Thi Công</p>
                            </a>
                          </li>
                        </ul>
                      </li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book text-info"></i>
                          <p>
                            Khối trực tiếp kinh doanh
                            <i class="right fa fa-angle-left"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>ZIPPO</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>ASM</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Showroom</p>
                            </a>
                          </li>
                        </ul>
                      </li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book text-info"></i>
                          <p>
                            Văn phòng hỗ trợ sản xuất
                          </p>
                        </a>
                      </li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book text-info"></i>
                          <p>
                            Khối trực tiếp sản xuất
                            <i class="right fa fa-angle-left"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Sơn nhám</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Cơ khí</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Mộc</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Gỗ tự nhiên</p>
                            </a>
                          </li>
                        </ul>
                      </li>

                      <li class="nav-header">QUY ĐỊNH CÁC BỘ PHẬN</li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book text-warning"></i>
                          <p>
                            Khối hỗ trợ kinh doanh
                            <i class="right fa fa-angle-left"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="/docs/19bI6-4Uj-ifjvMoWMZSKuY5UnJmf6XGV" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Admin</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1OybyO6h1epDjaqLpxiRnL7zSIrBJIfZr" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Nhân sự</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1f7uNRUzJgKTDsHicXQa8mwZlkPRHKFfs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>IT</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/13HVMysDbLjnxJP2NJk6yqsQ7hghLSfAC" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Thiết Kế</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1Pztu1ryUA2FOZgIlsHlhk1UkvozRHwri" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Cơ sở vật chất</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1P7sVxmfJHOXl3qPVuNasGSg4elw-Smiv" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Kế Toán</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1OiLwIE1kEUvu0Cxd3wWjJRVGtGamIFyN" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Giám sát</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1QYxVtRqhBwXqh4qlmYOdAX1cqFCxvM8E" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Kiểm soát nội bộ</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1OceqUMgy0jZU4nb7pogwKDC7Kw3vNfKm" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Thi Công</p>
                            </a>
                          </li>
                        </ul>
                      </li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book text-warning"></i>
                          <p>
                            Khối trực tiếp kinh doanh
                            <i class="right fa fa-angle-left"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>ZIPPO</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>ASM</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Showroom</p>
                            </a>
                          </li>
                        </ul>
                      </li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book text-warning"></i>
                          <p>
                            Văn phòng hỗ trợ sản xuất
                          </p>
                        </a>
                      </li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book text-warning"></i>
                          <p>
                            Khối trực tiếp sản xuất
                            <i class="right fa fa-angle-left"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Sơn nhám</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Cơ khí</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Mộc</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Gỗ tự nhiên</p>
                            </a>
                          </li>
                        </ul>
                      </li>
                      <li class="nav-header">MÔ TẢ CÔNG VIỆC</li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book  text-danger"></i>
                          <p>
                            Khối hỗ trợ kinh doanh
                            <i class="right fa fa-angle-left"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="/docs/1Rhu3oBrnlECmysMW-6xp1P6rCnKvRXtm" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Admin</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1JHWgMMErCyqUuZjPtNq_ehaQUJPG8oKF" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Nhân sự</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/15n2f7Nmv8iZ93F_9xXZ15r6BNi0Dh33O" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>IT</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1kZDEWsqulENsb7OV3RV4fe_X_LGeFzGh" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Thiết Kế</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1aUKI2X69xzc9tbUqI1Z1_n8_9-N7TjgE" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Cơ sở vật chất</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/11leBlFDEuJyhrxEn6f88TlSLgpq_zDB4" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Kế Toán</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1L0d_UyRhPW1JV4VcNV5Wbv3Nyn2fhbq_" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Giám sát</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1nMP3QZ_gk27Bc4DUHf8dMVlZo5jHpr29" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Kiểm soát nội bộ</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs/1bXcDgzwo5Eg7nSz8lrJV4iwxrIeYhtPD" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Thi Công</p>
                            </a>
                          </li>
                        </ul>
                      </li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book  text-danger"></i>
                          <p>
                            Khối trực tiếp kinh doanh
                            <i class="right fa fa-angle-left"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>ZIPPO</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>ASM</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Showroom</p>
                            </a>
                          </li>
                        </ul>
                      </li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book  text-danger"></i>
                          <p>
                            Văn phòng hỗ trợ sản xuất
                          </p>
                        </a>
                      </li>
                      <li class="nav-item has-treeview menu-open">
                        <a href="/docs" class="nav-link">
                          <i class="nav-icon fa fa-book  text-danger"></i>
                          <p>
                            Khối trực tiếp sản xuất
                            <i class="right fa fa-angle-left"></i>
                          </p>
                        </a>
                        <ul class="nav nav-treeview">
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Sơn nhám</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Cơ khí</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Mộc</p>
                            </a>
                          </li>
                          <li class="nav-item">
                            <a href="/docs" class="nav-link">
                              <i class="fa fa-eye" aria-hidden="true"></i>
                              <p>Gỗ tự nhiên</p>
                            </a>
                          </li>
                        </ul>
                      </li>
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
            </div>
        </aside>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="{{asset('js/app.js')}}"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://www.jqueryscript.net/demo/Export-Html-Table-To-Excel-Spreadsheet-using-jQuery-table2excel/src/jquery.table2excel.js"></script>
</body>
</html>
