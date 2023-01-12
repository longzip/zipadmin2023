<template>
    <div class="">
        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card-body" style="padding: 0px;">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Chọn Chiến Dịch</label>
                                <multiselect v-model="$parent.chiendich" :options="chiendich" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn chiến dịch" label="name" :preselect-first="true" @close="searchChienDich">
                                </multiselect>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Chọn ngày</label>
                                <date-range-picker class="form-control" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges">
                                    <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                                </date-range-picker>
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
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"> Online</h3>
                        <div class="card-tools">
                            <a href="#" @click="newModal" class="btn btn-primary">Thêm </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Ngày</th>
                                    <th>Chiến Dịch</th>
                                    <th>Chi Phí <small><i>{{ target.chiphi | currency }}</i> / {{ target.chiphitarget | currency }}</small></th>
                                    <th>Sản Phẩm</th>
                                    <th>Tin Nhắn <small><i>{{ target.mess | currency }}</i> / {{target.targetmess}}</small></th>
                                    <th>SĐT <small><i>{{ target.phone | currency }}</i> / {{target.targetphone}}</small></th>
                                    <th>KHTN <small><i>{{ target.khtn | currency }}</i> / {{target.targetkhtn}}</small></th>
                                    <th>SĐH</th>
                                    <th>DS</th>
                                    <th>Thao Tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template v-for="online in onlines.data">
                                    <tr>
                                        <td>{{online.date}}</td>
                                        <td>{{online.namechiendich}}</td>
                                        <td>
                                            <span>{{online.chiphi | currency}}</span> 
                                            <span class="float-right">{{online.chiphitarget | currency}}</span>
                                        </td>
                                        <td>
                                            <template v-for="pro in online.product">
                                                <span>{{pro.name_groups}}</span>,
                                            </template>
                                        </td>
                                        <td>
                                            <span>{{online.mess}}</span>
                                            <span class="float-right">{{online.targetmess}}</span>
                                        </td>
                                        <td>
                                            <span>{{online.phone}}</span>
                                            <span class="float-right">{{online.targetphone}}</span>

                                        </td>
                                        <td>
                                            <span>{{online.khtn}}</span>
                                            <span class="float-right">{{online.targetkhtn}}</span>
                                        </td>
                                        <td>{{online.number}}</td>
                                        <td>{{online.sum}}</td>
                                        <td>
                                            <a href="#" @click="editModal(online)">
                                                <i class="fa fa-edit blue"></i>
                                            </a>
                                            /
                                            <a href="#" @click="deleteonline(online)">
                                                <i class="fa fa-trash red"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="onlines" @pagination-change-page="loadonlines"></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="onlineModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Thêm</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateonline() : createonline()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Ngày</label>
                                <input v-model="form.date" type="date" name="date" class="form-control" >
                            </div>
                            <div class="form-group">
                                <label>Chi Phí</label>
                                <input v-model="form.chiphi" type="number" name="chiphi" class="form-control" :class="{ 'is-invalid': form.errors.has('chiphi') }">
                                <has-error :form="form" field="chiphi"></has-error>
                            </div>
                            <div class="form-group">
                                <label>Tên Chiến Dịch</label>
                                <multiselect v-model="form.chiendich" :options="chiendich" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn chiến dịch" label="name" :preselect-first="true">
                                </multiselect>
                            </div>
                            <div class="form-group">
                                <label>Số Lượng TN</label>
                                <input v-model="form.mess" type="number" name="mess" class="form-control" :class="{ 'is-invalid': form.errors.has('mess') }">
                                <has-error :form="form" field="mess"></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            editmode: false,
            onlines: {},
            online: {},
            roles: [],
            chiendich:[],
            name: [],
            // salesTarget2: [],
            zipTarget:{
                mess: 0,
                khtn: 0,
                phone: 0,
                chiphi: 0,
                chiphitarget: 0,
            },
            target:{
                mess: 0,
                khtn: 0,
                phone: 0,
                chiphi: 0,
                chiphitarget: 0,
            },
            // Create a new form instance
            form: new Form({
                id: '',
                chiphi: '',
                chiendich:'',
                date:'',
                mess: 0,
            }),
            opens: "center",
            line:[],
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
            startDate: moment('2020-11-30').format(),
            endDate: moment('2020-12-27').format(),

            selectedDate: {
                startDate: moment('2020-11-30'),
                endDate: moment('2020-12-27')
            },
        }
    },
    methods: {
        searchdata() {
            this.loadonlines();
        },
        detailModal(online) {
            this.online = online;
            $('#onlinedetail').modal('show');
        },
        loadonlines(page = 1) {
            let chiendich = queryString.stringify({ chiendich: this.$parent.chiendich.id });
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search});
            axios.get("/api/online?page=" + page + '&' + queryName + '&' + chiendich + '&' + dates_para)
            .then( data  => {
                this.onlines = data.data;
                this.reTotal();
            });
        },
        createonline() {
            this.$Progress.start();
            axios.post('/api/online',{form:this.form,line:this.line})
                .then(() => {
                    $('#onlineModalCenter').modal('hide');
                    this.loadonlines();
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.loadonlines();
        },
        updateonline() {
            this.$Progress.start();
            axios.put('api/online/' + this.form.id,{form:this.form,line:this.line})
                .then(() => {
                    // success
                    $('#onlineModalCenter').modal('hide');
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    );
                     this.loadonlines();
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        editModal(online) {
            this.editmode = true;
            this.line = online.online;
            this.form.reset();
            $('#onlineModalCenter').modal('show');
            this.form.fill(online);
        },
        newModal() {
            this.editmode = false;
            this.form.reset();
            $('#onlineModalCenter').modal('show');
        },
        deleteonline(online) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                // Send request to the server
                if (result.value) {
                    this.form.delete('api/online/' + online.id).then(() => {
                        swal.fire(
                            "Deleted!",
                            "Your file has been deleted.",
                            "success"
                        );
                         this.loadonlines();
                    }).catch(() => {
                        swal.fire("Failed!", "There was something wrong.", "warning");
                    });
                }
            })
        },
        getName(values) {
            if (!values) return ''
            return values.map((ten, index, values) => {
                return ten.name
            })
        },
        searchChienDich(){
            this.loadonlines();
        },
        reTotal(){
           
            this.target.chiphi = this.onlines.data.reduce(function(v, k){
                    return v + Number(k.chiphi);
            }, 0);

            this.target.phone = this.onlines.data.reduce(function(v, k){
                    return v + Number(k.phone);
            }, 0);

            this.target.khtn = this.onlines.data.reduce(function(v, k){
                    return v + Number(k.khtn);
            }, 0);

            this.target.chiphitarget = this.onlines.data.reduce(function(v, k){
                    return v + Number(k.chiphitarget);
            }, 0);

            this.target.mess = this.onlines.data.reduce(function(v, k){
                    return v + Number(k.mess);
            }, 0);

            this.target.targetmess = this.onlines.data.reduce(function(v, k){
                    return v + Number(k.targetmess);
            }, 0);

            this.target.targetkhtn = this.onlines.data.reduce(function(v, k){
                    return v + Number(k.targetkhtn);
            }, 0);

            this.target.targetphone = this.onlines.data.reduce(function(v, k){
                    return v + Number(k.targetphone);
            }, 0);
        },

    },
    created() {
        this.loadonlines();
        Fire.$on('AfterCreate', () => {
            this.loadonlines();
        });
        Fire.$on('searching', () => {
            this.loadonlines();
        });
        axios.get('api/online-target')
        .then(response => {
            this.chiendich = response.data;
        });
    }
}

</script>

