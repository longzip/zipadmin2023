<!doctype html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        .content {
            font-family: Dejavu Sans, sans-serif;
        }
        body{
            width: 100%;
            
        }
    </style>
</head>
<body>
    <div class="flex-center position-ref full-height">
        <div class="content">
            <div class="container">
                <div class="row">
                    <div style="width: 30%; float: left;">
                        <p style="text-align: center;font-weight: 1000;font-size: 20px; margin-bottom: 0.5rem;text-transform:uppercase;">công ty tnhh</p>
                        <p style="text-align: center;font-weight: 1000;font-size: 20px;text-transform:uppercase; margin-bottom: 0.5rem; margin-top:10px">nội thất zip</p>

                    </div>
                    <div style="width: 70%; float: left;">
                        <p style="text-align: center;font-weight: 900;font-size: 20px; margin-bottom: 5px;text-transform:uppercase;"> CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM </p>
                        <p style="text-align: center;font-weight: 900;font-size: 20px; margin-top: 5px;text-transform:uppercase;">Độc Lập - Tự Do - Hạnh Phúc</p>
                        <p style="text-align: right;font-size: 18px;font-style: italic;"> Hà Nội,ngày {{\Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('d')}} tháng {{\Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('m')}} năm {{\Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('Y')}}</p>
                    </div>

                </div>
                <div style="margin-top: 0px;">
                    @if($dataBinding["loai_nghi"] != 3)
                    <p style="text-align: center;font-weight: 1000;font-size: 20px; clear: both;margin-bottom: 5px;text-transform:uppercase;"> ĐƠN XIN NGHỈ PHÉP</p>
                    @endif
                    @if($dataBinding["loai_nghi"] == 3 )
                    <p style="text-align: center;font-weight: 1000;font-size: 20px; clear: both;margin-bottom: 5px;text-transform:uppercase;"> ĐƠN XIN NGHỈ VIỆC</p>
                    @endif
                    <p style="text-align: center;font-weight: 500;font-size: 18px;margin-top: 5px;"> Kính gửi:Ban Giám Đốc Công Ty TNHH Nội thất ZIP </p>
                </div>
                <div style="margin-left: 30px">
                    <p style="font-size: 14px">Tên tôi là:{{$dataBinding['user_name']}}</p>
                    <p style="font-size: 14px">Vị trí: {{$dataBinding['role_name']}}</p>
                    <p style="font-size: 14px">Số ngày xin nghỉ:
                        @if($dataBinding['so_ngay_nghi'] != null)
                            <span>{{$dataBinding['so_ngay_nghi']}} ngày</span>
                        @endif
                        @if($dataBinding['so_ngay_nghi'] == null)
                           <span style="font-size: 14px">không thời hạn</span>
                        @endif
                    <p style="font-size: 14px">Lý do: {{$dataBinding['ly_do']}}</p>
                    <p style="font-size: 14px">loại nghỉ:
                        <i class="fa {{$dataBinding["loai_nghi"] == 1 ? 'fa-chevron-circle-down' : 'fa fa-circle'}}"></i>
                        <label>nghỉ không lương </label>
                        <i class="fa {{$dataBinding["loai_nghi"] == 2 ? 'fa-chevron-circle-down' : 'fa fa-circle'}}"></i>
                        <label>nghỉ chế độ </label>
                        <i class="fa {{$dataBinding["loai_nghi"] == 3 ? 'fa-chevron-circle-down' : 'fa fa-circle'}}"></i>
                        <label>nghỉ việc </label>

                    </p>
                    <p style="font-size: 14px">Thời gian bắt đầu nghỉ: {{date('H:i d/m/Y',strtotime($dataBinding['ngay_gui_don']))}}
                        <span>
                            @if($dataBinding['end_date'] != null)
                            đến ngày:  {{date('H:i d/m/Y',strtotime($dataBinding['end_date']))}}
                        @endif
                        @if($dataBinding['end_date'] == null)
                           <p> </p>
                        @endif
                        </span>
                    </p>
                    <p style="font-size: 14px">Công việc bàn giao: {{$dataBinding['cv_ban_giao']}}</p>
                    <p style="font-size: 14px">Người nhận công việc bàn giao:
                    @if($dataBinding['nguoi_nhan_cv'] != null)
                        {{$dataBinding['nguoi_nhan_cv']}}
                    @endif
                    @if($dataBinding['nguoi_nhan_cv'] == null)
                        <span style="font-size: 14px">Không</span>
                    @endif
                    </p>
                    <p style="font-size: 14px">Số điện thoại: {{$dataBinding['phone']}}</p>
                    @if($dataBinding['end_date'] != null)
                            <p style="font-size: 14px">Tôi xin hứa sẽ có mặt tại công ty đúng hẹn,nếu sai tôi xin chịu hoàn toàn trách nhiệm.</p>
                    @endif
                    <p style="font-size: 14px">Tôi xin chân thành cảm ơn!</p>

                </div>
                <div class="row" style="width: 100%;">
                        <div class="col-md-3" style="width: 25%;float: left;">
                            <p style="text-align: center; font-weight: 500;font-size: 16px"> Phòng HC-NS </p>
                            <p style="text-align: center; font-weight: 500;font-size: 16px"> (Ký,họ tên) </p>
                        </div>
                        <div class="col-md-3" style="width: 25%;float: left;">
                            <p style="text-align: center; font-weight: 500;font-size: 16px"> Trưởng bộ phận </p>
                            <p style="text-align: center; font-weight: 500;font-size: 16px"> (Ký,họ tên) </p>
                        </div>
                        <div class="col-md-3" style="width: 25%;float: left;">
                            <p style="text-align: center; font-weight: 500;font-size: 16px"> Người nhận BG </p>
                            <p style="text-align: center; font-weight: 500;font-size: 16px"> (Ký,họ tên) </p>
                        </div>
                        <div class="col-md-3" style="width: 25%;float: left;">
                            <p style="text-align: center; font-weight: 500;font-size: 16px"> Người làm đơn </p>
                            <p style="text-align: center; font-weight: 500;font-size: 16px"> (Ký,họ tên) </p>
                        </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>