@extends('layouts.app')

@section('content')
<div class="container1">
  <div class="row justify-content-center">
    <div class="col-md-12">
      <div class="row"> 
        <div class="col-md-3">
          
        </div>
        <div class="search-date col-md-3">
          <label>Lọc ngày</label>
          {{ csrf_field() }}
          <input type="text" class="daterange" value="" name="datetimes" />
        </div>
        <div class="col-md-3">
          <label>Xuất file</label><br>
          <a class="btn btn-success" id="Export">Export to Excel</a>
        </div>
      </div>
      <div class="card">
        <div class="card-header">Xuất file Excell</div>
        <div class="card-body">
                    {{-- @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif
                    --}}
                    {{-- <form action="customer/nhap" method="POST" enctype="multipart/form-data"> Form bắt buộc phải có thuộc tính enctype thì mới up được file lên --}}
                      {{ csrf_field() }}
                      <div class="form-group">
                        <table class="table table-bordered" id="table2excel">
                          <thead>
                            {{-- <tr class="noExl" style="display: none;">
                              <td>Đơn Hàng</td>
                              <td>Sản Phẩm</td>
                              <td>Số Lượng</td>
                              <td>Gía Trước</td>
                              <td>Gía sau Ck</td>
                              <td>Ngày Giao</td>
                              <td>Ngày Ký Đơn</td>
                              <td>Kho</td>
                              <td>SR</td>
                            </tr> --}}
                            <tr class="noExl" style="display: none;">
                              <td>id</td>
                              <td>Sản Phẩm</td>
                              <td>Số Lượng</td>
                              <td>Gía/sp</td>
                              <td>Gía sau Ck/sp</td>
                              <td>Đơn hàng</td>
                              <td>Kho</td>
                              <td>Ngày giao</td>
                              <td>SR</td>
                              <td>Ngày kí</td>
                              <td>Cọc</td>
                              <td>Gía sau Ck</td>
                              <td>Vat</td>
                              <td>lđ</td>
                              <td>vc</td>
                              <td>ck</td>
                              <td>Giá trước ck/sp</td>
                              <td>Nhân Viên</td>
                              <td>điểm thưởng</td>
                            </tr>
                            <tr>
                              <td>id</td>
                              <td>Sản Phẩm</td>
                              <td>Số Lượng</td>
                              <td>Gía/sp</td>
                              <td>Gía sau Ck/sp</td>
                              <td>Đơn hàng</td>
                              <td>Kho</td>
                              <td>Ngày giao</td>
                              <td>SR</td>
                              <td>Ngày kí</td>
                              <td>Cọc</td>
                              <td>Gía sau Ck</td>
                              <td>Vat</td>
                              <td>lđ</td>
                              <td>vc</td>
                              <td>ck</td>
                              <td>Giá trước ck/sp</td>
                              <td>Nhân Viên</td>
                              <td>điểm thưởng</td>
                            </tr>
                          </thead>
                          <tbody id="fillod">
                            @if($a)
                            @foreach($a as $orders)
                            <tr>
                              <td>{{ $orders['ORDER_ID'] }}</td>
                              <td class="size_new">{{ $orders['ITEM'] }}</td>
                              <td>{{ $orders['QUANTITY'] }}</td>
                              <td>{{ $orders['PRICE'] }}</td>
                              <td>{{ $orders['AMOUNT'] }}</td>
                              <td>{{ $orders['so_number'] }}</td>
                              <td>{{ $orders['warehouse'] }}</td>
                              <td>{{ $orders['delivery_date'] }}</td>
                              <td>{{ $orders['costcenter'] }}</td>
                              <td>{{ $orders['start_date'] }}</td>
                              <td>{{ $orders['pre_pay'] }}</td>
                              <td>{{ $orders['amount'] }}</td>
                              <td>{{ $orders['vat'] }}</td>
                              <td>{{ $orders['fee_ld'] }}</td>
                              <td>{{ $orders['fee_vc'] }}</td>
                              <td>{{ $orders['qgg'] }}</td>
                              <td>{{ $orders['PRICE']*$orders['QUANTITY']  }}</td>
                              <td>{{ $orders['name']  }}</td>
                              <td>{{ $orders['POINT']*$orders['QUANTITY']  }} </td>
                            </tr>
                            @endforeach
                            @endif
                          </tbody>
                        </table>
                      </div>


                      <!-- <a class="btn btn-success" id="Export">Export to Excel</a>
                      {{-- href="{{ route('export_excel.excel') }}" --}}
                      <button type="reset" class="btn btn btn-danger btn-mleft">Hủy</button>
                    {{-- </form> --}} -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          <style type="text/css">
          .search-date{
            display: flex;
            padding: 10px;
            flex-direction: column;
          }
          .search-date input{
            border-radius: 5px;
            border: 1px solid #a99898d6;
            padding: 5px;
          }
          .daterangepicker .calendar {
            max-width: 350px;
          }
          .container1{
            margin: 0px 10px 0px 75px;
          }
          .size_new{
            font-size: 12px;
          }
        </style>
        <script type="text/javascript">
          $('.daterange').daterangepicker();


          $('input[name="datetimes"]').on('apply.daterangepicker', function(ev, picker) {
            var time    = $('input[name="datetimes"]').val();
            var fillter = '{{ route("export_excel.fillOrderDoanhThu") }}';
            $.ajax({
              data: {
                range : time,
              },
              url: fillter,
              type: 'GET',
              success: function(data) {
                if(data.status === true) {
                  $.bootstrapGrowl(data.message, { type: 'success' });
                  setTimeout(function(){
                   window.location.reload();
                 }, 200);
                }
                $("#fillod").html(data);
              },
            });
          });


  var  myfunction = function(){
  $("#table2excel").table2excel({
    // exclude CSS class
    exclude: ".noExl",
    name: "Worksheet Name",
    filename: "SomeFile", //do not include extension
    fileext: ".xls" // file extension
  });
}

//   // $("#table2excel").table2excel({
//   //   exclude: ".noExl",
//   //   name: "Worksheet Name",
//   //   filename: "SomeFile",
//   //   fileext: ".xls",
//   //   preserveColors: true
//   // });
//   // $("#table2excel").table2excel({
//   //   exclude: ".noExl",
//   //   name: "Worksheet Name",
//   //   filename: "SomeFile",
//   //   fileext: ".xls",
//   //   exclude_img: true,
//   //   exclude_links: true,
//   //   exclude_inputs: true
//   // });  
//   console.log('q')
// };
$("#Export").on('click',myfunction);
// $('input[name="datetimes"]').on('apply.daterangepicker', function(ev, picker) {
//   $("#Export").on('click', function() {
//     var start    = picker.startDate.format('YYYY-MM-DD');
//     var end    = picker.endDate.format('YYYY-MM-DD');
//     //   console.log(picker.startDate.format('DD-MM-YYYY'));
//     window.location ='/orders2/export_excel2?start='+start+'&end='+end;
//   });
// });
</script>

@endsection
