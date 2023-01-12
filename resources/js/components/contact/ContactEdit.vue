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
                            <div class="form-group col-md-6">
                                <label>Tên</label>
                                <input v-model="form.last_name" type="text" class="form-control" placeholder="Tên *">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tên Zalo</label>
                                <input disabled="true" v-model="form.first_name" type="hidden" class="form-control">
                                <input  v-model="form.zalo" type="text" class="form-control" placeholder="Tên Zalo">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Công ty</label>
                                <input v-model="form.company" type="text" class="form-control" placeholder="Công ty">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Số điện thoại</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input v-model="form.phone" type="text" class="form-control" data-inputmask='"mask": "999 999 99 99"' data-mask>
                                </div>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Điểm rơi DS</label>
                                <div class="input-group">
                                    <multiselect v-model="form.diemroi" :options="diemroi" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn điểm rơi" label="t" :preselect-first="true">
                                </multiselect>
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Email</label>
                                <input v-model="form.email" type="email" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Sản phẩm (quan tâm)</label>
                                <multiselect v-model="form.products" :options="products" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn sản phẩm" label="name" track-by="id" :preselect-first="true">
                                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} sản phẩm đã chọn</span></template>
                                </multiselect>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label>Địa chỉ</label>
                                <input v-model="form.address" type="text" class="form-control" placeholder="Địa chỉ">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tỉnh / Thành phố</label>
                                <multiselect v-model="form.city" :options="cities" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn tỉnh/ thành phố"></multiselect>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label>Khu vực</label>
                                <!-- <multiselect v-model="form.categories" :options="categories" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn khu vực" label="name" track-by="id" :preselect-first="true">
                                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} khu vực đã chọn</span></template>
                                </multiselect> -->
                                <multiselect v-model="form.salesarea" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn" :options="salesareas" :searchable="true" :allow-empty="false" :multiple="true">
                                    <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.name }}</strong> thuộc tỉnh<strong> {{ option.city }}</strong></template>
                                </multiselect>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tên Chiến Dịch</label>
                                <multiselect v-model="form.chiendich" :options="chiendich" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn chiến dịch" label="name" :preselect-first="true">
                                </multiselect>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Showroom</label>
                                <multiselect v-model="form.costcenters" :options="costcenters" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} showroom đã chọn</span></template>
                                </multiselect>
                            </div>
                           <!--  <div class="form-group col-md-2">
                                <label>Trạng thái</label>
                                <select  class="form-control" v-model="form.status">
                                    <option v-for="option in $parent.status" v-bind:value="option.value">
                                        {{ option.text }}
                                    </option>
                                </select>
                            </div> -->
                            <div class="form-group col-md-4">
                                <label>Chọn giai đoạn</a></label>
                                <select  class="form-control" v-model="form.giaidoan">
                                    <option v-for="option in $parent.giaidoan" v-bind:value="option.value">
                                        {{ option.text }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-8">
                                <label>Đặc điểm (nhận dạng)</label>
                                <input v-model="form.description" type="text" class="form-control" placeholder="Trang phục">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Ngày</label>
                                <input v-model="form.start_date" type="date" class="form-control" placeholder="Trang phục">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Giờ vào</label>
                                <input class="form-control" type="time" v-model="form.start_time">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Giờ ra</label>
                                <input class="form-control" type="time" v-model="form.end_time">
                            </div>
                        </div>
                        <br>
                        <h5 class="card-title">Ghi chú</h5>
                        <div class="form-group">
                            <textarea v-model="form.note" type="text" class="form-control" placeholder="Nhập ghi chú về khách hàng tiềm năng"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a @click="cancelContact" class="btn btn-default">Hủy</a>
                        <VueLoadingButton aria-label="Post message" class="btn btn-primary" @click.native="updateContact" :loading="isLoading" :styled="false">Cập nhật</VueLoadingButton>
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
            last_name: '',
            form: new Form({
                id: '',
                title: '',
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                zalo: '',
                company: '',
                address: '',
                city: '',
                description: '',
                start_date: '',
                start_time: '',
                end_time: '',
                costcenters: [],
                salesarea: [],
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
            this.$router.push({ name: 'ttkhachtiemnang', query: { id: this.form.id } })
        },
        updateContact() {
            this.isLoading = true;
            setTimeout(() => (this.isLoading = false), 3000);
            this.form.put('/api/contacts/' + this.form.id)
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
        axios.get("/api/contacts/" + this.id)
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
        axios.get('api/online-target')
        .then(response => {
            this.chiendich = response.data;
        });
        axios.get('api/diem-roi')
        .then(response => {
            this.diemroi = response.data;
        });
    }
}

</script>
