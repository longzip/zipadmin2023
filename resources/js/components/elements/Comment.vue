<template>
    <div class="card card-widget">
        <div v-for="comment in comments" class="card-footer card-comments">
            <div class="card-comment">
                <img class="img-circle img-sm" src="/img/man.png" alt="User Image">
                <div class="comment-text">
                    <span class="username">
                        {{comment.title}}
                        <span class="text-muted float-right">{{comment.created_at | myDateN}}</span>
                    </span><!-- /.username -->
                    {{comment.body}}
                </div>
                <!-- /.comment-text -->
            </div>
        </div>
        <!-- /.card-footer -->
        <div class="card-footer">
            <!-- .img-push is used to add margin to elements next to floating images -->
            <div class="img-push">
                <textarea v-model="comment" class="form-control form-control" placeholder="Nội dung bình luận"></textarea>
                <button @click.prevent="commentStore" class="btn btn-primary">Gửi bình luận</button>
            </div>
        </div>
        <!-- /.card-footer -->
    </div>
</template>
<script>
export default {
    props: ['comments','contactId'],
    data() {
        return {
            comment: ""
        }
    },
    methods: {
        commentStore() {
            this.$emit('create-comment', this.comment);
            this.comment = "";
        }
    }
}

</script>
