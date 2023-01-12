<template>
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Tìm kiếm</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn Khu Vực <a href="#" @click="reSelectArea"><i class="fa fa-refresh"></i></a></label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="resources" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn ngày</label>
                            <date-range-picker style="display: block;" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="$parent.selectedDate" @update="updatetime" :ranges="ranges">
                                <div slot="input" slot-scope="picker">{{ $parent.selectedDate.startDate | myDate }} - {{ $parent.selectedDate.endDate | myDate }}</div>
                            </date-range-picker>
                        </div>
                    </div>
                    <div class="form-group">
                        <label></label>
                        <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <ul class="nav nav-tabs">
                    <li class="nav-item text-center">
                        <a class="nav-link active" href="#kh15" @click="updateValues1" role="tab" data-toggle="tab">KH15</a>
                        <span v-for="lead in leads" :key="lead.id" class=" badge badge-info">
                            <i v-if="lead.total != null">{{ lead.total }}</i>
                        </span>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link" href="#khtn" @click="updateValues2" role="tab" data-toggle="tab">KHTN</a>
                        <span v-for="contact in contacts" :key="contact.id" class=" badge badge-info">
                            <i v-if="contact.total != null">{{ contact.total }}</i>
                        </span>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link" href="#dh" @click="updateValues3" role="tab" data-toggle="tab">Đơn Hàng</a>
                        <span v-for="order in orders" :key="order.id" class=" badge badge-info">
                            <i v-if="order.total != null">{{ order.total }}</i>
                        </span>
                    </li>
                    <li class="nav-item text-center">
                        <a class="nav-link" href="#da" @click="updateValues4" role="tab" data-toggle="tab">Dự Án</a>
                        <span v-for="project in projects" :key="project.id" class=" badge badge-info">
                            <i v-if="project.total != null">{{ project.total }}</i>
                        </span>
                    </li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade in active show" id="kh15">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <lead-list v-bind:leads="getLeads" @deleted="taivekh15"></lead-list>
                            </div>         
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <pagination :data="leads" :limit=3 @pagination-change-page="taivekh15"><span slot="prev-nav">&lt; Trước</span>
                                    <span slot="next-nav">Sau &gt;</span></pagination>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="khtn">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <contact-lists :contacts="contacts.data" @deleted="loadcontacts"></contact-lists>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <pagination :data="contacts" :limit=3 @pagination-change-page="loadcontacts"><span slot="prev-nav">&lt; Trước</span>
                                    <span slot="next-nav">Sau &gt;</span></pagination>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="dh">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="card">
                                    <!-- /.card-header -->
                                    <div class="card-body table-responsive p-0">
                                        <table class="table table-hover">
                                            <tbody>
                                                <tr>
                                                    <th>SDH</th>
                                                    <th>Mã SR</th>
                                                    <th>Tạo bởi</th>
                                                    <th>Ngày đặt hàng</th>
                                                    <th>Ngày giao</th>
                                                    <th>Sản phẩm</th>
                                                    <th>Tổng tiền</th>
                                                    <th>Đã thu</th>
                                                    <th>Còn nợ</th>
                                                    <th>Trạng thái</th>
                                                    <th>Modify</th>
                                                </tr>
                                                <tr v-for="order in orders.data" :key="order.id">
                                                    <td><a href="#" @click="showOrder(order)">{{order.so_number}}</a></td>
                                                    <td>{{order.costcenter}}</td>
                                                    <td>{{order.user_name}}</td>
                                                    <td>{{order.created_at | myDate}}</td>
                                                    <td>{{order.delivery_date | myDate}}</td>
                                                    <td>
                                                        <products :items="order.products"></products>
                                                    </td>
                                                    <td>{{order.amount | currency}}</td>
                                                    <td>{{order.pre_pay | currency}}</td>
                                                    <td>{{order.amount - order.pre_pay | currency}}</td>
                                                    <td><span class="badge" v-bind:class="[order.status_id == 2 ? 'badge-success' : 'badge-warning']">{{order.status_id | trangThaiSO}}</span></td>
                                                    <td>
                                                        <a href="#" @click="editOrder(order.so_number)">
                                                            <i class="fa fa-edit blue"></i>
                                                        </a>
                                                        /
                                                        <a href="#" @click="deleteOrder(order.id)">
                                                            <i class="fa fa-trash red"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <pagination :data="orders" @pagination-change-page="getResults"></pagination>
                                        <small>Hiển thị 25 đơn hàng từ {{orders.from}} đến {{orders.to}} / {{orders.total}} </small>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="da">
                        <div class="card-body table-responsive p-0">
                            <project-lists :contacts="projects.data" @deleted="loadprojects"></project-lists>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <pagination :data="projects" :limit=3 @pagination-change-page="loadprojects"><span slot="prev-nav">&lt; Trước</span>
                                <span slot="next-nav">Sau &gt;</span></pagination>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
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
                                <address>
                                    <strong>{{ order.customer_name}}</strong><br>
                                    {{ order.customer_address}}<br>
                                    Điện thoại: {{ order.customer_phone}}
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6 invoice-col">
                                <strong>Số đơn hàng #{{order.so_number}}</strong><br>
                                <strong>Ngày đơn hàng: </strong>{{order.delivery_date | myDate}}<br>
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
import LeadList from './elements/LeadLists.vue';
import SearchShowroom from './elements/SearchShowroom.vue';
import ContactLists from './elements/ContactLists.vue';
import ProjectLists from './elements/ProjectList.vue';
export default {
    components: { LeadList,SearchShowroom, ContactLists , ProjectLists  },
    data() {
        return {
            projects: {},
            orderLine: {},
            orders: {},
            order: {},
            editmode: false,
            form: new Form({
                id: '',
                title: '',
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                company: '',
                address: '',
                city: '',
                description: '',
                start_date: '',
                start_time: '',
                end_time: ''
            }),
            leads: {},
            startDate: moment().subtract(7, 'days').format(),
            endDate: moment().format(),
            selectedDate: {
                startDate: moment().subtract(7, 'days').format(),
                endDate: moment().format()
            },
            contacts: {},
            opens: "center",
            showroomsSelected: [],
            salesareasSelected: [],
            costcenters: [],
            salesareas: [],
            saleSelected: [],
            resources: [],
            sale_ids: [],
            salesareaIds: [],
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
                'Hôm nay': [moment(), moment()],
                'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                // 'Tuần này': [moment().subtract(7, 'days'), moment()],
                'Tuần trước': [moment().subtract(1, 'week').startOf('week'), moment()],
                'Hai tuần trước': [moment().subtract(2, 'week').startOf('week'), moment()],
                'Ba tuần trước': [moment().subtract(3, 'week').startOf('week'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
                // 'Tuần qua': [moment().subtract(1, 'week').startOf('week'), moment().subtract(1, 'week').endOf('week')],
                // 'Tháng qua': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],


            }
        }
    },

    methods: {
        searchdata() {
            this.taivekh15();
            this.loadcontacts(); 
            this.getResults(); 
            this.loadprojects(); 
        },
        reSelectArea() {
            this.$parent.saleSelected = [];
            this.loadcontacts();
        },
        updatetime(values) {
            this.$parent.startDate = values.startDate.toISOString().slice(0, 10)
            this.$parent.endDate = values.endDate.toISOString().slice(0, 10)
                      
        },
        updateValues1(values) {
            this.updatetime();
            this.taivekh15();
        },
        updateValues2(values) {
            this.updatetime();
            this.loadcontacts();
        },
        updateValues3(values) {
            this.updatetime();
            this.getResults();
        },
        updateValues4(values) {
            this.updatetime();
            this.loadprojects();
        },
        taivekh15(page = 1) {
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id
            });
            let url = "/api/leads?page=" + page;
            let dates_para = queryString.stringify({ dates: [this.$parent.startDate.slice(0, 10), this.$parent.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let salesareaIds = queryString.stringify({ salesareas: this.sale_ids }, { arrayFormat: 'bracket' });
            axios.get(url.concat('&',dates_para,'&',salesareaIds))
                .then(response => {
                    this.leads = response.data;
                });
        },
        loadcontacts(page = 1) {
            axios.get("/api/contacts?page=" + page + '&' + this.getPara())
                .then(response => {
                    this.contacts = response.data;
                });
        },
        getPara(){
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id
            });
            let dates_para = queryString.stringify({ dates: [this.$parent.startDate.slice(0, 10), this.$parent.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let salesareaIds = queryString.stringify({ salesareas: this.sale_ids }, { arrayFormat: 'bracket' });
            return  dates_para + '&' + salesareaIds;
        },
        loadprojects(page = 1) {
            axios.get("/api/project?page=" + page + '&' + this.getParaProject())
                .then(response => {
                    this.projects = response.data;
                });
        },
        getParaProject(){
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id
            });
            let dates_para = queryString.stringify({ dates: [this.$parent.startDate.slice(0, 10), this.$parent.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let salesareaIds = queryString.stringify({ salesareas: this.sale_ids }, { arrayFormat: 'bracket' });
            return  dates_para + '&' + salesareaIds;
        },
        getResults(page = 1) {
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id
            });
            let url = "/api/orders?page=" + page;
            let dates_para = queryString.stringify({ dates: [this.$parent.startDate.slice(0, 10), this.$parent.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let salesareaIds = queryString.stringify({ salesareas: this.sale_ids }, { arrayFormat: 'bracket' });
            axios.get(url.concat('&',dates_para,'&',salesareaIds))
                .then(response => {
                    this.orders = response.data;
                });
        },
        showOrder(order) {
            this.order = order;
            this.orderLine = order.orderLine;
            $('#orderLineModal').modal('show');
        },
    },
    computed: {
        getLeads() {
            return this.leads.data;
            return this.orders.data;
        }
    },
    created() {
        this.taivekh15();
        this.loadcontacts();
        this.getResults();
        this.loadprojects();
        Fire.$on('searching', () => {
            this.taivekh15();
            this.loadcontacts();
            this.getResults();
            this.loadprojects();
        })
        Fire.$on('AfterCreate', () => {
            this.taivekh15();
            this.loadcontacts();
            this.getResults();
            this.loadprojects();
        });
        axios.get('/api/picklists/areasPicklists')
            .then(res => this.resources = res.data);
    }
}

</script>
