<template>
    <div class="container">
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
                            <label>Chọn ngày</label>
                            <date-range-picker style="display: block;" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges">
                                <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                            </date-range-picker>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn Showroom</label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="costcenters" :multiple="true" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoShowroom">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn sale</label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="resources" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheoSale">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Bang target cho Sales</h3>
                <div class="card-tool">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link" href="#KHTN" @click="updateKHTN">KHTN</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#KH15" @click="updateKh15s">KH15</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#ĐH" @click="updateOrders">Đơn hàng</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#DS" @click="updateAmount">Doanh số</a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-info">
                            <tr>
                                <th rowspan="2" style="width: 10px">#</th>
                                <th rowspan="2">Nhan vien</th>
                                <th colspan="3">T1</th>
                                <th colspan="3">T2</th>
                                <th colspan="3">T3</th>
                                <th colspan="3">T4</th>
                                <th colspan="3">P</th>
                            </tr>
                            <tr>
                                <th>Ta </th>
                                <th>HT </th>
                                <th>%</th>
                                <th>Ta </th>
                                <th>HT </th>
                                <th>%</th>
                                <th>Ta </th>
                                <th>HT </th>
                                <th>%</th>
                                <th>Ta </th>
                                <th>HT </th>
                                <th>%</th>
                                <th>Ta </th>
                                <th>HT </th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody v-for="(showroom,key) in salesTarget">
                            <tr class="bg-yellow">
                                <th colspan="17">{{key}}</th>
                            </tr>
                            <tr v-for="(sale, index) in showroom">
                                <td style="width: 10px">{{++index}}</td>
                                <td>{{sale.name}}</td>
                                <td class="bg-light">{{sale.t0 | myNumber}}</td>
                                <td class="bg-light font-weight-bold">{{sale.c0 | myNumber}}</td>
                                <td class="bg-light">{{sale.t0 > 0 ? Math.round((sale.c0/sale.t0)*100) + '%' : ''}}</td>
                                <td>{{sale.t1 | myNumber}}</td>
                                <td class="font-weight-bold">{{sale.c1 | myNumber}}</td>
                                <td>{{sale.t1 > 0 ? Math.round((sale.c1/sale.t1)*100) + '%' : ''}}</td>
                                <td class="bg-light">{{sale.t2 | myNumber}}</td>
                                <td class="bg-light font-weight-bold">{{sale.c2 | myNumber}}</td>
                                <td class="bg-light">{{sale.t2 > 0 ? Math.round((sale.c2/sale.t2)*100) + '%' : ''}}</td>
                                <td>{{sale.t3 | myNumber}}</td>
                                <td class="font-weight-bold">{{sale.c3 | myNumber}}</td>
                                <td>{{sale.t3 > 0 ? Math.round((sale.c3/sale.t3)*100) + '%' : ''}}</td>
                                <td class="bg-green">{{(sale.t0 + sale.t1 + sale.t2 + sale.t3) | myNumber}}</td>
                                <td class="bg-green font-weight-bold">{{(sale.c0 + sale.c1 + sale.c2 + sale.c3) | myNumber}}</td>
                                <td class="bg-green">{{(sale.t0 + sale.t1 + sale.t2 + sale.t3) > 0 ? Math.round(((sale.c0 + sale.c1 + sale.c2 + sale.c3)/(sale.t0 + sale.t1 + sale.t2 + sale.t3))*100) + '%' : ''}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal fade" id="saleTargetModel" tabindex="-1" role="dialog" aria-labelledby="saleTargetModelTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saleTargetModelTitle">Target <small>{{sale.name}}</small></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead class="bg-info">
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Ngày hoàn thành</th>
                                    <th>KH15</th>
                                    <th>KHTN</th>
                                    <th>DH</th>
                                    <th>DS</th>
                                    <th>Cập nhật</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(target,index) in sale.targets">
                                    <td>{{++index}}</td>
                                    <td>{{target.close}}</td>
                                    <td>{{target.kh15_number}}</td>
                                    <td>{{target.number}}</td>
                                    <td></td>
                                    <td>{{target.amount_number | currency}}</td>
                                    <td> ... </td>
                                </tr>
                            </tbody>
                        </table>
                        <form class="form-inline">
                            <div class="form-group mb-2">
                                <label for="staticEmail2">KH15</label>
                                <input v-model="target.kh15_number" type="number" class="form-control" id="staticEmail2" min="0" max="5">
                            </div>
                            <div class="form-group mb-2">
                                <label for="staticEmail2">KHTN</label>
                                <input v-model="target.number" type="number" class="form-control" id="staticEmail2" min="0" max="5">
                            </div>
                            <div class="form-group mb-2">
                                <label for="staticEmail2">DH</label>
                                <input v-model="target.order_number" type="number" class="form-control" id="staticEmail2" min="0" max="5">
                            </div>
                            <div class="form-group mb-2">
                                <label for="staticEmail2">DS</label>
                                <input v-model="target.amount_number" type="number" class="form-control" id="staticEmail2" min="0" max="5">
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="inputPassword2">Ngày hoàn thành</label>
                                <input v-model="target.close" type="date" class="form-control" id="inputPassword2">
                            </div>
                            <button type="submit" @click.prevent="addTarget()" class="btn btn-primary mb-2">Thêm</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
            costcenters: [],
            resources: [],
            sale_ids: [],
            salesTarget: [],
            targets: {},
            sale: {},
            target: {
                id: 0,
                name: '',
                number: 1,
                kh15_number: 3,
                order_number: 0,
                amount_number: 0,
                close: moment().endOf('week').format().slice(0, 10)
            },
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
                'P1': [moment('2019-01-01').startOf('week'), moment('2019-01-27')],
                'P2': [moment('2019-01-28').startOf('week'), moment('2019-02-24')],
                'P3': [moment('2019-02-25').startOf('week'), moment('2019-03-24')],
                'P4': [moment('2019-03-25').startOf('week'), moment('2019-04-21')],
                'P5': [moment('2019-04-22').startOf('week'), moment('2019-05-19')],
                'P6': [moment('2019-05-20').startOf('week'), moment('2019-06-16')],
                'P7': [moment('2019-06-17').startOf('week'), moment('2019-07-14')],
                'P8': [moment('2019-07-15').startOf('week'), moment('2019-08-11')],
                'P9': [moment('2019-08-12').startOf('week'), moment('2019-09-08')],
                'P10': [moment('2019-09-09').startOf('week'), moment('2019-10-06')],
                'P11': [moment('2019-10-09').startOf('week'), moment('2019-11-03')],
                'P12': [moment('2019-11-04').startOf('week'), moment('2019-12-01')],
                'P13': [moment('2019-12-02').startOf('week'), moment('2019-12-29')],
            },
            startDate: moment('2021-04-19').format(),
            endDate: moment('2021-05-16').format(),

            selectedDate: {
                startDate: moment('2021-04-19'),
                endDate: moment('2021-05-16')
            },
            uri: '/api/contacts-targets',
        }
    },
    methods: {
        updateKh15s() {
            this.uri = '/api/leads-targets';
            this.loadSalesTarget();
        },
        updateKHTN() {
            this.uri = '/api/contacts-targets';
            this.loadSalesTarget();
        },
        updateOrders() {
            this.uri = '/api/orders-targets';
            this.loadSalesTarget();
        },
        updateAmount() {
            this.uri = '/api/orders-targets-2';
            this.loadSalesTarget();
        },
        loadSalesTarget() {
            this.$Progress.start();
            axios.get(this.uri + '?' + this.getPara())
                .then(responsive => {
                    this.salesTarget = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.salesTarget = [];
                    this.$Progress.fail();
                });
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.startDate = moment(this.startDate).add(1, 'day').format().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.endDate = moment(this.endDate).endOf('week').format().slice(0, 10);
            this.loadSalesTarget();
        },
        timTheoShowroom() {
            let costcenter_ids = this.$parent.showroomsSelected.map(costcenter => {
                return costcenter.id;
            });
            if (costcenter_ids.length > 0) {
                let uri = 'api/find-user-by-costcenter?' + queryString.stringify({ costcenters: costcenter_ids }, { arrayFormat: 'bracket' });
                axios.get(uri)
                    .then(response => {
                        this.$parent.saleSelected = this.resources = response.data;
                        this.loadSalesTarget();
                    });
            } else {
                this.$parent.saleSelected = [];
                this.loadSalesTarget();
            }

        },
        timTheoSale() {
            this.loadSalesTarget();
        },

        getPara() {
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id
            });
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let uri = queryString.stringify({ users: this.sale_ids }, { arrayFormat: 'bracket' });
            return uri + '&' + dates_para + '&' + queryName;
        },
        showTarget(sale) {
            this.sale = sale;
            $('#saleTargetModel').modal('show');
        },
        addTarget() {

            axios.post('api/sales-targets', {
                    userId: this.sale.id,
                    number: this.target.number,
                    kh15_number: this.target.kh15_number,
                    order_number: this.target.order_number,
                    amount_number: this.target.amount_number,
                    close: this.target.close
                })
                .then(responsive => {
                    this.sale.targets.push({
                        id: this.target.id,
                        name: '',
                        number: this.target.number,
                        kh15_number: this.target.kh15_number,
                        order_number: this.target.order_number,
                        amount_number: this.target.amount_number,
                        close: this.target.close
                    });
                    this.target.number = 0;
                    this.target.kh15_number = 0;
                    this.target.order_number = 0;
                    this.target.amount_number = 0;
                    this.sale.count = this.sale.targets.reduce(function(accumulator, target) {
                        return accumulator + target.number;
                    }, 0);
                    this.sale.kh15_count = this.sale.targets.reduce(function(accumulator, target) {
                        return accumulator + target.kh15_number;
                    }, 0);
                    this.sale.amount_count = this.sale.targets.reduce(function(accumulator, target) {
                        return accumulator + target.amount_number;
                    }, 0);
                    this.sale.order_count = this.sale.targets.reduce(function(accumulator, target) {
                        return accumulator + target.order_number;
                    }, 0);
                })
        },
        total(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + sale.count;
            }, 0);
        },
        kh15Total(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + sale.kh15_count;
            }, 0);
        },
        orderTotal(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + sale.order_count;
            }, 0);
        },
        amountTotal(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + sale.amount_count;
            }, 0);
        }
    },
    created() {
        axios.get('api/users-group-costcenters')
            .then(res => this.costcenters = res.data);
        axios.get('/api/picklists/users')
            .then(res => this.resources = res.data);
        Fire.$on('searching', () => {
            this.loadSalesTarget();
        })
    },
    async mounted() {
        this.loadSalesTarget();
    }
}

</script>
