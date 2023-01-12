<template>
    <div class="container-fluid">
        <br>
        <h3>Khách hàng 15</h3>
        <div>
            <ul>
                <template v-for="lead in leads.data">
                    <li>Tạo <strong>{{ lead.last_name }}</strong> lúc <i>{{ lead.created_at }}</i> bởi {{ lead.username }} / xem chi tiết <a v-bind:href="'/thong-tin-kh15?id='+ lead.id">tại đây</a></li>
                </template>
            </ul>
        </div>
        <h3>Khách hàng tiềm năng</h3>
        <div>
            <ul>
                <template v-for="c in contacts.data">
                    <li>Tạo <strong>{{ c.last_name }}</strong> lúc <i>{{ c.created_at }}</i> bởi {{ c.username }} / xem chi tiết <a v-bind:href="'/thong-tin-khach-tiem-nang?id='+ c.id">tại đây</a></li>
                </template>
            </ul>
        </div>
        <h3>Đơn Hàng</h3>
        <div>
            <ul>
                <template v-for="con in contacts.data">
                    <template v-for="d in con.orders">
                        <li>
                            Tạo đơn hàng <strong>{{ d.so_number }}</strong> lúc <i>{{ d.created_at }}</i> bởi showroom {{ d.costcenter }}
                            <br>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>SL</th>
                                        <th>Đơn giá</th>
                                        <th>CK</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in d.orderLine">
                                        <td>{{ item.item }}</td>
                                        <td>{{ item.quantity }}</td>
                                        <td>{{ item.price | currency }}</td>
                                        <td>{{ item.discount}} %</td>
                                        <td>{{ item.amount | currency }}</td>
                                    </tr>
                                </tbody>
                            </table> 
                            <div class="row">
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th style="width:50%">Khách đặt cọc: </th>
                                                    <td style="width:50%">{{d.pre_pay | currency}}</td>
                                                </tr>
                                                <tr>
                                                    <th style="width:50%">Cần phải thu: </th>
                                                    <td style="width:50%">{{d.amount - d.pre_pay | currency}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <th>Tổng tiền</th>
                                                    <td>{{d.subtotal | currency}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Giảm giá</th>
                                                    <td>{{d.qgg | currency}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phí lắp đặt</th>
                                                    <td>{{d.fee_ld | currency}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Phí vận chuyển</th>
                                                    <td>{{d.fee_vc | currency}}</td>
                                                </tr>
                                                <tr>
                                                    <th>VAT</th>
                                                    <td>{{d.vat | currency}}</td>
                                                </tr>
                                                <tr>
                                                    <th>Thanh toán</th>
                                                    <td>{{d.amount | currency}}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </template>
                </template>
            </ul>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            leads: {},
            contacts: {},
            contact: {},
            idcontact: {},
            orders: {},
        }
    },

    methods: {
        taive(page = 1) {
            let url = "/api/leads?page=" + page;
            let queryName = queryString.stringify({ name: this.$parent.search });
                
            if (queryName.length > 13 && this.$parent.search > 0) {
                axios.get(url.concat('&',queryName))
                    .then(response => {
                        this.leads = response.data;
                    });

                axios.get("/api/contacts?page=" + page + '&' + queryName)
                    .then(response => {
                        this.contacts = response.data;
                    });
            }
            
        },
    },
    created() {
        Fire.$on('searching', () => {
            this.taive();
        })
    }
}

</script>
