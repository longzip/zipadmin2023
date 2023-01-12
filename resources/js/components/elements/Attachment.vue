<template>
    <div class="row">
        <div class="card col-md-6" v-for="item in items" :key="item.id">
            <!-- data-fancybox="gallery" -->
            <a data-fancybox="gallery" v-bind:href="'http://noithatzip.com.vn/00khtn'+item.src" v-if="item.idfolder < 33906">
                <img v-bind:src="'http://noithatzip.com.vn/00khtn'+item.src" class="card-img-top" alt="Ấn vào tôi để xem ảnh.">
            </a>
            <!-- data-fancybox="gallery" -->
            <a data-fancybox="gallery" v-bind:href="item.src" v-if="item.idfolder > 28387">
                <img v-bind:src="item.src" class="card-img-top" alt="Ấn vào tôi để xem ảnh.">
            </a>
            <!-- data-fancybox="gallery" -->
            <div class="card-body">
                <h3>{{item.title}}</h3>
                <small><a href="#">{{item.user_name}}</a> đã thêm {{item.created_at | myDateN}} <a href="#" @click="deleteItem(item)"><i class="fa fa-trash"></i> Xóa</a></small>
                <p class="card-text">
                    {{item.description}}
                </p>
            </div>
        </div>
        <div v-if="upload" class="card">
            <div class="card-footer">
                <label>Chọn hình ảnh
                    <input type="file" id="files" ref="files" v-on:change="handleFileUpload()" multiple />
                </label>
                <VueLoadingButton style="margin-right: 5px;" aria-label="Post message" class="btn btn-primary float-right" @click.native="submitFile" :loading="isLoading" :styled="false">Tải lên</VueLoadingButton>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['items', 'upload'],
    data() {
        return {
            isLoading: false,
            files: ''
        }
    },
    methods: {
        submitFile() {
            this.isLoading = true;
            setTimeout(() => (this.isLoading = false), 5000);
            this.$emit('upload-image', this.files);
        },

        handleFileUpload() {
            this.files = this.$refs.files.files;
            this.isUpload = true;
        },
        deleteItem(item) {
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
                    axios.delete('api/attachments/' + item.id)
                        .then(() => {
                            this.items.splice(this.items.indexOf(item), 1);
                            swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        })
                        .catch(() => {
                            swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                footer: '<a href>Why do I have this issue?</a>'
                            })
                        });

                }
            })
        }
    }
}

</script>
