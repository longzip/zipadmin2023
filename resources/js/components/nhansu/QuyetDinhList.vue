
<template>
    <div class="">
        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quy Định</h3>
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
                                    <th>Quy trình </th>
                                    <!-- <th>Chế Tài</th> -->
                                    <!-- <th>Nội Dung</th> -->
                                    <th>Bộ phận áp Dụng</th>
                                    <th>Bộ phận GS</th>    
                                    <th>QDGS</th>      
                                  <!--  <th>Quy tắc giám sát</th> -->
                                  <!--  <th>Ngày Áp Dụng</th> -->
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="user in users.data" :key="user.id">
                                    <!-- <td>{{user.id}}</td> -->
                                  <!--   <td><span v-if="user.stt < 10">00</span><span v-if="10 < user.stt && user.stt < 100">0</span>{{user.stt}}</td> -->
                                    <td><a href="#" @click="userDetailCenter(user)"><span v-if="user.stt < 10">00</span><span v-if="10 <= user.id && user.id < 100">0</span>{{user.id}}</a></td>
                                   <td> {{user.sapo}}</td>
                                   <td><a href="#" @click="procedureDetailCenter(user)">{{user.name}}</a></td>
                                   <!-- <td>{{user.name_chetai}}</td> -->
                                   <td>
                                        <span v-for="role in user.roles" class="badge badge-info">{{role}}</span> &nbsp;
                                   </td>
                                   <td>
                                        <span v-for="giamsat in user.giamsat" class="badge badge-info">{{giamsat}}</span> &nbsp;
                                   </td>
                                   <td><a href="#" @click="qdgsDetaiCenter(user)">{{user.masapo}}</a></td>
                                 <!--   <td>{{user.date | myDate}}</td> -->
                                  <!--  <td>{{user.monitoring}}</td> -->
                                    <td>
                                        <a href="#" @click="editModal(user)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        <a href="#" @click="deleteUser(user)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="users" @pagination-change-page="loadUsers"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="userModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm Quy Định</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa Quy Định</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="reset()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                             <div class="form-group">
                                <label>Tên Quy Định</label>
                                <input v-model="form.sapo" type="sapo" name="sapo" placeholder="Nhập tên quy định" class="form-control" :class="{ 'is-invalid': form.errors.has('sapo') }">
                                <has-error :form="form" field="sapo"></has-error>
                            </div>
                           <div class="form-group">
                               <label>Quy trình</label>
                                <multiselect v-model="form.quytrinh" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn quy trình" :options="quytrinh" :searchable="true" :allow-empty="false">
                                    <template slot="singleLabel" slot-scope="{ option }">
                                        <strong>{{ option.name }}</strong> 
                                    </template>
                                </multiselect>
                            </div>

                            <div class="form-group">
                                <label>Nội Dung</label>
                                <textarea rows='10' style="white-space: pre-line;" v-model="form.detail" type="detail" name="detail" placeholder="Nhập Nội Dung" class="form-control" :class="{ 'is-invalid': form.errors.has('detail') }"></textarea> 
                                <has-error :form="form" field="detail"></has-error>
                            </div>
                            
                            <div class="col-md-12">
                              <div class="row row1">
                                <div class="form-group col-md-3">
                                    <label>Số lần</label>
                                    <br>
                                    <label style="font-size: larger; margin-top: 5px;">Một</label>
                                </div>
                                <div class="form-group col-md-9 ">
                                  <label>Mã chế tài</label>
                                  <multiselect v-model="form.chetai1" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn chế tài" :options="chetai" :searchable="true" :allow-empty="false">
                                      <template slot="singleLabel" slot-scope="{ option }">
                                          <strong>{{ option.name }}</strong> 
                                      </template>
                                  </multiselect>
                                </div>

                              </div>

                              <div class="row row2">
                                <div class="form-group col-md-3">
                                    <label>Số lần</label>
                                    <br>
                                    <label style="font-size: larger; margin-top: 5px;">Hai</label>
                                </div>
                                <div class="form-group col-md-9">
                                  <label>Mã chế tài</label>
                                  <multiselect v-model="form.chetai2" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn chế tài" :options="chetai" :searchable="true" :allow-empty="false">
                                      <template slot="singleLabel" slot-scope="{ option }">
                                          <strong>{{ option.name }}</strong> 
                                      </template>
                                  </multiselect>
                                </div>
                              </div>

                              <div class="row row3">
                                <div class="form-group col-md-3">
                                    <label>Số lần</label>
                                    <br>
                                    <label style="font-size: larger; margin-top: 5px;">Ba</label>
                                </div>
                                <div class="form-group col-md-9">
                                  <label>Mã chế tài</label>
                                  <multiselect v-model="form.chetai3" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn chế tài" :options="chetai" :searchable="true" :allow-empty="false">
                                      <template slot="singleLabel" slot-scope="{ option }">
                                          <strong>{{ option.name }}</strong> 
                                      </template>
                                  </multiselect>
                                </div>
                              </div>

                              <div class="row row4">
                                <div class="form-group col-md-3">
                                    <label>Số lần</label>
                                    <br>
                                    <label style="font-size: larger; margin-top: 5px;">Bốn</label>
                                </div>
                                <div class="form-group col-md-9">
                                  <label>Mã chế tài</label>
                                  <multiselect v-model="form.chetai4" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn chế tài" :options="chetai" :searchable="true" :allow-empty="false">
                                      <template slot="singleLabel" slot-scope="{ option }">
                                          <strong>{{ option.name }}</strong> 
                                      </template>
                                  </multiselect>
                                </div>
                              </div>

                              <div class="row row5">
                                <div class="form-group col-md-3">
                                    <label>Số lần</label>
                                    <br>
                                    <label style="font-size: larger; margin-top: 5px;">Năm</label>
                                </div>
                                <div class="form-group col-md-9">
                                  <label>Mã chế tài</label>
                                  <multiselect v-model="form.chetai5" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn chế tài" :options="chetai" :searchable="true" :allow-empty="false">
                                      <template slot="singleLabel" slot-scope="{ option }">
                                          <strong>{{ option.name }}</strong> 
                                      </template>
                                  </multiselect>
                                </div>
                              </div>
                            </div>

                            <div class="col-md-12">
                                <div class="float-right col-md-3">
                                    <a @click="addinfo2(form)" class="btn btn-sm btn-info add2" v-on:click="counter += 1">Thêm chế tài</a>
                                </div>
                                <div class="float-right col-md-3">
                                    <a @click="deleteinfo2(form)" class="btn btn-sm btn-info minus2" v-on:click="counter -= 1">Bỏ chế tài</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Bộ phận Áp Dụng</label>
                                <multiselect v-model="form.roles" tag-placeholder="Thêm vai trò" placeholder="Chọn bộ phận áp dụng" :options="roles" :multiple="true" :taggable="true"></multiselect>
                            </div>
                            <div class="form-group">
                                <label>Bộ phận Giám Sát</label>
                                <multiselect v-model="form.giamsat" tag-placeholder="Thêm vai trò" placeholder="Chọn bộ phận giám sát" :options="giamsat" :multiple="true" :taggable="true"></multiselect>
                            </div>
                          <div class="form-group">
                                <label>Mã Quy định Giám sát</label>
                                <multiselect v-model="form.maqdgs" track-by="id" label="sapo" placeholder="Chọn Quy định Giám sát" :options="codes" :searchable="true" :allow-empty="false">
                                    <template slot="singleLabel" slot-scope="{ option }">
                                        <strong>{{ option.sapo}}</strong> 
                                    </template>
                                </multiselect>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" @click="reset()">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success" @click="reset()">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary" @click="reset()">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal  fade" id="userDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                  <label class="detail" v-model="user.sapo">{{user.sapo}}</label>
                                  
                              </div>
                              <div class="col-md-6">
                                  <label>Quy Trình: </label>
                                   <label class="detail" v-model="user.name">{{user.name}}</label>
                              </div>
                            </div> 
                          </div>   
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Nội Dung: </label>
                                <br>
                                 <label style="white-space: pre-line;" class="detail" v-model="user.detail">{{user.detail}}</label>
                            </div>
                          </div>
                           
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Bộ Phận Áp Dụng: </label>
                                <span v-for="role in user.roles" class="badge badge-info">{{role}}</span> &nbsp;
                            </div>
                          </div>
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Bộ Phận Giám Sát: </label>
                                <span v-for="gs in user.giamsat" class="badge badge-info">{{gs}}</span> &nbsp;
                            </div>
                          </div>
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Quy Định Giám Sát: </label>
                                 <label v-model="user.monitoring">{{user.monitoring}}</label>
                            </div>
                          </div>
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                               <tr>
                                    <th class="punishment-table">Lần 1</th>
                                    <th class="punishment-table" v-if="user.chetai2_data != null">Lần 2</th>
                                    <th class="punishment-table" v-if="user.chetai3_data != null">Lần 3</th>
                                    <th class="punishment-table" v-if="user.chetai4_data != null">Lần 4</th>
                                    <th class="punishment-table" v-if="user.chetai5_data != null">Lần 5</th>
                                </tr>
                                    <td class="punishment-table">{{user.chetai1_data}}</td>
                                    <td class="punishment-table" v-if="user.chetai2_data != null">{{user.chetai2_data}}</td>
                                    <td class="punishment-table" v-if="user.chetai3_data != null">{{user.chetai3_data}}</td>
                                    <td class="punishment-table" v-if="user.chetai4_data != null">{{user.chetai4_data}}</td>
                                    <td class="punishment-table" v-if="user.chetai5_data != null">{{user.chetai5_data}}</td>
                            </div>
                          </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    </div>
                </div>
        </div>
        <div class="modal  fade" id="procedureDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Quy trình</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                          <div class="form-row"> 
                            <div class="form-row col-md-12">
                              <div class="col-md-6">
                                  <label>Tên Quy trình: </label>
                                  <label class="detail">{{quytrinhname}}</label>
                                  
                              </div>
                              <div class="col-md-6">
                                  <label>Ký hiệu: </label>
                                   <label class="detail">{{quytrinhcode}}</label>
                              </div>
                            </div> 
                          </div>   
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Link: </label>
                                <br>
                                 <label class="detail"><a href="#">{{quytrinhlink}}</a></label>
                            </div>
                          </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    </div>
                </div>
        </div>

         <div class="modal  fade" id="qdgsDetaiCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Quy định Giám sát</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                        <div class="form-row"> 
                          <div class="form-row col-md-12">
                            <div class="col-md-6">
                                <label>Tên Quy định: </label>
                                <label class="detail">{{qdgs.sapo}}</label>
                                
                            </div>
                            <div class="col-md-6">
                                <label>Quy Trình: </label>
                                 <label class="detail">{{qdgs.name}}</label>
                            </div>
                          </div> 
                        </div>   
                        <div class="form-row">     
                          <div class="form-group col-md-12">
                              <label>Nội Dung: </label>
                              <br>
                               <label style="white-space: pre-line;" class="detail">{{qdgs.detail}}</label>
                          </div>
                        </div>
                         
                        <div class="form-row">     
                          <div class="form-group col-md-12">
                              <label>Bộ Phận Áp Dụng: </label>
                              <span v-for="role in qdgs.roles" class="badge badge-info">{{role}}</span> &nbsp;
                          </div>
                        </div>
                        <div class="form-row">     
                          <div class="form-group col-md-12">
                              <label>Bộ Phận Giám Sát: </label>
                              <span v-for="gs in qdgs.giamsat" class="badge badge-info">{{gs}}</span> &nbsp;
                          </div>
                        </div>
                        <div class="form-row">     
                          <div class="form-group col-md-12">
                              <label>Quy Định Giám Sát: </label>
                               <label>{{qdgs.monitoring}}</label>
                          </div>
                        </div>
                        <div class="form-row">     
                          <div class="form-group col-md-12">
                             <tr>
                                  <th class="punishment-table">Lần 1</th>
                                  <th class="punishment-table" v-if="qdgs.chetai2_data != null">Lần 2</th>
                                  <th class="punishment-table" v-if="qdgs.chetai3_data != null">Lần 3</th>
                                  <th class="punishment-table" v-if="qdgs.chetai4_data != null">Lần 4</th>
                                  <th class="punishment-table" v-if="qdgs.chetai5_data != null">Lần 5</th>
                              </tr>
                                  <td class="punishment-table">{{qdgs.chetai1_data}}</td>
                                  <td class="punishment-table" v-if="qdgs.chetai2_data != null">{{qdgs.chetai2_data}}</td>
                                  <td class="punishment-table" v-if="qdgs.chetai3_data != null">{{qdgs.chetai3_data}}</td>
                                  <td class="punishment-table" v-if="qdgs.chetai4_data != null">{{qdgs.chetai4_data}}</td>
                                  <td class="punishment-table" v-if="qdgs.chetai5_data != null">{{qdgs.chetai5_data}}</td>
                          </div>
                        </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      </div>
                  </div>
                  </div>
              </div>
        </div>
   </div>
</template>

<style type="text/css">
  .row2, .row3, .row4, .row5 {
    display: none;
  }
  .minus2 {
    display: none;
  }
</style>

<script>
export default {
    data() {
        return {
            editmode: false,
            users: {},
            user: [],
            roles: [],
            giamsat: [],
            chetai: [],
            decisionpunsishment: [],
            group: [],
            quytrinh: [],
            codes: [],
            solan: [],
            counter: 0,
            qdgs:[],
            quytrinhname: [],
            quytrinhcode: [],
            quytrinhlink: [],
            // Create a new form instance
            form: new Form({
                id: '',
                quytrinh: '',
                sapo: '',
                detail: '',
                date: '',
                link: '',
                roles: [],
                group: '',
                giamsat: '',
                monitoring: '',
                maqdgs: '',
                chetai1: '',
                chetai2: '',
                chetai3: '',
                chetai4: '',
                chetai5: '',
            }),

        }
    },
    methods: {
      reset() {
        this.counter = 0;
        $(".row2").css("display","none");
        $(".row3").css("display","none");
        $(".row4").css("display","none");
        $(".row5").css("display","none");
        $(".add2").css("display","inline-block");
      },
       addinfo2(form){
          if (this.counter == 0) {
            $(".row2").css("display","flex");
            $(".minus2").css("display","inline-block");
          }

          if (this.counter == 1) {
            $(".row3").css("display","flex");
          }

          if (this.counter == 2) {
            $(".row4").css("display","flex");
          }

          if (this.counter == 3) {
            $(".row5").css("display","flex");
            $(".add2").css("display","none");
          }
        },
        deleteinfo2(form){
          if (this.counter == 4) {
            $(".row5").css("display","none");
            $(".add2").css("display","inline-block");
            form.chetai5 = '';
          }

          if (this.counter == 3) {
            $(".row4").css("display","none");
            form.chetai4 = '';
          }

          if (this.counter == 2) {
            $(".row3").css("display","none");
            form.chetai3 = '';
          }

          if (this.counter == 1) {
            $(".row2").css("display","none");
            $(".minus2").css("display","none");
            form.chetai2 = '';
          }
        },
        userDetailCenter(user) {
            this.user = user;
            for(let i = 0; i < this.users.data.length; ++i) {
                if(user.id_ma == this.users.data[i].id) {
                  this.qdgs = this.users.data[i]; 
                }
            }

            for(let i = 0; i < this.users.data.length; ++i) {
                if(user.id_ma == this.users.data[i].id) {
                  this.user.monitoring = this.users.data[i].sapo; 
                }
            }
            
            $('#userDetailCenter').modal({backdrop: 'static', keyboard: false});
        },
        qdgsDetaiCenter(user) {
            for(let i = 0; i < this.users.data.length; ++i) {
                if(user.id_ma == this.users.data[i].id) {
                  this.qdgs = this.users.data[i]; 
                }
            }

            for(let i = 0; i < this.users.data.length; ++i) {
                if(this.qdgs.id_ma == this.users.data[i].id) {
                  this.qdgs.monitoring = this.users.data[i].sapo; 
                }
            }

            $('#qdgsDetaiCenter').modal({backdrop: 'static', keyboard: false});
        },
        procedureDetailCenter(user) {
          this.user= user;
          this.quytrinhname = user.quytrinh.name;
          this.quytrinhcode = user.quytrinh.code;
          this.quytrinhlink = user.quytrinh.link;
          $('#procedureDetailCenter').modal({backdrop: 'static', keyboard: false});
        },
        loadUsers(page = 1) {
            let queryName = queryString.stringify({name: this.$parent.search});
            axios.get("/api/decision?page=" + page + '&' + queryName).then(({ data }) => (this.users = data));
        },
        createUser() {
            this.$Progress.start();
            this.form.post('/api/decision')
                .then(() => {
                    $('#userModalCenter').modal('hide');
                    this.loadUsers();
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        updateUser() {
            this.$Progress.start();
            this.form.put('api/decision/' + this.form.id)
                .then(() => {
                    // success
                    $('#userModalCenter').modal('hide');
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
    
        editModal(user) {
            this.editmode = true;
            this.form.reset();
            if (user.chetai2 !=null) {
              $(".row2").css("display","flex");
              $(".minus2").css("display","inline-block");
              this.counter = 1;
            }
            if (user.chetai3 !=null) {
              $(".row3").css("display","flex");
              this.counter = 2;
              $(".minus2").css("display","inline-block");
            }
            if (user.chetai4 !=null) {
              $(".row4").css("display","flex");
              this.counter = 3;
              $(".minus2").css("display","inline-block");
            }
            if (user.chetai5 !=null) {
              $(".row5").css("display","flex");
              $(".minus2").css("display","inline-block");
              $(".add2").css("display","none");
              this.counter = 4;
            }
            $('#userModalCenter').modal({backdrop: 'static', keyboard: false});
            this.form.fill(user);
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#userModalCenter').modal({backdrop: 'static', keyboard: false});
        },
        deleteUser(user) {
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
                    this.form.delete('api/decision/' + user.id).then(() => {
                        this.loadUsers();
                        this.$Progress.finish();
                    }).catch();
                }
            })
        },
        getName(values) {
            if (!values) return ''
            return values.map((ten, index, values) => {
                return ten.name
            })
        }

    },
    created() {

        this.loadUsers();
        Fire.$on('AfterCreate', () => {
            this.loadUsers();
        });
        Fire.$on('searching', () => {
            this.loadUsers();
        });
        axios.get("/api/getallroles").then(({ data }) => (this.roles = data));
        axios.get("/api/getallroles").then(({ data }) => (this.giamsat = data));
        axios.get("/api/getallchettai").then(({ data }) => (this.chetai = data));
        axios.get("/api/getallgroup").then(({ data }) => (this.group = data));
        axios.get("/api/getallquytrinh").then(({ data }) => (this.quytrinh = data));
        axios.get("/api/getmaquydinh").then(({ data }) => (this.codes = data));
    }
}

</script>

