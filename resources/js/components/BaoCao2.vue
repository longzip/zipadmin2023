<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Báo cáo</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <date-range-picker class="pull-right mt-2" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges" :alwaysShowCalendars="false">
                    <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                </date-range-picker>
            </div><!-- /.col -->
        </div>
        <div class="row">
             <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="#" @click="updateKHTN">KHTN</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" @click="updateKh15s">KH15</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" @click="updateOrders">Đơn hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" @click="updateAmount">Doanh số</a>
                </li>
            </ul>
            <div style="width:100%;">
                <bar-chart v-if="loaded" :chartdata="chartdata" :options="chartOptions" />
            </div>
        </div>
        <!-- /.content -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            loaded: false,
            chartdata: null,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
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
                'Tuần này': [moment().startOf('week'), moment()],
                'Tuần trước': [moment().subtract(1, 'week').startOf('week'), moment()],
                'Hai tuần trước': [moment().subtract(2, 'week').startOf('week'), moment()],
                'Ba tuần trước': [moment().subtract(3, 'week').startOf('week'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
            },
            startDate: moment().subtract(14, 'days').format(),
            endDate: moment().format(),
            selectedDate: {
                startDate: moment().subtract(14, 'days').format(),
                endDate: moment().format()
            },
            uri: 'api/contacts-count-by-costcenter',
        }
    },
    methods: {
        loadByShowroom() {
            this.loaded = false
            let dates_para = queryString.stringify({ sdates: [this.startDate, this.endDate] }, { arrayFormat: 'bracket' });
            axios.get(this.uri + '?' + dates_para)
                .then(responsive => {
                    this.chartdata = {
                        labels: Object.keys(responsive.data),
                        datasets: [{
                            label: 'KHTN',
                            backgroundColor: 'green',
                            data: Object.values(responsive.data)
                        }]
                    }
                    this.loaded = true
                });
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString()
            this.endDate = values.endDate.toISOString()
            this.loadByShowroom();
        },
        updateKHTN() {
            this.uri = 'api/contacts-count-by-costcenter';
            this.loadByShowroom();
        },
        updateKh15s() {
            this.uri = 'api/leads-count-by-costcenter';
            this.loadByShowroom();
        },
        updateOrders(){
            this.uri = 'api/orders-count-by-costcenter';
            this.loadByShowroom();
        },
        updateAmount(){
            this.uri = 'api/orders-amount-by-costcenter';
            this.loadByShowroom();
        }
    },
    async mounted() {
        this.loadByShowroom();
    }
}

</script>
