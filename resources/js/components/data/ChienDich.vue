<template>
    <div class="">
        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Chiến Dịch</h3>
                        <div class="card-tools">
                            <a href="#" @click="newModal" class="btn btn-primary">Thêm </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                        	<thead>	
                                <tr>
                                    <th>Tên</th>
                                    <th>Thời Gian</th>
                                    <th>Chi Phí</th>
                                    <th>Sản Phẩm</th>
                                    <th>Tin Nhắn</th>
                                    <th>SĐT</th>
                                    <th>KHTN</th>
                                    <th>DS</th>
                                    <th>Thao Tác</th>
                                </tr>
                        	</thead>
                            <tbody>
                                <template v-for="online in onlines.data">
	                                <tr>
	                                    <td>{{online.name}}</td>
	                                    <td>{{online.time}}</td>
	                                	<td>{{online.chiphi | currency}}</td>
	                                	<td>
	                                		<template v-for="pro in online.products">
	                                			<span>{{pro.name_groups}}</span>,
	                                		</template>
	                                	</td>
	                                	<td>{{online.mess}}</td>
	                                	<td>{{online.phone}}</td>
	                                	<td>{{online.khtn}}</td>
	                                	<td>{{online.doanhthu | currency}}</td>
	                                    <td>
	                                        <a href="#" @click="editModal(online)">
	                                            <i class="fa fa-edit blue"></i>
	                                        </a>
	                                        /
	                                        <a href="#" @click="deleteonline(online)">
	                                            <i class="fa fa-trash red"></i>
	                                        </a>
	                                    </td>
	                                </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="onlineModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateonline() : createonline()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Tên Chiến Dịch</label>
                                <input v-model="form.name" type="text" name="mess" class="form-control" :class="{ 'is-invalid': form.errors.has('mess') }">
                            </div>
                            <div class="row">
	                            <div class="form-group col-4">
	                                <label>Ngày Chạy</label>
	                                <input v-model="form.start" type="date" class="form-control">
	                            </div>
	                            <div class="form-group col-4">
	                                <label>Ngày Kết Thúc</label>
	                                <input v-model="form.end" type="date" class="form-control">
	                            </div>
                                <div class="form-group col-4">
                                    <label>Chi Phí MKT</label>
                                    <input v-model="form.chiphi" type="number" class="form-control">
                                </div>
                            </div>

							<div class="row">
	                            <div class="form-group col-12">
	                                <label>Sản phẩm</label>
	                                <multiselect v-model="form.products" :options="products" :multiple="true" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn sản phẩm" label="name_groups" track-by="id" :preselect-first="true">
	                                </multiselect>
	                            </div>
	                            <div class="form-group col-3">
	                                <label>Doanh Thu</label>
	                                <input v-model="form.doanhthu" type="number" class="form-control">
	                            </div>
	                            <div class="form-group col-3">
	                                <label>KHTN</label>
	                                <input v-model="form.khtn" type="number" class="form-control">
	                            </div>
	                            <div class="form-group col-3">
	                                <label>Phone</label>
	                                <input v-model="form.phone" type="number" class="form-control">
	                            </div>
	                            <div class="form-group col-3">
	                                <label>Mess</label>
	                                <input v-model="form.mess" type="number" class="form-control">
	                            </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
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
            editmode: false,
            onlines: {},
            online: {},
            lines: [],
            sanpham: {},
            roles: [],
            name: [],
            products: [],
            // Create a new form instance
            form: new Form({
                id: '',
                name: '',
                start: '',
                end: '',
                chiphi: 0,
                soluong: '',
                products: [],
                khtn: 0,
                mess: 0,
                phone: 0,
                doanhthu:0,
            }),

        }
    },
    methods: {
       
        loadonlines(page = 1) {
            let queryName = queryString.stringify({name: this.$parent.search});
            axios.get("/api/onlinetarget?page=" + page + '&' + queryName).then(({ data }) => (this.onlines = data));
        },
        createonline() {
            this.$Progress.start();
            axios.post('/api/onlinetarget',{form:this.form})
                .then(() => {
                    $('#onlineModalCenter').modal('hide');
                    this.loadonlines();
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        updateonline() {
            this.$Progress.start();
            axios.put('api/onlinetarget/' + this.form.id,{form:this.form})
                .then(() => {
                    $('#onlineModalCenter').modal('hide');
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    );
                    this.loadonlines();
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        editModal(online) {
        	this.lines = online.sanpham;
            this.editmode = true;
            this.form.reset();
            $('#onlineModalCenter').modal('show');
            this.form.fill(online);
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#onlineModalCenter').modal('show');
        },
        deleteonline(online) {
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
                    this.form.delete('api/onlinetarget/' + online.id).then(() => {
                        swal.fire(
                            "Deleted!",
                            "Your file has been deleted.",
                            "success"
                        );
                         this.loadonlines();
                    }).catch(() => {
                        swal.fire("Failed!", "There was something wrong.", "warning");
                    });
                }
            })
        },
        getName(values) {
            if (!values) return ''
            return values.map((ten, index, values) => {
                return ten.name
            })
        },
        deleteline(key){
        	this.lines.splice(key,1);
        }

    },
    created() {
        this.loadonlines();
        Fire.$on('AfterCreate', () => {
            this.loadonlines();
        });
        Fire.$on('searching', () => {
            this.loadonlines();
        });
        axios.get('api/products-list-target')
        .then(response => {
            this.products = response.data;
        });
    }
}

</script>