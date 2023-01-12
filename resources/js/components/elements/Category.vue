<template>
    <div>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#categoryModal">
        Tạo mới khu vực
    </button>
    <!-- Modal -->
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Tạo mới Khu vực</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createCategory()">
                <div class="modal-body">
                    <input v-model="form.name" class="form-control form-control-lg" type="text" placeholder="Tên khu vực"><br>
                        <input v-model="form.slug" class="form-control form-control-lg" type="text" placeholder="Địa chỉ"><br>
                        <input v-model="form.website_url" class="form-control form-control-lg" type="text" placeholder="Website url"><br>
                        <input v-model="form.facebook_url" class="form-control form-control-lg" type="text" placeholder="Trang facebook"><br>
                        <input v-model="form.google_map" class="form-control form-control-lg" type="text" placeholder="Địa chỉ bản đồ"><br>
                        <input v-model="form.phone" class="form-control form-control-lg" type="text" placeholder="Điện thoại"><br>
                        <textarea v-model="form.description" class="form-control form-control-lg" type="text" placeholder="Mô tả"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Tạo mới</button>
                </div>
            </form>
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
                id: '',
                name: '',
                slug: '',
                website_url: '',
                facebook_url: '',
                google_map: '',
                phone: '',
                type: '',
                description: ''
            }),
            category: []
        }
    },
    methods:{
        createCategory(){
            this.form.post("api/categories")
            .then(response => {
                this.category = response.data;
                $('#categoryModal').modal('hide');
                Fire.$emit('AfterCreateCategory');
            })
        },
    }
}

</script>
