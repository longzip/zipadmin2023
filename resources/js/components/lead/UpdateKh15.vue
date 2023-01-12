<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div v-show="!editmode" class="card-title">Thêm khách hàng KH15</div>
                        <div v-show="editmode" class="card-title">Cập nhật khách hàng KH15</div>
                    </div>
                    <div class="card-body">
                        <div class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Họ tên</label>
                                        <div class="form-row">
                                            <div class="col-5">
                                                <select class="form-control" v-model="form.title">
                                                    <option>Anh</option>
                                                    <option>Chị</option>
                                                    <option>Ông</option>
                                                    <option>Bà</option>
                                                </select>
                                            </div>
                                            <div class="col-7">
                                                <input v-model="form.first_name" type="text" class="form-control" placeholder="Họ đệm">
                                            </div>
                                            <div class="col-12 mt-3">
                                                <input v-model="form.last_name" type="text" class="form-control" placeholder="Tên *">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Công ty</label>
                                        <input v-model="form.company" type="text" class="form-control" placeholder="Công ty">
                                    </div>
                                    <div class="form-group">
                                        <label>Giờ vào</label>
                                        <input type="time" v-model="form.start_time">
                                    </div>
                                </div>
                                <div class="col-md-6 ml-auto">
                                    <div class="form-group">
                                        <label>Số điện thoại / Email</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-phone"></i></span>
                                            </div>
                                            <input v-model="form.phone" type="text" class="form-control" data-inputmask='"mask": "999 999 99 99"' data-mask>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input v-model="form.email" type="email" class="form-control" id="inputEmail3" placeholder="Email">
                                    </div>
                                    <div class="form-group">
                                        <label>Ngày</label>
                                        <input v-model="form.start_date" type="date" class="form-control" placeholder="Trang phục">
                                    </div>
                                    <div class="form-group">
                                        <label>Giờ ra</label>
                                        <input type="time" v-model="form.end_time">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-8">
                                    <label>Địa chỉ</label>
                                    <input v-model="form.address" type="text" class="form-control" placeholder="Địa chỉ">
                                </div>
                                <div class="col-4">
                                    <label>Tỉnh / Thành phố</label>
                                    <select class="form-control" v-model="form.city">
                                        <option>Hoà Bình</option>
                                        <option>Hà Giang</option>
                                        <option>Lào Cai</option>
                                        <option>Phú Thọ</option>
                                        <option>Vĩnh Phúc</option>
                                        <option>Sơn La</option>
                                        <option>Lai Châu</option>
                                        <option>Bắc Giang</option>
                                        <option>Bắc Ninh</option>
                                        <option>Lạng Sơn</option>
                                        <option>Cao Bằng</option>
                                        <option>Tuyên Quang</option>
                                        <option>Thái Nguyên</option>
                                        <option>Bắc Cạn</option>
                                        <option>Yên Bái</option>
                                        <option>Ninh Bình</option>
                                        <option>Hải Phòng</option>
                                        <option>Hải Dương</option>
                                        <option>Hưng Yên</option>
                                        <option>Quảng Ninh</option>
                                        <option>Hà Tây</option>
                                        <option>Nam Định</option>
                                        <option>Hà Nam</option>
                                        <option>Thái Bình</option>
                                        <option>Thanh Hoá</option>
                                        <option>Nghệ An</option>
                                        <option>Hà Tĩnh</option>
                                        <option>Hà Nội</option>
                                        <option>Hồ Chí Minh</option>
                                        <option>Đắc Lắc</option>
                                        <option>Quảng Nam</option>
                                        <option>Đà Nẵng</option>
                                        <option>Quảng Bình</option>
                                        <option>Quảng Trị</option>
                                        <option>Thừa Thiên Huế</option>
                                        <option>Quảng Ngãi</option>
                                        <option>Bình Định</option>
                                        <option>Phú Yên</option>
                                        <option>Khánh Hoà</option>
                                        <option>Gia Lai</option>
                                        <option>Kon Tum</option>
                                        <option>Đồng Nai</option>
                                        <option>Bình Thuận</option>
                                        <option>Lâm Đồng</option>
                                        <option>Bà Rịa - Vũng Tàu</option>
                                        <option>Bình Dương</option>
                                        <option>Bình Phước</option>
                                        <option>Tây Ninh</option>
                                        <option>Đồng Tháp</option>
                                        <option>Ninh Thuận</option>
                                        <option>Vĩnh Long</option>
                                        <option>Cần Thơ</option>
                                        <option>Long An</option>
                                        <option>Tiền Giang</option>
                                        <option>Trà Vinh</option>
                                        <option>Bến Tre</option>
                                        <option>An Giang</option>
                                        <option>Kiên Giang</option>
                                        <option>Cà Mau</option>
                                        <option>Bạc Liêu</option>
                                        <option>Sóc Trăng</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                <label>Khu vực</label>
                                <multiselect v-model="contact.category" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn khu vực" :options="options_categories" :searchable="true" :allow-empty="false" @select="chonKhuVuc">
                                </multiselect>
                                <category></category>
                            </div>
                            </div>
                            <div class="form-group">
                                <label>Mô tả</label>
                                <textarea v-model="form.description" type="text" class="form-control" placeholder="Mô tả" />
                                </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button v-show="!editmode" type="button" @click.prevent="taokh15" class="btn btn-primary">Thêm</button>
                        <button v-show="editmode" type="submit" @click.prevent="updatekh15" class="btn btn-success">Cập nhật</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return{
                editmode: false,
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
                description: '',
                start_date: '',
                start_time: '',
                end_time: ''
            }),
            }
        },
        methods: {
                loadLead(){
                axios.get('leads/session')
                .then(response => {
                    this.editmode = true;
                    this.form.fill(response.data);
                    
                })
                .catch(() => {
                    this.editmode = false;
                    this.form.reset();
                    this.form.start_date= moment().format().split("T")[0];
                    this.form.start_time=moment().format('LT');
                    this.form.end_time=moment().format('LT');
                });
                
            },
            updatekh15(){
                this.$Progress.start();
                // console.log('Editing data');
                this.form.put('api/leads/'+this.form.id)
                .then(() => {
                    // success
                    $('#kh15ModalLong').modal('hide');
                     swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                        )
                        this.$Progress.finish();
                        location.replace("/thong-tin-kh15")
                })
                .catch(() => {
                    this.$Progress.fail();
                });

            },
        taokh15() {
            this.$Progress.start();
            this.form.post('api/leads')
                .then(() => {
                    this.taivekh15();
                    $("#kh15ModalLong").modal("hide");
                    this.$Progress.finish();
                   location.replace("/kh15")
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        },
        created() {
            this.loadLead();
        }
    }
</script>
