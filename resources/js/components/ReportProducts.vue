<template>
    <div class="">
        <div class="card card-default">
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Chọn Showroom <a href="#" @click="reSelectShowroom"><i class="fa fa-refresh"></i></a></label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="costcenters" :multiple="true" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoShowroom">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form-group">
                            <label>Chọn sản phẩm <a href="#" @click="reSelectSanPham"><i class="fa fa-refresh"></i></a></label>
                            <select  class="form-control" v-model="p" @change="onChange($event)">
                                <option v-for="option in $parent.p" v-bind:value="option.value">
                                    {{ option.text }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Chọn ngày</label>
                            <date-range-picker class="form-control" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges">
                                <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                            </date-range-picker>
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
        <div class="row justify-content-center">
            <div class="col-12 col-md-3 mt-2">
                <div class="card">
                    <div class="card-head">
                        <h3>Sản Phẩm Keys</h3>
                    </div>
                    <div class="card-body table-responsive p-0 report-pro">
                        <table class="table table-hover report-product">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Sản Phẩm</th>
                                    <th>SL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pro,key,index) in orders.key">
                                    <td>{{index + 1}}</td>
                                    <td>
                                        <a @click="loadmorekey(key)" data-toggle="collapse" v-bind:href="'#' + key" role="button" aria-expanded="false" aria-controls="collapseExample">{{key}}</a>
                                        <div class="collapse" v-bind:id="key" v-for="(count,name) in loadmoreskey.sanpham" v-if="loadmoreskey.name == key">
                                            <div>{{name}} : {{count}}</div>
                                        </div>
                                        
                                    </td>
                                    <td>
                                        <a @click="loadmoretarget(index)" data-toggle="collapse" v-bind:href="'#a' + index" role="button" aria-expanded="false" aria-controls="collapseExample">{{pro}}</a>
                                         <div class="collapse" v-bind:id="'a' + index" v-for="(count,name,k) in loadmoresdetailtarget.sanpham">
                                            <div>{{count.a}} / {{count.t}}</div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 mt-2">
                <div class="card">
                    <div class="card-head">
                        <h3>Theo Dòng</h3>
                    </div>
                    <div class="card-body table-responsive p-0 report-pro">
                        <table class="table table-hover report-product">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Sản Phẩm</th>
                                    <th>SL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pro,key,index) in orders.dong">
                                    <td>{{index + 1}}</td>
                                    <td>
                                        <a @click="loadmore(key)" data-toggle="collapse" v-bind:href="'#' + key" role="button" aria-expanded="false" aria-controls="collapseExample">{{key}}</a>
                                        <div class="collapse" v-bind:id="key" v-for="(count,name) in loadmores.sanpham" v-if="loadmores.name == key">
                                            <div>
                                                <a @click="loadmoredetail(name)" data-toggle="collapse" v-bind:href="'#' + name" role="button" aria-expanded="false" aria-controls="collapseExample">{{name}} : {{count}}</a>
                                                <div class="collapse" v-bind:id="name" v-for="(c,n) in loadmoresdetail.sanpham" v-if="loadmoresdetail.name == name">
                                                    <div>{{n}} : {{c}}</div>
                                                </div>
                                            
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{pro}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 mt-2">
                <div class="card">
                    <div class="card-head">
                        <h3>Theo Sản Phẩm</h3>
                    </div>
                    <div class="card-body table-responsive p-0 report-pro">
                        <table class="table table-hover report-product">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Sản Phẩm</th>
                                    <th>SL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(pro,key,index) in orders.sanpham">
                                    <td>{{index + 1}}</td>
                                    <td>{{key}}</td>
                                    <td>{{pro}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3 mt-2">
                <div class="">
                    <label>Số Lượng</label>
                    
                    <pie-chart v-if="loaded" :chartdata="chartdata" :options="chartOptions" />
                </div>
                <div class="mt-3">
                    <label>Dòng Tiền</label>

                    <pie-chart v-if="loadednew" :chartdata="chartdatanew" :options="chartOptions" />
                </div>
            </div>
        </div>

    </div>
</template>
<style>
.report-product td {
    padding: 0px !important;
}

@media only screen and (max-width: 600px) {
    .report-pro  { overflow-y: auto; height: 300px; }
    .report-pro thead th { position: sticky; top: 0; z-index: 1;}
}
</style>
<script>
export default {
    data() {
        return {
            note:'',
            orderLine: {},
            orders: {},
            order: {},
            loaded: false,            
            loadednew: false,
            chartdata: null,
            chartdatanew: null,
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            customer: {},
            resource: {},
            loadmoresdetailtarget: [],
            loadmores: [],
            loadmoreskey: [],
            loadmoresdetail: [],
            khuvucSelected:[],
            resou:[],
            p: '',
            sum: [],
            checkAdmin: [],
            id:'',
            showroomsSelected: [],
            costcenters: [],
            products: [],
            selectorders: [],
            saleSelected: [],
            resources: [],
            costcenter_ids: [],
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
            startDate: moment('2020-11-30').format(),
            endDate: moment('2020-12-27').format(),

            selectedDate: {
                startDate: moment('2020-11-30'),
                endDate: moment('2020-12-27')
            },
        }
    },
    methods: {
        searchdata() {
            this.getResults();
        },
        loadmore(key){
            this.khuvuc = this.$parent.khuvucSelected.map(kvuc =>{
                return kvuc.id
            });
            this.costcenter_ids = this.$parent.showroomsSelected.map((costcenter, index, showroomsSelected) => {
                return costcenter.id;
            });
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10), 0 ] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let showroom = queryString.stringify({ costcenter: this.costcenter_ids }, { arrayFormat: 'bracket' });
            let products = queryString.stringify({ p: this.p });

            axios.get("/api/loadmore-product?group=" + key + '&' + products + '&' + showroom + '&' + dates_para + '&' + queryName)
                .then(response => {
                    this.loadmores = response.data;
                });
        },
        loadmoredetail(key){
            this.khuvuc = this.$parent.khuvucSelected.map(kvuc =>{
                return kvuc.id
            });
            this.costcenter_ids = this.$parent.showroomsSelected.map((costcenter, index, showroomsSelected) => {
                return costcenter.id;
            });
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10), 0 ] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let showroom = queryString.stringify({ costcenter: this.costcenter_ids }, { arrayFormat: 'bracket' });
            let products = queryString.stringify({ p: this.p });

            axios.get("/api/loadmore-productdetail?group=" + key + '&' + products + '&' + showroom + '&' + dates_para + '&' + queryName)
                .then(response => {
                    this.loadmoresdetail = response.data;
                });
        },
        loadmorekey(key){
            this.khuvuc = this.$parent.khuvucSelected.map(kvuc =>{
                return kvuc.id
            });
            this.costcenter_ids = this.$parent.showroomsSelected.map((costcenter, index, showroomsSelected) => {
                return costcenter.id;
            });
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10), 0 ] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let showroom = queryString.stringify({ costcenter: this.costcenter_ids }, { arrayFormat: 'bracket' });
            let products = queryString.stringify({ p: this.p });

            axios.get("/api/loadmore-key?key=" + key + '&' + products + '&' + showroom + '&' + dates_para + '&' + queryName)
                .then(response => {
                    this.loadmoreskey = response.data;
                });
        },
        checkdate(){
        },
        reSelectArea() {
            this.$parent.khuvucSelected = [];
            this.getResults();
        },
        reSelectSanPham() {
            this.p = [];
            this.getResults();
        },
        reSelectShowroom() {
            this.$parent.showroomsSelected = '';
            this.sale_ids = [];
            this.getResults();
        },

        // Our method to GET results from a Laravel endpoint
        getResults(page = 1) {
            this.orders = [];
            this.loaded = false;
            this.loadednew = false;
            this.khuvuc = this.$parent.khuvucSelected.map(kvuc =>{
                return kvuc.id
            });
            this.costcenter_ids = this.$parent.showroomsSelected.map((costcenter, index, showroomsSelected) => {
                return costcenter.id;
            });
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10), 0 ] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let showroom = queryString.stringify({ costcenter: this.costcenter_ids }, { arrayFormat: 'bracket' });
            // let khuvuc = queryString.stringify({ khuvucs: this.khuvuc }, { arrayFormat: 'bracket' });
            let products = queryString.stringify({ p: this.p });

            axios.get("/api/re-product?page=" + page + '&' + products + '&' + showroom + '&' + dates_para + '&' + queryName)
                .then(response => {
                    this.orders = response.data;
                    let tong = 0;                    
                    let tongamount = 0;
                    $.each(this.orders.dong, function(index, value) {
                        if (index !== 'Ivy' && index !== 'Olive' && index !== 'Iris' && index !== 'Tulip' && index !== 'Pansy') {
                            tong += value;
                        }
                    });
                    $.each(this.orders.tongtheotien, function(index, value) {
                        if (index !== 'Ivy' && index !== 'Olive' && index !== 'Iris' && index !== 'Tulip' && index !== 'Pansy') {
                            tongamount += value;
                        }
                    });
                    this.chartdata = {
                        labels: ['Ivy', 'Olive', 'Iris', 'Tulip', 'Pansy', 'Khác'],
                        datasets: [{
                            label: 'cái',
                            backgroundColor: [ '#8e5ea2', '#3cba9f', '#e8c3b9', '#c45850', '#dcd615', '#57d408'],
                            data: [this.orders.dong.Ivy, this.orders.dong.Olive, this.orders.dong.Iris, this.orders.dong.Tulip, this.orders.dong.Pansy, tong]
                        }]
                    }
                    this.chartdatanew = {
                        labels: ['Ivy', 'Olive', 'Iris', 'Tulip', 'Pansy', 'Khác'],
                        datasets: [{
                            label: 'đồng',
                            backgroundColor: [ '#8e5ea2', '#3cba9f', '#e8c3b9', '#c45850', '#dcd615', '#57d408'],
                            data: [this.orders.tongtheotien.Ivy, this.orders.tongtheotien.Olive, this.orders.tongtheotien.Iris, this.orders.tongtheotien.Tulip, this.orders.tongtheotien.Pansy, tongamount]
                        }]
                    }
                    this.loaded = true;
                    this.loadednew = true;
                });
        },
        loadmoretarget(values) {
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10), 0 ] }, { arrayFormat: 'bracket' });
            axios.get("/api/loadmore-key-target?" + dates_para + '&value=' + values )
                .then(response => {
                    this.loadmoresdetailtarget = response.data;
                });
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
        },
        addWeek() {
            this.p = [];
            var date = moment(this.endDate).endOf('week').format().slice(0, 10);
            this.p.push(date);
            this.p.push(moment(date).subtract(1, 'week').format().slice(0, 10));
            this.p.push(moment(date).subtract(2, 'week').format().slice(0, 10));
            this.p.push(moment(date).subtract(3, 'week').format().slice(0, 10));

        },
        onChange(event) {
        },
        timTheoShowroom() {
        },
        timTheokhuvuc(){
        },
    },
    created() {
        this.getResults();
        Fire.$on('searching', () => {
            this.getResults();
        })
        Fire.$on('AfterCreate', () => {
            this.getResults();
        });
        axios.get('/api/products/').then(res => this.product = res.data);
        axios.get('/api/products-list/').then(res => this.products = res.data);
        axios.get('/api/picklists/areasPicklists')
            .then(res => this.resou = res.data);
        axios.get('/api/listOrders')
            .then(res => this.selectorders = res.data);
        axios.get('/api/checkAdmin')
            .then(res => this.checkAdmin = res.data);
        axios.get('api/users-group-costcenters')
            .then(res => this.costcenters = res.data);
    },
}

</script>
