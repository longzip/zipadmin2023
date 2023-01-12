@php
echo '<?xml version="1.0" ?>';
@endphp
 <eExact xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="eExact-Schema.xsd">
<Orders>
    @foreach($orders as $order)
      <Order type="V" number="{{ $order->so_number }}" partialdelivery="1" confirm="0">
          <Description>{{ $order->description }}</Description>
          <YourRef>{{ $order->your_ref }}</YourRef>
          <Resource code="{{ $order->resource }}"/>
          <OrderedBy>
              <Debtor code="{{ $order->ordered_by }}" type="C" /> 
          </OrderedBy>
          <DeliverTo>
              <Debtor code="{{ $order->deliver_to  }}" type="C" />
          </DeliverTo>
          <InvoiceTo>
              <Debtor code="{{ $order->invoice_to  }}" type="C" />
          </InvoiceTo>
          <Warehouse code = "{{ $order->warehouse }}" />
          <DeliveryMethod code="DB" type="O" />
          <Costcenter code="{{ $order->costcenter }}"/>
        @php
            $lineNo=0;
        @endphp

        @foreach($order->orderlines as $orderline)
          <OrderLine lineNo="{{++$lineNo}}">
              <Item code="{{$orderline->item}}" type="S" />
              <Quantity>{{$orderline->quantity}}</Quantity>
              <Price type="S">
                <Currency code="VND" />
                <Value>{{$orderline->price}}</Value>
                <VAT code="0" />    
              </Price>
              <Discount>
                <Percentage>{{$orderline->discount}}</Percentage>
              </Discount>
            </OrderLine>
          @endforeach
      </Order>
    @endforeach
  </Orders>
</eExact>
