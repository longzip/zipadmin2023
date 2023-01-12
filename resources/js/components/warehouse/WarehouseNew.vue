<template>
    <div class="container-flush">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm mới kho khu vực</h3>
            </div>
            <div class="card-body table-responsive">
                <form role="form">
                    <div class="form-group">
                        <label>Nhập tên tỉnh (thành phố)</label>
                        <input v-model="form.city" type="text" name="city" placeholder="Tên tỉnh thành" class="form-control" :class="{ 'is-invalid': form.errors.has('city') }">
                        <has-error :form="form" field="city"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Chọn kho khu vực</label>
                        <select v-model="form.code" class="form-control" :class="{ 'is-invalid': form.errors.has('code') }" @change="changeLocation">
                            <option disabled value="">Chọn kho</option>
                            <option>A_MB</option>
                            <option>A_MT</option>
                            <option>A_MN</option>
                            <option>A_TA</option>
                        </select>
                        <has-error :form="form" field="code"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Nhập version BOM</label>
                        <input disabled="true" v-model="form.version" type="text" name="version" placeholder="Version" class="form-control" :class="{ 'is-invalid': form.errors.has('version') }">
                        <has-error :form="form" field="version"></has-error>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button @click.prevent="addWarehouse" class="btn btn-primary">Lưu</button>
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
                city: '',
                code: '',
                version: ''
            })
        }
    },
    methods: {
        addWarehouse() {
            this.form.post('/api/warehouses')
                .then(() => {
                    location.replace("/kho-khu-vuc")
                })
                .catch();
        },
        changeLocation(){
            if (this.form.code == 'A_MB') {
                this.form.version = 2
            }
            if (this.form.code == 'A_MT') {
                this.form.version = 3
            }
            if (this.form.code == 'A_MN') {
                this.form.version = 4
            }
            if (this.form.code == 'A_TA') {
                this.form.version = 5
            }
        }
    }
}

</script>
