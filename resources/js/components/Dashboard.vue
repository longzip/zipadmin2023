<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-7">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div><!-- /.col -->
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Chọn Showroom <a href="#" @click="reSelectShowroom"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" v-model="showroomsSelected" :options="costcenters" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoShowroom">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label>Chọn ngày</label>
                    <date-range-picker class="form-control" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges">
                        <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                    </date-range-picker>
                </div>
            </div><!-- /.col -->
        </div>
        <contact-widget :data="counts"></contact-widget>
        <div class="row">
            <div class="col-lg-4 col-12">
                <div class="row">
                    <div class="col-12">
                        <img v-bind:src="'https://admin.noithatzip.com/images/timthumb.png'" alt="" width="100%">
                    </div>
                </div>
                <div class="base-timer">
                  <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                    <g class="base-timer__circle">
                      <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45" />
                    </g>
                  </svg>
                   <span id="base-timer-label" class="base-timer__label">
                    <div class="row">
                        <div class="col-12 text-center"><a href="#" @click="loaddon(4)">Suất Khác: {{or.tong}} / 70</a></div></br>
                        <div class="col-12 text-center"><a href="#" @click="loaddon(51)">Suất Ivy m8: {{or.ivy8}} / 30</a></div></br>
                        <div class="col-12 text-center"><a href="#" @click="loaddon(52)">Suất Ivy khác: {{or.ivyk}} / 50</a></div></br>
                    </div>
                  </span>
                </div>
                <template v-for="(o,k) in donhang.data">
                    <div class="bot">
                        <h6 class="mergin">{{++k}}.{{o.costcenter}} - {{o.user_name}}: <small><a href="#" @click="showOrder(o)">{{o.so_number}}</a> / {{o.subtotal | currency}} </small></h6>
                    </div>
                </template>
            </div>
            <div class="col-lg-4 col-12">
                <h2>{{news.name}}</h2>
                <b>{{news.sapo}}</b>
                <div>
                    <span>Người Viết: <b>{{news.user_name}}</b></span>
                    <br>
                    <span>Ngày: {{news.created_at | myDate}}</span>
                </div> 
                <div v-html="news.content"></div>
            </div>
            <!-- <div class="col-lg-4 col-12">
                <h2>{{newst.name}}</h2>
                <b>{{newst.sapo}}</b>
                <div>
                    <span>Người Viết: <b>{{newst.user_name}}</b></span>
                    <br>
                    <span>Ngày: {{newst.created_at | myDate}}</span>
                </div> 
                <div v-html="newst.content"></div>
            </div> -->
            <section class="col-lg-4 col-12 connectedSortable">
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="pill" href="#khtn">TB Kinh Doanh</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="pill" href="#kh15">TB HO</a>
                    </li>
                </ul>
                <div class="tab-content" style="width:100%">
                    <div id="khtn" class="tab-pane active ml-0 mr-0 pr-0 pl-0 card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                Thông báo Kinh Doanh
                            </h3>
                            <div class="card-tools">
                                <pagination :data="messages" :pageSize="5" @pagination-change-page="loadMessages" :limit="5"></pagination>
                            </div>
                        </div>
                        <div class="card-body">
                            <messages-list v-show="!loading" :messages="messages.data"></messages-list>
                        </div>
                    </div>
                    <div id="kh15" class="tab-pane ml-0 mr-0 pr-0 pl-0 card">
                        <div class="card-header">
                           <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                Thông báo HO
                            </h3>
                            <div class="card-tools">
                            <pagination :data="nghipheps" :pageSize="5" @pagination-change-page="LoadNghipheps" :limit="5"></pagination>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- <messages-list v-show="!loading" :nghipheps="nghipheps.data"></messages-list> -->
                            <table class="table table-hover">
                                <tbody>
                                    <tr v-for="thongbao in nghipheps.data" class="timeline1" @click="thongbao_detail"  >
                                        <p>{{thongbao.user_name}}
                                            <span v-if="thongbao.action==1">đã thêm</span>
                                            <span v-if="thongbao.action==2">đã sửa</span>
                                            <span v-if="thongbao.action==3">đã xóa</span>
                                            <span v-if="thongbao.action==4">đã duyệt</span>
                                            <span v-if="thongbao.action==5">BPNS đã duyệt</span>
                                            <span v-if="thongbao.action==6">không duyệt</span>
                                            <span v-if="thongbao.type==1">chi phí</span>
                                            <span v-if="thongbao.type==2">nghỉ phép</span>
                                            <span v-if="thongbao.type==3">vi phạm</span>
                                            <span v-if="thongbao.type==4">quy định</span>
                                            <span v-if="thongbao.type==5">comment</span>
                                        {{thongbao.noidung}}</p>
                                        <span class=" badge badge-info"> {{ thongbao.created_at|myDateN }}
                                            <router-link v-if="thongbao.type==1" :to="{ name: getUri(thongbao.type), query : { id: thongbao.id_chuyenmuc }}"><span @click="del_thongbao(thongbao)" >detail</span></router-link>
                                            <router-link v-if="thongbao.type==2" :to="{ name: getUri(thongbao.type), query : { id: thongbao.id_chuyenmuc }}"><span @click="del_thongbao(thongbao)">detail</span></router-link>
                                            <router-link v-if="thongbao.type==3" :to="{ name: getUri(thongbao.type), query : { id: thongbao.id_chuyenmuc }}"><span @click="del_thongbao(thongbao)">detail</span></router-link>
                                            <router-link v-if="thongbao.type==4" :to="{ name: getUri(thongbao.type), query : { id: thongbao.id_chuyenmuc }}"><span @click="del_thongbao(thongbao)">detail</span></router-link>
                                            <router-link v-if="thongbao.type==5" :to="{ name: getUri(thongbao.type), query : { id: thongbao.id_chuyenmuc }}"><span @click="del_thongbao(thongbao)">detail</span></router-link>
                                        </span>
                                        <hr>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
            <!-- right col -->
        </div>

        <div class="modal  fade" id="orderLineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
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
                    <div class="modal-footer">
                        <div class="row no-print">
                            <div class="col-12">
                                <a id="print" href="#" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                                <button @click="duyetDonHang(order.id)" type="button" class="btn btn-success float-right"><i class="fa fa-credit-card"></i> Duyệt đơn hàng
                                </button>
                                <a id="downloadExcel" href="#" class="btn btn-primary float-right" style="margin-right: 5px;">
                                    <i class="fa fa-download"></i> Download Excell
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
</template>
<style type="text/css" lang="scss" scoped>
.timeline1{
    span{
        width: 75px;
    }
}
</style>
<script>
import ContactWidget from "./elements/ContactWidgets.vue";
import AreaChart from "./elements/AreaChart.vue";
import ToDoList from "./elements/ToDoList.vue";
import MessagesList from "./elements/MessagesList.vue";
import NghiPhepList from "./elements/NghiPhepList.vue";

export default {
    components: { ContactWidget, AreaChart, ToDoList, MessagesList ,NghiPhepList },
    data() {
        return {
            loading: true,
            or: [],
            order: [],
            donhang: [],
            costcenters: [],
            orderLine: [],
            counts: [],
            news: [],
            showroomsSelected: [],
            newst: [],
            messages: [],
            nghipheps: [],
            opens: "center", //which way the picker opens, default "center", can be "left"/"right"
            locale: {
                direction: 'ltr', //direction of text
                format: 'DD-MM-YYYY', //fomart of the dates displayed
                separator: ' - ', //separator between the two ranges
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                weekLabel: 'W',
                customRangeLabel: 'Custom Range',
                daysOfWeek: moment.weekdaysMin(), //array of days - see moment documenations for details
                monthNames: moment.monthsShort(), //array of month names - see moment documenations for details
                firstDay: 1, //ISO first day of week - see moment documenations for details
                showWeekNumbers: true //show week numbers on each row of the calendar
            },
            ranges: { //default value for ranges object (if you set this to false ranges will no be rendered)
                'Tuần này': [moment().startOf('week'), moment()],
                'Tuần trước': [moment().subtract(1 ,'week').startOf('week'), moment()],
                'Hai tuần trước': [moment().subtract(2 ,'week').startOf('week'), moment()],
                'Ba tuần trước': [moment().subtract(3 ,'week').startOf('week'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
            },
            startDate: moment().subtract(30, 'days').format(),
            endDate: moment().format(),
            selectedDate: {
                startDate: moment().subtract(30, 'days').format(),
                endDate: moment().format()
            },
        }
    },
    created() {
        this.loadMessages();
        this.loadCount();
        this.LoadNghipheps();
        this.loadNew();
        this.loadNewT();
        this.blackfriday();
        axios.get('/api/picklists/costcenter-picklists')
            .then(res => this.costcenters = res.data);
    },
    methods: {
        reSelectShowroom() {
            this.$parent.showroomsSelected = '';
            this.loadMessages();
        },
        getUri(name){
            if (name==1) {
                return 'detailchiphi';
            }
            if (name==2) {
                return 'detailnghiphep';
            }
            if (name==3) {
                return 'detailvipham';
            }
            if (name==4) {
                return 'detailquydinh';
            }
            if (name==5) {
                return 'detailnghiphep';
            }
        },
        del_thongbao(thongbao){
            axios.get('/api/delThongBao?id='+ thongbao.id).then(()=>{
                    this.LoadNghipheps();
                }).catch();
            console.log(thongbao.id);
        },
        updateValues(values){
            this.startDate = values.startDate.toISOString().slice(0, 10)
            this.endDate = values.endDate.toISOString().slice(0, 10)
            this.loadCount();
        },
        loadCount(){
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            axios.get('api/picklists/dashboard-picklists?' + dates_para)
            .then(res => this.counts = res.data)
        },
        timTheoShowroom() {
            this.loadMessages();
        },
        loadMessages(page = 1) {
            this.loading = true;
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let costcenter_ids = this.showroomsSelected.map((costcenter, index, showroomsSelected) => {
                return costcenter.id;
            });
            axios.get('api/messages?page=' + page + '&' + dates_para + '&' +queryString.stringify({ costcenters: costcenter_ids }, { arrayFormat: 'bracket' }))
                .then(res => {
                    this.messages = res.data;
                    this.loading = false;
                });
        },
        LoadNghipheps(page = 1){
            this.loading = true;
            axios.get('api/nghipheps?page=' + page).then(res =>{
                    this.nghipheps = res.data;
                    this.loading = false;
            });
            // console.log(this.nghipheps);
        },

        thongbao_detail(page = 1){
            this.loading = true;
            axios.get('api/nghipheps?page=' + page).then(res =>{
                    this.nghipheps = res.data;
                    this.loading = false;
            });
        },

        loadNew(){
            axios.get('api/shownew').then(res =>{
                    this.news = res.data;
            });
        },

        loadNewT(){
            axios.get('api/shownewt').then(res =>{
                    this.newst = res.data;
            });
        },

        blackfriday(){
            axios.get('api/listblackfriday').then(res =>{
                this.or = res.data;
            });
        },

        tao() {
            $(function() {
                var areaChartCanvas = $('#areaChart').get(0).getContext('2d')
                var chart = new Chart(areaChartCanvas, {
                    // The type of chart we want to create
                    type: 'line',

                    // The data for our dataset
                    data: {
                        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                        datasets: [{
                            label: 'Bảng khách kh15',
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: [0, 10, 5, 2, 20, 30, 20]
                        }]
                    },
                    // Configuration options go here
                    options: {}
                });
            })
        },

        loaddon(type){
            axios.get('api/listblackfridaytype?type=' + type).then(res =>{
                this.donhang = res.data;
            });
        },

        showOrder(order) {
            this.order = order;
            this.orderLine = order.orderLine;
            $('#orderLineModal').modal('show');
        },

    }
}

</script>

<style>
.base-timer {
  position: relative;
  height: 250px;
  width: 250px;
  margin: auto;
}

.base-timer__circle {
  fill: none;
  stroke: none;
}

.base-timer__path-elapsed {
  stroke-width: 7px;
  stroke: grey;
}

.base-timer__label {
  position: absolute;
  width: 250px;
  height: 250px;
  top: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
}
.bot{
    border-bottom: 5px solid gray;
}
.mergin{
    position: relative;
    bottom: -5px;
}
</style>