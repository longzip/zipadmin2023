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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Chọn sale</label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="resources" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheoSale">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label></label>
                            <a href="#" @click="searchdata" class="btn btn-success">Tìm </i></a>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title pull-left">Bảng target cho Sales</h3>
                <a href="#" class="btn btn-info pull-right" @click='block()'>Khóa</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="bg-info">
                        <tr>
                            <th style="width: 10px">#</th>
                            <th>Nhan vien</th>
                            <th>Showroom</th>
                            <th>KH15</th>
                            <th>KHTN <small class="text-secondary pull-right"> / Iris</small><small class="text-danger pull-right">  Ivy </small></th>
                            <th>DH</th>
                            <th>DS</th>
                        </tr>
                    </thead>
                    <tbody v-for="(showroom,key) in salesTarget">
                        <tr class="bg-light">
                            <th colspan="3">{{key}}</th>
                            <th> {{kh15Total(showroom)}}</th>
                            <th> {{total(showroom)}}</th>
                            <th> {{orderTotal(showroom)}}</th>
                            <th> {{amountTotal(showroom) | currency}}</th>
                        </tr>
                        <tr v-for="(sale, index) in showroom">
                            <td style="width: 10px">{{index}}</td>
                            <td>{{sale.name}}</td>
                            <td>{{sale.costcenter}}</td>
                            <td><a href="#" @click="showTarget(sale)">{{sale.kh15_count}}</a></td>
                            <td><a href="#" @click="showTarget(sale)">{{sale.count}}</a><small class="text-secondary pull-right"> / {{sale.iris}}</small><small class="text-danger pull-right"> {{sale.ivy}}</small></td>
                            <td><a href="#" @click="showTarget(sale)">{{sale.order_count}}</a></td>
                            <td><a href="#" @click="showTarget(sale)">{{sale.amount_count | currency}}</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="note">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Thông Báo</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <strong>Bạn đã chốt Target</strong>
                    <br>
                    Liên hệ IT để mở khóa
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="saleTargetModel" tabindex="-1" role="dialog" aria-labelledby="saleTargetModelTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saleTargetModelTitle">Target <small>{{sale.name}}</small></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                       <small class="text-secondary pull-right"> / Iris</small><small class="text-danger pull-right">  Ivy </small>
                        <table class="table table-bordered" v-show="sale.targets.length > 0">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    <th>KH15</th>
                                    <th>KHTN</th>
                                    <th>Iris</th>
                                    <th>Ivy</th>
                                    <th>DH</th>
                                    <th style="width: 130px">DS</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <thead class="bg-light">
                                <tr>
                                    <th>P</th>
                                    <th>{{sale.kh15_count}}</th>
                                    <th>{{sale.count}}</th>
                                    <th>{{sale.iris}}</th>
                                    <th>{{sale.ivy}}</th>
                                    <th>{{sale.order_count}}</th>
                                    <th>{{sale.amount_count | currency}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(target,index) in sale.targets">
                                    <td>T{{5 - ++index}}</td>
                                    <td><input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.kh15_number"></td>
                                    <td>
                                        <input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.number">
                                    </td>
                                    <td><input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.iris"></td>
                                    <td><input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.ivy"></td>
                                    <td><input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.order_number"></td>
                                    <td><input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.amount_number"></td>
                                    <td> {{ target.amount_number | currency }} </td>
                                </tr>
                            </tbody>
                        </table>
                        <form class="form-inline">
                            <div class="form-group mb-2">
                                <label for="staticEmail2">KH15</label>
                                <input v-model="target.kh15_number" type="number" class="form-control" id="staticEmail2" min="0" max="999">
                            </div>
                            <div class="form-group mb-2">
                                <label for="staticEmail2">KHTN</label>
                                <input v-model="target.number" type="number" class="form-control" id="staticEmail2" min="0" max="999">
                            </div>
                            <div class="form-group mb-2">
                                <label for="staticEmail2">DH</label>
                                <input v-model="target.order_number" type="number" class="form-control" id="staticEmail2" min="0" max="999">
                            </div>
                            <div class="form-group mb-2">
                                <label for="staticEmail2">Doanh số</label>
                                <input v-model="amount" type="number" class="form-control" id="staticEmail2" min="0" max="999999999">
                                <small>{{target.amount_number | currency}} VNĐ</small>
                            </div>
                            <!--                             <div class="form-group mx-sm-3 mb-2">
                                <label for="inputPassword2">Ngày hoàn thành</label>
                                <input v-model="target.close" type="date" class="form-control" id="inputPassword2">
                            </div> -->
                            <button type="submit" @click.prevent="createTarget()" v-on:click="counter += 1"  class="btn btn-primary mb-2">Thêm</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="store">
                            Save
                        </button>
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
            counter: 0,
            costcenters: [],
            resources: [],
            sale_ids: [],
            salesTarget: [],
            targets: {},
            sale: {
                targets: []
            },
            amount: 0,
            p: [],
            p2: [],
            target: {
                id: 0,
                name: '',
                number: 0,
                kh15_number: 0,
                order_number: 0,
                amount_number: 0,
                number1: 0,
                kh15_number1: 0,
                order_number1: 0,
                amount_number1: 0,
                number2: 0,
                kh15_number2: 0,
                order_number2: 0,
                amount_number2: 0,
                number3: 0,
                kh15_number3: 0,
                order_number3: 0,
                amount_number3: 0,
                ivy3: 0,
                iris3: 0,
                ivy2: 0,
                iris2: 0,
                ivy1: 0,
                iris1: 0,
                ivy: 0,
                iris: 0,

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
            ranges: { 
                'Zip': [moment('2013-12-31').endOf('week').isoWeekday(1), moment('2025-12-31').endOf('week')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
                'Z15/P1': [moment('2020-12-28').endOf('week').isoWeekday(1), moment('2021-01-24').endOf('week')],
                'Z15/P2': [moment('2021-01-25').endOf('week').isoWeekday(1), moment('2021-02-21').endOf('week')],
                'Z15/P3': [moment('2021-02-22').endOf('week').isoWeekday(1), moment('2021-03-21').endOf('week')],
                'Z15/P4': [moment('2021-03-22').endOf('week').isoWeekday(1), moment('2021-04-18').endOf('week')],
                'Z15/P5': [moment('2021-04-19').endOf('week').isoWeekday(1), moment('2021-05-16').endOf('week')],
                'Z15/P6': [moment('2021-05-17').endOf('week').isoWeekday(1), moment('2021-06-13').endOf('week')],
                'Z15/P7': [moment('2021-06-14').endOf('week').isoWeekday(1), moment('2021-07-11').endOf('week')],
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
            uri: '/api/sales-targets',
        }
    },
    methods: {
        searchdata() {
            this.loadSalesTarget();
        },
        block() {
            axios.get('api/block-targetsale'+ '?' + this.getPara())
            .then(()=> {
                swal.fire({
                        type: 'success',
                        title: 'Thành Công...',
                        text: 'Bạn Đã Khóa Target!',
                        footer: '<a href>Liên hệ IT khi mở Target?</a>'
                    })
                this.loadSalesTarget();
            })
            .catch(() => {
                swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Bạn không có quyền cập nhật!',
                        footer: '<a href>Liên hệ IT để có thông tin?</a>'
                    })
            });
        },
        loadSalesTarget() {
            this.$Progress.start();
            axios.get(this.uri + '?' + this.getPara())
                .then(responsive => {
                    this.salesTarget = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.startDate = moment(this.startDate).add(1, 'day').format().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.endDate = moment(this.endDate).endOf('week').format().slice(0, 10);
            this.addWeek();
        },
        addWeek() {
            this.p = [];
            var date = moment(this.endDate).endOf('week').format().slice(0, 10);
            this.p.push(date);
            this.p.push(moment(date).subtract(1, 'week').format().slice(0, 10));
            this.p.push(moment(date).subtract(2, 'week').format().slice(0, 10));
            this.p.push(moment(date).subtract(3, 'week').format().slice(0, 10));
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
            }

        },
        timTheoSale() {
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
        resetTarget(target) {
            this.amountChange(this.amount);
            // target.number = this.target.number;
            // target.kh15_number = this.target.kh15_number;
            // target.order_number = this.target.order_number;
            // target.amount_number = this.target.amount_number;
        },
        showTarget(sale) {
            this.sale = sale;
            if(this.sale.status == 1) {
                $('#saleTargetModel').modal('show');
            }
            if(this.sale.status == 0) {
                $('#note').modal('show');
            }
        },
       
        total(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + Number(sale.count);
            }, 0);
        },
        kh15Total(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + Number(sale.kh15_count);
            }, 0);
        },
        orderTotal(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + Number(sale.order_count);
            }, 0);
        },
        amountTotal(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + Number(sale.amount_count);
            }, 0);
        },
        recalculator() {
            this.sale.count = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.number);
            }, 0);
            this.sale.kh15_count = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.kh15_number);
            }, 0);
            this.sale.amount_count = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.amount_number);
            }, 0);
            this.sale.order_count = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.order_number);
            }, 0);
            this.sale.iris = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.iris);
            }, 0);
            this.sale.ivy = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.ivy);
            }, 0);
        },
        createTarget() {
            var p2 = this.sale.targets.map(function(target) {
                return target.close;
            });

            var p = this.p;
            for (var i = 0; i < 4; i++) {
                if (_.indexOf(p2, p[i]) == -1) {
                    if (i == 0) {
                        this.sale.targets.push({
                            id: this.sale.id,
                            name: '',
                            number: this.target.number3,
                            kh15_number: this.target.kh15_number3,
                            order_number: this.target.order_number3,
                            amount_number: this.target.amount_number3,
                            dahlia: this.target.dahlia3,
                            ivy: this.target.ivy3,
                            iris: this.target.iris3,
                            khac: this.target.khac3,
                            costcenterId: this.sale.id,
                            close: p[i]
                        });

                    }
                    if(i == 1) {
                        this.sale.targets.push({
                            id: this.sale.id,
                            name: '',
                            number: this.target.number2,
                            kh15_number: this.target.kh15_number2,
                            order_number: this.target.order_number2,
                            amount_number: this.target.amount_number2,
                            dahlia: this.target.dahlia2,
                            ivy: this.target.ivy2,
                            iris: this.target.iris2,
                            khac: this.target.khac2,
                            costcenterId: this.sale.id,
                            close: p[i]
                        });
                    }
                    if(i == 2) {
                        this.sale.targets.push({
                            id: this.sale.id,
                            name: '',
                            number: this.target.number1,
                            kh15_number: this.target.kh15_number1,
                            order_number: this.target.order_number1,
                            amount_number: this.target.amount_number1,
                            dahlia: this.target.dahlia1,
                            ivy: this.target.ivy1,
                            iris: this.target.iris1,
                            khac: this.target.khac1,
                            costcenterId: this.sale.id,
                            close: p[i]
                        });
                    }
                    if(i == 3) {
                        this.sale.targets.push({
                            id: this.sale.id,
                            name: '',
                            number: this.target.number,
                            kh15_number: this.target.kh15_number,
                            order_number: this.target.order_number,
                            amount_number: this.target.amount_number,
                            dahlia: this.target.dahlia,
                            ivy: this.target.ivy,
                            iris: this.target.iris,
                            khac: this.target.khac,
                            costcenterId: this.sale.id,
                            close: p[i]
                        });
                    }
                }
            }

            if (this.counter > 0) {
                _.each(this.sale.targets, this.resetTarget);
            }

            this.recalculator();
        },
        store() {
            axios.post('api/sales-targets', this.sale.targets)
                .then(() => {
                    $('#saleTargetModel').modal('hide');
                    this.loadSalesTarget();
                })
                .catch(() => {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Bạn không có quyền cập nhật!',
                        footer: '<a href>Liên hệ ASM để có thông tin?</a>'
                    })
                });
        },
        amountChange(newAmount) {
            this.target.amount_number = newAmount * 1000000 * 0.3 ;
            this.target.order_number = Math.round(newAmount / 20 * 0.3 );
            this.target.number = this.target.order_number * 3;
            this.target.kh15_number = this.target.order_number * 9;
            this.target.dahlia = Math.round(this.target.number * 0.2);
            this.target.ivy = Math.round(this.target.number * 0.2);
            this.target.iris = Math.round(this.target.number * 0.3);
            this.target.khac = this.target.number - this.target.iris - this.target.dahlia - this.target.ivy;

            this.target.amount_number3 = newAmount * 1000000 * 0.15 ;
            this.target.order_number3 = Math.round(newAmount / 20 * 0.15 );
            this.target.number3 = this.target.order_number3 * 3;
            this.target.kh15_number3 = this.target.order_number3 * 9;
            this.target.dahlia3 = Math.round(this.target.number3 * 0.2);
            this.target.ivy3 = Math.round(this.target.number3 * 0.2);
            this.target.iris3 = Math.round(this.target.number3 * 0.3);
            this.target.khac3 = this.target.number3 - this.target.iris3 - this.target.dahlia3 - this.target.ivy3;

            this.target.amount_number1 = newAmount * 1000000 * 0.35 ;
            this.target.order_number1 = Math.round(newAmount / 20 * 0.35 );
            this.target.number1 = this.target.order_number1 * 3;
            this.target.kh15_number1 = this.target.order_number1 * 9;
            this.target.dahlia1 = Math.round(this.target.number1 * 0.2);
            this.target.ivy1 = Math.round(this.target.number1 * 0.2);
            this.target.iris1 = Math.round(this.target.number1 * 0.3);
            this.target.khac1 = this.target.number1 - this.target.iris1 - this.target.dahlia1 - this.target.ivy1;

            this.target.amount_number2 = newAmount * 1000000 * 0.2 ;
            this.target.order_number2 = Math.round(newAmount / 20 * 0.2 );
            this.target.number2 = this.target.order_number2 * 3;
            this.target.kh15_number2 = this.target.order_number2 * 9;
            this.target.dahlia2 = Math.round(this.target.number2 * 0.2);
            this.target.ivy2 = Math.round(this.target.number2 * 0.2);
            this.target.iris2 = Math.round(this.target.number2 * 0.3);
            this.target.khac2 = this.target.number2 - this.target.iris2 - this.target.dahlia2 - this.target.ivy2;
            this.sale.targets = [];
            var p2 = this.sale.targets.map(function(target) {
                return target.close;
            });

            var p = this.p;

            for (var i = 0; i < 4; i++) {
                if (_.indexOf(p2, p[i]) == -1) {
                    if (i == 0) {
                        this.sale.targets.push({
                            id: this.sale.id,
                            name: '',
                            number: this.target.number3,
                            kh15_number: this.target.kh15_number3,
                            order_number: this.target.order_number3,
                            amount_number: this.target.amount_number3,
                            dahlia: this.target.dahlia3,
                            ivy: this.target.ivy3,
                            iris: this.target.iris3,
                            khac: this.target.khac3,
                            costcenterId: this.sale.id,
                            close: p[i]
                        });

                    }
                    if(i == 1) {
                        this.sale.targets.push({
                            id: this.sale.id,
                            name: '',
                            number: this.target.number2,
                            kh15_number: this.target.kh15_number2,
                            order_number: this.target.order_number2,
                            amount_number: this.target.amount_number2,
                            dahlia: this.target.dahlia2,
                            ivy: this.target.ivy2,
                            iris: this.target.iris2,
                            khac: this.target.khac2,
                            costcenterId: this.sale.id,
                            close: p[i]
                        });
                    }
                    if(i == 2) {
                        this.sale.targets.push({
                            id: this.sale.id,
                            name: '',
                            number: this.target.number1,
                            kh15_number: this.target.kh15_number1,
                            order_number: this.target.order_number1,
                            amount_number: this.target.amount_number1,
                            dahlia: this.target.dahlia1,
                            ivy: this.target.ivy1,
                            iris: this.target.iris1,
                            khac: this.target.khac1,
                            costcenterId: this.sale.id,
                            close: p[i]
                        });
                    }
                    if(i == 3) {
                        this.sale.targets.push({
                            id: this.sale.id,
                            name: '',
                            number: this.target.number,
                            kh15_number: this.target.kh15_number,
                            order_number: this.target.order_number,
                            amount_number: this.target.amount_number,
                            dahlia: this.target.dahlia,
                            ivy: this.target.ivy,
                            iris: this.target.iris,
                            khac: this.target.khac,
                            costcenterId: this.sale.id,
                            close: p[i]
                        });
                    }
                }
            }
                    console.log(this.sale.targets);

        }
    },
    created() {
        axios.get('api/users-group-costcenters')
            .then(res => this.costcenters = res.data);
        axios.get('/api/picklists/users')
            .then(res => this.resources = res.data);
        Fire.$on('searching', () => {
            this.loadSalesTarget();
        });
        this.addWeek();
    },
    async mounted() {
        this.loadSalesTarget();
    },
    watch: {
        // whenever question changes, this function will run
        amount: function(newAmount, oldAmount) {
            this.target.amount_number = newAmount * 1000000 * 0.3 ;
            this.target.order_number = Math.round(newAmount / 25 * 0.3 );
            this.target.number = this.target.order_number * 3;
            this.target.kh15_number = this.target.order_number * 9;
            this.target.dahlia = Math.round(this.target.number * 0.2);
            this.target.ivy = Math.round(this.target.number * 0.2);
            this.target.iris = Math.round(this.target.number * 0.3);
            this.target.khac = this.target.number - this.target.iris - this.target.dahlia - this.target.ivy;

            this.target.amount_number3 = newAmount * 1000000 * 0.15 ;
            this.target.order_number3 = Math.round(newAmount / 25 * 0.15 );
            this.target.number3 = this.target.order_number3 * 3;
            this.target.kh15_number3 = this.target.order_number3 * 9;
            this.target.dahlia3 = Math.round(this.target.number3 * 0.2);
            this.target.ivy3 = Math.round(this.target.number3 * 0.2);
            this.target.iris3 = Math.round(this.target.number3 * 0.3);
            this.target.khac3 = this.target.number3 - this.target.iris3 - this.target.dahlia3 - this.target.ivy3;

            this.target.amount_number1 = newAmount * 1000000 * 0.35 ;
            this.target.order_number1 = Math.round(newAmount / 25 * 0.35 );
            this.target.number1 = this.target.order_number1 * 3;
            this.target.kh15_number1 = this.target.order_number1 * 9;
            this.target.dahlia1 = Math.round(this.target.number1 * 0.2);
            this.target.ivy1 = Math.round(this.target.number1 * 0.2);
            this.target.iris1 = Math.round(this.target.number1 * 0.3);
            this.target.khac1 = this.target.number1 - this.target.iris1 - this.target.dahlia1 - this.target.ivy1;

            this.target.amount_number2 = newAmount * 1000000 * 0.2 ;
            this.target.order_number2 = Math.round(newAmount / 25 * 0.2 );
            this.target.number2 = this.target.order_number2 * 3;
            this.target.kh15_number2 = this.target.order_number2 * 9;
            this.target.dahlia2 = Math.round(this.target.number2 * 0.2);
            this.target.ivy2 = Math.round(this.target.number2 * 0.2);
            this.target.iris2 = Math.round(this.target.number2 * 0.3);
            this.target.khac2 = this.target.number2 - this.target.iris2 - this.target.dahlia2 - this.target.ivy2;
        }
    },
}

</script>
