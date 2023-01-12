@php
echo '<?xml version="1.0" ?>';
$customer = App\Customer::find($order->deliver_to);
$user = App\User::find($order->user_id);
$resource = 9001;
switch ($order->warehouse)
{
    case 'A_MB' :
        $resource = 9001;
        break;
    case 'A_MT':
        $resource = 9002;
        break;
    case 'A_MN':
        $resource = 9003;
        break;
    case 'A_TA':
        $resource = 9004;
        break;
    default:
        $resource = 9001;
        break;
}
@endphp
<!--  -->

<eExact xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="eExact-Schema.xsd">
  <Orders>
    <Order type="V" number="{{$order->so_number }}" partialdelivery="1" confirm="0" invoicemethod="H">
      <Description>{{ $order->description }}</Description>
      <YourRef>{{ $order->your_ref }}</YourRef>
      <Currency code="VND" />
      <CalcIncludeVAT></CalcIncludeVAT>
      <Resource number="{{ $resource }}" code="{{ $resource }}" />
      <OrderedBy>
        <Debtor code="{{ $order->deliver_to  }}" number="{{ $order->deliver_to  }}" type="C">
          <Name>{{$customer->name}}</Name>
        </Debtor>
        <Address>
          <Addressee>
            <Name></Name>
            <Title code="MR" />
            <JobDescription>--</JobDescription>
          </Addressee>
          <AddressLine1>{{$customer->address_line1}}</AddressLine1>
          <AddressLine2 />
          <AddressLine3 />
          <PostalCode />
          <City />
          <Country code="VN" />
          <Phone>{{$customer->phone}}</Phone>
        </Address>
        <Date>{{ $customer->created_at  }}</Date>           
      </OrderedBy>
      <DeliverTo>
        <Debtor code="{{ $order->deliver_to  }}" number="{{ $order->deliver_to  }}" type="C">
          <Name>{{$customer->name}}</Name>
        </Debtor>
        <Address>
          <Addressee>
            <Name></Name>
            <Title code="MR" />
            <JobDescription>--</JobDescription>
          </Addressee>
          <AddressLine1>{{$customer->address_line1}}</AddressLine1>
          <AddressLine2 />
          <AddressLine3 />
          <PostalCode />
          <City />
          <Country code="VN" />
          <Phone>{{$customer->phone}}</Phone>
        </Address>
        <Date>{{ $customer->created_at  }}</Date>
      </DeliverTo>
      <InvoiceTo>
        <Debtor code="{{ $order->deliver_to  }}" number="{{ $order->deliver_to  }}" type="C">
          <Name>{{$customer->name}}</Name>
        </Debtor>
        <Address>
          <Addressee>
            <Name></Name>
            <Title code="MR" />
            <JobDescription>--</JobDescription>
          </Addressee>
          <AddressLine1>{{$customer->address_line1}}</AddressLine1>
          <AddressLine2 />
          <AddressLine3 />
          <PostalCode />
          <City />
          <Country code="VN" />
          <Phone>{{$customer->phone}}</Phone>
        </Address>
        <Date>{{ $customer->created_at  }}</Date>
      </InvoiceTo>
      <Warehouse code = "{{ $order->warehouse }}">
      </Warehouse>
      <Costcenter code="{{ $order->costcenter }}">
      </Costcenter>
      @php
        $lineNo=0;
        if ($order->vat > 0){
            $vat = 'V10';
        }else{
            $vat = '0';
        }
        $recipe = 0;
        switch ($order->warehouse)
        {
            case 'A_MB' :
                $recipe = 2;
                break;
            case 'A_MT':
                $recipe = 3;
                break;
            case 'A_MN':
                $recipe = 4;
                break;
            case 'A_TA':
                $recipe = 5;
                break;
            default:
                $recipe = 1;
                break;
        }
      @endphp
      @foreach($order->orderlines as $orderline)
      <OrderLine lineNo="{{++$lineNo}}">
        <Item code="{{$orderline->item}}" type="S" />
        <Quantity>{{$orderline->quantity}}</Quantity>
        <Price type="S">
          <Currency code="VND" />
          <Value>{{$orderline->price}}</Value>
          <VAT code="{{$vat}}" />    
        </Price>
        <Discount>
          <Percentage>{{$orderline->discount}}</Percentage>
        </Discount>
        <Delivery>
         <Date>{{ $order->delivery_date }}</Date>
        </Delivery>
        <Recipe version="{{$recipe}}" />
      </OrderLine>
      @endforeach
      @if ($order->fee_kh > 0)
      <OrderLine lineNo="{{++$lineNo}}">
        <Item code="FEE_KH" type="P" />
        <Quantity>1</Quantity>
        <Price type="S">
          <Currency code="VND" />
          <Value>{{$order->fee_kh}}</Value>
          <VAT code="{{$vat}}" />    
        </Price>
        <Discount>
          <Percentage>0</Percentage>
        </Discount>
      </OrderLine>
      @endif
      @if ($order->fee_vc > 0)
      <OrderLine lineNo="{{++$lineNo}}">
        <Item code="FEE_VC" type="P" />
        <Quantity>1</Quantity>
        <Price type="S">
          <Currency code="VND" />
          <Value>{{$order->fee_vc}}</Value>
          <VAT code="{{$vat}}" />    
        </Price>
        <Discount>
          <Percentage>0</Percentage>
        </Discount>
      </OrderLine>
      @endif
      @if ($order->fee_ld > 0)
      <OrderLine lineNo="{{++$lineNo}}">
        <Item code="FEE_LD" type="P" />
        <Quantity>1</Quantity>
        <Price type="S">
          <Currency code="VND" />
          <Value>{{$order->fee_ld}}</Value>
          <VAT code="{{$vat}}" />    
        </Price>
        <Discount>
          <Percentage>0</Percentage>
        </Discount>
      </OrderLine>
      @endif
      @if ($order->qgg > 0)
      <OrderLine lineNo="{{++$lineNo}}">
        <Item code="QGG" type="P" />
        <Quantity>1</Quantity>
        <Price type="S">
          <Currency code="VND" />
          <Value>-{{$order->qgg}}</Value>
          <VAT code="{{$vat}}" />    
        </Price>
        <Discount>
          <Percentage>0</Percentage>
        </Discount>
      </OrderLine>
      @endif
    </Order>
  </Orders>
</eExact>
