<template>
    <table class="table table-hover">
        <tbody>
            <tr>
                
                <th>Mã SP</th>
                <th>Tên SP</th>
                <th>SL</th>
                <th>Giá</th>
                <th>CK</th>
                <th>Thành tiền</th>
            </tr>
            <tr v-for="productLine in productLines" :key="productLine.id">
                <!-- <td><a href="#" @click="showOrder(order.id)">{{order.so_number}}</a></td> -->
                <td>{{order.costcenter}}</td>
                <td>{{order.user_id}}</td>
                <td>{{order.created_at | myDate}}</td>
                <td>{{order.delivery_date | myDate}}</td>
                <td>{{order.amount}}</td>
                <td>{{order.pre_pay}}</td>
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
  	props: ['productLines'],
  	computed: {
    // a computed getter
    sortOrders: function () {
      // `this` points to the vm instance

      return this.orders;
    }
  }
  }
</script>
