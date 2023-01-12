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
                <h3 class="card-title pull-left">Báo Cáo Thi Công</h3>
                <div class="card-tools">
                    <a href="#" @click="newModal" class="btn btn-primary">
                        Thêm
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <!-- <th>Showroom</th> -->
                            <th>ĐH</th>
                            <th style="width: 150px;">Thông Tin Khách</th>                       
                            <th>Trạng Thái</th>
                            <th>Đã Thu</th>
                            <th>Phát Sinh</th>
                            <th>Mô Tả</th>
                            <th>Ghi Chú</th>
                            <th>Hình Ảnh</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="d in bctc.data">
							<td>{{d.so_number.so_number}}</td>
							<td>{{d.so_number.name}} / {{d.so_number.address}} / {{d.so_number.delivery_date}}</td>
							<td>{{d.status}}</td>
							<td>{{d.dathu | currency}}</td>
							<td>{{d.phatsinh | currency}}</td>
							<td>{{d.mota}}</td>
                            <td>{{d.note}}</td>
                            <td>
                            	<template v-for="e in d.attachs">
                            		<a target="_bank" v-bind:href="e.src">
                                        <img class="img-list" v-bind:src="e.src">
                                    </a>
                            	</template>
                            </td>
                            <td>
                           		<a href="#" @click="editModal(d)">
                                    <i class="fa fa-edit blue"></i>
                                </a>
                                
                                <a href="#" @click="deleteBC(d.id)" v-if="d.admin == 1">
                                    /<i class="fa fa-trash red"></i>
                                </a>
                                
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <pagination :data="bctc" @pagination-change-page="loadbctc"></pagination>
            </div>
        </div>

        <div class="modal fade" id="userModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Thêm Báo Cáo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Chọn Đơn</label>
                            <multiselect v-model="form.so_number" deselect-label="Can't remove this value" track-by="id" label="info" placeholder="Chọn Đơn Hàng" :options="donhang" :searchable="true" :allow-empty="true" :multiple="false" :taggable="true" @close="findinfo" >
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.info }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                    	<div v-if="check == 1">
                    		<div v-for="l in listsp">
                    			<div class="row">
		                            <div class=" col-md-6">
		                                <label>Kiện</label>
		                                <input v-model="form.kien = l.item" type="text" class="form-control" disabled=""></input>
		                            </div>
		                            <div class=" col-md-6">
		                                <label>Vị Trí</label>
		                                <multiselect v-model="form.pos = l.pos" :options="pos" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn"></multiselect>
		                            </div>
		                        </div>
                    		</div>
                        </div>
                        <div class="row">
                            <div class=" col-md-6">
                                <label>Trạng Thái</label>
	                            <multiselect v-model="form.status" :options="status" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn"></multiselect>
                            </div>
                            <div class=" col-md-6">
                                <label>Thanh Tóan</label>
                                <multiselect v-model="form.type" :options="type" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn"></multiselect>
                            </div>
                        </div>
                        <label>Mô Tả</label>
                        <textarea v-model="form.mota" type="text" class="form-control" placeholder="Nhập chi tiết về khoản tiền. Ai Cầm? Chuyển khoản qua Bank nào? "></textarea>
                        <div class="row">
                            <div class=" col-md-6">
                                <label>Đã Thu</label>
                                <input v-model="form.dathu" type="number" class="form-control" placeholder="Nhập số tiền thu đã thu"></input>
                            </div>
                            <div class=" col-md-6">
                                <label>Phát Sinh</label>
                                <input v-model="form.phatsinh" type="number" class="form-control" placeholder="Nhập chi phí phát sinh "></input>
                            </div>
                        </div>
                        <label>Ghi Chú</label>
                        <textarea v-model="form.note" type="text" class="form-control" placeholder="Nhập ghi chú "></textarea>

                        <div class="row col-md-12">
                            <label>Ảnh:</label>
                    		<input type="file" id="files" ref="files" v-on:change="handleFileUpload()" multiple />
                            <has-error :form="form" field="file"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="add">
                            Thêm
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Sửa Báo Cáo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Chọn Đơn</label>
                            <multiselect v-model="form.so_number" deselect-label="Can't remove this value" track-by="id" label="info" placeholder="Chọn Đơn Hàng" :options="donhang" :searchable="true" :allow-empty="true" :multiple="false" :taggable="true" @close="findinfo" >
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.info }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                    	<div v-if="check == 1">
                    		<div v-for="l in listsp">
                    			<div class="row">
		                            <div class=" col-md-6">
		                                <label>Kiện</label>
		                                <input v-model="form.kien = l.item" type="text" class="form-control" disabled=""></input>
		                            </div>
		                            <div class=" col-md-6">
		                                <label>Vị Trí</label>
		                                <multiselect v-model="form.pos = l.pos" :options="pos" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn"></multiselect>
		                            </div>
		                        </div>
                    		</div>
                        </div>
                        <div class="row">
                            <div class=" col-md-6">
                                <label>Trạng Thái</label>
	                            <multiselect v-model="form.status" :options="status" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn"></multiselect>
                            </div>
                            <div class=" col-md-6">
                                <label>Thanh Tóan</label>
                                <multiselect v-model="form.type" :options="type" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn"></multiselect>
                            </div>
                        </div>
                        <label>Mô Tả</label>
                        <textarea v-model="form.mota" type="text" class="form-control" placeholder="Nhập chi tiết về khoản tiền. Ai Cầm? Chuyển khoản qua Bank nào? "></textarea>
                        <div class="row">
                            <div class=" col-md-6">
                                <label>Đã Thu</label>
                                <input v-model="form.dathu" type="number" class="form-control" placeholder="Nhập số tiền thu đã thu"></input>
                            </div>
                            <div class=" col-md-6">
                                <label>Phát Sinh</label>
                                <input v-model="form.phatsinh" type="number" class="form-control" placeholder="Nhập chi phí phát sinh "></input>
                            </div>
                        </div>
                        <label>Ghi Chú</label>
                        <textarea v-model="form.note" type="text" class="form-control" placeholder="Nhập ghi chú "></textarea>

                        <div class="row col-md-12">
                            <label>Ảnh:</label>
                    		<input type="file" id="files" ref="files" v-on:change="handleFileUpload()" multiple />
                            <has-error :form="form" field="file"></has-error>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="edit">
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
            selectorders: [],            
            products: [],
            bctc: [],
            listsp: [],
            check:0,
            phone:'',
            styleObject: {
			    padding: '30px 15px 0px 0px'
			},
            donhang: [],
            status: ['Đã Xong','Lỗi'],
            pos: ['Nhà Máy','Showroom'],
            type: ['Quẹt Thẻ', 'Tiền Mặt', 'Chuyển Khoản'],
            form: new Form({
                so_number: '',
                status: '',
                note:'',
                kien:'',
                pos:'',
                dathu:'',
                type: '',  
                mota:'',  
                phatsinh:'',            
                id:'',
                id_tc:'',
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
        searchdata() {
            this.loadbctc();
        },
    	findinfo(){
    		let data = this.form.so_number;
    		if (data.type == 1) {
    			this.check = 1;
    			this.listsp = data.product;
    			console.log(listsp);
    		}else{
    			this.listsp = [];
    			this.check = 0;
    		}
    	},
        edit(){
            let formData = new FormData();
            if (this.files) {
                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    formData.append('file[' + i + ']', file);
                }
            }

            if (this.listsp) {
                for( var i = 0; i < this.listsp.length; i++ ){
                    let item = this.listsp[i];
                    formData.append('item[' + i + ']', JSON.stringify(item));
                    
                }
            }
            formData.append('status', this.form.status);
            formData.append('id_tc', this.form.so_number.id);
            formData.append('so_number', this.form.so_number.so_number);
            formData.append('costcenter', this.form.so_number.costcenter);
            formData.append('type', this.form.type);
            formData.append('id', this.form.id);
            formData.append('mota', this.form.mota);
            formData.append('dathu', this.form.dathu);
            formData.append('phatsinh', this.form.phatsinh);
            formData.append('note', this.form.note);
            axios.post('/api/editbctc',formData, {
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
                this.loadbctc();
            	$('#ModalCenter').modal('hide');

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

        handleFileUpload() {
            this.files = this.$refs.files.files;
        },

        add(){
        	let formData = new FormData();
            if (this.files) {
                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    formData.append('file[' + i + ']', file);
                }
            }

            if (this.listsp) {
                for( var i = 0; i < this.listsp.length; i++ ){
                    let item = this.listsp[i];
                    formData.append('item[' + i + ']', JSON.stringify(item));
                    
                }
            }
            formData.append('status', this.form.status);
            formData.append('id_tc', this.form.so_number.id);
            formData.append('costcenter', this.form.so_number.costcenter);
            formData.append('so_number', this.form.so_number.so_number);
            formData.append('type', this.form.type);
            formData.append('mota', this.form.mota);
            formData.append('dathu', this.form.dathu);
            formData.append('phatsinh', this.form.phatsinh);
            formData.append('note', this.form.note);
            axios.post('/api/addbctc',formData, {
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
                this.loadbctc();
            	$('#userModalCenter').modal('hide');

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

        deleteBC(t){
            axios.get('api/deletebctc?id=' + t)
                .then(() => {
                    this.loadbctc();
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },

        editModal(cv){
        	this.listsp = [];
            this.form.reset();
            $('#ModalCenter').modal('show');
            this.form.fill(cv);
            if (cv.check == 1) {
    			this.check = 1;
            	this.listsp = cv.product;
    		}else{
    			this.listsp = [];
    			this.check = 0;
    		}
        },

 

        loadbctc(page = 1) {
            this.$Progress.start();
            axios.get('api/bctc?page=' + page + '&' +  this.getPara())
                .then(responsive => {
                    this.bctc = responsive.data;
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
        },

        newModal() {
        	this.listsp = [];
            this.form.reset();
            this.editmode = false;
            $('#userModalCenter').modal('show');
        },
    },
    created() {
        this.loadbctc();
        Fire.$on('searching', () => {
            this.loadbctc();
        });
        axios.get('/api/getdonhang').then(({ data }) => (this.donhang = data));
    },
}

</script>
