<template>
    <div >
        <div class="card card-default">
            <div class="card-body" style="padding: 0px;">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Chọn Showroom <a href="#" @click="reSelectShowroom"><i class="fa fa-refresh"></i></a></label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="costcenters" :multiple="true" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoShowroom">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Chọn sale <a href="#" @click="reSelectSale"><i class="fa fa-refresh"></i></a></label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="resources" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheoSale">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Chọn ngày</label>
                            <date-range-picker style="display: block;" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="$parent.selectedDate" @update="updateValues" :ranges="ranges">
                                <div slot="input" slot-scope="picker">{{ $parent.selectedDate.startDate | myDate }} - {{ $parent.selectedDate.endDate | myDate }}</div>
                            </date-range-picker>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Chọn sản phẩm <a href="#" @click="reSelectSanPham"><i class="fa fa-refresh"></i></a></label>
                            <select  class="form-control" v-model="p" @change="onChange($event)">
                                <option v-for="option in $parent.p" v-bind:value="option.value">
                                    {{ option.text }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Chọn Trạng Thái <a href="#" @click="reSelectStatus"><i class="fa fa-refresh"></i></a></label>
                            <select  class="form-control" v-model="status" @change="onChange($event)">
                                <option v-for="option in $parent.status" v-bind:value="option.value">
                                    {{ option.text }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <!-- /.col --> 
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Chọn Khu Vực <a href="#" @click="reSelectArea"><i class="fa fa-refresh"></i></a></label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.khuvucSelected" :options="resou" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheokhuvuc">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                        </div>
                    </div>
                    <!-- /.col -->
                    
                    <div class="col-md-1">
                        <div class="form-group">
                            <label></label>
                            <a href="#" @click="searchdata" class="btn btn-success">Tìm</i></a>
                        </div>
                    </div>
                </div>
            </div>
           <!--  <div class="card-footer clearfix">
                <a v-bind:href="downloadExcel" target="_blank" class="btn btn-info float-right"><i class="fa fa-file-excel-o"></i> Xuất file</a>
            </div> -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 >Danh sách KHTN <a href="#" @click="loadcontacts"><i class="fa fa-refresh"></i></a></h5>
                        <div class="card-tools">
                            <!-- Button trigger modal -->
                            <!-- <a href="#" @click="newcontact()" class="btn btn-primary">
                                <i class="fa fa-plus nav-icon "></i> Tạo
                            </a> -->
                            Tổng: {{sum.sum_amount_pages | currency}} / {{sum.sum_amount | currency}} 
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0 ">
                        <contact-lists :contacts="contacts.data" @deleted="loadcontacts"></contact-lists>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="contacts" :limit=3 @pagination-change-page="loadcontacts"><span slot="prev-nav">&lt; Trước</span>
                            <span slot="next-nav">Sau &gt;</span></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</template>
<script>
import SearchShowroom from '../elements/SearchShowroom.vue';
import ContactLists from '../elements/ContactLists.vue';
export default {
    components: { SearchShowroom, ContactLists },
    data() {
        return {
            sum: {},
            contacts: {},
            costcenters: [],
            resources: [],
            sale_ids: [],
            khuvuc:[],
            khuvucSelected:[],
            resou:[],
            p: '',
            status: '',
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
                '2019': [moment('2018-12-31').endOf('week').isoWeekday(1), moment('2019-12-29').endOf('week')],
                '2020': [moment('2019-12-30').endOf('week').isoWeekday(1), moment('2020-12-27').endOf('week')],
                '2021': [moment('2020-12-28').endOf('week').isoWeekday(1), moment('2021-12-26').endOf('week')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
                'Z16/P1': [moment('2021-12-27').endOf('week').isoWeekday(1), moment('2022-01-23').endOf('week')],
                'Z16/P2': [moment('2022-01-24').endOf('week').isoWeekday(1), moment('2022-02-20').endOf('week')],
                'Z16/P3': [moment('2022-02-21').endOf('week').isoWeekday(1), moment('2022-03-20').endOf('week')],
                'Z16/P4': [moment('2022-03-21').endOf('week').isoWeekday(1), moment('2022-04-17').endOf('week')],
                'Z16/P5': [moment('2022-04-18').endOf('week').isoWeekday(1), moment('2022-05-15').endOf('week')],
                'Z16/P6': [moment('2022-05-16').endOf('week').isoWeekday(1), moment('2022-06-12').endOf('week')],
                'Z16/P7': [moment('2022-06-13').endOf('week').isoWeekday(1), moment('2022-07-10').endOf('week')],
                'Z16/P8': [moment('2022-07-11').endOf('week').isoWeekday(1), moment('2022-08-07').endOf('week')],
                'Z16/P9': [moment('2022-08-08').endOf('week').isoWeekday(1), moment('2022-09-04').endOf('week')],
                'Z16/P10': [moment('2022-09-05').endOf('week').isoWeekday(1), moment('2022-10-02').endOf('week')],
                'Z16/P11': [moment('2022-10-03').endOf('week').isoWeekday(1), moment('2022-10-30').endOf('week')],
                'Z16/P12': [moment('2022-10-31').endOf('week').isoWeekday(1), moment('2022-11-27').endOf('week')],
                'Z16/P13': [moment('2022-11-28').endOf('week').isoWeekday(1), moment('2022-12-25').endOf('week')],
            },
        }
    },
    methods: {
        searchdata() {
            this.loadcontacts();
        },
        reSelectArea() {
            this.$parent.khuvucSelected = [];
            this.loadcontacts();
        },
        reSelectStatus() {
            this.status = '';
            this.loadcontacts();
        },
        reSelectSanPham() {
            this.p = [];
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
        
        onChange(event) {
            // console.log(event.target.value)
        },
        updateValues(values) {
            this.$parent.startDate = values.startDate.toISOString().slice(0, 10)
            // this.$parent.startDate = moment(this.$parent.startDate).add(1, 'day').format().slice(0,10);
            this.$parent.endDate = values.endDate.toISOString().slice(0, 10)
            // this.$parent.endDate = moment(this.$parent.endDate).endOf('week').format().slice(0,10);
            // this.addWeek();
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
                        this.loadcontacts();
                    });

            } else {
                this.$parent.saleSelected = [];
                this.loadcontacts();
            }

        },
        timTheoSale() {
        },
        loadcontacts(page = 1) {  
            axios.get("/api/contacts?page=" + page + '&' + this.getPara())
                .then(response => {
                    this.contacts = response.data;
                });
            axios.get("/api/sumamount?page=" + page + '&' + this.getPara())
                .then(response => {
                    this.sum = response.data;
                });
        },
        editcontact(id) {
            axios.post("/setcontact", { id: id })
                .then(response => {
                    location.replace("/them-khach-tiem-nang")
                });
        },
        newcontact() {
            axios.get("/clearcontact")
                .then(response => {
                    location.replace("/them-khach-tiem-nang")
                });
        },
        ttkhachtiemnang(id) {
            console.log(id);
            axios.put('/contacts/session/' + id)
                .then(response => {
                    location.replace("/thong-tin-khach-tiem-nang")
                });
        },
        timTheokhuvuc() {
        },
        getPara(){
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id
            });
            this.khuvuc = this.$parent.khuvucSelected.map(kvuc =>{
                return kvuc.id
            });
            let dates_para = queryString.stringify({ sdates: [this.$parent.startDate.slice(0, 10), this.$parent.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let uri = queryString.stringify({ users: this.sale_ids }, { arrayFormat: 'bracket' });
            let test = queryString.stringify({ khuvucs: this.khuvuc }, { arrayFormat: 'bracket' });
            let status = queryString.stringify({ status: this.status });
            let products = queryString.stringify({ p: this.p });
            return uri + '&' + dates_para + '&' + queryName + '&' + test + '&' + status + '&' + products;
        },
        
    },
    computed:{
        downloadExcel(){
            let url = '/exports/contacts?' + this.getPara();
            console.log(url);
            return url;
        }
    },
    created() {
        this.loadcontacts();
        axios.get('api/users-group-costcenters')
            .then(res => this.costcenters = res.data);
        axios.get('api/products-list')
            .then(response => {
                this.products = response.data;
            });
        axios.get('/api/picklists/users')
            .then(res => this.resources = res.data);
        axios.get('/api/picklists/areasPicklists')
            .then(res => this.resou = res.data);
        Fire.$on('searching', () => {
            this.loadcontacts();
        });
    }
}

</script>


<style>
.form-group {
    margin-bottom: 5px;
}
</style>