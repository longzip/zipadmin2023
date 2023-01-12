@php
echo '<?xml version="1.0" ?>';
$customer = App\Customer::find($order->deliver_to);
@endphp
<eExact xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="eExact-Schema.xsd">
  <Accounts>
    <Account code="{{ $customer->id }}" status="A" type="C">
      <Name>{{ \App\Common::khongdau($customer->name) }}</Name>
      <Phone>{{ $customer->phone }}</Phone>
      <PhoneExt></PhoneExt>
      <Fax></Fax>
      <Email></Email>
      <HomePage></HomePage>
      <Contacts>
        <Contact default="1" gender="M" status="A">
          <LastName>{{ \App\Common::khongdau($customer->name) }}</LastName>
          <FirstName></FirstName>
          <MiddleName></MiddleName>
          <Initials></Initials>
          <Title code="MR">
              <Description>Mr.</Description>
              <Salutation>Dear Mister</Salutation>
              <Abbreviation>Mr.</Abbreviation>
          </Title>
          <Addresses>
              <Address type="D" desc="">
              <AddressLine1>{{ \App\Common::khongdau($customer->address_line1) }}</AddressLine1>
              <AddressLine2></AddressLine2>
              <AddressLine3></AddressLine3>
              <PostalCode></PostalCode>
              <City></City>
              <Country code="VN"/>
              <Phone>{{ $customer->phone }}</Phone>
              <Fax></Fax>
              <VATNumber></VATNumber>
             </Address>
              <Address type="I" desc="">
                <AddressLine1>{{ \App\Common::khongdau($customer->address_line1) }}</AddressLine1>
                <AddressLine2></AddressLine2>
                <AddressLine3></AddressLine3>
                <PostalCode></PostalCode>
                <City></City>
                <Country code="VN"/>
                <Phone>{{ $customer->phone }}</Phone>
                <Fax></Fax>
             </Address>
              <Address type="P" desc="">
                <AddressLine1>{{ \App\Common::khongdau($customer->address_line1) }}</AddressLine1>
                <AddressLine2></AddressLine2>
                <AddressLine3></AddressLine3>
                <PostalCode></PostalCode>
                <City></City>
                <Country code="VN"/>
                <Phone>{{ $customer->phone }}</Phone>
                <Fax></Fax>
             </Address>
              <Address type="V" desc="">
                <AddressLine1>{{ \App\Common::khongdau($customer->address_line1) }}</AddressLine1>
                <AddressLine2></AddressLine2>
                <AddressLine3></AddressLine3>
                <PostalCode></PostalCode>
                <City></City>
                <Country code="VN"/>
                <Phone>{{ $customer->phone }}</Phone>
                <Fax></Fax>
              </Address>
          </Addresses>
          <Language code="EN" />
          <JobDescription>--</JobDescription>
          <Phone>{{ $customer->phone }}</Phone>
          <PhoneExt></PhoneExt>
          <Fax></Fax>
          <Mobile></Mobile>
          <Email></Email>
          <WebAccess>0</WebAccess>
          <FreeFields>
              <FreeNumbers>
                  <FreeNumber number="1" label="NumberField1">0</FreeNumber>
                  <FreeNumber number="2" label="NumberField2">0</FreeNumber>
                  <FreeNumber number="3" label="NumberField3">0</FreeNumber>
                  <FreeNumber number="4" label="NumberField4">0</FreeNumber>
                  <FreeNumber number="5" label="NumberField5">0</FreeNumber>
              </FreeNumbers>
              <FreeYesNos>
                  <FreeYesNo number="1" label="YesNoField1">0</FreeYesNo>
                  <FreeYesNo number="2" label="YesNoField2">0</FreeYesNo>
                  <FreeYesNo number="3" label="YesNoField3">0</FreeYesNo>
                  <FreeYesNo number="4" label="YesNoField4">0</FreeYesNo>
                  <FreeYesNo number="5" label="YesNoField5">0</FreeYesNo>
              </FreeYesNos>
          </FreeFields>
        </Contact>
        <Contact default="0" gender="M" status="A">
            <LastName>Dinh Chien</LastName>
            <FirstName></FirstName>
            <MiddleName></MiddleName>
            <Initials></Initials>
            <Title code="MR">
                <Description>Mr.</Description>
                <Salutation>Dear Mister</Salutation>
                <Abbreviation>Mr.</Abbreviation>
            </Title>
            <Addresses>
                <Address type="D" desc="">
              <AddressLine1>Cam Phu , Cam Pha, Quang Ninh</AddressLine1>
              <AddressLine2></AddressLine2>
              <AddressLine3></AddressLine3>
              <PostalCode></PostalCode>
              <City></City>
              <Country code="VN"/>
              <Phone>0343053333</Phone>
              <Fax></Fax>
              <VATNumber></VATNumber>
             </Address>
              <Address type="P" desc="">
              <AddressLine1>Cam Phu , Cam Pha, Quang Ninh</AddressLine1>
              <AddressLine2></AddressLine2>
              <AddressLine3></AddressLine3>
              <PostalCode></PostalCode>
              <City></City>
              <Country code="VN"/>
              <Phone>0343053333</Phone>
              <Fax></Fax>
             </Address>
            </Addresses>
            <Language code="EN" />
            <JobDescription>--</JobDescription>
            <Phone></Phone>
            <PhoneExt></PhoneExt>
            <Fax></Fax>
            <Mobile></Mobile>
            <Email></Email>
            <WebAccess>0</WebAccess>
            <FreeFields>
                <FreeNumbers>
                    <FreeNumber number="1" label="NumberField1">0</FreeNumber>
                    <FreeNumber number="2" label="NumberField2">0</FreeNumber>
                    <FreeNumber number="3" label="NumberField3">0</FreeNumber>
                    <FreeNumber number="4" label="NumberField4">0</FreeNumber>
                    <FreeNumber number="5" label="NumberField5">0</FreeNumber>
                </FreeNumbers>
                <FreeYesNos>
                    <FreeYesNo number="1" label="YesNoField1">0</FreeYesNo>
                    <FreeYesNo number="2" label="YesNoField2">0</FreeYesNo>
                    <FreeYesNo number="3" label="YesNoField3">0</FreeYesNo>
                    <FreeYesNo number="4" label="YesNoField4">0</FreeYesNo>
                    <FreeYesNo number="5" label="YesNoField5">0</FreeYesNo>
                </FreeYesNos>
            </FreeFields>
        </Contact>
      </Contacts>
      <Debtor number="{{ $customer->id }}" code="{{ $customer->id }}">
          <Currency code="VND" />
          <SecurityLevel>10</SecurityLevel>
          <AutoMatching>0</AutoMatching>
          <CreditLine>0</CreditLine>
          <Discount>
              <Percentage>0</Percentage>
          </Discount>
          <SendReminder>1</SendReminder>
          <AccountEmployee>0</AccountEmployee>
          <PrintStatement>1</PrintStatement>
          <OrderConfirmation>N</OrderConfirmation>
          <AllowPartialShipment>0</AllowPartialShipment>
          <AddExtraReceiptToOrder>0</AddExtraReceiptToOrder>
          <AllowBackOrders>0</AllowBackOrders>
          <InvoiceCopies>0</InvoiceCopies>
          <InvoiceMethod>P</InvoiceMethod>
          <GroupInvoice>N</GroupInvoice>
          <AttachUBL>0</AttachUBL>
          <Mailbox></Mailbox>
      </Debtor>
    </Account>
  </Accounts>
</eExact>