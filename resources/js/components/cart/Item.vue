<template>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Tên sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Đơn giá</th>
                    <th>Chiết khấu</th>
                    <th>Thành tiền</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in items">
                    <td>{{ item.code }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.quantity }}</td>
                    <td>{{ item.price | currency }}</td>
                    <td>{{ item.discount | currency }}</td>
                    <td>{{ item.thanh_tien | currency }}</td>
                    <td>
                        <button v-on:click="removeItem(item)" class="btn btn-sm btn-danger">Xóa</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
export default {
    props: ['items'],
    methods: {
        removeItem(item){
            this.$emit('remove', item);
        }
    }
}

</script>
