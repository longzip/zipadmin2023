<template>
    <div class="">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chọn Trạng Thái</label>
                    <select  class="form-control" v-model="statuss" @change="onChange($event)">
                        <option v-for="option in $parent.statuss" v-bind:value="option.value">
                            {{ option.index }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chọn ngày</label>
                    <date-range-picker style="display: block;" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="$parent.selectedDate" @update="updateValues" :ranges="ranges">
                        <div slot="input" slot-scope="picker">{{ $parent.selectedDate.startDate | myDate }} - {{ $parent.selectedDate.endDate | myDate }}</div>
                    </date-range-picker>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chọn nhân viên<a href="#" @click="reSelectnhanvien"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelectednhanvien" :options="users"   :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheonhanvien">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chọn bộ phận<a href="#" @click="reSelectbophan"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelectedbophan" :options="roles" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheobophan">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
                </div>
            </div>
            <!-- <div class="col-md-3">
                <div class="form-group">
                    <label>Chọn loại vi phạm</label>
                    <select  class="form-control" v-model="statuss" @change="onChange($event)">
                        <option v-for="option in $parent.statuss" v-bind:value="option.value">
                            {{ option.index }}
                        </option>
                    </select>
                </div>
            </div> -->
        </div>

        <div class="row justify-content-center">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Vi phạm</h3>
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
                                    <tr v-for="(vipham, i) in viphams.data" :key="i+10">
                                        <td><a href="#" @click="notbienbanmode(vipham)"><span v-if="vipham.id < 10">00</span><span v-if="10 <= vipham.id && vipham.id < 100">0</span>{{vipham.id}}</a></td>
                                        <td><a href="#" @click="decisionDetailCenter(vipham)">{{vipham.decision_sapo}}</a></td>
                                        <td>
                                        <a href="#" @click="userDetailCenter(vipham)" v-if="vipham.user_name !=null">{{vipham.user_name}}</a>
                                        <a href="#" v-if="vipham.user_name==null">
                                            <label v-for="bp in vipham.roleid">
                                                <span class="badge badge-info">{{bp.name}}</span>
                                             </label>
                                         </a>
                                         </td>
                                        <td>{{vipham.creator}}</td>
                                        <td>{{vipham.ngayvipham}}</td>
                                        <td>{{vipham.created_at | formatDate}}</td>
                                        <td>
                                            <span v-if="vipham.danhgia == 0">Chưa đánh giá</span>
                                            <span v-if="vipham.danhgia == 2">Ghi nhận</span>
                                            <span v-if="vipham.bienban == null && vipham.danhgia == 1">Chờ lập biên bản</span>
                                            <span v-if="vipham.pheduyet == 0 && vipham.bienban != null">Chờ duyệt</span>
                                            <span v-if="vipham.bienban != null && vipham.danhgia == 1 && vipham.pheduyet == 1 && vipham.emailed == 0">Chờ gửi email</span>
                                            <span v-if="vipham.emailed == 1">Đã gửi mail</span>
                                        </td>
                                        <td>
                                            <div v-if="vipham.readonly == false">
                                                <a class="btn btn-primary" href="#" v-if="vipham.bienban != null && vipham.danhgia == 1 && vipham.pheduyet == 1 && vipham.emailed == 0" @click="sendmail(vipham)">Gửi mail</a>
                                                <a class="btn btn-primary" href="#" v-if="vipham.bienban == null && vipham.danhgia == 1" @click="isbienbanmode(vipham)">Biên bản</a>
                                                <a class="btn btn-primary" href="#" v-if="vipham.isapprove == true && vipham.pheduyet == 0 && vipham.bienban != null" @click="pheduyet(vipham)">Phê duyệt</a>
                                            </div>
                                        </td>
                                        <td>
                                            <div v-if="vipham.readonly == false && vipham.emailed == 0 ">
                                                <a href="#" @click="editModal(vipham)" >
                                                    <i class="fa fa-edit blue"></i>
                                                </a>
                                                <a href="#" @click="deleteModal(vipham)">
                                                    <i class="fa fa-trash red"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <pagination :data="viphams" @pagination-change-page="loadViPhams"></pagination>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        <!-- Modal-->
        <div class="modal fade" id="createModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm Vi phạm</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa Vi phạm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? editViPham() : createViPham()">
                        <div class="form-row col-md-12">
                            <label>Quy định</label>
                            <multiselect v-model="form.decision" ref="decision" deselect-label="Can't remove this value" track-by="id" label="sapo" placeholder="Chọn Quy định" :options="decisions" :searchable="true" :allow-empty="false">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.sapo }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Lần vi phạm: </label>
                            <input type="radio" id="lan-1" value="1" v-model="form.solan">
                            <label for="lan-1">Lần 1</label>
                            <input type="radio" id="lan-2" value="2" v-model="form.solan">
                            <label for="lan-2">Lần 2</label>
                            <input type="radio" id="lan-3" value="3" v-model="form.solan">
                            <label for="lan-3">Lần 3</label>
                            <input type="radio" id="lan-4" value="4" v-model="form.solan">
                            <label for="lan-4">Lần 4</label>
                            <input type="radio" id="lan-5" value="5" v-model="form.solan">
                            <label for="lan-5">Lần 5</label>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Nhân Viên</label>
                            <multiselect v-model="form.user" ref="user" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Nhân Viên" :options="users" :searchable="true" :allow-empty="true">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Bộ phận</label>
                            <multiselect v-model="form.roleid" ref="roleid" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Bộ phận" :options="roles" :searchable="true" :allow-empty="false" :multiple="true" >
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Nhân viên phê duyệt</label>
                            <multiselect v-model="form.nvpheduyet" ref="nvpheduyet" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Nhân Viên" :options="nvpheduyets" :searchable="true" :allow-empty="true" :multiple="true" :taggable="true">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                            <!-- <multiselect v-model="form.nvpheduyet" tag-placeholder="Thêm vai trò" placeholder="Chọn bộ phận áp dụng" :options="users" :multiple="true" :taggable="true"></multiselect> -->
                        </div>
                        <div class="form-row col-md-12">
                            <label>Tên vi phạm: </label>
                            <textarea  style="white-space: pre-line;" ref="tenvipham" v-model="form.tenvipham" type="detail" name="tenvipham" placeholder="Nhập tên Vi Phạm" class="form-control" :class="{ 'is-invalid': form.errors.has('detail') }"></textarea> 
                            <has-error :form="form" field="detail"></has-error>
                        </div>
                        <div class="form-row col-md-12">
                            <label for="off">Ngày vi phạm</label>
                            <input v-model="form.ngayvipham" ref="ngayvipham" type="date" class="form-control" id="off">
                        </div>
                        <div class="form-row col-md-12">
                            <label>Thời gian áp dụng: </label>
                            <textarea  style="white-space: pre-line;" ref="timeapply" v-model="form.timeapply" type="detail" name="timeapply" placeholder="Nhập thời gian áp dụng" class="form-control" :class="{ 'is-invalid': form.errors.has('detail') }"></textarea> 
                            <has-error :form="form" field="detail"></has-error>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Tường trình</label>
                            <textarea  style="white-space: pre-line;" ref="tuongtrinh" v-model="form.tuongtrinh" type="detail" name="detail" placeholder="Nhập Nội Dung" class="form-control" :class="{ 'is-invalid': form.errors.has('detail') }"></textarea> 
                            <has-error :form="form" field="detail"></has-error>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Tiền phạt</label>
                            <textarea  style="white-space: pre-line;" ref="tienphat" v-model="form.tienphat" type="detail" name="tienphat" placeholder="Nhập Số Tiền Phạt" class="form-control" :class="{ 'is-invalid': form.errors.has('detail') }"></textarea> 
                            <has-error :form="form" field="detail"></has-error>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Ảnh chứng minh</label>
                            <input type="file" id="file" ref="file" v-on:change="handleFileUpload" multiple/>
                            <has-error :form="form" field="file"></has-error>
                        </div>
                        <div class="form-row col-md-12" id="video">
                            <label>Link video chứng minh</label>
                            <div class="" v-for="(input,k) in videolinks" :key="k">
                                <div class="">
                                    <input type="text" class="form-control" v-model="input.link">
                                </div>
                                <div class="">
                                    <div class="">
                                        <a @click="remove(k)" v-show="k || ( !k && videolinks.length > 1)" class="btn btn-sm btn-info">Bỏ</a>
                                        <!-- <i class="fas fa-plus-circle" @click="add(k)" v-show="k == videolinks.length-1"></i> -->
                                    </div>
                                    <div class="">
                                        <a @click="add(k)" v-show="k == videolinks.length-1" class="btn btn-sm btn-info">Thêm</a>
                                        <!-- <i class="fas fa-minus-circle" @click="remove(k)" v-show="k || ( !k && videolinks.length > 1)"></i> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Đánh giá</label>
                            <input type="radio" id="dung-quy-dinh" value="2" v-model="form.danhgia" ref="danhgia">
                            <label for="dung-quy-dinh">Đúng quy định</label>
                            <input type="radio" id="sai-quy-dinh" value="1" v-model="form.danhgia" ref="danhgia">
                            <label for="sai-quy-dinh">Sai quy định</label>
                        </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="clear()">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
            </div>
        </div>
        <!-- Decision detail -->
        <div class="modal  fade" id="decisionDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Quy định</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                      <div class="form-row">
                        <div class="form-row col-md-12">
                          <div class="col-md-6">
                              <label>Tên Quy định: </label>
                              <label class="detail">{{decision.sapo}}</label>
                          </div>
                          <div class="col-md-6">
                              <label>Quy Trình: </label>
                               <label class="detail">{{quytrinh.name}}</label>
                          </div>
                        </div> 
                      </div>   
                      <div class="form-row">     
                        <div class="form-group col-md-12">
                            <label>Nội Dung: </label>
                            <br>
                             <label style="white-space: pre-line;" class="detail">{{decision.detail}}</label>
                        </div>
                      </div>
                      <div class="form-row">     
                        <div class="form-group col-md-12">
                           <tr>
                                <th class="punishment-table">Lần 1</th>
                                <th class="punishment-table" v-if="vipham.chetai2_data != null">Lần 2</th>
                                <th class="punishment-table" v-if="vipham.chetai3_data != null">Lần 3</th>
                                <th class="punishment-table" v-if="vipham.chetai4_data != null">Lần 4</th>
                                <th class="punishment-table" v-if="vipham.chetai5_data != null">Lần 5</th>
                            </tr>
                                <td class="punishment-table">{{vipham.chetai1_data}}</td>
                                <td class="punishment-table" v-if="vipham.chetai2_data != null">{{vipham.chetai2_data}}</td>
                                <td class="punishment-table" v-if="vipham.chetai3_data != null">{{vipham.chetai3_data}}</td>
                                <td class="punishment-table" v-if="vipham.chetai4_data != null">{{vipham.chetai4_data}}</td>
                                <td class="punishment-table" v-if="vipham.chetai5_data != null">{{vipham.chetai5_data}}</td>
                        </div>
                      </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <!-- User detail -->
        <div class="modal  fade" id="userDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Nhân viên</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                      <div class="form-row">
                        <div class="form-row col-md-12">
                            <label>Tên nhân viên: </label>
                            <label class="detail">{{user.name}}</label>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Email: </label>
                            <br>
                             <label class="detail">{{user.email}}</label>
                        </div>
                      </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <!-- File detail -->
        <div class="modal  fade" id="fileDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">File chứng minh</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                      <div class="form-row"> 
                        <div class="form-row col-md-12">
                            <label>Ảnh: </label>
                            <br>
                            <div v-for="(image, i) in vipham.images" :key="i + 20">
                                <div class="col-md-6">
                                    <img v-bind:src="'/uploads/vipham/' + image" style="width:600px;height:400px;">
                                </div>
                            </div>
                        </div>
                      </div>
                      <div class="form-row">
                        <div class="form-row col-md-12">
                            <label>Link Video: </label>
                            <br>
                            <div v-for="(video, i) in vipham.videos" :key="i + 30">
                                <video width="600" height="400" controls>
                                  <source v-bind:src="video" type="video/mp4">
                                  Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>
                      </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <!-- ViPham detail -->
        <div class="modal  fade" id="viphamDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Vi phạm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row"> 
                        <div class="form-row col-md-12">
                            <div class="col-md-6 float-left">
                                <label>Tên Quy định: </label>
                                <label class="detail">{{vipham.decision_sapo}}</label>
                            </div>
                        <div class="col-md-6 float-left">
                            <label>Quy Trình: </label>
                            <label class="detail">{{quytrinh.name}}</label>
                        </div>
                    </div> 
                </div>   
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                        <div class="col-md-6 float-left">
                            <label>Người vi phạm: </label>
                            <label class="detail">{{vipham.user_name}}</label>
                        </div>
                        <div class="col-md-6 float-left">
                            <label>Người tạo: </label>
                            <label class="detail">{{vipham.creator}}</label>
                        </div>
                    </div> 
                </div>
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                        <div class="col-md-6 float-left">
                            <label>Tên vi phạm: </label>
                            <label class="detail">{{vipham.tenvipham}}</label>
                        </div>
                        <div class="col-md-6 float-left">
                            <label>Bộ Phận: </label>
                            <label class="detail" v-for="bp in role_name">
                                <span class="badge badge-info">{{bp.name}}</span>
                            </label>
                        </div>
                    </div> 
                </div>
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                        <div class="col-md-6 float-left">
                           <label>Vi phạm lần thứ: </label>
                           <label class="detail">{{vipham.solan}}</label>
                         </div>
                        <div class="col-md-6 float-left">
                            <label>Tình trạng: </label>
                            <label class="detail">{{vipham.danhgia}}</label>
                        </div>
                    </div> 
                </div>
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                        <div class="col-md-6 float-left">
                            <label>Chế tài: </label>
                            <label class="detail">{{vipham.chetai}}</label>
                        </div>
                        <div class="col-md-6 float-left">
                            <label>Ngày áp dụng: </label>
                            <label class="detail">{{vipham.timeapply}}</label>
                        </div>
                    </div> 
                </div>
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                      <div class="col-md-6 float-left">
                          <label>Ngày vi phạm: </label>
                           <label class="detail">{{vipham.ngayvipham}}</label>
                      </div>
                      <div class="col-md-6 float-left">
                          <label>Ngày tạo: </label>
                           <label class="detail">{{vipham.created_at | formatDate}}</label>
                      </div>
                    </div> 
                </div>
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                      <div class="col-md-6 float-left">
                          <label>Ngày vi phạm: </label>
                           <label class="detail">{{vipham.ngayvipham}}</label>
                      </div>
                      <div class="col-md-6 float-left">
                          <label>Tiền phạt: </label>
                           <label class="detail">{{vipham.tienphat}}</label>
                      </div>
                    </div> 
                </div>
                <div class="form-row"> 
                    <div class="form-row col-md-12">
                        <div class="col-md-12 float-left">
                            <label>Tường trình: </label>
                            <label class="detail" style="white-space: pre-line;">{{vipham.tuongtrinh}}</label>
                        </div>
                    </div>
                    <div class="col-md-6 float-left">
                            <label>Nhân viên phê duyệt </label>
                            <label class="detail" v-for="nv in vipham.nvpheduyet">
                                <span class="badge badge-info">{{nv.name}}</span>
                            </label>
                        </div>
                </div>

                <div class="form-row">
                    <a v-if="vipham.images != null || vipham.videos != null" href="#" @click="fileDetail()">Xem file chứng minh</a>
                </div>
                <div id="fileDetail">
                    <div class="form-row"> 
                        <div class="form-row col-md-12">
                            <label>Ảnh: </label>
                            <br>
                            <div v-for="image in vipham.images" :key="vipham.id">
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
                            <div v-for="video in vipham.videos" :key="vipham.id">
                                <video width="530" height="400" controls>
                                  <source v-bind:src="video" type="video/mp4">
                                  Your browser does not support the video tag.
                                </video>
                            </div>
                        </div> 
                      </div>
                </div>

                    <div class="modal-footer">
                        <router-link :to="{ name: 'previewbienban', params: { vipham_id: vipham.id }}" data-dismiss="modal" v-if="bienbanmode == true" class="btn btn-primary btn-block">Tạo biên bản</router-link>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="hideFileDetail()">Close</button>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <!-- Phe duyet -->
        <div class="modal  fade" id="pheduyetDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <a href="#" v-bind:href="'/downloadPdf/' + vipham.bienban">Download biên bản</a>  
                            <!-- <a href="#" @click="downloadbienban(vipham)">Download biên bản</a>                         -->
                        </div> 
                      </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary pheduyet" href="#" @click="duyet(vipham)" data-dismiss="modal">Duyệt</a> 
                        <a class="btn btn-danger pheduyet" href="#" @click="khongduyet(vipham)" data-dismiss="modal">Không duyệt</a>  
                    </div>
                </div>
                </div>
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


export default {
    data() {
        return {
            opens: "center",
            editmode: false,
            bienbanmode: false,
            viphams: {},
            roles: [],
            role_name:{},
            users: [],
            user: [],
            nvpheduyets: [],
            roleids:[],
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
                role: [],
                ngayvipham: '',
                danhgia: '',
                lapbienban: '',
                tuongtrinh : '',
                files: [],
                linkvideos: [],
                solan: '',
                nvpheduyet: [],
                roleid:[],
                timeapply: '',
                tienphat: '',
                tenvipham: '',
            }),
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
            ranges: { //default value for ranges object (if you set this to false ranges will no be rendered)
                'P1': [moment('2019-01-01').endOf('week').isoWeekday(1), moment('2019-01-27').endOf('week')],
                'P2': [moment('2019-01-28').endOf('week').isoWeekday(1), moment('2019-02-24').endOf('week')],
                'P3': [moment('2019-02-25').endOf('week').isoWeekday(1), moment('2019-03-24').endOf('week')],
                'P4': [moment('2019-03-25').endOf('week').isoWeekday(1), moment('2019-04-21').endOf('week')],
                'P5': [moment('2019-04-22').endOf('week').isoWeekday(1), moment('2019-05-19').endOf('week')],
                'P6': [moment('2019-05-20').endOf('week').isoWeekday(1), moment('2019-06-16').endOf('week')],
                'P7': [moment('2019-06-17').endOf('week').isoWeekday(1), moment('2019-07-14').endOf('week')],
                'P8': [moment('2019-07-15').endOf('week').isoWeekday(1), moment('2019-08-11').endOf('week')],
                'P9': [moment('2019-08-12').endOf('week').isoWeekday(1), moment('2019-09-08').endOf('week')],
                'P10': [moment('2019-09-09').endOf('week').isoWeekday(1), moment('2019-10-06').endOf('week')],
                'P11': [moment('2019-10-09').endOf('week').isoWeekday(1), moment('2019-11-03').endOf('week')],
                'P12': [moment('2019-11-04').endOf('week').isoWeekday(1), moment('2019-12-01').endOf('week')],
                'P13': [moment('2019-12-02').endOf('week').isoWeekday(1), moment('2019-12-29').endOf('week')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
            }
        }
    },
    methods: {
        clear() {
            this.images = '';
            this.videolinks = [{
                    link: ''
                }];
            document.getElementById("file").value = "";
        },
        add(index) {
            this.videolinks.push({ link: '' });
        },
        remove(index) {
            this.videolinks.splice(index, 1);
        },
        handleFileUpload() {
            this.images = this.$refs.file.files;
            this.form.files = this.$refs.file.files;
        },
        hideFileDetail() {
            document.getElementById('fileDetail').style.display = "none";
        },
        downloadbienban(vipham) {
            // console.log(vipham);
            axios.get('api/downloadPdf/' + vipham.bienban);
        },
        pheduyet(vipham) {
            this.vipham = vipham;
            $('#pheduyetDetailCenter').modal({backdrop: 'static', keyboard: false});
        },
        duyet(vipham) {
            axios.get('api/duyet/' + vipham.id).then(() => {
                this.loadViPhams();
            });
        },
        khongduyet(vipham) {
            axios.get('api/khongduyet/' + vipham.id).then(() => {
                this.loadViPhams();
            });
        },updateValues(values) {
            this.$parent.startDate = values.startDate.toISOString().slice(0, 10)
            // this.$parent.startDate = moment(this.$parent.startDate).add(1, 'day').format().slice(0,10);
            this.$parent.endDate = values.endDate.toISOString().slice(0, 10)
            // this.$parent.endDate = moment(this.$parent.endDate).endOf('week').format().slice(0,10);
            this.loadViPhams();
            // this.addWeek();
        },
          addWeek() {
            this.p = [];
            var date = moment(this.endDate).endOf('week').format().slice(0, 10);
            this.p.push(date);
            this.p.push(moment(date).subtract(1, 'week').format().slice(0, 10));
            this.p.push(moment(date).subtract(2, 'week').format().slice(0, 10));
            this.p.push(moment(date).subtract(3, 'week').format().slice(0, 10));
        },
          onChange(event) {
            this.loadViPhams();
            // console.log(event.target.value)
        },
        reSelectnhanvien() {
            this.$parent.saleSelectednhanvien = [];
            this.loadViPhams();
            // console.log(this.$parent.saleSelectednhanvien);

        },
        reSelectbophan() {
            this.$parent.saleSelectedbophan = [];
            this.loadViPhams();
        },
        timTheonhanvien() {
            this.loadViPhams();
        },
        timTheobophan() {
            this.loadViPhams();
        },
        loadViPhams(page = 1) {

            let dates_para = queryString.stringify({ sdates: [this.$parent.startDate.slice(0, 10), this.$parent.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            // console.log(dates_para);
            let query = queryString.stringify({ status: this.statuss });
            // console.log(query);
            // let queryName = queryString.stringify({name: this.$parent.search});
            // axios.get("/api/users?page=" + page + '&' + queryName).then(({ data }) => (this.users = data));
            let nhanvien = this.$parent.saleSelectednhanvien.id;

            // console.log(this.$parent.saleSelectednhanvien);
            if(nhanvien){
                var nv = nhanvien;
            }else {
                var nv = 0;
            }
            let bophan = this.$parent.saleSelectedbophan.id;
            if(bophan){
                var bp = bophan;
            }else {
                var bp = 0;
            }
            let queryName = queryString.stringify({tenvipham: this.$parent.search});
            axios.get("/api/vipham?page=" + page + '&' + queryName + '&' + query + '&nhanvien=' + nv + '&' + '&bophan=' + bp +'&' + dates_para).then(({ data }) => (this.viphams = data));
             // console.log();
        },
        createViPham() {
            let formData = new FormData();
            if(this.images.length > 0) {
                for (var i = 0; i < this.images.length; ++i) {
                    formData.append('images[]', this.images[i]);
                }
            }
            if(this.videolinks.length > 0) {
                for (var i = 0; i < this.videolinks.length; ++i) {
                    formData.append('videos[]', this.videolinks[i].link);
                }
            }
            if(this.form.nvpheduyet.length > 0) {
                for (var i = 0; i < this.form.nvpheduyet.length; ++i) {
                    formData.append('nvpheduyet[]', parseInt(this.form.nvpheduyet[i]['id']));
                    // console.log(parseInt(this.form.nvpheduyet[i]['id']));
                }
            }
            if(this.form.roleid.length > 0) {
                for (var i = 0; i < this.form.roleid.length; ++i) {
                    formData.append('roleid[]', parseInt(this.form.roleid[i]['id']));
                    // console.log(parseInt(this.form.roleid[i]['id']));
                }
            }
            if(this.form.user == "" || this.form.user == null) {
                formData.append('userid', null);
            } else {
                formData.append('userid', this.form.user['id']);
            }
            formData.append('tenvipham', this.form.tenvipham);
            formData.append('tienphat', this.form.tienphat);
            formData.append('danhgia', this.form.danhgia);
            formData.append('solan', this.form.solan);
            formData.append('decisionid', this.$refs.decision.value.id);
            // formData.append('userid', this.$refs.user.value.id);
            // formData.append('roleid', this.$refs.role.value.id);
            formData.append('ngayvipham', this.$refs.ngayvipham.value);
            formData.append('timeapply',this.form.timeapply);
            formData.append('detail', this.$refs.tuongtrinh.value);

            this.$Progress.start();

            axios.post('/api/vipham',formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
            .then(() => {
                $('#createModalCenter').modal('hide');
                this.loadViPhams();
                this.$Progress.finish();
                this.images = '';
                this.videolinks = [{
                        link: ''
                    }];
                document.getElementById("file").value = "";
            })
            .catch();
        },
        fileDetailCenter(vipham) {
            this.vipham = vipham;
            $('#viphamDetailCenter').modal('hide');
            $('#fileDetailCenter').modal({backdrop: 'static', keyboard: false});
        },
        fileDetail() {
             document.getElementById('fileDetail').style.display = "flex";
        },
        editViPham() {
            let formData = new FormData();
            if(this.images.length > 0) {
                for (var i = 0; i < this.images.length; ++i) {
                    formData.append('images[]', this.images[i]);
                }
            }
            if(this.videolinks.length > 0) {
                for (var i = 0; i < this.videolinks.length; ++i) {
                    formData.append('videos[]', this.videolinks[i].link);
                }
            }
            if(this.form.nvpheduyet.length > 0) {
                for (var i = 0; i < this.form.nvpheduyet.length; ++i) {
                    formData.append('nvpheduyet[]', this.form.nvpheduyet[i]['id']);
                    // console.log(this.form.nvpheduyet[i]['id']);
                }
            }
            if(this.form.roleid.length > 0) {
                for (var i = 0; i < this.form.roleid.length; ++i) {
                    formData.append('roleid[]', parseInt(this.form.roleid[i]['id']));
                    // console.log(parseInt(this.form.roleid[i]['id']));
                }
            }
            // console.log(this.form.user);
            if(this.form.user == "" || this.form.user == null ) {
                formData.append('userid', null);
            } else {
                formData.append('userid', this.form.user['id']);
            }

            formData.append('tenvipham', this.form.tenvipham);
            formData.append('tienphat', this.form.tienphat);
            formData.append('id', this.form.id);
            formData.append('danhgia', this.form.danhgia);
            formData.append('solan', this.form.solan);
            formData.append('decisionid', this.$refs.decision.value.id);
            formData.append('ngayvipham', this.$refs.ngayvipham.value);
            formData.append('timeapply',this.form.timeapply);
            formData.append('detail', this.$refs.tuongtrinh.value);

            this.$Progress.start();
            for(var pair of formData.entries()) {
               console.log(pair[0]+ ', '+ pair[1]+ ', '+ pair[2]+ ', '+ pair[3]+ ', '+ pair[4]); 
            }

            axios.post('/api/updateViPham', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
            .then(() => {
                $('#createModalCenter').modal('hide');
                this.loadViPhams();
                this.$Progress.finish();
                this.images = '';
                this.videolinks = [{
                        link: ''
                    }];
                document.getElementById("file").value = ""; 
            })
            .catch();
        },
        sendmail(vipham) {
            axios.get('/api/sendEmail/' + vipham.id).then(() => {
                this.loadViPhams();
            });
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#createModalCenter').modal({backdrop: 'static', keyboard: false});
        },
        editModal(vipham) {
            // console.log(vipham);
            this.editmode = true;
            this.form.reset();
            $('#createModalCenter').modal({backdrop: 'static', keyboard: false});
            this.form.fill(vipham);
        },
        viphamDetailCenter(vipham) {
            this.decision = vipham.decision;
            this.quytrinh = vipham.quytrinh;
            this.vipham = vipham;
            // console.log(vipham);
            $('#viphamDetailCenter').modal({backdrop: 'static', keyboard: false});
        },
        notbienbanmode(vipham) {
            this.bienbanmode = false;
            this.vipham = vipham;
            this.role_name = vipham.roleid;
            // console.log(this.vipham);
            this.viphamDetailCenter(vipham);
        },
        isbienbanmode(vipham) {
            this.bienbanmode = true;
            this.vipham = vipham;
            this.role_name = vipham.roleid;
            this.viphamDetailCenter(vipham);
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
        deleteModal(vipham) {
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
                    this.form.delete('api/vipham/' + vipham.id).then(() => {
                        this.loadViPhams();
                        this.$Progress.finish();
                    }).catch();
                }
            })
        }
    },
    created() {
        this.loadViPhams();
        Fire.$on('AfterCreate', () => {
            this.loadViPhams();
        });
        Fire.$on('searching', () => {
            this.loadViPhams();
        });
        axios.get('/api/getAllUsers').then(({ data }) => (this.users = data));
        axios.get('/api/getAllUsers').then(({ data }) => (this.nvpheduyets = data));
        axios.get('api/getQuyDinh').then(({ data }) => (this.decisions = data));
        axios.get("/api/getallobjectroles").then(({ data }) => (this.roles = data));
        // console.log(this.users);
        // console.log(this.roles);

    }
}

</script>
