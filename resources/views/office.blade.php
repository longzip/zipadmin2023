<script type="text/javascript" src="//cdn.jsdelivr.net/jquery/1/jquery.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<?php
$data = json_decode($data);
$info = $data[0];
$start = date('d/m/Y', strtotime($info->start_date));


if($info->warehouse === 'A_MB'){
    $kho = 'KHO_AMB';
}else{
    $kho = 'KHO_AMN';
}
if($info->vat == 0){
    $vat =  '0 %';
}else{
    $vat =  '10 %';
}

$dh = curl_init();
foreach($info->orderLine as $key => $d){
    $detail[$key]['product_id'] = $d->item;
    $detail[$key]['price'] = $d->price;
    $detail[$key]['amount'] = $d->quantity;
    $detail[$key]['sale_off'] = $d->discount;
    $detail[$key]['money_sale_off'] = $d->price - $d->amount;
    $detail[$key]['vat_id'] = 'Miễn thuế';
}
$tiencoc[0]['month'] = 0;
$tiencoc[0]['date'] = $start;
$tiencoc[0]['price'] = $info->pre_pay;
$tiencoc[0]['desc'] = 'Tiền Cọc';
$curl = curl_init();
// print_r($tiencoc);
curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://zip.1office.vn/api/customer/customer/insert?access_token=123561329260618a3ac2874&type=C%C3%A1%20nh%C3%A2n&code='.$info->deliver_to.'&name='.$info->customer_name.'&phones='.$info->customer_phone.'&fountain_head_id='.$info->costcenter,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
  ),
));

$response = curl_exec($curl);

curl_close($curl);


$response = json_decode($response);
$check_kh = $response;
if($check_kh->error == 1){
    print_r($check_kh->message);
}else{
    echo "Thêm khách hàng thành công";
}


// .'&customer_charge='.$info->user_name
curl_setopt_array($dh, array(
  CURLOPT_URL => 'https://zip.1office.vn/api/sale/purchase/insert?access_token=123561329260618a3ac2874&currency_unit=VND&code='.$info->so_number.'&date_sign='.$start.'&customer_id='.$info->deliver_to.'&desc='.$info->note.'&total_price_internal='.$info->pre_pay.'&detail='.json_encode($detail).'&payment='.json_encode($tiencoc).'&date_sign='.$start.'&inventory_id='.$kho.'&vat_id='.$vat,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
  ),
));

$res = curl_exec($dh);

curl_close($dh);
echo "<br/>";

$res = json_decode($res);
$check_dh = $res;
if($check_dh->error == 1){
    print_r($check_dh->message);
}else{
    echo "Thêm đơn hàng thành công";
}

// echo "<br/>";
// echo 'payment: '.json_encode($tiencoc);
// echo "<br/>";
// echo 'total_price_internal: '.$info->pre_pay;
// echo "<br/>";
// echo 'inventory_id: '.$kho;



?>

<script>
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;
    
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
    
            if (sParameterName[0] === sParam) {
                return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
        return false;
    };
	$( document ).ready(function() {
	
		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        }); 
		$.ajax({
	         method: "GET",
	         url: "/api/updateorderoff",
	         data: {
		        "so": getUrlParameter('name'),
	         }
	     }).done(function( msg ) {
	        
	     });
	
	});
</script>