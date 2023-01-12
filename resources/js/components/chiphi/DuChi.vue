<template>
    <div class="">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chọn ngày</label>
                    <date-range-picker class="form-control" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges">
                        <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                    </date-range-picker>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Chọn Trạng Thái </label>
                    <select  class="form-control" v-model="status" @change="onChangeSearch($event)">
                        <option v-for="option in $parent.ttchiphi" v-bind:value="option.index" :selected="option.index">
                            {{ option.value }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label></label>
                    <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Bảng Dự Chi</h3>
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
                                    <th>Bộ Phận</th>
                                    <th>Tên Khoản Chi</th>
                                    <th>Ngày Đề Xuất</th>
                                    <th>Số Tiền {{tong.tong | currency}}</th>
                                    <th>Người Đề Xuất</th>
                                    <th>Thời Gian</th>
                                    <th style="width: 100px;">Tài Liệu</th>
                                    <th>Mô Tả</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="d in duchi.data">
									<td>{{d.bophan}}</td>
									<td>{{d.name}}</td>
									<td>{{d.date_dx}}</td>
									<td>{{d.dachi | currency}} / {{d.amount | currency}}</td>
									<td>{{d.name_dx}}</td>
									<td>{{d.start_chi}} - {{d.end_chi}}</td>
									<td style="width: 100px;">
										<template v-for="(i,k) in d.attachs">
	                                   		<a target="_blank" v-bind:href="i.src">
	                                   			Tài Liệu {{k}}
	                                   		</a></br>
										</template>
                                   	</td>
                                    <td>{{d.note}}</td>
                                    <td>
                                   		<a href="#" @click="editModal(d)" v-if="d.login == 9202 || d.login == 9378 || d.login == 9073 || d.login == d.tao">
                                            <i class="fa fa-edit blue"></i>/
                                        </a>
                                        
                                        <a href="#" @click="deleteDC(d)" v-if="d.login == d.tao">
                                            <i class="fa fa-trash red"></i>/
                                        </a>
                                        
                                        <a href="#" @click="chiketoan(d)" v-if="d.login == 9202 && d.duyet == 0 || d.login == 9073 && d.duyet == 0 || d.login == 9378 && d.duyet == 0">
                                            <i class="fa fa-check-square "></i>/
                                        </a>
                                        <a href="#" @click="chihoa(d)"  v-if="d.login == 9202 && d.duyet == 1 || d.login == 9003 && d.duyet == 1 || d.login == 9378 && d.duyet == 1">
                                            <i class="fa fa-check-square "></i>/
                                        </a>
                                        <a href="#" @click="duyet(d)" v-if="d.login == 9201	&& d.duyet == 2 && d.dachi < d.amount">
				                            <i class="fa fa-exchange green"></i>
				                        </a>
                                        <div>
                                           <span v-if="d.duyet == 0">Kế Toán Đang Duyệt</span> 
                                           <span v-if="d.duyet == 1" class="bg-dark text-white">Kế Toán Đã Duyệt</span> 
                                           <span v-if="d.duyet == 2 && d.dachi < d.amount" class="bg bg-warning">Chờ Chi</span> 
                                           <span v-if="d.duyet == 2 && d.dachi >= d.amount" class="bg-success text-white">Đã Chi</span> 
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="duchi" @pagination-change-page="loadDuChi"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal-->
        <div class="modal fade" id="create" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Cập Nhật</h5>
                        <h5 class="modal-title"  v-show="!editmode" id="addNewLabel">Thêm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? update() : create()">
                        <div class="form-group col-md-12">
                            <label>Khối: </label>
                            <br>
                            <input type="radio" id="lan-1" value="1" v-model="form.pos">
                            <label for="lan-1">Văn Phòng Miền Bắc</label>
                            <input type="radio" id="lan-2" value="2" v-model="form.pos">
                            <label for="lan-2">Văn Phòng Miền Nam</label>
                            <input type="radio" id="lan-3" value="3" v-model="form.pos">
                            <label for="lan-3">Nhà Máy</label>
                            <input type="radio" id="lan-4" value="4" v-model="form.pos">
                            <label for="lan-4">Sales</label>
                        </div>
						<div class="form-group col-md-12">
                            <label>Showroom</label>
                            <multiselect v-model="form.costcenters" ref="costcenters" tag-placeholder="Thêm Showroom" placeholder="Tìm hoặc chọn" :options="costcenters" :multiple="false" label="name" :taggable="true"></multiselect>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Tên Khoản Chi</label>
                            <input v-model="form.name" type="text" class="form-control" placeholder="Tên *" :class="{ 'is-invalid': form.errors.has('name') }">
                            <has-error :form="form" field="name"></has-error>
                        </div>
                        <div class="row form-group">
	                        <div class="form-group col-md-6">
	                            <label>Start</label>
	                            <input v-model="form.start_chi" type="date" class="form-control">
	                        </div>
	                        <div class="form-group col-md-6">
	                            <label>End</label>
	                            <input v-model="form.end_chi" type="date" class="form-control">
	                        </div>
                        </div>
                        <div class="row form-group">
	                        <div class="form-group col-md-6">
	                            <label>Số Tiền</label>
	                            <input v-model="form.amount" type="number"> 
	                        </div>
	                        <div class="form-group col-md-6">
	                            <label>Ngày Đề Xuất</label>
	                            <input v-model="form.date_dx" type="date" class="form-control">
	                        </div>
                        </div>
                        <div class="form-group">
                            <textarea v-model="form.note" type="text" class="form-control" placeholder="Nhập ghi chú "></textarea>
                        </div>
                        <div class="col-md-12">
                            <label>Tài Liệu</label>
                    		<input type="file" id="files" ref="files" v-on:change="handleFileUpload()" multiple />
                        </div>
                        <div class="modal-footer">
                            <button v-show="editmode" type="submit" class="btn btn-success">Cập Nhật</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                            <button type="submit" v-show="!editmode" class="btn btn-primary">Tạo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="duyet" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Cập Nhật</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    	<h4>Tên Chi: {{namechi}}</h4>
                    	<span>Đã Chi: {{dachi}}</span>
                    </div>
                    <form @submit.prevent="xacnhan()">
                        <div class="form-group col-md-12">
                            <label>Số Tiền</label>
                            <input v-model="formduyet.money" type="number"> 
                            <input v-model="formduyet.id" type="hidden"> 
                            <input v-model="formduyet.end_chi" type="hidden"> 
                            <input v-model="formduyet.start_chi" type="hidden"> 
                        </div>
                        <div class="form-group">
                            <textarea v-model="formduyet.note" type="text" class="form-control" placeholder="Nhập ghi chú "></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Duyệt</button>
                            <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    data() {
        return {
            tong:{
                tong: 0,
            },
        	duchi: {},
            opens: "center",
            editmode: false,
            costcenters: [],
            file: '',
            dachi: '',
            status: '',
            namechi: '',
            duchi: [],
            form: new Form({
                end_chi: '',
                amount: '',
                pos: '',
                id: '',
                note: '',
                start_chi: '',
                // date: '',
                file: '',
                name: '',
                date_dx: '',
                costcenters: [],
            }),
            formduyet: new Form({
                money: '',
                id: '',
                note: '',
                start_chi: '',
                end_chi: '',
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
        searchdata() {
            this.loadDuChi();
        },
        onChangeSearch(event) {
        },
    	chihoa(d){
    		axios.get("/api/duyetchi?id=" + d.id + '&duyet=2')
    		.then(() => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Duyệt Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                this.loadDuChi();
            });
    	},
        reTotal(){
            this.tong.tong = this.duchi.data.reduce(function(v, k){
                    return v + Number(k.amount);
            }, 0);
            // console.log(this.tong.tong);
        },
    	chiketoan(d){
    		axios.get("/api/duyetchi?id=" + d.id + '&duyet=1')
    		.then(() => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Duyệt Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                this.loadDuChi();
            });
    	},
    	xacnhan(){
    		this.formduyet.post('/api/duyetcp')
            .then(() => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Thêm Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                this.loadDuChi();
            	$('#duyet').modal('hide');

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
        update(){
            let formData = new FormData();
            if (this.files) {
                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    formData.append('file[' + i + ']', file);
                }
            }
            formData.append('pos', this.form.pos);
            formData.append('start_chi', this.form.start_chi);
            formData.append('date_dx', this.form.date_dx);
            formData.append('end_chi', this.form.end_chi);
            formData.append('amount', this.form.amount);
            formData.append('name', this.form.name);
            formData.append('note', this.form.note);
            if(this.form.costcenters != 'null'){
            	formData.append('showroom', this.form.costcenters.name);
            }else{
                formData.append('showroom', null);
            }
            formData.append('id', this.form.id);
            axios.post('/api/dc',formData, {
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
                this.loadDuChi();
            })
            .catch(error => {
                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: error.response.data.message,
                    footer: '<a href>Why do I have this issue?</a>'
                })
            });
            $('#create').modal('hide');
            
        },
       
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
        },
        handleFileUpload() {
            this.files = this.$refs.files.files;
        },
        onChange(event) {
            this.position = event.target.value;
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
        loadDuChi(page = 1) {
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });

            let queryName = queryString.stringify({ name: this.$parent.search });

            axios.get("/api/duchi?page=" + page + '&' + queryName +  '&' + dates_para + '&status=' + this.status).then(response => {
                this.duchi = response.data;
                this.reTotal();
            });
        },
        create() {
            let formData = new FormData();
            for( var i = 0; i < this.files.length; i++ ){
                let file = this.files[i];
                formData.append('file[' + i + ']', file);
            }
            formData.append('pos', this.form.pos);
            formData.append('start_chi', this.form.start_chi);
            formData.append('date', this.form.date);
            formData.append('date_dx', this.form.date_dx);
            formData.append('end_chi', this.form.end_chi);
            formData.append('amount', this.form.amount);
            formData.append('name', this.form.name);
            formData.append('note', this.form.note);
            if(this.form.costcenters != 'null'){
                formData.append('showroom', this.form.costcenters.name);
            }else{
                formData.append('showroom', null);
            }
            axios.post('/api/duchi',formData, {
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
            	$('#create').modal('hide');

                this.loadDuChi();
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
            $('#create').modal({backdrop: 'static', keyboard: false});
        },

        duyet(d) {
            this.dachi = d.dachi;
            this.namechi = d.name;
            this.formduyet.id = d.id;
            this.formduyet.start_chi = d.start_chi;
            this.formduyet.end_chi = d.end_chi;
            $('#duyet').modal({backdrop: 'static', keyboard: false});
        },

        editModal(d) {
            this.editmode = true;
            this.form.reset();
            $('#create').modal('show');
            this.form.fill(d);
        },

        deleteDC(d) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    this.form.delete('api/duchi/' + d.id).then(() => {
                        
                    }).catch(() => {
                        swal(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    });
			        this.loadDuChi();

                }
            })
        },
    },
    created() {
        this.loadDuChi();
        Fire.$on('AfterCreate', () => {
            this.loadDuChi();
        });
        Fire.$on('searching', () => {
            this.loadDuChi();
        });
        axios.get("/api/users-costcenters")
        .then(response => {
            this.costcenters = response.data;
        });
    }
}

</script>
