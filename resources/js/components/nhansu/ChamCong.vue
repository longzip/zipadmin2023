<template>
    <div class="">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Khối</label>
                    <select  class="form-control" v-model="position" @change="onChange($event)">
                        <option v-for="option in $parent.position" v-bind:value="option.value">
                            {{ option.index }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Bộ Phận<a href="#" @click="reSelectbophan"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="bophan" :multiple="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn" label="info" track-by="id" :preselect-first="true">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chọn nhân viên<a href="#" @click="reSelectnhanvien"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="users" :multiple="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
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
                    <label>Tìm </label><br/>
                    <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bảng Chấm Công</h3>
                        <div class="card-tools">
                            <a href="#" @click="newModal" class="btn btn-primary">
                                Thêm  <i class="fa fa-user-plus"></i>
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã</th>
                                    <th>Địa Điểm</th>
                                    <th>Nhân viên</th>
                                    <th>Ngày</th>
                                    <th>Vào</th>
                                    <th>Ra</th>
                                    <th>Thông Tin</th>
                                    <th>CheckOut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="cham in chamcong.data">
                                    <td>{{cham.ma_nv}}</td>
                                    <td>{{cham.type}}</td>
                                    <td>{{cham.name}}</td>
                                    <td>{{cham.date | myDate}}</td>
                                    <td>{{cham.in}}</td>
                                    <td>{{cham.out}}</td>
                                    <td>
                                        {{cham.info}}
                                        <br>
                                        <a target="_bank" v-bind:href="cham.domain + cham.img"  v-if="cham.img != null">
                                            <img class="img-list" v-bind:src="cham.domain + cham.img">
                                        </a>
                                        <a target="_bank" v-bind:href="cham.domain + cham.img_out"  v-if="cham.img_out != null">
                                            <img class="img-list" v-bind:src="cham.domain + cham.img_out">
                                        </a>
                                    </td>
                                    <td v-if="cham.type == 'Showroom' && cham.user_login == cham.ma_nv">
                                        <a href="#" @click="checkout(cham)" v-if="cham.img_out == null">
                                            <i class="fa fa-sign-out" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="chamcong"  :pageSize="5" :limit="5" @pagination-change-page="loadChamCong"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal-->
        <div class="modal fade" id="createModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"id="addNewLabel">Chấm Công</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="create()">
                        <div class="form-row col-md-12">
                            <label>Khối: </label>
                            <br>
                            <input type="radio" id="lan-1" value="1" v-model="form.pos">
                            <label for="lan-1">Văn Phòng Miền Bắc</label>
                            <input type="radio" id="lan-2" value="2" v-model="form.pos">
                            <label for="lan-2">Văn Phòng Miền Nam</label>
                            <!-- <input type="radio" id="lan-3" value="3" v-model="form.pos">
                            <label for="lan-3">Nhà Máy</label> -->
                            <input type="radio" id="lan-4" value="4" v-model="form.pos">
                            <label for="lan-4">Sales</label>
                            <input type="radio" id="lan-5" value="5" v-model="form.pos">
                            <label for="lan-5">CTV</label>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Import File</label>
                            <input type="file" id="file" ref="file" v-on:change="handleFileUpload" />
                        </div>
                        <div class="form-group col-md-12">
                            <label>Showroom</label>
                            <multiselect v-model="form.costcenters" ref="costcenters" tag-placeholder="Thêm Showroom" placeholder="Tìm hoặc chọn" :options="costcenters" :multiple="false" label="name" :taggable="true"></multiselect>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                            <button type="submit" class="btn btn-primary">Tạo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="out" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"id="addNewLabel">Checkout</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="update()">
                        <div class="form-row col-md-12">
                            <label>Import File</label>
                            <input type="file" id="fileo" ref="fileo" v-on:change="handleFileUploadOut" />
                            <input type="hidden"ref="id" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                            <button type="submit" class="btn btn-primary">Tạo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="chot" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chốt Công</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:200px;">Tên</th>
                                    <th>Tự Tính</th>
                                    <th>App</th>
                                    <th>Giải Trình</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="l in listcongsale">
                                    <td style="width:200px;">{{l.name}} </td>
                                    <td><input style="width:50px;" type="number" name="cong" v-model="l.cong"> công</td>
                                    <td>{{l.congapp}} công</td>
                                    <td><input type="number" name="cong" v-model="l.note"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                        <button type="submit" @click="addchot()" class="btn btn-primary">Chốt</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style type="text/css">
  .form-row {
    display: block;
  }

  #fileDetail {
    display: none;
  }

  .pheduyet {
    width: 50%;
  }

  .form-row {
    clear: both;
  }
</style>
<script>

export default {
    data() {
        return {
            opens: "center",
            sale_ids: [],
            listcongsale: [],
            bophan: [],
            role_name:{},
            users: [],
            selbophan: [],
            showroomsSelected: [],
            chamcong: [],
            costcenters: [],
            saleSelected:[],
            user: [],
            sm: 0,
            position: 4,
            form: new Form({
                pos: '',
                file: '',
                costcenters: [],

            }),
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
                'P12': [moment('2019-11-04').endOf('week').isoWeekday(1), moment('2019-12-01').endOf('week')],
                'P13': [moment('2019-12-02').endOf('week').isoWeekday(1), moment('2019-12-29').endOf('week')],
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
        }
    },
    methods: {
        searchdata() {
            this.loadChamCong();
        },
        update(){
            let formData = new FormData();
            formData.append('file', this.fileo);
            formData.append('id', this.id);
            axios.post('/api/chamcongout',formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
            .then(() => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Thêm Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                this.loadChamCong();
            })
            .catch(error => {
                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: error.response.data.message,
                    footer: '<a href>Why do I have this issue?</a>'
                })
            });
        },
       
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
        },
        handleFileUpload() {
            this.file = this.$refs.file.files[0];
        },
        handleFileUploadOut() {
            this.fileo = this.$refs.fileo.files[0];
        },
      
        onChange(event) {
            this.position = event.target.value;
        },
        checkout(cham) {
            this.id = cham.id;
            $('#out').modal('show');
        },
        fileChange(e) {
            if (this._spread) {
                const fileDom = e.target || e.srcElement;
                const excelIO = new Excel.IO();
                const spread = this._spread;
                const store = this.$store;

                excelIO.open(fileDom.files[0], (_data_) => {
                    const newSalesData = extractSheetData(data);
                    store.commit('updateRecentSales', newSalesData)
                });
            }
        },
        reSelectnhanvien() {
            this.$parent.saleSelected = [];
            this.loadChamCong();
        },
        reSelectbophan() {
            this.$parent.showroomsSelected = [];
            this.loadChamCong();
        },
        loadChamCong(page = 1) {
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });

            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id;
            });

            this.selbophan = this.$parent.showroomsSelected.map(sale => {
                return sale.info;
            });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let khoi = queryString.stringify({ khoi: this.position });
            let nhanvien = queryString.stringify({ users: this.sale_ids }, { arrayFormat: 'bracket' });
            let bp = queryString.stringify({ bp: this.selbophan }, { arrayFormat: 'bracket' });

            axios.get("/api/chamcong?page=" + page + '&' + queryName + '&' + nhanvien + '&' + bp + '&' + khoi +  '&' + dates_para).then(({ data }) => (this.chamcong = data));

            
        },
        create() {
            let formData = new FormData();
            formData.append('file', this.file);
            formData.append('pos', this.form.pos);
            if(this.form.costcenters != 'null'){
                formData.append('showroom', this.form.costcenters.name);
            }else{
                formData.append('showroom', null);
            }
            axios.post('/api/chamcong',formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
            .then(() => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Thêm Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                this.loadChamCong();
            })
            .catch(error => {
                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: error.response.data.message,
                    footer: '<a href>Why do I have this issue?</a>'
                })
            });
        },
 
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#createModalCenter').modal({backdrop: 'static', keyboard: false});
        },
    },
    created() {
        this.loadChamCong();
        Fire.$on('AfterCreate', () => {
            this.loadChamCong();
        });
        Fire.$on('searching', () => {
            this.loadChamCong();
        });
        axios.get("/api/users-costcenters")
        .then(response => {
            this.costcenters = response.data;
        });
        axios.get("/api/users-chamcong")
        .then(response => {
            this.users = response.data;
        });
        axios.get("/api/bophan-chamcong")
        .then(response => {
            this.bophan = response.data;
        });
        axios.get("/api/checksm")
        .then(response => {
            this.sm = response.data;
        });
    }
}

</script>
