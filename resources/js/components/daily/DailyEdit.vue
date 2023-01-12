<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Sửa khách hàng tiềm năng</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>Chức danh</label>
                                <select class="form-control" v-model="form.title">
                                    <option disabled value="">Chọn</option>
                                    <option>Anh</option>
                                    <option>Chị</option>
                                    <option>Cô</option>
                                    <option>Bác</option>
                                    <option>Ông</option>
                                    <option>Bà</option>
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tên Đại Lý</label>
                                <input v-model="form.name" type="text" class="form-control" placeholder="Tên Đại Lý*" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group col-md-3">
                                <label> Đại Diện</label>
                                <input v-model="form.last_name" type="text" class="form-control" placeholder="Tên Đại Diện*" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                <has-error :form="form" field="last_name"></has-error>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Diện Tích</label>
                                <input v-model="form.dientich" type="text" class="form-control" placeholder="Diện Tích">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Số điện thoại</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input v-model="form.phone" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                                    <has-error :form="form" field="phone"></has-error>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input v-model="form.email" type="email" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tỉnh / Thành phố</label>
                                <multiselect v-model="form.city" :options="cities" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn tỉnh/ thành phố"></multiselect>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Ngày</label>
                                <input v-model="form.start_date" type="date" class="form-control">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Sản phẩm (quan tâm)</label>
                                <multiselect v-model="form.products" :options="products" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn sản phẩm" label="name" track-by="id" :preselect-first="true">
                                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} sản phẩm đã chọn</span></template>
                                </multiselect>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Địa chỉ</label>
                                <input v-model="form.address" type="text" class="form-control" placeholder="Địa chỉ" :class="{ 'is-invalid': form.errors.has('address') }">
                                <has-error :form="form" field="address"></has-error>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Phân Tích Đại Lý</label>
                                <input v-model="form.description" type="text" class="form-control">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Mô tả cuộc trao đổi</label>
                                <input v-model="form.mota" type="text" class="form-control">
                            </div>
                     <!--        <div class="form-group col-md-4">
                                <label>Giờ</label>
                                <input class="form-control" type="time" v-model="form.start_time">
                            </div> -->
                        </div>
                        <br>
                        <h5 class="card-title">Ghi chú</h5>
                        <div class="form-group">
                            <textarea v-model="form.note" type="text" class="form-control" placeholder="Nhập ghi chú về khách hàng tiềm năng"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a @click="cancelContact" class="btn btn-default">Hủy</a>
                        <VueLoadingButton aria-label="Post message" class="btn btn-primary" @click.native="updateContact" :styled="false">Cập nhật</VueLoadingButton>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['id'],

    data() {
        return {
            form: new Form({
                id: '',
                title: '',
                name: '',
                last_name: '',
                email: '',
                phone: '',
                dientich: '',
                address: '',
                city: '',
                description: '',
                start_date: '',
                start_time: '',
                end_time: '',
                costcenters: [],
                salesarea: [],
                mota: '',
                diemroi: '',
                products: [],
                // status: 1,
                note: '',
                chiendich:'',
                giaidoan:''
            }),
            costcenters: [],
            chiendich: [],
            salesareas: [],
            diemroi: [],
            cities: [],
            products: []
        }
    },
    methods: {
        cancelContact() {
            this.$router.push({ name: 'ttkhachdaily', query: { id: this.form.id } })
        },
        updateContact() {
            this.form.put('/api/daily/' + this.form.id)
                .then(() => {
                    this.$router.go(-1);
                    //this.$router.push({ name: 'ttkhachtiemnang', params: { id: this.form.id }})
                })
                .catch(error => {
                    console.log(error.response.data.message);
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: error.response.data.message,
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });
        }
    },

    created() {
        axios.get("/api/daily/" + this.id)
            .then(response => {
                this.form.fill(response.data.data);
            })
            .catch(() => this.$router.go(-1));
        axios.get("/api/costcenters-list")
            .then(response => {
                this.costcenters = response.data;
            });
        axios.get('api/salesareas-list')
            .then(response => {
                this.salesareas = response.data;
            });
        axios.get('api/cities')
            .then(response => {
                this.cities = response.data;
            });
        axios.get('api/products-list')
            .then(response => {
                this.products = response.data;
            });
        }
   
}

</script>
