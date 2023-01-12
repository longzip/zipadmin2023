<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-2">
                <h3 class="m-0 text-dark">Báo Cáo Năm</h3>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <span class="col-sm-2">Năm Zip</span>
                    <select  class="form-control col-sm-10" v-model="form.yearzip" @change="onChangeZip($event)" >
                        <option v-for="option in $parent.year" v-bind:value="option.value" :selected="option.value == form.yearzip">
                            {{ option.nam }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="row">
                    <span class="col-sm-3">Năm Bán Hàng</span>
                    <select  class="form-control col-sm-9" v-model="form.yearsales" @change="onChangeSale($event)" style="width:80%">
                        <option v-for="option in $parent.year" v-bind:value="option.value" :selected="option.value == form.yearsales">
                            {{ option.nam }}
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
       <!--  <ul class="nav nav-pills" role="tablist">
            <li class="nav-item active">
                <a class="nav-link active" data-toggle="pill" href="#ds" @click="updateByDS">Doanh Số</a>
            </li>
        </ul> -->
        <div class="row" id="amount">
            <div class="tableFixHead table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="max-width:100px;">Showroom</th>
                            <th>Z</th>
                            <th v-if="form.yearsales == ''">P1</th>
                            <th v-if="form.yearsales == ''">P2</th>
                            <th>P3</th>
                            <th>P4</th>
                            <th>P5</th>
                            <th>P6</th>
                            <th>P7</th>
                            <th>P8</th>
                            <th>P9</th>
                            <th>P10</th>
                            <th>P11</th>
                            <th>P12</th>
                            <th>P13</th>
                            <th v-if="form.yearzip == ''">P1/Z{{form.yearsales + 1}}</th>
                            <th v-if="form.yearzip == ''">P2/Z{{form.yearsales + 1}} </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(sale, key, index) in sales">
                            <tr>
                                <td style="max-width:60px;" v-bind:class="{ 'table-warning': (index < 5 ) }">{{key}}</td>
                                <template v-for="(sr,stt) in sale">
	                                <td style="max-width: 100px;max-height: 50px;" v-bind:class="{ 'table-warning': (index < 5 ) }">
                                        <div style="width:100%;">
    	                                    <span class="table-primary">{{ formatPrice(sr[0]) }}</span>
                                            <span class="pull-right">{{ formatPrice(sr[1]) }}</span>
                                        </div>
                                        <div class="progress progress-xs pull-right " v-if="sr[1] > 0">
                                            <div class="text-dark" style="{width: (((sr[0] / sr[1]) * 100) + '%')}">
                                                {{ (formatdiv((sr[0] / sr[1]) * 100 )) + '%'}}
                                            </div>
                                        </div>
	                                </td>
                            	</template>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- /.content -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            form: new Form ({
                yearsales: '',
                yearzip: '15',
            }),
            sales: [],
            topCount: 0,
            uri: '/api/amount-year',
        }
    },
    methods: {
        onChangeZip(event) {
            this.form.yearsales = '';
        },
        onChangeSale(event) {
            this.form.yearzip = '';
        },
        searchdata() {
            this.loadSales();
        },
        loadSales() {
            
            this.$Progress.start();
            axios.get(this.uri + '?zip=' + this.form.yearzip + '&sale=' + this.form.yearsales)
                .then(responsive => {
                    this.sales = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
        },
        formatPrice(value) {
            let val = (value/1000000).toFixed(0).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        formatdiv(value) {
            let val = (value/1).toFixed(0).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
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

table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
tbody tr td { z-index: 0}
</style>