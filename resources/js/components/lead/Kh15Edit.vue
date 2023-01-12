<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Sửa khách hàng KH15</h3>
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
                            <div class="form-group col-md-2">
                                <label>Trạng Thái</label>
                                <input disabled="true" v-model="form.first_name" type="hidden" class="form-control" placeholder="Họ đệm">
                                <input v-model="form.first_name" type="hidden" class="form-control" placeholder="Họ đệm">
                                <span>Tạm Dừng</span>
                                <input type="checkbox" id="checkbox" ref="checkbox" class="form-control" v-model="form.checkbox">
                            </div>
                            <div class="form-group col-md-5">
                                <label>Tên</label>
                                <input v-model="form.last_name" type="text" class="form-control" placeholder="Tên *">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Tên Zalo</label>
                                <input  v-model="form.zalo" type="text" class="form-control" placeholder="Tên Zalo">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Công ty</label>
                                <input v-model="form.company" type="text" class="form-control" placeholder="Công ty">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Số điện thoại</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input v-model="form.phone" type="text" class="form-control" data-inputmask='"mask": "999 999 99 99"' data-mask>
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
                            <div class="form-group col-md-6">
                                <label>Khu vực</label>
                                <multiselect v-model="form.salesarea" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn" :options="salesareas" :searchable="true" :allow-empty="false"  :multiple="true">
                                    <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.name }}</strong> thuộc tỉnh<strong> {{ option.city }}</strong></template>
                                </multiselect>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Showroom</label>
                                <multiselect v-model="form.costcenters" :options="costcenters" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} showroom đã chọn</span></template>
                                </multiselect>
                            </div>
                            <div class="form-group col-md-12">
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
                            <!-- <div class="row"> -->
                                <div class="form-group col-md-4">
                                    <label>Ngày Bắt Đầu Khách Nhận Nhà</label><small>Có thể bỏ trống</small>
                                    <input v-model="form.start_date_roi" type="date" class="form-control" placeholder="Trang phục">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Ngày Kết Thúc Khách Nhận Nhà</label><small>Có thể bỏ trống</small>
                                    <input v-model="form.end_date_roi" type="date" class="form-control" placeholder="Trang phục">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Tên Chiến Dịch</label>
                                    <multiselect v-model="form.chiendich" :options="chiendich" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn chiến dịch" label="name" :preselect-first="true">
                                    </multiselect>
                                </div>
                            <!-- </div> -->
                        </div>
                        <div class="row">
                            <div class="form-group col-md-8">
                                <label>Địa chỉ</label>
                                <input v-model="form.address" type="text" class="form-control" placeholder="Địa chỉ">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Tỉnh / Thành phố</label>
                                <multiselect v-model="form.city" :options="cities" :searchable="true" :close-on-select="false" :show-labels="false" placeholder="Chọn tỉnh/ thành phố"></multiselect>
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
                company: '',
                address: '',
                city: '',
                zalo: '',
                checkbox: '',
                description: '',
                start_date: '',
                start_time: '',
                end_time: '',
                costcenters: [],
                salesarea: null,
                products: [],
                note: '',
                start_date_roi:'',
                chiendich:'',
                end_date_roi:'',
            }),
            chiendich: [],
            costcenters: [],
            salesareas: [],
            cities: ['Hoà Bình', 'Hà Giang', 'Lào Cai', 'Phú Thọ', 'Vĩnh Phúc', 'Sơn La', 'Lai Châu', 'Bắc Giang', 'Bắc Ninh', 'Lạng Sơn', 'Cao Bằng', 'Tuyên Quang', 'Thái Nguyên', 'Bắc Cạn', 'Yên Bái', 'Ninh Bình', 'Hải Phòng', 'Hải Dương', 'Hưng Yên', 'Quảng Ninh', 'Hà Tây', 'Nam Định', 'Hà Nam', 'Thái Bình', 'Thanh Hoá', 'Nghệ An', 'Hà Tĩnh', 'Hà Nội', 'Hồ Chí Minh', 'Đắc Lắc', 'Quảng Nam', 'Đà Nẵng', 'Quảng Bình', 'Quảng Trị', 'Thừa Thiên Huế', 'Quảng Ngãi', 'Bình Định', 'Phú Yên', 'Khánh Hoà', 'Gia Lai', 'Kon Tum', 'Đồng Nai', 'Bình Thuận', 'Lâm Đồng', 'Bà Rịa - Vũng Tàu', 'Bình Dương', 'Bình Phước', 'Tây Ninh', 'Đồng Tháp', 'Ninh Thuận', 'Vĩnh Long', 'Cần Thơ', 'Long An', 'Tiền Giang', 'Trà Vinh', 'Bến Tre', 'An Giang', 'Kiên Giang', 'Cà Mau', 'Bạc Liêu', 'Sóc Trăng'],
            products: [],
            isLoading: false,
        }
    },
    methods: {
        cancelContact() {
            location.replace('/kh15');
        },
        updateContact() {
            this.$Progress.start();
            this.isLoading = true;
            setTimeout(() => (this.isLoading = false), 3000);
            if (this.form.phone == '') {
                this.form.phone = undefined;
            }
            this.form.put('/api/leads/' + this.form.id)
                .then(() => {
                    this.$Progress.finish();
                    // location.replace("/kh15")
                    this.$router.go(-1);
                })
                .catch(() => {
                    this.$Progress.fail();
                })
                .then(() => this.isLoading = false);
        }
    },

    created() {
        axios.get("/api/leads/" + this.id)
            .then(response => {
                this.form.fill(response.data.data);
            })
            .catch(() => {
                this.$router.go(-1)
            });
        axios.get("/api/costcenters-list")
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
        // axios.get('api/cities')
        //     .then(response => {
        //         this.cities = response.data;
        //     });
    }
}

</script>
