<template>
    <div class="container-flush">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Thành Tích</h3>
            </div>
            <div class="container-chien">
                <div class="card-body">
                    <div class="row" v-for="(New, index) in News.data" :key="New.id" v-if="index == 0">
                        <div class="col-12 col-12">
                            <router-link :to="{ name: 'readthongbao', params: { NewsId: New.id }}">
                                <h2 class="c333 fz24">{{New.name}}</h2>
                            </router-link>
                        </div>
                        <div class="col-12 col-12">
                            <div v-if="New.is_youtube == 0">
                                <img class="w100" v-bind:src="'/uploads/news/' + New.ava">
                            </div>
                            <div v-if="New.is_youtube == 1">
                                <video id="video_background" preload="auto" autoplay="true" loop="loop" muted volume="0">
                                    <source v-bind:src="'/uploads/news/' + New.ava" type="video/mp4">
                                </video>
                            </div>
                        </div>
                        <div class="col-12 col-12">
                            <div v-html="New.sapo + '...'"></div>
                            <div class="badge res-hide">{{New.user_name}}</div>
                            <div class="badge res-hide badge-primary badge-pill">{{New.created_at}}</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer rmbg">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center" v-for="(New, index) in News.data" :key="New.id" v-if="index != 0">
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <router-link :to="{ name: 'readthongbao', params: { NewsId: New.id }}">
                                        <h5 class="c333">{{New.name}}</h5>
                                    </router-link>
                                </div>
                                <div class="col-md-6 col-6">
                                    <router-link :to="{ name: 'readthongbao', params: { NewsId: New.id }}">
                                        <div v-if="New.is_youtube == 0">
                                            <img class="w100" v-bind:src="'/uploads/news/' + New.ava">
                                        </div>
                                        <div v-if="New.is_youtube == 1">
                                            <video id="video_background" preload="auto" autoplay="true" loop="loop" muted volume="0">
                                                <source v-bind:src="'/uploads/news/' + New.ava" type="video/mp4">
                                            </video>
                                        </div>
                                    </router-link>
                                </div>
                                <div class="col-md-6 col-6 col-8-fix">
                                    <div style="overflow: hidden;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 3;-webkit-box-orient: vertical;" v-html="New.sapo + '...'"></div>
                                    <div class="badge res-hide">{{New.user_name}}</div>
                                    <span class="badge res-hide badge-primary badge-pill">{{New.created_at}}</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer rmbg">
                    <pagination :data="News" :limit=3 @pagination-change-page="loadNEW">
                        <span slot="prev-nav">&lt; Trước</span>
                        <span slot="next-nav">Sau &gt;</span>
                    </pagination>
                </div>
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
            files: new FormData(),
            form: new Form({
                id: '',
                name: '',
                content: '',
                category: '',
            }),
            files: new FormData(),
        }
    },
    methods: {
        loadNEW(page =1){
            let queryName = queryString.stringify({name: this.$parent.search});
            axios.get('/api/thanhtich?page=' + page + '&' + queryName)
            .then(response => {
                this.News = response.data
            })
        },
        detail(id) {
            location.replace("/thong-bao/" + id);
        },
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
    .w100 {
        width: 100%;
    }
    h2,h5 {
        text-decoration: none;
    }
    .c333 {
        color : #333;
        font-weight: bold; 
    }
    a:hover{
        text-decoration: none;
    }
    .rmbg {
        background-color:transparent;
    }
    .col-8-fix {
        padding:0px;
    }
    @media only screen and (min-width: 1200px) {
        .container-chien{
            max-width: 660px;
            margin: 0 auto;
        }
    }
    @media only screen and (max-width: 768px) {
        .fz24 {
            font-size: 24px;
        }
    }
    #video_background {
        width: 100%;
        z-index: -1000;
        overflow: hidden;
    }
</style>