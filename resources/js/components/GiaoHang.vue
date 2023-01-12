<template>
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-body" style="padding: 10px;">
                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn ngày</label>
                            <date-range-picker class="form-control" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges">
                                <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                            </date-range-picker>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Ngày Giao</label>
                            <input class="form-control" type="checkbox" v-model="checkedCategories" @change="checkdate()" >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label></label>
                            <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#admin" @click="loadAdmin">Admin</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#ketoan" @click="loadKetoan">Kế Toán</a>
            </li>
        </ul>
        <div class="tab-content"  style="width:100%">
            <div id="admin" class="tab-pane active ml-0 mr-0 pr-0 pl-0">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Lịch Giao</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0 ">
                                <div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th rowspan="2">#</th>
                                                <th colspan="3" class="text-center"><a href="#" @click="pheduyet(duyet.t1)">T1</a></th>
                                                <th colspan="3" class="text-center"><a href="#" @click="pheduyet(duyet.t2)">T2</a></th>
                                                <th colspan="3" class="text-center"><a href="#" @click="pheduyet(duyet.t3)">T3</a></th>
                                                <th colspan="3" class="text-center"><a href="#" @click="pheduyet(duyet.t4)">T4</a></th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Miền Bắc</th>
                                                <th class="text-center">Miền Trung</th>
                                                <th class="text-center">Miền Nam</th>
                                                <th class="text-center">Miền Bắc</th>
                                                <th class="text-center">Miền Trung</th>
                                                <th class="text-center">Miền Nam</th>
                                                <th class="text-center">Miền Bắc</th>
                                                <th class="text-center">Miền Trung</th>
                                                <th class="text-center">Miền Nam</th>
                                                <th class="text-center">Miền Bắc</th>
                                                <th class="text-center">Miền Trung</th>
                                                <th class="text-center">Miền Nam</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                       		<tr>
                                                <td>T2->T6</td>
                                               	<template v-for="g in giao.dautuan">
                                                    <td class="text-center">{{g | currency}}</td>
                                                </template>   
                                       		</tr>
                                            <tr>
                                                <td>T7->CN</td>
                                                <template v-for="g in giao.cuoituan">
                                                    <td class="text-center">{{g | currency}}</td>
                                                </template>   
                                            </tr>
                                        	<tr>
                                        		<td>Tổng Khu Vực</td>	
                                       			<template v-for="v in giao.kv">
                                        			<td class="text-center">{{v | currency}}</td>	
                                        		</template>   
                                        	</tr> 
                                        	<tr>
                                        		<td>Tổng T</td>
                                        		<template v-for="(v,key) in giao.tong">
                                        			<td colspan="3" class="text-center"><a href="#" @click="showdetailtong(v)">{{v.tong | currency}}</a></td>	
                                        		</template> 
                                        	</tr>
                                            <tr>
                                                <td>Admin Duyệt</td>
                                                <template v-for="(v,key) in giao.duyet">
                                                    <td colspan="3" class="text-center" v-if="v != null "> 
                                                        {{v.money | currency}}
                                                        <span class="badge badge-success" v-if="v.stt == 1"><a href="#" @click="showdetail(v)">( {{v.created_at}} )</a> </span>
                                                        <span class="badge badge-warning" v-if="v.stt == 0"><a href="#" @click="showdetail(v)">( {{v.created_at}} )</a> </span>
                                                     
                                                    </td>  
                                                    <td colspan="3" class="text-center" v-if="v == null ">Chưa Duyệt </td>  
                                                </template> 
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Chưa Giao</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0 ">
                                <div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Thời Gian</th>  
                                                <th>Miền Bắc</th>
                                                <th>Miền Trung</th>
                                                <th>Miền Nam</th>
                                                <th>Tổng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           	<template v-for="(giao,key) in chuagiao.tiep">
                                           		<tr>
        	                               			<td>P{{key}}</td>
                                                    <template v-for="(g) in giao" v-if="key != 'còn lại'">
                                                        <td>{{g | currency}}</td>
                                                    </template> 
                                                    <template v-for="(g) in giao" v-if="key == 'còn lại'">
                                                        <td><span class="badge badge-danger">{{g | currency}}</span></td>
                                                    </template>     
                                           		</tr>
                                            </template>   
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="ketoan" class="tab-pane ml-0 mr-0 pr-0 pl-0">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Tài Chính</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0 ">
                                <div>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Miền</th>  
                                                <th>Gía Trước</th>
                                                <th>Gía Sau</th>
                                                <th>Phí</th>
                                                <th>Cọc</th>
                                                <th>CK</th>
                                                <th>% CK</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a href="#" @click="loadmore('A_MB')">Miền Bắc</a></td>
                                                <td>{{ketoan.giatrcckA_MB | currency}}</td>
                                                <td>{{ketoan.giasauckA_MB | currency}}</td>
                                                <td>{{ketoan.feeA_MB | currency}}</td>
                                                <td>{{ketoan.cocA_MB | currency}}</td>
                                                <td>{{ketoan.giatrcckA_MB - ketoan.giasauckA_MB | currency}}</td>
                                                <td>{{formatPrice(100 - ketoan.giasauckA_MB * 100 / ketoan.giatrcckA_MB )}}%</td>
                                            </tr>
                                            <tr>
                                                <td><a href="#" @click="loadmore('A_MN')">Miền Nam</a></td>
                                                <td>{{ketoan.giatrcckA_MN | currency}}</td>
                                                <td>{{ketoan.giasauckA_MN | currency}}</td>
                                                <td>{{ketoan.feeA_MN | currency}}</td>
                                                <td>{{ketoan.cocA_MN | currency}}</td>
                                                <td>{{ketoan.giatrcckA_MN - ketoan.giasauckA_MN | currency}}</td>
                                                <td>{{formatPrice(100 - ketoan.giasauckA_MN * 100 / ketoan.giatrcckA_MN )}}%</td>
                                            </tr>
                                            <tr>
                                                <td><a href="#" @click="loadmore('A_MT')">Miền Trung</a></td>
                                                <td>{{ketoan.giatrcckA_MT | currency}}</td>
                                                <td>{{ketoan.giasauckA_MT | currency}}</td>
                                                <td>{{ketoan.feeA_MT | currency}}</td>
                                                <td>{{ketoan.cocA_MT | currency}}</td>
                                                <td>{{ketoan.giatrcckA_MT - ketoan.giasauckA_MT | currency}}</td>
                                                <td>{{formatPrice(100 - ketoan.giasauckA_MT * 100 / ketoan.giatrcckA_MT )}}%</td>
                                            </tr>
                                            <tr>
                                                <td>Tổng</td>
                                                <td>{{ketoan.giatrcck | currency}}</td>
                                                <td>{{ketoan.giasauck | currency}}</td>
                                                <td>{{ketoan.fee | currency}}</td>
                                                <td>{{ketoan.coc | currency}}</td>
                                                <td>{{ketoan.giatrcck - ketoan.giasauck | currency}}</td>
                                                <td>{{formatPrice(100 - ketoan.giasauck * 100 / ketoan.giatrcck )}}%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="showDanhgia" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Lịch Sử Duyệt</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Thời Gian</th>  
                                    <th>Số Tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(g,key) in listduyet">
                                    <tr>
                                        <td>{{g.created_at}}</td>
                                        <td>{{g.money}}</td>
                                    </tr>
                                </template>   
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <form @submit.prevent="Updatetamduyet(danhgia)">
                            <button type="submit" class="btn btn-warning" v-if="danhgia[3] == 1">Tạm Duyệt</button>
                        </form>
                        <form @submit.prevent="Updateduyet(danhgia)">
                            <button type="submit" class="btn btn-primary" v-if="danhgia[3] == 1">Duyệt</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>   

        <div class="modal fade" id="showDonHang" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title">Đơn Hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Đơn Hàng</th>  
                                    <th>Gía Trước CK</th>  
                                    <th>Gía Sau Ck</th>  
                                    <th>Phí</th>  
                                    <th>Cọc</th>  
                                    <th>Chiết Khấu</th>  
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="(g,key) in load">
                                    <tr>
                                        <td>{{g.donhang}}</td>
                                        <td>{{g.giatrcck | currency}}</td>
                                        <td>{{g.giasauck | currency}}</td>
                                        <td>{{g.fee | currency}}</td>
                                        <td>{{g.coc | currency}}</td>
                                        <td>{{g.giatrcck - g.giasauck | currency}}</td>
                                    </tr>
                                </template>   
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>  

        <div class="modal fade" id="showdetail" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <template v-for="(l,k) in listorder">
                        <div>
                            <a href="#" @click="showOrder(l.order)">
                                <span class="text-success" v-if="l.color == 2">{{k + 1}}. {{l.so_number}}</span>
                                <span class="text-danger" v-if="l.color == 1">{{k + 1}}. {{l.so_number}}</span>
                                <span v-if="l.color == 0">{{k + 1}}. {{l.so_number}}</span>
                            </a>
                            ( <template v-for="o in l.product.products">
                                <span>{{o.name}} , </span>
                            </template> )
                        </div>
                    </template>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>
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
            listduyet:[],   
            order:[],   
            orderLine:[],   
            listorder:[],   
            danhgia: [],
            load: [],
            chuagiao: [],
            ketoan: [],
            duyet: [],
            checkedCategories: '',
            giao: [],
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
            ranges: { 
                'Zip': [moment('2013-12-31').endOf('week').isoWeekday(1), moment('2025-12-31').endOf('week')],
                // '2019': [moment('2018-12-31').endOf('week').isoWeekday(1), moment('2019-12-29').endOf('week')],
                '2020': [moment('2019-12-30').endOf('week').isoWeekday(1), moment('2020-12-27').endOf('week')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
                'Z15/P1': [moment('2020-12-28').endOf('week').isoWeekday(1), moment('2021-01-24').endOf('week')],
                'Z15/P2': [moment('2021-01-25').endOf('week').isoWeekday(1), moment('2021-02-21').endOf('week')],
                'Z15/P3': [moment('2021-02-22').endOf('week').isoWeekday(1), moment('2021-03-21').endOf('week')],
                'Z15/P4': [moment('2021-03-22').endOf('week').isoWeekday(1), moment('2021-04-18').endOf('week')],
                'Z15/P5': [moment('2021-04-19').endOf('week').isoWeekday(1), moment('2021-05-16').endOf('week')],
                'Z15/P6': [moment('2021-05-17').endOf('week').isoWeekday(1), moment('2021-06-13').endOf('week')],
                'Z15/P7': [moment('2021-06-14').endOf('week').isoWeekday(1), moment('2021-07-11').endOf('week')],
                'Z14/P7': [moment('2020-06-15').endOf('week').isoWeekday(1), moment('2020-07-12').endOf('week')],
                'Z14/P8': [moment('2020-07-13').endOf('week').isoWeekday(1), moment('2020-08-09').endOf('week')],
                'Z14/P9': [moment('2020-08-10').endOf('week').isoWeekday(1), moment('2020-09-06').endOf('week')],
                'Z14/P10': [moment('2020-09-07').endOf('week').isoWeekday(1), moment('2020-10-04').endOf('week')],
                'Z14/P11': [moment('2020-10-05').endOf('week').isoWeekday(1), moment('2020-11-01').endOf('week')],
                'Z14/P12': [moment('2020-11-02').endOf('week').isoWeekday(1), moment('2020-11-29').endOf('week')],
                'Z14/P13': [moment('2020-11-30').endOf('week').isoWeekday(1), moment('2020-12-27').endOf('week')],
            },
            startDate: moment('2021-04-19').format(),
            endDate: moment('2021-05-16').format(),

            selectedDate: {
                startDate: moment('2021-04-19'),
                endDate: moment('2021-05-16')
            },
        }
    },
    methods: {
        searchdata() {
            this.getKeToan();
            this.getResults();
        },
        showdetailtong(v){
            axios.get("/api/searchordernew?start=" + v.start + '&end=' + v.end).then(response => {
                this.listorder = response.data;
            });
            $('#showdetail').modal('show');
        },
        showdetail(v){
            axios.get("/api/searchorder?start=" + v.start + '&end=' + v.end + '&stt=' + v.stt + '&orders=' + v.orders).then(response => {
                this.listorder = response.data;
            });
            $('#showdetail').modal('show');
        },
        checkdate(){
        },
    	updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
        }, 
        loadAdmin(){
            this.getResults();
        },  
        loadKetoan(){
            this.getKeToan();
        },  
        formatPrice(value) {
            let val = Math.round(value);
            return val;
        },
        getKeToan(){
            let check = this.checkedCategories;
            if(check) {
                var giao = 1;
            }else{
                var giao = 0;
            }
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10), giao ] }, { arrayFormat: 'bracket' });
            axios.get("/api/ketoanbaocao?" + dates_para).then(response => {
                this.ketoan = response.data;
            });
        },
        getResults(){
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10) ] }, { arrayFormat: 'bracket' });
        	axios.get("/api/admintarget?" + dates_para).then(response => {
                this.giao = response.data.giao;
                this.chuagiao = response.data.chuagiao;
                this.duyet = response.data.duyet;
            });
        },
        loadmore(w){
            $('#showDonHang').modal('show');
            let check = this.checkedCategories;
            if(check) {
                var giao = 1;
            }else{
                var giao = 0;
            }
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10), giao ] }, { arrayFormat: 'bracket' });
            axios.get("/api/load-w?w="+ w + '&' + dates_para).then(response => {
                this.load = response.data;
            });
        },
        pheduyet(dg){
            this.danhgia = dg;
            $('#showDanhgia').modal('show');
            axios.get("/api/showDuyetadmin?start=" + dg[1] + '&end=' + dg[2] + '&money=' + dg[0]).then(response => {
                this.listduyet = response.data;
            });
        },
        Updateduyet(danhgia){
            console.log(this.danhgia);
            axios.get("/api/updateDuyetadmin?start=" + danhgia[1] + '&end=' + danhgia[2] + '&money=' + danhgia[0]['tong'] + '&stt=1').then(response => {
                this.getResults();
                $('#showDanhgia').modal('hide');   
            });
        },
        Updatetamduyet(danhgia){
            axios.get("/api/updateDuyetadmin?start=" + danhgia[1] + '&end=' + danhgia[2] + '&money=' + danhgia[0]['tong'] + '&stt=0').then(response => {
                this.getResults();
                $('#showDanhgia').modal('hide');   
            });
        },
        showOrder(order) {
            this.order = order[0];
            this.orderLine = order[0].orderLine;
            $('#orderLineModal').modal('show');
        },
    },
    created() {
        this.getResults();
        
    }
}

</script>
