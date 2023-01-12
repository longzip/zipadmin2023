<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-2">
                <h3 class="m-0 text-dark">Tài Liệu</h3>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Phòng<a href="#" @click="reSelectSanPham"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="phong" :options="filphong" :multiple="false" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn">
                    </multiselect>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Sản Phẩm<a href="#" @click="reSelectSanPham"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="filsanpham" :multiple="true" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn">
                    </multiselect>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Loại<a href="#" @click="reSelectSanPham"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="loai" :options="filloai" :multiple="false" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn">
                    </multiselect>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Tìm </label><br/>
                    <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</a>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Thêm </label><br/>
                    <a href="#" @click="addModal" class="btn btn-success">Thêm</a>
                </div>
            </div>
        </div>
 
        <div class="row">
            <div class="tableFixHead table-responsive">
                <div class="row">
                    <template v-for="d in listdata">
                        <br>
                        <div class="col-2" v-if="d.loai !== 22">
                            <a data-fancybox="gallery" v-bind:href="'/uploads/tailieuanh/'+d.link" target="_blank"><img v-bind:src="'/uploads/tailieuanh/'+d.link" alt="" width="100%"></a>
                            <br><span><a href="#" @click="addPhong(d.id)"><i class="fa fa-plus">Thêm Phòng</i></a></span>
                        </div>
                        <div class="col-1"  v-if="d.loai === 22">
                            <a href="#" @click="showdata(d.link)" class="btn btn-success">{{d.link}}</a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <!-- /.content -->
        <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Add New</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Chọn Phòng</label>
                                <multiselect class="form-control" style="padding: 0;" v-model="listphong" :options="filphong" :multiple="false" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn">
                                </multiselect>
                            </div>
                            <br/>
                            <div class="form-group">
                                <label>Chọn Sản Phẩm</label>
                                <multiselect class="form-control" style="padding: 0;" v-model="listsanpham" :options="filsanpham" :multiple="true" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn">
                                </multiselect>
                            </div>
                            <br/>
                            <div class="form-group">
                                <label>Chọn Loại</label>
                                <multiselect class="form-control" style="padding: 0;" v-model="listloai" :options="filloai" :multiple="false" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn">
                                </multiselect>
                            </div>
                            <br/>
                            <div class="row col-md-12">
                                <label>Ảnh:</label>
                                <input type="file" id="files" ref="files" v-on:change="handleFileUpload()" multiple />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="phong" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Add Phòng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="createRoom()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Chọn Phòng</label>
                                <multiselect class="form-control" style="padding: 0;" v-model="listphong" :options="filphong" :multiple="false" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn">
                                </multiselect>
                            </div>
                            <input type="text" name="" class="hidden" v-model="typephong">
                            <br/>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="thietke" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                   
                    <div class="modal-body">
                        <template v-for="d in listtk">
                            <br>
                            <div class="col-2">
                                <a data-fancybox="gallery" v-bind:href="'/uploads/tailieuanh/'+d.link+'/'+d.file" target="_blank"><img v-bind:src="'/uploads/tailieuanh/'+d.link+'/'+d.file" alt="" width="100%"></a>
                            </div>
                        </template>
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
            file: '',
            phong: '',
            typephong: '',
            loai: '',
            listphong: '',
            listsanpham: '',
            listloai: new Form({
                id: '20',
                name: 'Tiêu Chuẩn',
            }),
            list: '',
            filsanpham: [],
            filloai: [],
            filphong: [],
            listdata: [],
            listtk: [],
        }
    },
    methods: {
        showdata(so){
            axios.get('api/showdatatailieu' + '?link=' + so)
                .then(responsive => {
                    this.listtk = responsive.data;
                    $('#thietke').modal('show');

                }).catch(error => {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: error.response.data.message,
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });
        },
        searchdata(){
            this.loaddata();
        },
        handleFileUpload() {
            this.files = this.$refs.files.files;
        },
        reSelectSanPham() {
            this.$parent.showroomsSelected = [];
        },
        addModal(){
            $('#ModalCenter').modal('show');
        },
        addPhong(id){
            this.typephong = id;
            $('#phong').modal('show');
        },
        createPhong(){
            let formData = new FormData();
            if (this.files) {
                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    formData.append('file[' + i + ']', file);
                }
            }
            if (this.listphong) {
                formData.append('phong', this.listphong.id);
            }
            // if (this.listsanpham) {
            //     formData.append('phong', this.listphong.name);
            // }
            if (this.listloai) {
                formData.append('loai', this.listloai.id);
            }
            for( var i = 0; i < this.listsanpham.length; i++ ){
                let list = this.listsanpham[i];
                formData.append('list[' + i + ']', list['id']);
            }
            axios.post('/api/addTaiLieu',formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
            .then(() => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Thêm Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                $('#ModalCenter').modal('hide');
            })
            .catch(error => {
                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'không được bỏ trống 4 ô đầu',
                    footer: '<a href>Why do I have this issue?</a>'
                })
            });
        },
        createRoom(){
            let formData = new FormData();
            
            if (this.listphong) {
                formData.append('phong', this.listphong.id);
            }
            formData.append('id', this.typephong);
            
            axios.post('/api/addroomTaiLieu',formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
            .then(() => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Thêm Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                $('#phong').modal('hide');
            })
            .catch(error => {
                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'không được bỏ trống 4 ô đầu',
                    footer: '<a href>Why do I have this issue?</a>'
                })
            });
        },
        createUser(){
            let formData = new FormData();
            if (this.files) {
                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    formData.append('file[' + i + ']', file);
                }
            }
            if (this.listphong) {
                formData.append('phong', this.listphong.id);
            }
            // if (this.listsanpham) {
            //     formData.append('phong', this.listphong.name);
            // }
            if (this.listloai) {
                formData.append('loai', this.listloai.id);
            }
            for( var i = 0; i < this.listsanpham.length; i++ ){
                let list = this.listsanpham[i];
                formData.append('list[' + i + ']', list['id']);
            }
            axios.post('/api/addTaiLieu',formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
            .then(() => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Thêm Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                $('#ModalCenter').modal('hide');
            })
            .catch(error => {
                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: 'không được bỏ trống 4 ô đầu',
                    footer: '<a href>Why do I have this issue?</a>'
                })
            });
        },
        loaddata() {
            this.sel = this.$parent.showroomsSelected.map(sale => {
                return sale.id;
            });
            let bp = queryString.stringify({ sanpham: this.sel }, { arrayFormat: 'bracket' });
            console.log(bp);
            if (this.phong) {
                var phong =  '&phong=' + this.phong.id;
            }else{
                var phong = '';
            }
            if (this.loai) {
                var loai =  '&loai=' + this.loai.id;
            }else{
                var loai = '';
            }
            console.log(this.phong);
            axios.get('api/searchtailieu' + '?' + bp +  phong + loai)
                .then(responsive => {
                    this.listdata = responsive.data;
                }).catch(error => {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: error.response.data.message,
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });
        },
    },
    created() {
        axios.get("/api/fil-tailieuphong")
        .then(response => {
            this.filphong = response.data;
        });
        axios.get("/api/fil-tailieusanpham")
        .then(response => {
            this.filsanpham = response.data;
        });
        axios.get("/api/fil-tailieuloai")
        .then(response => {
            this.filloai = response.data;
        });
    }
}
</script>

