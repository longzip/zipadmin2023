<template>
    <div class="">
<!-- detail nghỉ phép thông báo -->
        <div class="row justify-content-center">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Nghỉ phép</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <!-- <th>id</th> -->
                                        <th>Người tạo</th>
                                        <th>Phòng</th>
                                        <th>Người duyệt</th>
                                        <th>Thời gian</th>
                                        <th>Lý do</th>
                                        <th>Bàn giao</th>
                                        <!-- <th>tên</th> -->
                                        <!-- <th>SDT</th> -->
                                        <th>Bắt đầu</th>
                                        <th>Kết thúc</th>
                                        <th>Chế độ</th>
                                        <th>Tình trạng</th>
                                        <th>Xem đơn</th>
                                        <th>Modify</th>
                                    </tr>
                                        <tr>
                                            <!-- <td>{{valu.id}}</td> -->
                                            <td v-model="details.id">{{tennhanvien.name}}</td>
                                            <td>{{tenbophan.name}}</td>
                                            <td>
                                                <span v-if="details.nvpheduyet != null">{{pheduyet.name}}</span>
                                                <span v-if="details.nvpheduyet == null">BP Nhân sự</span>
                                            </td>
                                            <td>{{details.songaynghi}}</td>
                                            <td>{{details.lydo}}</td>
                                            <td>{{details.cvbangiao}} <br>
                                                <span style="color:#190707;font-weight:600;" v-if="details.nvbangiao != null">
                                                người nhận bàn giao:{{bangiao.name}}
                                                </span>
                                                <span style="color:#190707;font-weight:600;" v-if="details.nvbangiao == null">
                                                người nhận bàn giao: Không
                                                </span>
                                            </td>
                                            <!-- <td>{{details.phone}}</td> chu thich -->
                                            <td>{{details.date | formatDate  }}</td>
                                            <td v-if="details.dates != null">{{details.dates | formatDate  }}</td>
                                            <td v-if="details.dates == null">không thời hạn</td>
                                            <td>
                                                <span v-if="details.loainghi == 1">Nghỉ không lương</span>
                                                <span v-if="details.loainghi == 2">Nghỉ chế độ</span>
                                                <span v-if="details.loainghi == 3">Nghỉ việc</span>
                                            </td>
                                            <td><a v-if="details.tinh_trang == 1  || details.tinh_trang == 5 " class="btn btn-warning" >
                                                    đã duyệt
                                                </a>
                                                <a v-if="details.tinh_trang == 0 || details.tinh_trang == 2 || details.tinh_trang == 4" href="#"><router-link :to="{ name: 'previewnghiphep', params: { valu_id: details.id }}" data-dismiss="modal"  class="btn btn-primary btn-block">Xem</router-link></a>
                                                <a v-if="details.tinh_trang == 3" href="#"><router-link :to="{ name: 'previewnghiphep', params: { valu_id: details.id }}" data-dismiss="modal"  class="btn btn-primary btn-block">Không duyệt</router-link></a>
                                            </td>
                                            <td>
                                                <span v-if="details.bienban == null ">
                                                <a href="#"><i class="fa fa-eye-slash" style="font-size:30px;color:#DF3A01"></i></a>
                                                </span>
                                                <span v-if="details.bienban != null ">
                                                    <a target='_blank' v-bind:href="'uploads/nghiphep/'+details.bienban"><i class="fa fa-eye" style="font-size:30px;color:green"></i></a>
                                                </span>
                                            </td>
                                            <td>
                                                 <span v-if="details.tinh_trang == 0">
                                                 </span>
                                                 <span v-if="details.tinh_trang == 1">
                                                <a href="#" @click="deleteModal(details)">
                                                </a>
                                                 </span>
                                                 <span v-if="details.tinh_trang == 3">
                                                     <a href="#" @click="editModal(details)" >
                                                    <i class="fa fa-edit blue"></i>
                                                </a>
                                                <a href="#" @click="deleteModal(details)">
                                                    <i class="fa fa-trash red"></i>
                                                </a>
                                                 </span>
                                            </td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
        </div>

        <!-- comment,duyet -->
        <div id="comentModal" tabindex="-1" role="dialog" aria-labelledby="taskModalDetailTitle">
            <div role="document">
                    <form>
                        <comments :comments="details.comments" @create-comment="storeNghiphepComment"></comments>
                    </form>
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
        export default{
            data(){
                return {
                    editmode: false,
                    roles: [],
                    opens: "center",
                    users: [],
                    nghipheps:{},
                    bienban:[],
                    nvpheduyet: [],
                    nvbangiao:[],
                    pheduyet: [],
                    decisions: [],
                    decision: [],
                    quytrinh: [],
                    statuss: '',
                    tinhtrang: [],
                    details:[],
                    tennhanvien:[],
                    tenbophan:[],
                    user:[],
                    role:[],
                    bangiao:[],
                    nghiphep:[],
                    comments: {},
                    comment: {
                      body: ''
                    },
                    myTask: {},
                    tasks: {},
                    form: new Form({
                        id: '',
                        user: '',
                        role: '',
                        nvpheduyet:'',
                        nvbangiao:'',
                        songaynghi: '',
                        lydo: '',
                        cvbangiao: '',
                        date: '',
                        status: '',
                        pheduyet: '',
                        phone: '',
                        date : '',
                        dates: '',
                        loainghi: '',
                    }),
                }
            },
            methods:{
                likeModal(details) {
                    this.details = details;
                    this.bienban = details.bien_ban;
                    this.pheduyet = details.nvpheduyet;
                    // this.nhansu = nghiphep.nhan_su;
                    // console.log(nhansu);
                    this.tinhtrang =  details.tinh_trang;
                    $('#comentModal').modal({backdrop: 'static', keyboard: false});
                },
                // nhansuduyet(nghiphep){
                //     axios.get('/api/nhansuduyets/' + nghiphep.id).then(() => {
                //             $('#comentModal').modal('hide');
                //         this.loadnghiphep();
                //     });
                // },
                duyet(details) {
                        axios.get('/api/postduyet/' + details.id).then(() => {
                            $('#comentModal').modal('hide');
                        this.loadnghiphep();
                    });
                },
                duyetlanmot(nghiphep){
                    location.replace('/exportpdfnp/'+nghiphep.id);
                },
                khongduyet(nghiphep) {
                        axios.get('/api/khongduyets/' + nghiphep.id).then(() => {
                            $('#comentModal').modal('hide');
                        this.loadnghiphep();
                    });
                },
                storeNghiphepComment(comment) {
                    axios.put('api/nghiphepcomment/'+ this.details.id, {
                        body: comment
                    })
                    .then(response => {
                        this.details.comments.push(response.data);
                    });
                },
                editModal(nghiphep) {
                    this.editmode = true;
                    this.form.reset();
                    $('#createModalCenter').modal('show');
                    this.form.fill(nghiphep);
                },
                updatenghiphep() {

                    this.$Progress.start();
                    this.form.put('api/nghiphep/' + this.form.id)
                        .then(() => {
                            // success
                    $('#createModalCenter').modal('hide');
                        swal.fire(
                             'Updated!',
                             'Information has been updated.',
                             'success'
                            );
                            this.loadnghiphep();
                            this.$Progress.finish();
                            Fire.$emit('AfterCreate');
                        })
                        .catch(() => {
                            this.$Progress.fail();
                        });
                },
                updateValues(values) {
                    this.startDate = values.startDate.toISOString().slice(0, 10);
                    this.startDate = moment(this.startDate).add(1, 'day').format().slice(0,10);
                    this.endDate = values.endDate.toISOString().slice(0, 10);
                    this.endDate = moment(this.endDate).endOf('week').format().slice(0,10);
                    this.loadnghiphep();
                },
                hideFileDetail() {
                document.getElementById('fileDetail').style.display = "none";
                },
                createnghiphep() {
                    this.$Progress.start();
                    this.form.post('/api/nghiphep')
                    .then(() => {
                        $('#createModalCenter').modal('hide');
                        this.loadnghiphep();
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                        });
                },
                nghiphepDetailCenter(nghiphep) {
                this.nghiphep = nghiphep;
                this.user = nghiphep.user;
                this.role = nghiphep.role;
                $('#nghiphepDetailCenter').modal({backdrop: 'static', keyboard: false});
                 },
                isbienbanmode(nghiphep) {
                this.nghiphepDetailCenter(nghiphep);
                console.log(nghiphep);
                },
                reSelectnhanvien() {
                this.$parent.Selectednhanvien = [];
                this.loadnghiphep();
                },
                timTheonhanvien() {
                this.loadnghiphep();
                 },
                loadnghiphep() {
                    axios.get('api/detailNghiPhep?id='+ this.$route.query.id)
                    .then(response => {
                    this.details = response.data.data[0];
                    this.bangiao = this.details.nvbangiao;
                    this.pheduyet = this.details.nvpheduyet;
                    this.tennhanvien = this.details.user;
                    this.tenbophan = this.details.role;
                    // console.log(this.details.user.name);
                    this.loading = false;
                })
                },
                deleteModal(nghiphep) {
                    console.log(nghiphep);
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
                        this.form.delete('api/nghiphep/' + nghiphep.id).then(() => {
                            this.loadnghiphep();
                            this.$Progress.finish();
                        }).catch();
                    }
                })
                }
            },
            created() {
                this.loadnghiphep();
                Fire.$on('AfterCreate', () => {
                    this.loadnghiphep();
                });
                Fire.$on('searching', () => {
                    this.loadnghiphep();
                });
                axios.get('/api/phe-duyet').then(({ data }) => (this.nvpheduyet = data));
                axios.get('/api/getAllUsers').then(({ data }) => (this.nvbangiao = data));
                axios.get('/api/getAllUsers').then(({ data }) => (this.users = data));
                axios.get("/api/getallobjectroles").then(({ data }) => (this.roles = data));

            }
        }
    </script>
