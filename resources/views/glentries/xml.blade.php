@php
echo '<?xml version="1.0" ?>';
$customer = App\Customer::find($order->deliver_to);
$user = App\User::find($order->user_id);
$resource = $user->resource;
@endphp
<eExact xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="eExact-Schema.xsd">
 <GLEntries>
 <GLEntry>
  <DocumentDate>{{ $order->created_at}}</DocumentDate>
  <Journal code="600">
  </Journal>
  <FinEntryLine number="1" type="N" subtype="N">
   <GLAccount code="11301">
   </GLAccount>
    <Description>Đặt cọc cho đơn hàng {{ $order->so_number}}</Description>
   <Costcenter code="{{ $resource->costcenter }}">
   </Costcenter>
   <Debtor code="{{ $customer->id }}" number=" {{ $customer->id }}" type="C">
   </Debtor>
   <Resource code="{{ $user->username }}">
   </Resource>
   <Project code="{{ $order->so_number }}">
   </Project>
   <Amount>
    <Currency code="VND"/>
    <Debit>{{ $order->pre_pay }}</Debit>
    <Credit>0</Credit>
   </Amount>
   <FinReferences TransactionOrigin="N">
    <UniquePostingNumber>0</UniquePostingNumber>
    <YourRef>Đơn hàng {{ $order->so_number}}</YourRef>
    <DocumentDate>{{ $order->created_at}}</DocumentDate>
   </FinReferences>
  </FinEntryLine>
  <FinEntryLine number="2" type="N" subtype="N">
   <Date>{{ $order->created_at}}</Date>
   <GLAccount code="33605">
    <Description>Phải thu nội bộ (tiền cọc)</Description>
   </GLAccount>
    <Description>Đặt cọc cho đơn hàng {{ $order->so_number}}</Description>
   <Costcenter code="{{ $resource->code }}">
   </Costcenter>
   <Debtor code="{{ $customer->id }}" number=" {{ $customer->id }}" type="C">
   </Debtor>
   <Resource number="1" code="Administrator">
   </Resource>
   <Project code="{{ $order->so_number }}">
   </Project>
   <Amount>
    <Currency code="VND"/>
    <Debit>0</Debit>
    <Credit>{{ $order->pre_pay }}</Credit>
   </Amount>
   <FinReferences TransactionOrigin="I">
    <UniquePostingNumber>0</UniquePostingNumber>
    <YourRef>Đơn hàng {{ $order->so_number}}</YourRef>
    <DocumentDate>{{ $order->created_at}}</DocumentDate>
   </FinReferences>
  </FinEntryLine>
 </GLEntry>
</GLEntries>
</eExact>
