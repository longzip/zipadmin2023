<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-2">
                <h1 class="m-0 text-dark">Lịch Giao Của Đơn Hàng</h1>
            </div><!-- /.col -->
            <div class="form-group col-md-2">
                <label>Chọn T<a href="#" @click="reSelect"><i class="fa fa-refresh"></i></a></label>
                <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="calendar" :multiple="true" :close-on-select="false" :clear-on-select="true" :preserve-search="true" placeholder="Chọn" label="t" track-by="id_table" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheot">
                </multiselect>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn năm </label>
                    <select  class="form-control" v-model="search.year" @change="onChangeSearch($event)">
                        <option v-for="option in $parent.year" v-bind:value="option.value" :selected="option.value == search.year">
                            {{ option.nam }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn P </label>
                    <select  class="form-control" v-model="search.pt" @change="onChangeSearch($event)">
                        <option v-for="option in $parent.pt" v-bind:value="option.value" :selected="option.value == search.pt">
                            {{ option.index }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Tìm </label><br/>
                    <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                </div>
            </div>

        </div>
        <div class="tab-content"  style="width:100%">
            <div class=" table-responsive tableFixHead">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="min-width: 80px;">T Đơn Hàng</th>
                            <th style="min-width: 80px;">T Giao</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(l,k) in list">
                            <tr v-for="(v, i, key) in l">
                                <td class="text-center" :rowspan="l.length" v-if="i==0">{{k}}</td>
                                <td>
                                    <span>{{v.t}}</span> 
                                    - {{v.orders.length}} Đơn Hàng
                                    <template  v-if="v.orders.length > 0">
                                        (<small v-for="order in v.orders"><a @click="showOrder(order[0])" href="#">{{order[0]['so_number']}}</a>,</small>)
                                    </template>
                                    <br> {{v.v | currency}} / {{v.tong | currency}}
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal  fade" id="orderLineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết đơn hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row invoice-info">
                            <div class="col-sm-6 invoice-col">
                                To
                                <address v-if="order.sdt == null">
                                    <strong>{{ order.customer_name}}</strong><br>
                                    {{ order.customer_address}}<br>
                                    Điện thoại: {{ order.customer_phone}} 
                                </address>
                                <address v-if="order.sdt != null">
                                    {{ order.note}}<br>
                                    Điện thoại: <b>{{ order.sdt}}</b> 
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6 invoice-col">
                                <strong>Số đơn hàng #{{order.so_number}}</strong><br>
                                <strong>Ngày đơn hàng: </strong>{{order.start_date | myDate}}<br>
                                <strong>Ngày giao hàng: </strong>{{order.delivery_date | myDate}}<br>
                                <strong>Showroom: </strong>{{order.costcenter}}<br>
                                <strong>Nhân viên: </strong>{{order.user_name}} - {{order.user_id}}<br>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
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
                                        <tr v-for="item in orderLine">
                                            <td>{{ item.item }}</td>
                                            <td>{{ item.quantity }}</td>
                                            <td>{{ item.price | currency }}</td>
                                            <td>{{ item.discount}} %</td>
                                            <td>{{ item.amount | currency }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th style="width:50%">Khách đặt cọc: </th>
                                                <td style="width:50%">{{order.pre_pay | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th style="width:50%">Cần phải thu: </th>
                                                <td style="width:50%">{{order.amount - order.pre_pay | currency}}</td>
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
                                                <td>{{order.subtotal | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Giảm giá</th>
                                                <td>{{order.qgg | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Phí lắp đặt</th>
                                                <td>{{order.fee_ld | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Phí vận chuyển</th>
                                                <td>{{order.fee_vc | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>VAT</th>
                                                <td>{{order.vat | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Thanh toán</th>
                                                <td>{{order.amount | currency}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
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
            calendar: [],
            saleSelected: [],
            list: [],
            order: [],
            orderLine: [],
            search: new Form({
                year: '15',
                pt: '4',
            }),
        }
    },
    methods: {
        searchdata() {
            this.loadData();
        },
        reSelect(){
            this.$parent.saleSelected = [];
            // this.loadData();
        },
        timTheot(){
            // this.loadData();
        },
        onChangeSearch(event) {
            // this.loadData();
        },
        loadData() {
            this.$Progress.start();

            this.costcenter_ids = this.$parent.saleSelected.map(costcenter => {
                return costcenter.id_table;
            });
            let costcenters = queryString.stringify({ t: this.costcenter_ids }, { arrayFormat: 'bracket' });
            axios.get('api/bao-cao-lich-giao' + '?' + costcenters + '&z=' + this.search.year + '&p=' + this.search.pt )
                .then(responsive => {
                    this.list = responsive.data;
                    console.log(this.list);
                })
                .catch(() => this.$Progress.fail());
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            // this.loadData();
        },
        showOrder(order) {
            this.order = order;
            this.orderLine = order.orderLine;
            $('#orderLineModal').modal('show');
        },
    },
   
    created() {
        this.loadData();
        axios.get('api/select-week')
            .then(res => this.calendar = res.data);
    }
}
</script>

<style>
.bg-new {
    background-color:#e9ecef !important;
}

.fz111 {
    font-size:11px !important;
    color: #212529;
    font-weight: 700;
}

.fz11 {
    font-size:11px !important;
}

.tableFixHead          { overflow-y: auto; height: 700px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1;}

table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
tbody tr td { z-index: 0}
</style>