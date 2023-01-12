<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Users</h3>
                        <div class="card-tools">
                            <a href="#" @click="newModal" class="btn btn-primary">
                                Add User <i class="fa fa-user-plus"></i>
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>User Name</th>
                                    <th>Nghỉ Việc</th>
                                    <th>Vai trò</th>
                                    <th>Showroom</th>
                                    <th>Cập nhật</th>
                                    <th>Sửa/Xóa</th>
                                </tr>
                                <tr v-for="user in users.data" :key="user.id">
                                    <td>{{user.id}}</td>
                                    <td>{{user.name}}</td>
                                    <td>{{user.email}}</td>
                                    <td>{{user.username}}</td>
                                    <td>
                                    <span v-if="user.off != ''">{{user.off | myDate}}</span>
                                    </td>
                                    <td>
                                        <span v-for="role in user.roles" class="badge badge-info">{{role}}</span> &nbsp;
                                    </td>
                                    <td><span v-for="costcenter in getName(user.costcenters)" class="badge badge-light">{{costcenter}}</span></td>
                                    <td>{{user.updated_at | myDate}}</td>
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
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="Họ và tên" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.email" type="email" name="email" placeholder="Email" class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="email"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.username" type="text" name="username" placeholder="Mã số nhân viên" class="form-control" :class="{ 'is-invalid': form.errors.has('username') }">
                                <has-error :form="form" field="username"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Vai trò</label>
                                <multiselect v-model="form.roles" tag-placeholder="Thêm vai trò" placeholder="Tìm hoặc chọn" :options="roles" :multiple="true" :taggable="true"></multiselect>
                            </div>
                            <div>
                                <label>Thuộc Showroom</label>
                                <multiselect v-model="form.costcenters" tag-placeholder="" placeholder="Tìm showroom" label="name" track-by="id" :options="costcenters" :multiple="true" :taggable="true"></multiselect>
                            </div>
                            <div v-show="editmode" class="form-group">
                                <label for="off">Ngày nghỉ việc</label>
                                <input v-model="form.off" type="date" class="form-control" id="off">
                            </div>
                            <div class="form-group">
                                <label>Mật Khẩu</label>
                                <input v-model="form.password" type="password" name="password" placeholder="Mật khẩu" class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password"></has-error>
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
    </div>
</template>
<script>
export default {
    data() {
        return {
            editmode: false,
            users: {},
            roles: [],
            // Create a new form instance
            form: new Form({
                id: '',
                name: '',
                username: '',
                email: '',
                password: '',
                roles: [],
                costcenters: [],
                off: '',
            }),
            costcenters: []
        }
    },
    methods: {
        loadUsers(page = 1) {
            let queryName = queryString.stringify({name: this.$parent.search});
            axios.get("/api/users?page=" + page + '&' + queryName).then(({ data }) => (this.users = data));
        },
        createUser() {
            this.$Progress.start();
            this.form.post('/api/users')
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
            if (this.form.password == '') {
                this.form.password = undefined;
            }
            // console.log('Editing data');
            this.form.put('api/users/' + this.form.id)
                .then(() => {
                    // success
                    $('#userModalCenter').modal('hide');
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
                    // swal.fire({
                    //     type: 'error',
                    //     title: 'Oops...',
                    //     text: 'Something went wrong!',
                    //     footer: '<a href>Bạn đã bị khóa chức năng này?</a>'
                    // })
                    this.form.delete('api/users/' + user.id).then(() => {
                        swal(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        this.users.splice(this.users.indexOf(user), 1);
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
        axios.get("/api/getallroles").then(({ data }) => (this.roles = data));
        axios.get("/api/costcenters-list").then(({ data }) => (this.costcenters = data));
    }
}

</script>
