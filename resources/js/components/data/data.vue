<template>
    <div class="">
        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Khách Hàng</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên</th>
                                    <th>phone</th>
                                    <th>Trạng thái</th>
                                    <th>Thông tin khác</th>
                                </tr>
                                <tr v-for="(data, index) in datas.data" :key="data.id">
                                    <td>{{index}}</td> 
                                    <td><span v-if="data.kh15 <= 0">{{data.name}}</span>
                                    	<span v-if="data.kh15 > 0 ">
	                                    	<router-link :to="{ name: 'kh15Show', query: { id: data.kh15 }}">
											{{ data.name }}
						                    </router-link>
					                    </span>
                                    </td>
                                    <td><a href="#" @click="call(data)">{{data.phone}}</a></td>
                                    <td>
                                    	<span v-if="data.type == 0">Từ Chối</span>
                                    	<span v-if="data.type == 1">Chưa Nghe Máy</span>
                                    	<span v-if="data.type == 2">Quan Tâm</span>
                                    	<span v-if="data.type == 4">Không ở Khu Vực Data</span>
                                    	<span v-if="data.type == 3">Có Nhu Cầu</span>
                                    </td>
                                    <td>{{data.address}}</td>
                                    <!-- <td>
                                        <a href="#" @click="editModal(data)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteUser(data)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td> -->
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="datas" @pagination-change-page="loadData" :pageSize="5" :limit="5"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="showData" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập Nhật Trạng Thái</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="Update()">
                        <div class="modal-body">
	                        <span>Khách Hàng: <i>{{data.name}}</i></span><br>
	                        <span>Số Điện Thoại: <i>{{data.phone}}</i></span><br>
                            <div class="form-group">
                                <input type="hidden" value="data.id" v-model="form.id">
                                <input type="hidden" value="data.phone" v-model="form.phone">
                                <input type="hidden" value="data.name" v-model="form.name">
                                <input type="hidden" value="data.kv_id" v-model="form.kv_id">
                                <input type="radio" id="tu-choi" value="0" v-model="form.picked">
								<label for="tu-choi">Từ Chối</label><br>
								<input type="radio" id="chua-nghe-may" value="1" v-model="form.picked">
								<label for="chua-nghe-may">Chưa Nghe Máy</label><br>
								<input type="radio" id="quan-tam" value="2" v-model="form.picked">
								<label for="quan-tam">Quan Tâm</label><br>
								<input type="radio" id="co-nhu-cau" value="3" v-model="form.picked">
								<label for="co-nhu-cau">Có Nhu Cầu</label><br>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>      
    </div>
</template>
<script>
export default {
    data() {
        return {
            datas: {},
            data: [],
            pagedata: '',
            // Create a new form instance
            form: new Form({
                id: '',
                picked: '',
                phone: '',
                name: '',
                note: '',

            }),
        }
    },
    methods: {
        loadData(page = 1) {
        	// console.log(page);
        	this.pagedata = page;
            let queryName = queryString.stringify({name: this.$parent.search});
            axios.get("/api/data?page=" + page + '&' + queryName).then(({ data }) => (this.datas = data));
        },
        call(data){
            this.data = data;
		    window.location = 'tel:0'+data.phone;
            $('#showData').modal('show');
            this.form.id = data.id;
            this.form.name = data.name;
            this.form.phone = data.phone;
            this.form.note = data.note;
            this.form.kv = data.kv;
		},
		Update(){
			this.form.put('/api/data/' + this.form.id)
            .then(response => {
                this.loadData(this.pagedata);
	            $('#showData').modal('hide');

            });
		}
    },
    created() {
        this.loadData();
        Fire.$on('AfterCreate', () => {
            this.loadData();
        });
        Fire.$on('searching', () => {
            this.loadData();
        })
    }
}

</script>

