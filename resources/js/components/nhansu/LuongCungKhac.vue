<template>
    <div class="">
        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lương Cơ Bản</h3>
                        <div class="card-tools">
                            <a href="#" @click="newModal" class="btn btn-primary">
                                Thêm  <i class="fa fa-user-plus"></i>
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>Nhân Viên / Bộ Phận</th>
                                    <th>Lương Cứng</th>
                                    <th>Tiền Ăn</th>
                                    <th>Xăng Xe</th>
                                    <th>Điện Thoại</th>
                                    <th>Trách Nhiệm</th>
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="s in show">
                                   	<td>{{s.name}}</td>
                                   	<td>{{s.luong1}}</td>
                                    <td>{{s.luong2}}</td>
                                   	<td>{{s.luong4}}</td>
                                   	<td>{{s.luong5}}</td>
                                   	<td>{{s.luong3}}</td>
                                   	<td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                  <!--   <div class="card-footer">
                        <pagination :data="show" @pagination-change-page="loadLuong"></pagination>
                    </div> -->
                </div>
            </div>
        </div>
        <!-- User detail -->
        <div class="modal fade" id="addluongkhac" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
	                    <h5 class="modal-title" id="exampleModalLongTitle">Thêm</h5>
	                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                        <span aria-hidden="true">&times;</span>
	                    </button>
	                </div>
	                <div class="modal-body row">
                        <div class="form-group col-md-6">
	                    	<label>Bộ phận</label>
	                        <multiselect class="form-control" style="padding: 0;" v-model="form.role" :options="roles" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
	                    </div>
                        <div class="form-group col-md-6">
                            <label>Nhân viên </label>
                            <multiselect class="form-control" style="padding: 0;" v-model="form.sale" :options="nv" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                        <div class="form-row col-md-3">
                            <label for="luong">Lương Cứng</label>
                            <input v-model="form.luong" type="number" class="form-control" id="luong">
                        	{{form.luong | currency}}
                        </div>
                        <div class="form-row col-md-3">
                            <label for="xangxe">Xăng Xe</label>
                            <input v-model="form.xangxe" type="number" class="form-control" id="xangxe">
                        	{{form.xangxe | currency}}
                        </div>
                        <div class="form-row col-md-3">
                            <label for="phone">Điện Thoại</label>
                            <input v-model="form.phone" type="number" class="form-control" id="phone">
                        	{{form.phone | currency}}
                        </div>
                        <div class="form-row col-md-3">
                            <label for="tienan">Tiền Ăn</label>
                            <input v-model="form.tienan" type="number" class="form-control" id="tienan">
                        	{{form.tienan | currency}}
                        </div>
                        <div class="form-row col-md-3">
                            <label for="trachnhiem">Trách Nhiệm</label>
                            <input v-model="form.trachnhiem" type="number" class="form-control" id="trachnhiem">
                        	{{form.trachnhiem | currency}}
                        </div>
                        <div class="form-row col-md-3">
                            <label for="off">Ngày Áp Dụng</label>
                            <input v-model="form.ngayapdung" type="date" class="form-control" id="off">
                        </div>
	                </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <a href="#" @click="add" class="btn btn-primary">Tạo</a>
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
            nv: [],
            show: [],
            roles: [],
            form: new Form({
                role: '',
                sale: '',
                ngayapdung: '',
                tienan: 0,
                phone: 0,
                luong: 0,
                xangxe: 0,
                trachnhiem: 0,
            }),
        }
    },
    methods: {
    	newModal(){
            $('#addluongkhac').modal('show');
    	},
        loadLuong(page = 1) {
        	axios.get('api/showluongkhac?type=0').then(response => {
                this.show = response.data;
            });
        },
        add(){
        	if (this.form.role.id) {
        		var vitri = this.form.role.id;
        	}else{
        		var vitri = null;
        	}
        	if (this.form.sale.id) {
        		var nv = this.form.sale.id;
        	}else{
        		var nv = null;
        	}
        	axios.get('api/addluongkhac?luong=' + this.form.luong + '&xang=' + this.form.xangxe + '&phone=' + this.form.phone + '&bophan=' + vitri + '&nv=' + nv + '&date=' + this.form.ngayapdung + '&trachnhiem=' + this.form.trachnhiem + '&tienan=' + this.form.tienan).then(response => {
        			swal.fire({
                        type: 'success',
                        title: '',
                        text: 'Thêm Thành CÔNG',
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                    this.loadLuong();
                })
                .catch(function(error) {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: error.response.data.message,
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });
        },
    },
    created() {
        this.loadLuong();
        Fire.$on('AfterCreate', () => {
            this.loadLuong();
        });
        Fire.$on('searching', () => {
            this.loadLuong();
        });
        axios.get("/api/getallobjectroles").then(({ data }) => (this.roles = data));
        axios.get('/api/getAllUsers').then(({ data }) => (this.nv = data));
    }
}

</script>
