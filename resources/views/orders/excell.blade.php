<table>
<tbody>
<tr>
<td>Nội thất ZIP</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td colspan="5">BẢNG B&Aacute;O GI&Aacute;</td>
</tr>
<tr>
<td>K&iacute;nh gửi:</td>
<td>Qu&yacute; kh&aacute;ch h&agrave;ng</td>
<td>&nbsp;</td>
<td>#</td>
<td>{{$order->so_number}}</td>
</tr>
<tr>
<td>Người nhận:</td>
@php
$customer = App\Customer::find($order->deliver_to);
@endphp
<td>{{$customer->name}}</td>
<td>&nbsp;</td>
<td>Ng&agrave;y:</td>
<td>{{$order->delivery_date}}</td>
</tr>
<tr>
<td>Mobile:</td>
<td>{{$customer->phone}}</td>
<td>&nbsp;</td>
<td>SR</td>
<td>{{$order->costcenter}}</td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td>T&ecirc;n sản phẩm</td>
<td>Số lượng</td>
<td>Đơn gi&aacute;</td>
<td>CK</td>
<td>Th&agrave;nh tiền</td>
</tr>
@php
    $lineNo=0;
@endphp
@foreach($order->orderlines as $orderline)
<tr>
<td>{{$orderline->item}}</td>
<td>{{$orderline->quantity}}</td>
<td>{{$orderline->price}}</td>
<td>{{$orderline->discount}}</td>
<td>{{$orderline->amount}}</td>
</tr>
@endforeach
<tr>
<td colspan="4">Tổng</td>
<td>{{$order->subtotal}}</td>
</tr>
<tr>
<td colspan="4">VAT</td>
<td>{{$order->vat}}</td>
</tr>
<tr>
<td colspan="4">Th&agrave;nh tiền</td>
<td>{{$order->amount}}</td>
</tr>
</tbody>
</table>