<template>
    <div class="container-flush">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Sửa Bảng Tin</h3>
            </div>
            <div class="card-body table-responsive">
               <form role="form">
                    <div class="form-group">
                        <label>Nhập Tiêu Đề</label>
                        <input v-model="form.name" type="text" ref="name" name="name" placeholder="Tên Tiêu Đề" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Chuyên Mục</label>
                        <multiselect v-model="form.category" :options="category"  ref="category" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn Chuyên Mục"></multiselect>
                        <has-error :form="form" field="category"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Phòng Ban</label>
                        <multiselect v-model="form.role" ref="role" tag-placeholder="Thêm Thông Báo Cho Phòng Ban" placeholder="Tìm hoặc chọn" :options="role" :multiple="false" :taggable="true"></multiselect>
                        <has-error :form="form" field="role"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Nhập Tóm Tắt</label>
                        <textarea rows='2' v-model="form.sapo" name="sapo" ref="sapo" placeholder="Nhập Tóm Tắt" class="form-control" :class="{ 'is-invalid': form.errors.has('sapo') }"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Nhập Nội Dung</label>
                        <!-- <vue-editor v-model="form.content" ref="content" :editors="editors"></vue-editor> -->
                        <textarea rows='15' v-model="form.content" name="content" ref="content" placeholder="Nhập Tóm Tắt" class="form-control" :class="{ 'is-invalid': form.errors.has('content') }"></textarea>
                        <has-error :form="form" field="content"></has-error>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button @click.prevent="updateNews" class="btn btn-primary">Lưu</button>
            </div>
        </div>
    </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { VueEditor } from 'vue2-editor'

export default {
    components: {
        VueEditor
    },
    props: ['NewsId','Files'],
    data() {
        return {
            role: [],
            editors: ClassicEditor,
            editorConfig: {
                
            },
            file: '',
            form: new Form({
                id: '',
                name: '',
                content: '',
                category: '',
                role: '',
                sapo: '',
            }),
            category: ['Tin Tổng Hợp','Khuyến Mại','Thành Tích','Dự Án','Đánh Giá Của Khách Hàng','Teambuilding'],
        }
    },
    created(){
        axios.get("/api/getallroles").then(({ data }) => (this.role = data));
        axios.get('/api/news/' + this.NewsId)
        .then(res => this.form.fill(res.data))
        .catch(()=> location.replace("/list-bang-tin"))
    },
    methods: {
        updateNews() {
            this.form.put('/api/news/' + this.NewsId)
            .then(() => {
                location.replace("/list-bang-tin")
            })
            .catch();
        }
    }
}

</script>
