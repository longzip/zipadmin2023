<template>
    <div class="container-fluid">
        <div class="col-sm-12">
            <h1 class="m-0 text-dark">Chăm KH</h1>
        </div>
        <div class="row mb-2">
            <div class="col-md-2">
                <label for="">Ngày Chăm</label>
                <div class="form-group">
                    <date-range-picker class="mt-2" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges" :alwaysShowCalendars="false">
                        <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                    </date-range-picker>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn năm </label>
                    <select  class="form-control" v-model="search.year" @change="onChangeSearch($event)">
                        <option v-for="option in $parent.year" v-bind:value="option.value" :selected="option.value == search.year">
                            {{ option.nam }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <label for="">P<a href="#" @click="reSelect"><i class="fa fa-refresh"></i></a></label>
                <multiselect class="form-control" style="padding: 0;" v-model="search.pt" :options="$parent.pt" :multiple="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn" label="index" track-by="value" :preselect-first="true" @close="onChangeSearch($event)">
                </multiselect>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>KH15</label>
                    <input class="form-control" type="checkbox" v-model="search.kh" @change="checkkh()" >
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Showroom <a href="#" @click="reSelectShowroom"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="costcenters" :multiple="true" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="loadshowroom">
                    </multiselect>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                </div>
            </div>
        </div>
        <div class="tab-content"  style="width:100%">
            <div class=" table-responsive tableFixHead">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="min-width: 80px;">Showroom</th>
                            <th style="min-width: 80px;">Tạm Dừng</th>
                            <th style="min-width: 80px;">Đang chăm sóc</th>
                            <th style="min-width: 80px;">Gần đơn hàng</th>
                            <th style="min-width: 80px;">Đơn hàng chờ</th>
                            <th style="min-width: 80px;">Đơn hàng</th>
                            <th style="min-width: 80px;">Tiềm Năng Xa</th>
                            <th style="min-width: 80px;">Gọi Điện</th>
                            <th style="min-width: 80px;">Có Cuộc Hẹn Tại Nhà</th>
                            <th style="min-width: 80px;">Có Cuộc Hẹn Tại Showroom</th>
                            <th style="min-width: 80px;">Cuộc Hẹn Tại Nhà</th>
                            <th style="min-width: 80px;">Cuộc Hẹn Tại Showroom</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(v,k) in dulieu">
                            <td>{{v.showroom}} ( {{v.tong}} )</td>
                            <td style="min-width: 80px;">{{v.tamdung | myNumber}} / 
                                <a href="#" @click="showinfo(v.cctamdung,v.showroom)">
                                    {{v.tamdungcc | myNumber}}
                                </a>
                                <br><a href="#" @click="showinfo(v.infotamdung,v.showroom)">
                                     {{v.tamdunghd | myNumber}}
                                </a>
                                <span v-if="v.infotamdung"> /
                                    {{Object.keys(v.infotamdung).length}}
                                </span>
                                <span v-if="!v.infotamdung"> /
                                    -
                                </span>
                            </td>
                            <td style="min-width: 80px;">{{v.dcs | myNumber}} / 
                                <a href="#" @click="showinfo(v.ccdcs,v.showroom)">
                                    {{v.dcscc | myNumber}}
                                </a>
                                <br><a href="#" @click="showinfo(v.infodcs,v.showroom)">
                                    {{v.dcshd | myNumber}}
                                </a>
                                <span v-if="v.infodcs"> /
                                    {{Object.keys(v.infodcs).length}}
                                </span>
                                <span v-if="!v.infodcs"> /
                                    -
                                </span>
                            </td>
                            <td style="min-width: 80px;">{{v.gdh | myNumber}} / 
                                <a href="#" @click="showinfo(v.ccgdh,v.showroom)">
                                    {{v.gdhcc | myNumber}}
                                </a>
                                <br><a href="#" @click="showinfo(v.infogdh,v.showroom)">
                                    {{v.gdhhd | myNumber}}
                                </a>
                                <span v-if="v.infogdh"> /
                                    {{Object.keys(v.infogdh).length}}
                                </span>
                                <span v-if="!v.infogdh"> /
                                    -
                                </span>
                            </td>
                            <td style="min-width: 80px;">{{v.dhc | myNumber}} / 
                                <a href="#" @click="showinfo(v.ccdhc,v.showroom)">
                                    {{v.dhccc | myNumber}}
                                </a>
                                <br><a href="#" @click="showinfo(v.infodhc,v.showroom)">
                                    {{v.dhchd | myNumber}}
                                </a>
                                <span v-if="v.infodhc"> /
                                    {{Object.keys(v.infodhc).length}}
                                </span>
                                <span v-if="!v.infodhc"> /
                                    -
                                </span>
                            </td>
                            <td style="min-width: 80px;">{{v.dh | myNumber}} / 
                                <a href="#" @click="showinfo(v.ccdh,v.showroom)">
                                    {{v.dhcc | myNumber}}
                                </a>
                                <br><a href="#" @click="showinfo(v.infodh,v.showroom)">
                                    {{v.dhhd | myNumber}}
                                </a>
                                <span v-if="v.infodh"> /
                                    {{Object.keys(v.infodh).length}}
                                </span>
                                <span v-if="!v.infodh"> /
                                    -
                                </span>
                            </td>
                            <td style="min-width: 80px;">{{v.tnx | myNumber}} / 
                                <a href="#" @click="showinfo(v.cctnx,v.showroom)">
                                    {{v.tnxcc | myNumber}}
                                </a>
                                <br><a href="#" @click="showinfo(v.infotnx,v.showroom)">
                                    {{v.tnxhd | myNumber}}
                                </a>
                                <span v-if="v.infotnx"> /
                                    {{Object.keys(v.infotnx).length}}
                                </span>
                                <span v-if="!v.infotnx"> /
                                    -
                                </span>
                            </td>
                            <td style="min-width: 80px;">{{v.gd | myNumber}} / 
                                <a href="#" @click="showinfo(v.ccgd,v.showroom)">
                                    {{v.gdcc | myNumber}}
                                </a>
                                <br><a href="#" @click="showinfo(v.infogd,v.showroom)">
                                    {{v.gdhd | myNumber}}
                                </a>
                                <span v-if="v.infogd"> /
                                    {{Object.keys(v.infogd).length}}
                                </span>
                                <span v-if="!v.infogd"> /
                                    -
                                </span>
                            </td>
                            <td style="min-width: 80px;">{{v.cchtn | myNumber}} / 
                                <a href="#" @click="showinfo(v.cccchtn,v.showroom)">
                                    {{v.cchtncc | myNumber}}
                                </a>
                                <br><a href="#" @click="showinfo(v.infocchtn,v.showroom)">
                                    {{v.cchtnhd | myNumber}}
                                </a>
                                <span v-if="v.infocchtn"> /
                                    {{Object.keys(v.infocchtn).length}}
                                </span>
                                <span v-if="!v.infocchtn"> /
                                    -
                                </span>
                            </td>
                            <td style="min-width: 80px;">{{v.cchts | myNumber}} / 
                                <a href="#" @click="showinfo(v.cccchts,v.showroom)">
                                    {{v.cchtscc | myNumber}}
                                </a>
                                <br><a href="#" @click="showinfo(v.infocchts,v.showroom)">
                                    {{v.cchtshd | myNumber}}
                                </a>
                                <span v-if="v.infocchts"> /
                                    {{Object.keys(v.infocchts).length}}
                                </span>
                                <span v-if="!v.infocchts"> /
                                    -
                                </span>
                            </td>
                            
                            <td style="min-width: 80px;">{{v.chts | myNumber}} / 
                                <a href="#" @click="showinfo(v.ccchts,v.showroom)">
                                    {{v.chtscc| myNumber}}
                                </a>
                                <br><a href="#" @click="showinfo(v.infochts,v.showroom)">
                                    {{v.chtshd | myNumber}}
                                </a>
                                <span v-if="v.infochts"> /
                                    {{Object.keys(v.infochts).length}}
                                </span>
                                <span v-if="!v.infochts"> /
                                    -
                                </span>
                            </td>
                            <td style="min-width: 80px;">{{v.chtn | myNumber}} / 
                                <a href="#" @click="showinfo(v.ccchtn,v.showroom)">
                                    {{v.chtncc | myNumber}}
                                </a>
                                <br><a href="#" @click="showinfo(v.infochtn,v.showroom)">
                                    {{v.chtnhd | myNumber}}
                                </a>
                                <span v-if="v.infochtn"> /
                                    {{Object.keys(v.infochtn).length}}
                                </span>
                                <span v-if="!v.infochtn"> /
                                    -
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="show" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Khách {{showroom}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <ul v-for="(v,k) in list">
                            <li>
                                <router-link v-if="type == 0" :to="{ name: 'ttkhachtiemnang', query: { id: k }}" target= '_blank'>
                                    <p>{{ v.name }}</p>
                                </router-link>
                                <router-link v-if="type == 1" :to="{ name: 'kh15Show', query: { id: k }}" target= '_blank'>
                                    <p>{{ v.name }}</p>
                                </router-link>
                                <ul>
                                    <li v-for="(i) in v.data">
                                                <!-- {{i}} -->
                                        <template v-for="(item,key) in i">
                                            <template v-if="item.idfolder > 0">
                                                <a target="_blank" v-bind:href="'http://192.168.100.49/00khtn'+item.src" v-if="item.check == 1 && item.idfolder < 33906">
                                                    Tài Liệu {{key + 1}} lúc {{item.created_at}}<br/>
                                                </a>
                                                <a target="_blank" v-bind:href="'http://noithatzip.com.vn/00khtn'+item.src" v-if="item.check == 0 && item.idfolder < 33906">
                                                    Tài Liệu {{key + 1}} lúc {{item.created_at}}<br/>
                                                </a>
                                                <a target="_blank" v-bind:href="'https://admin.noithatzip.com/'+item.src" v-if="item.idfolder > 33905">
                                                    Tài Liệu {{key + 1}} lúc {{item.created_at}}<br/>
                                                </a>
                                            </template>
                                            <template v-if="item.creator_id > 0">
                                                <span>{{item.user_name}} {{item.title}} lúc {{item.created_at}}</span><br/>
                                            </template>
                                        </template>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
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
            search: new Form({
                year: '15',
                pt: [{index: "5",value:"5"}],
                kh: '',
            }),
            type: 0,
            costcenters: [],
            showroom: '',
            dulieu: [],
            sale_ids: [],
            list: [],
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
                '2019': [moment('2018-12-31').endOf('week').isoWeekday(1), moment('2019-12-29').endOf('week')],
                '2020': [moment('2019-12-30').endOf('week').isoWeekday(1), moment('2020-12-27').endOf('week')],
                '2021': [moment('2020-12-28').endOf('week').isoWeekday(1), moment('2021-12-26').endOf('week')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
                'Z16/P1': [moment('2021-12-27').endOf('week').isoWeekday(1), moment('2022-01-23').endOf('week')],
                'Z16/P2': [moment('2022-01-24').endOf('week').isoWeekday(1), moment('2022-02-20').endOf('week')],
                'Z16/P3': [moment('2022-02-21').endOf('week').isoWeekday(1), moment('2022-03-20').endOf('week')],
                'Z16/P4': [moment('2022-03-21').endOf('week').isoWeekday(1), moment('2022-04-17').endOf('week')],
                'Z16/P5': [moment('2022-04-18').endOf('week').isoWeekday(1), moment('2022-05-15').endOf('week')],
                'Z16/P6': [moment('2022-05-16').endOf('week').isoWeekday(1), moment('2022-06-12').endOf('week')],
                'Z16/P7': [moment('2022-06-13').endOf('week').isoWeekday(1), moment('2022-07-10').endOf('week')],
                'Z16/P8': [moment('2022-07-11').endOf('week').isoWeekday(1), moment('2022-08-07').endOf('week')],
                'Z16/P9': [moment('2022-08-08').endOf('week').isoWeekday(1), moment('2022-09-04').endOf('week')],
                'Z16/P10': [moment('2022-09-05').endOf('week').isoWeekday(1), moment('2022-10-02').endOf('week')],
                'Z16/P11': [moment('2022-10-03').endOf('week').isoWeekday(1), moment('2022-10-30').endOf('week')],
                'Z16/P12': [moment('2022-10-31').endOf('week').isoWeekday(1), moment('2022-11-27').endOf('week')],
                'Z16/P13': [moment('2022-11-28').endOf('week').isoWeekday(1), moment('2022-12-25').endOf('week')],
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
            this.loadData();
        },
        reSelectShowroom() {
            this.$parent.showroomsSelected = [];
            // this.loadData();
        },
        loadshowroom(){
            // this.loadData();
        },
        reSelect() {
            this.search.pt =  [{index: "11",value:"11"}];
            // this.loadData();
        },
        onChangeSearch(event) {
            // this.loadData();
        },
        checkkh(){
            // this.loadData();
        },
        loadData() {
            this.$Progress.start();

            let check = this.search.kh;
            if(check) {
                this.type = 1;
            }else{
                this.type = 0;
            }
            // console.log(this.search.pt);
            this.sale_ids = this.search.pt.map(sale => {
                return sale.value;
            });
            let costcenter_ids = this.$parent.showroomsSelected.map(costcenter => {
                return costcenter.id;
            });
            let showoom = queryString.stringify({ costcenter: costcenter_ids }, { arrayFormat: 'bracket' });

            let listp = queryString.stringify({ p: this.sale_ids }, { arrayFormat: 'bracket' });
            // console.log(listp);
            let dates_para = queryString.stringify({ da: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            axios.get('api/bao-cao-khtn' + '?' + dates_para + '&' + listp + '&year=' + this.search.year + '&type=' + this.type  + '&' + showoom)
                .then(responsive => {
                    this.dulieu = responsive.data;
                    this.$Progress.finish()
                })
                .catch(() => this.$Progress.fail());
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            // this.loadData();
        },
        showinfo(v,showroom){
            this.list = v;
            this.showroom = showroom;
            let check = this.search.kh;
            if(check) {
                this.type = 1;
            }
            // console.log(this.dulieu);
            $('#show').modal('show');
        },
    },
   
    created() {
        this.loadData();
        axios.get('api/users-group-costcenters')
            .then(res => this.costcenters = res.data);
    }
}
</script>

<style>
.bg-new {
    background-color:#e9ecef !important;
}

.fz111 {
    font-size:11px !important;
    color: #212529;
    font-weight: 700;
}

.fz11 {
    font-size:11px !important;
}

.tableFixHead          { overflow-y: auto; height: 700px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1;}

table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
tbody tr td { z-index: 0}
</style>