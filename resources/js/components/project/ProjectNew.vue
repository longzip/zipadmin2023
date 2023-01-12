<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Tạo khách hàng dự án</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Tên Dự Án</label>
                                <input v-model="form.name_project" type="text" class="form-control" placeholder="Nhập Tên Dự Án" :class="{ 'is-invalid': form.errors.has('name_project') }">
                                <has-error :form="form" field="name_project"></has-error>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Mô Tả Dự Án</label>
                                <div class="form-group">
                                    <textarea v-model="form.sapo_project" type="text" class="form-control" placeholder="Nhập mô tả về dự án"></textarea>
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Công ty</label>
                                <input v-model="form.company" type="text" class="form-control" placeholder="Nhập Tên Công ty" :class="{ 'is-invalid': form.errors.has('company') }">
                                <has-error :form="form" field="company"></has-error>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Mô tả công ty</label>
                                <div class="form-group">
                                    <textarea v-model="form.descompany" type="text" class="form-control" placeholder="Nhập ghi chú về công ty"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 green"><label>Liên Hệ 1</label></div>
                            <div class="form-group col-md-3">
                                <label>Chức danh</label>
                                <input v-model="form.chucdanh1" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('chucdanh1') }">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tên</label>
                                <input v-model="form.last_name1" type="text" class="form-control" placeholder="Tên *" :class="{ 'is-invalid': form.errors.has('last_name1') }">
                                <has-error :form="form" field="last_name1"></has-error>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Số điện thoại</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input v-model="form.phone1" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('phone1') }">
                                    <has-error :form="form" field="phone1"></has-error>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input v-model="form.email1" type="email" class="form-control" id="inputEmail3" placeholder="Email" :class="{ 'is-invalid': form.errors.has('email1') }">
                                <has-error :form="form" field="email1"></has-error>
                            </div>
                           <div class="form-group col-md-8">
                                <label>Địa Chỉ</label>
                                <input v-model="form.address1" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('address1') }">
                                <has-error :form="form" field="address1"></has-error>
                            </div>
                            <div class="col-md-12">
                                <div class="float-right">
                                    <button @click="addinfo2" class="btn btn-sm btn-info add2">Thêm Liên Hệ</button>

                                    <button @click="remove2" class="btn btn-sm btn-info cancel2 hide">Hủy Liên Hệ 2</button>
                                </div>
                            </div>

                            <div class="col-md-12 green hide remove2"><label>Liên Hệ 2</label></div>
                            <div class="form-group col-md-3 hide remove2">
                                <label>Chức danh</label>
                                <input v-model="form.chucdanh2" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('chucdanh2') }">
                            </div>
                            <div class="form-group col-md-6 hide remove2">
                                <label>Tên</label>
                                <input v-model="form.last_name2" type="text" class="form-control" placeholder="Tên *" >
                            </div>
                            <div class="form-group col-md-3 hide remove2">
                                <label>Số điện thoại</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input v-model="form.phone2" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group col-md-4 hide remove2">
                                <label>Email</label>
                                <input v-model="form.email2" type="email2" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                            <div class="form-group col-md-8 hide remove2" >
                                <label>Địa Chỉ</label>
                                <input v-model="form.address2" type="text" class="form-control">
                            </div>

                            



                            <div class="col-md-12 green"><label>Thông Tin Khảo Sát</label></div>
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
                                <label>Khu vực</label>
                                <multiselect v-model="form.salesarea" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn" :options="salesareas" :searchable="true" :allow-empty="false">
                                    <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.name }}</strong> thuộc tỉnh<strong> {{ option.city }}</strong></template>
                                </multiselect>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label>Showroom <a href="#" @click="loadShowroom"><i class="fas fa-sync-alt"></i></a></label>
                                <multiselect v-model="form.costcenters" :options="costcenters" :multiple="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} showroom đã chọn</span></template>
                                </multiselect>
                            </div>
                            <div class="form-group col-md-2">
                                <label>Trạng thái</label>
                                <select  class="form-control" v-model="form.status">
                                    <option v-for="option in $parent.status" v-bind:value="option.value">
                                        {{ option.text }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group col-md-7">
                                <label>Đặc điểm (nhận dạng)</label>
                                <input v-model="form.note" type="text" class="form-control" placeholder="Trang phục">
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
                            <textarea v-model="form.description" type="text" class="form-control" placeholder="Nhập ghi chú về khách dự án"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a @click="cancelProject" class="btn btn-default">Hủy</a>
                        <button @click.prevent="addProject" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['lead_id'],

    data() {
        return {
            last_name: '',
            form: new Form({
                id: '',
                sapo_project: '',
                name_project: '',
                chucdanh1: '',
                last_name1: '',
                email1: '',
                phone1: '',
                address1: '',

                chucdanh2: '',
                last_name2: '',
                email2: '',
                phone2: '',
                address2: '',

                company: '',
                descompany: '',
                city: '',
                description: '',
                start_date: moment().format().split("T")[0],
                start_time: '',
                end_time: moment().format('LT'),
                costcenters: [],
                products: [],
                salesarea: null,
                status: 1,
                note: ''
            }),
            costcenters: [],
            salesareas: [],
            products: [],
            cities: ['Hoà Bình', 'Hà Giang', 'Lào Cai', 'Phú Thọ', 'Vĩnh Phúc', 'Sơn La', 'Lai Châu', 'Bắc Giang', 'Bắc Ninh', 'Lạng Sơn', 'Cao Bằng', 'Tuyên Quang', 'Thái Nguyên', 'Bắc Cạn', 'Yên Bái', 'Ninh Bình', 'Hải Phòng', 'Hải Dương', 'Hưng Yên', 'Quảng Ninh', 'Hà Tây', 'Nam Định', 'Hà Nam', 'Thái Bình', 'Thanh Hoá', 'Nghệ An', 'Hà Tĩnh', 'Hà Nội', 'Hồ Chí Minh', 'Đắc Lắc', 'Quảng Nam', 'Đà Nẵng', 'Quảng Bình', 'Quảng Trị', 'Thừa Thiên Huế', 'Quảng Ngãi', 'Bình Định', 'Phú Yên', 'Khánh Hoà', 'Gia Lai', 'Kon Tum', 'Đồng Nai', 'Bình Thuận', 'Lâm Đồng', 'Bà Rịa - Vũng Tàu', 'Bình Dương', 'Bình Phước', 'Tây Ninh', 'Đồng Tháp', 'Ninh Thuận', 'Vĩnh Long', 'Cần Thơ', 'Long An', 'Tiền Giang', 'Trà Vinh', 'Bến Tre', 'An Giang', 'Kiên Giang', 'Cà Mau', 'Bạc Liêu', 'Sóc Trăng']
        }
    },
    methods: {
        addinfo2(){
            $('.remove2').removeClass('hide');
            $('.cancel2').removeClass('hide');
            $('.add2').addClass('hide');
        },
        remove2(){
            $('.remove2').addClass('hide');
            $('.remove2 input').val('');
            $('.add2').removeClass('hide');
            $('.cancel2').addClass('hide');
        },
        cancelProject() {
            location.replace('/project');
        },
        deleteLead(id) {
            axios.delete('/api/leads/' + id)
        },
        addProject() {
            this.isLoading = true;
            setTimeout(() => (this.isLoading = false), 3000);
            this.deleteLead_id = this.lead_id;
            if (this.form.phone == '') {
                this.form.phone = undefined;
            }
            this.form.post('/api/project')
                .then(response => {
                    this.deleteLead(this.lead_id);
                    this.$router.push({ name: 'ttkhachduan', params: { id: response.data.id } });

                })
                .catch(() => {

                });
        },
        loadShowroom() {
            axios.get("/api/users-costcenters")
                .then(response => {
                    this.costcenters = response.data;
                    if (this.costcenters.length > 0) {
                        this.form.costcenters.push(this.costcenters[0]);
                    }
                });
        }
    },
    created() {
        this.loadShowroom();
        if (this.lead_id) {
            axios.get("/api/leads/" + this.lead_id)
                .then(response => {
                    this.form.fill(response.data.data);
                })
                .catch(() => {
                    this.$router.go(-1)
                });
        }

        axios.get('api/salesareas-list')
            .then(response => {
                this.salesareas = response.data;
            });
        axios.get('api/products-list')
            .then(response => {
                this.products = response.data;
            });
    }
}

</script>
