<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-2">
                <h3 class="m-0 text-dark">Báo Cáo Ngày</h3>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Chọn T</label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="calendar" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn" label="t" track-by="id_table" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheot">
                    </multiselect>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Showroom <a href="#" @click="reSelectShowroom"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="costcenters" :multiple="true" group-values="items" group-label="cat" :group-select="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoShowroom">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group pull-left">
                    <label>Chi Tiết</label>
                    <input class="form-control" type="checkbox" v-model="checkedCategories" @change="checkdetail()" >
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label></label>
                    <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                </div>
            </div>
        </div>
 
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#sr" @click="showroom">Showroom</a>
            </li>
            <li class="nav-item">
                <a class="nav-link " data-toggle="pill" href="#online" @click="online">Online</a>
            </li>
        </ul>
        <div class="tab-content"  style="width:100%">
            <div id="sr" class="tab-pane active ml-0 mr-0 pr-0 pl-0">
                <div class="table-responsive tableFixHead">
                    <table class="table table-bordered res">
                        <thead>
                            <th class="text-center" style="max-width:100px;" :rowspan="2">
                                Nhân Viên<br/>
                                <template v-for="(v,k) in listnumber">
                                    <span class="text-center" :colspan="v">{{k}}</span>
                                </template>
                            </th>
                            <th class="text-center" style="max-width:100px;" :rowspan="1">P</th>
                                <template v-for="(d) in listdate">
                                    <th class="text-center" style="padding: 12px 0px; width=37px;" v-if=" d !== 'Sun' ">{{d}}</th>
                                    <th class="text-center bg-dark text-white" v-if=" d === 'Sun' " style="padding: 12px 0px; width=37px;">{{d}}</th>
                                </template>
                            <tr>
                                <template v-if="chay == 1" v-for="(s,i,m) in tong" >
                                    <th class="text-center" style="padding: 12px 0px; width=37px;top:44px;" v-if="m == 0 ">
                                        <span v-if="s.login == 9003">
                                            <span v-if="s.kh15 == 0"> - </span><span v-if="s.kh15 != 0"> {{s.kh15}} </span>/
                                            <span v-if="s.khtn == 0"> - </span><span v-if="s.khtn != 0"> {{s.khtn}} </span> 
                                            <span v-if="s.tachkhtn == 0 && m != 0">& - </span><span v-if="s.tachkhtn != 0  && m != 0">& {{s.tachkhtn}} </span>/
                                            <span v-if="s.order == 0"> - </span><span v-if="s.order != 0"> {{s.order}} </span>/
                                            <span v-if="s.amount == 0"> - </span><span v-if="s.amount != 0"> {{formatPrice(s.amount)}} </span>
                                        </span>
                                    </th>
                                    <th class="text-center" style="padding: 12px 0px; width=37px;top:44px;" v-if="m > 0">
                                        <span>
                                            <span v-if="s.kh15 == 0"> - </span><span v-if="s.kh15 != 0"> {{s.kh15}} </span>/
                                            <span v-if="s.khtn == 0"> - </span><span v-if="s.khtn != 0"> {{s.khtn}} </span> 
                                            <span v-if="s.tachkhtn == 0 && m != 0">& - </span><span v-if="s.tachkhtn != 0  && m != 0">& {{s.tachkhtn}} </span>/
                                            <span v-if="s.order == 0"> - </span><span v-if="s.order != 0"> {{s.order}} </span>/
                                            <span v-if="s.amount == 0"> - </span><span v-if="s.amount != 0"> {{formatPrice(s.amount)}} </span>
                                        </span>
                                    </th>
                                </template>
                                <template v-if="chay == 0" >
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </template>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(d,k) in listdata">
                                <tr v-for="(nv,knv,i) in d" :key="knv">
                                    <td v-bind:class="{ 'text-center bg-secondary text-white': (knv == k), 'text-center': (knv != k)}">{{knv}}</td>
                                    <template v-for="(cham,t,n) in nv">
                                        <td v-bind:class="{ 'text-center bg-secondary text-white': (knv == k), 'text-center': (knv != k)}">
                                            <div>
                                                <span v-if="cham.kh15 == 0"> - </span><span v-if="cham.kh15 != 0"> {{cham.kh15}} </span>/
                                                <span v-if="cham.khtn == 0"> - </span><span v-if="cham.khtn != 0"> {{cham.khtn}} </span> 
                                                <span v-if="cham.tachkhtn == 0 && n != 0">& - </span><span v-if="cham.tachkhtn != 0 && n != 0">& {{cham.tachkhtn}} </span>/
                                                <span v-if="cham.order == 0"> - </span><span v-if="cham.order != 0"> {{cham.order}} </span>/
                                                <span v-if="cham.amount == 0"> - </span><span v-if="cham.amount != 0"> {{formatPrice(cham.amount)}} </span>
                                            </div>
                                            <div class="bg-info" v-if="cham.tlogin == 9003 || cham.tlogin == 9074">
                                                <span v-if="cham.tkh15 == 0"> - </span><span v-if="cham.tkh15 > 0"> {{cham.tkh15}} /</span>
                                                <span v-if="cham.tkhtn == 0"> - </span><span v-if="cham.tkhtn > 0"> {{cham.tkhtn}} /</span>
                                                <span v-if="cham.torder == 0"> - </span><span v-if="cham.torder > 0"> {{cham.torder}} /</span>
                                                <span v-if="cham.tamount == 0"> - </span><span v-if="cham.tamount > 0"> {{formatPrice(cham.tamount)}} </span>
                                            </div>
                                        </td>
                                    </template>   
                                </tr>
                            </template>       
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="online" class="tab-pane ml-0 mr-0 pr-0 pl-0">
                <div class="table-responsive tableFixHead">
                    <table class="table table-bordered res">
                        <thead>
                            <th class="text-center" style="max-width:100px;" :rowspan="2">
                                Nhân Viên<br/>
                                <template v-for="(v,k) in listnumber">
                                    <span class="text-center" :colspan="v">{{k}}</span>
                                </template>
                            </th>
                            <th class="text-center" style="max-width:100px;" :rowspan="1">P</th>
                                <template v-for="(d) in listdate">
                                    <th class="text-center" style="padding: 12px 0px; width=37px;" v-if=" d !== 'Sun' ">{{d}}</th>
                                    <th class="text-center bg-dark text-white" v-if=" d === 'Sun' " style="padding: 12px 0px; width=37px;">{{d}}</th>
                                </template>
                            <tr>
                                <th></th>
                                <template v-for="(s,i,m) in tong">
                                    <th class="text-center" style="padding: 12px 0px; width=37px;top:44px;">
                                        <span v-if="s.kh15 == 0"> - </span><span v-if="s.kh15 != 0"> {{s.kh15}} </span>/
                                        <span v-if="s.khtn == 0"> - </span><span v-if="s.khtn != 0"> {{s.khtn}} </span> 
                                        <span v-if="s.tachkhtn == 0 && m != 0">& - </span><span v-if="s.tachkhtn != 0  && m != 0">& {{s.tachkhtn}} </span>/
                                        <span v-if="s.order == 0"> - </span><span v-if="s.order != 0"> {{s.order}} </span>/
                                        <span v-if="s.amount == 0"> - </span><span v-if="s.amount != 0"> {{formatPrice(s.amount)}} </span>
                                    </th>
                                </template>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(d,k) in listdata">
                                <tr v-for="(nv,knv,i) in d" :key="knv">
                                    <td v-bind:class="{ 'text-center bg-secondary text-white': (knv == k), 'text-center': (knv != k)}">{{knv}}</td>
                                    <template v-for="(cham,t,n) in nv">
                                        <td v-bind:class="{ 'text-center bg-secondary text-white': (knv == k), 'text-center': (knv != k)}">
                                            <div>
                                                <span v-if="cham.kh15 == 0"> - </span><span v-if="cham.kh15 != 0"> {{cham.kh15}} </span>/
                                                <span v-if="cham.khtn == 0"> - </span><span v-if="cham.khtn != 0"> {{cham.khtn}} </span> 
                                                <span v-if="cham.tachkhtn == 0 && n != 0">& - </span><span v-if="cham.tachkhtn != 0 && n != 0">& {{cham.tachkhtn}} </span>/
                                                <span v-if="cham.order == 0"> - </span><span v-if="cham.order != 0"> {{cham.order}} </span>/
                                                <span v-if="cham.amount == 0"> - </span><span v-if="cham.amount != 0"> {{formatPrice(cham.amount)}} </span>
                                            </div>
                                            <div class="bg-info" v-if="cham.tlogin == 9003 || cham.tlogin == 9074">
                                                <span v-if="cham.tkh15 == 0"> - </span><span v-if="cham.tkh15 > 0"> {{cham.tkh15}} /</span>
                                                <span v-if="cham.tkhtn == 0"> - </span><span v-if="cham.tkhtn > 0"> {{cham.tkhtn}} /</span>
                                                <span v-if="cham.torder == 0"> - </span><span v-if="cham.torder > 0"> {{cham.torder}} /</span>
                                                <span v-if="cham.tamount == 0"> - </span><span v-if="cham.tamount > 0"> {{formatPrice(cham.tamount)}} </span>
                                            </div>
                                        </td>
                                    </template>   
                                </tr>
                            </template>       
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            checkedCategories: '',
            chay: '',
            costcenters: [],
            listnumber: [],
            calendar: [],
            listdata: [],
            tong: [],
            listdate: [],
            saleSelected: [],
            showroomsSelected: [],
            url: 'api/bao-cao-ngay-showroom'
        }
    },
    methods: {
        searchdata() {
            this.loadBaoCao();
        },
        reSelectShowroom() {
            this.$parent.showroomsSelected = [];
            // this.loadBaoCao();
        },
        onChangeSearchp(event){
            this.search.pt = event.target.value;
            axios.get('api/select-week?p=' + this.search.pt)
            .then(res => this.calendar = res.data);
        },
        showroom(){
            this.url = 'api/bao-cao-ngay-showroom';
            // this.loadBaoCao();
        },
        online(){
            this.url ='api/bao-cao-ngay-online';
            // this.loadBaoCao();
        },
        loadBaoCao() {
            this.$Progress.start();
            this.costcenter_ids = this.$parent.showroomsSelected.map(costcenter => {
                return costcenter.id;
            });

            let check = this.checkedCategories;
            if(check) {
                var chitiet = 1;
            }else{
                var chitiet = 0;
            }

            let costcenters = queryString.stringify({ costcenters: this.costcenter_ids }, { arrayFormat: 'bracket' });
            if (this.$parent.saleSelected.length == 0) {
               var week = 0;
            }else{
               var week = this.$parent.saleSelected.id_table;
            }
            axios.get(this.url + '?week=' + week + '&' + costcenters + '&detail=' + chitiet)
            .then(responsive => {
                this.listdate = responsive.data.date;
                this.listnumber = responsive.data.group;
                this.listdata = responsive.data.data;
                this.tong = responsive.data.tong;
                this.chay = responsive.data.chay;
                console.log(this.chay);
                this.$Progress.finish();
            })
            .catch(() => {
                this.$Progress.fail();
            });
        },
        timTheot(){
            // this.loadBaoCao();
        },
        checkdetail(){
            // this.loadBaoCao();
        },
        timTheoShowroom() {
            // this.loadBaoCao();
        },
        formatPrice(value) {
            let val = (value/1000000).toFixed(0).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
    },
    created() {
        axios.get('api/users-group-costcenters')
            .then(res => this.costcenters = res.data);
        axios.get('api/select-week')
            .then(res => this.calendar = res.data);
        // this.loadBaoCao();
        // Fire.$on('AfterCreate', () => {
        //     this.loadBaoCao();
        // });
        Fire.$on('searching', () => {
            this.loadBaoCao();
        });
    }
}
</script>

<style>
.tableFixHead          { height: 1000px !important; }
.res th, .res td {
    padding: 0.25rem;
}
</style>
