<template>
    <table class="table table-hover">
        <tbody>
            <tr>
                <th>SDH</th>
                <th>Mã SR</th>
                <th>Tạo bởi</th>
                <th>Ngày đặt hàng</th>
                <th>Ngày giao</th>
                <th>Tổng tiền</th>
                <th>Đã thu</th>
                <th>Còn nợ</th>
                <th>Trạng thái</th>
                <th>Modify</th>
            </tr>
            <tr v-for="order in sortOrders" :key="order.id">
                <!-- <td><a href="#" @click="showOrder(order.id)">{{order.so_number}}</a></td> -->
                <td>{{order.costcenter}}</td>
                <td>{{order.user_id}}</td>
                <td>{{order.created_at | myDate}}</td>
                <td>{{order.delivery_date | myDate}}</td>
                <td>{{order.amount}}</td>
                <td>{{order.pre_pay}}</td>
                <td>{{order.amount}} {{order.pre_pay}}</td>
                <td>{{order.status_id | trangThaiSO}}</td>
                <!-- <td>
                    <a href="#" @click="editOrder(order.so_number)">
                        <i class="fa fa-edit blue"></i>
                    </a>
                    /
                    <a href="#" @click="deleteOrder(order.id)">
                        <i class="fa fa-trash red"></i>
                    </a>
                </td> -->
            </tr>
        </tbody>
    </table>
</template>
<script>
  export default {
  	props: ['orders'],
  	computed: {
    // a computed getter
    sortOrders: function () {
      // `this` points to the vm instance

      return this.orders;
    }
  }
  }
</script>
