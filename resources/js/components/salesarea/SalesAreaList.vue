<template>
    <div class="container-flush">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách khu vực</h3>
                <div class="card-tools">
                    <a href="/them-khu-vuc" class="btn btn-primary">
                        Thêm mới <i class="fa fa-user-plus"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Tên khu vực</th>
                            <th>Tỉnh / TP</th>
                            <th>Mô tả</th>
                            <th>Cập nhật</th>
                            <th>Modify</th>
                        </tr>
                        <tr v-for="salesArea in salesAreas.data" :key="salesArea.id">
                            <td>{{salesArea.id}}</td>
                            <td>{{salesArea.name}}</td>
                            <td>{{salesArea.city}}</td>
                            <td>{{salesArea.description}}</td>
                            <td>{{salesArea.update_at | myDate}}</td>
                            <td>
                                <router-link :to="{ name: 'editSalesArea', params: { salesAreaId: salesArea.id }}"><i class="fa fa-edit blue"></i></router-link>
                                /
                                <a href="#" @click="deleteSalesArea(salesArea.id)">
                                    <i class="fa fa-trash red"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <pagination :data="salesAreas" :limit=3 @pagination-change-page="loadSalesArea"><span slot="prev-nav">&lt; Trước</span>
                    <span slot="next-nav">Sau &gt;</span></pagination>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            salesAreas: []
        }
    },
    methods: {
        loadSalesArea(page =1){
            let queryName = queryString.stringify({name: this.$parent.search});
            axios.get('/api/salesareas?page=' + page + '&' + queryName)
            .then(response => {
                this.salesAreas = response.data
            })
        },
        deleteSalesArea(id) {
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
        this.loadSalesArea();
        Fire.$on('searching', () => {
            this.loadSalesArea();
        })
    }
}

</script>
