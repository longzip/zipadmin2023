<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-4">
                <h1 class="m-0 text-dark">Khu Vực Target</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <date-range-picker class="pull-right mt-2" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges" :alwaysShowCalendars="false">
                    <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                </date-range-picker>
            </div><!-- /.col -->
        </div>
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#khtn" @click="updateByKHTN">KHTN</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#kh15" @click="updateByKH15">KH15</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#dh" @click="updateByDH">Đơn Hàng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#ds" @click="updateByDS">Doanh Số</a>
            </li>
        </ul>
        <div class="row" id="chung">
            <div class="tableFixHead table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="max-width:100px;">Showroom</th>
                            <th>P</th>
                            <th @click="detailTarget(0)">T1</th>
                            <th @click="detailTarget(1)">T2</th>
                            <th @click="detailTarget(2)">T3</th>
                            <th @click="detailTarget(3)">T4</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="row" id="amount" style="display:none;">
            <div class="tableFixHead table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="max-width:100px;">Showroom</th>
                            <th>P</th>
                            <th @click="detailTarget(0)">T1</th>
                            <th @click="detailTarget(1)">T2</th>
                            <th @click="detailTarget(2)">T3</th>
                            <th @click="detailTarget(3)">T4</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="modelProducts" tabindex="-1" role="dialog" aria-labelledby="taskModalDetailTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalDetailTitle">Chi Tiết Sản Phẩm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-dark table-bordered">
                            <thead>
                                <td>P/T</td>
                                <td>Daliah</td>
                                <td>Ivy</td>
                                <td>Iris</td>
                                <td>Khác</td>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modelTotalProducts" tabindex="-1" role="dialog" aria-labelledby="taskModalDetailTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalDetailTitle">Chi Tiết Sản Phẩm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-dark table-bordered">
                            <thead>
                                <td>P/T</td>
                                <td>Daliah</td>
                                <td>Ivy</td>
                                <td>Iris</td>
                                <td>Khác</td>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            sp:{},
            sales: [],
            topCount: 0,
            detaildata:[],
            value:0,
            checkT:0,
            opens: "center",
            checkP:'', //which way the picker opens, default "center", can be "left"/"right"
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
            ura: '/api/detailTarget',
            uri: '/api/users-target',
        }
    },
    methods: {
        showtotal(value){
            console.log(value);
            $('#modelTotalProducts').modal('show');
        },
        detailTarget(value) {
            // console.log(value);
            this.value = value;
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)], value:this.value}, { arrayFormat: 'bracket' }, {});
            axios.get(this.ura + '?' + dates_para)
                .then(responsive => {
                    this.detaildata = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
          $('#DetailCenter').modal({backdrop: 'static', keyboard: false});
          this.fillP(this.startDate);

        },
        fillP(value){
            var v = moment(String(value)).format('YYYY-MM-DD');
            if(v == '2019-05-20'){this.checkP = 'P6';}
            if(v == '2019-06-17'){this.checkP = 'P7';}
            if(v == '2019-07-15'){this.checkP = 'P8';}
            if(v == '2019-08-12'){this.checkP = 'P9';}
            if(v == '2019-09-09'){this.checkP = 'P10';}
            if(v == '2019-10-09'){this.checkP = 'P11';}
            if(v == '2019-11-04'){this.checkP = 'P12';}
            if(v == '2019-12-02'){this.checkP = 'P13';}
            if(v == '2019-12-30'){this.checkP = 'P1/Z14';}
            if(v == '2020-01-27'){this.checkP = 'P2/Z14';}
            if(v == '2020-02-24'){this.checkP = 'P3/Z14';}
            if(v == '2020-03-23'){this.checkP = 'P4/Z14';}
            if(v == '2020-04-20'){this.checkP = 'P5/Z14';}
            if(v == '2020-05-18'){this.checkP = 'P6/Z14';}
            if(v == '2020-06-15'){this.checkP = 'P7/Z14';}
            if(v == '2020-07-13'){this.checkP = 'P8/Z14';}
            if(v == '2020-08-10'){this.checkP = 'P9/Z14';}
            if(v == '2020-09-07'){this.checkP = 'P10/Z14';}
            if(v == '2020-10-05'){this.checkP = 'P11/Z14';}
            if(v == '2020-11-02'){this.checkP = 'P12/Z14';}
            if(v == '2020-11-30'){this.checkP = 'P13/Z14';}
            
        },
        loadSales() {
            this.$Progress.start();
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            axios.get(this.uri + '?' + dates_para)
                .then(responsive => {
                    this.sales = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.startDate = moment(this.startDate).add(1, 'day').format().slice(0,10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.endDate = moment(this.endDate).format().slice(0,10);
            this.loadSales();
        },
        updateByKHTN() {
            $('#chung').show();
            $('#amount').hide();
            this.uri = 'api/users-target';
            this.ura = 'api/detailTarget';
            this.loadSales();
        },
        updateByKH15() {
            $('#chung').show();
            $('#amount').hide();
            this.uri = 'api/showroom-target-lead';
            this.ura = 'api/detailKH15';
            this.loadSales();
        },
        updateByDH() {
            $('#chung').show();
            $('#amount').hide();
            this.uri = 'api/showroom-target-order';
            this.ura = 'api/detailDonHang';
            this.loadSales();
        },
        updateByDS() {
            $('#chung').hide();
            $('#amount').show();
            this.uri = 'api/showroom-target-amount';
            this.ura = 'api/detailDoanhSo';
            this.loadSales();
        },
        formatPrice(value) {
            let val = (value/1000000).toFixed(1).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        formatPriceDon(value) {
            let val = (-value/1000000).toFixed(1).replace(',', '.');
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        formatdiv(value) {
            let val = (value/1).toFixed(0).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        show(value){
            console.log(value);
            $('#modelProducts').modal('show');
            axios.post('api/showdataProduct', {data: value})
            .then(response => {
                this.sp = response.data;
            });
        }
    },
    async mounted() {
        this.loadSales();
    }
}
</script>

<style>
.bg-new {
    background-color:#b3d7f5 !important;
}

.tableFixHead          { overflow-y: auto; height: 700px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1;}
.popup td {
    padding: .5rem !important;
}
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
tbody tr td { z-index: 0}
</style>