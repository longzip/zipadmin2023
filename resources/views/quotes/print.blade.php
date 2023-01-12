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
          <small class="float-right">Ngày tạo: {{ $quote->created_at }}</small>
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
        @php
          $contact = $quote->quoteable;
          $user = $quote->creator;
        @endphp
        <address>
          <strong>{{$contact->name}}</strong><br>
          {{$contact->address}}<br>
          <br>
          Điện thoại: {{$contact->phone}}<br>
          Email:
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Số #</b><br>
        <br>
        <b>Showroom: {{ $contact->costcentersList()->first()->name}}</b> <br>
        <b>Ngày giao hàng:</b> <br>
        <b>Tạo bởi:</b> {{ $user->id }} - {{ $user->name }}
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
            <th>Mã sản phẩm</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Đơn giá</th>
            <th>Chiết khấu</th>
            <th>Thành tiền</th>
          </tr>
          </thead>
          <tbody>
          @foreach($quote->productLines as $quoteline)

          <tr>
            @php
              $product = \App\Product::find($quoteline->product_id);
            @endphp
            <td>{{$product->code}}</td>
            <td>{{$product->name}}</td>
            <td>{{$quoteline->quantity}}</td>
            <td>{{ number_format($quoteline->price , 0, '.', ' ')}}</td>
            <td>{{ number_format($quoteline->discount , 0, '.', ' ')}}</td>
            <td>{{ number_format($quoteline->amount , 0, '.', ' ')}}</td>
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
                <td>{{ number_format($quote->subtotal, 0, '.', ' ')}}</td>
            </tr>
            <tr>
                <th>Giảm giá</th>
                <td>{{ number_format($quote->qgg, 0, '.', ' ') }}</td>
            </tr>
            <tr>
                <th>Phí vận chuyển</th>
                <td>{{ number_format($quote->fee_vc, 0, '.', ' ') }}</td>
            </tr>
            <tr>
                <th>Phí lắp đặt</th>
                <td>{{ number_format($quote->fee_ld, 0, '.', ' ') }}</td>
            </tr>
            <tr>
                <th>VAT (10%)</th>
                <td>{{ number_format($quote->vat , 0, '.', ' ')}}</td>
            </tr>
            <tr>
                <th>Thanh toán:</th>
                <td>{{ number_format($quote->total , 0, '.', ' ')}}</td>
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
