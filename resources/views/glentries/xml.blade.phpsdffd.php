@php
echo '<?xml version="1.0" ?>';
@endphp
<eExact xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="eExact-Schema.xsd">
 <GLEntries>
 <GLEntry status="E">
  <Division code="004" />
  <DocumentDate>2019-01-14</DocumentDate>
  <Journal code="113" type="K">
   <Description>Ti&#234;&#768;n m&#259;&#803;t - &#273;ang chuy&#234;&#777;n</Description>
   <GLAccount code="    11300" type="B" subtype="S" side="D">
    <Description>Ti&#234;&#768;n &#273;ang chuy&#234;&#777;n</Description>
   </GLAccount>
   <GLPaymentInTransit code="    99300" type="B" subtype="N" side="D">
    <Description>Ta&#768;i khoa&#777;n Suspense - Unallocated cash</Description>
   </GLPaymentInTransit>
  </Journal>
   <Costcenter code="0">
    <Description>0</Description>
   </Costcenter>
  <Amount>
   <Currency code="VND"/>
   <Value>0</Value>
  </Amount>
  <ForeignAmount>
   <Currency code="VND"/>
   <Value>0</Value>
  </ForeignAmount>
  <FinEntryLine number="1" type="N" subtype="Z">
   <Date>2019-01-14</Date>
   <FinYear number="2019"/>
  <FinPeriod number="1"/>
   <GLAccount code="    13100" type="B" subtype="D" side="G">
    <Description>Pha&#777;i thu kha&#769;ch ha&#768;ng</Description>
   </GLAccount>
    <Description>Thu coc KH</Description>
   <Costcenter code="HNI_BTL">
    <Description>ZIP B&#259;&#769;c T&#432;&#768; Li&#234;m</Description>
   </Costcenter>
   <Debtor code="100001" />
   <Resource number="900000003" code="ADMIN_MB">
    <LastName>ADMIN MI&#202;&#768;N B&#258;&#769;C</LastName>
   </Resource>
   <Warehouse code="A_MB">
    <Description>Kho admin mi&#234;&#768;n b&#259;&#769;c</Description>
   </Warehouse>
   <Project code="11" type="H" status="A">
    
   </Project>
   <Quantity>0</Quantity>
   <Amount>
    <Currency code="VND"/>
    <Debit>0</Debit>
    <Credit>2000000</Credit>
       <VAT code="0" type="B" vattype="N" taxtype="V">
        <Description>Standard VAT code</Description>
        <MultiDescriptions>
             <MultiDescription number="1">Standard VAT code</MultiDescription>
             <MultiDescription number="2">Standard VAT code</MultiDescription>
             <MultiDescription number="3">Standard VAT code</MultiDescription>
             <MultiDescription number="4">Standard VAT code</MultiDescription>
        </MultiDescriptions>
        <Percentage>0</Percentage>
        <Charged>0</Charged>
        <VATExemption>0</VATExemption>
        <ExtraDutyPercentage>0</ExtraDutyPercentage>
       </VAT>
   </Amount>
   <VATTransaction code="0">
    <VATAmount>0</VATAmount>
    <VATBaseAmount>0</VATBaseAmount>
    <VATBaseAmountFC>0</VATBaseAmountFC>
   </VATTransaction>
   <Payment>
    <PaymentMethod code="B"/>
    <PaymentCondition code="00">
     <Description>Payment condition 00</Description>
    </PaymentCondition>
    <TransactionNumberSubAdministration>       3</TransactionNumberSubAdministration>
    <CSSDYesNo>K</CSSDYesNo>
    <CSSDAmount1>0</CSSDAmount1>
    <CSSDAmount2>0</CSSDAmount2>
    <InvoiceNumber>22000001</InvoiceNumber>
    <BankTransactionID>{E3030B41-8C74-4A58-BFD3-02BE43215DB8}</BankTransactionID>
   </Payment>
   <Delivery>
    <Date>2019-01-08</Date>
   </Delivery>
   <FinReferences TransactionOrigin="P">
    <UniquePostingNumber>0</UniquePostingNumber>
    <YourRef>3399</YourRef>
    <DocumentDate>2019-01-08</DocumentDate>
   </FinReferences>
   <Discount>
    <Percentage>0</Percentage>
   </Discount>
  </FinEntryLine>
  <BankStatement number="0">
  <GLOffset code="    13100"/>
  <BankStatementLine type="Z" termType="S" status="J" entry="20130001" ID="{E3030B41-8C74-4A58-BFD3-02BE43215DB8}" lineNo="0" statementType="B" paymentType="K">
   <Description>Thu coc KH 5000059 Our ref.:</Description>
   <ValueDate>2019-01-08</ValueDate>
   <ReportingDate>2019-01-08</ReportingDate>
   <StatementDate></StatementDate>
   <GLAccount code="    11300" side="D" type="B" subtype="S">
    <Description>Ti&#234;&#768;n &#273;ang chuy&#234;&#777;n</Description>
   </GLAccount>
   <OwnBankAccount code="113" type="K">
    <Description>Ti&#234;&#768;n m&#259;&#803;t - &#273;ang chuy&#234;&#777;n</Description>
    <Currency code="VND"/>
    <Journal code="113" type="K"/>
   <GLAccount code="    11300" side="D" type="B" subtype="S">
    <Description>Ti&#234;&#768;n &#273;ang chuy&#234;&#777;n</Description>
   </GLAccount>
    <GLPaymentInTransit code="    99300"/>
    <Country code="VN"/>
    <BankName>Cash</BankName>
    <BankCreditor>              900000</BankCreditor>
   </OwnBankAccount>
   <Debtor code="             5000059" number="100021"/>
   <Amount>
    <Currency code="VND"/>
    <Debit>3000000</Debit>
    <Credit>0.0</Credit>
   </Amount>
   <ForeignAmount>
    <Currency code="VND"/>
    <Debit>3000000</Debit>
    <Credit>0.0</Credit>
    <Rate>1</Rate>
   </ForeignAmount>
   <Reference></Reference>
   <YourRef>3399</YourRef>
   <InvoiceNumber>22000001</InvoiceNumber>
   <Journalization>
    <Resource number="1"/>
    <Date>2019-01-08</Date>
   </Journalization>
   <IsBlocked>0</IsBlocked>
   <PaymentTermIDs>
    <PaymentTermID>{58105951-D7D1-4993-8FAD-9FE625D25422}</PaymentTermID>
   </PaymentTermIDs>
  <GLOffset code="    13100"/>
  </BankStatementLine>
  </BankStatement>
 </GLEntry>
</GLEntries>
</eExact>
