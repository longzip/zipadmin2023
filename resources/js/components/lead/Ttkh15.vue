<template>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <a class="" href="#" @click="editlead()">
                            <h3 class="profile-username text-center">{{ lead.first_name}} {{ lead.last_name}}</h3>
                        </a>
                        <p class="text-muted text-center">{{ lead.company}}</p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>{{ lead.address}}</b> <a class="float-right">{{ lead.city}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Phone</b> <a class="float-right" href="#" @click="callKH(lead)">{{lead.phone}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right">{{ lead.email}}</a>
                            </li>
                        </ul>
                        <a class="" href="#" @click="createKHTN()" v-if="lead.idContact == ''">
                            <h3 class="btn btn-primary btn-block">Chuyển thành KHTN</h3>
                        </a>
                        <h3 class="btn btn-primary btn-block" v-if="lead.idContact != ''">Đã thành KHTN</h3>
                        <!-- <router-link :to="{ name: 'createContact', params: { lead_id: lead.id }}" class="btn btn-primary btn-block">Chuyển thành KHTN</router-link> -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
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
                                <strong><i class="fa fa-book mr-1"></i> Đặc điểm</strong>
                                <blockquote class="blockquote">
                                    <p class="mb-0">{{lead.note}}</p>
                                    <footer class="blockquote-footer">Vào showroom {{getName(lead.coscenters)}} từ {{lead.start_time}} đến {{lead.end_time}} <cite title="Ngày">{{lead.start_date}}</cite> bởi {{lead.username}} - {{lead.user_id}} <cite v-if="lead.type == 1"> - Khách Data</cite></footer>
                                </blockquote>
                                <p class="lead">Đặc điểm</p>
                                <p class="text-muted">
                                    <span class="badge badge-primary" v-for="kh15 in lead.kh15s">{{kh15.name}}: {{kh15.description}}</span>
                                </p>
                                <p class="lead">Lo Lắng</p>
                                <p>
                                    <span class="badge badge-warning" v-for="lost in lead.losts">{{lost.name}}: {{lost.description}}</span>
                                </p>
                                <hr>
                                <strong><i class="fa fa-map-marker mr-1"></i> Địa chỉ</strong>
                                <p class="text-muted">{{ lead.address}}</p>
                                <hr>
                                <strong><i class="fa fa-pencil mr-1"></i> Sản phẩm quan tâm</strong>
                                <p class="text-muted">
                                    <span class="badge badge-success" v-for="product in lead.products">{{product.name}}</span>
                                </p>
                                <hr>
                                <strong><i class="fa fa-file-text-o mr-1"></i> Notes</strong>
                                <p class="text-muted">{{lead.description}}.</p>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <contact-tasks :tasks="lead.tasks" @show-task="showTask"></contact-tasks>
                        <comments :comments="lead.comments" @create-comment="storeLeadComment"></comments>
                    </tab>
                    <tab name="Đặc điểm" v-bind:suffix=" '(' + lead.kh15s.length + ')'">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Các đặc điểm</div>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li v-for="kh15 in lead.kh15s" :key="kh15.id">
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
                    <tab name="Lo lắng" v-bind:suffix=" '(' + lead.losts.length + ')'">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Các lo lắng</div>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li v-for="lost in lead.losts" :key="lost.id"><strong><i class="fas fa-check"></i> {{lost.name}} <a href="#" @click="deleteLost(lost)">
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
                    <tab name="Tư vấn">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Bảng kế hoạch</div>
                            </div>
                            <div class="card-body">
                                <ul class="timeline">
                                    <li v-for="task in lead.tasks" :key="task.id">
                                        <i>{{task.priority}}</i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fa fa-clock-o"></i> {{task.created_at | myDate}}</span>
                                            <h3 class="timeline-header">{{task.title}} </h3>
                                            <div class="timeline-body">
                                                Gán cho: {{task.user_name}}<br>
                                                Đến: {{task.duedate | myDate}}<br>
                                                Trạng thái: {{task.completed | completed}}<br>
                                                {{task.task}}
                                            </div>
                                            <div class="timeline-footer">
                                                <a v-show="!task.completed" @click="taskCompleted(task)" href="#" class="btn btn-primary btn-sm">Hoàn thành</a>
                                                <a v-show="!task.completed" href="#" @click="deleteTask(task)" class="btn btn-danger btn-sm">Hủy</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <a href="#" class="btn btn-primary" @click="showTaskModal">
                                    Tạo kế hoạch
                                </a>
                            </div>
                        </div>
                    </tab>
                    <tab name="Bình luận">
                        <comments :comments="lead.comments" @create-comment="storeLeadComment"></comments>
                    </tab>
                    <tab name="Tài liệu"  v-bind:suffix=" '(' + lead.attachs.length + ')'">
                        <attachment :items="lead.attachs" @upload-image="uploadImage" :upload="true"></attachment>
                    </tab>
                    <tab name="Videos" v-bind:suffix=" '(' + lead.videos.length + ')'">
                        <contact-videos :videos="lead.videos" @create-video="themvideo" @upload-video="uploadVideo"></contact-videos>
                    </tab>
                    <tab name="Báo giá" v-bind:suffix=" '(' + lead.quotes.length + ')'">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Danh sách báo giá</div>
                                <div class="card-tools">
                                    <router-link :to="{ name: 'taobaogialead', params: { leadId: this.lead.id }}" class="btn btn-primary"><i class="fas fa-plus-square"></i> Tạo báo giá</router-link>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <quote-list :quotes='lead.quotes'></quote-list>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <!-- /.card-footer -->
                            <div class="card-footer">
                            </div>
                            <!-- /.card-footer -->
                        </div>
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
        <!-- Modal Task-->
        <div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tạo kế hoạch mới</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Bước</label>
                            <select v-model="tasklead.priority" class="form-control">
                                <option>Bước 1</option>
                                <option>Bước 2</option>
                                <option>Bước 3</option>
                                <option>Bước 4</option>
                                <option>Bước 5</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Chọn loại hoạt động</label>
                            <multiselect v-model="tasklead.title" :options="activityTypes" label="name" track-by="name" placeholder="Chọn hoạt động"></multiselect>
                        </div>
                        <div class="form-group">
                            <label>Nội dung kế hoạch</label>
                            <textarea v-model="tasklead.task" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                        </div>
                        <div class="form-group">
                            <label>Thực hiện</label>
                            <multiselect v-model="tasklead.user" :options="users" label="name" track-by="id" placeholder="Chọn người thực hiện"></multiselect>
                        </div>
                        <div class="form-group">
                            <label>Ngày hoàn thành</label>
                            <input v-model="tasklead.duedate" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="button" @click.prevent="taokehoach" class="btn btn-primary">Tạo kế hoạch</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createKHTN" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nhập Điều Kiện Chuyển KHTN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Đối tượng sử dụng</label>
                            <input v-model="create.doituong" type="text" class="form-control" placeholder="Không Được Bỏ Trống">
                        </div>
                        <div class="form-group">
                            <label>Mặt Bằng Nhà</label>
                            <input v-model="create.matbang" type="text" class="form-control" placeholder="Không Được Bỏ Trống">
                        </div>
                        <div class="form-group">
                            <label>Ngân Sách Đầu Tư (đv: Triệu)</label>
                            <input v-model="create.ngansach" type="number" class="form-control" placeholder="Không Được Bỏ Trống">
                        </div>
                        <div class="form-group">
                            <label>Điểm rơi DS</label>
                            <div class="input-group">
                                <multiselect v-model="create.diemroi" :options="diemroi" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn điểm rơi" label="t" :preselect-first="true">
                            </multiselect>
                            </div>
                        </div>
                        <div class="row col-md-12">
                            <label>Ảnh:</label>
                            <input type="file" id="files" ref="files" v-on:change="handleFileUpload()" multiple />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="button" @click.prevent="taodieukien" class="btn btn-primary">Tạo Điều Kiện</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="showData" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập Nhật Trạng Thái</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="UpdateTV()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="radio" id="chua-nghe-may" value="1" v-model="call">
                                <label for="nghe-may">Nghe Máy</label><br>
                                <input type="radio" id="quan-tam" value="2" v-model="call">
                                <label for="chua-nghe-may">Chưa Nghe Máy</label><br>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
    </div>
</template> 
<script>
import Attachment from '../elements/Attachment.vue';
import QuoteList from '../elements/QuoteList.vue';
import ContactVideos from '../contact/ContactVideos.vue';
import ContactTasks from '../elements/Task.vue';
export default {
    props: ['id'],
    components: { ContactTasks, Attachment, ContactVideos, QuoteList },
    data() {
        return {
            attachmens: [],  
            call: '',
            diemroi: [],
            create: {
                id: '',
                doituong: '',
                matbang: '',
                kichthuoc: '',
                ngansach: '',                
                diemroi: '',
            },
            lead: {
                id: '',
                title: '',
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                company: '',
                address: '',
                city: '',
                description: '',
                start_date: '',
                start_time: '',
                end_time: ''
            },
            kh15s: {},
            myTask: {},
            tasklead: {
                contact_id: '',
                user: '',
                title: '',
                task: '',
                priority: '',
                duedate: '',
            },
            kh15: {
                name: '',
                description: ''
            },
            file: '',
            //picklist
            users: [],
            activityTypes: [],
            coscenters: [],
            kh15Types: [
                'Nhà chung cư',
                'Nhà riêng',
                'Không gian',
                'Bảo hành',
                'Vận chuyển',
            ],
            lostTypes: [
                'Giá',
                'Kích thước',
                'Không gian',
                'Bảo hành',
                'Vận chuyển',
            ],
            lost: {
                name: '',
                description: ''
            },
            losts: {},

        }
    },
    methods: {
        UpdateTV(){
            console.log(this.call);
            if (this.call == 1) {
                var titele = 'Gọi Điện Nghe';
            }else{
                var titele = 'Gọi Điện Không Trả Lời';
            }
            axios.put('api/leads/tasks/' + this.lead.id, {
                    title: titele,
                    task: '',
                    priority: 'Bước 0',
                    duedate: moment().format().slice(0, 10),
                    user_id: this.$user.id,               
                })
            .then(response => {
                $('#showData').modal('hide');
                this.loadLead();
            });

            axios.get('/api/updateProgramLead?id=' + this.$route.query.id)
                .then(response => {
                });
        },
        callKH(data){
            this.data = data;
            window.location = 'tel:'+data.phone;
            $('#showData').modal('show');
        },
        handleFileUpload() {
            this.files = this.$refs.files.files;
        },
        themvideo(video_src) {
            axios.put('api/leads/' + this.lead.id + '/videos', {
                    title: video_src,
                })
                .then(response => {
                    this.lead.videos.push(response.data);
                    video_src = '';
                });
        },
        taodieukien(){
            let formData = new FormData();
            if (this.files) {
                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    formData.append('file[' + i + ']', file);
                }
            }

            formData.append('doituong', this.create.doituong);
            formData.append('matbang', this.create.matbang);
            formData.append('diemroi', this.create.diemroi.id_table);
            formData.append('t', this.create.diemroi.t);
            formData.append('ngansach', this.create.ngansach);
            formData.append('phone', this.lead.phone);
            axios.post('/api/adddk',formData, {
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
                $('#createKHTN').modal('hide');
                this.$router.push({  name: 'createContact', params: { lead_id: this.lead.id } })
            })
            .catch(error => {
                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'không được bỏ trống 4 ô đầu',
                    footer: '<a href>Why do I have this issue?</a>'
                })
            });

        },
        uploadVideo(file) {
            let formData = new FormData();
            formData.append('file', file);
            axios.post('api/leads/' + this.lead.id + '/attachmens',
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
        uploadImage(files) {
            // console.log(files[1]);
            let formData = new FormData();
            // formData.append('file', file);
            for( var i = 0; i < files.length; i++ ){
                let file = files[i];
                formData.append('files[' + i + ']', file);
            }
            axios.post('api/leads/' + this.lead.id + '/image',
                    formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(response => {
                    this.loadLead();
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
        addMedia() {
            let formData = new FormData();
            formData.append('file', this.file);
            axios.post('api/leads/' + this.lead.id + '/attachmens',
                formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(response => {
                console.log(response.data);
            });
        },
        addKh15() {
            axios.put('api/leads/' + this.lead.id + '/kh15s', {
                    name: this.kh15.name,
                    description: this.kh15.description
                })
                .then(response => {
                    this.lostTypes.name = '';
                    this.lead.kh15s.push(response.data);
                });
        },
        deleteKh15(kh15) {
            axios.delete('api/kh15s/' + kh15.id)
                .then(() => this.lead.kh15s.splice(this.lead.kh15s.indexOf(kh15), 1))
        },
        addLost() {
            axios.put('api/leads/' + this.lead.id + '/losts', {
                    name: this.lost.name,
                    description: this.lost.description
                })
                .then(response => {
                    this.lostTypes.name = '';
                    this.lead.losts.push(response.data);
                });
        },
        deleteLost(lost) {
            axios.delete('api/losts/' + lost.id)
                .then(() => this.lead.losts.splice(this.lead.losts.indexOf(lost), 1))
        },
        loadLead() {
            axios.get('/api/leads/' + this.$route.query.id)
                .then(response => {
                    this.lead = response.data.data;
                    this.loadPicklist();
                })
                .catch(response => {
                    location.replace('/kh15');
                });
        },
        getName(values) {
            if (!values) return ''
            return values.map((item, index, values) => {
                return item.name
            })
        },

        setContact(id) {
            axios.put('/contacts/session/' + id)
                .then(response => {
                    location.replace("/thong-tin-khach-tiem-nang");
                });
        },
        storeLeadComment(comment) {
            axios.put('api/leads/comments/' + this.lead.id, {
                    'body': comment
                })
                .then(response => {
                    this.lead.comments.push(response.data);
                })
        },

        taokehoach() {
            axios.put('api/leads/tasks/' + this.lead.id, {
                    title: this.tasklead.title.name,
                    task: this.tasklead.task,
                    priority: this.tasklead.priority,
                    duedate: this.tasklead.duedate,
                    user_id: this.tasklead.user.id
                })
                .then(response => {
                    this.tasklead = response.data;
                    this.lead.tasks.push(response.data.data);
                    $('#taskModal').modal('hide');
                    this.tasklead.title = null;
                });
        },
        loadPicklist() {
            axios.get('api/picklists/contact-picklists')
                .then(response => {
                    this.users = response.data.users;
                    this.activityTypes = response.data.activity_types;
                    this.coscenters = response.data.coscenters;
                });
        },        
        taskCompleted(task) {
            axios.post('/api/tasks/' + task.id + '/completed')
                .then(response => {
                    task.completed = true;
                });
        },
        deleteTask(task) {
            axios.delete('/api/tasks/' + task.id)
                .then(response => {
                    this.lead.tasks.splice(this.lead.tasks.indexOf(task), 1);
                });
        },
        showTask(task) {
            this.myTask = task;
            $('#taskModalDetail').modal('show');
        },

        createKHTN() {
            $('#createKHTN').modal('show');
        },

        storeTaskComment(comment) {
            axios.put('api/tasks/' + this.myTask.id + '/comment', {
                    body: comment
                })
                .then(response => {
                    this.myTask.comments.push(response.data);
                });
        },
        showTaskModal() {
            this.tasklead.duedate = moment().format().slice(0, 10);
            this.tasklead.user = {
                id: this.$user.id,
                name: this.$user.name
            };
            $('#taskModal').modal('show');
        }
    },
    created() {
        this.loadLead();
        axios.get('api/diem-roi')
        .then(response => {
            this.diemroi = response.data;
        });
    }
}

</script>
