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
                <h3 class="card-title pull-left">Tuyển Dụng</h3>
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
                            <th>Số Lượng</th>
                            <th>Thời Gian</th>
                            <th>CTV</th>
                            <th>Người Đề Xuất</th>
                            <th>Chú Ý</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="td in tuyendung.data">
                            <td>{{td.tenbophan}} {{td.showroom}}</td>
                            <td>{{td.soluong}}</td>
                            <td>{{td.start}} - {{td.end}}</td>
                            <td>
                                <template v-for="name in td.ctv">
                                    <span>{{name.name}},</span>
                                </template>
                            </td>
                            <td>{{td.nguoitao}}</td>
                            <td>{{td.chuy}}</td>
                            <td>
                            	<a href="#" @click="editModal(td)">
                                    <i class="fa fa-edit blue"></i>/
                                </a>
                                
                                <a href="#" @click="deleteTD(td)" v-if="td.login == td.create">
                                    <i class="fa fa-trash red"></i>/
                                </a>
                                
                                <a href="#" @click="addUser(td)" >
                                	<i class="fa fa-user-plus"></i>
	                            </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <pagination :data="tuyendung" @pagination-change-page="loadtuyendung"></pagination>
            </div>
        </div>

        <div class="modal fade" id="userModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? update() : create()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Vị Trí</label>
                                <multiselect v-model="form.roles" tag-placeholder="Thêm Vị Trí" placeholder="Tìm hoặc chọn" :options="roles" :multiple="false" :taggable="true" label="name"></multiselect>
                            </div>
                            <div class="form-group">
                                <label>Số Lượng</label>
                                <input v-model="form.soluong" type="text" name="soluong" placeholder="số lượng" class="form-control">
                            </div>
                            <div>
                                <label>Thuộc Showroom</label>
                                <multiselect v-model="form.costcenters" tag-placeholder="" placeholder="Tìm showroom" label="name" track-by="id" :options="costcenters" :multiple="false" :taggable="true"></multiselect>
                            </div>
                            <div class="form-group">
                                <label for="start">Bắt Đầu</label>
                                <input v-model="form.start" type="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="end">Kết Thúc</label>
                                <input v-model="form.end" type="date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Chú Ý</label>
                                <input v-model="form.chuy" type="text" name="chuy" placeholder="Chú Ý" class="form-control">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="phan" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Phân Tuyển Dụng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    	<div class="form-row col-md-12">
                            <label>Tên Nhân Viên</label>
                            <multiselect v-model="p.user" ref="users" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn nhân viên" :options="users" :searchable="true" :allow-empty="false" :multiple="true">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                            <input type="hidden" name="idtd" v-model="p.id">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="themphan">
                            Cập Nhật
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
            editmode: false,
			users: [],
			roles: [],
			tuyendung: [],
            costcenters: [],
            idtd: '',
			form: new Form({
                id: '',
                chuy: '',
                soluong: '',
                roles: [],
                costcenters: [],
                start: '',
                end: '',
            }),
            p: new Form({
                id: '',
                user: '',
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
            ranges: { //default value for ranges object (if you set this to false ranges will no be rendered)
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
                'P11/Z14': [moment('2020-10-05').startOf('week'), moment('2020-11-01')],
                'P12/Z14': [moment('2020-11-02').startOf('week'), moment('2020-11-29')],
                'P13/Z14': [moment('2020-11-30').startOf('week'), moment('2020-12-27')],
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
    	themphan(){
            // console.log(this.p.user);
            for(var i = 0; i < this.p.user.length; i++){
        		axios.get('/api/phanquyen?id=' + this.p.id + '&idnv=' + this.p.user[i].id)
                .then(() => {
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            }
            this.loadtuyendung();
            $('#phan').modal('hide');
    	},
    	addUser(td) {
    		this.p.id = td.id;
            $('#phan').modal('show');
        },
    	editModal(td) {
            this.editmode = true;
            this.form.reset();
            $('#userModalCenter').modal('show');
            this.form.fill(td);
        },
        deleteTD(td){
        	this.form.delete('api/tuyendung/' + td.id).then(() => {
                this.loadtuyendung();
                swal(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                )
            });
        },
        create(){
            this.$Progress.start();
            this.form.post('/api/tuyendung')
            .then(() => {
                $('#userModalCenter').modal('hide');
                this.loadtuyendung();
                this.$Progress.finish();
            })
            .catch(() => {
                this.$Progress.fail();
            });
        },
        update() {
            this.$Progress.start();
            this.form.put('api/tuyendung/' + this.form.id)
                .then(() => {
                    $('#userModalCenter').modal('hide');
                	this.loadtuyendung();
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        loadtuyendung() {
            this.$Progress.start();
            axios.get('api/tuyendung' + '?' + this.getPara())
                .then(responsive => {
                    this.tuyendung = responsive.data;
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
            this.startDate = moment(this.startDate).add(1, 'day').format().slice(0,10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.endDate = moment(this.endDate).format().slice(0,10);
            this.loadtuyendung();
        },

        newModal() {
            this.form.reset();
            $('#userModalCenter').modal('show');
        },
    },
    created() {
        this.loadtuyendung();
        Fire.$on('searching', () => {
            this.loadtuyendung();
        });
        axios.get("/api/getallobjectroles").then(({ data }) => (this.roles = data));
        axios.get('/api/getAllUsers').then(({ data }) => (this.users = data));
        axios.get("/api/costcenters-list").then(({ data }) => (this.costcenters = data));
    },
}

</script>
