@php
echo '<?xml version="1.0" ?>';
$convmap = array(0x80, 0xffff, 0, 0xffff);
@endphp
<eExact>
<Accounts>
  @foreach($customers as $customer)
 <Account code="{{$customer->id}}" status="A" type="C">
 <Name>{{mb_encode_numericentity($customer->name, $convmap, 'UTF-8' )}}</Name>
 <Phone>{{$customer->phone}}</Phone>
 <VATNumber></VATNumber>
 <Email></Email>
 <Contacts>
   <Contact default="1" gender="V" status="A">
    <LastName>{{mb_encode_numericentity($customer->name, $convmap, 'UTF-8' )}}</LastName>
   <Title code="{{$customer->title}}"/>
   <Addresses>
   <Address type="V" desc="">
      <AddressLine1>{{mb_encode_numericentity($customer->address_line1, $convmap, 'UTF-8' )}}</AddressLine1>
      <Country code="VN"/>
      <Phone>{{$customer->phone}}</Phone>
      <Fax></Fax>
      <VATNumber></VATNumber>
     </Address>
   </Addresses>
   </Contact>
  </Contacts>
 <Debtor code="{{$customer->id}}"/>
 </Account>
 @endforeach
</Accounts>
</eExact>
