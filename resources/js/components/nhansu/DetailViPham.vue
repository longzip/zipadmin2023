<template>
    <div class="">

        <!-- ViPham detail -->
    <div  id="viphamDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
        <div class="row justify-content-center">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Vi phạm</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <th>Mã</th>
                                        <th>Tên Quy Định</th>
                                        <th>Nhân viên</th>
                                        <th>Người Tạo</th>
                                        <th>Ngày vi phạm</th>
                                        <th>Ngày tạo</th>
                                        <th>Tình trạng</th>
                                        <th>Duyệt</th>
                                        <th>Modify</th>
                                    </tr>
                                    <tr>
                                        <td><a href="#">{{details.id}}</a></td>
                                        <td><a href="#">{{details.decision_sapo}}</a></td>
                                        <td><a href="#">{{details.user_name}}</a></td>
                                        <td>{{details.creator}}</td>
                                        <td>{{details.ngayvipham}}</td>
                                        <td>{{details.created_at | formatDate}}</td>
                                        <td>
                                            <span v-if="details.danhgia == 0">Chưa đánh giá</span>
                                            <span v-if="details.danhgia == 2">Ghi nhận</span>
                                            <span v-if="details.bienban == null && details.danhgia == 1">Chờ lập biên bản</span>
                                            <span v-if="details.pheduyet == 0 && details.bienban != null">Chờ duyệt</span>
                                            <span v-if="details.bienban != null && details.danhgia == 1 && details.pheduyet == 1 && details.emailed == 0">Chờ gửi email</span>
                                            <span v-if="details.emailed == 1">Đã gửi mail</span>
                                        </td>
                                        <td>
                                            <div>
                                                 <!-- v-if="details.readonly == false" -->
                                                <a class="btn btn-primary" href="#" v-if="details.bienban != null && details.danhgia == 1 && details.pheduyet == 1 && details.emailed == 0" @click="sendmail(details)">Gửi mail</a>
                                                <a class="btn btn-primary" href="#" v-if="details.bienban == null && details.danhgia == 1" @click="isbienbanmode(details)">Biên bản</a>
                                                <a class="btn btn-primary" href="#" v-if="details.isapprove == true && details.pheduyet == 0 && details.bienban != null" @click="pheduyetDetailCenter(details)">Phê duyệt</a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
        </div>

    </div>




    <!-- ViPham detail -->
        <div  id="viphamDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <!-- class="modal  fade" -->
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Vi phạm</h5>
                </div>
                <div class="modal-body">
                    <div class="form-row"> 
                        <div class="form-row col-md-12">
                            <div class="col-md-6 float-left">
                                <label>Tên Quy định: </label>
                                <label class="detail">{{details.decision_sapo}}</label>
                            </div>
                        <div class="col-md-6 float-left">
                            <label>Quy Trình: </label>
                            <label class="detail">{{details.quytrinh.name}}</label>
                        </div>
                    </div> 
                </div>   
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                        <div class="col-md-6 float-left">
                            <label>Người vi phạm: </label>
                            <label class="detail">{{details.user_name}}</label>
                        </div>
                        <div class="col-md-6 float-left">
                            <label>Người tạo: </label>
                            <label class="detail">{{details.creator}}</label>
                        </div>
                    </div> 
                </div>
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                        <div class="col-md-6 float-left">
                           <label>Vi phạm lần thứ: </label>
                           <label class="detail">{{details.solan}}</label>
                         </div>
                        <div class="col-md-6 float-left">
                            <label>Tình trạng: </label>
                            <label class="detail">{{details.danhgia}}</label>
                        </div>
                    </div> 
                </div>
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                        <div class="col-md-6 float-left">
                            <label>Chế tài: </label>
                            <label class="detail">{{details.chetai}}</label>
                        </div>
                        <div class="col-md-6 float-left">
                            <label>Ngày áp dụng: </label>
                            <label class="detail">{{details.timeapply}}</label>
                        </div>
                    </div> 
                </div>
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                      <div class="col-md-6 float-left">
                          <label>Ngày vi phạm: </label>
                           <label class="detail">{{details.ngayvipham}}</label>
                      </div>
                      <div class="col-md-6 float-left">
                          <label>Ngày tạo: </label>
                           <label class="detail">{{details.created_at | formatDate}}</label>
                      </div>
                    </div> 
                </div>
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                      <div class="col-md-6 float-left">
                          <label>Tên vi phạm: </label>
                           <label class="detail">{{details.tenvipham}}</label>
                      </div>
                      <div class="col-md-6 float-left">
                          <label>Tiền phạt: </label>
                           <label class="detail">{{details.tienphat}}</label>
                      </div>
                    </div> 
                </div>
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                        <div class="col-md-12 float-left">
                            <label>Tường trình: </label>
                            <label class="detail" style="white-space: pre-line;">{{details.tuongtrinh}}</label>
                        </div>
                    </div> 
                </div>
                <div class="form-row">
                    <a v-if="details.images != null || details.videos != null" href="#" @click="fileDetail()">Xem file chứng minh</a>
                </div>
                <div id="fileDetail">
                    <div class="form-row"> 
                        <div class="form-row col-md-12">
                            <label>Ảnh: </label>
                            <br>
                            <div v-for="image in details.images" :key="details.id">
                                <div class="col-md-6">
                                    <img v-bind:src="'/uploads/vipham/' + image" style="width:530px;height:400px;">
                                </div>
                            </div>
                        </div> 
                      </div>  
                      <div class="form-row">
                        <div class="form-row col-md-12">
                            <label>Link Video: </label>
                            <br>
                            <div v-for="video in details.videos" :key="details.id">
                                <video width="530" height="400" controls>
                                  <source v-bind:src="video" type="video/mp4">
                                  Your browser does not support the video tag.
                                </video>
                            </div>
                        </div> 
                      </div>
                </div>
                    <div class="modal-footer">
                        <router-link :to="{ name: 'previewbienban', params: { vipham_id: details.id }}" data-dismiss="modal" v-if="bienbanmode == true" class="btn btn-primary btn-block">Tạo biên bản</router-link>
                    </div>
                </div>
                </div>
            </div>
        </div>

        <!-- Phe duyet -->
        <div  class="modal  fade" id="pheduyetDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Phê duyệt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                      <div class="form-row"> 
                        <div class="form-row col-md-12">
                            <a href="#" v-bind:href="'/downloadPdf/' + 3">Download biên bản</a>  
                            <!-- <a href="#" @click="downloadbienban(vipham)">Download biên bản</a>                         -->
                        </div> 
                      </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary pheduyet" href="#"  data-dismiss="modal">Duyệt</a> 
                        <a class="btn btn-danger pheduyet" href="#"  data-dismiss="modal">Không duyệt</a>  
                    </div>
                </div>
                </div>
            </div>
    </div>
</div>

</template>

    <style type="text/css" lang="scss" scoped>
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
            opens: "center",
            editmode: false,
            bienbanmode: false,
            viphams: {},
            roles: [],
            users: [],
            user: [],
            details:[],
            nvpheduyets: [],
            decisions: [],
            decision: [],
            quytrinh: [],
            vipham: [],sale_ids: [],
            statuss: '',
            p: '',
            images: '',
            counter: 0,
            temp: '',
            videolinks: [
                {
                    link: ''
                }
            ],
            form: new Form({
                id: '',
                decision: '',
                user: '',
                role: '',
                ngayvipham: '',
                danhgia: '',
                lapbienban: '',
                tuongtrinh : '',
                files: [],
                linkvideos: [],
                solan: '',
                nvpheduyet: [],
                timeapply: '',
                tienphat: '',
                tenvipham: '',
            }),
        }
    },
    methods: {

        downloadbienban(vipham) {
            console.log(vipham);
            axios.get('api/downloadPdf/' + vipham.bienban);
        },
        pheduyet(vipham) {
            this.vipham = vipham;
            $('#pheduyetDetailCenter').modal({backdrop: 'static', keyboard: false});
        },
        duyet(vipham) {
            axios.get('api/duyet/' + vipham.id).then(() => {
                this.loadViPhamd();
            });
        },
        khongduyet(vipham) {
            axios.get('api/khongduyet/' + vipham.id).then(() => {
                this.loadViPhamd();
            });
        },updateValues(values) {
            this.$parent.startDate = values.startDate.toISOString().slice(0, 10)
            // this.$parent.startDate = moment(this.$parent.startDate).add(1, 'day').format().slice(0,10);
            this.$parent.endDate = values.endDate.toISOString().slice(0, 10)
            // this.$parent.endDate = moment(this.$parent.endDate).endOf('week').format().slice(0,10);
            // this.loadViPham();
            // this.addWeek();
        },
        loadViPhamd() {
            axios.get('api/detailViPham?id='+ this.$route.query.id)
            .then(response => {
                    this.details = response.data.data[0];
                    this.loading = false;
                })
        },
        // fileDetailCenter(vipham) {
        //     this.vipham = vipham;
        //     $('#viphamDetailCenter').modal('hide');
        //     $('#fileDetailCenter').modal({backdrop: 'static', keyboard: false});
        // },
        // fileDetail() {
        //      document.getElementById('fileDetail').style.display = "flex";
        // },

        sendmail(details) {
            axios.get('/api/sendEmail/' + details.id).then(() => {
                this.loadViPhamd();
            });
        },
        editModal(vipham) {
            console.log(vipham);
            this.editmode = true;
            this.form.reset();
            $('#createModalCenter').modal({backdrop: 'static', keyboard: false});
            this.form.fill(vipham);
        },
        viphamDetailCenter(vipham) {
            this.decision = vipham.decision;
            this.quytrinh = vipham.quytrinh;
            this.vipham = vipham;
            // $('#viphamDetailCenter').modal({backdrop: 'static', keyboard: false});
        },
        isbienbanmode(details) {
            this.bienbanmode = true;
            this.viphamDetailCenter(details);
        },
        decisionDetailCenter(vipham) {
            this.decision = vipham.decision;
            this.quytrinh = vipham.quytrinh;
            this.vipham = vipham;
            $('#decisionDetailCenter').modal({backdrop: 'static', keyboard: false});
        },
        userDetailCenter(vipham) {
            this.user = vipham.user;
            $('#userDetailCenter').modal({backdrop: 'static', keyboard: false});
        },
    },
    created() {
        this.loadViPhamd();
        axios.get('/api/getAllUsers').then(({ data }) => (this.users = data));
        axios.get('/api/getAllUsers').then(({ data }) => (this.nvpheduyets = data));
        axios.get('api/getQuyDinh').then(({ data }) => (this.decisions = data));
        axios.get("/api/getallobjectroles").then(({ data }) => (this.roles = data));

    }
}

</script>
