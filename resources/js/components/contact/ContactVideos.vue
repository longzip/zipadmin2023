<template>
    <div class="card">
        <div class="card-footer">
            <div class="card-body" v-for="item in videos" :key="item.id">
                <video width="400" controls v-if="item.title && item.title.length > 20">
                    <source v-bind:src="item.title" type="video/mp4">
                </video>
                <iframe width="560" height="315" :src="'https://www.youtube.com/embed/'+item.title" v-if="item.title && item.title.length < 20" frameborder="0" allowfullscreen></iframe>
                <p>
                    <a href="#">{{item.user_name}}</a> đã thêm {{item.created_at | myDateN}} <a href="#" @click="deleteVideo(item)"><i class="fa fa-trash"></i> Xóa video</a>
                </p>
            </div>
            <label>Chọn
                <input type="file" id="file" ref="file" v-on:change="handleFileUpload()" />
            </label>
            <VueLoadingButton v-show="isUpload" style="margin-right: 5px;" aria-label="Post message" class="btn btn-primary float-right" @click.native="submitFile" :loading="isLoading" :styled="false">Tải lên</VueLoadingButton>
            <div class="form-group">
                <input v-model="video_src" type="text" class="form-control" placeholder="Dán đường đẫn video">
                <VueLoadingButton v-show="!isUpload" style="margin-right: 5px;" aria-label="Post message" class="btn btn-primary float-right" @click.native="themvideo" :loading="isLoading" :styled="false">Thêm</VueLoadingButton>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['videos'],
    data() {
        return {
            isLoading: false,
            isUpload: false,
            video_src: '',
            file: ''
        }
    },
    methods: {
        themvideo() {
            this.isLoading = true;
            setTimeout(() => (this.isLoading = false), 30000);
            this.$emit('create-video', this.video_src);
        },
        submitFile() {
            this.isLoading = true;
            setTimeout(() => (this.isLoading = false), 30000);
            this.$emit('upload-video', this.file);
        },

        handleFileUpload() {
            this.file = this.$refs.file.files[0];
            this.isUpload = true;
        },
        deleteVideo(video) {
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
                    axios.delete('api/videos/' + video.id)
                        .then(() => {
                            this.videos.splice(this.videos.indexOf(video), 1);
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
