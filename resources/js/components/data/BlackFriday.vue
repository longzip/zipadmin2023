<template>
    <div class="">
        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data BlackFriday</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên</th>
                                    <th>phone</th>
                                    <th>Thông tin khác</th>
                                    <th>Trạng Thái</th>
                                </tr>
                                <tr v-for="(data, index) in datas.data" :key="data.id">
                                    <td>{{index}}</td> 
                                    <td><span>{{data.last_name}}</span>
                                    </td>
                                    <td><a href="#" @click="call(data)">{{data.phone}}</a></td>
                                    <td>{{data.address}}</td>
                                    <td>
                                    	<template v-for="d in data.tasks">
                                    		<div v-if="d.priority == 'Bước 0'">
                                    			{{d.title}}
                                    		</div>
                                    	</template>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="datas" @pagination-change-page="loadData" :pageSize="5" :limit="5"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            datas: {},
            data: [],
            pagedata: '',
        }
    },
    methods: {
        loadData(page = 1) {
        	// console.log(page);
        	this.pagedata = page;
            let queryName = queryString.stringify({name: this.$parent.search});
            axios.get("/api/blackfriday?page=" + page + '&' + queryName).then(({ data }) => (this.datas = data));
        },
        call(data){
            this.data = data;
		    window.location = 'tel:0'+data.phone;
            $('#showData').modal('show');
            this.form.id = data.id;
            this.form.name = data.name;
            this.form.phone = data.phone;
            this.form.note = data.note;
            this.form.kv = data.kv;
		},
    },
    created() {
        this.loadData();
        Fire.$on('AfterCreate', () => {
            this.loadData();
        });
        Fire.$on('searching', () => {
            this.loadData();
        })
    }
}

</script>

