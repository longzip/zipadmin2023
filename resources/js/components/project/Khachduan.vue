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
                            <label>Chọn ngày</label>
                            <date-range-picker style="display: block;" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="$parent.selectedDate" @update="updateValues" :ranges="ranges">
                                <div slot="input" slot-scope="picker">{{ $parent.selectedDate.startDate | myDate }} - {{ $parent.selectedDate.endDate | myDate }}</div>
                            </date-range-picker>
                        </div>
                    </div>
                    <div class="col-md-3">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label></label>
                            <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                        </div>
                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <div class="card-footer clearfix">
                <a v-bind:href="downloadExcel" target="_blank" class="btn btn-info float-right"><i class="fa fa-file-excel-o"></i> Xuất file</a>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách dự án<a href="#" @click="loadcontacts"><i class="fa fa-refresh"></i></a></h3>
                        <div class="card-tools">
                            <!-- Button trigger modal -->
                            <a href="#" @click="newcontact()" class="btn btn-primary">
                                <i class="fa fa-plus nav-icon "></i> Tạo
                            </a>
                        </div>
                    </div>
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
                <!-- /.card -->
            </div>
        </div>
    </div>
</template>
<script>
import SearchShowroom from '../elements/SearchShowroom.vue';
import ContactLists from '../elements/ProjectList.vue';
export default {
    components: { SearchShowroom, ContactLists },
    data() {
        return {
            contacts: {},
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
            }
        }
    },
    methods: {
        updateValues(values) {
            this.$parent.startDate = values.startDate.toISOString().slice(0, 10)
            this.$parent.endDate = values.endDate.toISOString().slice(0, 10)
        },
        searchdata() {
            this.loadcontacts();
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
            axios.get("/api/project?page=" + page + '&' + this.getPara())
                .then(response => {
                    this.contacts = response.data;
                });
        },
        // editcontact(id) {
        //     axios.post("/setcontact", { id: id })
        //         .then(response => {
        //             location.replace("/them-khach-tiem-nang")
        //         })
        // },
        newcontact() {
            axios.get("/clearcontact")
                .then(response => {
                    location.replace("/them-du-an")
                })
        },
        ttkhachtiemnang(id) {
            console.log(id);
            axios.put('/contacts/session/' + id)
                .then(response => {
                    location.replace("/thong-tin-khach-tiem-nang")
                })
        },
        getPara(){
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id
            });
            let dates_para = queryString.stringify({ dates: [this.$parent.startDate.slice(0, 10), this.$parent.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let uri = queryString.stringify({ users: this.sale_ids }, { arrayFormat: 'bracket' });
            return uri + dates_para + '&' + queryName;
        },
        
    },
    computed:{
        downloadExcel(){
            let url = '/exports/project?' + this.getPara();
            console.log(url);
            return url;
        }
    },
    created() {
        this.loadcontacts();
        axios.get('api/users-group-costcenters')
            .then(res => this.costcenters = res.data);
        axios.get('/api/picklists/users')
            .then(res => this.resources = res.data);
        Fire.$on('searching', () => {
            this.loadcontacts();
        })
    }
}

</script>
