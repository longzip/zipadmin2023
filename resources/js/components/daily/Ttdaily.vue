<template>
    <div class="container-flush mt-3">
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <a class="" href="#" >
                            <h3 class="profile-username text-center">{{ contact.last_name}} <i v-show="contact.isPublished" class="fa fa-check"></i></h3>
                        </a>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Phone</b> <a class="float-right">{{ contact.phone}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right">{{ contact.email}}</a>
                            </li>
                        </ul>
                        <router-link class="btn btn-primary btn-block" :to="{ name: 'editDaily', params: { id: contact.id }}">Sửa thông tin</router-link>
                    </div>
                    <!-- /.card-body -->
                </div>

                <!-- /.card -->
            </div>
            <div class="col-md-9">
                <tabs>
                    <tab name="Chung">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <strong><i class="fa fa-book mr-1"></i>Thông Tin Cơ Bản</strong>
                                <blockquote class="blockquote">
                                    <p class="mb-0">Chú Thích: <i>{{contact.note}}</i></p>
                                    <p class="mb-0"><small class="badge badge-info">{{ contact.status | ContactStatus}}</small></p>
                                    <footer class="blockquote-footer"><cite title="Ngày">{{contact.start_date}}</cite> bởi {{contact.username}} - {{contact.user_id}}</footer>
                                </blockquote>
                                <p class="lead">Đặc điểm</p>
                                <p class="text-muted">
                                    <span class="badge badge-primary" v-for="kh15 in contact.kh15s">{{kh15.name}}: {{kh15.description}}</span>
                                </p>
                                <p class="lead">Lo Lắng</p>
                                <p>
                                    <span class="badge badge-warning" v-for="lost in contact.losts">{{lost.name}}: {{lost.description}}</span>
                                </p>
                                <hr>
                                <strong><i class="fa fa-map-marker mr-1"></i> Địa chỉ</strong>
                                <p class="text-muted">{{ contact.address}}</p>
                                <hr>
                                <strong><i class="fa fa-pencil mr-1"></i> Sản phẩm quan tâm</strong>
                                <p class="text-muted">
                                    <span class="badge badge-success" v-for="product in contact.products">{{product.name}}</span>
                                </p>
                                <hr>
                                <strong><i class="fa fa-file-text-o mr-1"></i> Notes</strong>
                                <p class="text-muted">{{contact.description}}.</p>
                            </div>
                        </div>
                        <contact-tasks :tasks="contact.tasks" @show-task="showTask"></contact-tasks>
                        <attachment :items="contact.attachs" @upload-image="uploadImage"></attachment>
                        <comments :comments="contact.comments" @create-comment="storeContactComment"></comments>
                    </tab>
                    <tab name="Đặc điểm">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Các đặc điểm</div>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li v-for="kh15 in contact.kh15s" :key="kh15.id">
                                        <strong><i class="fas fa-check"></i> {{kh15.name}} <a href="#" @click="deleteKh15(kh15)">
                                                <i class="fa fa-trash red"></i>
                                            </a></strong>
                                        <p class="text-muted">{{kh15.description}}</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <form class="form-inline">
                                    <div class="form-group mb-2">
                                        <label for="seclectKh15" class="sr-only">Chọn</label>
                                        <multiselect id="seclectKh15" v-model="kh15.name" :options="kh15Types" placeholder="Chọn đặc điểm"></multiselect>
                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label for="inputDescription" class="sr-only">Nhập</label>
                                        <input v-model="kh15.description" type="text" class="form-control" id="inputDescription" placeholder="Mô tả">
                                    </div>
                                    <button @click.prevent="addKh15" type="submit" class="btn btn-primary mb-2">Thêm đặc điểm</button>
                                </form>
                            </div>
                        </div>
                    </tab>
                    <tab name="Lo lắng" >
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Các lo lắng</div>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li v-for="lost in contact.losts" :key="lost.id"><strong><i class="fas fa-check"></i> {{lost.name}} <a href="#" @click="deleteLost(lost)">
                                                <i class="fa fa-trash red"></i>
                                            </a></strong>
                                        <p class="text-muted">{{lost.description}}</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <form class="form-inline">
                                    <div class="form-group mb-2">
                                        <label for="seclectLost" class="sr-only">Chọn</label>
                                        <multiselect id="seclectLost" v-model="lost.name" :options="lostTypes" placeholder="Chọn đặc điểm"></multiselect>
                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label for="inputDescription" class="sr-only">Nhập</label>
                                        <input v-model="lost.description" type="text" class="form-control" id="inputDescription" placeholder="Mô tả">
                                    </div>
                                    <button @click.prevent="addLost" type="submit" class="btn btn-primary mb-2">Thêm lo lắng</button>
                                </form>
                            </div>
                        </div>
                    </tab>
                    <tab name="Tư vấn" >
                        <div class="card">
                            <div class="card-footer">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Chọn loại hoạt động</label>
                                        <multiselect v-model="taskcontact.title" :options="activityTypes" :taggable="true" @tag="addActivityType" placeholder="Chọn hoạt động"></multiselect>
                                    </div>
                                    <div class="form-group">
                                        <label>Nội dung kế hoạch</label>
                                        <textarea v-model="taskcontact.task" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Thực hiện</label>
                                        <multiselect v-model="taskcontact.user" :options="users" label="name" track-by="id" placeholder="Chọn người thực hiện"></multiselect>
                                    </div>
                                    <div class="form-group">
                                        <label>Ngày hoàn thành</label>
                                        <input v-model="taskcontact.duedate" type="date" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <button type="button" @click.prevent="taokehoach" class="btn btn-primary">Tạo kế hoạch</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <contact-tasks :tasks="contact.tasks" @show-task="showTask"></contact-tasks>
                    </tab>
                    <tab name="Bình luận">
                        <comments :comments="contact.comments" @create-comment="storeContactComment"></comments>
                    </tab>
                    <!-- Videos -->
                    <tab name="Videos">
                        <contact-videos :videos="contact.videos" @create-video="themvideo" @upload-video="uploadVideo"></contact-videos>
                    </tab>
                    <tab name="Tài liệu">
                        <attachment :items="contact.attachs" @upload-image="uploadImage" :upload="true"></attachment>
                    </tab>
                    <!-- Quote -->
                    <tab name="Báo giá">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Danh sách báo giá</div>
                                <div class="card-tools">
                                    <router-link :to="{ name: 'taobaogiaDL', params: { contactId: this.contact.id }}" class="btn btn-primary"><i class="fas fa-plus-square"></i> Tạo báo giá</router-link>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <quote-list :quotes='contact.quotes'></quote-list>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <!-- /.card-footer -->
                            <div class="card-footer">
                            </div>
                            <!-- /.card-footer -->
                        </div>
                    </tab>
                    <tab name="Đơn hàng">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>SDH</th>
                                    <th>Mã SR</th>
                                    <th>Tạo bởi</th>
                                    <th>Ngày đặt hàng</th>
                                    <th>Ngày giao</th>
                                    <th>Tổng tiền</th>
                                    <th>Đã thu</th>
                                    <th>Trạng thái</th>
                                </tr>
                                <tr v-for="order in contact.orders" :key="order.id">
                                    <td>{{order.so_number}}</td>
                                    <td>{{order.costcenter}}</td>
                                    <td>{{order.user_id}}</td>
                                    <td>{{order.created_at | myDate}}</td>
                                    <td>{{order.delivery_date | myDate}}</td>
                                    <td>{{order.amount}}</td>
                                    <td>{{order.pre_pay}}</td>
                                    <td>{{order.status_id | trangThaiSO}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </tab>
                </tabs>
            </div>
        </div>
        <div class="modal fade" id="taskModalDetail" tabindex="-1" role="dialog" aria-labelledby="taskModalDetailTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalDetailTitle">{{myTask.title}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4>Mô tả</h4>
                        <textarea disabled="true" v-model="myTask.task" class="form-control form-control"></textarea>
                        <h4 class="mt-2">Thêm bình luận</h4>
                        <comments :comments="myTask.comments" @create-comment="storeTaskComment"></comments>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import QuoteList from '../daily/QuoteListDL.vue';
import ContactTasks from '../elements/Task.vue';
import kh15 from '../elements/Kh15.vue';
import ContactVideos from '../contact/ContactVideos.vue';
import Attachment from '../elements/Attachment.vue';
export default {
    components: { QuoteList, kh15, ContactTasks, ContactVideos, Attachment },
    props: ['id'],
    data() {
        return {
            contact: {},
            categories: {},
            activities: {},
            quotes: {},
            quote: {},
            quoteitems: {},
            comments: {},
            comment: {
                body: ''
            },
            myTask: {},
            tasks: {},
            taskcontact: {
                contact_id: '',
                user: {
                    id: this.$user.id,
                    name: this.$user.name
                },
                title: '',
                task: '',
                priority: '',
                duedate: moment().format().split("T")[0],
            },
            activity: {
                subject: '',
                date_start: '',
                due_date: '',
                time_start: '',
                time_end: '',
                status: '',
                note: '',
            },
            company: [],
            file: '',
            attachmens: [],
            task: null,
            youtubes: [],
            youtube_id: '',
            kh15: {
                name: '',
                description: ''
            },
            kh15s: {},
            lost: {
                name: '',
                description: ''
            },
            losts: {},

            //picklist
            users: [],
            activityTypes: [],
            coscenters: [],
            kh15Types: [
                'Nhà chung cư',
                'Nhà riêng',
                'Bao nhiêu con',
                'Có ở cùng bố mẹ',
                'Con có chung phòng không',
                'Có mang việc về nhà không',
                'Hay tiếp khách ở nhà không'
            ],
            lostTypes: [
                'Giá',
                'Gỗ',
                'Cơ khí',
                'Màu sắc',
                'Kích thước',
                'Không gian phòng',
                'Bảo hành',
                'Lắp đặt',
                'Vận chuyển',
                'Khác'
            ],
            images: [],
            index: null
        }
    },
    methods: {
        storeContactComment(comment) {
            axios.put('api/daily/' + this.contact.id + '/comment', {
                    body: comment
                })
                .then(response => {
                    this.contact.comments.push(response.data);
                });
        },
        loadcontact() {
            this.$Progress.start();
            axios.get("/api/daily/" + this.$route.query.id)
                .then(response => {
                    this.contact = response.data.data;
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        loadActivityType() {
            axios.get('/api/activities-list')
                .then(res => this.activityTypes = res.data);
        },
        loadUsers() {
            axios.get('api/users/' + this.$user.id + '/friends')
                .then(res => this.users = res.data)
        },
        getName(values) {
            if (!values) return ''
            return values.map((item, index, values) => {
                return item.name
            })
        },
        addKh15() {
            axios.put('api/daily/' + this.contact.id + '/kh15s', {
                    name: this.kh15.name,
                    description: this.kh15.description
                })
                .then(response => {
                    this.lostTypes.name = '';
                    this.contact.kh15s.push(response.data);
                });
        },
        deleteKh15(kh15) {
            axios.delete('api/kh15s/' + kh15.id)
                .then(() => this.contact.kh15s.splice(this.contact.kh15s.indexOf(kh15), 1))
        },
        addLost() {
            axios.put('api/daily/' + this.contact.id + '/losts', {
                    name: this.lost.name,
                    description: this.lost.description
                })
                .then(response => {
                    this.lostTypes.name = '';
                    this.contact.losts.push(response.data);
                });
        },
        deleteLost(lost) {
            axios.delete('api/losts/' + lost.id)
                .then(() => this.contact.losts.splice(this.contact.losts.indexOf(lost), 1))
        },
        addActivityType(newActivityType) {
            this.activityTypes.push(newActivityType);
        },
        taokehoach() {
            axios.put('api/daily/' + this.contact.id + '/tasks', {
                    title: this.taskcontact.title,
                    task: this.taskcontact.task,
                    priority: this.taskcontact.priority,
                    duedate: this.taskcontact.duedate,
                    user_id: this.taskcontact.user.id
                })
                .then(response => {
                    this.contact.tasks.push(response.data.data);
                    this.taskcontact.title = null;
                    this.taskcontact.task = '';
                    this.taskcontact.user = { id: this.$user.id, name: this.$user.name }
                    this.taskcontact.duedate = moment().format().split("T")[0];
                });
        },
        showTask(task) {
            this.myTask = task;
            $('#taskModalDetail').modal('show');
        },
        storeTaskComment(comment) {
            axios.put('api/tasks/' + this.myTask.id + '/comment', {
                    body: comment
                })
                .then(response => {
                    this.myTask.comments.push(response.data);
                });
        },
        uploadImage(files) {
            let formData = new FormData();
            for( var i = 0; i < files.length; i++ ){
                let file = files[i];
                formData.append('files[' + i + ']', file);
            }
            axios.post('api/daily/' + this.contact.id + '/image',
                formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(response => {
                this.contact.attachs.push(response.data.data);
            })
            .catch(function() {
                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong!',
                    footer: '<a href>Why do I have this issue?</a>'
                })
            });
        },

        download($attachmen) {
            location.replace("/attachmens/" + $attachmen);
        },
        //đang check
        themvideo(video_src) {
            axios.put('api/daily/' + this.contact.id + '/videos', {
                    title: video_src,
                })
                .then(response => {
                    this.contact.videos.push(response.data);
                    video_src = '';
                });
        },
        addMedia() {
            let formData = new FormData();
            formData.append('file', this.file);
            axios.post('api/daily/' + this.contact.id + '/attachmens',
                formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(response => {
                console.log(response.data);
            });
        },
        uploadVideo(file) {
            let formData = new FormData();
            formData.append('file', file);
            axios.post('api/daily/' + this.contact.id + '/attachmens',
                    formData, {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }
                ).then(response => {
                    this.themvideo(response.data);
                })
                .catch(function() {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });
        },
    },
    created() {
        this.loadcontact();
        this.loadActivityType();
        this.loadUsers();
    }
}

</script>
<style scoped>
.image {
    float: left;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    border: 1px solid #ebebeb;
    margin: 5px;
}

</style>
