<template>
    <div class="container-fluid">
        <div class="row mb-12">
            <h4 class="m-0 text-dark">Danh Sách Khách Hàng</h4>
        </div>
        <div class="row mb-12">
            <div class="col-sm-2">
                <div class="form-group">
	                <label>Chọn Ngày</label>
	                <date-range-picker class="form-control" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges">
                        <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                    </date-range-picker>
	            </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>Chọn sản phẩm <a href="#" @click="reSelectSanPham"><i class="fa fa-refresh"></i></a></label>
                    <select  class="form-control" v-model="p" @change="onChange($event)">
                        <option v-for="option in $parent.p" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Showroom <a href="#" @click="reSelect"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="costcenters" :multiple="true" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn"  @close="timTheoSR">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Chọn Trạng Thái <a href="#" @click="reSelectStatus"><i class="fa fa-refresh"></i></a></label>
                    <select  class="form-control" v-model="status" @change="onChange($event)">
                        <option v-for="option in $parent.status" v-bind:value="option.value">
                            {{ option.text }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Tên Chiến Dịch</label>
                    <multiselect v-model="cd" :options="chiendich" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn chiến dịch" label="name" :preselect-first="true" @close="timTheoCD">
                    </multiselect>
                </div>
            </div>

            <div class="col-md-1">
                <div class="form-group">
                    <label>KHTN</label>
                    <input type="checkbox" class="form-control" v-model="checkkhtn"  @change="loadgetkhtn()" >
                </div>
            </div>
            <div class="col-sm-2">
                
            </div><!-- /.col -->
        </div>
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#marketing" @click="marketing">Marketing {{sumkhachmarketing}}</a>
            </li>
            <template >
                <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#data" @click="data">Data</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" data-toggle="pill" href="#dh" @click="">Online</a>
                </li> -->
                <li class="nav-item" v-if="login.id != 269">
                    <a class="nav-link" data-toggle="pill" href="#sr" @click="showroom">Showroom</a>
                </li>
                <li class="nav-item" v-if="login.id == 269">
                    <a class="btn btn-success" href="/them-marketing">Thêm</a>
                </li>
            </template>
            
        </ul>
        <div class="tab-content"  style="width:100%">
            <div id="marketing" class="tab-pane active ml-0 mr-0 pr-0 pl-0" style="min-width:680px">
                <div class=" table-responsive tableFixHead">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Ngày Tạo</th>
                                <th class="text-center">Showroom</th>
                                <th class="text-center">Tên</th>
                                <th class="text-center">SĐT</th>
                                <th class="text-center">Nhân Viên</th>
                                <th class="text-center">Chiến Dịch</th>
                                <th class="text-center">Trạng Thái</th>
                                <th class="text-center">Giai Đoạn</th>
                                <th class="text-center"><a class="btn btn-success" href="/them-marketing">Thêm</a></th>
                            </tr>
                        </thead>
                        <tbody>
                        	<template v-for="(value,key) in khach">
	                            <tr v-for="(v, i) in value">
	                            	<td class="text-center" :rowspan="value.length" v-if="i==0">{{key}}</td>
	                            	<td class="text-center">{{v.costcenters}}</td>
	                            	<td class="text-center">
	                            		<a v-bind:href="v.email" v-if="v.email != null">{{v.title}}  {{v.last_name}}</a>
	                            		<span v-if="v.email == null">{{v.title}}  {{v.last_name}}</span>

                                        <router-link  target= '_blank'  :to="{ name: 'ttkhachtiemnang', query: { id: v.id_khtn }}" v-if="v.id_khtn != null">
                                            <span>Xem KHTN</span>
                                        </router-link>
                                        <router-link target= '_blank'  :to="{ name: 'ttkhachtiemnang', query: { id: v.idContact }}" v-if=" v.id_khtn == null && v.idContact != '' ">
                                            <span>Xem KHTN</span>
                                        </router-link>
                                        <router-link  target= '_blank'  :to="{ name: 'kh15Show', query: { id: v.id }}">
                                            <i class="fa fa-info-circle" v-bind:style="{ fontSize: '24px'}"></i>
                                        </router-link>
	                            	</td>
	                            	<td class="text-center">
	                            		<!-- <router-link :to="{ name: 'ttkhachhang', query: { id: v.id , idContact: v.idContact }}"> -->
				                            <p><a href="#" @click="showphone(v.phone)">{{ v.phone | formatPhone}}</a></p>
				                        <!-- </router-link></td> -->
	                            	<td class="text-center">
	                            		<router-link :to="{ name: 'ttnhanvien', query: { id: v.id , costcenters: v.costcenters , user: v.user_create}}">
				                            <p>{{v.username}}</p>
				                        </router-link></td>
	                            	</td>
                                    <td class="text-center">
                                        <p>{{v.chiendich}}</p>
                                    </td>
	                            	<td class="text-center">
                                        <span class="badge" v-if="v.typecontact != null" v-bind:class="getStatusClass(v.typecontact)">{{v.typecontact | ContactStatus}}</span>
                                        <span class="badge" v-if="v.typecontact == null">
                                            <span v-if="v.statuskh == 1">Đang Chăm</span>
                                            <span v-if="v.statuskh == 2" class="badge badge-secondary">Tạm Dừng</span>
                                        </span>
                                    </td>
	                            	<td class="text-center">
            							<span v-if="v.giaidoan == 1">B1</span>
            							<span v-if="v.giaidoan == 2">B2</span>
            							<span v-if="v.giaidoan == 3">C1</span>
            							<span v-if="v.giaidoan == 4">C2</span>
            							<span v-if="v.giaidoan == 5">D</span>
            							<span v-if="v.giaidoan == 6">Tạm Dừng</span>
            							<span v-if="v.giaidoan == 7">Không Có</span>
            							<a href="#" @click="showgiaidoan(v)">({{v.time_giaidoan | myDateTime}})</a>
	                            	</td>
	                            	<td>
	                            		<router-link  target= '_blank'  :to="{ name: 'kh15Show', query: { id: v.id }}">
	                            			<i class="fa fa-info-circle" v-bind:style="{ fontSize: '24px'}"></i>
	                            		</router-link>
	                            		<router-link  target= '_blank'  :to="{ name: 'ttkhachtiemnang', query: { id: v.id_khtn }}" v-if="v.id_khtn != null">
	                            			<i class="fa fa-user"></i>
	                            		</router-link>
	                            		<router-link target= '_blank'  :to="{ name: 'ttkhachtiemnang', query: { id: v.idContact }}" v-if=" v.id_khtn == null && v.idContact != '' ">
	                            			<i class="fa fa-user"></i>
	                            		</router-link>
	                            		
	                            		<router-link :to="{ name: 'editLead', params: { id: v.id }}" v-if="v.asm == 0 && v.id_khtn == null || v.user_login == 269 || v.marketing == 0">
					                        <i class="fa fa-edit blue"></i>
					                    </router-link>
					                    <a href="#" @click="deleteLead(v.id)" v-if="v.costcenters == '' && v.asm == 1 && v.marketing == 0">
					                        <i class="fa fa-trash red"></i>
					                    </a>
					                    <span v-if="v.costcenters != '' && v.marketing == 0">
					                    	Đang chăm sóc	
					                    </span>
	                            	</td>
	                            </tr>
                        	</template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="data" class=" tab-pane ml-0 mr-0 pr-0 pl-0" style="min-width:680px">
                <div class="tableFixHead table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Ngày Tạo</th>
                                <th class="text-center">Showroom</th>
                                <th class="text-center">Tên</th>
                                <th class="text-center">SĐT</th>
                                <th class="text-center">Mô Tả</th>
                                <th class="text-center">Nhân Viên</th>
                                <th class="text-center">Giai Đoạn</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                        	<template v-for="(value,key) in khach">
	                            <tr v-for="(v, i) in value">
	                            	<td class="text-center" :rowspan="value.length" v-if="i==0">{{key}}</td>
	                            	<td class="text-center">{{v.costcenters}}</td>
	                            	<td class="text-center">{{v.title}}  {{v.last_name}}</td>
                                    <td class="text-center">
                                        <p><a href="#" @click="showphone(v.phone)">{{ v.phone | formatPhone}}</a></p>
                                    </td>
	                            	<td class="text-center">{{v.note}}<br>{{v.description}}</td>
	                            	<td class="text-center">{{v.username}}</td>
	                            	<td class="text-center">
            							<span v-if="v.giaidoan == 1">B1</span>
            							<span v-if="v.giaidoan == 2">B2</span>
            							<span v-if="v.giaidoan == 3">C1</span>
            							<span v-if="v.giaidoan == 4">C2</span>
            							<span v-if="v.giaidoan == 5">D</span>
            							<span v-if="v.giaidoan == 6">Tạm Dừng</span>
            							<span v-if="v.giaidoan == 7">Không Có</span>
            							<a href="#" @click="showgiaidoan(v)">({{v.time_giaidoan | myDateTime}})</a>
	                            	</td>
	                            	<td>
	                            		<router-link :to="{ name: 'kh15Show', query: { id: v.id }}" v-if="v.marketing != 0">
	                            			<i class="fa fa-info-circle"></i>
	                            		</router-link>
	                            		<router-link :to="{ name: 'ttkhachtiemnang', query: { id: v.id_khtn }}" v-if="v.marketing != 0 && v.id_khtn !=null ">
	                            			<i class="fa fa-user"></i>
	                            		</router-link>
	                            		<router-link :to="{ name: 'editLead', params: { id: v.id }}" v-if="v.asm == 0 && v.id_khtn ==null">
					                        <i class="fa fa-edit blue"></i>
					                    </router-link>
					                    <a href="#" @click="deleteLead(v.id)" v-if="v.costcenters == '' && v.asm == 1 && v.marketing == 0">
					                        <i class="fa fa-trash red"></i>
					                    </a>
					                    <span v-if="v.costcenters != '' && v.marketing == 0">
					                    	Đang chăm sóc	
					                    </span>
                                        <router-link target= '_blank'  :to="{ name: 'ttkhachtiemnang', query: { id: v.idContact }}" v-if=" v.id_khtn == null && v.idContact != '' ">
                                            <i class="fa fa-user"></i>
                                        </router-link>
                                        
	                            	</td>
	                            </tr>
                        	</template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="dh" class=" tab-pane ml-0 mr-0 pr-0 pl-0" style="min-width:680px">
                <div class="tableFixHead table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nhân viên</th>
                                <th>P</th>
                                <th>T1</th>
                                <th>T2</th>
                                <th>T3</th>
                                <th>T4</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="sr" class=" tab-pane ml-0 mr-0 pr-0 pl-0" style="min-width:680px">
                <div class="tableFixHead table-responsive">
                    <div class=" table-responsive tableFixHead">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Showroom</th>
                                <th class="text-center">Tên</th>
                                <th class="text-center">Thời Gian</th>
                                <th class="text-center">SĐT</th>
                                <th class="text-center">Nhân Viên</th>
                                <!-- <th class="text-center">SP</th> -->
                                <th class="text-center">Báo Giá <small>{{sumbaogia | currency}}</small></th>
                                <th class="text-center">Trạng Thái</th>
                                <th class="text-center">Giai Đoạn</th>
                                <th class="text-center">Điểm Rơi</th>
                                <th class="text-center">Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(value,key) in khach">
                                <tr v-for="(v, i) in value">
                                    <td class="text-center" :rowspan="value.length" v-if="i==0">{{key}}</td>
                                    <td class="text-center">
                                        <span v-if="v.diemroi == 1">{{v.title}}  {{v.last_name}}</span>
                                        <span v-if="v.diemroi != 1" class="badge badge-danger">{{v.title}}  {{v.last_name}}</span>
                                    </td>
                                    <td class="text-center">
                                        <div>KHTN: {{v.start_date | myDate}}</div>
                                        <div v-if="v.start_date_kh != null">KH15: {{v.start_date_kh | myDate}}</div>
                                    </td>
                                    <td class="text-center">
                                        <!-- <router-link :to="{ name: 'ttkhachhang', query: { id: v.id , idContact: v.idContact }}"> -->
                                            <p><a href="#" @click="showphone(v.phone)">{{ v.phone | formatPhone}}</a></p>
                                        <!-- </router-link></td> -->
                                    <td class="text-center">
                                        <router-link :to="{ name: 'ttnhanvien', query: { id: v.id , costcenters: v.costcenters , user: v.user_create}}">
                                            <p>{{v.username}}</p>
                                        </router-link></td>
                                    </td>
                                  <!--   <td class="text-center">
                                        <span v-for="(sp) in v.products">
                                            {{sp.name}},
                                        </span>
                                    </td> -->
                                    <td class="text-center">
                                        <template v-for="(amount,k) in v.quotes">
                                            <div>
                                                {{++k}} : {{amount.total | currency}}
                                            </div>
                                        </template>
                                        
                                    </td>
                                    <td class="text-center">
                                        <span class="badge" v-bind:class="getStatusClass(v.status)">
                                            {{v.status | ContactStatus}}
                                        </span>
                                        <div v-if="v.trangthai != null">
                                        <small>{{v.trangthai.time}}-{{v.trangthai.date}}</small>    
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <span v-if="v.giaidoan == 1">B1</span>
                                        <span v-if="v.giaidoan == 2">B2</span>
                                        <span v-if="v.giaidoan == 3">C1</span>
                                        <span v-if="v.giaidoan == 4">C2</span>
                                        <span v-if="v.giaidoan == 5">D</span>
                                        <span v-if="v.giaidoan == 6">Tạm Dừng</span>
                                        <span v-if="v.giaidoan == 7">Không Có</span>
                                        <a href="#" @click="showgiaidoankhtn(v)">({{v.time_giaidoan | myDateTime}})</a>
                                    </td>
                                    <td class="text-center">{{v.diemroi_name}}</td>
                                    <td>
                                        <!-- <router-link  target= '_blank'  :to="{ name: 'kh15Show', query: { id: v.id }}">
                                            <i class="fa fa-info-circle"></i>
                                        </router-link> -->
                                        <router-link  target= '_blank'  :to="{ name: 'ttkhachtiemnang', query: { id: v.id_khtn }}" v-if=" v.id_khtn != null">
                                            <i class="fa fa-user"></i>
                                        </router-link>
                                        <router-link  target= '_blank'  :to="{ name: 'ttkhachtiemnang', query: { id: v.idContact }}" v-if="v.id_khtn == null && v.idContact != '' ">
                                            <i class="fa fa-user"></i>
                                        </router-link>
                                        
                                        <router-link  target= '_blank'  :to="{ name: 'editLead', params: { id: v.id }}" v-if="v.asm == 0 && v.id_khtn ==null ">
                                            <i class="fa fa-edit blue"></i>
                                        </router-link>

                                        <a href="#" v-if="v.duyet == 0" @click="removeList(v.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a>

                                        <a href="#" v-if=" v.asm == 0 && v.duyet == 0" @click="duyetList(v.id)">
                                            <i class="fa fa-check-square "></i>
                                        </a>

                                        <span href="#" v-if="v.duyet == 1" >
                                            <i class="fa fa-check-square "></i>
                                        </span>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>

        </div>

        <div class="modal fade" id="saleTargetModel" tabindex="-1" role="dialog" aria-labelledby="saleTargetModelTitle" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 550px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saleTargetModelTitle">Lịch Sử Giai Đoạn <small>{{namek}}</small></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered" >
                            <thead class="bg-info">
                                <tr>
                                    <th>#</th>
                                    <th>Giai Đoạn</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(g,index) in loadgiaidoan">
                                    <td>{{index}}</td>
                                    <td>
                                    	<span v-if="g.id_tt == 1">B1</span>
            							<span v-if="g.id_tt == 2">B2</span>
            							<span v-if="g.id_tt == 3">C1</span>
            							<span v-if="g.id_tt == 4">C2</span>
            							<span v-if="g.id_tt == 5">D</span>
            							<span v-if="g.id_tt == 6">Tạm Dừng</span>
            							<span v-if="g.id_tt == 7">Không Có</span>
                                    </td>
                                    <td>{{g.created_at | myDateTime}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="phoneshow" tabindex="-1" role="dialog" aria-labelledby="saleTargetModelTitle" aria-hidden="true">
            <div class="modal-dialog" role="document" style="max-width: 550px;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="saleTargetModelTitle">SĐT: <small>{{phone}}</small></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
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
            phone: '',
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
            uri: '/api/marketing',
            namek: '',
            nguon: 2,
            chien: '',
            khach:[],
            status: '',
            cd: '',
            checkkhtn: '',
            p:[],
            showroomsSelected: [],
            costcenters: [],
            loadgiaidoan:[],
            chiendich:[],
            newstt: ['Gọi Điện','Có Cuộc Hẹn Tại Nhà','Có Cuộc Hẹn Tại Showroom','Cuộc Hẹn Tại Nhà','Cuộc Hẹn Tại Showroom'],
            login: [],
            trangthai: '',
            sumbaogia: 0,
            sumkhachmarketing:0,
        }
    },
    methods: {
        showphone(phone){
            this.phone = phone;
            $('#phoneshow').modal('show');
        },
        loadgetkhtn(){
            this.loadSales();
        },
    	reSelectStatus() {
            this.status = '';
            this.loadSales();
        },
    	timTheoSR() {
            this.loadSales();
        },
        timTheoCD() {
            this.loadSales();
        },
    	reSelect() {
            this.$parent.showroomsSelected = [];
            this.loadSales();
        },
    	reSelectSanPham() {
            this.p = [];
            this.loadSales();
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.loadSales();
        },
        loadSales() {
            let setkhtn = this.checkkhtn;
            var getkhtn = 0;
            if(setkhtn) {
                var getkhtn = 1;
            }
            if (this.cd.length !== 0) {
                this.chien = '&chiendich=' + this.cd.id;
            }
            this.$Progress.start();
            let costcenter_ids = this.$parent.showroomsSelected.map(costcenter => {
                return costcenter.id;
            });
            let status = queryString.stringify({ status: this.status });
            let marketing = queryString.stringify({ marketing: this.trangthai });
            let sr = queryString.stringify({ costcenter: costcenter_ids }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let products = queryString.stringify({ p: this.p });
            let dates_para = queryString.stringify({ datenew: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            axios.get(this.uri + '?' + dates_para + '&nguon=' + this.nguon  + '&getkhtn=' + getkhtn + this.chien + '&' + products + '&' + queryName + '&' + sr + '&' + status + '&' + marketing)
                .then(responsive => {
                    this.khach = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
            if (this.nguon == 4) {
                axios.get('api/sum-showroom-baogia' + '?' + dates_para + '&nguon=' + this.nguon + '&' + products + '&' + queryName + '&' + sr + '&' + status + '&' + marketing)
                    .then(responsive => {
                        this.sumbaogia = responsive.data;
                        this.$Progress.finish();
                    })
                    .catch(() => this.$Progress.fail());
            }

            if (this.nguon == 2) {
                axios.get('api/sum-khach' + '?' + dates_para + '&nguon=' + this.nguon + '&' + products + '&getkhtn=' + getkhtn + '&' + queryName + '&' + sr + '&' + status + '&' + marketing)
                    .then(responsive => {
                        this.sumkhachmarketing = responsive.data;
                    })
            }
        },
        onChange(event) {
            this.loadSales();
        },
        onChangeStatus(){
            this.loadSales();
        },
        marketing() {
        	this.nguon = 2;
            this.uri = 'api/marketing';
            this.loadSales();
        },
        data() {
        	this.nguon = 1;
            this.uri = 'api/khachdata';
            this.loadSales();
        },
        showroom() {
            this.nguon = 4;
            this.uri = 'api/khachshowroom';
            this.loadSales();
        },
        showgiaidoan(id){
	        $('#saleTargetModel').modal('show');
            this.namek = id.last_name;
        	axios.get("/api/loadgiaidoan?id=" + id.id)
                .then(response => {
                    this.loadgiaidoan = response.data;
                });
        },
        showgiaidoankhtn(id){
            $('#saleTargetModel').modal('show');
            this.namek = id.last_name;
            axios.get("/api/loadgiaidoankhtn?id=" + id.id)
                .then(response => {
                    this.loadgiaidoan = response.data;
                });
        },
        getStatusClass(status) {
            if (!status) return 'badge-primary'
            switch (status) {
                case 1:
                    return 'badge-primary';
                    break;
                case 2:
                    return 'badge-secondary';
                    break;
                case 3:
                    return 'badge-warning';
                    break;
                case 4:
                    return 'badge-dark';
                    break;
                case 5:
                    return 'badge-danger';
                    break;
                case 6:
                    return 'badge-success';
                    break;
                case 7:
                    return 'badge-info';
                case 8:
                    return 'badge-info';
            }
        },
        deleteLead(id) {
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
                    axios.delete('api/marketing/' + id)
                    .then(response => {
                        this.$emit('deleted');
                        swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
				        this.loadSales();

                    })
                        
                }
            })
        },

        removeList(id){
            axios.get("/api/removelistsr?id=" + id)
                .then(response => {
                    this.loadSales();
                });
        },
        duyetList(id){
            axios.get("/api/duyetlistsr?id=" + id)
                .then(response => {
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )
                    this.loadSales();
                });
        },
    },
    created() {
        this.loadSales();
        Fire.$on('searching', () => {
            this.loadSales();
        })
        axios.get('api/users-group-costcenters-new')
            .then(res => this.costcenters = res.data);
        axios.get('/api/picklists/login')
            .then(res => this.login = res.data);
        axios.get('api/online-target')
        .then(response => {
            this.chiendich = response.data;
        });
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