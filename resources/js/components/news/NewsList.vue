<template>
    <div class="container-flush">
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Cập nhật File Đính Kèm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="update()" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <br>
                                <input v-model="form.id" type="hidden" ref="id" name="id"  class="form-control" >
                                <input type="file" id="file" ref="file" v-on:change="handleFileUpload" />
                                <br>
                            </div>     
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="addNewImg" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Cập nhật Ảnh</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="updateimages()" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <br>
                                <input v-model="form.id" type="hidden" ref="id" name="id"  class="form-control" >
                                <input type="file" id="ava" ref="ava" v-on:change="handleImageUpload" />
                                <br>
                            </div> 
                            <div class="form-group">
                                <label>Youtube</label>
                                <input type="checkbox" id="checkbox" ref="checkbox" v-model="form.checkbox">
                            </div>    
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button  type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh Tin Nội Bộ</h3>
                <div class="card-tools">
                    <a href="/them-bang-tin" class="btn btn-primary">
                        Thêm mới <i class="fa fa-plus"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <tbody>
                        <tr>
                            <th>ID</th>
                            <th>Ava</th>
                            <th>Tiêu Đề</th>
                            <th>Chuyên Mục</th>
                            <th>File Đính Kèm</th>
                            <th>Modify</th>
                        </tr>
                        <tr v-for="New in News.data" :key="New.id">
                            <td>{{New.id}}</td>
                            <td>
                                <a href="#" @click="editModalIMG(New)" v-if="New.is_youtube == 0">
                                    <img class="img-list" v-bind:src="'/uploads/news/' + New.ava">
                                </a>
                                <a href="#" @click="editModalIMG(New)" v-if="New.is_youtube == 1">
                                    <video id="video_background" preload="auto" autoplay="true" loop="loop" muted volume="0">
                                        <source v-bind:src="'/uploads/news/' + New.ava" type="video/mp4">
                                    </video>
                                </a>
                            </td>
                            <td>{{New.name}}</td>
                            <td>{{New.category}}</td>
                            <td>
                                <a href="#" @click="editModal(New)">
                                    <i class="fa fa-pencil gray"></i>
                                </a>
                                /
                                <a href="#" @click="download(New.files)"  v-if="New.files === ''">
                                    <i class="fa fa-download red"></i>
                                </a>
                                
                                <a href="#" @click="download(New.files)" v-if="New.files !== ''" >
                                    <i class="fa fa-download green"></i>
                                </a>
                            </td>
                            <td>
                                <router-link :to="{ name: 'editNews', params: { NewsId: New.id , FilesName : New.files }}"><i class="fa fa-edit blue"></i></router-link>
                                /
                                <a href="#" @click="deleteNews(New.id)">
                                    <i class="fa fa-trash red"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <pagination :data="News" :limit=3 @pagination-change-page="loadNEW">
                    <span slot="prev-nav">&lt; Trước</span>
                    <span slot="next-nav">Sau &gt;</span>
                </pagination>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            editmode: false,
            News: {},
            file: '',
            ava: '',
            files: new FormData(),
            form: new Form({
                id: '',
                name: '',
                content: '',
                category: '',
                checkbox: '',
            }),
            files: new FormData(),
        }
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
        update() {
            let formData = new FormData();
            formData.append('file', this.file);
            formData.append('id', this.$refs.id.value);

            axios.post('/api/updateFile',formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(() => {
                    this.loadNEW();
                    $('#addNew').modal('hide');
                    swal.fire(
                        'Lưu!',
                        'Đã Update File Đính Kèm',
                        'success'
                    )
                });
        },
        updateimages() {
            let formData = new FormData();
            formData.append('ava', this.ava);
            formData.append('id', this.$refs.id.value);
            formData.append('checkbox', this.$refs.checkbox.checked);

            axios.post('/api/updateImage',formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
            .then(() => {
                this.loadNEW();
                $('#addNewImg').modal('hide');
                swal.fire(
                    'Lưu!',
                    'Đã Update Ava',
                    'success'
                )
            });
        },
        editModal(New) {
            this.editmode = true;
            this.form.reset();
            $('#addNew').modal('show');
            this.form.fill(New);
        },
        editModalIMG(New) {
            this.form.reset();
            $('#addNewImg').modal('show');
            this.form.fill(New);
        },
        download(files) {
            if(files == '') {
                swal.fire({
                    title: 'Bài chưa có File đính kèm',
                    text: "Nên Chức Năng Này Không Hoạt Động",
                    type: 'warning',
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hiểu Rồi'
                })
            }else{
                location.replace("/uploads/news/" + files);
            }
        },
        loadNEW(page =1){
            let queryName = queryString.stringify({name: this.$parent.search});
            axios.get('/api/news?page=' + page + '&' + queryName)
            .then(response => {
                this.News = response.data
            })
        },
        deleteNews(id) {
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
                    axios.delete('api/news/' + id)
                    .then(() => {
                        swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!',
                            footer: '<a href>Why do I have this issue?</a>'
                        })
                    })
                    .catch(() => {
                        swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                        this.loadNEW();
                    })
                }
            })
        }
    },
    created() {
        this.loadNEW();
        Fire.$on('searching', () => {
            this.loadNEW();
        })
    }
}

</script>

<style>
  .img-list {
    hight: 50px;
    width: 50px;
  }
</style>