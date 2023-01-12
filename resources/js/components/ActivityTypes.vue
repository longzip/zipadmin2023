<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h2>Các loại hoạt động tư vấn, chốt</h2>
                        </div>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#activityTypeModal">
                                Thêm loại
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>Tên loại</th>
                                    <th>Mô tả</th>
                                    <th>Ngày tạo</th>
                                    <th>Sửa/Xóa</th>
                                </tr>
                                <tr v-for="item in activityTypes">
                                    <td>{{item.id}}</td>
                                    <td>{{item.name}}</td>
                                    <td>{{item.description}}</td>
                                    <td>{{item.created_at | myDate}}</td>
                                    <td>
                                        <a href="#" @click="editActivityType(item)"><i class="fa fa-edit blue"></i></a>
                                        /
                                        <a href="#" @click="deleteActivityType(item.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Button trigger modal -->
        <!-- Modal -->
        <div class="modal fade" id="activityTypeModal" tabindex="-1" role="dialog" aria-labelledby="activityTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 v-show="!editmode" class="modal-title">Thêm mới loại hoạt động</h3>
                        <h3 v-show="editmode" class="modal-title">Sửa loại hoạt động</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input v-model="form.name" class="form-control form-control-lg" type="text" placeholder="Nhập tên loại hoạt động" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                        <br>
                        <textarea v-model="form.description" type="text" placeholder="Mô tả loại hoạt động" class="form-control form-control-lg" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                        <has-error :form="form" field="description"></has-error>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button v-show="!editmode" type="button" @click.prevent="createactivityType" class="btn btn-primary">Thêm loại</button>
                        <button v-show="editmode" type="button" @click.prevent="updateActivityType" class="btn btn-primary">Lưu</button>
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
            activityTypes: {},
            form: new Form({
                id: '',
                name: '',
                description: ''
            })
        }
    },
    methods: {
        createactivityType() {
            this.form.post('api/activity-types')
                .then(response => {
                    this.form.reset();
                    $('#activityTypeModal').modal('hide');
                    this.loadactivityTypes();
                })
        },
        editActivityType(activityType) {
            this.form.fill(activityType);
            this.editmode = true;
            $('#activityTypeModal').modal('show');
        },
        updateActivityType() {
            this.form.put('/api/activity-types/' + this.form.id)
                .then(response => {
                    
                    $('#activityTypeModal').modal('hide');
                    this.form.reset();
                    this.editmode = false;
                    swal.fire(
                        'Cập nhật!',
                        'Thông tin đã được lưu.',
                        'success'
                    )
                    this.loadactivityTypes();
                })
        },
        loadactivityTypes() {
            this.$Progress.start();
            axios.get("api/activity-types")
                .then(response => {
                    this.activityTypes = response.data.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
        },
        deleteActivityType(id) {
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
                    axios.delete('api/activity-types/' + id)
                        .then(() => {
                            swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            this.loadactivityTypes();
                        })
                        .catch(() => {
                            swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                footer: '<a href>Why do I have this issue?</a>'
                            })
                        })

                }
            })
        }
    },
    created() {
        this.loadactivityTypes();
    }
}

</script>
