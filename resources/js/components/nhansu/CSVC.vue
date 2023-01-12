<template>
    <div class="">
        <div class="card card-default">
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
                    <div class="col-md-2">
                        <div class="form-group">
                            <label></label>
                            <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title pull-left">CSVC</h3>
                <div class="card-tools">
                    <a href="#" @click="nhapMaModal" class="btn btn-defaut">
                        Nhập Mã
                    </a>
                    <a href="#" @click="newModal" class="btn btn-primary">
                        Thêm
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Showroom</th>
                            <th>Sản Phẩm</th>
                            <th>Số Lượng</th>
                            <td>Bảo Dưỡng</td>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(d,k) in csvc">
                            <tr v-for="(nv,knv) in d">
                                <td :rowspan="Object.keys(d).length" v-if="knv===0">{{k}}</td>
                                <td>{{nv.sanpham}} </td>
                                <td>{{nv.so_luong}} </td>
                                <td>
                                    <a href="#" @click="baoduong(nv)">
                                        <i class="fa fa-cog"></i>
                                    </a>
                                </td>
                            </tr>
                        </template>   
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="nhapMaModal" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Thêm Mã</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nhập Mã</label>
                            <input v-model="ma.code" type="text" class="form-control" placeholder="Nhập Mã">
                        </div>
                        <div class="form-group">
                            <label>Mô Tả</label>
                            <input v-model="ma.note" type="text" class="form-control" placeholder="Mô Tả">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="addma">
                            Lưu
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="userModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Thêm CV</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                         <div class="form-group">
                            <label>Showroom</label>
                            <multiselect v-model="form.costcenters" :options="costcenters" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} showroom đã chọn</span></template>
                            </multiselect>
                        </div>
                        <div class="form-group ">
                            <label>Chọn Sản Phẩm</label>
                            <multiselect v-model="form.sanpham" :options="products" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                            </multiselect>
                        </div>
                        <div class="form-group">
                            <label>Số Lượng</label>
                            <input v-model="form.soluong" type="text" class="form-control" placeholder="Số Lượng">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="add">
                            Lưu
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="baoduongModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Bảo Dưỡng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Showroom</label>
                            <input v-model="formbaoduong.showroom" type="text" class="form-control" disabled="">
                        </div>
                        <div class="form-group">
                            <label>Showroom</label>
                            <input v-model="formbaoduong.sanpham" type="text" class="form-control" disabled="">
                        </div>
                        <div class="form-group">
                            <label>Ghi Chú</label>
                            <textarea v-model="formbaoduong.note" type="text" class="form-control" placeholder="Nhập ghi chú "></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="addbaoduong">
                            Lưu
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
            products: [],
            csvc: [],
            costcenters:[],
            form: new Form({
                sanpham: '',
                costcenters: '',
                id: '',
                soluong: 1,
            }),
            ma: new Form({
                code: '',
                note: '',
                id: '',
            }),
            formbaoduong: new Form({
                showroom: '',
                sanpham: '',
                id: '',
                note: '',
            }),
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
        add(){
            this.form.post('/api/csvc')
            .then(() => {
                // $('#userModalCenter').modal('hide');
                this.loadcsvc();
                this.$Progress.finish();
            })
            .catch(() => {
                this.$Progress.fail();
            });
        },

        addma(){
            axios.post('/api/addmacsvc', {
                code: this.ma.code,
                note: this.ma.note,
            })
            .then(() => {
                this.loadcsvc();
                this.$Progress.finish();
            })
            .catch(() => {
                this.$Progress.fail();
            });
        },
        
        loadcsvc() {
            this.$Progress.start();
            axios.get('api/csvc' + '?' + this.getPara())
                .then(responsive => {
                    this.csvc = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
        },

        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
        },

        searchdata() {
            this.loadcsvc();
        },
        
        getPara() {
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            return  dates_para + '&' + queryName;
            // return queryName;
        },

        // updateValues(values) {
        //     this.startDate = values.startDate.toISOString().slice(0, 10);
        //     this.endDate = values.endDate.toISOString().slice(0, 10);
        //     this.loadcsvc();
        // },

        newModal() {
            this.form.reset();
            $('#userModalCenter').modal('show');
        },

        nhapMaModal() {
            $('#nhapMaModal').modal('show');
        },

        baoduong(cs) {
            this.formbaoduong.fill(cs);
            $('#baoduongModalCenter').modal('show');
        },

        addbaoduong(){
            this.formbaoduong.put('/api/csvc/' + this.formbaoduong.id)
            .then(() => {
                $('#baoduongModalCenter').modal('hide');
                this.loadcsvc();
                this.$Progress.finish();
            })
            .catch(() => {
                this.$Progress.fail();
            });
        },
    },
    created() {
        this.loadcsvc();
        Fire.$on('searching', () => {
            this.loadcsvc();
        });
        axios.get('/api/getallproduct/').then(res => this.products = res.data);
        axios.get("/api/costcenters-merge-list")
            .then(response => {
                this.costcenters = response.data;
            });
       
    },
}

</script>
