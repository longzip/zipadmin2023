<template>
    <div class="container">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Tìm kiếm</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn năm </label>
                            <select  class="form-control" v-model="search.year" @change="onChangeSearch($event)">
                                <option v-for="option in $parent.year" v-bind:value="option.value" :selected="option.value == search.year">
                                    {{ option.nam }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn P </label>
                            <select  class="form-control" v-model="search.pt" @change="onChangeSearch($event)">
                                <option v-for="option in $parent.pt" v-bind:value="option.value" :selected="option.value == search.pt">
                                    {{ option.index }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label></label>
                            <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title pull-left">Bảng target Sản Phẩm</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><a href="#" class="btn btn-info " v-if="login.id == 9003" @click="cre()">Update</a></th>
                            <th>Ivy</th>
                            <th>Iris</th>
                            <th>Olive</th>
                            <th>Tulip</th>
                        </tr>
                        <tr>
                            <th>Tổng</th>
                            <th><input type="number" @keyup="reivy" v-model="totalkey.totalivy" style="width:60px;"> </th>
                            <th><input type="number" @keyup="reiris" v-model="totalkey.totaliris" style="width:60px;"></th>
                            <th><input type="number" @keyup="redahlia" v-model="totalkey.totaldahlia" style="width:60px;"></th>
                            <th><input type="number" @keyup="rekhac" v-model="totalkey.totalkhac" style="width:60px;"></th>
                        </tr>
                    </thead>
                    <tbody v-for="(pro,key,i) in allproduct.list">
                        <tr>
                            <td>T{{4-key}}</td>
                            <td><input type="number" @keyup="re" v-model="pro.ivy " style="width:60px;"></td>
                            <td><input type="number" @keyup="re" v-model="pro.iris" style="width:60px;"></td>
                            <td><input type="number" @keyup="re" v-model="pro.dahlia" style="width:60px;"></td>
                            <td><input type="number" @keyup="re" v-model="pro.khac " style="width:60px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title pull-left">Bảng target cho Showroom</h3>
                <a href="#" class="btn btn-info pull-right" v-if="login.id == 9003 || login.id == 9074" @click='duyetsale()'>Duyệt Sales</a>
                <a href="#" class="btn btn-info pull-right" v-if="login.id == 9003" @click='showUser()'>Phân Quyền</a>
                <a href="#" class="btn btn-info pull-right" v-if="login.id == 9003 && checkstt.check==1" @click='block()'>Khóa</a>
                <a href="#" class="btn btn-info pull-right" v-if="login.id == 9003 && checkstt.check==0" @click='open()'>Mở</a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead class="bg-info">
                        <tr>
                            <th rowspan="2" style="width: 10px">#</th>
                            <th rowspan="2" >Showroom</th>
                            <th rowspan="2" >Khu vuc</th>
                            <th>KH15</th>
                            <th>KHTN <small class="text-secondary pull-right"> / Iris</small><small class="text-danger pull-right">  Ivy </small></th>
                            <th>DH</th>
                            <th>DS</th>
                        </tr>
                        <tr>
                            <th>{{ zipTarget.kh15_number | currency }}</th>
                            <th>{{ zipTarget.number | currency }}</th>
                            <th>{{ zipTarget.order_number | currency }}</th>
                            <th>{{ zipTarget.amount_number  | currency }}</th>
                        </tr>
                    </thead>
                    <tbody v-for="(showroom,key,i) in salesTarget">
                        <tr class="bg-light">
                            <th colspan="3">{{key}}</th>
                            <th> {{kh15Total(showroom)}}</th>
                            <th> {{total(showroom)}}</th>
                            <th> {{orderTotal(showroom)}}</th>
                            <th> {{amountTotal(showroom) | currency}} </th>
                        </tr>
                        <tr v-for="(sale, index) in showroom">
                            <td style="width: 10px">
                                <span v-if="i == 0">{{index + 1}}</span>
                                <span v-if="i == 1">{{index + 1 + salesTarget['Hà Nội'].length}}</span>
                                <span v-if="i == 2">{{index + 1 + salesTarget['Hà Nội'].length + salesTarget['Hồ Chí Minh'].length}}</span>
                                <span v-if="i == 3">{{index + 1 + salesTarget['Hà Nội'].length + salesTarget['Hồ Chí Minh'].length + salesTarget['Miền Nam'].length}}</span>
                                <span v-if="i == 4">{{index + 1 + salesTarget['Hà Nội'].length + salesTarget['Hồ Chí Minh'].length + salesTarget['Miền Nam'].length  + salesTarget['Miền Bắc'].length}}</span>
                            </td>
                            <td>{{sale.name}}</td>
                            <td>{{sale.warehouse}}</td>
                            <td><a href="#" @click="showTarget(sale)">{{sale.kh15_count}}</a></td>
                            <td><a href="#" @click="showTarget(sale)">{{sale.count}}</a><small class="text-secondary pull-right"> / {{sale.iris}}</small><small class="text-danger pull-right">  {{sale.ivy}}</small></td>
                            <td><a href="#" @click="showTarget(sale)">{{sale.order_count}}</a></td>
                            <td><a href="#" @click="showTarget(sale)">{{sale.amount_count | currency}}</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="saleTargetModel" tabindex="-1" role="dialog" aria-labelledby="saleTargetModelTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saleTargetModelTitle">Target <small>{{sale.name}}</small></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                       <small class="text-secondary pull-right"> / Iris</small><small class="text-danger pull-right"> Ivy </small>
                        <table class="table table-bordered" v-show="sale.targets.length > 0">
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    <th>KH15</th>
                                    <th>KHTN </th>
                                    <th>Iris</th>
                                    <th>Ivy</th>
                                    <th>DH</th>
                                    <th style="width: 130px">DS</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <thead class="bg-light">
                                <tr>
                                    <th>P</th>
                                    <th>{{sale.kh15_count}}</th>
                                    <th>{{sale.count}}</th>
                                    <th>{{sale.iris}}</th>
                                    <th>{{sale.ivy}}</th>
                                    <th>{{sale.order_count}}</th>
                                    <th>{{sale.amount_count | currency}}</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(target,index) in sale.targets">
                                    <td>T{{5 - ++index}}</td>
                                    <td><input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.kh15_number"></td>
                                    <td>
                                        <input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.number">
                                    </td>
                                    <td><input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.iris"></td>
                                    <td><input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.ivy"></td>
                                    <td><input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.order_number"></td>
                                    <td><input type="text" class="form-control" name="redirect" @keyup="recalculator" v-model="target.amount_number" ></td>
                                    <td> {{ target.amount_number | currency }} </td>
                                </tr>
                            </tbody>
                        </table>
                        <form class="form-inline">
                            <div class="form-group mb-2">
                                <label for="staticEmail2">KH15</label>
                                <input v-model="target.kh15_number" type="number" class="form-control" id="staticEmail2" min="0" max="999">
                            </div>
                            <div class="form-group mb-2">
                                <label for="staticEmail2">KHTN</label>
                                <input v-model="target.number" type="number" class="form-control" id="staticEmail2" min="0" max="999">
                            </div>
                            <div class="form-group mb-2">
                                <label for="staticEmail2">DH</label>
                                <input v-model="target.order_number" type="number" class="form-control" id="staticEmail2" min="0" max="999">
                            </div>
                            <div class="form-group mb-2">
                                <label for="staticEmail2">Doanh số</label>
                                <input v-model="amount" type="number" class="form-control" id="staticEmail2" min="0" max="999999999">
                                <small>{{target.amount_number | currency}} VNĐ</small>
                            </div>
                            <button type="submit" @click.prevent="createTarget()" v-on:click="counter += 1" class="btn btn-primary mb-2">Thêm</button>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="store">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="note">
            <div class="modal-dialog">
                <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Thông Báo</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <strong>Bạn đã chốt Target</strong>
                    <br>
                    Liên hệ IT để mở khóa
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

                </div>
            </div>
        </div>
        <div class="modal fade" id="saleModel" tabindex="-1" role="dialog" aria-labelledby="saleTargetModelTitle" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 550px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Phân quyền nhân viên được xem</h3>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-11">
                            <div class="form-group">
                                <label>Chọn nhân viên</label>
                                <multiselect class="form-control" v-model="form.user" :options="asm" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                                </multiselect>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Chọn năm </label>
                                            <select  class="form-control" v-model="form.year" @change="onChange($event)">
                                                <option v-for="option in $parent.year" v-bind:value="option.value" :selected="option.value == form.year">
                                                    {{ option.nam }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Chọn I </label>
                                            <select  class="form-control" v-model="form.i" @change="onChangei($event)">
                                                <option v-for="option in $parent.i" v-bind:value="option.value" >
                                                    {{ option.quy }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Chọn P </label>
                                            <select  class="form-control" v-model="form.pt" @change="onChangept($event)">
                                                <option v-for="option in $parent.pt" v-bind:value="option.value">
                                                    {{ option.index }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-11">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <td>P</td>
                                        <td>Tên</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(items,key) in thang">
                                        <td>P{{key}} ({{items.length}})</td>
                                        <td>
                                            <span v-for="item in items">
                                                {{item.name}} <a href="#" @click="del(item.id)">x</a>,
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" @click="roles">
                            Save
                        </button>
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
            counter: 0,
            checkstt: [],
            login: [],
            costcenters: [],
            allproduct: {
                list: [],
            },
            resources: [],
            sale_ids: [],
            thang: [],
            salesTarget: [],
            salesTarget2: [],
            targets: {},
            asm: [],
            form: new Form({
                year: '15',
                i: '',
                pt: '',
                user: '',
            }),
            search: new Form({
                year: '15',
                pt: '5',
            }),
            pro:[],
            totalkey:[],
            zipTarget:{
                kh15_number: 0,
                number: 0,
                order_number: 0,
                amount_number: 0
            },
            sale: {
                targets: []
            },
            amount: 0,
            p: [],
            p2: [],
            target: {
                id: 0,
                name: '',
                number: 0,
                kh15_number: 0,
                order_number: 0,
                amount_number: 0,
                number1: 0,
                kh15_number1: 0,
                order_number1: 0,
                amount_number1: 0,
                number2: 0,
                kh15_number2: 0,
                order_number2: 0,
                amount_number2: 0,
                number3: 0,
                kh15_number3: 0,
                order_number3: 0,
                amount_number3: 0,
                close: moment().endOf('week').format().slice(0, 10)
            },
            uri: '/api/costcenters-targets',
        }
    },
    methods: {
        searchdata() {
            this.loadSalesTarget();
        },
        onChange(event) {
            axios.post('/api/picklists/p', {year: this.form.year}).then(res => this.thang = res.data);
        },
        onChangeSearch(event) {
            this.addWeek();
        },
        onChangei(event) {
            this.form.pt = '';
        },
        onChangept(event) {
            this.form.i = '';
        },
        showUser() {
            $('#saleModel').modal('show');
        },
        block() {
            axios.get('api/block-target'+ '?' + this.getPara())
            .then(()=> {
                swal.fire({
                        type: 'success',
                        title: 'Thành Công...',
                        text: 'Bạn Đã Khóa Target!',
                        footer: '<a href>Liên hệ IT khi mở Target?</a>'
                    })
                this.loadSalesTarget();
            })
            .catch(() => {
                swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Bạn không có quyền cập nhật!',
                        footer: '<a href>Liên hệ IT để có thông tin?</a>'
                    })
            });
        },
        open() {
            axios.get('api/open-target'+ '?' + this.getPara())
            .then(()=> {
                swal.fire({
                        type: 'success',
                        title: 'Thành Công...',
                        text: 'Bạn Đã Mở Khóa Target!',
                        footer: '<a href>Liên hệ IT khi mở Target?</a>'
                    })
                this.loadSalesTarget();
            })
            .catch(() => {
                swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Bạn không có quyền cập nhật!',
                        footer: '<a href>Liên hệ IT để có thông tin?</a>'
                    })
            });
        },
        loadSalesTarget() {
            this.$Progress.start();
            axios.get(this.uri + '?' + this.getPara())
                .then(responsive => {
                    this.salesTarget = responsive.data;
                    this.salesTarget2 = _.flatten(Object.values(responsive.data));
                    this.reTotal();
                    this.$Progress.finish();
                })
                .catch(() => {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Bạn không có quyền xem!',
                        footer: '<a href="https://www.facebook.com/tran.hoa.7967">Liên hệ giám đốc để có thông tin?</a>'
                    })
                });

            var block = new Date(this.datepick());
            block.setDate(block.getDate()+parseInt(27));

            axios.get('api/checkblock' + '?date=' + moment(String(block)).format('YYYY-MM-DD'))
                .then(responsive => {
                    this.checkstt = responsive.data;
                });

            axios.get('api/allproduct?'+ this.getPara())
                .then(responsive => {
                    this.allproduct = responsive.data;
                    this.pro = responsive.data.list;
                    this.totalkey = responsive.data.total;
                    // console.log(this.total);
                });
        },
        del(item){
            console.log(item)
            axios.post('api/delete-role', {id: item})
            .then(()=> {
                axios.post('/api/picklists/p', {year: this.form.year}).then(res => this.thang = res.data);
            })
            .catch(() => {
            });
        },
        duyetsale(){
                axios.get('api/addtargetsaleser?year=' + this.search.year + '&pt=' + this.search.pt)
                    .then(response => {
                        swal.fire({
                            type: 'success',
                            title: 'Thành Công',
                            text: 'Bạn đã cập nhật lại target sales, liên hệ với sales để thông báo!',
                            footer: ''
                        })
                    });
        },
        addWeek() {
            this.p = [];
            var t1 = new Date(this.datepick());
            t1.setDate(t1.getDate()+parseInt(6));
            var t2 = new Date(this.datepick());
            t2.setDate(t2.getDate()+parseInt(13));
            var t3 = new Date(this.datepick());
            t3.setDate(t3.getDate()+parseInt(20));
            var t4 = new Date(this.datepick());
            t4.setDate(t4.getDate()+parseInt(27));
            this.p.push(moment(String(t1)).format('YYYY-MM-DD'));
            this.p.push(moment(String(t2)).format('YYYY-MM-DD'));
            this.p.push(moment(String(t3)).format('YYYY-MM-DD'));
            this.p.push(moment(String(t4)).format('YYYY-MM-DD'));
        },
        timTheoShowroom() {
            let costcenter_ids = this.$parent.showroomsSelected.map(costcenter => {
                return costcenter.id;
            });
            if (costcenter_ids.length > 0) {
                let uri = 'api/find-user-by-costcenter?' + queryString.stringify({ costcenters: costcenter_ids }, { arrayFormat: 'bracket' });
                axios.get(uri)
                    .then(response => {
                        this.$parent.saleSelected = this.resources = response.data;
                    });
            } else {
                this.$parent.saleSelected = [];
            }

        },
        timTheoSale() {
        },
        datepick(){
            if (this.search.year == 14) {
                if(this.search.pt == 1){
                    var date = "2019-12-30";
                }
                if(this.search.pt == 2){
                    var date = "2020-01-27";
                }
                if(this.search.pt == 3){
                    var date = "2020-02-24";
                }
                if(this.search.pt == 4){
                    var date = "2020-03-23";
                }
                if(this.search.pt == 5){
                    var date = "2020-04-20";
                }
                if(this.search.pt == 6){
                    var date = "2020-05-18";
                }
                if(this.search.pt == 7){
                    var date = "2020-06-15";
                }
                if(this.search.pt == 8){
                    var date = "2020-07-13";
                }
                if(this.search.pt == 9){
                    var date = "2020-08-10";
                }
                if(this.search.pt == 10){
                    var date = "2020-09-07";
                }
                if(this.search.pt == 11){
                    var date = "2020-10-05";
                }
                if(this.search.pt == 12){
                    var date = "2020-11-02";
                }
                if(this.search.pt == 13){
                    var date = "2020-11-30";
                }
            }
            if (this.search.year == 15) {
                if(this.search.pt == 1){
                    var date = "2020-12-28";
                }
                if(this.search.pt == 2){
                    var date = "2021-01-25";
                }
                if(this.search.pt == 3){
                    var date = "2021-02-22";
                }
                if(this.search.pt == 4){
                    var date = "2021-03-22";
                }
                if(this.search.pt == 5){
                    var date = "2021-04-19";
                }
                if(this.search.pt == 6){
                    var date = "2021-05-17";
                }
                if(this.search.pt == 7){
                    var date = "2021-06-14";
                }
                if(this.search.pt == 8){
                    var date = "2021-07-12";
                }
                if(this.search.pt == 9){
                    var date = "2021-08-09";
                }
                if(this.search.pt == 10){
                    var date = "2021-09-06";
                }
                if(this.search.pt == 11){
                    var date = "2021-10-04";
                }
                if(this.search.pt == 12){
                    var date = "2021-11-01";
                }
                if(this.search.pt == 13){
                    var date = "2021-11-29";
                }
            }
            if (this.search.year == 16) {
                if(this.search.pt == 1){
                    var date = "2021-12-27";
                }
                if(this.search.pt == 2){
                    var date = "2022-01-24";
                }
                if(this.search.pt == 3){
                    var date = "2022-02-21";
                }
                if(this.search.pt == 4){
                    var date = "2022-03-21";
                }
                if(this.search.pt == 5){
                    var date = "2022-04-18";
                }
                if(this.search.pt == 6){
                    var date = "2022-05-16";
                }
                if(this.search.pt == 7){
                    var date = "2022-06-13";
                }
                if(this.search.pt == 8){
                    var date = "2022-07-11";
                }
                if(this.search.pt == 9){
                    var date = "2022-08-08";
                }
                if(this.search.pt == 10){
                    var date = "2022-09-05";
                }
                if(this.search.pt == 11){
                    var date = "2022-10-03";
                }
                if(this.search.pt == 12){
                    var date = "2022-10-31";
                }
                if(this.search.pt == 13){
                    var date = "2022-11-28";
                }
            }
            return date;
        },

        getPara() {
            // this.datepick();
            // console.log(date);

            // console.log(this.search.year);
            // console.log(this.search.pt);
            var d = new Date(this.datepick());
            d.setDate(d.getDate()+parseInt(27));
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id
            });
            
            let dates_para = queryString.stringify({ dates: [this.datepick(), moment(String(d)).format('YYYY-MM-DD')] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let uri = queryString.stringify({ users: this.sale_ids }, { arrayFormat: 'bracket' });
            return uri + '&' + dates_para + '&' + queryName;
        },
        showTarget(sale) {
            this.sale = sale;
            if(this.sale.status == 1) {
                $('#saleTargetModel').modal('show');
            }
            if(this.sale.status == 0) {
                $('#note').modal('show');
            }
        },
        createTarget() {
            var p2 = this.sale.targets.map(function(target) {
                return target.close;
            });

            var p = this.p;

            for (var i = 0; i < 4; i++) {
                if (_.indexOf(p2, p[i]) == -1) {
                    console.log(p[3]);
                    if (i == 0) {
                        this.sale.targets.push({
                            id: this.target.id,
                            name: '',
                            number: this.target.number3,
                            kh15_number: this.target.kh15_number3,
                            order_number: this.target.order_number3,
                            amount_number: this.target.amount_number3,
                            dahlia: this.target.dahlia3,
                            ivy: this.target.ivy3,
                            iris: this.target.iris3,
                            khac: this.target.khac3,
                            costcenterId: this.sale.id,
                            close: p[3-i]
                        });

                    }
                    if(i == 1) {
                        this.sale.targets.push({
                            id: this.target.id,
                            name: '',
                            number: this.target.number2,
                            kh15_number: this.target.kh15_number2,
                            order_number: this.target.order_number2,
                            amount_number: this.target.amount_number2,
                            dahlia: this.target.dahlia2,
                            ivy: this.target.ivy2,
                            iris: this.target.iris2,
                            khac: this.target.khac2,
                            costcenterId: this.sale.id,
                            close: p[3-i]
                        });
                    }
                    if(i == 2) {
                        this.sale.targets.push({
                            id: this.target.id,
                            name: '',
                            number: this.target.number1,
                            kh15_number: this.target.kh15_number1,
                            order_number: this.target.order_number1,
                            amount_number: this.target.amount_number1,
                            dahlia: this.target.dahlia1,
                            ivy: this.target.ivy1,
                            iris: this.target.iris1,
                            khac: this.target.khac1,
                            costcenterId: this.sale.id,
                            close: p[3-i]
                        });
                    }
                    if(i == 3) {
                        this.sale.targets.push({
                            id: this.target.id,
                            name: '',
                            number: this.target.number,
                            kh15_number: this.target.kh15_number,
                            order_number: this.target.order_number,
                            amount_number: this.target.amount_number,
                            dahlia: this.target.dahlia,
                            ivy: this.target.ivy,
                            iris: this.target.iris,
                            khac: this.target.khac,
                            costcenterId: this.sale.id,
                            close: p[3-i]
                        });
                    }
                }
            }
            if (this.counter > 0) {
                _.each(this.sale.targets, this.resetTarget);
            }

            this.recalculator();
        },
        resetTarget(target){
            this.amountChange(this.amount);

            // target.number = this.target.number;
            // target.kh15_number = this.target.kh15_number;
            // target.order_number = this.target.order_number;
            // target.amount_number = this.target.amount_number;
        },
        store() {
            axios.post('api/costcenters-targets', this.sale.targets)
            .then(()=> {
                $('#saleTargetModel').modal('hide');
                this.loadSalesTarget();
            })
            .catch(() => {
                swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Bạn không có quyền cập nhật!',
                        footer: '<a href>Liên hệ ASM để có thông tin?</a>'
                    })
            });
        },
        roles() {
            axios.post('api/picklists', {year: this.form.year,i: this.form.i,pt: this.form.pt,user: this.form.user})
            .then(()=> {
                axios.post('/api/picklists/p', {year: this.form.year}).then(res => this.thang = res.data);
            })
            .catch(() => {
            });
        },
        addTarget(target) {
            
            if (target.id == 0) {
                axios.post('api/costcenters-targets', target)
                    .then(responsive => {
                        this.sale.targets.push({
                            id: this.target.id,
                            name: '',
                            number: this.target.number,
                            kh15_number: this.target.kh15_number,
                            order_number: this.target.order_number,
                            amount_number: this.target.amount_number,
                            ivy: this.target.ivy,
                            iris: this.target.iris,
                            close: this.target.close
                        });
                        // this.recalculator();
                    })
            }
            else
            {
                axios.put('api/costcenters-targets/' + target.id, target);
            }

        },
        recalculator() {
            this.sale.count = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.number);
            }, 0);
            this.sale.kh15_count = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.kh15_number);
            }, 0);
            this.sale.amount_count = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.amount_number);
            }, 0);
            this.sale.order_count = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.order_number);
            }, 0);
            this.sale.ivy = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.ivy);
            }, 0);
            this.sale.iris = this.sale.targets.reduce(function(accumulator, target) {
                return accumulator + Number(target.iris);
            }, 0);
        },
        reTotal(){
            this.zipTarget.kh15_number = this.salesTarget2.reduce(function(accumulator, showroom){
                return accumulator + Number(showroom.kh15_count);
            }, 0);
            this.zipTarget.number = this.salesTarget2.reduce(function(accumulator, showroom){
                return accumulator + Number(showroom.count);
            }, 0);
            this.zipTarget.order_number = this.salesTarget2.reduce(function(accumulator, showroom){
                return accumulator + Number(showroom.order_count);
            }, 0);
            this.zipTarget.amount_number = this.salesTarget2.reduce(function(accumulator, showroom){
                return accumulator + Number(showroom.amount_count);
            }, 0);
            this.zipTarget.ivy = this.salesTarget2.reduce(function(accumulator, showroom){
                return accumulator + Number(showroom.ivy);
            }, 0);
            this.zipTarget.iris = this.salesTarget2.reduce(function(accumulator, showroom){
                return accumulator + Number(showroom.iris);
            }, 0);

        },
        total(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + Number(sale.count);
            }, 0);
        },
        kh15Total(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + Number(sale.kh15_count);
            }, 0);
        },
        orderTotal(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + Number(sale.order_count);
            }, 0);
        },
        amountTotal(sales) {
            return sales.reduce(function(accumulator, sale) {
                return accumulator + Number(sale.amount_count);
            }, 0);
        },
        amountChange(newAmount) {

            this.target.amount_number = newAmount * 1000000 * 0.3 ;
            this.target.order_number = Math.round(newAmount / 20 * 0.3 );
            this.target.number = this.target.order_number * 3;
            this.target.kh15_number = this.target.order_number * 9;
            this.target.dahlia = Math.round(this.target.number * 0.2);
            this.target.ivy = Math.round(this.target.number * 0.2);
            this.target.iris = Math.round(this.target.number * 0.3);
            this.target.khac = this.target.number - this.target.iris - this.target.dahlia - this.target.ivy;

            this.target.amount_number3 = newAmount * 1000000 * 0.15 ;
            this.target.order_number3 = Math.round(newAmount / 20 * 0.15 );
            this.target.number3 = this.target.order_number3 * 3;
            this.target.kh15_number3 = this.target.order_number3 * 9;
            this.target.dahlia3 = Math.round(this.target.number3 * 0.2);
            this.target.ivy3 = Math.round(this.target.number3 * 0.2);
            this.target.iris3 = Math.round(this.target.number3 * 0.3);
            this.target.khac3 = this.target.number3 - this.target.iris3 - this.target.dahlia3 - this.target.ivy3;

            this.target.amount_number1 = newAmount * 1000000 * 0.35 ;
            this.target.order_number1 = Math.round(newAmount / 20 * 0.35 );
            this.target.number1 = this.target.order_number1 * 3;
            this.target.kh15_number1 = this.target.order_number1 * 9;
            this.target.dahlia1 = Math.round(this.target.number1 * 0.2);
            this.target.ivy1 = Math.round(this.target.number1 * 0.2);
            this.target.iris1 = Math.round(this.target.number1 * 0.3);
            this.target.khac1 = this.target.number1 - this.target.iris1 - this.target.dahlia1 - this.target.ivy1;

            this.target.amount_number2 = newAmount * 1000000 * 0.2 ;
            this.target.order_number2 = Math.round(newAmount / 20 * 0.2 );
            this.target.number2 = this.target.order_number2 * 3;
            this.target.kh15_number2 = this.target.order_number2 * 9;
            this.target.dahlia2 = Math.round(this.target.number2 * 0.2);
            this.target.ivy2 = Math.round(this.target.number2 * 0.2);
            this.target.iris2 = Math.round(this.target.number2 * 0.3);
            this.target.khac2 = this.target.number2 - this.target.iris2 - this.target.dahlia2 - this.target.ivy2;
            this.sale.targets = [];
            var p2 = this.sale.targets.map(function(target) {
                return target.close;
            });

            var p = this.p;

            for (var i = 0; i < 4; i++) {
                if (_.indexOf(p2, p[i]) == -1) {
                    // console.log(p[3-i]);
                    if (i == 0) {
                        this.sale.targets.push({
                            id: this.target.id,
                            name: '',
                            number: this.target.number3,
                            kh15_number: this.target.kh15_number3,
                            order_number: this.target.order_number3,
                            amount_number: this.target.amount_number3,
                            dahlia: this.target.dahlia3,
                            ivy: this.target.ivy3,
                            iris: this.target.iris3,
                            khac: this.target.khac3,
                            costcenterId: this.sale.id,
                            close: p[3-i]
                        });

                    }
                    if(i == 1) {
                        this.sale.targets.push({
                            id: this.target.id,
                            name: '',
                            number: this.target.number2,
                            kh15_number: this.target.kh15_number2,
                            order_number: this.target.order_number2,
                            amount_number: this.target.amount_number2,
                            dahlia: this.target.dahlia2,
                            ivy: this.target.ivy2,
                            iris: this.target.iris2,
                            khac: this.target.khac2,
                            costcenterId: this.sale.id,
                            close: p[3-i]
                        });
                    }
                    if(i == 2) {
                        this.sale.targets.push({
                            id: this.target.id,
                            name: '',
                            number: this.target.number1,
                            kh15_number: this.target.kh15_number1,
                            order_number: this.target.order_number1,
                            amount_number: this.target.amount_number1,
                            dahlia: this.target.dahlia1,
                            ivy: this.target.ivy1,
                            iris: this.target.iris1,
                            khac: this.target.khac1,
                            costcenterId: this.sale.id,
                            close: p[3-i]
                        });
                    }
                    if(i == 3) {
                        this.sale.targets.push({
                            id: this.target.id,
                            name: '',
                            number: this.target.number,
                            kh15_number: this.target.kh15_number,
                            order_number: this.target.order_number,
                            amount_number: this.target.amount_number,
                            dahlia: this.target.dahlia,
                            ivy: this.target.ivy,
                            iris: this.target.iris,
                            khac: this.target.khac,
                            costcenterId: this.sale.id,
                            close: p[3-i]
                        });
                    }
                }
            }
        },
        cre(){
            // console.log(Array.isArray(this.pro));
            for (var i = 0; i < 3; i++) {
            axios.get('api/addtargetproduct?dahlia=' + this.pro[i]['dahlia'] + '&khac=' + this.pro[i]['khac'] + '&close=' + this.pro[i]['close'] + '&ivy=' + this.pro[i]['ivy'] + '&iris=' + this.pro[i]['iris'] + '&id=' + this.pro[i]['id'])
                .then(responsive => {

                },2000);
            }
            axios.get('api/addtargetproduct?dahlia=' + this.pro[3]['dahlia'] + '&khac=' + this.pro[3]['khac'] + '&close=' + this.pro[3]['close'] + '&ivy=' + this.pro[3]['ivy'] + '&iris=' + this.pro[3]['iris'] + '&id=' + this.pro[3]['id'])
                .then(responsive => {
                        swal.fire(
                            'Updated!',
                            'Information has been updated.',
                            'success'
                        )
                        this.loadSalesTarget();
                },2000);
        },
        re() {
            this.totalkey.totaliris = this.pro.reduce(function(accumulator, target) {
                return accumulator + Number(target.iris);
            }, 0);
            this.totalkey.totalivy = this.pro.reduce(function(accumulator, target) {
                return accumulator + Number(target.ivy);
            }, 0);
            this.totalkey.totaldahlia = this.pro.reduce(function(accumulator, target) {
                return accumulator + Number(target.dahlia);
            }, 0);
            this.totalkey.totalkhac = this.pro.reduce(function(accumulator, target) {
                return accumulator + Number(target.khac);
            }, 0);
        },
        redahlia() {
            for(var i = 0; i < 4; i++){
                this.pro[i].dahlia = Math.round(this.totalkey.totaldahlia / 4);
            }
        },
        reivy() {
            for(var i = 0; i < 4; i++){
                this.pro[i].ivy = Math.round(this.totalkey.totalivy / 4);
            }
        },
        reiris() {
            for(var i = 0; i < 4; i++){
                this.pro[i].iris = Math.round(this.totalkey.totaliris / 4);
            }
        },
        rekhac() {
            for(var i = 0; i < 4; i++){
                this.pro[i].khac = Math.round(this.totalkey.totalkhac / 4);
            }
        },
    },
    created() {
        
        axios.get('api/users-group-costcenters')
            .then(res => this.costcenters = res.data);
        axios.get('/api/picklists/users')
            .then(res => this.resources = res.data);
        axios.get('/api/picklists/asm')
            .then(res => this.asm = res.data);
        axios.post('/api/picklists/p')
            .then(res => this.thang = res.data);
        axios.get('/api/picklists/login')
            .then(res => this.login = res.data);
        Fire.$on('searching', () => {
            this.loadSalesTarget();
        })
        this.addWeek();
    },
    async mounted() {
        this.loadSalesTarget();
    },
    watch: {
        // whenever question changes, this function will run
        amount: function(newAmount, oldAmount) {
            this.target.amount_number = newAmount * 1000000 * 0.3 ;
            this.target.order_number = Math.round(newAmount / 25 * 0.3 );
            this.target.number = this.target.order_number * 3;
            this.target.kh15_number = this.target.order_number * 9;
            this.target.dahlia = Math.round(this.target.number * 0.2);
            this.target.ivy = Math.round(this.target.number * 0.2);
            this.target.iris = Math.round(this.target.number * 0.3);
            this.target.khac = this.target.number - this.target.iris - this.target.dahlia - this.target.ivy;

            this.target.amount_number3 = newAmount * 1000000 * 0.15 ;
            this.target.order_number3 = Math.round(newAmount / 25 * 0.15 );
            this.target.number3 = this.target.order_number3 * 3;
            this.target.kh15_number3 = this.target.order_number3 * 9;
            this.target.dahlia3 = Math.round(this.target.number3 * 0.2);
            this.target.ivy3 = Math.round(this.target.number3 * 0.2);
            this.target.iris3 = Math.round(this.target.number3 * 0.3);
            this.target.khac3 = this.target.number3 - this.target.iris3 - this.target.dahlia3 - this.target.ivy3;

            this.target.amount_number1 = newAmount * 1000000 * 0.35 ;
            this.target.order_number1 = Math.round(newAmount / 25 * 0.35 );
            this.target.number1 = this.target.order_number1 * 3;
            this.target.kh15_number1 = this.target.order_number1 * 9;
            this.target.dahlia1 = Math.round(this.target.number1 * 0.2);
            this.target.ivy1 = Math.round(this.target.number1 * 0.2);
            this.target.iris1 = Math.round(this.target.number1 * 0.3);
            this.target.khac1 = this.target.number1 - this.target.iris1 - this.target.dahlia1 - this.target.ivy1;

            this.target.amount_number2 = newAmount * 1000000 * 0.2 ;
            this.target.order_number2 = Math.round(newAmount / 25 * 0.2 );
            this.target.number2 = this.target.order_number2 * 3;
            this.target.kh15_number2 = this.target.order_number2 * 9;
            this.target.dahlia2 = Math.round(this.target.number2 * 0.2);
            this.target.ivy2 = Math.round(this.target.number2 * 0.2);
            this.target.iris2 = Math.round(this.target.number2 * 0.3);
            this.target.khac2 = this.target.number2 - this.target.iris2 - this.target.dahlia2 - this.target.ivy2;
        }
    },
}

</script>
