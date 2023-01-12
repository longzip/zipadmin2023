<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-3">
                <h1 class="m-0 text-dark">Báo cáo cơ hội bán hàng</h1>
            </div><!-- /.col -->
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Chọn ngày</label>
                    <date-range-picker class="pull-right mt-2 form-control" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges" :alwaysShowCalendars="false">
                        <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                    </date-range-picker>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label>Chọn Showroom <a href="#" @click="reSelectShowroom"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="costcenters" :multiple="true" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoShowroom">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-sm-2">
                <div class="form-group">
                    <label>Chọn sale <a href="#" @click="reSelectSale"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="resources" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheoSale">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
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
                    <a class="nav-link active" data-toggle="pill" href="#khtn" @click="updateKHTN">KHTN</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#kh15" @click="updateKh15s">KH15</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#dh" @click="updateOrders">Đơn Hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#ds" @click="updateAmount">Doanh Số</a>
                </li>
                <div class="nav-link">
                    Tổng: <span class="right badge badge-danger"> {{tong}}</span>
                </div>
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
            contacts: {},
            costcenters: [],
            resources: [],
            sale_ids: [],
            loaded: false,
            chartdata: null,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            uri: 'api/contacts-count-by-date',
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
            }
        }
    },
    methods: {
        searchdata() {
            this.loadcontacts();
        },
        reSelectShowroom() {
            this.$parent.showroomsSelected = [];
            this.loadcontacts();
        },
        reSelectSale() {
            this.$parent.saleSelected = [];
            this.loadcontacts();
        },
        loadCounted() {
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id
            });
            this.loaded = false
            let dates_para = queryString.stringify({ sdates: [this.startDate, this.endDate] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let uri = queryString.stringify({ users: this.sale_ids }, { arrayFormat: 'bracket' });
            axios.get(this.uri + '?' + dates_para + '&' + queryName + '&' + uri)
                .then(responsive => {
                    this.tong = responsive.data[0];
                    this.chartdata = {
                        labels: Object.keys(responsive.data[1]),
                        datasets: [{
                            label: responsive.data[2],
                            fill: false,
                            backgroundColor: 'green',
                            borderColor: 'orange',
                            data: Object.values(responsive.data[1])
                        }]
                    }
                    this.loaded = true
                });
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
                        this.loadCounted();
                    });
            } else {
                this.$parent.saleSelected = [];
                this.loadCounted();
            }
        },
        timTheoSale() {
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString()
            this.endDate = values.endDate.toISOString()
        },
        updateKHTN() {
            this.uri = 'api/contacts-count-by-date';
            this.loadCounted();
        },
        updateKh15s() {
            this.uri = 'api/leads-count-by-date';
            this.loadCounted();
        },
        updateOrders(){
            this.uri = 'api/orders-count-by-date';
            this.loadCounted();
        },
        updateAmount(){
            this.uri = 'api/orders-amount-by-date';
            this.loadCounted();
        }
    },
    async mounted() {
        this.loadCounted();
    },
    created() {
        this.loadCounted();
        axios.get('api/users-group-costcenters')
            .then(res => this.costcenters = res.data);
        axios.get('/api/picklists/users')
            .then(res => this.resources = res.data);
        Fire.$on('searching', () => {
            this.loadCounted();
        })
    }
}
</script>