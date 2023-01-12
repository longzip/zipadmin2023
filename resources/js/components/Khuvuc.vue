<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Danh sách khu vực</div>
                        <div class="card-tools">
                            <category></category>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>Tên khu vực</th>
                                    <th>Ngày tạo</th>
                                </tr>
                                <tr v-for="item in categories.data">
                                    <td>{{item.id}}</td>
                                    <td><a @click="showCategory(item.id)" href="#">{{item.name}}</a></td>
                                    <td>{{item.created_at | myDate}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        <pagination :data="categories" :limit=3 @pagination-change-page="loadCategories"><span slot="prev-nav">&lt; Trước</span>
                            <span slot="next-nav">Sau &gt;</span></pagination>
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
            categories: {}
        }
    },
    methods: {
        loadCategories(page = 1) {
            axios.get("api/categories?page=" + page)
                .then(response => {
                    this.categories = response.data;
                })
        },
        showCategory(id) {
            axios.get('/categories/session/' + id)
                .then(response => {
                    location.replace('/them-khu-vuc');
                });
        }
    },
    created() {
        this.loadCategories();
        Fire.$on('AfterCreateCategory', () => {
            this.loadCategories();
        });
    }
}

</script>
