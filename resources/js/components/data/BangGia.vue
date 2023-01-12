<template>
    <div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Chọn sản phẩm <a href="#" @click="reSelectSanPham"><i class="fa fa-refresh"></i></a></label>
                        <select  class="form-control" v-model="p" @change="onChange($event)">
                            <option v-for="option in $parent.p" v-bind:value="option.value">
                                {{ option.text }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    	<h4>Bảng Phí</h4>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>Phí</th>
                                    <th>Gía</th>
                                </tr>
                                <tr>
                                	<td>Cắt lòng giường</td>
                                	<td>4.000.000</td>
                                </tr>
                                <tr>
                                	<td>Thay màu sofa</td>
                                	<td>1.000.000</td>
                                </tr>
                                <tr>
                                	<td>Cắt đệm</td>
                                	<td>500.000</td>
                                </tr>
                                <tr>
                                    <td>Thay đổi màu laminate</td>
                                    <td>1.600.000</td>
                                </tr>
                                <tr>
                                    <td>Bỏ cầu thang Iris</td>
                                    <td>3.500.000</td>
                                </tr>
                                <tr>
                                    <td>Phí thiết kế ( Diện tích >= 1 m2)</td>
                                    <td>5.000.000 / m2</td>
                                </tr>
                                <tr>
                                    <td>Phí thiết kế ( Diện tích < 1 m2)</td>
                                    <td>8.000.000 / m2</td>
                                </tr>
                                <tr>
                                    <td>Nâng cao chân Lemon</td>
                                    <td>1.500.00</td>
                                </tr>
                                <tr>
                                    <td>Phí xoay giường</td>
                                    <td>1.600.000 </td>
                                </tr>
                                <tr>
                                    <td>Phí trừ đi 10cm</td>
                                    <td>850.000 </td>
                                </tr>
                                <tr>
                                    <td>Bàn rộng 500</td>
                                    <td>1.000.000 </td>
                                </tr>
                                <tr>
                                    <td>Bàn rộng 600</td>
                                    <td>1.500.000 </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                    	<h4>Bảng Gía</h4>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>Sản Phẩm</th>
                                    <th>Mã</th>
                                    <th>Gía</th>
                                    <th>Tiền thưởng</th>
                                </tr>
                                <tr v-for="order in orders.data" :key="order.id">
                                	<td>{{order.name}}</td>
                                	<td>{{order.code}}</td>
                                	<td>{{order.price | currency}}</td>
                                	<td>{{order.point | currency}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination :data="orders" @pagination-change-page="getResults" :limit="5"></pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            orders: {},
            p:'',
        }
    },
    methods: {
        reSelectSanPham() {
            this.p = [];
            this.getResults();
        },
        getResults(page = 1) {
            let queryName = queryString.stringify({ name: this.$parent.search });
            let products = queryString.stringify({ p: this.p });
            axios.get("/api/products-list-tab?page=" + page + '&' + products + '&' + queryName)
                .then(response => {
                    this.orders = response.data;
                });
        },

        onChange(event) {
            this.getResults();
        },
    },
    created() {
        this.getResults();
        Fire.$on('searching', () => {
            this.getResults();
        })
        Fire.$on('AfterCreate', () => {
            this.getResults();
        });
    }
}

</script>
