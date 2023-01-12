<template>
    <div class="container-flush">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách kho khu vực</h3>
                <div class="card-tools">
                    <a href="/them-kho-khu-vuc" class="btn btn-primary">
                        Thêm mới <i class="fa fa-user-plus"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Tên tỉnh</th>
                            <th>Code</th>
                            <th>Version</th>
                            <th>Cập nhật</th>
                            <th>Modify</th>
                        </tr>
                        <tr v-for="warehouse in warehouses.data" :key="warehouse.id">
                            <td>{{warehouse.id}}</td>
                            <td>{{warehouse.city}}</td>
                            <td>{{warehouse.code}}</td>
                            <td>{{warehouse.version}}</td>
                            <td>{{warehouse.update_at | myDate}}</td>
                            <td>
                                <router-link :to="{ name: 'editWarehouse', params: { warehouseId: warehouse.id }}"><i class="fa fa-edit blue"></i></router-link>
                                /
                                <a href="#" @click="deleteWarehouse(warehouse.id)">
                                    <i class="fa fa-trash red"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            warehouses: []
        }
    },
    methods: {
        deleteWarehouse(id) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                }
            })
        }
    },
    created() {
        axios.get('/api/warehouses')
            .then(response => {
                this.warehouses = response.data
            })
    }
}

</script>
