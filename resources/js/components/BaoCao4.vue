<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-4">
                <h1 class="m-0 text-dark">Khu Vực Target</h1>
            </div><!-- /.col -->
            <div class="col-sm-4">
                <date-range-picker class="pull-right mt-2" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges" :alwaysShowCalendars="false">
                    <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                </date-range-picker>
            </div><!-- /.col -->
            <div class="col-md-2">
                <div class="form-group">
                    <label></label>
                    <a href="#" @click="searchdata" class="btn btn-success">Tìm Kiếm</i></a>
                </div>
            </div>
        </div>
        <ul class="nav nav-pills" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#khtn" @click="updateByKHTN">KHTN</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#kh15" @click="updateByKH15">KH15</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#dh" @click="updateByDH">Đơn Hàng</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#ds" @click="updateByDS">Doanh Số</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#dsnew" @click="updateByDSnew">Doanh Số New</a>
            </li>
        </ul>
        <div class="row" id="chung">
            <div class="tableFixHead table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="max-width:100px;">Showroom</th>
                            <th>P</th>
                            <th @click="detailTarget(0)">T1</th>
                            <th @click="detailTarget(1)">T2</th>
                            <th @click="detailTarget(2)">T3</th>
                            <th @click="detailTarget(3)">T4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(sale, index) in sales">
                            <tr class="table-info">
                                <td style="max-width:60px;">{{sale.warehouse}}</td>
                                <td style=" max-width: 80px;">
                                    <a href="#" class="modal-open" @click="showtotal(sale.warehouse)">
                                        <span v-bind:class="{ 'bg-danger': (sale.sumc >= sale.sumt ), 'bg-new' : (sale.sumc < sale.sumt) }">
                                            {{ sale.sumc }}
                                        </span>
                                    </a>
                                    <span class=" pull-right">{{ sale.sumt }}</span><br/>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.sumc / sale.sumt) * 100 + '%'}">
                                            {{ (formatPrice((sale.sumc / sale.sumt) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ sale.sumdd1 }}</span>
                                    <span class="border-left">{{ sale.sumdc1 }}</span>

                                    <span class="border-left pull-right">{{ sale.t1 }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c1 >= sale.t1 ), 'bg-new' : (sale.c1 < sale.t1) }">{{ sale.c1 }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.c1 / sale.t1) * 100 + '%'}">
                                            {{ (formatPrice((sale.c1 / sale.t1) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ sale.sumdd2 }}</span>
                                    <span class="border-left">{{ sale.sumdc2 }}</span>

                                    <span class="border-left pull-right">{{ sale.t2 }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c2 >= sale.t2 ), 'bg-new' : (sale.c2 < sale.t2) }">{{ sale.c2 }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.c2 / sale.t2) * 100 + '%'}">
                                            {{ (formatPrice((sale.c2 / sale.t2) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ sale.sumdd3 }}</span>
                                    <span class="border-left">{{ sale.sumdc3 }}</span>

                                    <span class="border-left pull-right">{{ sale.t3 }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c3 >= sale.t3 ), 'bg-new' : (sale.c3 < sale.t3) }">{{ sale.c3 }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.c3 / sale.t3) * 100 + '%'}">
                                            {{ (formatPrice((sale.c3 / sale.t3) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ sale.sumdd4 }}</span>
                                    <span class="border-left">{{ sale.sumdc4 }}</span>

                                    <span class="border-left pull-right">{{ sale.t4 }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c4 >= sale.t4 ), 'bg-new' : (sale.c4 < sale.t4) }">{{ sale.c4 }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark"  v-bind:style="{width: (sale.c4 / sale.t4) * 100 + '%'}">
                                            {{ (formatPrice((sale.c4 / sale.t4) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr  v-for="(month, key) in sale.costcenter" v-if="month.sumc > 0"> <!-- v-if="month.sumc > 0" -->
                                <!-- <td v-if="key == 0" :rowspan="sale.costcenter.length" style="max-width:60px;">{{sale.warehouse}}</td> -->
                                <td style="max-width:100px;">{{month.costcenter}}</td>
                                <td style=" max-width: 80px;">
                                    <a href="#" class="modal-open" @click="show(month)">
                                        <span v-bind:class="{ 'bg-danger': (sale.sumc >= sale.sumt ), 'bg-new' : (sale.sumc < sale.sumt) }">
                                            {{ month.sumc }}
                                        </span>
                                    </a>
                                    <span class="pull-right ">{{ month.sumt }}</span><br/>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.sumc / month.sumt) * 100 + '%'}">
                                            {{ (formatPrice((month.sumc / month.sumt) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ month.sumdd1 }}</span> 
                                    <span class="border-left">{{ month.sumdc1 }}</span>

                                    <span class="border-left pull-right">{{ month.t1 }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c1 >= month.t1) , 'bg-new' : (month.c1 < month.t1)}">{{ month.c1 }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.c1 / month.t1) * 100 + '%'}">
                                            {{ (formatPrice((month.c1 / month.t1) * 100)) + '%'}}
                                        </div>
                                    </div>
                                            
                                </td>
                                <td>
                                    <span class="bg-new">{{ month.sumdd2 }}</span> 
                                    <span class="border-left">{{ month.sumdc2 }}</span>

                                    <span class="border-left pull-right">{{ month.t2 }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c2 >= month.t2) , 'bg-new' : (month.c2 < month.t2)}">{{ month.c2 }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.c2 / month.t2) * 100 + '%'}">
                                            {{ (formatPrice((month.c2 / month.t2) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ month.sumdd3 }}</span> 
                                    <span class="border-left">{{ month.sumdc3 }}</span>

                                    <span class="border-left pull-right">{{ month.t3 }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c3 >= month.t3) , 'bg-new' : (month.c3 < month.t3)}">{{ month.c3 }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.c3 / month.t3) * 100 + '%'}">
                                            {{ (formatPrice((month.c3 / month.t3) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ month.sumdd4 }}</span> 
                                    <span class="border-left">{{ month.sumdc4 }}</span>

                                    <span class="border-left pull-right">{{ month.t4 }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c4 >= month.t4) , 'bg-new' : (month.c4 < month.t4)}">{{ month.c4 }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark"  v-bind:style="{width: (month.c4 / month.t4) * 100 + '%'}">
                                            {{ (formatPrice((month.c4 / month.t4) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- start detail target popup -->
        <div class="modal  fade" id="DetailCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                          <div class="form-row">
                            <div class="form-group col-md-12 ">
                                <div class="topmodel">
                                    <span v-if="value==0">Tuần 1/{{checkP}}</span>
                                    <span v-if="value==1">Tuần 2/{{checkP}}</span>
                                    <span v-if="value==2">Tuần 3/{{checkP}}</span>
                                    <span v-if="value==3">Tuần 4/{{checkP}}</span>
                                </div>
                                <table class="table table-bordered popup">
                                    <thead>
                                        <tr>
                                        <th>Showroom</th>
                                        <th>Thứ 2 / {{formatPrice(tong.t2)}}</th>
                                        <th>Thứ 3 / {{formatPrice(tong.t3)}}</th>
                                        <th>Thứ 4 / {{formatPrice(tong.t4)}}</th>
                                        <th>Thứ 5 / {{formatPrice(tong.t5)}}</th>
                                        <th>Thứ 6 / {{formatPrice(tong.t6)}}</th>
                                        <th>Thứ 7 / {{formatPrice(tong.t7)}}</th>
                                        <th>Chủ Nhật / {{formatPrice(tong.t8)}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <!-- <td v-for='showroom in sales.warehouse'>{{showroom}}</td> -->
                                        <tr class="text-center" v-for='(data,index) in detaildata'>
                                            <td >{{index}}</td>
                                            <td >{{data[0]<=9000 ? (data[0]==0 ? '-' : data[0])  : formatPrice(data[0])}} </td>
                                            <td >{{data[1]<=9000 ? (data[1]==0 ? '-' : data[1])  : formatPrice(data[1])}} </td>
                                            <td >{{data[2]<=9000 ? (data[2]==0 ? '-' : data[2])  : formatPrice(data[2])}} </td>
                                            <td >{{data[3]<=9000 ? (data[3]==0 ? '-' : data[3])  : formatPrice(data[3])}} </td>
                                            <td >{{data[4]<=9000 ? (data[4]==0 ? '-' : data[4])  : formatPrice(data[4])}} </td>
                                            <td >{{data[5]<=9000 ? (data[5]==0 ? '-' : data[5])  : formatPrice(data[5])}} </td>
                                            <td >{{data[6]<=9000 ? (data[6]==0 ? '-' : data[6])  : formatPrice(data[6])}} </td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                          </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end detail target popup -->
        <div class="row" id="amount" style="display:none;">
            <div class="tableFixHead table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="max-width:100px;">Showroom</th>
                            <th>P</th>
                            <th @click="detailTarget(0)">T1</th>
                            <th @click="detailTarget(1)">T2</th>
                            <th @click="detailTarget(2)">T3</th>
                            <th @click="detailTarget(3)">T4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(sale, index) in sales" v-if="index < 4">
                            <tr class="table-info">
                                <td style="max-width:60px;">{{ sale.warehouse }}</td>
                                <td style=" max-width: 80px;">
                                    <span v-bind:class="{ 'bg-danger': (sale.sumc >= sale.sumt ), 'bg-new' : (sale.sumc < sale.sumt) }">{{ formatPrice(sale.sumc) }}</span>
                                    <span class=" pull-right ">{{ formatPrice(sale.sumt) }}</span><br/>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.sumc / sale.sumt) * 100 + '%'}">
                                            {{ (formatdiv((sale.sumc / sale.sumt) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(sale.sumdd1) }}</span>
                                    <span class="border-left">{{ formatPrice(sale.sumdc1) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(sale.t1) }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c1 >= sale.t1 ), 'bg-new' : (sale.c1 < sale.t1) }">{{ formatPrice(sale.c1) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.c1 / sale.t1) * 100 + '%'}">
                                            {{ (formatdiv((sale.c1 / sale.t1) * 100)) + '%'}}
                                        </div>
                                    </div>
                                    <span class="border-left" v-if="index > 4 && sale.asmdon1 > 0 || sale.huydon1 != 0 && sale.cold1 > 0">{{ formatPrice(sale.cold1) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.asmdon1 > 0">A: {{ formatPrice(sale.asmdon1) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.huydon1 != 0">H: {{ formatPriceDon(sale.huydon1) }}&nbsp;&nbsp;</span>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(sale.sumdd2) }}</span>
                                    <span class="border-left">{{ formatPrice(sale.sumdc2) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(sale.t2) }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c2 >= sale.t2 ), 'bg-new' : (sale.c2 < sale.t2) }">{{ formatPrice(sale.c2) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.c2 / sale.t2) * 100 + '%'}">
                                            {{ (formatdiv((sale.c2 / sale.t2) * 100)) + '%'}}
                                        </div>
                                    </div>
                                    <span class="border-left" v-if="index > 4 && sale.asmdon2 > 0 || sale.huydon2 != 0 && sale.cold2 > 0">{{ formatPrice(sale.cold2) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.asmdon2 > 0">A: {{ formatPrice(sale.asmdon2) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.huydon2 != 0">H: {{ formatPriceDon(sale.huydon2) }}&nbsp;&nbsp;</span>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(sale.sumdd3) }}</span>
                                    <span class="border-left">{{ formatPrice(sale.sumdc3) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(sale.t3) }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c3 >= sale.t3 ), 'bg-new' : (sale.c3 < sale.t3) }">{{ formatPrice(sale.c3) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.c3 / sale.t3) * 100 + '%'}">
                                            {{ (formatdiv((sale.c3 / sale.t3) * 100)) + '%'}}
                                        </div>
                                    </div>
                                    <span class="border-left" v-if="index > 4 && sale.asmdon3 > 0 || sale.huydon3 != 0 && sale.cold3 > 0">{{ formatPrice(sale.cold3) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.asmdon3 > 0">A: {{ formatPrice(sale.asmdon3) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.huydon3 != 0">H: {{ formatPriceDon(sale.huydon3) }}&nbsp;&nbsp;</span>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(sale.sumdd4) }}</span>
                                    <span class="border-left">{{ formatPrice(sale.sumdc4) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(sale.t4) }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c4 >= sale.t4 ), 'bg-new' : (sale.c4 < sale.t4) }">{{ formatPrice(sale.c4) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark"  v-bind:style="{width: (sale.c4 / sale.t4) * 100 + '%'}">
                                            {{ (formatdiv((sale.c4 / sale.t4) * 100)) + '%'}}
                                        </div>
                                    </div>
                                    <span class="border-left" v-if="index > 4 && sale.asmdon4 > 0 || sale.huydon4 != 0 && sale.cold4 > 0">{{ formatPrice(sale.cold4) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.asmdon4 > 0">A: {{ formatPrice(sale.asmdon4) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.huydon4 != 0">H: {{ formatPriceDon(sale.huydon4) }}&nbsp;&nbsp;</span>
                                </td>
                            </tr>
                            <tr  v-for="(month, key) in sale.costcenter" v-if="month.sumt > 0">
                                <!-- <td v-if="key == 0" :rowspan="sale.costcenter.length" style="max-width:60px;">{{sale.warehouse}}</td> -->
                                <td style="max-width:100px;">{{month.costcenter}}</td>
                                <td style=" max-width: 80px;">

                                    <span v-bind:class="{ 'bg-danger': (sale.sumc >= sale.sumt ), 'bg-new' : (sale.sumc < sale.sumt) }">{{ formatPrice(month.sumc) }}</span>
                                    <span class="pull-right ">{{ formatPrice(month.sumt) }}</span><br/>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.sumc / month.sumt) * 100 + '%'}">
                                            {{ (formatdiv((month.sumc / month.sumt) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(month.sumdd1) }}</span> 
                                    <span class="border-left">{{ formatPrice(month.sumdc1) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(month.t1) }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c1 >= month.t1) , 'bg-new' : (month.c1 < month.t1)}">{{ formatPrice(month.c1) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.c1 / month.t1) * 100 + '%'}">
                                            {{ (formatdiv((month.c1 / month.t1) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(month.sumdd2) }}</span> 
                                    <span class="border-left">{{ formatPrice(month.sumdc2) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(month.t2) }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c2 >= month.t2) , 'bg-new' : (month.c2 < month.t2)}">{{ formatPrice(month.c2) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.c2 / month.t2) * 100 + '%'}">
                                            {{ (formatdiv((month.c2 / month.t2) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(month.sumdd3) }}</span> 
                                    <span class="border-left">{{ formatPrice(month.sumdc3) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(month.t3) }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c3 >= month.t3) , 'bg-new' : (month.c3 < month.t3)}">{{ formatPrice(month.c3) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.c3 / month.t3) * 100 + '%'}">
                                            {{ (formatdiv((month.c3 / month.t3) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(month.sumdd4) }}</span> 
                                    <span class="border-left">{{ formatPrice(month.sumdc4) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(month.t4) }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c4 >= month.t4) , 'bg-new' : (month.c4 < month.t4)}">{{ formatPrice(month.c4) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark"  v-bind:style="{width: (month.c4 / month.t4) * 100 + '%'}">
                                            {{ (formatdiv((month.c4 / month.t4) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template v-for="(sale, index) in sales" v-if="sale.sumt > 0 && index > 3">
                            <tr class="table-info">
                                <td style="max-width:60px;">{{ sale.warehouse }}</td>
                                <td style=" max-width: 80px;">
                                    <span v-bind:class="{ 'bg-danger': (sale.sumc >= sale.sumt ), 'bg-new' : (sale.sumc < sale.sumt) }">{{ formatPrice(sale.sumc) }}</span>
                                    <span class=" pull-right ">{{ formatPrice(sale.sumt) }}</span><br/>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.sumc / sale.sumt) * 100 + '%'}">
                                            {{ (formatdiv((sale.sumc / sale.sumt) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(sale.sumdd1) }}</span>
                                    <span class="border-left">{{ formatPrice(sale.sumdc1) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(sale.t1) }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c1 >= sale.t1 ), 'bg-new' : (sale.c1 < sale.t1) }">{{ formatPrice(sale.c1) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.c1 / sale.t1) * 100 + '%'}">
                                            {{ (formatdiv((sale.c1 / sale.t1) * 100)) + '%'}}
                                        </div>
                                    </div>
                                    <span class="border-left" v-if="index > 4 && sale.asmdon1 > 0 || sale.huydon1 != 0 && sale.cold1 > 0">{{ formatPrice(sale.cold1) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.asmdon1 > 0">A: {{ formatPrice(sale.asmdon1) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.huydon1 != 0">H: {{ formatPriceDon(sale.huydon1) }}&nbsp;&nbsp;</span>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(sale.sumdd2) }}</span>
                                    <span class="border-left">{{ formatPrice(sale.sumdc2) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(sale.t2) }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c2 >= sale.t2 ), 'bg-new' : (sale.c2 < sale.t2) }">{{ formatPrice(sale.c2) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.c2 / sale.t2) * 100 + '%'}">
                                            {{ (formatdiv((sale.c2 / sale.t2) * 100)) + '%'}}
                                        </div>
                                    </div>
                                    <span class="border-left" v-if="index > 4 && sale.asmdon2 > 0 || sale.huydon2 != 0 && sale.cold2 > 0">{{ formatPrice(sale.cold2) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.asmdon2 > 0">A: {{ formatPrice(sale.asmdon2) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.huydon2 != 0">H: {{ formatPriceDon(sale.huydon2) }}&nbsp;&nbsp;</span>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(sale.sumdd3) }}</span>
                                    <span class="border-left">{{ formatPrice(sale.sumdc3) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(sale.t3) }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c3 >= sale.t3 ), 'bg-new' : (sale.c3 < sale.t3) }">{{ formatPrice(sale.c3) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (sale.c3 / sale.t3) * 100 + '%'}">
                                            {{ (formatdiv((sale.c3 / sale.t3) * 100)) + '%'}}
                                        </div>
                                    </div>
                                    <span class="border-left" v-if="index > 4 && sale.asmdon3 > 0 || sale.huydon3 != 0 && sale.cold3 > 0">{{ formatPrice(sale.cold3) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.asmdon3 > 0">A: {{ formatPrice(sale.asmdon3) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.huydon3 != 0">H: {{ formatPriceDon(sale.huydon3) }}&nbsp;&nbsp;</span>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(sale.sumdd4) }}</span>
                                    <span class="border-left">{{ formatPrice(sale.sumdc4) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(sale.t4) }}</span>
                                    <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c4 >= sale.t4 ), 'bg-new' : (sale.c4 < sale.t4) }">{{ formatPrice(sale.c4) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark"  v-bind:style="{width: (sale.c4 / sale.t4) * 100 + '%'}">
                                            {{ (formatdiv((sale.c4 / sale.t4) * 100)) + '%'}}
                                        </div>
                                    </div>
                                    <span class="border-left" v-if="index > 4 && sale.asmdon4 > 0 || sale.huydon4 != 0 && sale.cold4 > 0">{{ formatPrice(sale.cold4) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.asmdon4 > 0">A: {{ formatPrice(sale.asmdon4) }}</span>
                                    <span class="border-left pull-right" v-if="index > 4 && sale.huydon4 != 0">H: {{ formatPriceDon(sale.huydon4) }}&nbsp;&nbsp;</span>
                                </td>
                            </tr>
                            <tr  v-for="(month, key) in sale.costcenter" v-if="month.sumt > 0">
                                <!-- <td v-if="key == 0" :rowspan="sale.costcenter.length" style="max-width:60px;">{{sale.warehouse}}</td> -->
                                <td style="max-width:100px;">{{month.costcenter}}</td>
                                <td style=" max-width: 80px;">

                                    <span v-bind:class="{ 'bg-danger': (sale.sumc >= sale.sumt ), 'bg-new' : (sale.sumc < sale.sumt) }">{{ formatPrice(month.sumc) }}</span>
                                    <span class="pull-right ">{{ formatPrice(month.sumt) }}</span><br/>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.sumc / month.sumt) * 100 + '%'}">
                                            {{ (formatdiv((month.sumc / month.sumt) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(month.sumdd1) }}</span> 
                                    <span class="border-left">{{ formatPrice(month.sumdc1) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(month.t1) }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c1 >= month.t1) , 'bg-new' : (month.c1 < month.t1)}">{{ formatPrice(month.c1) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.c1 / month.t1) * 100 + '%'}">
                                            {{ (formatdiv((month.c1 / month.t1) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(month.sumdd2) }}</span> 
                                    <span class="border-left">{{ formatPrice(month.sumdc2) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(month.t2) }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c2 >= month.t2) , 'bg-new' : (month.c2 < month.t2)}">{{ formatPrice(month.c2) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.c2 / month.t2) * 100 + '%'}">
                                            {{ (formatdiv((month.c2 / month.t2) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(month.sumdd3) }}</span> 
                                    <span class="border-left">{{ formatPrice(month.sumdc3) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(month.t3) }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c3 >= month.t3) , 'bg-new' : (month.c3 < month.t3)}">{{ formatPrice(month.c3) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark" v-bind:style="{width: (month.c3 / month.t3) * 100 + '%'}">
                                            {{ (formatdiv((month.c3 / month.t3) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="bg-new">{{ formatPrice(month.sumdd4) }}</span> 
                                    <span class="border-left">{{ formatPrice(month.sumdc4) }}</span>

                                    <span class="border-left pull-right">{{ formatPrice(month.t4) }}</span>
                                    <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (month.c4 >= month.t4) , 'bg-new' : (month.c4 < month.t4)}">{{ formatPrice(month.c4) }}</span>
                                    <div class="progress progress-xs">
                                        <div class="text-dark"  v-bind:style="{width: (month.c4 / month.t4) * 100 + '%'}">
                                            {{ (formatdiv((month.c4 / month.t4) * 100)) + '%'}}
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row" id="amountnew" style="display:none;">
            <div class="tableFixHead table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th style="max-width:100px;">Showroom</th>
                            <th>P</th>
                            <th @click="detailTarget(0)">T1</th>
                            <th @click="detailTarget(1)">T2</th>
                            <th @click="detailTarget(2)">T3</th>
                            <th @click="detailTarget(3)">T4</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(sale, index) in doanhsodata" v-if="Object.keys(sale).length > 0">
                            <tr v-bind:class="{ 'table-info': (sale[0]['type'] == 1) }">
                                <td style="max-width:60px;">{{ index }}</td>
                                <td style=" max-width: 80px;">
                                    <div>
                                        <span>{{ formatPrice(sale[0]['tong']) | myNumber}}</span>
                                        <span class=" pull-right"> {{ formatPrice(sale[0]['targettong']) | myNumber}}</span><br/>
                                    </div>
                                    <div>
                                        <span class="style-color">{{ formatPrice(sale[0]['ds']) | myNumber}} / {{ formatPrice(sale[0]['chia']) | myNumber}}</span>
                                        <span class="style-color pull-right" v-if="sale[0]['typetarget'] == 1"> {{formatPrice(sale[0]['target']) | myNumber}} / {{formatPrice(sale[0]['target'] - sale[0]['targettong']) | myNumber}}  </span><br/>
                                    </div>
                                </td>
                                <td style=" max-width: 80px;">
                                    <div>
                                        <span>{{ formatPrice(sale[1]['tong']) | myNumber}}</span>
                                        <span class=" pull-right"> 
                                            <a href="#" @click="updateTarget(1,index)">
                                                {{ formatPrice(sale[1]['targettong']) | myNumber}}
                                            </a>
                                        </span><br/>
                                    </div>
                                    <div>
                                        <span class="style-color">{{ formatPrice(sale[1]['ds']) | myNumber}} / {{ formatPrice(sale[1]['chia']) | myNumber}}</span>
                                        <span class="style-color pull-right" v-if="sale[0]['typetarget'] == 1"> {{formatPrice(sale[1]['target']) | myNumber}} / {{formatPrice(sale[1]['targettong'] - sale[1]['target']) | myNumber}}  </span><br/>
                                    </div>
                                </td>
                                <td style=" max-width: 80px;">
                                    <div>
                                        <span>{{ formatPrice(sale[2]['tong']) | myNumber}}</span>
                                        <span class=" pull-right"> 
                                            <a href="#" @click="updateTarget(2,index)">
                                                {{ formatPrice(sale[2]['targettong']) | myNumber}}
                                            </a>
                                        </span><br/>
                                    </div>
                                    <div>
                                        <span class="style-color">{{ formatPrice(sale[2]['ds']) | myNumber}} / {{ formatPrice(sale[2]['chia']) | myNumber}}</span>
                                        <span class="style-color pull-right" v-if="sale[0]['typetarget'] == 1"> {{formatPrice(sale[2]['target']) | myNumber}} / {{formatPrice(sale[2]['targettong'] - sale[2]['target']) | myNumber}}  </span><br/>
                                    </div>
                                </td>
                                <td style=" max-width: 80px;">
                                    <div>
                                        <span>{{ formatPrice(sale[3]['tong']) | myNumber}}</span>
                                        <span class=" pull-right"> 
                                            <a href="#" @click="updateTarget(3,index)">
                                                {{ formatPrice(sale[3]['targettong']) | myNumber}}
                                            </a>
                                        </span><br/>
                                    </div>
                                    <div>
                                        <span class="style-color">{{ formatPrice(sale[3]['ds']) | myNumber}} / {{ formatPrice(sale[3]['chia']) | myNumber}}</span>
                                        <span class="style-color pull-right" v-if="sale[0]['typetarget'] == 1"> {{formatPrice(sale[3]['target']) | myNumber}} / {{formatPrice(sale[3]['targettong'] - sale[3]['target']) | myNumber}}  </span><br/>
                                    </div>
                                </td>
                                <td style=" max-width: 80px;">
                                    <div>
                                        <span>{{ formatPrice(sale[4]['tong']) | myNumber}}</span>
                                        <span class=" pull-right"> 
                                            <a href="#" @click="updateTarget(4,index)">
                                                {{ formatPrice(sale[4]['targettong']) | myNumber}}
                                            </a>
                                        </span><br/>
                                    </div>
                                    <div>
                                        <span class="style-color">{{ formatPrice(sale[4]['ds']) | myNumber}} / {{ formatPrice(sale[4]['chia']) | myNumber}}</span>
                                        <span class="style-color pull-right" v-if="sale[0]['typetarget'] == 1"> {{formatPrice(sale[4]['target']) | myNumber}} / {{formatPrice(sale[4]['targettong'] - sale[4]['target']) | myNumber}}  </span><br/>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="modal fade" id="modelProducts" tabindex="-1" role="dialog" aria-labelledby="taskModalDetailTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalDetailTitle">Chi Tiết Sản Phẩm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-dark table-bordered">
                            <thead>
                                <td>P/T</td>
                                <td>Daliah</td>
                                <td>Ivy</td>
                                <td>Iris</td>
                                <td>Khác</td>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 10px;">P</td>
                                    <td style="width: 120px;">
                                        <span>{{sp.daliap}}</span>
                                        <span class="pull-right">{{sp.targetda}}</span>
                                        <p>{{Math.round(sp.daliap / sp.targetda *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivyp}}</span>
                                        <span class="pull-right">{{sp.targetiv}}</span>
                                        <p>{{Math.round(sp.ivyp / sp.targetiv *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.irisp}}</span>
                                        <span class="pull-right">{{sp.targetir}}</span>
                                        <p>{{Math.round(sp.irisp / sp.targetir *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.khacp}}</span>
                                        <span class="pull-right">{{sp.targetk}}</span>
                                        <p>{{Math.round(sp.khacp / sp.targetk *100)}}%</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>T1</td>
                                    <td style="width: 120px;">
                                        <span>{{sp.daliat1}}</span>
                                        <span class="pull-right">{{sp.targetda1}}</span>
                                        <p>{{Math.round(sp.daliat1 / sp.targetda1 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivt1}}</span>
                                        <span class="pull-right">{{sp.targetiv1}}</span>
                                        <p>{{Math.round(sp.ivt1 / sp.targetiv1 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.irist1}}</span>
                                        <span class="pull-right">{{sp.targetir1}}</span>
                                        <p>{{Math.round(sp.irist1 / sp.targetir1 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.khact1}}</span>
                                        <span class="pull-right">{{sp.targetk1}}</span>
                                        <p>{{Math.round(sp.khact1 / sp.targetk1 *100)}}%</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>T2</td>
                                    <td style="width: 120px;">
                                        <span>{{sp.daliat2}}</span>
                                        <span class="pull-right">{{sp.targetda2}}</span>
                                        <p>{{Math.round(sp.daliat2 / sp.targetda2 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivt2}}</span>
                                        <span class="pull-right">{{sp.targetiv2}}</span>
                                        <p>{{Math.round(sp.ivt2 / sp.targetiv2 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivt2}}</span>
                                        <span class="pull-right">{{sp.targetir2}}</span>
                                        <p>{{Math.round(sp.ivt2 / sp.targetir2 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.khact2}}</span>
                                        <span class="pull-right">{{sp.targetk2}}</span>
                                        <p>{{Math.round(sp.khact2 / sp.targetk2 *100)}}%</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>T3</td>
                                    <td style="width: 120px;">
                                        <span>{{sp.daliat3}}</span>
                                        <span class="pull-right">{{sp.targetda3}}</span>
                                        <p>{{Math.round(sp.daliat3 / sp.targetda3 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivt3}}</span>
                                        <span class="pull-right">{{sp.targetiv3}}</span>
                                        <p>{{Math.round(sp.ivt3 / sp.targetiv3 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.irist3}}</span>
                                        <span class="pull-right">{{sp.targetir3}}</span>
                                        <p>{{Math.round(sp.irist3 / sp.targetir3 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.khact3}}</span>
                                        <span class="pull-right">{{sp.targetk3}}</span>
                                        <p>{{Math.round(sp.khact3 / sp.targetk3 *100)}}%</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>T4</td>
                                    <td style="width: 120px;">
                                        <span>{{sp.daliat4}}</span>
                                        <span class="pull-right">{{sp.targetda4}}</span>
                                        <p>{{Math.round(sp.daliat4 / sp.targetda4 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivt4}}</span>
                                        <span class="pull-right">{{sp.targetiv4}}</span>
                                        <p>{{Math.round(sp.ivt4 / sp.targetiv4 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.irist4}}</span>
                                        <span class="pull-right">{{sp.targetir4}}</span>
                                        <p>{{Math.round(sp.irist4 / sp.targetir4 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.khact4}}</span>
                                        <span class="pull-right">{{sp.targetk4}}</span>
                                        <p>{{Math.round(sp.khact4 / sp.targetk4 *100)}}%</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modelTotalProducts" tabindex="-1" role="dialog" aria-labelledby="taskModalDetailTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalDetailTitle">Chi Tiết Sản Phẩm</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-dark table-bordered">
                            <thead>
                                <td>P/T</td>
                                <td>Daliah</td>
                                <td>Ivy</td>
                                <td>Iris</td>
                                <td>Khác</td>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="width: 10px;">P</td>
                                    <td style="width: 120px;">
                                        <span>{{sp.daliap}}</span>
                                        <span class="pull-right">{{sp.targetda}}</span>
                                        <p>{{Math.round(sp.daliap / sp.targetda *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivyp}}</span>
                                        <span class="pull-right">{{sp.targetiv}}</span>
                                        <p>{{Math.round(sp.ivyp / sp.targetiv *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.irisp}}</span>
                                        <span class="pull-right">{{sp.targetir}}</span>
                                        <p>{{Math.round(sp.irisp / sp.targetir *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.khacp}}</span>
                                        <span class="pull-right">{{sp.targetk}}</span>
                                        <p>{{Math.round(sp.khacp / sp.targetk *100)}}%</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>T1</td>
                                    <td style="width: 120px;">
                                        <span>{{sp.daliat1}}</span>
                                        <span class="pull-right">{{sp.targetda1}}</span>
                                        <p>{{Math.round(sp.daliat1 / sp.targetda1 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivt1}}</span>
                                        <span class="pull-right">{{sp.targetiv1}}</span>
                                        <p>{{Math.round(sp.ivt1 / sp.targetiv1 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.irist1}}</span>
                                        <span class="pull-right">{{sp.targetir1}}</span>
                                        <p>{{Math.round(sp.irist1 / sp.targetir1 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.khact1}}</span>
                                        <span class="pull-right">{{sp.targetk1}}</span>
                                        <p>{{Math.round(sp.khact1 / sp.targetk1 *100)}}%</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>T2</td>
                                    <td style="width: 120px;">
                                        <span>{{sp.daliat2}}</span>
                                        <span class="pull-right">{{sp.targetda2}}</span>
                                        <p>{{Math.round(sp.daliat2 / sp.targetda2 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivt2}}</span>
                                        <span class="pull-right">{{sp.targetiv2}}</span>
                                        <p>{{Math.round(sp.ivt2 / sp.targetiv2 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivt2}}</span>
                                        <span class="pull-right">{{sp.targetir2}}</span>
                                        <p>{{Math.round(sp.ivt2 / sp.targetir2 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.khact2}}</span>
                                        <span class="pull-right">{{sp.targetk2}}</span>
                                        <p>{{Math.round(sp.khact2 / sp.targetk2 *100)}}%</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>T3</td>
                                    <td style="width: 120px;">
                                        <span>{{sp.daliat3}}</span>
                                        <span class="pull-right">{{sp.targetda3}}</span>
                                        <p>{{Math.round(sp.daliat3 / sp.targetda3 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivt3}}</span>
                                        <span class="pull-right">{{sp.targetiv3}}</span>
                                        <p>{{Math.round(sp.ivt3 / sp.targetiv3 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.irist3}}</span>
                                        <span class="pull-right">{{sp.targetir3}}</span>
                                        <p>{{Math.round(sp.irist3 / sp.targetir3 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.khact3}}</span>
                                        <span class="pull-right">{{sp.targetk3}}</span>
                                        <p>{{Math.round(sp.khact3 / sp.targetk3 *100)}}%</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>T4</td>
                                    <td style="width: 120px;">
                                        <span>{{sp.daliat4}}</span>
                                        <span class="pull-right">{{sp.targetda4}}</span>
                                        <p>{{Math.round(sp.daliat4 / sp.targetda4 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.ivt4}}</span>
                                        <span class="pull-right">{{sp.targetiv4}}</span>
                                        <p>{{Math.round(sp.ivt4 / sp.targetiv4 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.irist4}}</span>
                                        <span class="pull-right">{{sp.targetir4}}</span>
                                        <p>{{Math.round(sp.irist4 / sp.targetir4 *100)}}%</p>
                                    </td>
                                    <td style="width: 120px;">
                                        <span>{{sp.khact4}}</span>
                                        <span class="pull-right">{{sp.targetk4}}</span>
                                        <p>{{Math.round(sp.khact4 / sp.targetk4 *100)}}%</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="target" tabindex="-1" role="dialog" aria-labelledby="taskModalDetailTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalDetailTitle">Update Target T{{t}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="text" class="form-control" name="redirect" v-model="amount">
                        <button type="submit" @click.prevent="createTarget()" class="btn btn-primary mb-2">Thêm</button>

                    </div>
                </div>
            </div>
        </div>
        <!-- /.content -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            tong:{
                t2:0,
                t3:0,
                t4:0,
                t5:0,
                t6:0,
                t7:0,
                t8:0,
            },
            t:'',
            amount:'',
            sp:{},
            sales: [],
            doanhsodata: [],
            topCount: 0,
            detaildata:[],
            value:0,
            checkT:0,
            opens: "center",
            checkP:'', //which way the picker opens, default "center", can be "left"/"right"
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
            ura: '/api/detailTarget',
            uri: '/api/users-target',
        }
    },
    methods: {
        showtotal(value){
            $('#modelTotalProducts').modal('show');
        },
        searchdata() {
            this.loadSales();
            this.loaddATA();
        },
        updateTarget(value,showroom){
            this.t = value;
            if (showroom == 'Online' || showroom == 'Online  ') {
                $('#target').modal('show');
            }

        },
        detailTarget(value) {
            // console.log(value);
            this.value = value;
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)], value:this.value}, { arrayFormat: 'bracket' }, {});
            axios.get(this.ura + '?' + dates_para)
                .then(responsive => {
                    this.detaildata = responsive.data;
                    let retotal = [];
                    $.each(this.detaildata, function(key, value) {
                        retotal.push(value);
                    });
                    this.reTotal(retotal);
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
            $('#DetailCenter').modal({backdrop: 'static', keyboard: false});
            this.fillP(this.startDate);

        },
        reTotal(retotal){
            // // this.retotal = this.detaildata;
            let t2 = [];let t3 = [];let t4 = [];let t5 = [];
            let t6 = [];let t7 = [];let t8 = [];
            $.each(retotal,function(v, k){
                t2.push(k[0]);
                t3.push(k[1]);
                t4.push(k[2]);
                t5.push(k[3]);
                t6.push(k[4]);
                t7.push(k[5]);
                t8.push(k[6]);
            });
            this.tong.t2 = t2.reduce(function(v, k){
                return v + Number(k);
            }, 0);
            this.tong.t3 = t3.reduce(function(v, k){
                return v + Number(k);
            }, 0);
            this.tong.t4 = t4.reduce(function(v, k){
                return v + Number(k);
            }, 0);
            this.tong.t5 = t5.reduce(function(v, k){
                return v + Number(k);
            }, 0);
            this.tong.t6 = t6.reduce(function(v, k){
                return v + Number(k);
            }, 0);
            this.tong.t7 = t7.reduce(function(v, k){
                return v + Number(k);
            }, 0);
            this.tong.t8 = t8.reduce(function(v, k){
                return v + Number(k);
            }, 0);
        },
        createTarget(){
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let link = 'api/inputtarget?' + dates_para + '&week=' + this.t + '&amount=' + this.amount;
            axios.get(link).then(response => {
                this.loaddATA();
            });
        },
        fillP(value){
            var v = moment(String(value)).format('YYYY-MM-DD');
            if(v == '2019-05-20'){this.checkP = 'P6';}
            if(v == '2019-06-17'){this.checkP = 'P7';}
            if(v == '2019-07-15'){this.checkP = 'P8';}
            if(v == '2019-08-12'){this.checkP = 'P9';}
            if(v == '2019-09-09'){this.checkP = 'P10';}
            if(v == '2019-10-09'){this.checkP = 'P11';}
            if(v == '2019-11-04'){this.checkP = 'P12';}
            if(v == '2019-12-02'){this.checkP = 'P13';}
            if(v == '2019-12-30'){this.checkP = 'P1/Z14';}
            if(v == '2020-01-27'){this.checkP = 'P2/Z14';}
            if(v == '2020-02-24'){this.checkP = 'P3/Z14';}
            if(v == '2020-03-23'){this.checkP = 'P4/Z14';}
            if(v == '2020-04-20'){this.checkP = 'P5/Z14';}
            if(v == '2020-05-18'){this.checkP = 'P6/Z14';}
            if(v == '2020-06-15'){this.checkP = 'P7/Z14';}
            if(v == '2020-07-13'){this.checkP = 'P8/Z14';}
            if(v == '2020-08-10'){this.checkP = 'P9/Z14';}
            if(v == '2020-09-07'){this.checkP = 'P10/Z14';}
            if(v == '2020-10-05'){this.checkP = 'P11/Z14';}
            if(v == '2020-11-02'){this.checkP = 'P12/Z14';}
            if(v == '2020-11-30'){this.checkP = 'P13/Z14';}
            
        },
        loadSales() {
            this.$Progress.start();
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            axios.get(this.uri + '?' + dates_para)
                .then(responsive => {
                    this.sales = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail(); 
                    alert('bạn không có quyền xem');
                });
        },
        loaddATA() {
            
            this.$Progress.start();
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            axios.get(this.uri + '?' + dates_para)
                .then(responsive => {
                    this.doanhsodata = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => {
                    this.$Progress.fail(); 
                    alert('bạn không có quyền xem');
                });
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.startDate = moment(this.startDate).add(1, 'day').format().slice(0,10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.endDate = moment(this.endDate).format().slice(0,10);
        },
        updateByKHTN() {
            $('#chung').show();
            $('#amountnew').hide();
            $('#amount').hide();
            this.uri = 'api/users-target';
            this.ura = 'api/detailTarget';
            this.loadSales();
        },
        updateByKH15() {
            $('#chung').show();
            $('#amountnew').hide();
            $('#amount').hide();
            this.uri = 'api/showroom-target-lead';
            this.ura = 'api/detailKH15';
            this.loadSales();
        },
        updateByDH() {
            $('#chung').show();
            $('#amountnew').hide();
            $('#amount').hide();
            this.uri = 'api/showroom-target-order';
            this.ura = 'api/detailDonHang';
            this.loadSales();
        },
        updateByDS() {
            $('#chung').hide();
            $('#amountnew').hide();
            $('#amount').show();
            this.uri = 'api/showroom-target-amount';
            this.ura = 'api/detailDoanhSo';
            this.loadSales();
        },
        updateByDSnew() {
            $('#chung').hide();
            $('#amount').hide();
            $('#amountnew').show();
            this.loaddATA();
            this.uri = 'api/showroom-target-amount-new';
            this.ura = 'api/detailDoanhSo';
        },
        formatPrice(value) {
            let val = (value/1000000).toFixed(1).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        formatPriceDon(value) {
            let val = (-value/1000000).toFixed(1).replace(',', '.');
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        },
        formatdiv(value) {
            let val = (value/1).toFixed(0).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        },
        show(value){
            console.log(value);
            $('#modelProducts').modal('show');
            axios.post('api/showdataProduct', {data: value})
            .then(response => {
                this.sp = response.data;
            });
        }
    },
    async mounted() {
    }
}
</script>

<style>
.bg-new {
    background-color:#b3d7f5 !important;
}

.style-color {
    color: #7b838c !important;
    font-size: 11px;
    font-style: oblique;
}

.tableFixHead          { overflow-y: auto; height: 700px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1;}
.popup td {
    padding: .5rem !important;
}
table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
tbody tr td { z-index: 0}
</style>