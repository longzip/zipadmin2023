
<template>
    <div class="">
        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Quy Định</h3>
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
                                </tr>
                                <tr v-for="user in decisionfiltered" :key="user.id">
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
            decisionfiltered: [],
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
            axios.get("/api/decision?page=" + page + '&' + queryName)
                .then(({ data }) => (this.users = data))
                .then(()=> {
                    this.decisionfiltered = [];
                    for(let i = 0; i < this.users.data.length; ++i) {
                        if(this.users.data[i].id_ma != null) {
                            this.decisionfiltered.push(this.users.data[i]);
                        }
                    }
                });

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

