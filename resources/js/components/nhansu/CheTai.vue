<template>
    <div class="">
        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Chế Tài</h3>
                        <div class="card-tools">
                            <a href="#" @click="newModal" class="btn btn-primary">Thêm </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <!-- <th>Mã</th> -->
                                    <th>Tên</th>
                                    <th>Mô tả</th>
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="user in users.data" :key="user.id">
                                    <!-- <td><a href="#" @click="detailModal(user)">CT-<span v-if="user.id < 10">00</span><span v-if="10 < user.id && user.id < 100">0</span>{{user.id}}</a></td>  -->
                                    <td><a href="#" @click="detailModal(user)">{{user.name}}</a></td>
                                    <td><span style="white-space: pre-line;">{{user.huong_dan}}</span></td>
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
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm Chế Tài</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa Chế Tài</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Tên chế tài</label>
                                <input v-model="form.name" type="text" name="name" placeholder="Nhập Tên Chế Tài" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                             <div class="form-group">
                                <label>Mô tả</label>
                                <textarea style="white-space: pre-line;" v-model="form.huong_dan" type="text" name="huong_dan" placeholder="Nhập hướng dẫn" class="form-control" :class="{ 'is-invalid': form.errors.has('huong_dan') }"></textarea>
                                <has-error :form="form" field="huong_dan"></has-error>
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
        <div class="modal fade" id="userdetail" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm Chế Tài</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa Chế Tài</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Tên Chế Tài: </label>
                                <label >{{user.name}}</label>
                            </div>
                              <div class="form-group">
                                <label>Mô tả: </label>
                                <br>
                                <label style="white-space: pre-line;">{{user.huong_dan}}</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
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
            editmode: false,
            users: {},
            user: {},
            roles: [],
            name: [],
            // Create a new form instance
            form: new Form({
                id: '',
                name: '',
                quytrinh: '',
                detail: '',
                link: '',
                roles: [],
                huong_dan: '',
            }),
        }
    },
    methods: {
        detailModal(user) {
            this.user = user;
            $('#userdetail').modal('show');
        },
        loadUsers(page = 1) {
            let queryName = queryString.stringify({name: this.$parent.search});
            axios.get("/api/chetai?page=" + page + '&' + queryName).then(({ data }) => (this.users = data));
        },
        createUser() {
            this.$Progress.start();
            this.form.post('/api/chetai')
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
            this.form.put('api/chetai/' + this.form.id)
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
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#userModalCenter').modal('show');
        },
        deleteUser(user) {
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
                    this.form.delete('api/chetai/' + user.id).then(() => {
                        swal.fire(
                            "Deleted!",
                            "Your file has been deleted.",
                            "success"
                        );
                         this.loadUsers();
                    }).catch(() => {
                        swal.fire("Failed!", "There was something wrong.", "warning");
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
    }
}

</script>

