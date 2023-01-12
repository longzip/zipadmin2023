<template>
    <div class="">
        <div class="card card-default">
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn ngày</label>
                            <date-range-picker style="display: block;" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges">
                                <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                            </date-range-picker>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label></label>
                <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title pull-left">Danh Sách Lịch Giao</h3>
                <div class="card-tools">
                    <a href="#" @click="newModal" class="btn btn-primary">
                        Thêm
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <!-- <th>Showroom</th> -->
                            <th>ĐH</th>
                            <th>Sản Phẩm</th>                            
                            <th style="width: 50px;">Khách</th>
                            <th>SĐT</th>
                            <th style="max-width: 250px;">Địa Chỉ</th>
                            <th>Phải Thu {{tong | currency}}</th>
                            <th>Thi Công</th>
                            <th>Thông Tin Xe</th>
                            <th>Ghi Chú</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="t in tc.data">
                            <tr v-bind:class="{ 'bg bg-secondary': t.color == 0,'bg-info text-white': t.color == 1,'bg-success text-white': t.color == 2,'bg-primary text-white': t.color == 3,'bg-warning text-dark': t.color == 4,'bg-success text-dark': t.color == 5}">
                                <!-- <td>{{t.costcenter}}</td> -->
                                <td v-html="t.order"></td>
                                <td v-html="t.product"></td>
                                <td style="width: 50px;">{{t.name}}</td>
                                <td>
                                    <a href="#" @click="showphone(t.phone)" v-if="t.phone">{{ t.phone | formatPhone}}</a>
                                    <a href="#" @click="showphone(t.phone)" v-if="!t.phone"></a>
                                </td>
                                <td style="max-width: 250px;">{{t.address}}</td>
                                <td>{{(t.money) | currency}}</td>
                                <td>
                                    <div v-for="n in t.thicong">
                                        <p>{{n.name}}</p>
                                    </div>
                                    <div>
                                    {{t.date}} 
                                    </div>
                                    <div>
                                    {{t.giohenkhach}}   
                                    </div>
                                </td>
                                <td>
                                    {{t.thongtinnhaxe}}
                                    <div>
                                        {{t.giovaonm}}
                                    </div>
                                </td>
                                <td>{{t.note}}</td>
                                <td>
                                    <a href="#" @click="editModal(t)" v-if="t.admin == 1 && t.status == 0 && t.type == 0">
                                        <i class="fa fa-edit blue"></i>
                                    </a>
                                    <a href="#" @click="editModalBD(t)" v-if="t.admin == 1 && t.type == 1 && t.costcenter != null">
                                        <i class="fa fa-edit red"></i>
                                    </a>
                                    <a href="#" @click="duyet(t)" v-if="t.admin == 1 && t.status == 0 && t.id > 0">
                                        <i class="fa fa-check blue"></i>
                                    </a>
                                    <a href="#" @click="deleteModal(t)" v-if="t.admin == 1 && t.type > 0">
                                        <i class="fa fa-trash red"></i>
                                    </a>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <pagination :data="tc" @pagination-change-page="loadlich"></pagination>
            </div>
        </div>

        <div class="modal fade" id="userModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa Lịch</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Đơn Hàng</label>
                            <input v-model="form.so_number" v-show="editmode" type="text" class="form-control" disabled="">
                            <input v-model="form.idorder" type="hidden">
                            <input v-model="form.start_date" type="hidden">
                            <input v-model="form.costcenter" type="hidden">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-8">
                                <label>Tên Khách Hàng</label>
                                <input v-model="form.name" type="text" class="form-control" placeholder="Tên ">
                            </div>
                            <div class="col-md-4">
                                <label>Sđt</label>
                                <input v-model="form.phone" type="number" class="form-control"> 
                            </div>
                        </div>
                       
                        <div class="row form-group" v-for="(pr,key,i) in listpro" :key="pr.item.name" v-show="editmode">
                            <div class="form-group col-md-6">
                                <label>Chọn Sản Phẩm</label>
                                <multiselect v-model="form.item = pr.item" :options="products" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                                </multiselect>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>SL</label>
                                    <input v-model="form.quantity = pr.quantity" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Số Tiền</label>
                                <input v-model="form.amount = pr.amount" type="text" class="form-control">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Tổng</label>
                                <input class="form-control" type="number" v-model="form.total">
                                {{form.total | currency}}
                            </div>
                            <div class="form-group col-md-4">
                                <label>Đặt Cọc</label>
                                <input v-model="form.prepay" type="number" class="form-control">
                                {{form.prepay | currency}}
                            </div>
                            <div class="form-group col-md-4">
                                <label>Còn Lại</label>
                                <input class="form-control" type="number" v-model="form.money">
                                {{form.money | currency}}
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>VAT</label>
                                <input class="form-control" type="number" v-model="form.vat">
                            </div>
                            <div class="form-group col-md-3">
                                <label>VC</label>
                                <input v-model="form.fee_vc" type="number" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label>LĐ</label>
                                <input class="form-control" type="number" v-model="form.fee_ld">
                            </div>
                            <div class="form-group col-md-3">
                                <label>CK</label>
                                <input class="form-control" type="number" v-model="form.qgg">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Địa Chỉ</label>
                            <input v-model="form.address" type="text" class="form-control">
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Giờ Vào NM</label>
                                <input class="form-control" type="time" v-model="form.giovaonm">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Ngày Giao</label>
                                <input v-model="form.date" type="date" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Giờ hẹn khách</label>
                                <input class="form-control" type="time" v-model="form.giohenkhach">
                            </div>
                        </div>
                        <div class="row">
                            <div class=" col-md-6">
                                <label>Thông Tin Nhà Xe</label>
                                <textarea v-model="form.thongtinnhaxe" type="text" class="form-control" placeholder="Nhập Tên Nhà Xe,SĐT Lái Xe,Tên Người Lái,Biển Số Xe"></textarea>
                            </div>
                            <div class=" col-md-6">
                                <label>Ghi Chú</label>
                                <textarea v-model="form.note" type="text" class="form-control" placeholder="Nhập ghi chú "></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Chọn Thi Công</label>
                            <multiselect v-model="form.thicong" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Nhân Viên" :options="thicong" :searchable="true" :allow-empty="true" :multiple="true" :taggable="true">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" v-show="editmode" class="btn btn-primary" @click="edit">
                            Cập Nhật
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm Lịch</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Đơn Hàng</label>
                            <input v-model="formnew.so_number" v-show="!editmode" type="text" class="form-control">
                        </div>
                        <div class="form-row col-md-12" v-show="!editmode">
                            <label>Loại: </label>
                            <br>
                            <input type="radio" id="lan-1" value="1" v-model="formnew.pos">
                            <label for="lan-1">Bảo Dưỡng</label>
                            <input type="radio" id="lan-2" value="2" v-model="formnew.pos">
                            <label for="lan-2">Hoàn Thiện</label>
                            <input type="radio" id="lan-3" value="3" v-model="formnew.pos">
                            <label for="lan-3">Tháo Lắp</label>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label>Tên Khách Hàng</label>
                                <input v-model="formnew.name" type="text" class="form-control" placeholder="Tên ">
                            </div>
                            <div class="col-md-4">
                                <label>Sđt</label>
                                <input v-model="formnew.phone" type="number" class="form-control"> 
                            </div>
                        </div>
                        <div class="row form-group" v-show="!editmode">
                            <div class="form-group col-md-3">
                                <label>Ký Hiệu</label>
                                <input v-model="formnew.maloi" type="text" class="form-control"  placeholder="Ký Hiệu ">
                            </div>
                            <div class="form-group col-md-5">
                                <label>Sản Phẩm</label>
                                <input v-model="formnew.sanpham" type="text" class="form-control"  placeholder="Sản Phẩm ">
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>SL</label>
                                    <input v-model="formnew.quantity" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Số Tiền</label>
                                <input v-model="formnew.amount" type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-1" v-bind:style="styleObject">
                            	<a @click="themsp" class="btn btn-info">Thêm</a>	
                            </div>
                            
                            <table class="table table-bordered" v-show="listnew.length >0">
                                <thead>
                                    <tr>
                                        <th>Mã</th>                            
                                        <th>Sản Phẩm</th>                            
                                        <th>SL</th>
                                        <th>Tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(t,i) in listnew">
                                        <tr>
                                            <td>{{t.maloi}}
                                                <a  @click="deletepro(i)">
                                                    <i class="fa fa-trash red"></i>
                                                </a>
                                            </td>
                                            <td>{{t.sanpham}}</td>
                                            <td>{{t.quantity}}</td>
                                            <td>{{t.amount | currency}}</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>VAT</label>
                                <input class="form-control" type="number" v-model="formnew.vat">
                            </div>
                            <div class="form-group col-md-3">
                                <label>VC</label>
                                <input v-model="formnew.fee_vc" type="number" class="form-control">
                            </div>
                            <div class="form-group col-md-3">
                                <label>LĐ</label>
                                <input class="form-control" type="number" v-model="formnew.fee_ld">
                            </div>
                            <div class="form-group col-md-3">
                                <label>CK</label>
                                <input class="form-control" type="number" v-model="formnew.qgg">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Địa Chỉ</label>
                            <input v-model="formnew.address" type="text" class="form-control">
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Giờ Vào NM</label>
                                <input class="form-control" type="time" v-model="formnew.giovaonm">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Ngày Giao</label>
                                <input v-model="formnew.date" type="date" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Giờ hẹn khách</label>
                                <input class="form-control" type="time" v-model="formnew.giohenkhach">
                            </div>
                        </div>
                        <div class="row">
                            <div class=" col-md-6">
                                <label>Thông Tin Nhà Xe</label>
                                <textarea v-model="formnew.thongtinnhaxe" type="text" class="form-control" placeholder="Nhập Tên Nhà Xe,SĐT Lái Xe,Tên Người Lái,Biển Số Xe"></textarea>
                            </div>
                            <div class=" col-md-6">
                                <label>Ghi Chú</label>
                                <textarea v-model="formnew.note" type="text" class="form-control" placeholder="Nhập ghi chú "></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Chọn Thi Công</label>
                            <multiselect v-model="formnew.thicong" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Nhân Viên" :options="thicong" :searchable="true" :allow-empty="true" :multiple="true" :taggable="true">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" v-show="!editmode" class="btn btn-primary" @click="add">
                            Lưu
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="BDModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addNewLabel">Sửa Bảo Dưỡng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Đơn Hàng</label>
                            <input v-model="formbd.so_number"  type="text" class="form-control" disabled="">
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <label>Tên Khách Hàng</label>
                                <input v-model="formbd.name" type="text" class="form-control" placeholder="Tên " disabled="">
                            </div>
                            <div class="col-md-4">
                                <label>Sđt</label>
                                <input v-model="formbd.phone" type="number" class="form-control"> 
                            </div>
                        </div>
                        <div class="row form-group" >
                            <div class="form-group col-md-3">
                                <label>Ký Hiệu</label>
                                <input v-model="formbd.maloi" type="text" class="form-control"  placeholder="Ký Hiệu ">
                            </div>
                            <div class="form-group col-md-5">
                                <label>Sản Phẩm</label>
                                <input v-model="formbd.sanpham" type="text" class="form-control"  placeholder="Sản Phẩm ">
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label>SL</label>
                                    <input v-model="formbd.quantity" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Số Tiền</label>
                                <input v-model="formbd.amount" type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-1" v-bind:style="styleObject">
                                <a @click="themspbd" class="btn btn-info">Thêm</a>    
                            </div>
                            
                            <table class="table table-bordered" v-show="listnewbd.length >0">
                                <thead>
                                    <tr>
                                        <th>Mã</th>                            
                                        <th>Sản Phẩm</th>                            
                                        <th>SL</th>
                                        <th>Tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-for="(t,i) in listnewbd">
                                        <tr>
                                            <td>{{t.maloi}}
                                                <a  @click="deletepro(i)">
                                                    <i class="fa fa-trash red"></i>
                                                </a>
                                            </td>
                                            <td>{{t.sanpham}}</td>
                                            <td>{{t.quantity}}</td>
                                            <td>{{t.amount | currency}}</td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <label>Địa Chỉ</label>
                            <input v-model="formbd.address" type="text" class="form-control">
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Giờ Vào NM</label>
                                <input class="form-control" type="time" v-model="formbd.giovaonm">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Ngày Giao</label>
                                <input v-model="formbd.date" type="date" class="form-control">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Giờ hẹn khách</label>
                                <input class="form-control" type="time" v-model="formbd.giohenkhach">
                            </div>
                        </div>
                        <div class="row">
                            <div class=" col-md-6">
                                <label>Thông Tin Nhà Xe</label>
                                <textarea v-model="formbd.thongtinnhaxe" type="text" class="form-control" placeholder="Nhập Tên Nhà Xe,SĐT Lái Xe,Tên Người Lái,Biển Số Xe"></textarea>
                            </div>
                            <div class=" col-md-6">
                                <label>Ghi Chú</label>
                                <textarea v-model="formbd.note" type="text" class="form-control" placeholder="Nhập ghi chú "></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Chọn Thi Công</label>
                            <multiselect v-model="formbd.thicong" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Nhân Viên" :options="thicong" :searchable="true" :allow-empty="true" :multiple="true" :taggable="true">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="editbd">
                            Lưu
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="phoneshow" tabindex="-1" role="dialog" aria-labelledby="saleTargetModelTitle" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 550px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saleTargetModelTitle">SĐT: <small>{{phone}}</small></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
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
            selectorders: [],            
            products: [],
            tc: [],
            tong: 0,
            phone:'',
            styleObject: {
			    padding: '30px 15px 0px 0px'
			},
            thicong: [],
            listpro: [],
            listnew: [],
            listnewbd: [],
            listold: [],
            form: new Form({
                so_number: '',
                idorder: '',
                costcenter: '',
                start_date: '',               
                name: '',                
                date: '',                
                address: '',
                thicong: '',
                giovaonm: '',
                item: '',
                money: '',
                prepay: '',
                total: '',
                pos: '',
                fee_ld: 0,
                quantity: 0,
                amount: 0,
                qgg: 0,
                idorder: '',
                fee_vc: 0,
                vat: 0,
                note:'',
                phone: '',  
                thongtinnhaxe:'',  
                giohenkhach:'',            
                id:'',
            }),
            formnew: new Form({
                so_number: '',
                idorder: '',
                costcenter: '',
                maloi: '',
                start_date: '',               
                name: '',                
                date: '',                
                address: '',
                thicong: '',
                giovaonm: '',
                item: '',
                pos: '',
                sanpham: '',
                fee_ld: 0,
                quantity: 0,
                amount: 0,
                qgg: 0,
                idorder: '',
                fee_vc: 0,
                vat: 0,
                note:'',
                phone: '',  
                thongtinnhaxe:'',  
                giohenkhach:'',            
                money: '',                
                id:'',
            }),
            formbd: new Form({
                so_number: '',
                idorder: '',
                costcenter: '',
                maloi: '',
                start_date: '',               
                name: '',                
                date: '',                
                address: '',
                thicong: '',
                giovaonm: '',
                item: '',
                pos: '',
                sanpham: '',
                quantity: 0,
                amount: 0,
                idorder: '',
                note:'',
                phone: '',  
                thongtinnhaxe:'',  
                giohenkhach:'',            
                money: '',                
                id:'',
            }),
            opens: "center", //which way the picker opens, default "center", can be "left"/"right"
            locale: {
                direction: 'ltr', //direction of text
                format: 'DD-MM-YYYY', //fomart of the dates displayed
                separator: ' - ', //separator between the two ranges
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                weekLabel: 'W',
                customRangeLabel: 'Custom Range',
                daysOfWeek: moment.weekdaysMin(), //array of days - see moment documenations for details
                monthNames: moment.monthsShort(), //array of month names - see moment documenations for details
                firstDay: 1, //ISO first day of week - see moment documenations for details
                showWeekNumbers: true //show week numbers on each row of the calendar
            },
            ranges: { 
                'Zip': [moment('2013-12-31').endOf('week').isoWeekday(1), moment('2025-12-31').endOf('week')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
                'Z15/P1': [moment('2020-12-28').endOf('week').isoWeekday(1), moment('2021-01-24').endOf('week')],
                'Z15/P2': [moment('2021-01-25').endOf('week').isoWeekday(1), moment('2021-02-21').endOf('week')],
                'Z15/P3': [moment('2021-02-22').endOf('week').isoWeekday(1), moment('2021-03-21').endOf('week')],
                'Z15/P4': [moment('2021-03-22').endOf('week').isoWeekday(1), moment('2021-04-18').endOf('week')],
                'Z15/P5': [moment('2021-04-19').endOf('week').isoWeekday(1), moment('2021-05-16').endOf('week')],
                'Z15/P6': [moment('2021-05-17').endOf('week').isoWeekday(1), moment('2021-06-13').endOf('week')],
                'Z15/P7': [moment('2021-06-14').endOf('week').isoWeekday(1), moment('2021-07-11').endOf('week')],
                'Z14/P8': [moment('2020-07-13').endOf('week').isoWeekday(1), moment('2020-08-09').endOf('week')],
                'Z14/P9': [moment('2020-08-10').endOf('week').isoWeekday(1), moment('2020-09-06').endOf('week')],
                'Z14/P10': [moment('2020-09-07').endOf('week').isoWeekday(1), moment('2020-10-04').endOf('week')],
                'Z14/P11': [moment('2020-10-05').endOf('week').isoWeekday(1), moment('2020-11-01').endOf('week')],
                'Z14/P12': [moment('2020-11-02').endOf('week').isoWeekday(1), moment('2020-11-29').endOf('week')],
                'Z14/P13': [moment('2020-11-30').endOf('week').isoWeekday(1), moment('2020-12-27').endOf('week')],
            },
            startDate: moment('2020-11-30').format(),
            endDate: moment('2020-12-27').format(),

            selectedDate: {
                startDate: moment('2020-11-30'),
                endDate: moment('2020-12-27')
            },
        }
    },
    methods: {
        searchdata() {
            this.loadlich();
        },
        edit(){
            let formData = new FormData();
            if (this.listpro) {
                for( var i = 0; i < this.listpro.length; i++ ){
                    let item = this.listpro[i];
                    formData.append('item[' + i + ']', JSON.stringify(item));
                    
                }
            }
            if (this.form.thicong) {
                for( var i = 0; i < this.form.thicong.length; i++ ){
                    let list = this.form.thicong[i];
                    formData.append('thicong[' + i + ']', list.id);
                }
            }

            formData.append('so_number', this.form.so_number);
            formData.append('idorder', this.form.idorder);
            formData.append('costcenter', this.form.costcenter);
            if (this.form.start_date) {
                formData.append('start_date', this.form.start_date);
            }
            if (this.form.idorder) {
                formData.append('idorder', this.form.idorder);
            }
            if (this.form.costcenter) {
                formData.append('costcenter', this.form.costcenter);
            }
            if (this.form.giovaonm) {
                formData.append('giovaonm', this.form.giovaonm);
            }
            if (this.form.id) {
                formData.append('id', this.form.id);
            }
              
            formData.append('fee_ld', this.form.fee_ld);
            formData.append('qgg', this.form.qgg);
            formData.append('fee_vc', this.form.fee_vc);
            formData.append('vat', this.form.vat);
            formData.append('thongtinnhaxe', this.form.thongtinnhaxe);
            formData.append('giohenkhach', this.form.giohenkhach);
            formData.append('name', this.form.name);
            formData.append('date', this.form.date);
            formData.append('address', this.form.address);
            formData.append('note', this.form.note);
            formData.append('prepay', this.form.prepay);
            formData.append('money', this.form.money);
            formData.append('total', this.form.total);
            formData.append('phone', this.form.phone);
            axios.post('/api/thicongedit',formData, {
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
                $('#userModalCenter').modal('hide');
                this.loadlich();
            });
        },

        themsp(){
            this.listold = [];
            this.listold.maloi = this.formnew.maloi;
            this.listold.sanpham = this.formnew.sanpham;
            this.listold.amount = this.formnew.amount;
            this.listold.quantity = this.formnew.quantity;
            this.listnew.push(this.listold);
            this.formnew.quantity = 0;
            this.formnew.amount = 0;
            this.formnew.maloi = '';
            this.formnew.sanpham = '';
        },

        themspbd(){
            this.listold = [];
            this.listold.maloi = this.formbd.maloi;
            this.listold.sanpham = this.formbd.sanpham;
            this.listold.amount = this.formbd.amount;
            this.listold.quantity = this.formbd.quantity;
            this.listnewbd.push(this.listold);
            this.formbd.quantity = 0;
            this.formbd.amount = 0;
            this.formbd.maloi = '';
            this.formbd.sanpham = '';
        },

        duyet(t){
            axios.get('api/duyetthicong?id=' + t.id)
                .then(() => {
                    this.loadlich();
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },

        deleteModal(t){
            axios.get('api/deletethicong?id=' + t.id)
                .then(() => {
                    this.loadlich();
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },

        editModal(cv){
            this.editmode = true;
            this.form.reset();
            $('#userModalCenter').modal('show');
            this.form.fill(cv);
            this.listpro = cv.products;
        },

        showphone(phone){
            this.phone = phone;
            $('#phoneshow').modal('show');
        },

        add(){

            let formData = new FormData();
            if (this.listnew.length > 0) {
                for( var i = 0; i < this.listnew.length; i++ ){
                    let item = this.listnew[i];
                    formData.append('item[' + i + ']', JSON.stringify(Object.values(item)));
                }
            }
            if (this.formnew.thicong) {
                for( var i = 0; i < this.formnew.thicong.length; i++ ){
                    let list = this.formnew.thicong[i];
                    formData.append('thicong[' + i + ']', list.id);
                }
            }

            formData.append('so_number', this.formnew.so_number);

            if (this.formnew.giovaonm) {
                formData.append('giovaonm', this.formnew.giovaonm);
            }
              
            formData.append('fee_ld', this.formnew.fee_ld);
            formData.append('qgg', this.formnew.qgg);
            formData.append('fee_vc', this.formnew.fee_vc);
            formData.append('vat', this.formnew.vat);
            formData.append('thongtinnhaxe', this.formnew.thongtinnhaxe);
            formData.append('giohenkhach', this.formnew.giohenkhach);
            formData.append('name', this.formnew.name);
            formData.append('date', this.formnew.date);
            formData.append('address', this.formnew.address);
            formData.append('note', this.formnew.note);
            formData.append('phone', this.formnew.phone);
            formData.append('pos', this.formnew.pos);
            axios.post('/api/thicongadd',formData, {
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
                $('#ModalCenter').modal('hide');
                this.loadlich();
            });
        },

        editbd(){

            let formData = new FormData();
            if (this.listnewbd.length > 0) {
                for( var i = 0; i < this.listnewbd.length; i++ ){
                    let item = this.listnewbd[i];
                    formData.append('item[' + i + ']', JSON.stringify(Object.values(item)));
                }
            }
            console.log(this.formbd);
            if (this.formbd.thicong) {
                for( var i = 0; i < this.formbd.thicong.length; i++ ){
                    let list = this.formbd.thicong[i];
                    console.log(list);
                    formData.append('thicong[' + i + ']', list.id);
                }
            }
            console.log(this.formbd);

            formData.append('so_number', this.formbd.so_number);
            formData.append('id', this.formbd.id);
            if (this.formbd.giovaonm) {
                formData.append('giovaonm', this.formbd.giovaonm);
            }
              
            formData.append('thongtinnhaxe', this.formbd.thongtinnhaxe);
            formData.append('giohenkhach', this.formbd.giohenkhach);
            formData.append('name', this.formbd.name);
            formData.append('date', this.formbd.date);
            formData.append('address', this.formbd.address);
            formData.append('note', this.formbd.note);
            formData.append('phone', this.formbd.phone);
            formData.append('pos', this.formbd.pos);

            axios.post('/api/bdedit',formData, {
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
                $('#BDModalCenter').modal('hide');
                this.loadlich();
            });
        },
        
        deletepro(k){
            this.$delete(this.listnew, k);
            this.$delete(this.listnewbd, k);
        },

        loadlich(page = 1) {
            this.$Progress.start();
            axios.get('api/thicong?page=' + page + '&' +  this.getPara())
                .then(responsive => {
                    this.tc = responsive.data;
                    this.$Progress.finish();
                    this.tong = this.tc.data.reduce(function(v, k){
                        
                            return v + Number(k.money);
                    }, 0);
                })
                .catch(() => this.$Progress.fail());
        },


        getPara() {
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            return  dates_para + '&' + queryName;
        },

        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
        },

        editModalBD(bd) {
            this.formbd.fill(bd);
            $('#BDModalCenter').modal('show');
        },

        newModal() {
            this.editmode = false;
            this.listpro = [];
            this.formnew.reset();
            $('#ModalCenter').modal('show');
        },
    },
    created() {
        this.loadlich();
        Fire.$on('searching', () => {
            this.loadlich();
        });
        axios.get('/api/products-list/').then(res => this.products = res.data);
        axios.get('/api/getThiCongUsers').then(({ data }) => (this.thicong = data));
        axios.get('/api/listOrders')
            .then(res => this.selectorders = res.data);
    },
}

</script>
