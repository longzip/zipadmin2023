<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Sửa khách dự án</h3>
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
                                <input v-model="form.company.name_company" type="text" class="form-control" placeholder="Nhập Tên Công ty":class="{ 'is-invalid': form.errors.has('company.name_company') }">
                                <has-error :form="form" field="company.name_company"></has-error>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Mô tả công ty</label>
                                <div class="form-group">
                                    <textarea v-model="form.company.sapo" type="text" class="form-control" placeholder="Nhập ghi chú về công ty"></textarea>
                                </div>
                            </div>

                            <div class="col-md-12 green"><label>Liên Hệ 1</label></div>
                            <div class="form-group col-md-3">
                                <label>Chức danh</label>
                                <input v-model="form.company.chucdanh_one" type="text" class="form-control" >
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tên</label>
                                <input v-model="form.company.name_one" type="text" class="form-control" placeholder="Tên *" :class="{ 'is-invalid': form.errors.has('company.name_one') }">
                                <has-error :form="form" field="company.name_one"></has-error>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Số điện thoại</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input v-model="form.company.phone_one" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('company.phone_one') }">
                                    <has-error :form="form" field="company.phone_one"></has-error>
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input v-model="form.company.email_one" type="email" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                           <div class="form-group col-md-8">
                                <label>Địa Chỉ</label>
                                <input v-model="form.company.address_one" type="text" class="form-control" :class="{ 'is-invalid': form.errors.has('company.address_one') }">
                                <has-error :form="form" field="company.address_one"></has-error>
                            </div>
                 

                            <div class="col-md-12 green"><label>Liên Hệ 2</label></div>
                            <div class="form-group col-md-3">
                                <label>Chức danh</label>
                                <input v-model="form.company.chucdanh_two" type="text" class="form-control" >
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tên</label>
                                <input v-model="form.company.name_two" type="text" class="form-control" placeholder="Tên ">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Số điện thoại</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                    </div>
                                    <input v-model="form.company.phone_two" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group col-md-4">
                                <label>Email</label>
                                <input v-model="form.company.email_two" type="email2" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                            <div class="form-group col-md-8" >
                                <label>Địa Chỉ</label>
                                <input v-model="form.company.address_two" type="text" class="form-control" >
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
                                <label>Showroom</label>
                                <multiselect v-model="form.costcenters" :options="costcenters" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
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
                        <a @click="cancelContact" class="btn btn-default">Hủy</a>
                        <VueLoadingButton aria-label="Post message" class="btn btn-primary" @click.native="updateContact"  :styled="false">Cập nhật</VueLoadingButton>
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
                address: '',
                city: '',
                description: '',
                start_date: '',
                start_time: '',
                end_time: '',
                costcenters: [],
                salesarea: [],
                products: [],
                status: 1,
                note: ''
            }),
            costcenters: [],
            salesareas: [],
            cities: [],
            products: [],
            company: []
        }
    },
    methods: {
        cancelContact() {
            this.$router.push({ name: 'ttkhachduan', params: { id: this.form.id } })
        },
        updateContact() {
            this.form.put('/api/project/' + this.form.id)
                .then(() => {
                    this.$router.go(-1);
                    //this.$router.push({ name: 'ttkhachtiemnang', params: { id: this.form.id }})
                })
                .catch(error => {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Bạn không có quyền sửa',
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });
        }
    },

    created() {
        axios.get("/api/project/" + this.id)
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
