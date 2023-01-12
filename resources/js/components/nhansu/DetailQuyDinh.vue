 <template>
    <div class="">
      <div class="" id="userDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div  role="document">
                <div class="">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Quy định</h5>
                    </div>
                    <div class="modal-body">
                          <div class="form-row"> 
                            <div class="form-row col-md-12">
                              <div class="col-md-6">
                                  <label>Tên Quy định: </label>
                                  <label class="detail" v-model="details.sapo">{{details.sapo}}</label>
                              </div>
                              <div class="col-md-6">
                                  <label>Quy Trình: </label>
                                   <label class="detail" v-model="details.quytrinh">{{name_quytrinh.name}}</label>
                              </div>
                            </div> 
                          </div>   
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Nội Dung: </label>
                                 <label style="white-space: pre-line;" class="detail" v-model="details.detail">{{details.detail}}</label>
                            </div>
                          </div>
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Bộ Phận Áp Dụng: </label>
                                <span v-for="role in details.roles" class="badge badge-info">{{role}}</span> &nbsp;
                            </div>
                          </div>
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Bộ Phận Giám Sát: </label>
                                <span v-for="gs in details.giamsat" class="badge badge-info">{{gs}}</span> &nbsp;
                            </div>
                          </div>
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Quy Định Giám Sát: </label>
                                 <label v-model="details.monitoring">{{details.masapo}}</label>
                            </div>
                          </div>
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                               <tr>
                                    <th>Lần 1</th>
                                    <th class="punishment-table" v-if="details.chetai2_data != null">Lần 2</th>
                                    <th class="punishment-table" v-if="details.chetai3_data != null">Lần 3</th>
                                    <th class="punishment-table" v-if="details.chetai4_data != null">Lần 4</th>
                                    <th class="punishment-table" v-if="details.chetai5_data != null">Lần 5</th>
                                </tr>
                                    <td class="punishment-table">{{details.chetai1_data}}</td>
                                    <td class="punishment-table" v-if="details.chetai2_data != null">{{details.chetai2_data}}</td>
                                    <td class="punishment-table" v-if="details.chetai3_data != null">{{details.chetai3_data}}</td>
                                    <td class="punishment-table" v-if="details.chetai4_data != null">{{details.chetai4_data}}</td>
                                    <td class="punishment-table" v-if="details.chetai5_data != null">{{details.chetai5_data}}</td>
                            </div>
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
            details:[],
            name_quytrinh:'',
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
        loadQuyDinh() {
            axios.get('api/detailQuyDinh?id='+ this.$route.query.id)
            .then(response => {
                    this.details = response.data.data[0];
                    this.name_quytrinh = this.details.quytrinh;
                    console.log(this.details);
                    this.loading = false;
                })
        },
    },
    created() {
        this.loadQuyDinh();

    }
}

</script>

