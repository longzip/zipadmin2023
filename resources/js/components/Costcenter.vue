<template>
    <section class="content">
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm Showroom</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Cập nhật thông tin Showroom</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? update() : create()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input v-model="form.code" type="text" name="code" placeholder="Code" class="form-control" :class="{ 'is-invalid': form.errors.has('code') }">
                                <has-error :form="form" field="code"></has-error>
                            </div>
                            <div class="form-group">
                                <input v-model="form.name" type="text" name="name" placeholder="Name" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name"></has-error>
                            </div>
                            <div class="form-group">
                                <label for="inputWarehouse">Kho khu vực*</label>
                                <select id="inputWarehouse" v-model="form.warehouse" class="form-control" :class="{ 'is-invalid': form.errors.has('warehouse') }">
                                    <option v-for="warehouse in options.warehouses" :key="warehouse.key" :value="warehouse.key">
                                        @{{ warehouse.label }}
                                    </option>
                                </select>
                                <has-error :form="form" field="warehouse"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    Showroom ZIP
                </div>
                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal">Thêm Showroom <i class="fas fa-user-plus fa-fw"></i></button>
                </div>
            </div>
            <div class="cart-body table-responsive p-0">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Khu vực</th>
                            <th>Ngày sửa</th>
                            <th>Modify</th>
                        </tr>
                        <tr v-for="costcenter in costcenters.data" :key="costcenter.id">
                            <td>{{costcenter.code}}</td>
                            <td>{{costcenter.name}}</td>
                            <td>{{costcenter.warehouse}}</td>
                            <td>{{costcenter.created_at | myDate}}</td>
                            <td>
                                <a href="#" @click="editModal(costcenter)">
                                    <i class="fa fa-edit blue"></i>
                                </a>
                                /
                                <a href="#">
                                    <i class="fa fa-trash red"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <pagination :data="costcenters" @pagination-change-page="load"></pagination>
            </div>
        </div>
    </section>
</template>
<script>
export default {
    data() {
        return {
            editmode: false,
            value: null,
            costcenters: {},
            form: new Form({
                id: '',
                code: '',
                name: '',
                manager: '',
                warehouse: ''
            }),
            options: {
                warehouses: [
                    { label: 'TP. Hà Nội', key: 'TP. Hà Nội' },
                    { label: 'TP. Hồ Chí Minh', key: 'TP. Hồ Chí Minh' },
                    { label: 'Tỉnh Miền Bắc', key: 'Tỉnh Miền Bắc' },
                    { label: 'Tỉnh Miền Nam', key: 'Tỉnh Miền Nam' },                   
                ]
            }
        }
    },
    methods: {
        load(page = 1) {
            axios.get("api/costcenter?page=" + page).then(({ data }) => (this.costcenters = data));
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#addNew').modal('show');
        },
        editModal(costcenter) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(costcenter);
        },
        create() {
            this.form.post('api/costcenter')
                .then(() => {
                    this.load();
                    $('#addNew').modal('hide')
                    swal.fire(
                        'Thêm!',
                        'Thông tin của bạn đã được lưu.',
                        'success'
                    )
                });
        },
        update() {
            this.form.put('api/costcenter/' + this.form.id)
                .then(() => {
                    this.load();
                    $('#addNew').modal('hide');
                    swal.fire(
                        'Lưu!',
                        'Thông tin của bạn đã được lưu.',
                        'success'
                    )
                });
        },
    },
    created() {
        this.load();
    }
}

</script>
