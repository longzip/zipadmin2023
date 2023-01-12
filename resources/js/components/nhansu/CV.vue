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
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title pull-left">CV</h3>
                <div class="card-tools">
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
                            <th>Vị Trí</th>
                            <th>Tên</th>
                            <th>Năm Sinh</th>
                            <th>SĐT</th>
                            <th>Email</th>
                            <th>Tài Liệu</th>
                            <th>Thời Gian</th>
                            <th>Kết Qủa</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(d,k) in cv">
                            <tr v-for="(nv,knv) in d">
                                <td :rowspan="Object.keys(d).length" v-if="knv===0">{{k}}</td>
                                <td>{{nv.name}} <small>{{nv.ctv}}</small></td>
                                <td>{{nv.bir}}</td>
                                <td>
                                    <span v-if="nv.date == 1" class="label label-info">{{nv.phone}}</span>
                                    <span v-if="nv.date == 0">{{nv.phone}}</span>
                                </td>
                                <td>{{nv.email}}</td>
                                <td>
                                    <a target="_blank" v-bind:href="'/uploads/cv/' + nv.file">
                                        Tải Xuống
                                    </a>
                                </td>
                                <td>{{nv.time | myDateTime}}</td>
                                <td>
                                    <span v-if="nv.status == 1"> Không Đến</span>
                                    <span v-if="nv.status == 2"> Không Đạt</span>
                                    <span v-if="nv.status == 4"> Đạt</span>
                                    <span v-if="nv.hen != null"> Hẹn</span>
                                    <span v-if="nv.nghilam != null"> Nghỉ Làm</span>
                                    <!-- <span v-if="nv.status == 3"> Đi Làm - Mã NV: {{nv.mnv}}</span> -->
                                    <span v-if="nv.dilam != null"> Đi Làm </span>
                                    <span v-if="nv.khong != null"> Không Nhận Việc </span>
                                </td>
                                <td>
                                    <a href="#" @click="editModal(nv)" v-if="nv.login == nv.create && nv.status == 0">
                                        <i class="fa fa-edit blue"></i>/
                                    </a>
                                    
                                    <a href="#" @click="deletecv(nv)" v-if="nv.login == nv.create && nv.status == 0">
                                        <i class="fa fa-trash red"></i>/
                                    </a>
                                    
                                    <a href="#" @click="danhgia(nv)" v-if="nv.login == 9017 && nv.status == 0 || nv.login == 9017 && nv.status == 4">
                                        <i class="fa fa-user-plus"></i>
                                    </a>
                                </td>
                            </tr>
                        </template>   
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="userModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm CV</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa CV</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group ">
                            <label>Vị Trí</label>
                            <multiselect v-model="form.tuyendung" tag-placeholder="Thêm Vị Trí" placeholder="Tìm hoặc chọn" :options="tuyendung" :multiple="false" label="name" :taggable="true"></multiselect>
                        </div>
                        <div class="form-group">
                            <label>Tên UV</label>
                            <input v-model="form.name" type="text" class="form-control" placeholder="Tên *">
                        </div>
                        <div class="row form-group">
                            <div class="form-group col-md-6">
                                <label>Date</label>
                                <input v-model="form.time" type="datetime-local" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Năm Sinh</label>
                                <input v-model="form.bir" type="number" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Sđt</label>
                                <input v-model="form.phone" type="number" class="form-control"> 
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <input v-model="form.email" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea v-model="form.note" type="text" class="form-control" placeholder="Nhập ghi chú "></textarea>
                        </div>
                        <div class="col-md-12">
                            <label>CV</label>
                            <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" />
                        </div>   
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" v-show="!editmode" class="btn btn-primary" @click="add">
                            Lưu
                        </button>
                        <button type="button" v-show="editmode" class="btn btn-primary" @click="edit">
                            Cập Nhật
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="danhgia" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addNewLabel">đánh giá</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>
                            <span>Ứng Viên: {{name}}</span>
                        </div>
                        <input type="radio" id="lan-1" value="1" v-model="stt">
                        <label for="lan-1">Không Đến</label>
                        <input type="radio" id="lan-2" value="2" v-model="stt">
                        <label for="lan-2">Không Đạt</label>
                        <input type="radio" id="lan-4" value="4" v-model="stt">
                        <label for="lan-4">Đạt</label>
                        <input type="radio" id="lan-5" value="5" v-model="stt">
                        <label for="lan-5">Hẹn</label>
                        <input type="radio" id="lan-7" value="7" v-model="stt">
                        <label for="lan-7">Đi Làm</label>
                        <input type="radio" id="lan-6" value="6" v-model="stt">
                        <label for="lan-6">Đã Nghỉ</label>
                        <input type="radio" id="lan-8" value="8" v-model="stt">
                        <label for="lan-8">Không Nhận Việc</label>
                        
                        <div v-show="this.stt == 7" class="form-group col-md-12">
                            <label>Email</label>
                            <input v-model="email" type="text" class="form-control">
                        </div>
                        <div v-show="this.stt == 7 || this.stt == 8 || this.stt == 6 || this.stt == 5" class="form-group col-md-12">
                            <label>Email</label>
                            <input v-model="date" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="adddg">
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
            stt:1,
            email:'',
            date:'',
            id:'',
            name:'',
            editmode: false,
            cv:{},
            tuyendung:[],
            form: new Form({
                bophan: '',
                phone: '',
                name: '',
                id: '',
                bir: '',
                note: '',
                time: '',
                file: '',
                email: '',
                tuyendung: '',
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
            startDate: moment('2021-03-22').format(),
            endDate: moment('2021-04-18').format(),

            selectedDate: {
                startDate: moment('2021-03-22'),
                endDate: moment('2021-04-18')
            },
        }
    },
    methods: {
        adddg(){
            axios.get('api/sttcv?id=' + this.id + '&email=' + this.email + '&stt=' + this.stt + '&date=' + this.date)
            .then(responsive => {
                this.loadcv();
                this.$Progress.finish();
            })
            .catch(() => this.$Progress.fail());
            $('#danhgia').modal('hide');
        },
        danhgia(cv) { 
            this.email = cv.email;       
            this.id = cv.id;       
            this.name = cv.name;       
            $('#danhgia').modal('show');
        },
        deletecv(cv) {
            this.form.delete('api/cv/' + cv.id).then(() => {
                swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            }).catch();
            $('#userModalCenter').modal('hide');
        },
        edit(){
            let id = this.form.tuyendung.id;
            let name = this.form.tuyendung.name;
            let formData = new FormData();
            formData.append('file', this.file);
            formData.append('tuyendung',id);
            formData.append('bophan', name);
            formData.append('time', this.form.time);
            formData.append('phone', this.form.phone);
            formData.append('bir', this.form.bir);
            formData.append('email', this.form.email);
            formData.append('name', this.form.name);
            formData.append('note', this.form.note);

            this.form.put('api/cv/' + this.form.id,formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(() => {
                this.loadcv();
                swal.fire(
                    'Lưu!',
                    'Đã Update',
                    'success'
                )
            });
            $('#userModalCenter').modal('hide');
        },
        editModal(cv){
            this.editmode = true;
            this.form.reset();
            $('#userModalCenter').modal('show');
            this.form.fill(cv);
        },
        handleFileUpload() {
            this.file = this.$refs.file.files[0];
        },
        add(){
            let formData = new FormData();
            formData.append('file', this.file);
            formData.append('tuyendung', this.form.tuyendung.id);
            formData.append('bophan', this.form.tuyendung.name);
            formData.append('time', this.form.time);
            formData.append('phone', this.form.phone);
            formData.append('bir', this.form.bir);
            formData.append('email', this.form.email);
            formData.append('name', this.form.name);
            formData.append('note', this.form.note);

            axios.post('/api/cv',formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(() => {
                this.loadcv();
                swal.fire(
                    'Lưu!',
                    'Đã Thêm',
                    'success'
                )
            });
            $('#userModalCenter').modal('hide');
        },
        
        loadcv() {
            this.$Progress.start();
            axios.get('api/cv' + '?' + this.getPara())
                .then(responsive => {
                    this.cv = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
        },
        
        getPara() {
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            return  dates_para + '&' + queryName;
        },

        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.loadcv();
        },

        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#userModalCenter').modal('show');
        },
    },
    created() {
        this.loadcv();
        Fire.$on('searching', () => {
            this.loadcv();
        });
        axios.get("/api/alltd")
        .then(response => {
            this.tuyendung = response.data;
        });
    },
}

</script>
