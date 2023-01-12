<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Tạo Biên Bản</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>Tên</label>
                                <input v-model="form.name" type="text" class="form-control" placeholder="Tên *" :class="{ 'is-invalid': form.errors.has('last_name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Nhân Viên</label>
                                <multiselect v-model="form.resources" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Nhân Viên" :options="resources" :searchable="true" :allow-empty="false">
                                    <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.name }}</strong> </template>
                                </multiselect>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Mã Quy Định</label>
                                <multiselect v-model="form.decision" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn Mã" :options="decision" :searchable="true" :allow-empty="false">
                                    <template slot="singleLabel" slot-scope="{ option }"><strong>{{ option.name }}</strong> </template>
                                </multiselect>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Tiêu Đề</label>
                                <input  type="text" class="form-control" :placeholder=" form.decision.sapo" disabled="true">
                            </div>
                            <div class="form-group col-md-12">
                                <label>Nội Dung</label>
                                <textarea  type="text" class="form-control" :placeholder="form.decision.detail" disabled="true"></textarea>
                            </div>
                        </div>
                        <br>
                        <h5 class="card-title">Ghi chú</h5>
                        <div class="form-group">
                            <textarea v-model="form.note" type="text" class="form-control" placeholder="Nhập ghi chú về biên bản"></textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a @click="cancelContact" class="btn btn-default">Hủy</a>
                        <button @click.prevent="addContact" class="btn btn-primary">Cập nhật</button>
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
                name: '',
                note: '',
                resources: '',
                decision: '',
            }),
            users: [],
            resources: [],
            decision: [],
        }
    },
    methods: {
        cancelContact() {
            location.replace('/bien-ban');
        },
        addContact() {
            this.form.post('/api/bienban')
                .then(response => {
                    this.$router.push({ name: 'bienban' });
                });
        },
    },
    created() {
        axios.get('/api/picklists/users')
            .then(res => this.resources = res.data);
        axios.get('api/list-quy-dinh')
            .then(response => {
                this.decision = response.data;
            });
    }
}

</script>
