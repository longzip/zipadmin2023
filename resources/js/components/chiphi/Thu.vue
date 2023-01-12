<template>
    <div class="">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thu</h3>
                        <div class="card-tools">
                            <a href="#" @click="newModal" class="btn btn-primary">Thêm </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>Đơn Hàng</th>
                                    <!-- <th>Showroom</th>
                                    <th>Loại Thu</th>
                                    <th>Ngày dự kiến thu</th>
                                    <th>Ngày tiền về</th> -->
                                    <th>Số tiền</th>
                                    <th>Mô tả</th>
                                    <th>Chọn Bank</th>
                                    <th>Danh sách vay</th>
                                </tr>
                                <!-- <tr v-for="user in users.data" :key="user.id">
                                    <td><a href="#" @click="detailModal(user)"><span>{{user.name}}</span></a></td>
                                     <td>{{user.code}}</td>
                                    <td><a v-bind:href="user.link">Xem tài liệu</a></td> -->
                                    <!-- <td>
                                        <a href="#" @click="editModal(user)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteUser(user)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td> -->
                                </tr> 
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="userModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addNewLabel">Thêm Loại Thu</h5>
                        <!-- v-show="!editmode" -->
                        <h5 class="modal-title"  id="addNewLabel">Sửa Loại Thu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="">
                        <!-- editmode ? updateUser() : createUser() -->
                        <div class="modal-body">
                            <div class="form-group col-md-12">
                                <label>Đơn Hàng</label>
                                <multiselect v-model="form.donhang" ref="donhang" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn đơn hàng" :options="donhang" :searchable="true" :allow-empty="true">
                                    <template SLot="singleLabel" slot-scope="{ option }">
                                        <strong>{{ option.name }}</strong>
                                    </template>
                                </multiselect>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Showroom</label>
                                <multiselect v-model="form.showroom" ref="showroom" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn đơn hàng" :options="showroom" :searchable="true" :allow-empty="true">
                                    <template SLot="singleLabel" slot-scope="{ option }">
                                        <strong>{{ option.name }}</strong>
                                    </template>
                                </multiselect>
                            </div>
                            <div class="form-ro w col-md-12">
                                <label >Loại Thu</label> <!-- v-if="chiphi.duyet == 0" -->
                                <textarea  style="white-space: pre-line;" ref="loaithu" v-model="form.loaithu" type="detail" name="loaithu" placeholder="Nhập tên loại thu" class="form-control" :class="{ 'is-invalid': form.errors.has('detail') }"></textarea>
                                <has-error :form="form" field="detail"></has-error>
                            </div>
                            <div class="form-row col-md-12">
                                <label for="off">Ngày dự kiến thu</label>
                                <input v-model="form.ngaythu" ref="ngaythu" type="date" class="form-control" id="off">
                            </div>
                            <div class="form-row col-md-12">
                                <label for="off">Ngày tiền về</label>
                                <input v-model="form.ngaytienve" ref="ngaytienve" type="date" class="form-control" id="off">
                            </div>
                            <div class="form-ro w col-md-12">
                                <label >Số tiền</label> <!-- v-if="chiphi.duyet == 0" -->
                                <textarea  style="white-space: pre-line;" ref="sotien" v-model="form.sotien" type="number" name="sotien" placeholder="Nhập số tiền" class="form-control" :class="{ 'is-invalid': form.errors.has('detail') }"></textarea>
                                <has-error :form="form" field="detail"></has-error>
                            </div>
                            <div class="form-ro w col-md-12">
                                <label >Mô tả</label> <!-- v-if="chiphi.duyet == 0" -->
                                <textarea  style="white-space: pre-line;" ref="loaithu" v-model="form.mota" type="detail" name="mota" placeholder="Nhập mô tả" class="form-control" :class="{ 'is-invalid': form.errors.has('detail') }"></textarea>
                                <has-error :form="form" field="detail"></has-error>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Bankk</label>
                                <multiselect v-model="form.bank" ref="bank" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn bankk" :options="bank" :searchable="true" :allow-empty="true">
                                    <template SLot="singleLabel" slot-scope="{ option }">
                                        <strong>{{ option.name }}</strong>
                                    </template>
                                </multiselect>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Danh sách vay</label>
                                <multiselect v-model="form.danhsachvay" ref="danhsachvay" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn danh sách vay" :options="danhsachvay" :searchable="true" :allow-empty="true">
                                    <template SLot="singleLabel" slot-scope="{ option }">
                                        <strong>{{ option.name }}</strong>
                                    </template>
                                </multiselect>
                            </div>
                        </div>
















                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button  type="submit" class="btn btn-success">Update</button>
                            <!-- v-show="editmode" -->
                            <button id="addContact"  type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
          <div class="modal  fade" id="userdetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Bản</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                          <div class="form-row col-md-12">
                            <div class="form-group col-md-6">
                                <label>Tên Loại Chi Phí: </label>
                                <!-- <label>{{user.name}}</label> -->
                            </div>
                          </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
/*@media screen and (max-width: 768px) { 
    .hide-detail {
        display: none;
    }
}*/
</style>
<script>
export default {
    data() {
        return {
            donhang:[],
            showroom:[],
            loaithu:'',
            ngaythu: '',
            ngaytienve: '',
            sotien:'',
            mota:'',
            bank:[],
            danhsachvay:[],
            thus:{},
            // Create a new form instance
            form: new Form({
                donhang:[],
                showroom:[],
                loaithu:'',
                ngaythu: '',
                ngaytienve: '',
                sotien:'',
                mota:'',
                bank:[],
                danhsachvay:[],
            }),
        }
    },
    methods: {
         addContact() {
            this.form.post('/api/loaichiphi')
                .then(response => {
                    this.$router.push({ name: 'loaichiphi' });
                });
        },
        loadThu(page = 1) {
           axios.get("/api/thu?page=" + page).then(({ data }) => (this.thus = data));
        },
        createUser() {
            this.$Progress.start();
            this.form.post('/api/loaichiphi')
                .then(() => {
                    $('#userModalCenter').modal('hide');
                    this.loadThu();
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        updateUser() {
            this.$Progress.start();
            this.form.put('api/loaichiphi/' + this.form.id)
                .then(() => {
                    // success
                    $('#userModalCenter').modal('hide');
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    );
                    this.loadThu();

                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        editModal(user) {
            this.editmode = true;
            this.form.reset();
            $('#userModalCenter').modal('show');
            this.form.fill(user);
        },
        detailModal(user) {
            this.user = user;
            $('#userdetail').modal('show');
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#userModalCenter').modal('show');
        },
        deleteUser(user) {
            this.$Progress.start();
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                // Send request to the server
                if (result.value) {
                    this.form.delete('api/loaichiphi/' + user.id).then(($res) => {
                        swal.fire(
                            "Deleted!",
                            "Your file has been deleted.",
                            "success"
                        );
                        this.loadThu();
                        this.$Progress.finish();
                    }).catch(() => {
                        swal.fire("Failed!", "There was something wronge.", "warning");
                    });
                }
            })
        },
        getName(values) {
            if (!values) return ''
            return values.map((ten, index, values) => {
                return ten.name
            })
        }

    },
    created() {
        this.loadThu();
        Fire.$on('AfterCreate', () => {
            this.loadThu();
        });
        Fire.$on('searching', () => {
            this.loadThu();
        })
        // axios.get('/api/picklists/users').then(res => this.resources = res.data);
       // axios.get("/api/getallroles").then(({ data }) => (this.roles = data));
        // axios.get("/api/getallroles").then(({ data }) => (this.roles = data));

        // axios.get('api/list-quy-dinh')
        //     .then(response => {
        //         this.decision = response.data;
        //     });
    }
}

</script>

