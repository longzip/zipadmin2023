<template>
    <div >
        <div class="card card-default">
            <div class="card-body" style="padding: 0px;">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Chọn ngày</label>
                            <date-range-picker style="display: block;" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="$parent.selectedDate" @update="updateValues" :ranges="ranges">
                                <div slot="input" slot-scope="picker">{{ $parent.selectedDate.startDate | myDate }} - {{ $parent.selectedDate.endDate | myDate }}</div>
                            </date-range-picker>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Chọn Khu Vực </label>
                            <select  class="form-control" v-model="searchwarehouse" @change="onChangeSearch($event)">
                                <option v-for="option in $parent.warehouse" v-bind:value="option.value" :selected="option.value == searchyear">
                                    {{ option.index }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Chọn năm </label>
                            <select  class="form-control" v-model="searchyear" @change="onChangeSearch($event)">
                                <option v-for="option in $parent.year" v-bind:value="option.value" :selected="option.value">
                                    {{ option.nam }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="">P</label>
                        <select  class="form-control" v-model="searchpt" @change="onChangeSearch($event)"
                        this.list = [];>
                            <option v-for="option in $parent.pt" v-bind:value="option.value" :selected="option.value">
                                {{ option.index }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label></label>
                            <br>    
                            <a href="#" @click="searchdata" class="btn btn-success">Tìm</i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#chi">Giao chi tiết</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#sp">Giao đầu sản phẩm</a>
            </li>
        </ul>
        <div class="tab-content"style="width:100%">
            <div id="chi" class="tab-pane active ml-0 mr-0 pr-0 pl-0" style="min-width:680px">
                <div class="table-responsive tableFixHead">
                    <table class="table table-bordered res">
                        <thead>
                            <tr>
                                <td>Sản Phẩm</td>
                                <template v-for="(v,k) in ngay">
                                    <td>{{k}}</td>
                                </template>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(s,b,i) in danhsach">
                                <tr v-bind:class="{ 'bg-new fz11': ( i % 2) }">
                                    <td> {{b}} ({{s}}) </td>
                                    <template v-for="(v,k) in ngay">
                                        <td v-bind:class="k | formatCN">{{v[b]}}  </td>
                                    </template>
                                </tr>  
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="sp" class="tab-pane ml-0 mr-0 pr-0 pl-0" style="min-width:680px">
                <div class="table-responsive tableFixHead">
                    <table class="table table-bordered res">
                        <thead>
                            <tr>
                                <td>SP\SIZE</td>
                                <td>120</td>
                                <td>140</td>
                                <td>150</td>
                                <td>160</td>
                                <td>180</td>
                                <td>200</td>
                                <td>220</td>
                                <td>100</td>
                                <td>125</td>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(s,b,i) in list">
                                <tr v-bind:class="{ 'bg-new fz11': ( i % 2) }">
                                    <td class="text-center">{{b}}</td>
                                    <template v-if="s[160] && searchpt > 0">
                                        <td class="text-center">
                                            <template v-if="s[120]">
                                            {{(s[120][1] + s[120][2] + s[120][3] + s[120][4] ) | myNumber}}</br>
                                            <small>{{s[120][1] | myNumber}} / {{s[120][2] | myNumber}} / {{s[120][3] | myNumber}} / {{s[120][4] | myNumber}}</small>
                                        </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[140]">
                                            {{(s[140][1] + s[140][2] + s[140][3] + s[140][4] ) | myNumber}}</br>
                                            <small>{{s[140][1] | myNumber}} / {{s[140][2] | myNumber}} / {{s[140][3] | myNumber}} / {{s[140][4] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[150]">
                                            {{(s[150][1] + s[150][2] + s[150][3] + s[150][4] ) | myNumber}}</br>
                                            <small>{{s[150][1] | myNumber}} / {{s[150][2] | myNumber}} / {{s[150][3] | myNumber}} / {{s[150][4] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[160]">
                                            {{(s[160][1] + s[160][2] + s[160][3] + s[160][4] ) | myNumber}}</br>
                                            <small>{{s[160][1] | myNumber}} / {{s[160][2] | myNumber}} / {{s[160][3] | myNumber}} / {{s[160][4] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[180]">
                                            {{(s[180][1] + s[180][2] + s[180][3] + s[180][4]) | myNumber}}</br>
                                            <small>{{s[180][1] | myNumber}} / {{s[180][2] | myNumber}} / {{s[180][3] | myNumber}} / {{s[180][4] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[200]">
                                            {{(s[200][1] + s[200][2] + s[200][3] + s[200][4]) | myNumber}}</br>
                                            <small>{{s[200][1] | myNumber}} / {{s[200][2] | myNumber}} / {{s[200][3] | myNumber}} / {{s[200][4] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[220]">
                                            {{(s[220][1] + s[220][2] + s[220][3] + s[220][4]) | myNumber}}</br>
                                            <small>{{s[220][1] | myNumber}} / {{s[220][2] | myNumber}} / {{s[220][3] | myNumber}} / {{s[220][4] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[100]">
                                            {{(s[100][1] + s[100][2] + s[100][3] + s[100][4] ) | myNumber}}</br>
                                            <small>{{s[100][1] | myNumber}} / {{s[100][2] | myNumber}} / {{s[100][3] | myNumber}} / {{s[100][4] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[125]">
                                            {{(s[125][1] + s[125][2] + s[125][3] + s[125][4] ) | myNumber}}</br>
                                            <small>{{s[125][1] | myNumber}} / {{s[125][2] | myNumber}} / {{s[125][3] | myNumber}} / {{s[125][4] | myNumber}}</small>
                                            </template>
                                        </td>
                                    </template>
                                    <template v-if="!s[160] && searchpt > 0">
                                        <td :colspan="9" class="text-center">
                                            <template v-for="data in s">
                                            {{(data[1] + data[2] + data[3] + data[4] ) | myNumber}}</br>
                                            <small>{{data[1] | myNumber}} / {{data[2] | myNumber}} / {{data[3] | myNumber}} / {{data[4] | myNumber}}</small>
                                            </template>
                                        </td>
                                    </template>
                                    <template v-if="s[160] && searchpt == 0">
                                        <td class="text-center">
                                            <template v-if="s[120]">
                                            {{(s[120][1] + s[120][2] + s[120][3] + s[120][4] + s[120][5] + s[120][6] + s[120][7] + s[120][8] + s[120][9] + s[120][10] + s[120][11] + s[120][12] + s[120][13]) | myNumber}}</br>
                                            <small>{{s[120][1] | myNumber}} / {{s[120][2] | myNumber}} / {{s[120][3] | myNumber}} / {{s[120][4] | myNumber}} / {{s[120][5] | myNumber}} / {{s[120][6] | myNumber}} / {{s[120][7] | myNumber}} / {{s[120][8] | myNumber}} / {{s[120][9] | myNumber}} / {{s[120][10] | myNumber}} / {{s[120][11] | myNumber}} / {{s[120][12] | myNumber}} / {{s[120][13] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[140]">
                                            {{(s[140][1] + s[140][2] + s[140][3] + s[140][4] + s[140][5] + s[140][6] + s[140][7] + s[140][8] + s[140][9] + s[140][10] + s[140][11] + s[140][12] + s[140][13]) | myNumber}}</br>
                                            <small>{{s[140][1] | myNumber}} / {{s[140][2] | myNumber}} / {{s[140][3] | myNumber}} / {{s[140][4] | myNumber}} / {{s[140][5] | myNumber}} / {{s[140][6] | myNumber}} / {{s[140][7] | myNumber}} / {{s[140][8] | myNumber}} / {{s[140][9] | myNumber}} / {{s[140][10] | myNumber}} / {{s[140][11] | myNumber}} / {{s[140][12] | myNumber}} / {{s[140][13] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[150]">
                                            {{(s[150][1] + s[150][2] + s[150][3] + s[150][4] + s[150][5] + s[150][6] + s[150][7] + s[150][8] + s[150][9] + s[150][10] + s[150][11] + s[150][12] + s[150][13]) | myNumber}}</br>
                                            <small>{{s[150][1] | myNumber}} / {{s[150][2] | myNumber}} / {{s[150][3] | myNumber}} / {{s[150][4] | myNumber}} / {{s[150][5] | myNumber}} / {{s[150][6] | myNumber}} / {{s[150][7] | myNumber}} / {{s[150][8] | myNumber}} / {{s[150][9] | myNumber}} / {{s[150][10] | myNumber}} / {{s[150][11] | myNumber}} / {{s[150][12] | myNumber}} / {{s[150][13] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[160]">
                                            {{(s[160][1] + s[160][2] + s[160][3] + s[160][4] + s[160][5] + s[160][6] + s[160][7] + s[160][8] + s[160][9] + s[160][10] + s[160][11] + s[160][12] + s[160][13]) | myNumber}}</br>
                                            <small>{{s[160][1] | myNumber}} / {{s[160][2] | myNumber}} / {{s[160][3] | myNumber}} / {{s[160][4] | myNumber}} / {{s[160][5] | myNumber}} / {{s[160][6] | myNumber}} / {{s[160][7] | myNumber}} / {{s[160][8] | myNumber}} / {{s[160][9] | myNumber}} / {{s[160][10] | myNumber}} / {{s[160][11] | myNumber}} / {{s[160][12] | myNumber}} / {{s[160][13] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[180]">
                                            {{(s[180][1] + s[180][2] + s[180][3] + s[180][4] + s[180][5] + s[180][6] + s[180][7] + s[180][8] + s[180][9] + s[180][10] + s[180][11] + s[180][12] + s[180][13]) | myNumber}}</br>
                                            <small>{{s[180][1] | myNumber}} / {{s[180][2] | myNumber}} / {{s[180][3] | myNumber}} / {{s[180][4] | myNumber}} / {{s[180][5] | myNumber}} / {{s[180][6] | myNumber}} / {{s[180][7] | myNumber}} / {{s[180][8] | myNumber}} / {{s[180][9] | myNumber}} / {{s[180][10] | myNumber}} / {{s[180][11] | myNumber}} / {{s[180][12] | myNumber}} / {{s[180][13] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[200]">
                                            {{(s[200][1] + s[200][2] + s[200][3] + s[200][4] + s[200][5] + s[200][6] + s[200][7] + s[200][8] + s[200][9] + s[200][10] + s[200][11] + s[200][12] + s[200][13]) | myNumber}}</br>
                                            <small>{{s[200][1] | myNumber}} / {{s[200][2] | myNumber}} / {{s[200][3] | myNumber}} / {{s[200][4] | myNumber}} / {{s[200][5] | myNumber}} / {{s[200][6] | myNumber}} / {{s[200][7] | myNumber}} / {{s[200][8] | myNumber}} / {{s[200][9] | myNumber}} / {{s[200][10] | myNumber}} / {{s[200][11] | myNumber}} / {{s[200][12] | myNumber}} / {{s[200][13] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[220]">
                                            {{(s[220][1] + s[220][2] + s[220][3] + s[220][4] + s[220][5] + s[220][6] + s[220][7] + s[220][8] + s[220][9] + s[220][10] + s[220][11] + s[220][12] + s[220][13]) | myNumber}}</br>
                                            <small>{{s[220][1] | myNumber}} / {{s[220][2] | myNumber}} / {{s[220][3] | myNumber}} / {{s[220][4] | myNumber}} / {{s[220][5] | myNumber}} / {{s[220][6] | myNumber}} / {{s[220][7] | myNumber}} / {{s[220][8] | myNumber}} / {{s[220][9] | myNumber}} / {{s[220][10] | myNumber}} / {{s[220][11] | myNumber}} / {{s[220][12] | myNumber}} / {{s[220][13] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[100]">
                                            {{(s[100][1] + s[100][2] + s[100][3] + s[100][4] + s[100][5] + s[100][6] + s[100][7] + s[100][8] + s[100][9] + s[100][10] + s[100][11] + s[100][12] + s[100][13]) | myNumber}}</br>
                                            <small>{{s[100][1] | myNumber}} / {{s[100][2] | myNumber}} / {{s[100][3] | myNumber}} / {{s[100][4] | myNumber}} / {{s[100][5] | myNumber}} / {{s[100][6] | myNumber}} / {{s[100][7] | myNumber}} / {{s[100][8] | myNumber}} / {{s[100][9] | myNumber}} / {{s[100][10] | myNumber}} / {{s[100][11] | myNumber}} / {{s[100][12] | myNumber}} / {{s[100][13] | myNumber}}</small>
                                            </template>
                                        </td>
                                        <td class="text-center">
                                            <template v-if="s[125]">
                                            {{(s[125][1] + s[125][2] + s[125][3] + s[125][4] + s[125][5] + s[125][6] + s[125][7] + s[125][8] + s[125][9] + s[125][10] + s[125][11] + s[125][12] + s[125][13]) | myNumber}}</br>
                                            <small>{{s[125][1] | myNumber}} / {{s[125][2] | myNumber}} / {{s[125][3] | myNumber}} / {{s[125][4] | myNumber}} / {{s[125][5] | myNumber}} / {{s[125][6] | myNumber}} / {{s[125][7] | myNumber}} / {{s[125][8] | myNumber}} / {{s[125][9] | myNumber}} / {{s[125][10] | myNumber}} / {{s[125][11] | myNumber}} / {{s[125][12] | myNumber}} / {{s[125][13] | myNumber}}</small>
                                            </template>
                                        </td>
                                    </template>
                                    <template v-if="!s[160] && searchpt == 0">
                                        <td :colspan="9" class="text-center">
                                            <template v-for="data in s">
                                            {{(data[1] + data[2] + data[3] + data[4] + data[5] + data[6] + data[7] + data[8] + data[9] + data[10] + data[11] + data[12] + data[13]) | myNumber}}</br>
                                            <small>{{data[1] | myNumber}} / {{data[2] | myNumber}} / {{data[3] | myNumber}} / {{data[4] | myNumber}} / {{data[5] | myNumber}} / {{data[6] | myNumber}} / {{data[7] | myNumber}} / {{data[8] | myNumber}} / {{data[9] | myNumber}} / {{data[10] | myNumber}} / {{data[11] | myNumber}} / {{data[12] | myNumber}} / {{data[13] | myNumber}}</small>
                                            </template>
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
            danhsach: [],
            list: [],
            searchwarehouse: '',
            searchpt: '',
            searchyear: 15,
            searchpt: 0,
            ngay: [],
            opens: "center", //which way the picker opens, default "center", can be "left"/"right"
            locale: {
                direction: 'ltr', //direction of text
                format: 'DD-MM-YYYY', //fomart of the dates displayed
                separator: ' - ', //separator between the two ranges
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                weekLabel: 'W',
                customRangeLabel: 'Custom Range',
                daysOfWeek: moment.weekdaysMin(), //array of days - see moment documenations for details
                monthNames: moment.monthsShort(), //array of month names - see moment documenations for details
                firstDay: 1, //ISO first day of week - see moment documenations for details
                showWeekNumbers: true //show week numbers on each row of the calendar
            },
            ranges: { 
                'Zip': [moment('2013-12-31').endOf('week').isoWeekday(1), moment('2025-12-31').endOf('week')],
                'Z14': [moment('2020-01-01').isoWeekday(1), moment('2020-12-27').endOf('week')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
                'Z15/P1': [moment('2020-12-28').endOf('week').isoWeekday(1), moment('2021-01-24').endOf('week')],
                'Z15/P2': [moment('2021-01-25').endOf('week').isoWeekday(1), moment('2021-02-21').endOf('week')],
                'Z15/P3': [moment('2021-02-22').endOf('week').isoWeekday(1), moment('2021-03-21').endOf('week')],
                'Z15/P4': [moment('2021-03-22').endOf('week').isoWeekday(1), moment('2021-04-18').endOf('week')],
                'Z15/P5': [moment('2021-04-19').endOf('week').isoWeekday(1), moment('2021-05-16').endOf('week')],
                'Z15/P6': [moment('2021-05-17').endOf('week').isoWeekday(1), moment('2021-06-13').endOf('week')],
                'Z15/P7': [moment('2021-06-14').endOf('week').isoWeekday(1), moment('2021-07-11').endOf('week')],
                'Z14/P8': [moment('2020-07-13').endOf('week').isoWeekday(1), moment('2020-08-09').endOf('week')],
                'Z14/P9': [moment('2020-08-10').endOf('week').isoWeekday(1), moment('2020-09-06').endOf('week')],
                'Z14/P10': [moment('2020-09-07').endOf('week').isoWeekday(1), moment('2020-10-04').endOf('week')],
                'Z14/P11': [moment('2020-10-05').endOf('week').isoWeekday(1), moment('2020-11-01').endOf('week')],
                'Z14/P12': [moment('2020-11-02').endOf('week').isoWeekday(1), moment('2020-11-29').endOf('week')],
                'Z14/P13': [moment('2020-11-30').endOf('week').isoWeekday(1), moment('2020-12-27').endOf('week')],
            },
            startDate: moment('2021-04-19').format(),
            endDate: moment('2021-05-16').format(),

            selectedDate: {
                startDate: moment('2021-04-19'),
                endDate: moment('2021-05-16')
            },
        }
    },
    methods: {
        searchdata() {
            this.load();
        },
        onChangeSearch(event) {
            this.list = [];
        },
        load() {  
            this.list = [];
            let dates_para = queryString.stringify({ sdates: [this.$parent.startDate.slice(0, 10), this.$parent.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            axios.get("/api/loadsanphamgiao?" + dates_para + '&kho=' + this.searchwarehouse)
                .then(response => {
                    this.ngay = response.data.ngay;
                    this.danhsach = response.data.danhsach;
                });
             axios.get("/api/loadtongspgiao?z=" + this.searchyear + '&p=' + this.searchpt + '&kho=' + this.searchwarehouse)
                .then(response => {
                    this.list = response.data;
                });
        },
        updateValues(values) {
            this.$parent.startDate = values.startDate.toISOString().slice(0, 10)
            this.$parent.endDate = values.endDate.toISOString().slice(0, 10)
        },
        loadf() {  
            let dates_para = queryString.stringify({ sdates: [this.$parent.startDate.slice(0, 10), this.$parent.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            axios.get("/api/loadsanphamgiao?" + dates_para + '&kho=' + this.searchwarehouse)
                .then(response => {
                    this.ngay = response.data.ngay;
                    this.danhsach = response.data.danhsach;
                });
        },
        updateValues(values) {
            this.$parent.startDate = values.startDate.toISOString().slice(0, 10)
            this.$parent.endDate = values.endDate.toISOString().slice(0, 10)
        },
        
    },
    created() {
        this.loadf();
    }
}

</script>

<style>
.bg-new {
    background-color:#e0f1d5!important;
}

.fz11 {
    color: #565454!important;
}

.tableFixHead          { overflow-y: auto; height: 700px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1;}

table  { border-collapse: collapse; width: 100%; }

th     { background:#eee; }
tbody tr td { z-index: 0; font-size: 12px;}
.res th, .res td {
    padding: 0rem !important;
}
.bg-cn{
    background: #d0d0d0  !important;
}
</style>