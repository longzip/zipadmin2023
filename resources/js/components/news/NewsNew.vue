<template>
    <div class="container-flush">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thêm Tin</h3>
            </div>
            <div class="card-body table-responsive">
                <form role="form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label>Nhập Tiêu Đề</label>
                        <input v-model="form.name" type="text" ref="name" placeholder="Tên Tiêu Đề" class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Chuyên Mục</label>
                        <multiselect v-model="form.category" ref="category" :options="category" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn Chuyên Mục" :class="{ 'is-invalid': form.errors.has('category') }"></multiselect>
                        <has-error :form="form" field="category"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Phòng Ban</label>
                        <multiselect v-model="form.roles" ref="roles" tag-placeholder="Thêm Thông Báo Cho Phòng Ban" placeholder="Tìm hoặc chọn" :options="roles" :multiple="false" :taggable="true"></multiselect>
                        <has-error :form="form" field="roles"></has-error>
                    </div>
                    <div class="form-group">
                        <label>Avatar</label>
                        <br>
                        <input type="file" id="ava" ref="ava" v-on:change="handleImageUpload" />
                        <has-error :form="form" field="ava"></has-error>
                    </div> 
                    <div class="form-group">
                        <label>Youtube</label>
                        <input type="checkbox" id="checkbox" ref="checkbox" v-model="form.checkbox">
                    </div>       
                    <div class="form-group">
                        <label>UpFile</label>
                        <br>
                        <input type="file" id="file" ref="file" v-on:change="handleFileUpload" />
                        <has-error :form="form" field="file"></has-error>
                    </div>                  
                    <div class="form-group">
                        <label>Nhập Tóm Tắt</label>
                        <textarea rows='2' v-model="form.sapo" name="sapo" ref="sapo" placeholder="Nhập Tóm Tắt" class="form-control" :class="{ 'is-invalid': form.errors.has('sapo') }"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Nhập Nội Dung</label>
                        <textarea rows='15' v-model="form.content" name="content" ref="content" placeholder="Nhập Tóm Tắt" class="form-control" :class="{ 'is-invalid': form.errors.has('content') }"></textarea>
                        <has-error :form="form" field="content"></has-error>
                    </div>  
                </form>
            </div>
            <div class="card-footer">
                <button  @click.prevent="addnews" class="btn btn-primary">Lưu</button>
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
    name: 'app',
    data() {
        return {
            roles: [],
            editors: ClassicEditor,
            editorConfig: {
                
            },
            file: '',
            ava: '',
            form: new Form({
                id: '',
                name: '',
                content: '',
                category: '',
                roles: '',
                sapo: '',
                checkbox: '',
            }),
            category: ['Tin Tổng Hợp','Khuyến Mại','Thành Tích','Dự Án','Đánh Giá Của Khách Hàng','Teambuilding'],
            files: new FormData(),
        };
    },
    methods: {
        handleFileUpload() {
            this.file = this.$refs.file.files[0];
            console.log(this.file);
        },
        handleImageUpload() {
            this.ava = this.$refs.ava.files[0];
            console.log(this.ava);
        },
        addnews() {
            let formData = new FormData();
            formData.append('file', this.file);
            formData.append('ava', this.ava);
            formData.append('category', this.$refs.category.value);
            formData.append('name', this.$refs.name.value);
            formData.append('content', this.$refs.content.value);
            formData.append('roles', this.$refs.roles.value);
            formData.append('sapo', this.$refs.sapo.value);
            formData.append('checkbox', this.$refs.checkbox.checked);

            axios.post('/api/news',formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
            .then(() => {
                location.replace("/list-bang-tin")
            })
            .catch();
        }
    },    
    created() {
        axios.get("/api/getallroles").then(({ data }) => (this.roles = data));
    }
}

</script>

<style>
  .ck-editor__editable {
    min-height: 400px;
   }
</style>