<template>
    <div class="">
        <div class="row justify-content-center">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Chi Phí</h3>
                            <div class="card-tools">
                                <a href="#" @click="newModal" class="btn btn-primary">
                                    Thêm  <i class="fa fa-user-plus"></i>
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
<!--                                         <th>ID</th> -->
                                        <th>Tên</th>
                                        <th>Người Tạo</th>
                                        <th>Bộ Phận</th>
                                        <th>Loại chi phí</th>
                                        <th>Tổng chi phí</th>
                                        <th>Tình trạng</th>
                                        <th>Tổng tiền thực</th>
                                        <th>Duyệt</th>
                                        <th>Check</th>
                                    </tr>
                                    <tr v-for="chiphi in chiphis.data">
                                     <!--    <td>{{chiphi.id}}</td> -->
                                        <td><a href="#" @click="detail_chiphi(chiphi)">{{chiphi.tendexuat}}</a></td>
                                        <td>{{chiphi.user_name}}</td>
                                        <td>{{chiphi.role_name}}</td>
                                        <td>{{chiphi.loaichiphi_name}}</td>
                                        <td>{{chiphi.tongchiphi}}</td>
                                        <td v-if="chiphi.tinhtrang==0">Khẩn cấp</td>
                                        <td v-else-if="chiphi.tinhtrang==1">Ưu tiên</td>
                                        <td v-else-if="chiphi.tinhtrang==2">Bình thường</td>
                                        <td>{{chiphi.tongtienthuc}}</td>
                                        <td>
                                                <a v-if="chiphi.duyet == 0 || chiphi.duyet == 5 "  href="#" @click="guidexuat(chiphi)" class="btn btn-danger">Gửi</a>
                                                <a v-if="chiphi.duyet == 1 "  href="#" class="btn btn-danger" @click="likeModal(chiphi)">Chờ Duyệt</a>
                                                <a v-if="chiphi.duyet == 2 "  href="#" class="btn btn-danger" @click="likeModal(chiphi)">Kế toán đã duyệt</a>
                                                <a v-if="chiphi.duyet == 3 " href="#" class="btn btn-danger" @click="likeModal(chiphi)">Đã duyệt</a>
                                                <a v-if="chiphi.duyet == 5 " href="#" class="btn btn-danger" @click="likeModal(chiphi)">Không duyệt</a>
                                                </td>
                                        <td v-if="chiphi.duyet == 0 || chiphi.duyet == 5">
                                            <a href="#" @click="editModal(chiphi)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            /
                                            <a href="#" @click="deleteUser(chiphi)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                        </td>
                                        <td v-if=" chiphi.duyet == 3 && chiphi.ketoan==1">
                                            <a href="#" @click="editModal(chiphi)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            /
                                            <a href="#" @click="deleteUser(chiphi)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        <!-- Modal-->
        <div class="modal fade" id="createModalCenter"   tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered box-chiphi" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm Chi Phí</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa Chi Phí</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="form-ro w col-md-12">
                            <label >Đề xuất</label> <!-- v-if="chiphi.duyet == 0" -->
                            <textarea  style="white-space: pre-line;" ref="tendexuat" v-model="chiphiall.tendexuat" type="detail" name="tendexuat" placeholder="Nhập tên Đề Xuất" class="form-control" :class="{ 'is-invalid': form.errors.has('detail') }"></textarea> 
                            <has-error :form="form" field="detail"></has-error>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Người Tạo</label>
                            <multiselect v-model="chiphiall.user" ref="user" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Nhân Viên" :options="users" :searchable="true" :allow-empty="true">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Bộ phận</label>
                            <multiselect v-model="chiphiall.role" ref="role" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Bộ phận" :options="roles" :searchable="true" :allow-empty="false">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                        <div class="col-md-12"> <!-- form-row col-md-12 row-inline  -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div >
                                        <label>Nội dung đề xuất</label> <br>
                                        <input type="text" class="form-control" name="noidung" placeholder="Nhập vào nội dung đề xuất." v-model="form.noidung" />
                                        <strong> Số lượng:</strong><input type="number" class="form-control" name="soluong" placeholder="so luong" v-model="form.soluong" />
                                        <strong> Giá:</strong><input type="number" class="form-control" name="price" placeholder="price?"  v-model="form.price"/>
                                        <strong>Mục đích:</strong> <input type="text" class="form-control" name="mucdich" placeholder="Nhập nội dung mục đích." v-model="form.mucdich" />
                                        <strong>Tổng tiền:</strong> <input disabled type="number" class="form-control" name="tongtien" v-bind:value="form.soluong * form.price">
                                        <strong> Ghi chú:</strong><input type="text" class="form-control" name="ghichu" placeholder="Nhập ghi chú."  v-model="form.ghichu"/>
                                        <button type="button" class="add-phone" @click="addItem(items.data)">Add</button>
                                        <br/>
                                    </div>
                                    <h3  v-show="!editmodedetail" >List all</h3>
                                    <h3  v-show="editmodedetail" >List  Edit</h3>
                                    <table>
                                        <thead>
                                          <tr>
                                            <th>Nội Dung Đề Xuất</th>
                                            <th>Giá</th>
                                            <th>Số Lượng</th>
                                            <th>Mục Đích</th>
                                            <th>Ghi Chú</th>
                                            <th>Tổng Tiền</th>
                                            <th>Check</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in items.data">
                                                <th>{{item.NoiDung}}</th>
                                                <th> {{item.GiaTien}}  </th>
                                                <th> {{item.SoLuong}} </th>
                                                <th> {{item.MucDich}} </th>
                                                <th> {{item.GhiChu}} </th>
                                                <th> {{item.TongTien}} </th>
                                                <th>
                                                    <a href="#" @click="deleteModalDetail(item)">
                                                        <i class="fa fa-trash red"></i>
                                                    </a>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table> <br>
                                    <span class="multiselect__tag">Tổng đề xuất: </span><strong class="multiselect__tag">{{count}}</strong> </br>
                                    <div class="sum"><span class="multiselect__tag">Tổng thanh toán:</span> <input type="number" name="tongchiphi" v-model="chiphi.tongchiphi" disabled /></div>

                                </div>
                            </div>
                        </div>
                        <div class="row-inline">
                            <div class="form-group col-md-6">
                                <label>Loại Chi Phí</label>
                                <multiselect v-model="chiphiall.loaichiphi" ref="loaichiphi" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Loại Chi Phí" :options="loaichiphi" :searchable="true" :allow-empty="true">
                                <template SLot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong>
                                </template>
                            </multiselect>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tình trạng</label>
                                <select v-model="chiphiall.tinhtrang">
                                    <option value="0">Khẩn cấp</option>
                                    <option value="1">Ưu tiên </option>
                                    <option value="2">Bình thường</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" @click="clear()">Close</button>
                            <button v-show="editmode" type="button" class="btn btn-success"  @click="updateChiPhi()" >Update</button>
                            <button v-show="!editmode" type="button"  class="btn btn-primary"  @click="createChiPhi()">Create</button>
                        </div>
                    </form>
            </div>
            </div>
        </div>
        <!-- Modal-->
        <!-- updated-chiphithuc -->
        <div class="modal fade" id="createModalCenter1" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered box-chiphi" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa Chi Phí Thực</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <div class="form-ro w col-md-12">
                            <label>Đề xuất: </label>
                            <strong>{{chiphi.tendexuat}}</strong>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Người Tạo: </label>
                            <strong>{{chiphi.user_name}}</strong>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Bộ phận: </label>
                            <strong>{{chiphi.role_name}}</strong>
                        </div>
                        <div class="col-md-12"> <!-- form-row col-md-12 row-inline  -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h3  v-show="!editmodedetail" > <p>List all</p></h3>
                                    <h3  v-show="editmodedetail" ><p>List  Edit</p></h3>
                                    <table>
                                        <thead>
                                          <tr>
                                            <th>Nội Dung Đề Xuất</th>
                                            <th>Giá</th>
                                            <th>Số Lượng</th>
                                            <th>Mục Đích</th>
                                            <th>Ghi Chú</th>
                                            <th>Tổng Tiền</th>
                                            <th v-if="chiphi.duyet == 3">Tổng tiền thực</th>
                                            <th>Check</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in items.data">
                                                <th v-if="chiphi.duyet == 3 ">{{item.NoiDung}}</th>
                                                <th> {{item.GiaTien}}  </th>
                                                <th> {{item.SoLuong}} </th>
                                                <th> {{item.MucDich}} </th>
                                                <th> {{item.GhiChu}} </th>
                                                <th> {{item.TongTien}} </th>
                                                <th  v-if="chiphi.duyet == 3 ">
                                                    <input keyup="keymonitor" type="number" class="form-control" @keyup="sum1(chiphi)" name="" placeholder="" v-model="item.TienThucTe">
                                                </th>
                                                <th>
                                                    <a href="#" @click="deleteModalDetail(item)">
                                                        <i class="fa fa-trash red"></i>
                                                    </a>
                                                </th>
                                            </tr>
                                        </tbody>
                                    </table> <br>
                                    <div class="sum"><span class="multiselect__tag">Tổng thanh toán:</span> <strong>{{chiphi.tongchiphi}}</strong></div>
                                    <div class="sum"><button class="multiselect__tag"  >Tổng thanh toán thực:</button>
                                        <strong class="multiselect__tag">{{chiphi.tongtienthuc}}</strong></div>


                                </div>
                            </div>
                        </div>
                        <div class="row-inline">
                            <div class="form-group col-md-6">
                                <label>Loại Chi Phí: </label>
                                {{chiphi.loaichiphi_name}}
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tình trạng: </label>
                                <span v-if="chiphi.tinhtrang == 0">Khẩn cấp</span>
                                <span v-if="chiphi.tinhtrang == 1">Ưu tiên</span>
                                <span v-if="chiphi.tinhtrang == 2">Bình thường</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" @click="clear()">Close</button>
                            <button v-show="editmode" type="button" class="btn btn-success"  @click="updateTienThuc()" >Update</button>
                            <button v-show="!editmode" type="button"  class="btn btn-primary"  @click="createChiPhi()">Create</button>
                        </div>
                    </form>
            </div>
            </div>
        </div>
        <!-- end-update_chiphithuc -->
        <div class="modal  fade" id="chiphidetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Chi Phí</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                          <div class="form-row">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nội Dung</th>
                                        <th>Số Lượng</th>
                                        <th>Gía Tiền</th>
                                        <th>Mục đích</th>
                                        <th>Ghi chú</th>
                                        <th>Tổng tiền</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="detail in chiphidetail">
                                        <td>{{detail.NoiDung}} </td>
                                        <td>{{detail.SoLuong}} </td>
                                        <td>{{detail.GiaTien}} </td>
                                        <td>{{detail.MucDich}} </td>
                                        <td>{{detail.GhiChu}} </td>
                                        <td>{{detail.TongTien}} </td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>

                    </div>
                    <div class="modal-footer">
                        <div class="form-row col-md-12">
                            <label>Ảnh:</label>
                            <input type="file" id="file" ref="file" multiple/>
                            <has-error :form="form" field="file"></has-error>
                        </div>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal  fade" id="comentModal" tabindex="-1" role="dialog" aria-labelledby="taskModalDetailTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Duyệt đề xuất</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <comments :comments="chiphi.comments" @create-comment="storeChiPhiComment"></comments>
                        <div class="modal-footer">
                        <span v-if="chiphi.bangiamdoc == 1 && chiphi.duyet==2">
                                <a  @click="giamdocduyet(chiphi)" class="btn btn-success">Giám đốc Duyệt</a>
                                <a  @click="khongduyet(chiphi)" class="btn btn-primary">không duyệt</a>
                        </span>
                        <span ><a class="btn btn-success" v-if="chiphi.duyet==3"> đã duyệt</a></span>
                        <span v-if="chiphi.ketoan == 1 && chiphi.duyet==1">
                            <a  @click="ketoanduyet(chiphi)" class="btn btn-success">Kế toán Duyệt</a>
                            <a @click="khongduyet(chiphi)" class="btn btn-primary">không duyệt</a>
                        </span>
                        </div>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="clear()">Close</button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</template>
    <style type="text/css" lang="scss" scoped>
      .form-row {
        display: block;
      }
      .row-inline{
          display:flex;
          justify-content: space-between;
        }
      .pheduyet {
        width: 50%;
      }
      .form-row {
        clear: both;
      }
      .btn-add{
        display: flex;
        justify-content: flex-end;
         }
     .row{
         .add-phone{
            border: none;
            background-color: #38b97f;
            width: 75px;
            height: 35px;
            border-radius: 5px;
            margin-top: 5px;
            }
    }
    .sum{
        display:flex;
    }
// .minus2 {
//   display: inline;
// }
    @media (min-width: 576px)
    {
        .modal-dialog {
        max-width: 900px;
            }
    }
    </style>
<script>
export default {
    data() {
        return {
            i:{},
            editmode: false,
            editmodedetail: false,
            price:0, //price
            soluong:0,
            comments:{},
            comment: {
                      body: ''
                    },
            resources: [],
            items: {},
            roles: [],
            users: [],
            user: [],
            loaichiphi: [],
            decisions: [],
            decision: [],
            tongtien2: 0,
            chiphis:{},
            chiphi:{},
            item:{},
            chiphidetail:{},
            tongtienthuc:'',
            chiphiall: new Form({
                id: '',
                user: '',
                role: '',
                loaichiphi: [],
                tongtienthuc:'',
                tenchiphi:'',
                tongchiphi:'',
                loaichiphi:'',
                tinhtrang:'',
                tendexuat:'',
                chiphi:'',



            }),
            form: new Form({
                id: '',
                tenchiphi:'',
                sotien: '',
                soluong:'',
                tongtien:'',
                item:{},// push item from input
                price:0, //price
                soluong:0,
                ghichu:'',
                mucdich:'',
                dprice:0,
                check:'',
                tprice:0,
                cprice:0,
                tienthucte:0,
                sum:0,
            }),

            sum:0,
            ok:0,
            count:0,
            tienthucte:0,
            sum_thuc:0,
            cprice:0,
        }
    },
    methods: {
        sum1: function (chiphi) {
            var tong=0;
            var sum_thuc1=0;
            var cprice=0;
            this.chiphi = chiphi;
            this.items.data=chiphi.item;
            if(this.items.data.lenght==0){
                    sum_thuc1=0;
                     }
                else
                {
                    this.items.data.forEach(element => {
                    cprice=parseInt(element.TienThucTe);
                    sum_thuc1+=cprice;

                    });
                    this.chiphi.tongtienthuc=sum_thuc1;
                }

        },
        addItem(items)
            {
                axios.post('api/CphiItem',{noidung:this.form.noidung,soluong:this.form.soluong,giatien:this.form.price,mucdich:this.form.mucdich,ghichu:this.form.ghichu}).then(response => {
                            this.items.data.push({
                            NoiDung:this.form.noidung,
                            GiaTien:this.form.price,
                            MucDich:this.form.mucdich,
                            SoLuong:this.form.soluong,
                            GhiChu:this.form.ghichu,
                            TongTien:this.form.price*this.form.soluong,
                            TienThucTe:this.form.price*this.form.soluong,
                        });
                            this.form.noidung=null;
                            this.form.price=null;
                            this.form.mucdich=null;
                            this.form.soluong=null;
                            this.form.ghichu=null;
                })
                .catch(error => {
                })
                this.count=this.items.data.length+1;
                var cprice=0;
                var sum=0;

                if(this.items.data.length==0){
                    sum=this.form.price*this.form.soluong;
                }
                else
                {
                    items.forEach(element => {
                        console.log(items);
                    cprice+=parseInt(element.TongTien);
                    sum = cprice+parseInt(this.form.price*this.form.soluong)
                    });
                }
                this.chiphi.tongchiphi=sum;
            },
        loadChiPhis(page = 1) {
            axios.get("/api/chiphi?page=" + page).then(({ data }) => (this.chiphis = data));
        },
        createChiPhi() {
           axios.post('api/chiphi',{tendexuat:this.chiphiall.tendexuat,user:this.chiphiall.user,role:this.chiphiall.role,loaichiphi:this.chiphiall.loaichiphi,tongchiphi:this.chiphi.tongchiphi,tinhtrang:this.chiphiall.tinhtrang,tongtienthuc:this.chiphi.tongchiphi})
           .then((result) => {
            this.loadChiPhis();
            this.items.data=[];
            this.chiphiall.tendexuat=null;
            this.chiphiall.role=null;
            this.chiphiall.user=null;
            this.chiphiall.loaichiphi=null;
            this.chiphiall.tongchiphi=null;
            this.chiphi.tongchiphi=null;
            this.chiphiall.tinhtrang=null;
            this.chiphiall.tongtienthuc=null;
            this.chiphi.tongchiphi=null;
            this.count =0;
            this.$Progress.start();
            })
                .catch(error => {
                })
        },
        newModal() {
            this.editmode = false;
            this.editmodedetail = false;
            this.chiphiall.reset();
            this.items.data = [];
            $('#createModalCenter').modal({backdrop: 'static', keyboard: false});

        },
        detail_chiphi(chiphi){
            this.chiphi = chiphi;
            this.chiphidetail = chiphi.detail;
            $('#chiphidetail').modal({backdrop: 'static', keyboard: false});
        },
        editModal(chiphi) {
            this.editmode = true;
            this.editmodedetail = true;
            this.items.data = chiphi.item;
            this.chiphi = chiphi;
            this.form.reset();
            if(chiphi.duyet==3){
            $('#createModalCenter1').modal({backdrop: 'static', keyboard: false});}
            if(chiphi.duyet==0){
            $('#createModalCenter').modal({backdrop: 'static', keyboard: false});}
            this.chiphiall.fill(chiphi);
        },
        updateChiPhi() {
            this.$Progress.start();
            axios.post('api/updatedChiPhi',{chiphiall:this.chiphiall,item:this.items.data,chiphi:this.chiphi
            })
           .then(() => {
                    // success
                    $('#createModalCenter').modal('hide');
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    );
                    this.loadChiPhis();
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                })

            .catch(error => {
                this.$Progress.fail();
            })
        },
        updateTienThuc() {
            this.$Progress.start();
            axios.post('api/updatedChiPhiThuc',{chiphi:this.chiphi,item:this.items.data,
            })
           .then(() => {
                    // success
                    $('#createModalCenter').modal('hide');
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    );
                    this.loadChiPhis();
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                })

            .catch(error => {
                this.$Progress.fail();
            })
            console.log(this.chiphiall);



        },
        deleteModalDetail(item){
            this.$Progress.start();
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
                    this.form.delete('api/chiphidetail/' + item.id).then(($res) => {
                        swal.fire(
                            "Deleted!",
                            "Your file has been deleted.",
                            "success"
                        );
                        this.loadChiPhis();
                        this.items.data = $res.data.i;
                        this.$Progress.finish();
                        sum_thuc1=0;
                    }).catch(() => {
                        swal.fire("Failed!", "There was something wronge.", "warning");
                    });
                }
            })
        },
        deleteUser(chiphi) {
            this.$Progress.start();
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
                    this.form.delete('api/chiphi/' + chiphi.id).then(($res) => {
                        swal.fire(
                            "Deleted!",
                            "Your file has been deleted.",
                            "success"
                        );
                        this.loadChiPhis();
                        this.$Progress.finish();
                    }).catch(() => {
                        swal.fire("Failed!", "There was something wronge.", "warning");
                    });
                }
            })
        },
        likeModal(chiphi) {
                    this.chiphi = chiphi;
                    this.pheduyet = chiphi.nvpheduyet;
                    this.tinhtrang =  chiphi.tinh_trang;
                    $('#comentModal').modal({backdrop: 'static', keyboard: false});
        },
        guidexuat(chiphi){
            axios.get('/api/guidexuat/' + chiphi.id).then(() => {
                            $('#comentModal').modal('hide');
                        this.loadChiPhis();
                    });
        },
        ketoanduyet(chiphi) {
                        axios.get('/api/duyetkt/' + chiphi.id).then(() => {
                            $('#comentModal').modal('hide');
                        this.loadChiPhis();
                    });
                },
        giamdocduyet(chiphi) {
                        axios.get('/api/duyetgd/' + chiphi.id).then(() => {
                            $('#comentModal').modal('hide');
                        this.loadChiPhis();
                    });
                },
        khongduyet(chiphi) {
                axios.get('/api/khongduyetcp/' + chiphi.id).then(() => {
                    $('#comentModal').modal('hide');
                this.loadChiPhis();
            });
        },
        storeChiPhiComment(comment) {
            axios.put('api/chiphicomment/'+ this.chiphi.id, {
                body: comment
            })
            .then(response => {
                this.chiphi.comments.push(response.data);
            });
        },

    },
       created() {
        this.loadChiPhis();

        axios.get('/api/getAllUsers').then(({ data }) => (this.users = data));
        axios.get('/api/getAllLoaiChiPhi').then(({ data }) => (this.loaichiphi = data));
        axios.get("/api/getallobjectroles").then(({ data }) => (this.roles = data));
        axios.get("/api/CphiController").then(({ data }) => (this.items = data));
    },
}

</script>
