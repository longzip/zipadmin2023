<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-4 mt-2">
                <br>
                <h3 class="m-0 text-dark">Nguyên Liệu Sản Xuất</h3>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chọn T</label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="calendar" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn" label="t" track-by="id_table" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn">
                    </multiselect>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group mt-2">
                    <br>
                    <a href="#" @click="searchdata" class="btn btn-success">Search</i></a>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group mt-2">
                    <br>
                    <a href="#" @click="newOrder" class="btn btn-primary">
                        SX đơn
                    </a>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group mt-2">
                    <br>
                    <a href="#" @click="newLo" class="btn btn-primary">
                        SX lô
                    </a>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group mt-2">
                    <br>
                    <a href="#" @click="newLe" class="btn btn-primary">
                        SX lẻ
                    </a>
                </div>
            </div>
        </div>
 
        <div class="table-responsive tableFixHead">
            <table class="table table-bordered res">
                <thead>
                    <th>Sản Phẩm</th>
                    <th>Nhóm</th>
                    <!-- <th>STT</th>
                    <th>NVL</th>
                    <th>Số Lượng</th> -->
                </thead>
                <tbody>
                    <template v-for="(data,key,j) in nguyenvatlieu">
                        <tr v-for="(v,k,i) in data" >
                            <td :rowspan="1000000" v-if="i===0 && j===0">
                                <span  v-for="(k,d) in sp">
                                    <a href="#" @click="show(d,k)">
                                       <strong>{{k}}</strong> : {{d}}  <br>
                                    </a>
                                </span>
                            </td>
                            <td :rowspan="Object.keys(data).length" v-if="i===0">
                                <a href="#" @click="showmore(key,data)">
                                {{key}}
                                </a>
                            </td>
                     <!--        <td >{{i + 1}}</td>   
                            <td >{{k}}</td>                            
                            <td >{{v}}</td>    -->
                        </tr> 
                    </template>
                </tbody>
            </table>
        </div>

        <div class="modal fade" id="newOrder" tabindex="-1" role="dialog" aria-labelledby="newOrderTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addNewLabel">Thêm SX Đơn Hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label>Chọn Đơn Hàng</label>
                                <multiselect class="form-control" v-model="formDelivery.selectorder" :options="selectorders" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="so_number" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoOrder">
                                </multiselect>
                            </div>
                            <div class="form-group col-md-5">
                                <label>Chọn Sản Phẩm</label>
                                <multiselect class="form-control" v-model="formDelivery.choiceproduct" :options="products" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="item" track-by="id" :preselect-first="true" @close="findSoluong">
                                </multiselect>
                            </div>
                            <div class="form-group col-md-1">
                                <label for="off">SL: {{this.formDelivery.choiceproduct.quantity}}</label>
                                <input v-model="formDelivery.soluong" type="text" class="form-control" id="off">
                            </div>
                            <div class="form-group col-md-1 mt-2">
                                <br>
                                <a href="#" @click="dutinh" class="btn btn-primary">
                                    Dự Tính
                                </a>
                            </div>
                            <div class="form-group col-md-12">
                                <div class="card card-danger" v-if="note">
                                    <div class="card-header">
                                        <h3 class="card-title"><div v-html="note"></div></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="type == 1">
                            <div class="form-group col-md-12" v-if="typecheck == 2">
                                Chưa có bảng định mức
                            </div>
                            <div class="form-group col-md-12" v-if="typecheck == 1">
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <th>STT</th>
                                                <th>Mã</th>
                                                <th>Mô Tả</th>
                                                <th>Số Lượng</th>
                                            </tr>
                                            <tr v-for="(data,key) in nvl" >
                                                <td>{{key + 1}}</td>
                                                <td>{{data.code}}</td>
                                                <td>{{data.des}}</td>
                                                <td>{{data.soluong * formDelivery.soluong}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" v-if="typecheck == 1">
                        <a href="#" @click="sanxuat" class="btn btn-success">
                            Sản Xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="newLo" tabindex="-1" role="dialog" aria-labelledby="newLoTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addNewLabel">Thêm SX Lô</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-5">
                                <label>Chọn Sản Phẩm</label>
                                <multiselect class="form-control" v-model="formDelivery.choiceproduct" :options="sanpham" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" >
                                </multiselect>
                            </div>
                            <div class="form-group col-md-1">
                                <label for="off">SL: </label>
                                <input v-model="formDelivery.soluong" type="text" class="form-control" id="off">
                            </div>
                            <div class="form-group col-md-1 mt-2">
                                <br>
                                <a href="#" @click="dutinh" class="btn btn-primary">
                                    Dự Tính
                                </a>
                            </div>
                        </div>
                        <div class="row" v-if="type == 1">
                            <div class="form-group col-md-12" v-if="typecheck == 2">
                                Chưa có bảng định mức
                            </div>
                            <div class="form-group col-md-12" v-if="typecheck == 1">
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <th>STT</th>
                                                <th>Mã</th>
                                                <th>Mô Tả</th>
                                                <th>Số Lượng</th>
                                            </tr>
                                            <tr v-for="(data,key) in nvl" >
                                                <td>{{key + 1}}</td>
                                                <td>{{data.code}}</td>
                                                <td>{{data.des}}</td>
                                                <td>{{data.soluong * formDelivery.soluong}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" v-if="typecheck == 1">
                        <a href="#" @click="sanxuat" class="btn btn-success">
                            Sản Xuất
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="newLe" tabindex="-1" role="dialog" aria-labelledby="newLeTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addNewLabel">Thêm SX Lẻ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="show" tabindex="-1" role="dialog" aria-labelledby="showTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addNewLabel">{{namesp}} / {{sl}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group col-md-12">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <th>STT</th>
                                            <th>Mã</th>
                                            <th>Mô Tả</th>
                                            <th>Số Lượng</th>
                                        </tr>
                                        <tr v-for="(data,key) in dinhmuc" v-bind:class="{ ' bg-secondary text-white': (data.nvl == 1 ) , 'bg-new' : (data.nvl == 0)}">
                                            <template v-if="data.type == 1">
                                               
                                            </template>
                                            <td>
                                                <template v-if="data.type == 2">
                                                &emsp;
                                                </template>
                                                <template v-if="data.type == 3">
                                                &emsp;&emsp;
                                                </template>
                                                <template v-if="data.type == 4">
                                                &emsp;&emsp;&emsp;
                                                </template>
                                                <template v-if="data.type == 5">
                                                &emsp;&emsp;&emsp;&emsp;
                                                </template>
                                                <template v-if="data.type == 6">
                                                &emsp;&emsp;&emsp;&emsp;&emsp;
                                                </template>
                                                {{key + 1}}
                                            </td>
                                            <td>
                                                {{data.code}}
                                            </td>
                                            <td>
                                                {{data.des}}
                                            </td>
                                            <td>
                                                {{data.soluong * sl}}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="showhs" tabindex="-1" role="dialog" aria-labelledby="showhsTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addNewLabel">Lịch Sử</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group col-md-12">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <th>STT</th>
                                            <th>Sản Phẩm</th>
                                            <th>Số Lượng</th>
                                            <th>Đơn</th>
                                            <th>Người</th>
                                            <th>Thời Gian</th>
                                        </tr>
                                        <tr v-for="(data,key) in hs">
                                            <td>{{key + 1}}</td>
                                            <td>{{data.product}}</td>
                                            <td>{{data.soluong}}</td>
                                            <td>{{data.so_number}}</td>
                                            <td>{{data.name}}</td>
                                            <td>{{data.created_at}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="showmore" tabindex="-1" role="dialog" aria-labelledby="showhsTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"  id="addNewLabel">{{title}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group col-md-12">
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover">
                                    <tbody>
                                        <tr>
                                            <th>STT</th>
                                            <th>Item</th>
                                            <th>Số Lượng</th>
                                        </tr>
                                        <tr v-for="(data,key,i) in datamore">
                                            <td>{{i + 1}}</td>
                                            <td>{{key}}</td>
                                            <td>{{data}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
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
            typecheck:2,
            note:'',
            namesp:'',
            sl:'',
            title:'',
            type: 0,
            calendar: [],
            nvl: [],
            formDelivery: new Form({
                choiceproduct: '',
                selectorder: '',
                soluong: '',
                soluongold: '',
                deliverydate: '',
                all: '',
            }),
            selectorders: [],
            products: [],
            nguyenvatlieu: [],
            sp: [],
            sanpham:[],
            dinhmuc:[],
            hs:[],
            datamore:[],
        }
    },
    methods: {
        deleteSX(name,so,sl,date){
            let url = 'api/delete-sx?name=' + name + '&so=' + so + '&sl=' + sl + '&date=' + date;
            axios.get(url)
            .then(response => {
                this.loadData();
            });
        },
        show(name,sl){
            this.namesp = name;
            this.sl = sl;

            let url = 'api/show-sx?name=' + name + '&sl=' + sl;
            axios.get(url)
            .then(response => {
                $('#show').modal('show');
                this.dinhmuc = response.data;
                // console.log(response);
            });
        },
        showhs(date){
            let url = 'api/show-hs?date=' + date ;
            axios.get(url)
            .then(response => {
                $('#showhs').modal('show');
                console.log(response);
                this.hs = response.data;
            });
        },
        newOrder() {
            $('#newOrder').modal('show');
        },
        showmore(name,data) {
            this.datamore = data;
            this.title = name;
            $('#showmore').modal('show');
        },
        newLe() {
            $('#newLe').modal('show');
        },
        newLo() {
            $('#newLo').modal('show');
        },
        searchdata() {
            this.loadData();
        },
        loadData() {
            this.$Progress.start();
            if (this.$parent.saleSelected.length == 0) {
               var week = 0;
            }else{
               var week = this.$parent.saleSelected.id_table;
            }
            axios.get('api/showdinhmuc' + '?week=' + week)
            .then(responsive => {
                this.nguyenvatlieu = responsive.data.nvl;
                this.sp = responsive.data.sp;
                
                this.$Progress.finish();
            })
            .catch(() => {
                this.$Progress.fail();
            });
        },
        timTheoOrder(){
            let order = this.formDelivery.selectorder.id;
            let uri = 'api/find-products-order?id=' + order;
            axios.get(uri)
            .then(response => {
                this.formDelivery.choiceproduct = '';
                this.formDelivery.soluong = '';
                this.formDelivery.deliverydate = '';
                this.products = [];
                this.products = response.data;
            });

            let url = 'api/showproductsorder?id=' + this.formDelivery.selectorder.so_number;
            axios.get(url)
            .then(response => {
                this.note = response.data;
            });
        },
        findSoluong(){
            this.formDelivery.soluong = this.formDelivery.choiceproduct.quantity;
            this.formDelivery.deliverydate = this.formDelivery.choiceproduct.delivery;
        },
        dutinh(){
            let url = 'api/find-dinhmuc?name=' + this.formDelivery.choiceproduct.name;
            axios.get(url)
            .then(response => {
                this.nvl = response.data;
                this.type = 1;
                if(response.data.length == 0){
                    this.typecheck = 2;
                }else{
                    this.typecheck = 1;
                }
            });
        },
        sanxuat(){
            let url = 'api/add-dinhmuc?name=' + this.formDelivery.choiceproduct.name + '&soluong=' + this.formDelivery.soluong + '&so=' + this.formDelivery.selectorder.so_number;
            axios.get(url)
            .then(response => {
                this.formDelivery.choiceproduct = '';
                this.formDelivery.selectorder = '';
                this.formDelivery.soluong = '';
                this.formDelivery.deliverydate = '';
                swal.fire(
                    "Thành Công!",
                    "success"
                );
                $('#newOrder').modal('hide');
                $('#newLo').modal('hide');
                this.nvl = [];
                this.loadData();
            });
        }
    },
    created() {
        this.loadData();
        axios.get('api/select-week')
            .then(res => this.calendar = res.data);
        axios.get('/api/listOrders')
            .then(res => this.selectorders = res.data);
        axios.get('api/products-list')
            .then(response => {
                this.sanpham = response.data;
            });
    }
}
</script>
