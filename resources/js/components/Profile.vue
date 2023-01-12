<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-5">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active show" href="#activity" data-toggle="tab">Activity</a></li>
                            <li class="nav-item"><a class="nav-link" href="#settings" data-toggle="tab">Settings</a></li>
                            <li class="nav-item"><a class="nav-link" href="#target" data-toggle="tab">Target</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane active show" id="activity">
                                <logs-list :activities="activities.data"></logs-list>
                                <pagination :data="activities" :limit=3 @pagination-change-page="loadActivities"><span slot="prev-nav">&lt; Trước</span>
                                    <span slot="next-nav">Sau &gt;</span></pagination>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="inputlast_name" class="col-sm-2 control-label">Họ và tên</label>
                                        <div class="col-sm-12">
                                            <input type="" v-model="form.last_name" class="form-control" id="inputlast_name" placeholder="Nhập họ và tên" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                            <has-error :form="form" field="last_name"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                        <div class="col-sm-12">
                                            <input type="email" v-model="form.email" class="form-control" id="inputEmail" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password" class="col-sm-12 control-label">Mật khẩu (để trống nếu không muốn thay đổi)</label>
                                        <div class="col-sm-12">
                                            <input type="password" v-model="form.password" class="form-control" id="password" placeholder="Passport" :class="{ 'is-invalid': form.errors.has('password') }">
                                            <has-error :form="form" field="password"></has-error>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button @click.prevent="updateInfo" type="submit" class="btn btn-success">Cập nhật</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="target">
                                <table class="table table-bordered">
                                    <thead class="bg-info">
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>Nhân viên</th>
                                            <th>Số khách</th>
                                            <th>Ngày hoàn thành</th>
                                            <th>Cập nhật</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(target,index) in targets">
                                            <td>{{index}}</td>
                                            <td>{{target.name}}</td>
                                            <td>{{target.number}}</td>
                                            <td>{{target.close}}</td>
                                            <td> ... </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div>
                <!-- /.nav-tabs-custom -->
            </div>
            <!-- end tabs -->
        </div>
    </div>
</template>
<script>
import LogsList from "./elements/LogsList.vue";
export default {
    components: { LogsList },
    data() {
        return {
            form: new Form({
                id: '',
                last_name: '',
                email: '',
                password: ''
            }),
            activities: [],
            targets: []
        }
    },
    methods: {
        updateInfo() {
            this.$Progress.start();
            if (this.form.password == '') {
                this.form.password = undefined;
            }
            this.form.put('api/profile')
                .then(() => {
                    swal.fire(
                        'Lưu!',
                        'Thông tin của bạn đã được lưu.',
                        'success'
                    )
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        loadActivities(page = 1) {
            axios.get('/api/nhat-ky?page=' + page)
                .then(response => {
                    this.activities = response.data
                });
        },
        loadTarget(){
            axios.get('/api/user-targets')
            .then(response => {
                this.targets = response.data;
            });
        }
    },
    created() {
        axios.get("api/profile")
            .then(({ data }) => (this.form.fill(data)));
        this.loadActivities();
        this.loadTarget();
    }
}

</script>
