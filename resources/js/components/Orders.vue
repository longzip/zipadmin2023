<template>
    <div class="">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Tìm kiếm</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Chọn Showroom <a href="#" @click="reSelectShowroom"><i class="fa fa-refresh"></i></a></label>
                            <multiselect class="form-control" v-model="showroomsSelected" :options="costcenters" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoShowroom">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Chọn sale <a href="#" @click="reSelectSale"><i class="fa fa-refresh"></i></a></label>
                            <multiselect class="form-control" v-model="saleSelected" :options="resources" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheoSale">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                            </multiselect>
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
                            <label>Chọn Khu Vực <a href="#" @click="reSelectArea"><i class="fa fa-refresh"></i></a></label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.khuvucSelected" :options="resou" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheokhuvuc">
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
                            <label>Ngày Giao</label>
                            <input class="form-control" type="checkbox" v-model="checkedCategories" @change="checkdate()" >
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label></label>
                            <a href="#" @click="searchdata" class="btn btn-success">Tìm </i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 mt-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Đơn hàng đã nhập</h3>
                        <div class="card-tools">
                            Tổng Tiền: <b>{{sum.sum_amount | currency}}  </b> /
                            Đã Thu: <b>{{sum.sum_pre_pay | currency}}  </b> /
                            Còn Nợ: <b>{{sum.pay | currency}}  </b> 
                            <b v-for="a in checkAdmin" :key="a.id">
                                <a href="#" v-if="a.checkAdmin == 1" @click="updateDelivery()" class="btn btn-primary">Sửa Ngày</a>
                            </b>
                            Tổng Đơn Hàng {{ totals.total }}
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>SDH</th>
                                    <th>Mã SR</th>
                                    <th>Tạo bởi</th>
                                    <th>Ngày đặt hàng</th>
                                    <th>Ngày giao</th>
                                    <th>Sản phẩm</th>
                                    <th>Tổng tiền</th>
                                    <th>Đã thu</th>
                                    <th>Còn nợ</th>
                                    <th>Ngày Nhập</th>
                                    <th>Modify</th>
                                </tr>
                                <tr v-for="order in orders.data" :key="order.id">
                                    <td>
                                        <a href="#" @click="showOrder(order)">{{order.so_number}}</a><br>
                                        <a v-bind:href="'/push-1office?name='+ order.so_number" v-if="order.office == 0"><i class="fa fa-folder"></i></a>
                                    </td>
                                    <td>{{order.costcenter}}<span v-if="order.xeploai == 1"> ( Data ) </span><span v-if="order.xeploai == 2"> ( Marketing ) </span></td>
                                    <td>
                                        {{order.user_name}} <span v-if="order.chia != null"> ( {{order.chia}} ) </span><br/>
                                        <a href="#" @click="pushOrder(order)" v-if="order.folder == 1"><i class="fa fa-folder"></i></a>
                                    </td>
                                    <td><router-link :to="{ name: 'ttkhachtiemnang', query: { id: order.idContacts }}">{{order.start_date | myDate}}</router-link></td>
                                    <td>
                                        <a href="#" @click="editModal(order)">{{order.delivery_date | myDate}}</a>
                                    </td>
                                    <td>
                                        <products :items="order.products"></products>
                                    </td>
                                    <td>{{order.amount | currency}}</td>
                                    <td>{{order.pre_pay | currency}}</td>
                                    <td>
                                        <a href="#" v-if="order.user_login == 9074 || order.user_login == 9097 || order.user_login == 9161 " @click="updateO(order)">
                                            {{order.amount - order.pre_pay | currency}}
                                        </a>
                                        <a href="#" v-else>
                                            {{order.amount - order.pre_pay | currency}}
                                        </a>
                                    </td>
                                    <td>{{order.created_at | myDateTime}}</td>
                                    <td>
                                        <a href="#" @click="editOrder(order.so_number)">
                                            <i class="fa fa-edit blue"></i>
                                        </a>
                                        /
                                        <a href="#" @click="deleteOrder(order.id)">
                                            <i class="fa fa-trash red"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <pagination :data="orders" @pagination-change-page="getResults" :limit="5"></pagination>
                        <small>Hiển thị 25 đơn hàng từ {{orders.from}} đến {{orders.to}} / {{orders.total}} </small>
                        Tổng Tiền: <b>{{sum.sum_amount_pages | currency}} </b> /
                        Đã Thu: <b>{{sum.sum_pre_pay_pages | currency}} </b> /
                        Còn Nợ: <b>{{sum.pay_pages | currency}}  </b> /
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="out" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"id="addNewLabel">Tài Liệu Thiết Kế</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="clear()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="upFolder()">
                        <div class="form-row col-md-12">
                            <label>Import File</label>
                            <input type="file" id="fileo" ref="fileo" v-on:change="handleFileUploadOut" />
                            <input type="hidden" ref="so_number" />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
                            <button type="submit" class="btn btn-primary">Tạo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal  fade" id="orderLineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết đơn hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row invoice-info">
                            <div class="col-sm-6 invoice-col">
                                To
                                <address v-if="order.sdt == null">
                                    <strong>{{ order.customer_name}}</strong><br>
                                    {{ order.customer_address}}<br>
                                    Điện thoại: {{ order.customer_phone}} 
                                </address>
                                <address v-if="order.sdt != null">
                                    {{ order.note}}<br>
                                    Điện thoại: <b>{{ order.sdt}}</b> 
                                </address>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6 invoice-col">
                                <strong>Số đơn hàng #{{order.so_number}}</strong><br>
                                <strong>Ngày đơn hàng: </strong>{{order.start_date | myDate}}<br>
                                <strong>Ngày giao hàng: </strong>{{order.delivery_date | myDate}}<br>
                                <strong>Showroom: </strong>{{order.costcenter}}<br>
                                <strong>Nhân viên: </strong>{{order.user_name}} - {{order.user_id}}<br>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>SL</th>
                                            <th>Đơn giá</th>
                                            <th>CK</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in orderLine">
                                            <td>{{ item.item }}</td>
                                            <td>{{ item.quantity }}</td>
                                            <td>{{ item.price | currency }}</td>
                                            <td>{{ item.discount}} %</td>
                                            <td>{{ item.amount | currency }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th style="width:50%">Khách đặt cọc: </th>
                                                <td style="width:50%">{{order.pre_pay | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th style="width:50%">Cần phải thu: </th>
                                                <td style="width:50%">{{order.amount - order.pre_pay | currency}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th>Tổng tiền</th>
                                                <td>{{order.subtotal | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Giảm giá</th>
                                                <td>{{order.qgg | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Phí lắp đặt</th>
                                                <td>{{order.fee_ld | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Phí vận chuyển</th>
                                                <td>{{order.fee_vc | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>VAT</th>
                                                <td>{{order.vat | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Thanh toán</th>
                                                <td>{{order.amount | currency}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row no-print">
                            <div class="col-12">
                                <a id="print" href="#" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                                <button @click="duyetDonHang(order.id)" type="button" class="btn btn-success float-right"><i class="fa fa-credit-card"></i> Duyệt đơn hàng
                                </button>
                                <a id="downloadExcel" href="#" class="btn btn-primary float-right" style="margin-right: 5px;">
                                    <i class="fa fa-download"></i> Download Excell
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="orderModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Update Ngày Giao</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="updateOrder()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="off">Ngày Giao Hàng</label>
                                <input v-model="form.delivery_date" type="date" class="form-control" id="off">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="orderDelivery" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập Nhật Chi Tiết Ngày Giao</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="updatedelivery()">
                        <div class="col-md-11">
                            <div class="form-group">
                                <label>Chọn Đơn Hàng</label>
                                <multiselect class="form-control" v-model="formDelivery.selectorder" :options="selectorders" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="so_number" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoOrder">
                                </multiselect>
                            </div>
                        </div>
                        
                        <div class="col-md-11">
                            <div class="form-group">
                                <label>Chọn Sản Phẩm</label>
                                <multiselect class="form-control" v-model="formDelivery.choiceproduct" :options="products" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="item" track-by="id" :preselect-first="true" @close="findSoluong">
                                </multiselect>
                            </div>
                        </div>
                        <div class="row col-md-11">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="off">Số Lượng</label>
                                    <input v-model="formDelivery.soluong" type="text" class="form-control" id="off">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="off">Số Lượng Cũ</label>
                                    <input v-model="formDelivery.soluongold" type="text" class="form-control" >
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Giao Hết</label><br>
                                    <input type="checkbox" id="checkbox" ref="checkbox" v-model="formDelivery.all">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="off">Ngày Giao Hàng</label>
                                    <input v-model="formDelivery.deliverydate" type="date" class="form-control" id="off">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Cập Nhật</button>
                        </div>
                    </form>

                    <table>
                        <thead>
                            <td>Sản Phẩm</td>
                            <td>Số Lượng</td>
                            <td>Ngày Giao</td>
                        </thead>
                        <tbody>
                            <tr v-for="list in listGiao">
                                <td>{{list.item}}</td>
                                <td>{{list.quantity}}</td>
                                <td>{{list.delivery}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal fade" id="orderO" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập Nhật Đơn Hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <h4>{{note}}</h4>
                    <form @submit.prevent="updateOr()">
                        <div class="col-md-11">
                            <div class="form-group">
                                <label>Chọn Đơn Hàng</label>
                                <multiselect class="form-control" v-model="formU.choiceorder" :options="selectorders" :multiple="false" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="so_number" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoO">
                                </multiselect>
                            </div>
                        </div>
                        
                        <div class="col-md-11">
                            <div class="form-group">
                                <label>Chọn Sản Phẩm</label>
                                <multiselect class="form-control" v-model="formU.sp" :options="products" :multiple="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="item" track-by="id" :preselect-first="true" @close="findSoluong">
                                </multiselect>
                            </div>
                        </div>

                        <div class="col-md-11">
                            <div class="form-group">
                                <input type="radio" id="jack" value="0" v-model="formU.status">
                                <label for="jack">Hủy Hết</label>
                                <input type="radio" id="john" value="1" v-model="formU.status">
                                <label for="john">Hủy Sản Phẩm</label>
                                <input type="radio" id="mike" value="2" v-model="formU.status">
                                <label for="mike">Thay Đơn Hàng</label>
                                <input type="hidden" v-model="formU.id">

                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Cập Nhật</button>
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
            note:'',
            so_number:'',
            orderLine: {},
            orders: {},
            listGiao:[],
            order: {},
            customer: {},
            checkedCategories: '',
            resource: {},
            khuvucSelected:[],
            resou:[],
            p: '',
            sum: [],
            checkAdmin: [],
            formDelivery: new Form({
                choiceproduct: '',
                selectorder: '',
                soluong: '',
                soluongold: '',
                deliverydate: '',
                all: '',
            }),
            formU: new Form({
                choiceorder: '',
                sp: [],
                status: [],
                id:'',
            }),
            form: new Form({
                id: '',
                delivery_date: '',
            }),
            id:'',
            showroomsSelected: [],
            costcenters: [],
            products: [],
            selectorders: [],
            saleSelected: [],
            resources: [],
            sale_ids: [],
            totals:[],
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
            this.getResults();
        },
        checkdate(){
        },
        reSelectArea() {
            this.$parent.khuvucSelected = [];
            this.getResults();
        },
        reSelectSanPham() {
            this.p = [];
            this.getResults();
        },
        reSelectShowroom() {
            this.$parent.showroomsSelected = '';
            this.sale_ids = [];
            this.getResults();
        },
        reSelectSale() {
            this.$parent.saleSelected = [];
            this.sale_ids = [];
            this.getResults();
        },

        handleFileUploadOut() {
            this.fileo = this.$refs.fileo.files[0];
        },
        // Our method to GET results from a Laravel endpoint
        getResults(page = 1) {
            this.khuvuc = this.$parent.khuvucSelected.map(kvuc =>{
                return kvuc.id
            });
            let check = this.checkedCategories;
            if(check) {
                var giao = 1;
            }else{
                var giao = 0;
            }
            console.log(giao);
            let dates_para = queryString.stringify({ sdates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10), giao ] }, { arrayFormat: 'bracket' });
            let queryName = queryString.stringify({ name: this.$parent.search });
            let uri = queryString.stringify({ users: this.sale_ids }, { arrayFormat: 'bracket' });
            let test = queryString.stringify({ khuvucs: this.khuvuc }, { arrayFormat: 'bracket' });
            let products = queryString.stringify({ p: this.p });
            axios.get("/api/orders?page=" + page + '&' + products + '&' + uri + '&' + test + '&' + dates_para + '&' + queryName)
                .then(response => {
                    this.orders = response.data;
                    console.log(response.data);
                    this.totals =  response.data.meta;
                });
            axios.get("/api/infosum?page=" + page + '&' + products + '&' + uri + '&' + test + '&' + dates_para + '&' + queryName)
                .then(response => {
                    this.sum = response.data;
                });
                console.log(this.costcenters);
        },
        onNgayTao: function(daterange) {
            this.chonNgayTao = daterange
            axios.post('/api/searchorderat2', {
                    ngaydat: this.chonNgayTao.start,
                    ngaydat2: this.chonNgayTao.end
                })
                .then(response => {
                    this.orders = response.data;
                });
        },
        onNgayGiao: function(daterange) {
            this.chonNgayGiao = daterange
            axios.post('/api/searchorderat', {
                    ngaygiao: this.chonNgayGiao.start,
                    ngaygiao2: this.chonNgayGiao.end
                })
                .then(response => {
                    this.orders = response.data;
                });
        },

        upFolder(){
            let formData = new FormData();
            formData.append('file', this.fileo);
            formData.append('so_number', this.so_number);
            axios.post('/api/addthietke',formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
            .then(() => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Thêm Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                this.getResults();
                $('#out').modal('hide');

            })
            .catch(error => {
                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: error.response.data.message,
                    footer: '<a href>Why do I have this issue?</a>'
                })
            });
        },

        updateOrder() {
            this.$Progress.start();
            axios.get('api/updateDate?id=' + this.form.id + '&date='+ this.form.delivery_date).then((result) => {
                $('#orderModalCenter').modal('hide');
                swal.fire(
                    'Updated!',
                    'Cập Nhật Thành Công',
                    'success'
                )
                this.$Progress.finish();
                Fire.$emit('AfterCreate');
            })
            .catch(() => {
                this.$Progress.fail();
            });
        },
       
        updateOr(){
            this.$Progress.start();
            console.log(this.formU.sp);
            this.sp_code = this.formU.sp.map(sp => {
                return sp.id
            });
            axios.get('api/updateorder?sp='+ this.sp_code + '&order='+ this.formU.choiceorder.id + '&id='+ this.formU.id + '&status='+ this.formU.status).then((result) => {
                $('#orderDelivery').modal('hide');
                swal.fire(
                    'Updated!',
                    'Cập Nhật Thành Công',
                    'success'
                )
                this.$Progress.finish();
                Fire.$emit('AfterCreate');
            })
            .catch(() => {
                this.$Progress.fail();
            });
            
        },

        updatedelivery(){
            this.$Progress.start();
            console.log(this.formDelivery.all);
            if (this.formDelivery.all) {
                axios.get('api/updateall?date='+ this.formDelivery.deliverydate + '&order='+ this.formDelivery.selectorder.so_number + '&idorder='+ this.formDelivery.selectorder.id).then((result) => {
                    $('#orderDelivery').modal('hide');
                    swal.fire(
                        'Updated!',
                        'Cập Nhật Thành Công',
                        'success'
                    )
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            }else{
                axios.get('api/updateDelivery?soluong=' + this.formDelivery.soluong + '&soluongold=' + this.formDelivery.soluongold +'&date='+ this.formDelivery.deliverydate + '&order='+ this.formDelivery.selectorder.so_number + '&idorder='+ this.formDelivery.selectorder.id + '&item='+ this.formDelivery.choiceproduct.item + '&productid='+ this.formDelivery.choiceproduct.product_id + '&discount='+ this.formDelivery.choiceproduct.discount + '&price='+ this.formDelivery.choiceproduct.price).then((result) => {
                    $('#orderDelivery').modal('hide');
                    swal.fire(
                        'Updated!',
                        'Cập Nhật Thành Công',
                        'success'
                    )
                    this.$Progress.finish();
                    Fire.$emit('AfterCreate');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
            }
        },

        showOrder(order) {
            this.order = order;
            this.orderLine = order.orderLine;
            $('#orderLineModal').modal('show');
            $('#print').attr("href", '/orders/print/' + order.id);
            $('#downloadExcel').attr("href", '/orders/pdf/' + order.id);
        },

        editModal(order) {
            $('#orderModalCenter').modal('show');
            this.form.fill(order);
        },

        pushOrder(order){
            this.so_number = order.so_number;
            $('#out').modal('show');
        },
        edittrangthai(order) {
            $('#orderModaltrangthai').modal('show');
            this.form.fill(order);
        },

        updateDelivery(){
            $('#orderDelivery').modal('show');
        },

        updateO(order){
            this.formU.id = order.id;
            // console.log(order.status);
            if(order.status){
                this.note = 'Đã có cập nhật';
            }else{
                this.note = '';
            }
            $('#orderO').modal('show');
        },

        timTheoO(){
            let order = this.formU.choiceorder.id;
            let uri = 'api/find-products-order?id=' + order;
            axios.get(uri)
            .then(response => {
                this.formU.choiceproduct = [];
                this.products = [];
                this.products = response.data;
            });
        },

        timTheoOrder(){
            let order = this.formDelivery.selectorder.id;
            let uri = 'api/find-products-order?id=' + order;
            let url = 'api/show-products-order?id=' + order;
            axios.get(uri)
            .then(response => {
                this.formDelivery.choiceproduct = '';
                this.formDelivery.soluong = '';
                this.formDelivery.deliverydate = '';
                this.products = [];
                this.products = response.data;
            });
            axios.get(url)
            .then(response => {
                this.listGiao = response.data;
            });
        },

        findSoluong(){
            this.formDelivery.soluong = this.formDelivery.choiceproduct.quantity;
            this.formDelivery.deliverydate = this.formDelivery.choiceproduct.delivery;
                
        },

        getResource() {
            axios.get("api/resources/" + this.order.user_id)
                .then(({ data }) => (this.resource = data));
        },

        getCustomer() {
            axios.get('api/customers/' + this.order.deliver_to)
                .then(response => {
                    this.customer = response.data;
                });
        },

        duyetDonHang(id) {
            axios.get('api/orders/status/' + id)
                .then(response => {
                    this.getResults();
                    $('#orderLineModal').modal('hide');
                });
        },

        deleteOrder(id) {
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
                    axios.delete('api/orders/' + id)
                        .then(() => {
                            swal.fire(
                                'Deleted!',
                                'Đơn hàng sẽ bị xóa.',
                                'success'
                            );
                            this.getResults();
                        })
                        .catch(() => {
                            swal.fire("Failed!", "There was something wronge.", "warning");
                        });
                }
            })
        },

        donhangmoi() {
            axios.get("/cart/clear")
                .then(response => {
                    //location.replace("/tao-don-hang");
                    swal.fire({
                        title: '<strong>Thông <u>báo</u></strong>',
                        type: 'info',
                        html: 'Bạn cần tạo <b>khách hàng</b>, ' +
                            '<a href="/them-khach-tiem-nang">tại đây</a> ' +
                            'và chuyển thành đơn hàng',
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                        confirmButtonText: '<i class="fa fa-thumbs-up"></i> Đồng ý!',
                        confirmButtonAriaLabel: 'Thumbs up, great!',
                        cancelButtonText: '<i class="fa fa-thumbs-down"></i>',
                        cancelButtonAriaLabel: 'Quên rồi',
                    })
                })
        },

        editOrder(so_number) {
            var _this = this;
            axios.post("cart/load", {
                    so: so_number
                })
                .then(response => {
                    location.replace("/tao-don-hang")
                })
                .catch(response => {
                    swal("Failed!", 'Đơn hàng đã duyệt', "warning");
                })
        },
        // Tìm kiếm
        // updateValues(values) {
        //     this.startDate = values.startDate.toISOString().slice(0, 10)
        //     this.endDate = values.endDate.toISOString().slice(0, 10)
        //     this.getResults();
        // },
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            // this.startDate = moment(this.startDate).add(1, 'day').format().slice(0,10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            // this.endDate = moment(this.endDate).add(1, 'day').format().slice(0,10);
            // this.addWeek();
        },
        addWeek() {
            this.p = [];
            var date = moment(this.endDate).endOf('week').format().slice(0, 10);
            this.p.push(date);
            this.p.push(moment(date).subtract(1, 'week').format().slice(0, 10));
            this.p.push(moment(date).subtract(2, 'week').format().slice(0, 10));
            this.p.push(moment(date).subtract(3, 'week').format().slice(0, 10));

        },
        onChange(event) {
            // console.log(event.target.value)
        },
        timTheoShowroom() {
            // console.log(this.showroomsSelected);
            // if (!is_object(this.showroomsSelected)) {
                let costcenter_ids = this.showroomsSelected.map((costcenter, index, showroomsSelected) => {
                        return costcenter.id;
                    });
                // }
            // else{
            //     let costcenter_ids = this.showroomsSelected.array_map((costcenter, index, showroomsSelected) => {
            //         return costcenter.id;
            //     });
            // }

            // console.log(costcenter_ids);
            
            let uri = 'api/find-user-by-costcenter?' + queryString.stringify({ costcenters: costcenter_ids }, { arrayFormat: 'bracket' });
            axios.get(uri)
                .then(response => {
                    this.saleSelected = [];
                    this.resources = response.data;
                    this.sale_ids = response.data.map((sale, index, saleSelected) => {
                        return sale.id;
                    });
                    this.getResults();
                });
        },
        timTheoSale() {
            this.sale_ids = this.saleSelected.map((sale, index, saleSelected) => {
                return sale.id
            });
        },
        timTheokhuvuc(){
        },
    },
    created() {
        this.getResults();
        Fire.$on('searching', () => {
            this.getResults();
        })
        Fire.$on('AfterCreate', () => {
            this.getResults();
        });
        axios.get('/api/products/').then(res => this.product = res.data);
        axios.get('/api/products-list/').then(res => this.products = res.data);
        axios.get('/api/picklists/costcenter-picklists')
            .then(res => this.costcenters = res.data);
        axios.get('/api/picklists/users')
            .then(res => this.resources = res.data); 
        axios.get('/api/picklists/areasPicklists')
            .then(res => this.resou = res.data);
        axios.get('/api/listOrders')
            .then(res => this.selectorders = res.data);
        axios.get('/api/checkAdmin')
            .then(res => this.checkAdmin = res.data);
    }
}

</script>
