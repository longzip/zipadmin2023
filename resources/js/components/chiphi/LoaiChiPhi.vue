<template>
    <div class="">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Loại Chi Phí</h3>
                        <div class="card-tools">
                            <a href="#" @click="newModal" class="btn btn-primary">Thêm </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <!-- <th>ID</th> -->
                                    <th>Tên Loại Chi Phí</th>
                                    <!-- <th>Định Mức</th> -->
                                    <!-- <th>Người Kiểm Soát</th>
                                    <th>Bộ Phận Giám Sát</th> -->
                                    <!-- <th>Ký Hiệu</th>
                                    <th>Link</th> --> 
                                    <th>Thao tác</th>
                                </tr>
                                <tr v-for="user in users.data" :key="user.id">
                                    <td><a href="#" @click="detailModal(user)"><span>{{user.name}}</span></a></td>
                                    <!-- <td>{{user.code}}</td>
                                    <td><a v-bind:href="user.link">Xem tài liệu</a></td> -->
                                    <td>
                                        <a href="#" @click="editModal(user)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteUser(user)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="users" @pagination-change-page="loadUsers"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="userModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm Loại Chi Phí</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa Loại Chi Phí</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Tên Loại Chi Phí</label>
                                <input v-model="form.name" type="text" name="name" placeholder="Nhập Tên Loại Chi Phí" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button id="addContact" v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
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
                                <label>{{user.name}}</label>
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
            editmode: false,
            users: {},
            user: {},
            // Create a new form instance
            form: new Form({
                id: '',
                name: '',
                link: '',
                code: '',
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
        loadUsers(page = 1) {
            let queryName = queryString.stringify({name: this.$parent.search});
            axios.get("/api/loaichiphi?page=" + page + '&' + queryName).then(({ data }) => (this.users = data));
        },
        createUser() {
            this.$Progress.start();
            this.form.post('/api/loaichiphi')
                .then(() => {
                    $('#userModalCenter').modal('hide');
                    this.loadUsers();
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
                    this.loadUsers();

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
                        this.loadUsers();
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
        this.loadUsers();
        Fire.$on('AfterCreate', () => {
            this.loadUsers();
        });
        Fire.$on('searching', () => {
            this.loadUsers();
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

