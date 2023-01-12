<template>
    <div class="">
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
                            <label>Chọn năm </label>
                            <select  class="form-control" v-model="search.year" @change="onChangeSearch($event)">
                                <option v-for="option in $parent.year" v-bind:value="option.value" :selected="option.value == search.year">
                                    {{ option.nam }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn P </label>
                            <select  class="form-control" v-model="search.pt" @change="onChangeSearch($event)">
                                <option v-for="option in $parent.pt" v-bind:value="option.value" :selected="option.value == search.pt">
                                    {{ option.index }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Lương Phụ Cấp</h3>
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
                                    <th>KPI</th>
                                    <th>Lương Khác</th>
                                    <th>Lỗi</th>
                                    <th>Công Tác Phí</th>
                                    <th>Lương Khác</th>
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="s in show">
                                    <td>{{s.name}}</td>
                                    <td>{{s.luong1}}</td>
                                    <td>{{s.luong2}}</td>
                                    <td>{{s.luong3}}</td>
                                    <td>{{s.luong4}}</td>
                                    <td>{{s.luong5}}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                   <!--  <div class="card-footer">
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
                            <label for="luongkhac">Lương Khác</label>
                            <input v-model="form.luongkhac" type="number" class="form-control" id="luongkhac">
                            {{form.luongkhac | currency}}
                        </div>
                        <div class="form-row col-md-3">
                            <label for="kpi">KPI</label>
                            <input v-model="form.kpi" type="number" class="form-control" id="kpi">
                            {{form.kpi | currency}}
                        </div>
                        <div class="form-row col-md-3">
                            <label for="bhxh">BHXH</label>
                            <input v-model="form.bhxh" type="number" class="form-control" id="bhxh">
                            {{form.bhxh | currency}}
                        </div>
                        <div class="form-row col-md-3">
                            <label for="congtac">Công Tác</label>
                            <input v-model="form.congtac" type="number" class="form-control" id="congtac">
                            {{form.congtac | currency}}
                        </div>
                        <div class="form-row col-md-3">
                            <label for="loi">Lỗi</label>
                            <input v-model="form.loi" type="number" class="form-control" id="loi">
                            {{form.loi | currency}}
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
                bhxh: 0,
                luongkhac: 0,
                congtac: 0,
                kpi: 0,
                loi: 0,
            }),
            search: new Form({
                year: '14',
                pt: '12',
            }),
        }
    },
    methods: {
        newModal(){
            $('#addluongkhac').modal('show');
        },
        loadLuong(page = 1) {
            axios.get('api/showluongkhac?type=1').then(response => {
                this.show = response.data;
            });
        },
        onChangeSearch(event) {
            this.loadLuong();
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
            axios.get('api/addluongphu?luongkhac=' + this.form.luongkhac + '&kpi=' + this.form.kpi + '&bhxh=' + this.form.bhxh + '&bophan=' + vitri + '&nv=' + nv + '&date=' + this.form.ngayapdung + '&loi=' + this.form.loi + '&congtac=' + this.form.congtac).then(response => {
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
