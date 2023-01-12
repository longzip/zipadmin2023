<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Thêm Marketing</h3>
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
                            <input disabled="true" v-model="form.first_name" type="hidden" class="form-control" placeholder="Họ đệm">
                            <div class="form-group col-md-4">
                                <label>Tên</label>
                                <input v-model="form.last_name" type="text" class="form-control" placeholder="Tên *" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                <has-error :form="form" field="last_name"></has-error>
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
                            <div class="form-group col-md-3">
                                <label>Email or Link Facebook</label>
                                <input v-model="form.email" type="email" class="form-control" id="inputEmail3" placeholder="Email or Link Facebook">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Sản phẩm (quan tâm)</label>
                                <multiselect v-model="form.products" :options="products" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn sản phẩm" label="name" track-by="id" :preselect-first="true">
                                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} sản phẩm đã chọn</span></template>
                                </multiselect>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tỉnh / Thành phố</label>
                                <multiselect v-model="form.city" :options="cities" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn tỉnh/ thành phố"></multiselect>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Showroom</label>
                                <multiselect v-model="form.costcenters" :options="costcenters" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} showroom đã chọn</span></template>
                                </multiselect>
                            </div>
                            <!-- <div class="col-md-2">
                                <div class="form-group">
                                    <label>Chọn giai đoạn</a></label>
                                    <select  class="form-control" v-model="form.giaidoan">
                                        <option v-for="option in $parent.giaidoan" v-bind:value="option.value">
                                            {{ option.text }}
                                        </option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="form-group col-md-6">
                                <label>Địa chỉ</label>
                                <input v-model="form.address" type="text" class="form-control" placeholder="Địa chỉ">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tên Chiến Dịch</label>
                                <multiselect v-model="form.chiendich" :options="chiendich" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn chiến dịch" label="name" :preselect-first="true">
                                </multiselect>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Ngày</label>
                                <input v-model="form.start_date" type="date" class="form-control" placeholder="Trang phục">
                            </div>
                        </div>
                        <br>
                        <h5 class="card-title">Ghi chú</h5>
                        <div class="form-group">
                            <textarea v-model="form.note" type="text" class="form-control" placeholder="Nhập ghi chú về khách hàng"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a @click="cancelLead" class="btn btn-default">Hủy</a>
                        <a class="btn btn-primary" @click="addLead" :loading="isLoading" :styled="false">Tạo Marketing</a>
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
            form: new Form({
                id: '',
                title: '',
                first_name: '',
                last_name: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                costcenters: [],
                products: [],
                note: '',
                start_date:'',
                chiendich:'',
            }),
            chiendich: [],
            costcenters: [],
            salesareas: [],
            products: [],
            giaidoan:[],
            isLoading: false,
            cities: ['Hoà Bình', 'Hà Giang', 'Lào Cai', 'Phú Thọ', 'Vĩnh Phúc', 'Sơn La', 'Lai Châu', 'Bắc Giang', 'Bắc Ninh', 'Lạng Sơn', 'Cao Bằng', 'Tuyên Quang', 'Thái Nguyên', 'Bắc Cạn', 'Yên Bái', 'Ninh Bình', 'Hải Phòng', 'Hải Dương', 'Hưng Yên', 'Quảng Ninh', 'Hà Tây', 'Nam Định', 'Hà Nam', 'Thái Bình', 'Thanh Hoá', 'Nghệ An', 'Hà Tĩnh', 'Hà Nội', 'Hồ Chí Minh', 'Đắc Lắc', 'Quảng Nam', 'Đà Nẵng', 'Quảng Bình', 'Quảng Trị', 'Thừa Thiên Huế', 'Quảng Ngãi', 'Bình Định', 'Phú Yên', 'Khánh Hoà', 'Gia Lai', 'Kon Tum', 'Đồng Nai', 'Bình Thuận', 'Lâm Đồng', 'Bà Rịa - Vũng Tàu', 'Bình Dương', 'Bình Phước', 'Tây Ninh', 'Đồng Tháp', 'Ninh Thuận', 'Vĩnh Long', 'Cần Thơ', 'Long An', 'Tiền Giang', 'Trà Vinh', 'Bến Tre', 'An Giang', 'Kiên Giang', 'Cà Mau', 'Bạc Liêu', 'Sóc Trăng']
        }
    },
    methods: {
        cancelLead() {
            location.replace('/khach');
        },
        addLead() {
            this.$Progress.start();
     
            this.form.post('api/marketing')
                .then(() => {
                    location.replace("/khach")
                })
                .catch(() => {
                });
        },
        // onChange(event) {
        //     this.taivekh15();
        // },

    },
    created() {
        axios.get("/api/users-costcenters")
            .then(response => {
                this.costcenters = response.data;
            });
        axios.get('api/salesareas-list')
            .then(response => {
                this.salesareas = response.data;
            });
        axios.get('api/products-list')
            .then(response => {
                this.products = response.data;
            });
        axios.get('api/online-target')
        .then(response => {
            this.chiendich = response.data;
        });
    }
}

</script>
