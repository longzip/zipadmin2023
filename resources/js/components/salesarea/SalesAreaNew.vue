<template>
    <div class="container-flush">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm mới kho khu vực</h3>
            </div>
            <div class="card-body table-responsive">
                <form role="form">
                    <div class="form-group">
                        <label>Nhập tên khu vực</label>
                        <input v-model="form.name" type="text" name="name" placeholder="Tên khu vực" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Tỉnh / Thành phố</label>
                        <multiselect v-model="form.city" :options="cities" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn tỉnh/ thành phố"></multiselect>
                        <has-error :form="form" field="city"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Mô tả</label>
                        <textarea rows='10' v-model="form.description" name="description" placeholder="Nhập mô tả" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                        <has-error :form="form" field="description"></has-error>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button @click.prevent="addSalesArea" class="btn btn-primary">Lưu</button>
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
                name: '',
                city: '',
                description: ''
            }),
            cities: ['Hoà Bình', 'Hà Giang', 'Lào Cai', 'Phú Thọ', 'Vĩnh Phúc', 'Sơn La', 'Lai Châu', 'Bắc Giang', 'Bắc Ninh', 'Lạng Sơn', 'Cao Bằng', 'Tuyên Quang', 'Thái Nguyên', 'Bắc Cạn', 'Yên Bái', 'Ninh Bình', 'Hải Phòng', 'Hải Dương', 'Hưng Yên', 'Quảng Ninh', 'Hà Tây', 'Nam Định', 'Hà Nam', 'Thái Bình', 'Thanh Hoá', 'Nghệ An', 'Hà Tĩnh', 'Hà Nội', 'Hồ Chí Minh', 'Đắc Lắc', 'Quảng Nam', 'Đà Nẵng', 'Quảng Bình', 'Quảng Trị', 'Thừa Thiên Huế', 'Quảng Ngãi', 'Bình Định', 'Phú Yên', 'Khánh Hoà', 'Gia Lai', 'Kon Tum', 'Đồng Nai', 'Bình Thuận', 'Lâm Đồng', 'Bà Rịa - Vũng Tàu', 'Bình Dương', 'Bình Phước', 'Tây Ninh', 'Đồng Tháp', 'Ninh Thuận', 'Vĩnh Long', 'Cần Thơ', 'Long An', 'Tiền Giang', 'Trà Vinh', 'Bến Tre', 'An Giang', 'Kiên Giang', 'Cà Mau', 'Bạc Liêu', 'Sóc Trăng']
        }
    },
    methods: {
        addSalesArea() {
            this.form.post('/api/salesareas')
                .then(() => {
                    location.replace("/khu-vuc")
                })
                .catch();
        }
    }
}

</script>
