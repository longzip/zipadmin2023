<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Chọn năm </label>
                    <select  class="form-control" v-model="search.year">
                        <option v-for="option in $parent.year" v-bind:value="option.value" :selected="option.value == search.year">
                            {{ option.nam }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chọn P </label>
                    <select  class="form-control" v-model="search.pt">
                        <option v-for="option in $parent.pt" v-bind:value="option.value" :selected="option.value == search.pt">
                            {{ option.index }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group pull-left">
                    <label>Văn Phòng</label>
                    <input class="form-control" type="checkbox" v-model="search.vp" >
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title pull-left">Bảng Lương</h3>
                <div class="card-tools">
                    <a href="#" @click="chotcong" class="btn btn-warning" v-if="sm == 1 && chot == 0">
                        Chốt Công
                    </a>
                    <a href="#" @click="chotcongvp" class="btn btn-warning" v-if="hr == 1">
                        Chốt Công
                    </a>
                    <!-- <a href="#" @click="duyetcong" class="btn btn-success" v-if="asm == 1 && asmchot == 0">
                        Duyệt
                    </a> -->
                    <a href="#" @click="dayds" class="btn btn-danger" v-if="qa == 1">
                        Đẩy DS
                    </a>
                    <a href="#" @click="chiads" class="btn btn-danger" v-if="qa == 1">
                        Chia DS
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class=" tableFixHead">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                           <!-- <th class="text-center" :rowspan="2">Bộ Phận</th> -->
                           <th class="text-center" :rowspan="2">Tên</th>
                           <th class="text-center" :rowspan="2">Công</th>
                           <th class="text-center" :rowspan="2">APP</th>
                           <th class="text-center" :rowspan="2">Lương Cứng {{tong.luongcung | currency}}</th>
                           <th class="text-center" :rowspan="2">Lương Time {{tong.luongtime | currency}}</th>
                           <th class="text-center" :rowspan="2" v-if="this.vp == 0">Doanh Số {{tong.dsrieng | currency}}</th>
                           <th class="text-center" :colspan="3" v-if="this.vp == 0">THƯỞNG INC+KPIS </th>
                           <th class="text-center" :colspan="4" v-if="this.vp == 1">Phụ Cấp</th>
                           <th class="text-center" :colspan="3">Các Khoản Giảm Trừ</th>
                           <th class="text-center" :rowspan="2">Công tác Phí {{tong.congtac | currency}}</th>
                           <th class="text-center" :rowspan="2">Lương Khác {{tong.luongkhac | currency}}</th>
                           <th class="text-center" :rowspan="2">Tổng Lương {{tong.tong | currency}}</th>
                        </tr>
                        <tr>
                           <th class="text-center"  v-if="this.vp == 0" style="top:45px;">Điểm Thưởng {{tong.point | currency}}</th>
                           <th class="text-center" style="top:45px;">KPI {{tong.kpi | currency}}</th>
                           <th class="text-center" v-if="this.vp == 0" style="top:45px;">Lương DS {{tong.saletarget | currency}}</th>
                           <th class="text-center" v-if="this.vp == 1" style="top:45px;">Điện Thoại {{tong.phone | currency}}</th>
                           <th class="text-center" v-if="this.vp == 1" style="top:45px;">Xăng Xe {{tong.xang | currency}}</th>
                           <th class="text-center" v-if="this.vp == 1" style="top:45px;">Tiền Ăn {{tong.tienan | currency}}</th>
                           <th class="text-center" style="top:45px;">Công Đoàn {{tong.congdoan | currency}}</th>
                           <th class="text-center" style="top:45px;">Phạt {{tong.loi | currency}}</th>
                           <th class="text-center" style="top:45px;">BHXH {{tong.bhxh | currency}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(d,k) in list">
                            <tr v-for="(nv,i) in d" v-bind:class="{ 'bg-secondary text-white': (k % 2 == 0) }">
                                <!-- <td :rowspan="d.length" v-if="i == 0">{{k}} 
                                    <a href="#" @click="khongduyet(k)" v-if="asm == 1 && nv.duyet == 0">
                                        <i class="fa fa-trash red"></i> 
                                    </a>
                                </td> -->
                                <td>{{nv.name}}</td>
                                <td>{{nv.cong}}</td>
                                <td>{{nv.congapp}}</td>
                                <td>{{nv.luongcung | currency}}</td>
                                <td>{{nv.luongtime | currency}}</td>
                                <td v-if="nv.vp == 0">
                                    <span v-if="nv.dsrieng > 0">{{nv.dsrieng | currency}}</span>
                                    <span v-if="nv.dsrieng == 0">-</span>
                                </td>
                                <td v-if="nv.vp == 0">
                                    <span v-if="nv.point > 0">{{nv.point | currency}}</span>
                                    <span v-if="nv.point == 0">-</span>
                                </td>
                                <td>
                                    <span v-if="nv.kpi > 0">{{nv.kpi | currency}}</span>
                                    <span v-if="nv.kpi == 0">-</span>
                                </td>
                                <td v-if="nv.vp == 0">
                                    <span v-if="nv.saletarget > 0">{{nv.saletarget | currency}}</span>
                                    <span v-if="nv.saletarget == 0">-</span>
                                </td>
                                <td v-if="nv.vp == 1">{{nv.phone | currency}}</td>
                                <td v-if="nv.vp == 1">{{nv.xang | currency}}</td>
                                <td v-if="nv.vp == 1">{{nv.tienan | currency}}</td>
                                <td>{{nv.congdoan | currency}}</td>
                                <td>
                                    <span v-if="nv.loi > 0">{{nv.loi | currency}}</span>
                                    <span v-if="nv.loi == 0">-</span>
                                </td>
                                <td>
                                    <span v-if="nv.bhxh > 0">{{nv.bhxh | currency}}</span>
                                    <span v-if="nv.bhxh == 0">-</span>
                                </td>
                                <td>
                                    <span v-if="nv.congtac > 0">{{nv.congtac | currency}}</span>
                                    <span v-if="nv.congtac == 0">-</span>
                                </td>
                                <td>
                                    <span v-if="nv.luongkhac > 0">{{nv.luongkhac | currency}}</span>
                                    <span v-if="nv.luongkhac == 0">-</span>
                                </td>
                                <td>{{nv.tong | currency}}</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="chot" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chốt Công</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:200px;">Showroom</th>
                                    <th style="width:200px;">Tên</th>
                                    <th>Tự Tính</th>
                                    <th>App</th>
                                    <th>Giải Trình</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="l in listcongsale">
                                    <td style="width:200px;">{{l.showroom}}</td>
                                    <td style="width:200px;">{{l.name}}</td>
                                    <td><input style="width:50px;" type="number" name="cong" v-model="l.cong"> công</td>
                                    <td>{{l.congapp}} công</td>
                                    <td><input type="text" name="cong" v-model="l.note"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                        <a type="submit" @click="addchot()" class="btn btn-primary">Chốt</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="chotvp" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chốt Công</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <thead>
                                <tr>
                                    <th style="width:200px;">Bộ Phận</th>
                                    <th style="width:200px;">Tên</th>
                                    <th>Tự Tính</th>
                                    <th>App</th>
                                    <th>Giải Trình</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="l in listcongvp">
                                    <td style="width:200px;">{{l.showroom}}</td>
                                    <td style="width:200px;">{{l.name}}</td>
                                    <td><input style="width:50px;" type="number" name="cong" v-model="l.cong"> công</td>
                                    <td>{{l.congapp}} công</td>
                                    <td><input type="text" name="cong" v-model="l.note"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                        <a type="submit" @click="addchotvp()" class="btn btn-primary">Chốt</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="order" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Doanh Số</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label>Chọn Đơn  {{form.or.sr1}} - {{form.or.amount}} - {{form.or.point}}</label>
                                <multiselect class="form-control" v-model="form.or" :options="order" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="so_number" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" >
                                </multiselect>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Chọn Đơn Hủy</label>
                                <multiselect class="form-control" v-model="form.old" :options="orderHuy" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="so_number" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" >
                                </multiselect>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Showroom</label>
                                <multiselect  v-model="form.sr" :options="costcenters" :multiple="false" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"></template>
                                </multiselect>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nhân viên</label>
                                <multiselect  v-model="form.sale" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Nhân Viên" :options="nv" :searchable="true" :allow-empty="true" :multiple="false" :taggable="true">
                                    <template slot="singleLabel" slot-scope="{ option }">
                                        <strong>{{ option.name }}</strong> 
                                    </template>
                                </multiselect>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Doanh Số</label>
                            <input type="text" v-model="form.amount" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Điểm Thưởng</label>
                            <input type="text" v-model="form.point" class="form-control">
                        </div>
                    </div>
                  
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                        <a type="submit" @click="tinhlai" class="btn btn-primary">Tính Lại</a>
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
            sm: 0,
            qa: 0,
            hr: 0,
            asm: 0,
            vp:0,
            asmchot: 0,
            chot: 0,
            list: [],
            nv: [],
            or: '',
            order: [],
            orderHuy: [],
            listcongsale: [],
            listcongvp: [],
            search: new Form({
                year: '15',
                pt: '4',
                vp: '',
            }),
            form: new Form({
                point: '',
                sale: '',
                sr: '',
                note: '',
                old: [],
                or: [],
                amount: '',
            }),
            costcenters: [],
            sum: [],
            tong:{
                dsrieng: 0,
                tong: 0,
                phone: 0,
                xang: 0,
                luongcung: 0,
                luongtime: 0,
                point: 0,
                bhxh: 0,
                congdoan: 0,
                loi: 0,
                tienan: 0,
                congtac: 0,
                saletarget: 0,
                luongkhac: 0,
            },
        }
    },
    methods: {
        searchdata() {
            this.loadluong();
        },
        tinhlai(){
            axios.get("/api/updatedon?z=" + this.search.year + '&p=' + this.search.pt + '&point=' + this.form.point + '&amount=' + this.form.amount + '&old=' + this.form.old.so_number + '&or=' + this.form.or.so_number + '&sale=' + this.form.sale.id + '&sr=' + this.form.sr.name)
            .then(response => {
                this.loadluong();

                $('#order').modal('hide');
            });
        },
        reTotal(){
           
            this.tong.dsrieng = this.sum.reduce(function(v, k){
                    return v + Number(k.dsrieng);
            }, 0);
            if (this.search.pt == 8) {
                this.tong.dsrieng = this.tong.dsrieng + 95480000 - 6724;
            }
            this.tong.tong = this.sum.reduce(function(v, k){
                    return v + Number(k.tong);
            }, 0);
            this.tong.point = this.sum.reduce(function(v, k){
                    return v + Number(k.point);
            }, 0);
            this.tong.xang = this.sum.reduce(function(v, k){
                    return v + Number(k.xang);
            }, 0);
            this.tong.phone = this.sum.reduce(function(v, k){
                    return v + Number(k.phone);
            }, 0);
            this.tong.bhxh = this.sum.reduce(function(v, k){
                    return v + Number(k.bhxh);
            }, 0);
            this.tong.congdoan = this.sum.reduce(function(v, k){
                    return v + Number(k.congdoan);
            }, 0);
            this.tong.congtac = this.sum.reduce(function(v, k){
                    return v + Number(k.congtac);
            }, 0);
            this.tong.luongkhac = this.sum.reduce(function(v, k){
                    return v + Number(k.luongkhac);
            }, 0);
            this.tong.tienan = this.sum.reduce(function(v, k){
                    return v + Number(k.tienan);
            }, 0);
            this.tong.loi = this.sum.reduce(function(v, k){
                    return v + Number(k.loi);
            }, 0);
            this.tong.kpi = this.sum.reduce(function(v, k){
                    return v + Number(k.kpi);
            }, 0);
            this.tong.saletarget = this.sum.reduce(function(v, k){
                    return v + Number(k.saletarget);
            }, 0);
            this.tong.luongcung = this.sum.reduce(function(v, k){
                    return v + Number(k.luongcung);
            }, 0);
            this.tong.luongtime = this.sum.reduce(function(v, k){
                    return v + Number(k.luongtime);
            }, 0);

            console.log(this.tong)

            // this.target.chiphitarget = this.onlines.data.reduce(function(v, k){
            //         return v + Number(k.chiphitarget);
            // }, 0);

            // this.target.mess = this.onlines.data.reduce(function(v, k){
            //         return v + Number(k.mess);
            // }, 0);

            // this.target.targetmess = this.onlines.data.reduce(function(v, k){
            //         return v + Number(k.targetmess);
            // }, 0);

            // this.target.targetkhtn = this.onlines.data.reduce(function(v, k){
            //         return v + Number(k.targetkhtn);
            // }, 0);

            // this.target.targetphone = this.onlines.data.reduce(function(v, k){
            //         return v + Number(k.targetphone);
            // }, 0);
        },
        loadluong() {
            let check = this.search.vp;
            if(check) {
                this.vp = 1;
            }else{
                this.vp = 0;
            }
            axios.get("/api/luongsale?z=" + this.search.year + '&p=' + this.search.pt + '&vp=' + this.vp)
            .then(response => {
                this.list = response.data.new;
                this.sum = response.data.tong;
                this.reTotal();
            });

            axios.get("/api/sm-chot?z=" + this.search.year + '&p=' + this.search.pt)
            .then(response => {
                this.chot = response.data;
            });

            axios.get("/api/asm-chot?z=" + this.search.year + '&p=' + this.search.pt)
            .then(response => {
                this.asmchot = response.data;
            });
        },
        chotcong(){
            axios.get("/api/listcongsale?z=" + this.search.year + '&p=' + this.search.pt)
            .then(response => {
                this.listcongsale = response.data;
            });
            $('#chot').modal('show');
        },
        chotcongvp(){
            axios.get("/api/listcongvp?z=" + this.search.year + '&p=' + this.search.pt)
            .then(response => {
                this.listcongvp = response.data;
            });
            $('#chotvp').modal('show');
        },
        dayds(){
            axios.get("/api/dayds?z=" + this.search.year + '&p=' + this.search.pt)
            .then(response => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Duyệt Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                this.loadluong();
            });
        },
        chiads(){
            axios.get("/api/timdon?z=" + this.search.year + '&p=' + this.search.pt)
            .then(response => {
                this.order = response.data;
            });
            axios.get("/api/timdonhuy?z=" + this.search.year + '&p=' + this.search.pt)
            .then(response => {
                this.orderHuy = response.data;
            });
            $('#order').modal('show');
        },
        addchot(){
            for (var i = 0; i < this.listcongsale.length; i++) {
                axios.get("/api/updatecong?name=" + this.listcongsale[i].name + '&ma=' + this.listcongsale[i].username + '&congapp=' + this.listcongsale[i].congapp + '&cong=' + this.listcongsale[i].cong + '&id=' + this.listcongsale[i].id + '&note=' + this.listcongsale[i].note + '&z=' + this.search.year + '&p=' + this.search.pt + '&sr=' + this.listcongsale[i].showroom + '&sm=' + this.listcongsale[i].sm + '&duyet=0')
                .then(response => {
                    $('#chot').modal('hide');
                    swal.fire({
                        type: 'success',
                        title: 'Chúc Mừng',
                        text: 'Chốt Thành Công',
                        footer: '<a href>Why do I have this issue?</a>'
                    });
                    this.loadluong();
                });
            }
        },
        addchotvp(){
            for (var i = 0; i < this.listcongvp.length; i++) {
                axios.get("/api/updatecong?name=" + this.listcongvp[i].name + '&ma=' + this.listcongvp[i].username + '&congapp=' + this.listcongvp[i].congapp + '&cong=' + this.listcongvp[i].cong + '&id=' + this.listcongvp[i].id + '&note=' + this.listcongvp[i].note + '&z=' + this.search.year + '&p=' + this.search.pt + '&sr=' + this.listcongvp[i].showroom + '&sm=' + this.listcongvp[i].sm + '&duyet=1')
                .then(response => {
                    $('#chotvp').modal('hide');
                    swal.fire({
                        type: 'success',
                        title: 'Chúc Mừng',
                        text: 'Chốt Thành Công',
                        footer: '<a href>Why do I have this issue?</a>'
                    });
                    this.loadluong();
                });
            }
        },
        khongduyet(sr){
            axios.get("/api/deletesr?z=" + this.search.year + '&p=' + this.search.pt + '&sr=' + sr)
            .then(response => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Xóa Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                this.loadluong();
            });
        },
        duyetcong(){
            axios.get("/api/duyetcong?z=" + this.search.year + '&p=' + this.search.pt)
            .then(response => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Duyệt Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                this.loadluong();
            });
        },
    },
    created() {
        this.loadluong();
        Fire.$on('searching', () => {
            this.loadluong();
        })
        axios.get("/api/checksm")
        .then(response => {
            this.sm = response.data;
        });
        axios.get("/api/checkqa")
        .then(response => {
            this.qa = response.data;
        });
        axios.get("/api/checkasm")
        .then(response => {
            this.asm = response.data;
        });
        axios.get("/api/checkhr")
        .then(response => {
            this.hr = response.data;
        });
        axios.get("/api/users-costcenters")
        .then(response => {
            this.costcenters = response.data;
        });
        axios.get('/api/getAllUsers').then(({ data }) => (this.nv = data));

    },
}

</script>
<style>

.tableFixHead          { overflow-y: auto; height: 700px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1;}

table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
tbody tr td { z-index: -2px;}
</style>