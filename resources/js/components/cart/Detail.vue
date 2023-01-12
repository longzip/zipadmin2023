<template>
    <div class="table-responsive">
        <table class="table">
            <tr>
                <td>Số lượng sản phẩm:</td>
                <td>{{ details.total_quantity }}</td>
            </tr>
            <tr>
                <td>Giảm giá:</td>
                <td>{{ details.qgg | currency }}</td>
            </tr>
            <tr>
                <td>Phí vận chuyển:</td>
                <td>{{ details.fee_vc | currency }}</td>
            </tr>
            <tr>
                <td>Phí lắp đặt:</td>
                <td>{{ details.fee_ld | currency }}</td>
            </tr>
            <tr>
                <td>Thành tiền:</td>
                <td>{{ details.sub_total | currency }}</td>
            </tr>
            <tr>
                <td>VAT:</td>
                <td>{{ details.vat | currency }}</td>
            </tr>
            <tr>
                <td>Thanh toán:</td>
                <td><strong>{{ details.total | currency }}</strong></td>
            </tr>
        </table>
    </div>
</template>
<script>
export default {
    props: ['details'],
}

</script>
