<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
           .content {
            font-family: Dejavu Sans, sans-serif;
            }

        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="container" style="margin-top: 20px; margin-left: 10px">
                    <div class="row">
                        <div style="width: 30%; float: left;">
                            <p style="text-align: center;font-weight: 1000;font-size: 20px; margin-bottom: 0.5rem;">CÔNG TY TNHH</p> 
                            <p style="text-align: center;font-weight: 1000;font-size: 20px; margin-bottom: 0.5rem; margin-top:10px">NỘI THẤT ZIP</p> 
                            
                            @php
                                $stt = $dataBinding['stt'];
                                $date_expire = '2018-12-31 00:00:00';
                                $date = new DateTime($date_expire);
                                $now = new DateTime();

                                $datediff = date_diff($date, $now)->format('%a');
                                
                                $p = $datediff / 28;
                                $count = 13;
                                while($p - 13 >= 0) {
                                    $count++;
                                    if($p - 13 == 0) {
                                        $p = 1;
                                    } else {
                                        $p -= 13;
                                    }
                                }
                                if(ceil($p) < 10) {
                                    $pformated = '0'.ceil($p);
                                } else {
                                    $pformated = ceil($p);
                                }
                                

                                echo '<p style="text-align: left;font-size: 18px;font-style: italic; margin-left: 20px"> Số: BB/'.$pformated.$count.'-'.$stt.'</p>
                    </div></p>';
                            @endphp
                            
                         </div>
                        <div style="width: 70%; float: left;">
                                <p style="text-align: center;font-weight: 900;font-size: 20px; margin-bottom: 5px;"> CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM </p>
                                <p style="text-align: center;font-weight: 900;font-size: 20px; margin-top: 5px"> Độc Lập - Tự Do - Hạnh Phúc</p>
                                <p style="text-align: right;font-size: 18px;font-style: italic;"> Hà Nội,ngày {{\Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('d')}} tháng {{\Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('m')}}  năm {{\Carbon\Carbon::now('Asia/Ho_Chi_Minh')->format('Y')}}</p>
                         </div>
                         
                  </div>
                     <div style="margin-top: 0px;">
                            <p style="text-align: center;font-weight: 1000;font-size: 20px; clear: both;margin-bottom: 5px;"> BIÊN BẢN </p>
                            <p style="text-align: center;font-weight: 900;font-size: 18px;margin-top: 5px;"> CỦA PHÒNG QUẢN LÝ CHẤT LƯỢNG CÔNG TY TNHH NỘI THẤT ZIP </p>
                            <p style="text-align: center; font-style: italic;font-size: 18px"> V/v: Xử phạt vi phạm {{$dataBinding['ten_vi_pham']}} </p>
                    </div>
                    <div style="margin-left: 30px">
                            <p style="font-size: 14px"> - Căn cứ vào Điều lệ,Nội quy Công ty TNHH Nội thất zip;</p>
                            <p style="font-size: 14px"> - Căn cứ vào chức năng,quyền hạn của Phòng quản lý chất lượng của Công ty;</p>
                            <p style="font-size: 14px"> - Căn cứ vào yêu cầu kinh doanh,</p>
                            <p style="font-weight: 800;font-size: 14px"> Phòng QA tiến hành lập biên bản phạt tiền với nội dung sau:</p>
                            <p style="font-size: 14px"> 1.Đối tượng vị phạm: {{$dataBinding['user_name']}}</p>
                            <p style="font-size: 14px"> 2.Ngày vi phạm: {{$dataBinding['ngayvipham']}}</p>
                            <p style="font-size: 14px"> 3.Lý do phạt tiền: </p>
                            <p style="font-size: 14px; margin-left: 10px; white-space: pre-line;">{!! nl2br($dataBinding['tuong_trinh'])!!}</p>
                            {{-- <p style="font-size: 14px; margin-left: 10px; white-space: pre-line;">abc \\n dèf</p> --}}
                            <p style="font-size: 14px"> 4.Hình thức: </p>
                            @if ($dataBinding['money'] != null)
                                <p style="font-size: 14px"> + Phạt tiền: {{$dataBinding['money']}} đồng trừ vào lương {{$dataBinding['time_apply']}}</p>
                            @endif
                            
                            @if ($dataBinding['che_tai'] == 'Cảnh cáo' && $dataBinding['money'] == null|| $dataBinding['che_tai'] == 'Cảnh Cáo' && $dataBinding['money'] == null||$dataBinding['che_tai'] == 'cảnh cáo' && $dataBinding['money'] == null)
                                <p style="font-size: 14px"> + Cảnh cáo</p>
                            @endif

                            @if ($dataBinding['che_tai'] == 'Đình chỉ' && $dataBinding['money'] == null|| $dataBinding['che_tai'] == 'đình chỉ' && $dataBinding['money'] == null|| $dataBinding['che_tai'] == 'Đình Chỉ' && $dataBinding['money'] == null)
                                <p style="font-size: 14px"> + {{$dataBinding['che_tai']}}</p>
                                <p style="font-size: 14px"> + Thời gian áp dụng: {{$dataBinding['time_apply']}}</p>
                            @endif

                            @if ($dataBinding['che_tai'] == 'Đuổi việc' && $dataBinding['money'] == null|| $dataBinding['che_tai'] == 'đuổi việc' && $dataBinding['money'] == null|| $dataBinding['che_tai'] == 'Đuổi Việc' && $dataBinding['money'] == null)
                                <p style="font-size: 14px"> + {{$dataBinding['che_tai']}}</p>
                                <p style="font-size: 14px"> + Thời gian áp dụng: Bắt đầu từ {{$dataBinding['time_apply']}}</p>
                            @endif
                            
                            <p style="font-size: 14px"> + Thực hiện đọc kĩ và chấp hành đúng quy định đơn hàng mà Công ty đã ban hành.</p>
                    </div>
                    <div class="row" style="margin-top: 30px; display:flex; margin-left: 10px">
                       <div class="col-md-6" style="width:50%; float: left;">
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> Người vi phạm </p>
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> (Ký,họ tên) </p>
                       </div>
                       <div class="col-md-6" style="padding-left: 160px; width:50%; float: right;">
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> QA-Nội Thất Zip </p>
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> (Ký,họ tên) </p>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>