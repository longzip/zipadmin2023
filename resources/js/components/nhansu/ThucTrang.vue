<template>
    <div class="">
        <div class="card card-default">
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn ngày</label>
                            <date-range-picker style="display: block;" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges">
                                <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                            </date-range-picker>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title pull-left">Thực Trạng</h3>
                <div class="card-tools">
                    <a href="#" @click="newModal" class="btn btn-primary" v-if="login.id == 9003 || login.id == 9074">
                        Định Biên
                    </a>
                    
                    <a href="#" @click="dx" class="btn btn-primary">
                        Đề Xuất
                    </a>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Showroom</th>
                            <th>Nhan vien</th>
                            <th>Ngày Nghỉ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(d,k) in thuctrang">
                                <tr v-for="(nv,knv) in d">
                                    <td class="text-center" :rowspan="Object.keys(d).length" v-if="knv===0">{{k}}</td>
                                    <td>{{nv.name}}</td>
                                    <td>{{nv.date | formatDate}}</td>
                                </tr>
                            </template>   
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="userModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Định Biên</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>Showroom</th>
                                    <th>SM</th>
                                    <th>Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="db in dinhbien">
                                    <td>{{db.name}}</td>
                                    <td><input type="text" class="form-control" name="redirect" v-model="db.sm"></td>
                                    <td><input type="text" class="form-control" name="redirect" v-model="db.sales"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="add">
                            Lưu
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="userDeXuat" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Định Biên</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>Showroom</th>
                                    <th>SM</th>
                                    <th>Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="db in dexuat">
                                    <td>{{db.name}}</td>
                                    <td><input type="text" class="form-control" name="redirect" v-model="db.sm"></td>
                                    <td><input type="text" class="form-control" name="redirect" v-model="db.sale"></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="themdx">
                            Đề Xuất
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
            login: [],
            thuctrang:[],
            dinhbien:{},
            dexuat:{},
            // dinhbien: new Form({
            //     sm: '',
            //     sales: '',
            // }),
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
            ranges: { //default value for ranges object (if you set this to false ranges will no be rendered)
                'P6': [moment('2019-05-20').startOf('week'), moment('2019-06-16')],
                'P7': [moment('2019-06-17').startOf('week'), moment('2019-07-14')],
                'P8': [moment('2019-07-15').startOf('week'), moment('2019-08-11')],
                'P9': [moment('2019-08-12').startOf('week'), moment('2019-09-08')],
                'P10': [moment('2019-09-09').startOf('week'), moment('2019-10-06')],
                'P11': [moment('2019-10-09').startOf('week'), moment('2019-11-03')],
                'P12': [moment('2019-11-04').startOf('week'), moment('2019-12-01')],
                'P13': [moment('2019-12-02').startOf('week'), moment('2019-12-29')],
                'P1/Z14': [moment('2019-12-30').startOf('week'), moment('2020-01-26')],
                'P2/Z14': [moment('2020-01-27').startOf('week'), moment('2020-02-23')],
                'P3/Z14': [moment('2019-02-24').startOf('week'), moment('2020-03-22')],
                'P4/Z14': [moment('2020-03-23').startOf('week'), moment('2020-04-19')],
                'P5/Z14': [moment('2020-04-20').startOf('week'), moment('2020-05-17')],
                'P6/Z14': [moment('2020-05-18').startOf('week'), moment('2020-06-14')],
                'P7/Z14': [moment('2020-06-15').startOf('week'), moment('2020-07-12')],
                'P8/Z14': [moment('2020-07-13').startOf('week'), moment('2020-08-09')],
                'P9/Z14': [moment('2020-08-10').startOf('week'), moment('2020-09-06')],
                'P10/Z14': [moment('2020-09-07').startOf('week'), moment('2020-10-04')],
                'P11/Z14': [moment('2020-10-05').startOf('week'), moment('2020-11-01')],
                'P12/Z14': [moment('2020-11-02').startOf('week'), moment('2020-11-29')],
                'P13/Z14': [moment('2020-11-30').startOf('week'), moment('2020-12-27')],
            },
            startDate: moment('2020-06-15').format(),
            endDate: moment('2020-07-12').format(),
            selectedDate: {
                startDate: moment('2020-06-15'),
                endDate: moment('2020-07-12')
            },
        }
    },
    methods: {
        themdx(){
            for(var i = 0; i < this.dexuat.length; i++){
                axios.get('api/adddx' + '?name=' + this.dexuat[i].name + '&sales=' + this.dexuat[i].sale + '&sm=' + this.dexuat[i].sm).then(() => new Promise(resolve => setTimeout(resolve, 3000)));
            }
            $('#userDeXuat').modal('hide');
        },
        add(){
            for(var i = 0; i < this.dinhbien.length; i++){
                axios.get('api/adddinhbien' + '?id=' + this.dinhbien[i].id + '&sales=' + this.dinhbien[i].sales + '&sm=' + this.dinhbien[i].sm).then(() => new Promise(resolve => setTimeout(resolve, 1000)));
            }
            $('#userModalCenter').modal('hide');
        },
        loaddinhbien() {
            this.$Progress.start();
            axios.get('api/thuctrang' + '?' + this.getPara())
                .then(responsive => {
                    this.thuctrang = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
        },
        
        getPara() {
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            return  dates_para + '&' + queryName;
        },

        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.loaddinhbien();
        },
        newModal() {
            axios.get('api/dinhbien' + '?' + this.getPara())
            .then(responsive => {
                this.dinhbien = responsive.data;
            });
            $('#userModalCenter').modal('show');

        },

        dx() {
            axios.get('api/dexuat' + '?' + this.getPara())
            .then(responsive => {
                this.dexuat = responsive.data;
            });
            $('#userDeXuat').modal('show');

        },
    },
    created() {
        this.loaddinhbien();
        Fire.$on('searching', () => {
            this.loaddinhbien();
        });
        axios.get('/api/picklists/login')
            .then(res => this.login = res.data);
    },
}

</script>
