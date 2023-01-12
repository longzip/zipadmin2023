<template>
    <table class="table table-hover">
        <tbody>
            <tr>
                <th>Báo giá</th>
                <th>Sản phẩm</th>
                <th>Phí VC</th>
                <th>Phí LĐ</th>
                <th>Giảm giá</th>
                <!-- <th>Tổng tiền</th> -->
                <th>VAT</th>
                <th>Thanh toán</th>
            </tr>
            <tr v-for="quote in quotes" :key="quote.id">
                <td><router-link :to="{ name: 'showQuote', params: { quoteId: quote.id }}">{{quote.subject}}</router-link></td>
                <td>
                    <products :products="quote.products"></products>
                </td>
                <td>{{quote.fee_vc | currency}}</td>
                <td>{{quote.fee_ld | currency}}</td>
                <td>{{quote.qgg | currency}}</td>
                <!-- <td>{{quote.subtotal | currency}}</td> -->
                <td>{{quote.vat | currency}}</td>
                <td>{{quote.total | currency}}</td>
            </tr>
        </tbody>
    </table>
</template>
<script>
export default {
    props: ['quotes'],
}

</script>
