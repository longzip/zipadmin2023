 <template>
     <div>
        <div class="row">
               <div class="col-md-3">
                        <div class="form-group">
                            <label>Chọn nhân viên<a href="#" @click="reSelectnhanvien"><i class="fa fa-refresh"></i></a></label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.Selectednhanvien" :options="users"   :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheonhanvien" >
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                        </div>
                </div>
                <div class="col-md-3">
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





        <div class="row justify-content-center">
                <div class="col-12 mt-5">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Nghỉ phép</h3>
                            <div class="card-tools">
                                <a href="#" @click="newModal" class="btn btn-primary">
                                    Thêm  <i class="fa fa-user-plus"></i>
                                </a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <!-- <th>id</th> -->
                                        <th>Người tạo</th>
                                        <th>Phòng</th>
                                        <th>Người duyệt</th>
                                        <th>Thời gian</th>
                                        <th>Lý do</th>
                                        <th>Bàn giao</th>
                                        <!-- <th>tên</th> -->
                                        <!-- <th>SDT</th> -->
                                        <th>Bắt đầu</th>
                                        <th>Kết thúc</th>
                                        <th>Chế độ</th>
                                        <th>Tình trạng</th>
                                        <th>Xem đơn</th>
                                        <th>Modify</th>
                                    </tr>
                                        <tr v-for="valu in nghipheps.data" >
                                            <!-- <td>{{valu.id}}</td> -->
                                            <td><a href="#" @click="detail_nghiphep(valu)">{{valu.user.name}}</a></td>
                                            <td>{{valu.role.name}}</td>
                                            <td>
                                                <span v-if="valu.nvpheduyet != null">{{valu.nvpheduyet.name}}</span>
                                                <span v-if="valu.nvpheduyet == null">BP Nhân sự</span>
                                            </td>

                                            <td>{{valu.songaynghi}}</td>
                                            <td>{{valu.lydo}}</td>
                                            <td>{{valu.cvbangiao}} <br>
                                                <span style="color:#190707;font-weight:600;" v-if="valu.nvbangiao != null">
                                                người nhận bàn giao:{{valu.nvbangiao.name}}
                                                </span>
                                                <span style="color:#190707;font-weight:600;" v-if="valu.nvbangiao == null">
                                                người nhận bàn giao: Không
                                                </span>
                                            </td>
                                            <!-- <td>{{valu.phone}}</td> -->
                                            <td>{{valu.date | formatDate  }}</td>
                                            <td>{{valu.dates | formatDate  }}</td>
                                            <td>
                                                <span v-if="valu.loainghi == 1">Nghỉ không lương</span>
                                                <span v-if="valu.loainghi == 2">Nghỉ chế độ</span>
                                                <span v-if="valu.loainghi == 3">Nghỉ việc</span>
                                            </td>
                                            <td><a v-if="valu.tinh_trang == 1  || valu.tinh_trang == 5 " class="btn btn-warning" >
                                                    đã duyệt
                                                </a>
                                                <a v-if="valu.tinh_trang == 0 || valu.tinh_trang == 2 || valu.tinh_trang == 4" href="#"><router-link :to="{ name: 'previewnghiphep', params: { valu_id: valu.id }}" data-dismiss="modal"  class="btn btn-primary btn-block">Xem</router-link></a>
                                                <a v-if="valu.tinh_trang == 3" href="#"><router-link :to="{ name: 'previewnghiphep', params: { valu_id: valu.id }}" data-dismiss="modal"  class="btn btn-primary btn-block">Không duyệt</router-link></a>
                                            </td>
                                            <td>
                                                <span v-if="valu.bienban == null ">
                                                <a href="#"><i class="fa fa-eye-slash" style="font-size:30px;color:#DF3A01"></i></a>
                                                </span>
                                                <span v-if="valu.bienban != null ">
                                                    <a target='_blank' v-bind:href="'uploads/nghiphep/'+valu.bienban"><i class="fa fa-eye" style="font-size:30px;color:green"></i></a>
                                                </span>
                                            </td>
                                            <td>
                                                 <span v-if="valu.tinh_trang == 0">
                                                 </span>
                                                 <span v-if="valu.tinh_trang == 1">
                                                <a href="#" @click="deleteModal(valu)">
                                                </a>
                                                 </span>
                                                 <span v-if="valu.tinh_trang == 3">
                                                     <a href="#" @click="editModal(valu)" >
                                                    <i class="fa fa-edit blue"></i>
                                                </a>
                                                <a href="#" @click="deleteModal(valu)">
                                                    <i class="fa fa-trash red"></i>
                                                </a>
                                                 </span>
                                            </td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <pagination :data="nghipheps" @pagination-change-page="loadnghiphep"></pagination>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
        </div>

        <div class="modal  fade" id="nghiphepdetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Nghỉ Phép</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                          <div class="form-row">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Người tạo</th>
                                        <th>Phòng</th>
                                        <th>Người duyệt</th>
                                        <th>Lý do</th>
                                        <th>Số ngày nghỉ</th>
                                        <th>Sđt</th>
                                        <th>Bàn giao</th>
                                        <th>Bắt đầu</th>
                                        <th>Kết thúc</th>
                                        <th>Chế độ</th>
                                        <th>Biên bản</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{nghiphepdetail.a}} </td>
                                        <td>{{nghiphepdetail.b}} </td>
                                        <td>{{nghiphepdetail.c}} </td>
                                        <td>{{nghiphepdetail.lydo}} </td>
                                        <td>{{nghiphepdetail.songaynghi}} </td>
                                        <td>{{nghiphepdetail.phone}} </td>
                                        <td>{{nghiphepdetail.cvbangiao}} </td>
                                        <td>{{nghiphepdetail.date | formatDate  }} </td>
                                        <td>{{nghiphepdetail.dates | formatDate  }} </td>
                                        <td>
                                                <span v-if="nghiphepdetail.loainghi == 1">Nghỉ không lương</span>
                                                <span v-if="nghiphepdetail.loainghi == 2">Nghỉ chế độ</span>
                                        </td>
                                        <td><a href=""><router-link :to="{ name: 'previewnghiphep', params: { valu_id: nghiphepdetail.id }}" data-dismiss="modal"  class="btn btn-primary btn-block">Biên bản</router-link></a></td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="createModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenter" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Tạo đơn xin nghỉ</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Sửa đơn</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                   
                   <form @submit.prevent="editmode ? updatenghiphep() : createnghiphep()">
                        <div class="form-row col-md-12">
                            <label>Tên Nhân Viên</label>
                            <multiselect v-model="form.user" ref="users" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn nhân viên" :options="users" :searchable="true" :allow-empty="false">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Chọn bộ phận</label>
                            <multiselect v-model="form.role" ref="role" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn bộ phận" :options="roles" :searchable="true" :allow-empty="false">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                         <div class="form-row col-md-12">
                            <label>Chọn nhân viên phê duyệt</label>
                            <multiselect v-model="form.nvpheduyet" ref="nvpheduyet" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn nhân viên phê duyệt" :options="nvpheduyet" :searchable="true" :allow-empty="false">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                        <div class="form-row col-md-12 col-12">
                            <div class="row">
                                <div class="col-6">     
                                <label for="off">số ngày nghỉ</label>
                                <input v-model="form.songaynghi"  step="any" name="songaynghi" class="form-control" placeholder="số ngày nghỉ">
                                <input v-model="form.phone" type="hidden" class="form-control" :class="{ 'is-invalid': form.errors.has('songaynghi') }">
                                <has-error :form="form" field="songaynghi"></has-error>
                                </div>
                                <div class="col-6">   
                                <label for="off">số điện thoại</label>
                                <input v-model="form.phone"  type="text" name="phone" class="form-control" id="phone">
                                <input v-model="form.phone" type="hidden" class="form-control" :class="{ 'is-invalid': form.errors.has('phone') }">
                                <has-error :form="form" field="phone"></has-error>
                                </div>
                            </div>
                        </div>
                         <div class="form-row col-md-12">
                            <label for="off">lý do xin nghỉ</label>
                            <input v-model="form.lydo"  type="text" name="lydo" class="form-control" placeholder="lý do xin nghỉ">
                            <input v-model="form.lydo" type="hidden" class="form-control" :class="{ 'is-invalid': form.errors.has('lydo') }">
                            <has-error :form="form" field="lydo"></has-error>
                        </div>
                         <div class="form-row col-md-12">
                            <label for="off">công việc bàn giao</label>
                            <input v-model="form.cvbangiao"  type="text" name="cvbangiao" class="form-control" placeholder="công việc bàn giao">
                            <input v-model="form.cvbangiao" type="hidden" class="form-control" :class="{ 'is-invalid': form.errors.has('cvbangiao') }">
                            <has-error :form="form" field="cvbangiao"></has-error>
                        </div>
                        <div class="form-row col-md-12">
                            <label>Người nhận bàn giao công việc</label>
                            <multiselect v-model="form.nvbangiao" ref="nvbangiao" deselect-label="Can't remove this value" track-by="id" label="name" placeholder="Chọn nhân viên nhận bàn giao công việc" :options="nvbangiao" :searchable="true" :allow-empty="false">
                                <template slot="singleLabel" slot-scope="{ option }">
                                    <strong>{{ option.name }}</strong> 
                                </template>
                            </multiselect>
                        </div>
                        <div class="form-row col-md-12 col-12">
                            <div class="row">
                            <div class="col-6">
                                <label for="off">Ngày xin nghỉ</label>
                                <input v-model="form.date" ref="date" type="datetime-local" class="form-control" id="off">
                                <input v-model="form.date" type="hidden" class="form-control" :class="{ 'is-invalid': form.errors.has('date') }">
                                <has-error :form="form" field="date"></has-error>
                            </div>

                            <div class="col-6">
                                <has-error :form="form" field="date"></has-error>
                                <label for="off">kết thúc ngày</label>
                                <input v-model="form.dates" name="dates" ref="dates" type="datetime-local" class="form-control" id="offs">
                                <input v-model="form.dates" type="hidden" class="form-control" :class="{ 'is-invalid': form.errors.has('dates') }">
                                <has-error :form="form" field="dates"></has-error>
                            </div></div>
                        </div>
                         <div class="form-row col-md-12">
                            <label>loại nghỉ</label><br/>
                            <input type="radio" id="nghi-khong-luong" value="1" v-model="form.loainghi" ref="loainghi">
                            <label for="nghi-khong-luong">không lương</label>
                            <input type="radio" id="nghi-che-do" value="2" v-model="form.loainghi" ref="loainghi">
                            <label for="nghi-che-do">Chế độ</label>
                            <input type="radio" id="nghi-khong-luong" value="3" v-model="form.loainghi" ref="loainghi">
                            <label for="nghi-khong-luong">nghỉ việc</label>
                             <input v-model="form.dates" type="hidden" class="form-control" :class="{ 'is-invalid': form.errors.has('loainghi') }">
                            <has-error :form="form" field="loainghi"></has-error>
                        </div>
                        <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="clear()">Close</button>
                        <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                        <button v-show="!editmode" type="submit" class="btn btn-primary" >Create</button>
                        </div>
                </form>
            </div>
            </div>
        </div>
        <div class="modal  fade" id="nghiphepDetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết đơn xin nghỉ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-row"> 
                        <div class="form-row col-md-12">
                            <div class="col-md-10 float-left">
                                <label>Tên Nhân viên: </label>
                                <label class="detail">{{user.name}}</label>
                                <br>
                                <label>Tên bộ phận: </label>
                                <label class="detail">{{role.name}}</label>
                                <br>
                                <label>số ngày nghỉ: </label>
                                <label class="detail">{{nghiphep.songaynghi}}</label>
                                <br>
                                <label>Lý do: </label>
                                <label class="detail">{{nghiphep.lydo}}</label>
                                <br>
                                <label>loại nghỉ: </label>
                                <label>
                                    <span v-if="nghiphep.loainghi == 0">Nghỉ phép</span>
                                    <span v-if="nghiphep.loainghi == 1">Nghỉ không lương</span>
                                    <span v-if="nghiphep.loainghi == 2">Nghỉ chế độ</span>
                                    <span v-if="nghiphep.loainghi == 3">Nghỉ việc</span>
                                </label>
                                <br>
                                <label>công việc bàn giao: </label>
                                <label class="detail">{{nghiphep.cvbangiao}}</label>
                                <br>
                                <label>số điện thoại: </label>
                                <label class="detail">{{nghiphep.cvbangiao}}</label>
                                <br>
                                <label>ngày gửi đơn: </label>
                                <label class="detail">{{nghiphep.date | formatDate }} <span> đến ngày: {{nghiphep.dates | formatDate }}</span></label>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="modal-footer">
                    <router-link :to="{ name: 'previewnghiphep', params: { nghiphep_id: nghiphep.id }}" data-dismiss="modal"  class="btn btn-primary ">Tạo đơn xin nghỉ</router-link>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" @click="hideFileDetail()">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal  fade" id="comentModal" tabindex="-1" role="dialog" aria-labelledby="taskModalDetailTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Duyệt đơn xin nghỉ</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form>
                        <comments :comments="nghiphep.comments" @create-comment="storeNghiphepComment"></comments>
                        <div class="modal-footer">
                        <span v-if="pheduyet.id == nghiphep.nhanvien && nghiphep.bienban == null  && nghiphep.check == 0">

                                <a  @click="duyetlanmot(nghiphep)" class="btn btn-success">trưởng phòng Duyệt</a>
                                <a @click="khongduyet(nghiphep)" class="btn btn-primary">không duyệt</a>
                        </span>
                        <span ><a v-if="nghiphep.bienban != null"  class="btn btn-success">Trưởng phòng đã duyệt</a></span>
                        <span v-if="nghiphep.bienban != null && nghiphep.check == 1">
                            <a  @click="duyet(nghiphep)" class="btn btn-success">Nhân Sự Duyệt</a>
                            <a @click="khongduyet(nghiphep)" class="btn btn-primary">không duyệt</a>
                        </span>

                        <!-- <span v-if-else="nhansu.id == nghiphep.nhanvien || nghiphep.bienban != null ">
                            <a href="#">duyet lan 2</a>
                        </span> -->
                        <!-- <span v-if="pheduyet.id != nghiphep.nhanvien || nhansu.id != nghiphep.nhanvien">
                            <p></p>
                        </span> -->
                        <!-- <span v-else="pheduyet.id != nghiphep.nhanvien || nghiphep.bienban != null ">
                            <p></p>
                        </span> -->
                        </div>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" @click="clear()">Close</button> 
                    </form>
                </div>

            </div>
        </div>
    </div>
 </template>

  <style type="text/css">
      .form-row {
        display: block;
      }

      #fileDetail {
        display: none;
      }

      .pheduyet {
        width: 50%;
      }

      .form-row {
        clear: both;
      }

    </style>

    <script>
        export default{
            data(){
                return {
                    editmode: false,
                    bienbanmode: false,
                    roles: [],
                    opens: "center",
                    users: [],
                    nghipheps:{},
                    bienban:[],
                    nvpheduyet: [],
                    nvbangiao:[],
                    pheduyet: [],
                    decisions: [],
                    decision: [],
                    quytrinh: [],
                    statuss: '',
                    tinhtrang: [],
                    user:[],
                    role:[],
                    nghiphepdetail:[],
                    nghiphep:[],
                    comments: {},
                    comment: {
                      body: ''
                    },
                    myTask: {},
                    tasks: {},
                    form: new Form({
                        id: '',
                        user: '',
                        role: '',
                        nvpheduyet:'',
                        nvbangiao:'',
                        songaynghi: '',
                        lydo: '',
                        cvbangiao: '',
                        date: '',
                        status: '',
                        pheduyet: '',
                        phone: '',
                        date : '',
                        dates: '',
                        loainghi: '',

                    }),
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
            methods:{
                searchdata() {
                    this.loadnghiphep();
                },
                clear() {
                    this.form.reset();
                },
                newModal() {
                    this.editmode = false;
                    $('#createModalCenter').modal({backdrop: 'static', keyboard: false});
                },
                likeModal(nghiphep) {
                    this.nghiphep = nghiphep;
                    this.bienban = nghiphep.bien_ban;
                    this.pheduyet = nghiphep.nvpheduyet;
                    // this.nhansu = nghiphep.nhan_su;
                    // console.log(nhansu);
                    this.tinhtrang =  nghiphep.tinh_trang;
                    $('#comentModal').modal({backdrop: 'static', keyboard: false});
                },
                // nhansuduyet(nghiphep){
                //     axios.get('/api/nhansuduyets/' + nghiphep.id).then(() => {
                //             $('#comentModal').modal('hide');
                //         this.loadnghiphep();
                //     });
                // },
                detail_nghiphep(valu){
                this.nghiphepdetail = valu;
                this.nghiphepdetail.a = valu.user.name;
                this.nghiphepdetail.b = valu.role.name;
                this.nghiphepdetail.c = valu.nvpheduyet.name;
                console.log(this.nghiphepdetail.nvpheduyet);
                // console.log(.name);
                $('#nghiphepdetail').modal({backdrop: 'static', keyboard: false});
                },
                duyet(nghiphep) {
                        axios.get('/api/postduyet/' + nghiphep.id).then(() => {
                            $('#comentModal').modal('hide');
                        this.loadnghiphep();
                    });
                },
                duyetlanmot(nghiphep){
                    location.replace('/exportpdfnp/'+nghiphep.id);
                },
                khongduyet(nghiphep) {
                        axios.get('/api/khongduyets/' + nghiphep.id).then(() => {
                            $('#comentModal').modal('hide');
                        this.loadnghiphep();
                    });
                },
                storeNghiphepComment(comment) {
                    axios.put('api/nghiphepcomment/'+ this.nghiphep.id, {
                        body: comment
                    })
                    .then(response => {
                        this.nghiphep.comments.push(response.data);
                    });
                },
                editModal(nghiphep) {
                    this.editmode = true;
                    this.form.reset();
                    $('#createModalCenter').modal('show');
                    this.form.fill(nghiphep);
                },
                updatenghiphep() {

                    this.$Progress.start();
                    this.form.put('api/nghiphep/' + this.form.id)
                        .then(() => {
                            // success
                    $('#createModalCenter').modal('hide');
                        swal.fire(
                             'Updated!',
                             'Information has been updated.',
                             'success'
                            );
                            this.loadnghiphep();
                            this.$Progress.finish();
                            Fire.$emit('AfterCreate');
                        })
                        .catch(() => {
                            this.$Progress.fail();
                        });
                },
                updateValues(values) {
                    this.startDate = values.startDate.toISOString().slice(0, 10);
                    this.startDate = moment(this.startDate).add(1, 'day').format().slice(0,10);
                    this.endDate = values.endDate.toISOString().slice(0, 10);
                    this.endDate = moment(this.endDate).endOf('week').format().slice(0,10);
                },
                hideFileDetail() {
                document.getElementById('fileDetail').style.display = "none";
                },
                createnghiphep() {
                    this.$Progress.start();
                    this.form.post('/api/nghiphep')
                    .then(() => {
                        $('#createModalCenter').modal('hide');
                        this.loadnghiphep();
                        this.$Progress.finish();
                    })
                    .catch(() => {
                        this.$Progress.fail();
                        });
                },
                 nghiphepDetailCenter(nghiphep) {
                this.nghiphep = nghiphep;
                this.user = nghiphep.user;
                this.role = nghiphep.role;
                $('#nghiphepDetailCenter').modal({backdrop: 'static', keyboard: false});
                 },
                isbienbanmode(nghiphep) {
                this.bienbanmode = true;
                this.nghiphepDetailCenter(nghiphep);
                },
                reSelectnhanvien() {
                    this.$parent.Selectednhanvien = [];
                    this.loadnghiphep();
                },
                timTheonhanvien() {
                 },
                loadnghiphep(page = 1) {
                    let queryName = queryString.stringify({name: this.$parent.search});

                    let datey = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
                    let nhanvien = this.$parent.Selectednhanvien.id;
                    if(nhanvien){
                    var nv = nhanvien;
                    }else {
                    var nv = 0;
                    }
                    axios.get("/api/nghiphep?page=" + page + '&' + queryName + '&nhanvien=' + nv + '&' + datey).then(({ data }) => (this.nghipheps = data));
                },
                deleteModal(nghiphep) {
                    console.log(nghiphep);
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
                        this.form.delete('api/nghiphep/' + nghiphep.id).then(() => {
                            this.loadnghiphep();
                            this.$Progress.finish();
                        }).catch();
                    }
                })
                }
            },
            created() {
                this.loadnghiphep();
                Fire.$on('AfterCreate', () => {
                    this.loadnghiphep();
                });
                Fire.$on('searching', () => {
                    this.loadnghiphep();
                });
                axios.get('/api/phe-duyet').then(({ data }) => (this.nvpheduyet = data));
                axios.get('/api/getAllUsers').then(({ data }) => (this.nvbangiao = data));
                axios.get('/api/getAllUsers').then(({ data }) => (this.users = data));
                axios.get("/api/getallobjectroles").then(({ data }) => (this.roles = data));

            }
        }

    </script>