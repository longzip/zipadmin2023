<template>
    <div class="container-flush">
        <button type="button" class="btn btn-success"  @click.prevent="goBack">back</button>
        <div class="card">
            <div class="card-body table-responsive">
                <div v-for="New in News.data" :key="New.id">
            		<div class="col-12 col-md-12">
	                	<h2 class="c333">{{New.name}}</h2>
            		</div>
            		<div class="col-12 col-md-12">
	            		<div v-html="New.sapo"></div>
            		</div>
            		<div class="col-12 col-md-12" v-if="New.files !== ''" >
	            		<div class="pull-right" style="padding: 10px 0;">
	            			<span class="button-ef">
	                            <i class="fa fa-download green"></i>
	                            Tải File Đính Kèm
	                        </span>
	            		</div>
            		</div>
            		<div class="col-12 col-md-12" style="clear:both">
	                    <div v-html="New.content"></div>
            		</div>
                </div>
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
    props: ['NewsId'],
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
    created(){
        axios.get('/api/thong-bao/' + this.NewsId)
        .then(response => {
            this.News = response.data
        })
        .catch(()=> location.replace("/tin-tong-hop"))
    },
    methods: {
      goBack() {
        window.location.reload();
      }
    }
}

</script>

<style>
.button-ef {
  background-color: #004A7F;
  -webkit-border-radius: 10px;
  border-radius: 10px;
  border: none;
  color: #FFFFFF;
  cursor: pointer;
  display: inline-block;
  font-family: Arial;
  font-size: 14px;
  padding: 5px 10px;
  text-align: center;
  text-decoration: none;
}
@-webkit-keyframes glowing {
  0% { background-color: #004A7F; -webkit-box-shadow: 0 0 3px #004A7F; }
  50% { background-color: #0094FF; -webkit-box-shadow: 0 0 10px #0094FF; }
  100% { background-color: #004A7F; -webkit-box-shadow: 0 0 3px #004A7F; }
}
 
@-moz-keyframes glowing {
  0% { background-color: #004A7F; -moz-box-shadow: 0 0 3px #004A7F; }
  50% { background-color: #0094FF; -moz-box-shadow: 0 0 10px #0094FF; }
  100% { background-color: #004A7F; -moz-box-shadow: 0 0 3px #004A7F; }
}
 
@-o-keyframes glowing {
  0% { background-color: #004A7F; box-shadow: 0 0 3px #004A7F; }
  50% { background-color: #0094FF; box-shadow: 0 0 10px #0094FF; }
  100% { background-color: #004A7F; box-shadow: 0 0 3px #004A7F; }
}
 
@keyframes glowing {
  0% { background-color: #004A7F; box-shadow: 0 0 3px #004A7F; }
  50% { background-color: #0094FF; box-shadow: 0 0 10px #0094FF; }
  100% { background-color: #004A7F; box-shadow: 0 0 3px #004A7F; }
}
 
.button-ef {
  -webkit-animation: glowing 1500ms infinite;
  -moz-animation: glowing 1500ms infinite;
  -o-animation: glowing 1500ms infinite;
  animation: glowing 1500ms infinite;
}
</style>