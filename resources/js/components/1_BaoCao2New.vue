<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-2">
                <h1 class="m-0 text-dark">Báo cáo</h1>
            </div><!-- /.col -->
            <div class="col-sm-5">
                <div class="form-group">
                    <label>Chọn ngày</label>
                    <date-range-picker class="pull-right mt-2 form-control checkip" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges" :alwaysShowCalendars="false">
                        <div slot="input" slot-scope="picker" class="datecss">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                    </date-range-picker>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <label>Chọn Showroom <a href="#" @click="reSelectShowroom"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="costcenters" :multiple="true" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoShowroom">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
                </div>
            </div>
            <!-- /.col -->
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
            </ul>
            <div style="width:100%;">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th style="width: 10px">#</th>
                            <th style="width: 150px">ShowRoom</th>
                            <th style="width: 150px">Sếp hạng</th>
                            <th style="width: 30px">Số lượng/SR</th>
                            <th style="width: 100px">Sản phẩm</th>
                            <th style="width: 10px">Số lượng/SP</th>
                        </tr>
                        <tr v-for="(sale, index) in sales">
                            <td>{{index}}</td>
                            <!-- <td>{{ sale.showroom }}</td> -->
                            <td>{{ sale.name}}</td>
                            <td>
                                <div class="progress progress-xs">
                                    <div class="progress-bar progress-bar-danger" v-bind:style="{width: (sale.count/topCount)*100 + '%'}"></div>
                                </div>
                            </td>
                            <td><span class="badge bg-danger">{{ sale.count }}</span></td>
                            <td><span class="badge bg-danger">{{ sale.count }}</span></td>
                            <td><span class="badge bg-danger">{{ sale.count }}</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.content -->
    </div>
</template>
<style type="text/css" lang="scss">
//     .form-control{
//     padding: 0px !important;
//     height: 45px;
//     .datecss{
//     padding: 6px 10px;
//     }
//     .checkip{
//     padding: 6px 10px;
//     }
//     }
//     .mt-2{
//     margin: 0px !important;
//     }
</style>
<script>
export default {
    data() {
        return {
            sales: [],
            topCount: 0,
            costcenters: [],
            resources: [],
            sale_ids: [],
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
        reSelectShowroom() {
            this.$parent.showroomsSelected = [];
            this.loadcontacts();
        },
        loadSales() {
            this.sale_ids = this.$parent.showroomsSelected.map(sale => {
                return sale.id
            });
            this.$Progress.start();
            let dates_para = queryString.stringify({ sdates: [this.startDate, this.endDate] }, { arrayFormat: 'bracket' });
            let costcenter = queryString.stringify({ costcenter: this.sale_ids }, { arrayFormat: 'bracket' });
            axios.get(this.uri + '?' + dates_para + '&' + costcenter)
                .then(responsive => {
                    this.sales = responsive.data;
                    this.topCount = this.sales[0].count;
                    this.$Progress.finish();
                })
                .catch(()=> this.$Progress.fail());
        },

        timTheoShowroom() {
            this.loadSales();
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString()
            this.endDate = values.endDate.toISOString()
            this.loadSales();
        },
        updateKHTN() {
            this.uri = 'api/contacts-count-by-costcenter';
            this.loadSales();
        },
        updateKh15s() {
            this.uri = 'api/leads-count-by-costcenter';
            this.loadSales();
        },
        updateOrders(){
            this.uri = 'api/orders-count-by-costcenter';
            this.loadSales();
        },
        updateAmount(){
            this.uri = 'api/orders-amount-by-costcenter';
            this.loadSales();
        }
    },
    async mounted() {
        this.loadSales();
    },
    created() {
        this.loadSales();
        axios.get('api/users-group-costcenters')
            .then(res => this.costcenters = res.data);
        Fire.$on('searching', () => {
            this.loadSales();
        })
    }
}

</script>
