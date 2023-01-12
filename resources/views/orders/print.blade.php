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
	<meta name="csrf-token" content="n7AHd2sgKJFlqwl9aVOLl3kHZJhbIERsHsGtfx69">
	<title>ZIP</title>
	<link rel="stylesheet" href="/css/app.css">
</head>
<body onload="window.print();">

<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h2 class="page-header">
          <i class="fa fa-globe"></i> Công ty TNHH Nội thất ZIP
          <small class="float-right">Ngày tạo: {{ $order->created_date }}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        From
        <address>
          <strong>ZIP.</strong><br>
          Địa chỉ: Số 5, lô A, ngõ 172 phố Vũ Hữu<br>
          quận Thanh Xuân, Hà Nội.<br>
          Điện thoại: (84) 4 3543 0021<br>
          Email: cskh@noithatzip.com
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        To
        <address>
          <strong>{{$customer->name}}</strong><br>
          {{$customer->address_line1}}<br>
          <br>
          Điện thoại: {{$customer->phone}}<br>
          Email:
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Số #{{ $order->so_number }}</b><br>
        <br>
        <b>Showroom:</b> {{ $order->delivery_date }}<br>
        <b>Ngày giao hàng:</b> {{ $order->delivery_date }}<br>
        <b>Tạo bởi:</b> MNV {{ $order->user_id }}
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Qty</th>
            <th>Product</th>
            <th>Serial #</th>
            <th>Description</th>
            <th>Subtotal</th>
          </tr>
          </thead>
          <tbody>
          @foreach($order->orderlines as $orderline)
          <tr>
            <td>{{$orderline->item}}</td>
            <td>{{$orderline->quantity}}</td>
            <td>{{$orderline->price}}</td>
            <td>{{$orderline->discount}}</td>
            <td>{{$orderline->amount}}</td>
          </tr>
          @endforeach
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-6">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Khách đặt cọc:</th>
              <td>{{ $order->pre_pay }}</td>
            </tr>
          </table>
        </div>

      </div>
      <!-- /.col -->
      <div class="col-6">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Tổng tiền:</th>
              <td>{{ $order->subtotal }}</td>
            </tr>
            <tr>
              <th>VAT</th>
              <td>{{ $order->vat }}</td>
            </tr>
            <tr>
              <th>Phí vận chuyển + Lắp đặt:</th>
              <td>Miễn phí</td>
            </tr>
            <tr>
              <th>Thanh toán:</th>
              <td>{{ $order->amount }}</td>
            </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->
	<script src="/js/app.js"></script>
</body>
</html>
