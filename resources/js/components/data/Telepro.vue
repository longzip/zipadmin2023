<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-4">
                <h1 class="m-0 text-dark">Telepro</h1>
            </div><!-- /.col -->
            <div class="col-sm-2">
                <a class="btn btn-success" @click="push">Đẩy</a>
            </div>
        </div>
  
        <div class="row">
            <div class="tableFixHead table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tên</th>
                            <th>Ghi Âm</th>
                            <th>SĐT</th>
                            <th>Email</th>
                            <th>Ghi Chú</th>
                            <th>Vị Trí Căn Hộ</th>
                            <th>Thành Viên</th>
                            <th>Email+Zalo</th>
                            <th>Quan Tâm</th>
                            <th>Lý Do Từ Chối</th>
                            <th>Loại Nhà</th>
                            <th>Nhu Cầu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="s in sales.data">
                            <tr>
                                <td>{{s.contact}}</td>
                                <td><a v-bind:href="s.recording" target="_blank">Nghe</a></td>
                                <td>{{s.phone}}</td>
                                <td>{{s.email}}</td>
                                <td>{{s.notes}}</td>
                                <td>{{s.results_parsed.canhocuakhotoathapnaotaioceanpark}}</td>
                                <td>{{s.results_parsed.canhokhcobaonhieunguoio}}</td>
                                <td>{{s.results_parsed.diachiemailzalocuakhachhanglagi}}</td>
                                <td>{{s.results_parsed.khachhangcoquantamtoithietkenoithatcuazipkhong}}</td>
                                <td>{{s.results_parsed.lidokhachhangtuchoikhongnhanthongtintuvan}}</td>
                                <td>{{s.results_parsed.loainhacuakhachla}}</td>
                                <td>{{s.results_parsed.nhucaucuthecuakhachhanglagi}}</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <div>
                <pagination :data="sales" @pagination-change-page="loadSales" :pageSize="5" :limit="5"></pagination>
            </div>
        </div>
        
        <!-- /.content -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            sales: [],
            opens: "center",
        }
    },
    methods: {
        loadSales(page = 1) {
            this.$Progress.start();
            axios.get('/api/telepro?page=' + page)
                .then(responsive => {
                    this.sales = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
        },
        push(){
        	axios.get('/api/telepro-push')
                .then(responsive => {
                    
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
        }
    },
    async mounted() {
        this.loadSales();
    }
}
</script>

<style>
.bg-new {
    background-color:#b3d7f5 !important;
}

.tableFixHead          { overflow-y: auto; height: 700px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1;}
.popup td {
    padding: .5rem !important;
}
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
tbody tr td { z-index: 0}
</style>