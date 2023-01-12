<template>
    <div>
        <div class="card" v-for="task in tasks" :key="task.id">
            <div class="card-body">
                <h5 class="card-title" @click="showTask(task)">{{task.title}} <i class="fa fa-comment-o"></i>({{task.comments.length}})
                    <span v-show="task.completed" class="badge badge-success">Đã hoàn thành</span>
                    <!-- <span v-show="!task.completed" class="badge badge-light">Chưa hoàn thành</span> -->
                </h5>
                <footer class="blockquote-footer"><strong>Tạo cho {{task.user_name}}</strong> hết hạn <cite title="Source Title">{{task.duedate | myDateN}}</cite>, ngày tạo <cite title="Source Title">{{task.created_at }}</cite></footer>
                <p class="lead card-text">{{task.task}}</p>
                <p>
                    <span v-show="task.comments.length > 0">Bình luận:</span>
                    <ul>
                        <li v-for="comment in task.comments">({{comment.title}}) {{comment.body}}</li>
                    </ul>
                </p>
            </div>
            <div class="card-footer clearfix">
                <a v-show="!task.completed" href="#" @click="hoanThanh(task)" class="btn btn-sm btn-info float-left">Hoàn thành</a>
                <a v-show="!task.completed" href="#" @click="huyNhiemVu(task)" class="btn btn-sm btn-secondary float-right">Hủy</a>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['tasks'],
    methods: {
        showTask(task) {
            this.$emit('show-task', task);
        },
        hoanThanh(task) {
            axios.post('/api/tasks/' + task.id + '/completed')
                .then(response => {
                    task.completed = true;
                });
        },
        huyNhiemVu(task) {
            axios.delete('/api/tasks/' + task.id)
                .then(response => {
                    this.tasks.splice(this.tasks.indexOf(task), 1);
                });
        }
    }
}

</script>
