<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Chọn ngày</label>
                    <date-range-picker style="display: block;" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="$parent.selectedDate" @update="updateValues" :ranges="ranges">
                        <div slot="input" slot-scope="picker">{{ $parent.selectedDate.startDate | myDate }} - {{ $parent.selectedDate.endDate | myDate }}</div>
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
        <!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách dự án</h3>
                        <div class="card-tools">
                            <!-- Button trigger modal -->
                            <a href="#" @click="newdaily()" class="btn btn-primary">
                                <i class="fa fa-plus nav-icon "></i> Tạo
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <div class="tableFixHead">
					        <table class="table table-hover ">
					            <thead>
					                <tr>
					                    <th>Ngày tạo</th>
					                    <th>Đại Lý</th>
					                    <th>Người Đại Diện</th>
					                    <th>Tạo bởi</th>
					                    <th>Địa Chỉ</th>
					                    <th>Sản phẩm quan tâm</th>
					                    <th>Báo giá</th>
					                    <th>Thao tác</th>
					                </tr>
					            </thead>
					            <tbody>
					                <tr v-for="contact in contacts.data">
					                    <td>
					                        <router-link :to="{ name: 'ttkhachdaily', query: { id: contact.id }}" >
					                            <p>{{ contact.created_at | myDateTime}}</p>
					                        </router-link>
					                        <small>
					                            <p>{{ contact.start_date | myDate}} </p>
					                        </small>
					                    </td>
					                    <td> {{ contact.name }}</td>
					                    <td>
					                        <router-link :to="{ name: 'ttkhachdaily', query: { id: contact.id }}">
					                            {{ contact.title }} {{ contact.last_name }}
					                        </router-link>
					                    </td>
					                    <td>
					                        {{ contact.username }}
					                    </td>
					                    <td>{{ contact.address }}</td>
					                    <td>
					                        <products :items="contact.products"></products>
					                    </td>
					                    <td>{{ contact.amount | currency }}</td>
					                    <td>
					                        <router-link :to="{ name: 'editDaily', params: { id: contact.id }}">
					                            <i class="fa fa-edit blue"></i>
					                        </router-link>
					                        /
					                        <a href="#" @click="deleteContact(contact.id)">
					                            <i class="fa fa-trash red"></i>
					                        </a>
					                    </td>
					                </tr>
					            </tbody>
					        </table>
					    </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="contacts" :limit=3 @pagination-change-page="loaddaily"><span slot="prev-nav">&lt; Trước</span>
                            <span slot="next-nav">Sau &gt;</span></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            contacts: {},
            costcenters: [],
            resources: [],
            sale_ids: [],
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
                'Tuần này': [moment().startOf('week'), moment()],
                'Tuần trước': [moment().subtract(1, 'week').startOf('week'), moment()],
                'Hai tuần trước': [moment().subtract(2, 'week').startOf('week'), moment()],
                'Ba tuần trước': [moment().subtract(3, 'week').startOf('week'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
            }
        }
    },
    methods: {
        searchdata() {
            this.loaddaily();
        },
        updateValues(values) {
            this.$parent.startDate = values.startDate.toISOString().slice(0, 10)
            this.$parent.endDate = values.endDate.toISOString().slice(0, 10)
        },
        loaddaily(page = 1) {
            axios.get("/api/daily?page=" + page + '&' + this.getPara())
                .then(response => {
                    this.contacts = response.data;
                });
        },
        newdaily() {
            location.replace("/them-dai-ly")
        },
        ttkhachtiemnang(id) {
            axios.put('/daily/session/' + id)
                .then(response => {
                    location.replace("/thong-tin-khach-tiem-nang")
                })
        },
        getPara(){
            let dates_para = queryString.stringify({ dates: [this.$parent.startDate.slice(0, 10), this.$parent.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            return dates_para + '&' + queryName;
        },
        
    },
    created() {
        this.loaddaily();
        Fire.$on('searching', () => {
            this.loaddaily();
        })
    }
}

</script>
