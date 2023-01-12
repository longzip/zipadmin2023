<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-2">
                <h3 class="m-0 text-dark">Báo Cáo Công</h3>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Khối</label>
                    <select  class="form-control" v-model="position" @change="onChange($event)">
                        <option v-for="option in $parent.position" v-bind:value="option.value">
                            {{ option.index }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Bộ Phận<a href="#" @click="reSelectbophan"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="bophan" :multiple="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn" label="info" track-by="id" :preselect-first="true">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn nhân viên<a href="#" @click="reSelectnhanvien"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="users" :multiple="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn ngày</label>
                    <date-range-picker class="form-control" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges">
                        <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                    </date-range-picker>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                </div>
            </div>
        </div>
 
        <div class="row">
            <div class="tableFixHead table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="max-width:100px;" :rowspan="2">Showroom</th>
                            <th class="text-center" style="max-width:100px;" :rowspan="2">Nhân Viên</th>
                            <th></th>
                            <template v-for="(v,k) in listnumber">
                                <th class="text-center" :colspan="v">{{k}}</th>
                            </template>
                        </tr>
                        <tr>
                            <template v-for="(d) in listdate">
                                <th class="text-center" style="padding: 12px 0px; width=37px;top:47px;" v-if=" d !== 'Sun' ">{{d}}</th>
	                            <th class="text-center bg-dark text-white" v-if=" d === 'Sun' " style="padding: 12px 0px; width=37px;top:47px;">{{d}}</th>
                            </template>
                        </tr>
                    </thead>
                    <tbody>
                    	<template v-for="(d,k) in listdata">
                            <tr v-for="(nv,knv,i) in d">
                            	<td class="text-center" :rowspan="Object.keys(d).length" v-if="i===0">{{k}}</td>
                            	<td class="text-center">{{knv}}</td>
                            	<template v-for="(cham) in nv">
                                    <td class="text-center">{{cham}}</td>
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
        	sale_ids: [],
            d: [],
            bophan: [],
            role_name:{},
            users: [],
            listdate: [],
            listnumber: [],
            listdata: [],
            selbophan: [],
            showroomsSelected: [],
            chamcong: [],
            costcenters: [],
            saleSelected:[],
            user: [],
            position: 4,
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
                // '2019': [moment('2018-12-31').endOf('week').isoWeekday(1), moment('2019-12-29').endOf('week')],
                '2020': [moment('2019-12-30').endOf('week').isoWeekday(1), moment('2020-12-27').endOf('week')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
                'Z15/P1': [moment('2020-12-28').endOf('week').isoWeekday(1), moment('2021-01-24').endOf('week')],
                'Z15/P2': [moment('2021-01-25').endOf('week').isoWeekday(1), moment('2021-02-21').endOf('week')],
                'Z15/P3': [moment('2021-02-22').endOf('week').isoWeekday(1), moment('2021-03-21').endOf('week')],
                'Z15/P4': [moment('2021-03-22').endOf('week').isoWeekday(1), moment('2021-04-18').endOf('week')],
                'Z15/P5': [moment('2021-04-19').endOf('week').isoWeekday(1), moment('2021-05-16').endOf('week')],
                'Z15/P6': [moment('2021-05-17').endOf('week').isoWeekday(1), moment('2021-06-13').endOf('week')],
                'Z15/P7': [moment('2021-06-14').endOf('week').isoWeekday(1), moment('2021-07-11').endOf('week')],
                'Z14/P7': [moment('2020-06-15').endOf('week').isoWeekday(1), moment('2020-07-12').endOf('week')],
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
            this.loadChamCong();
        },
        onChange(event) {
            this.position = event.target.value;
        },
        fileChange(e) {
            if (this._spread) {
                const fileDom = e.target || e.srcElement;
                const excelIO = new Excel.IO();
                const spread = this._spread;
                const store = this.$store;

                excelIO.open(fileDom.files[0], (_data_) => {
                    const newSalesData = extractSheetData(data);
                    store.commit('updateRecentSales', newSalesData)
                });
            }
        },
        reSelectnhanvien() {
            this.$parent.saleSelected = [];
            this.loadChamCong();
        },
        reSelectbophan() {
            this.$parent.showroomsSelected = [];
            this.loadChamCong();
        },
    	updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.loadChamCong();
        },
        loadChamCong() {
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10) ] }, { arrayFormat: 'bracket' });
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id;
            });

            this.selbophan = this.$parent.showroomsSelected.map(sale => {
                return sale.info;
            });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let khoi = queryString.stringify({ khoi: this.position });
            let nhanvien = queryString.stringify({ users: this.sale_ids }, { arrayFormat: 'bracket' });
            let bp = queryString.stringify({ bp: this.selbophan }, { arrayFormat: 'bracket' });
            axios.get('api/baocaochamcong' + '?' + queryName + '&' + nhanvien + '&' + bp + '&' + khoi +  '&' + dates_para)
                .then(responsive => {
                    this.listdate = responsive.data.date;
                    this.listdata = responsive.data.data;
                    this.listnumber = responsive.data.group;
                }).catch(error => {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: error.response.data.message,
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });
        },
    },
    created() {
        this.loadChamCong();
        Fire.$on('AfterCreate', () => {
            this.loadChamCong();
        });
        Fire.$on('searching', () => {
            this.loadChamCong();
        });
        axios.get("/api/users-chamcong")
        .then(response => {
            this.users = response.data;
        });
        axios.get("/api/bophan-chamcong")
        .then(response => {
            this.bophan = response.data;
        });
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