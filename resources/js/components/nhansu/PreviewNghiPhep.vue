<template>
    <div>
        <div class="container"  style="margin-top: 20px; margin-left: 10px">
                    <div class="row">
                        <div style="width: 30%; float: left;">
                            <p style="text-align: center;font-weight: 1000;font-size: 20px; margin-bottom: 0.5rem;">CÔNG TY TNHH</p>
                            <p style="text-align: center;font-weight: 1000;font-size: 20px; margin-bottom: 0.5rem; margin-top:10px">NỘI THẤT ZIP</p> 
                            
                         </div>
                        <div style="width: 70%; float: left;">
                                <p style="text-align: center;font-weight: 900;font-size: 20px; margin-bottom: 5px;"> CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM </p>
                                <p style="text-align: center;font-weight: 900;font-size: 20px; margin-top: 5px"> Độc Lập - Tự Do - Hạnh Phúc</p>
                                <p style="text-align: right;font-size: 18px;font-style: italic;"> Hà Nội,ngày {{new Date().getDate()}} tháng {{new Date().getMonth() + 1}} năm {{new Date().getFullYear()}}</p>
                         </div>
                         
                  </div>
                     <div style="margin-top: 0px;">
                            <p style="text-align: center;font-weight: 1000;font-size: 20px; clear: both;margin-bottom: 5px;" v-if="nghiphep.loainghi != 3"> ĐƠN XIN NGHỈ PHÉP </p>
                            <p style="text-align: center;font-weight: 1000;font-size: 20px; clear: both;margin-bottom: 5px;" v-if="nghiphep.loainghi == 3"> ĐƠN XIN NGHỈ VIỆC </p>
                            <p style="text-align: center;font-weight: 900;font-size: 18px;margin-top: 5px;"> Kính gửi:Ban Giám Đốc Công Ty TNHH Nội thất ZIP </p>
                    </div>
                    <div style="margin-left: 30px">
                            <p style="font-size: 14px">Tên tôi là: {{user.name}}</p>
                            <p style="font-size: 14px">Vị trí: {{roles.name}}</p>
                            <p style="font-size: 14px">Số ngày xin nghỉ:  {{nghiphep.songaynghi}}  ngày</p>
                            <p style="font-size: 14px">Lý do: {{nghiphep.lydo}}</p>
                            <p style="font-size: 14px">Loại nghỉ:
                          <!--   <input type="radio" name="loainghi" value="0" v-if="nghiphep.loainghi == 0">
                            <input type="radio" name="loainghi" value="1" v-if="nghiphep.loainghi == 1">
                            <input type="radio" name="loainghi" value="2" v-if="nghiphep.loainghi == 2">Nghỉ chế độ -->
                            <!-- <input type="radio" id="one" :disabled = "nghiphep.loainghi != 0 " value="One" v-bind:checked="nghiphep.loainghi == 0 ">
                            <label for="one">Nghỉ phép</label> -->
                            <input type="radio" id="two" :disabled = "nghiphep.loainghi != 1 " value="Two" v-bind:checked=" nghiphep.loainghi == 1 ">
                            <label for="two">Nghỉ không lương</label>
                            <input type="radio" id="three" :disabled = "nghiphep.loainghi != 2 " value="three" v-bind:checked="nghiphep.loainghi == 2 ">
                            <label for="three">Nghỉ chế độ</label>
                            <input type="radio" id="three" :disabled = "nghiphep.loainghi != 3 " value="three" v-bind:checked="nghiphep.loainghi == 3 ">
                            <label for="three">Nghỉ việc</label>

                            </p>
                            <p style="font-size: 14px">Thời gian bắt đầu nghỉ: {{nghiphep.date | formatDate }}   <span v-if="nghiphep.dates != null"> đến ngày: {{nghiphep.dates | formatDate }}</span></p>
                            <p style="font-size: 14px">Công việc bàn giao: {{nghiphep.cvbangiao}}</p>
                            <p style="font-size: 14px">Điện thoại liên hệ khi cần: {{nghiphep.phone}}</p>
                            <p style="font-size: 14px" v-if="nghiphep.dates != null">Tôi xin hứa sẽ có mặt tại công ty đúng hẹn,nếu sai tôi xin chịu hoàn toàn trách nhiệm.</p>
                            <p style="font-size: 14px">Tôi xin chân thành cảm ơn!</p>

                    </div>
                      <div class="row" style="width: 100%;">
                       <div class="col-md-3" >
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> Phòng HC-NS </p>
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> (Ký,họ tên) </p>
                       </div>
                       <div class="col-md-3" >
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> Trưởng bộ phận </p>
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> (Ký,họ tên) </p>
                       </div>
                       <div class="col-md-3" >
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> Người nhận BG </p>
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> (Ký,họ tên) </p>
                       </div>
                       <div class="col-md-3" >
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> Người làm đơn </p>
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> (Ký,họ tên) </p>
                       </div>
                    </div>
                </div>
        <span v-if="bangiao != null && bangiao.id == details.nhanvien && details.bienban == null  && details.check == 0 && details.tinh_trang == 0">
            <a  @click="xacnhanbg(details)" class="btn btn-success">Xác nhận bàn giao</a>
            <a @click="khongduyet(details)" class="btn btn-primary">Không xác nhận</a>
        </span>
        <span v-if="pheduyet != null && pheduyet.id == details.nhanvien && details.bienban == null  && details.check == 0 && (details.tinh_trang == 2 || details.tinh_trang == 4 )">
            <a  @click="duyetlanmot(details)" class="btn btn-success">trưởng phòng Duyệt</a>
            <a @click="khongduyet(details)" class="btn btn-primary">không duyệt</a>
        </span>
        <span v-if="details.bienban != null && details.check == 1 && pheduyet != null && (details.tinh_trang == 4 || details.tinh_trang == 0 || details.tinh_trang == 2 )">
            <a  @click="duyet(details)" class="btn btn-success">Nhân Sự Duyệt</a>
            <a @click="khongduyet(details)" class="btn btn-primary">không duyệt</a>
        </span>
        <span v-if="details.bienban == null && pheduyet == null && details.check == 1 && details.tinh_trang != 3 && (bangiao == null || details.tinh_trang == 4)"  >
            <a  @click="duyetTP(details)" class="btn btn-success">Nhân Sự Duyệt TP</a>
            <a @click="khongduyet(details)" class="btn btn-primary">không duyệt</a>
        </span>
        <span v-if="details.bienban != null && details.giamdoc == 1 && details.tinh_trang != 5 && details.tinh_trang != 3 && pheduyet == null">
            <a  @click="duyetgdnp(details)" class="btn btn-success">Giám đốc Duyệt</a>
            <a @click="khongduyet(details)" class="btn btn-primary">không duyệt</a>
        </span>
        <!-- <a href="#" @click="show(vipham)">show</a> -->

        <div id="comentModal" tabindex="-1" role="dialog" aria-labelledby="taskModalDetailTitle">
            <div role="document">
                    <form>
                        <comments :comments="details.comments" @create-comment="storeNghiphepComment"></comments>
                    </form>
            </div>
        </div>
        <div class="">
            <div class="">
                <span ><a v-if="details.tinh_trang == 4"  class="btn btn-success">Đã xác nhận bàn giao</a></span>
                <span ><a v-if="details.bienban != null && pheduyet != null"  class="btn btn-success">Trưởng phòng đã duyệt</a></span>
                <span ><a v-if="details.tinh_trang == 1 || (pheduyet == null && details.bienban != null) "  class="btn btn-success">Nhân sự đã duyệt</a></span>
                <span ><a v-if="details.tinh_trang == 5"  class="btn btn-success">Giám đốc đã duyệt</a></span>
                <span ><a v-if="details.tinh_trang == 3"  class="btn btn-success">Không duyệt</a></span>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['valu_id'],
    data() {
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
                    bangiao: [],
                    decisions: [],
                    decision: [],
                    quytrinh: [],
                    statuss: '',
                    tinhtrang: [],
                    details:[],
                    user:[],
                    role:[],
                    nghiphep:[],
                    comments: {},
                    comment: {
                      body: ''
                    },
                    myTask: {},
                    tasks: {},
            nghiphep: [],
            user: [],
        }
    },
    methods: {
        show(nghiphep) {
            console.log(this.valu);
        },
        generatePDF(nghiphep) {
            // console.log(vipham.id);
            // axios.get('api/exportViPhamPdf/' + vipham.data.id)
            // .then();
            location.replace('/exportpdfnp/'+nghiphep.id);
        },
        likeModal(details) {
            this.details = details;
            this.bienban = details.bien_ban;
            this.pheduyet = details.nvpheduyet;
            // this.nhansu = nghiphep.nhan_su;
            // console.log(nhansu);
            this.tinhtrang =  details.tinh_trang;
            $('#comentModal').modal({backdrop: 'static', keyboard: false});
        },
        duyet(details) {
                axios.get('/api/postduyet/' + details.id).then(() => {
                    $('#comentModal').modal('hide');
                this.loadnghiphep();
            });
        },
        duyetgdnp(details){
            axios.get('/api/duyetgdnp/' + details.id).then(() => {
                    $('#comentModal').modal('hide');
                this.loadnghiphep();
            });
        },
        duyetlanmot(details){
            location.replace('/exportpdfnp/'+details.id);
        },
        duyetTP(details){
            location.replace('/exportpdfnptp/'+details.id);
        },
        xacnhanbg(details){
             axios.get('/api/xacnhanbg/'+details.id).then(() => {
                    $('#comentModal').modal('hide');
                this.loadnghiphep();
            });
        },
        khongduyet(details) {
                axios.get('/api/khongduyets/' + details.id).then(() => {
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
        nghiphepDetailCenter(nghiphep) {
                this.nghiphep = nghiphep;
                this.user = nghiphep.user;
                this.role = nghiphep.role;
                $('#nghiphepDetailCenter').modal({backdrop: 'static', keyboard: false});
                 },
        isbienbanmode(nghiphep) {
            this.nghiphepDetailCenter(nghiphep);
            // console.log(nghiphep);
        },
        reSelectnhanvien() {
            this.$parent.Selectednhanvien = [];
            this.loadnghiphep();
        },
        timTheonhanvien() {
            this.loadnghiphep();
         },
        loadnghiphep() {
            axios.get('api/detailNghiPhep?id='+ this.valu_id)
            .then(response => {
            this.details = response.data.data[0];
            this.bienban = this.details.bien_ban;
            this.pheduyet = this.details.nvpheduyet;
            this.bangiao = this.details.nvbangiao;

            // this.nhansu = nghiphep.nhan_su;
            // console.log(nhansu);
            this.tinhtrang =  this.details.tinh_trang;
            // console.log(this.details.user.name);
            this.loading = false;
            console.log(this.details);
            })
        },

    },
    created() {
        // console.log(this.valu_id);
        this.loadnghiphep();
        axios.get('api/nghiphep/' + this.valu_id)
            .then(res => this.nghiphep = res.data.data)
            .then(() => {
                // console.log(this.nghiphep);
                this.user = this.nghiphep.user;
                this.users = this.nghiphep.user;
                this.roles = this.nghiphep.role;
                // console.log(this.user);
            });
        // console.log(this.vipham);
    }
}

</script>
