<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-3">
                <h1 class="m-0 text-dark">Báo cáo Online</h1>
            </div><!-- /.col -->
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Chọn ngày</label>
                    <date-range-picker class="pull-right mt-2 form-control" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges" :alwaysShowCalendars="false">
                        <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                    </date-range-picker>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Chiến Dịch</label>
                    <multiselect v-model="$parent.chiendich" :options="chiendich" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn chiến dịch" label="name" :preselect-first="true" @close="searchChienDich">
                    </multiselect>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chọn Sản Phẩm</label>
                    <multiselect v-model="$parent.chiendichsanpham" :options="load" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn sản phẩm" label="product" :preselect-first="true" @close="searchProduct">
                    </multiselect>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label></label>
                    <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <ul class="nav nav-pills" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="pill" href="#khtn" @click="tabsoluong">Số Lượng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#kh15" @click="tabtien">Tiền</a>
                </li>
            </ul>
            <div style="width:100%;">
                <line-chart v-if="loaded" :chartdata="chartdata" :options="chartOptions" />
            </div>
        </div>
        <!-- /.content -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            tong: [],
            chiendich:[],
            load: [],
            sale_ids: [],
            loaded: false,
            chartdata: null,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            uri: 'api/bao-cao-so-luong',
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
            this.loadCounted();
        },
        loadCounted() {
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id
            });
            this.loaded = false
            let dates_para = queryString.stringify({ sdates: [this.startDate, this.endDate] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let chiendich = queryString.stringify({ chiendich: this.$parent.chiendich.id });
            let product = queryString.stringify({ product: this.$parent.chiendichsanpham.product });
            axios.get(this.uri + '?' + dates_para + '&' + queryName + '&' + chiendich + '&' + product)
                .then(responsive => {
                    this.chartdata = {
                        labels: Object.keys(responsive.data[0][0]),
                        datasets: [{
                            label: responsive.data[1],
                            fill: false,
                            backgroundColor: 'green',
                            borderColor: 'green',
                            data: Object.values(responsive.data[0][0])
                        },{
                            label: responsive.data[2],
                            fill: false,
                            backgroundColor: 'red',
                            borderColor: 'red',
                            data: Object.values(responsive.data[0][1])
                        },{
                            label: responsive.data[3],
                            fill: false,
                            backgroundColor: 'orange',
                            borderColor: 'orange',
                            data: Object.values(responsive.data[0][2])
                        }]
                    }
                    this.loaded = true
                });
        },
        
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.startDate = moment(this.startDate).add(1, 'day').format().slice(0,10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.endDate = moment(this.endDate).endOf('week').format().slice(0,10);
        },
        tabsoluong() {
            this.uri = 'api/bao-cao-so-luong';
            this.loadCounted();
        },
        tabtien() {
            this.uri = 'api/bao-cao-tien';
            this.loadCounted();
        },
        searchChienDich(){
            axios.get('api/load-sp-cd?id='+this.$parent.chiendich.id)
            .then(response => {
                this.load = response.data;
            });
        },
        searchProduct(){
        },
    },
    async mounted() {
        this.loadCounted();
    },
    created() {
        this.loadCounted();
        Fire.$on('searching', () => {
            this.loadCounted();
        });
        axios.get('api/online-target')
        .then(response => {
            this.chiendich = response.data;
        });
    }
}
</script>