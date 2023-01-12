<template>
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-body" style="padding: 10px;">
                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn ngày</label>
                            <date-range-picker style="display: block;" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="$parent.selectedDate" @update="updateValues" :ranges="ranges">
                                <div slot="input" slot-scope="picker">{{ $parent.selectedDate.startDate | myDate }} - {{ $parent.selectedDate.endDate | myDate }}</div>
                            </date-range-picker>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Chọn Nhân Viên</label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="resources" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheoSale">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                        </div>
                    </div>
                </div>
            </div>
           <!--  <div class="card-footer clearfix">
                <a v-bind:href="downloadExcel" target="_blank" class="btn btn-info float-right"><i class="fa fa-file-excel-o"></i> Xuất file</a>
            </div> -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách nhân viên vi phạm <a href="#" @click="loadcontacts"><i class="fa fa-refresh"></i></a></h3>
                        <div class="card-tools">
                            <!-- Button trigger modal -->
                                <router-link :to="{ name: 'taobienban'}" class="btn btn-primary">
                                    <i class="fa fa-plus nav-icon "></i> Tạo
                                </router-link>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0 ">
                        <div class="tableFixHead">
                            <table class="table table-hover ">
                                <thead>
                                    <tr>
                                        <th>Ngày lập biên bản</th>                                       
                                        <th>Tên Biên Bản</th>
                                        <th>Nhân Viên vi phạm</th>
                                        <th>Tạo bởi</th>
                                        <th>Nhóm quy định</th>
                                        <th>Gửi mail </th>
                                       <!--  <th>Nội Dung</th>
                                        <th>Ghi Chú</th> -->
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in bienban.data" :key="user.id">
                                    <!-- <td>{{user.id}}</td> -->
                                        <td>{{user.date | myDate}}</td>
                                        <td> <a href="#" @click="show(user)">{{user.name}}</a></td>
                                        <td>{{user.userPhat}}</td>
                                        <td>{{user.userCreate}}</td>
                                        <td>{{user.name_quydinh}}</td>
                                        <td><button @click="sendMail(user)" class="btn btn-primary">gửi mail</button></td>
                                        <!-- <td>{{user.tieude}}</td>
                                        <td>{{user.note}}</td> -->
                                        
                                        <td>
                                            <router-link :to="{ name: 'suabienban', params: { id: user.id }}">
                                                <i class="fa fa-edit blue"></i>
                                            </router-link>
                                        
                                            <a href="#" @click="deleteUser(user)">
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
                        <pagination :data="contacts" :limit=3 @pagination-change-page="loadcontacts"><span slot="prev-nav">&lt; Trước</span>
                            <span slot="next-nav">Sau &gt;</span></pagination>
                    </div>
                </div>
                <!-- /.card -->
            </div>
         </div>


         <div class="modal  fade" id="LineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Bản</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                          <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Tên biên bản</label>
                                 <input v-model="user.name" type="text" class="form-control"  disabled="true">
                            </div>
                        </div>
                         <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Email</label>
                                 <input v-model="user.email" type="text" class="form-control" disabled="true">
                            </div>
                        </div>
                         <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Nhân viên vi phạm</label>
                                 <input v-model="user.userPhat" type="text" class="form-control"  disabled="true">
                            </div>
                        </div>
                        <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Nhóm quy trình</label>
                                 <input v-model="user.name_quytrinh" type="text" class="form-control" disabled="true">
                            </div>
                        </div>
                        <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Tên quy định</label>
                                 <input v-model="user.name_quydinh" type="text" class="form-control"  disabled="true">
                            </div>
                        </div>
                        <div class="form-row">                         
                            <div class="form-group col-md-12">
                                    <label for="date">Ngày vi phạm</label>
                                    <input v-model="user.ngayvipham" type="text" class="form-control" id="date" disabled="true">
                            </div>
                        </div>
                         <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Số tiền phạt</label>
                                 <input v-model="user.price" type="text" class="form-control" disabled="true">
                            </div>
                        </div>
                         <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Lý do phạt</label>
                                 <textarea v-model="user.reason" type="text" class="form-control" disabled="true"></textarea>
                            </div>
                        </div>
                         <div class="form-row">     
                            <div class="form-group col-md-12">
                                <label>Ghi chú</label>
                                 <textarea v-model="user.note" type="text" class="form-control" disabled="true"></textarea>
                            </div>
                        </div>
                         
                           <div class="modal-footer" style="float:right;">
                        <div class="row no-print">
                            <div class="col-12" style="float:right">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                <a id="print" :href="'/quydinh/print/'+user.id" target="_blank" class="btn btn-default btn-success" style="margin-right: 10px;"><i class="fa fa-print"></i> Print</a>
                            </div>
                        </div>
                    </div>
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
            reason: '',
            note: '',
            date: '' ,
            ngayvipham: '',
            form: '',
            note: '',
            bienban: '', 
            email: '',
            user: [],
            resources: [],
            contacts: {},
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
         sendMail(user) {
            this.user = user.id;
            axios.get("api/postSendMail/" + this.user)
                .then(response => {
                    this.$router.push({ name: 'bienban' });
                });
        },
         deleteUser(user) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    this.form.delete('api/bienban/' + user.id).then(() => {
                        this.loadUsers();
                        this.$Progress.finish();
                    }).catch();
                }
            })
        },
        show(user) {
            this.user = user;
            // this.orderLine = order.orderLine;
            $('#LineModal').modal('show');
        },
        updateValues(values) {
            this.$parent.startDate = values.startDate.toISOString().slice(0, 10)
            this.$parent.endDate = values.endDate.toISOString().slice(0, 10)
            this.loadcontacts();
        },
        timTheoSale() {
            this.loadcontacts();
        },
        loadcontacts(page = 1) {
            this.sale_ids = this.$parent.saleSelected.map(sale => {
                return sale.id
            });
            let dates_para = queryString.stringify({ sdates: [this.$parent.startDate.slice(0, 10), this.$parent.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            axios.get("/api/bienban?page=" + page + '&' + dates_para + '&' + queryName)
                .then(response => {
                    this.bienban = response.data;
                });
        },
        editcontact(id) {
            axios.post("/setcontact", { id: id })
                .then(response => {
                    location.replace("/them-khach-tiem-nang")
                })
        },
    },
    created() {
        this.loadcontacts();
        axios.get('/api/picklists/users')
            .then(res => this.resources = res.data);
        Fire.$on('searching', () => {
            this.loadcontacts();
        });
       axios.get('/api/picklists/users')
            .then(res => this.resources = res.data);
        
    }
}

</script>
