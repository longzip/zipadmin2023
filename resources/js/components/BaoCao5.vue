<template>
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-2">
                <h1 class="m-0 text-dark">Sales Target</h1>
            </div><!-- /.col -->
            <div class="col-sm-3">
                <label for="">Ngày</label>
                <date-range-picker class="mt-2" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="selectedDate" @update="updateValues" :ranges="ranges" :alwaysShowCalendars="false">
                    <div slot="input" slot-scope="picker">{{ selectedDate.startDate | myDate }} - {{ selectedDate.endDate | myDate }}</div>
                </date-range-picker>
            </div>
            <div class="col-sm-2">
                <div class="form-group" style="float: left;">
                    <label>Chọn Tất</label>
                    <input class="form-control" type="checkbox" v-model="checked" @change="check()" >
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label>Chọn Showroom <a href="#" @click="reSelect"><i class="fa fa-refresh"></i></a></label>
                    <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="costcenters" :multiple="true" group-values="items" group-label="cat" :group-select="true" :close-on-select="true" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoShowroom">
                        <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                    </multiselect>
                </div>
            </div>
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
        </ul>
        <div class="tab-content"  style="width:100%">
            <div id="khtn" class="tab-pane active ml-0 mr-0 pr-0 pl-0" style="min-width:680px">
                <div class=" table-responsive tableFixHead">
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
                            <template v-for="(sale, index) in sales">
                                <tr class="table-info">
                                    <td style="max-width: 40px;">{{sale.costcenter}} </td>
                                    <td>
                                        <span v-bind:class="{ 'bg-danger': (sale.sumc >= sale.sumt) , 'bg-new' : (sale.sumc < sale.sumt)}" style="padding-right: 3px;">{{ sale.sumc }}</span>
                                        <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                            <div class=" text-dark"  v-bind:style="{width: (sale.sumc / sale.sumt) * 100 + '%'}">
                                                {{ (formatPrice((sale.sumc / sale.sumt) * 100 )) + '%'}}
                                            </div>
                                        </div>
                                        <span class="pull-right" style="padding-right: 3px;">{{ sale.sumt }}</span>
                                        <br/>
                                        <div class="progress progress-xs pull-right" v-if="sale.hienthi == 1" style="display: inline-table;position: relative;top: 3px;">
                                            <div class=" text-dark"  v-bind:style="{width: (sale.sumc / sale.sumtsr) * 100 + '%'}">
                                                {{ (formatPrice((sale.sumc / sale.sumtsr) * 100 )) + '%'}}
                                            </div>
                                        </div>
                                        <span class="pull-right" v-if="sale.hienthi == 1" style="padding-right: 3px;">{{ sale.sumtsr }}</span><br>
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy}} / {{sale.tivy  * 3}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris}} / {{sale.tiris  * 3}}  &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td style="min-width: 140px">
                                        <div>
                                            <span class="bg-new">{{sale.sumdd1}}</span>
                                            <span class="border-left">{{ sale.sumdc1 }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c1 / sale.t1) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c1 / sale.t1) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-left:2px; padding-right:2px;">{{ sale.t1 }}</span>
                                            <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c1 >= sale.t1) , 'bg-new' : (sale.c1 < sale.t1)}">{{ sale.c1 }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c1 / sale.tsr1) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c1 / sale.tsr1) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" style="padding-right:2px;" v-if="sale.hienthi == 1">{{ sale.tsr1 }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="border-left fz111">{{ sale.sumMt1 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt1 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt1 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt1 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt1 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt1 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt1 }}</span>
                                        </div> 
                                        <div  style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy1}} / {{sale.tivy1 * 3}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris1}} / {{sale.tiris1 * 3}}  &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td style="min-width: 140px">
                                        <div>
                                            <span class="bg-new">{{sale.sumdd2}}</span>
                                            <span class="border-left">{{ sale.sumdc2 }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c2 / sale.t2) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c2 / sale.t2) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-left:2px; padding-right:2px;">{{ sale.t2 }}</span>
                                            <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c2 >= sale.t2) , 'bg-new' : (sale.c2 < sale.t2)}">{{ sale.c2 }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c2 / sale.tsr2) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c2 / sale.tsr2) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" style="padding-right:2px;" v-if="sale.hienthi == 1">{{ sale.tsr2 }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="border-left fz111">{{ sale.sumMt2 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt2 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt2 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt2 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt2 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt2 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt2 }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy2}} / {{sale.tivy2 * 3}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris2}} / {{sale.tiris2 * 3}}  &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td style="min-width: 140px">
                                        <div>
                                            <span class="bg-new">{{sale.sumdd3}}</span>
                                            <span class="border-left">{{ sale.sumdc3 }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c3 / sale.t3) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c3 / sale.t3) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-left:2px; padding-right:2px;">{{ sale.t3 }}</span>
                                            <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c3 >= sale.t3) , 'bg-new' : (sale.c3 < sale.t3)}">{{ sale.c3 }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c3 / sale.tsr3) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c3 / sale.tsr3) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" style="padding-right:2px;" v-if="sale.hienthi == 1">{{ sale.tsr3 }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="border-left fz111">{{ sale.sumMt3 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt3 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt3 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt3 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt3 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt3 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt3 }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy3}} / {{sale.tivy3 * 3}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris3}} / {{sale.tiris3 * 3}}  &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td style="min-width: 140px">
                                        <div>
                                            <span class="bg-new">{{sale.sumdd4}}</span>
                                            <span class="border-left">{{ sale.sumdc4 }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c4 / sale.t4) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c4 / sale.t4) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-left:2px; padding-right:2px;">{{ sale.t4 }}</span>
                                            <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (sale.c4 >= sale.t4) , 'bg-new' : (sale.c4 < sale.t4)}">{{ sale.c4 }}</span>
                                            <br/>
                                            <div class="progress progress-xs  pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c4 / sale.tsr4) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c4 / sale.tsr4) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" style="padding-right:2px;" v-if="sale.hienthi == 1">{{ sale.tsr4 }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="border-left fz111">{{ sale.sumMt4 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt4 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt4 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt4 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt4 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt4 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt4 }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy4}} / {{sale.tivy4 * 3}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris4}} / {{sale.tiris4 * 3}}  &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(month, key) in sale.name">
                                    <td>{{ month.name}}</td>
                                    <td style=" max-width: 60px;">
                                        <span v-bind:class="{ 'bg-danger': (month.c >= month.t) , 'bg-new' : (month.c < month.t)}">{{ month.c}}</span>
                                        <span class=" pull-right ">{{ month.t}}</span><br/>
                                        <div class="progress progress-xs" style="display: inline-table;">
                                            <div class=" text-dark"  v-bind:style="{width: (month.c / month.t) * 100 + '%'}">
                                                {{ (formatPrice((month.c / month.t) * 100 ))+ '%'}}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="fz11" title="SL Ivy / Target">IV: {{month.civy}} / {{month.tivy * 3}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{month.ciris}} / {{month.tiris * 3}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td v-for="contact in month.contacts " style=" min-width: 140px ">
                                        <div>
                                            <span class="bg-new">{{ contact.sumd }}</span>
                                            <span class="border-left">{{ contact.sumc }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="month.sm == 0">
                                                <div class=" text-dark"  v-bind:style="{width: (contact.c / contact.t) * 100 + '%'}">
                                                    <span class="border-left">{{ (formatPrice((contact.c / contact.t) * 100 )) + '%'}} </span>
                                                </div>
                                            </div>
                                            <span class="border-left pull-right">{{ contact.t }}</span>
                                            <span class="border-left  pull-right" v-bind:class="{ 'bg-danger': (contact.c >= contact.t) , 'bg-new' : (contact.c < contact.t)}">{{ contact.c }}</span>
                                        </div>
                                        <div>
                                            <span class="border-left fz111">{{ contact.monday }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ contact.tuesday }}</span>
                                            <span class="border-left fz111">{{ contact.wednesday }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ contact.thursday }}</span>
                                            <span class="border-left fz111">{{ contact.friday }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ contact.saturday }}</span>
                                            <span class="border-left fz111">{{ contact.sunday }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{contact.civy}} / {{contact.tivy * 3}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{contact.ciris}} / {{contact.tiris * 3}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="kh15" class=" tab-pane ml-0 mr-0 pr-0 pl-0" style="min-width:680px">
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
                            <template v-for="(sale, index) in sales">
                                <tr class="table-info">
                                    <td style="max-width: 40px;">{{sale.costcenter}}</td>
                                    <td>
                                        <span v-bind:class="{ 'bg-danger': (sale.sumc >= sale.sumt ) , 'bg-new' : (sale.sumc < sale.sumt)}" style="padding-right: 3px;">{{ sale.sumc }}</span>
                                        <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                            <div class="text-dark" style="{width: (((sale.sumc / sale.sumt) * 100) + '%')}">
                                                {{ (formatPrice((sale.sumc / sale.sumt) * 100 )) + '%'}}
                                            </div>
                                        </div>
                                        <span class=" pull-right ">{{ sale.sumt }}</span>
                                        <br/>
                                        <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                            <div class="text-dark" style="{width: (sale.sumc / sale.sumtsr) * 100 + '%'}">
                                                {{ (formatPrice((sale.sumc / sale.sumtsr) * 100 )) + '%'}}
                                            </div>
                                        </div>
                                        <span class="pull-right" v-if="sale.hienthi == 1" style="padding-right: 3px;">{{ sale.sumtsr }}</span><br>
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy}} / {{sale.tivy * 9}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris}} / {{sale.tiris * 9}} &nbsp;&nbsp;&nbsp; </span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="bg-new">{{sale.sumdd1}}</span>
                                            <span class="border-left text-muted">{{ sale.sumdc1 }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c1 / sale.t1) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c1 / sale.t1) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right">{{ sale.t1 }}</span>
                                            <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (sale.c1 >= sale.t1 ) , 'bg-new' : (sale.c1 < sale.t1)}">{{ sale.c1 }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class="text-dark"  v-bind:style="{width: (sale.c1 / sale.tsr1) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c1 / sale.tsr1) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" style="padding-right:2px;" v-if="sale.hienthi == 1">{{ sale.tsr1 }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="border-left fz111">{{ sale.sumMt1 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt1 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt1 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt1 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt1 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt1 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt1 }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy1}} / {{sale.tivy1 * 9}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris1}} / {{sale.tiris1 * 9}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="bg-new">{{sale.sumdd2}}</span>
                                            <span class="border-left">{{ sale.sumdc2 }}</span>
                                            <div class="progress progress-xs  pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark" v-bind:style="{width: (sale.c2 / sale.t2) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c2 / sale.t2) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right">{{ sale.t2 }}</span>
                                            <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (sale.c2 >= sale.t2 ) , 'bg-new' : (sale.c2 < sale.t2)}">{{ sale.c2 }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c2 / sale.tsr2) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c2 / sale.tsr2) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" v-if="sale.hienthi == 1">{{ sale.tsr2 }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="border-left fz111">{{ sale.sumMt2 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt2 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt2 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt2 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt2 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt2 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt2 }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy2}} / {{sale.tivy2 * 9}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris2}} / {{sale.tiris2 * 9}}  &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="bg-new">{{sale.sumdd3}}</span>
                                            <span class="border-left">{{ sale.sumdc3 }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c3 / sale.t3) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c3 / sale.t3) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right">{{ sale.t3 }}</span>
                                            <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (sale.c3 >= sale.t3 ) , 'bg-new' : (sale.c3 < sale.t3)}">{{ sale.c3 }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c3 / sale.tsr3) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c3 / sale.tsr3) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" v-if="sale.hienthi == 1">{{ sale.tsr3 }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="border-left fz111">{{ sale.sumMt3 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt3 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt3 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt3 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt3 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt3 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt3 }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy3}} / {{sale.tivy3 * 9}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris3}} / {{sale.tiris3 * 9}}  &nbsp;&nbsp;&nbsp; </span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="bg-new">{{sale.sumdd4}}</span>
                                            <span class="border-left">{{ sale.sumdc4 }}</span>
                                            <span class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c4 / sale.t4) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c4 / sale.t4) * 100 )) + '%'}}
                                                </div>
                                            </span>
                                            <span class="border-left pull-right">{{ sale.t4 }}</span>
                                            <span class="border-left pull-right"  v-bind:class="{ 'bg-danger': (sale.c4 >= sale.t4 ) , 'bg-new' : (sale.c4 < sale.t4)}">{{ sale.c4 }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c4 / sale.tsr4) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c4 / sale.tsr4) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" v-if="sale.hienthi == 1">{{ sale.tsr4 }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="border-left fz111">{{ sale.sumMt4 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt4 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt4 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt4 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt4 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt4 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt4 }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy4}} / {{sale.tivy4 * 9}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris4}} / {{sale.tiris4 * 9}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(month, key) in sale.name">
                                    <td>{{ month.name}}</td>
                                    <td style=" max-width: 60px;">

                                        <span v-bind:class="{ 'bg-danger': (month.c >= month.t ) , 'bg-new' : (month.c < month.t)}">{{ month.c}}</span>
                                        <span class=" pull-right">{{ month.t}}</span><br/>
                                        <div class="progress progress-xs" style="display: inline-table;">
                                            <div class=" text-dark"  v-bind:style="{width: (month.c / month.t) * 100 + '%'}">
                                                {{ (formatPrice((month.c / month.t) * 100 ))+ '%'}}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="fz11" title="SL Ivy / Target">IV: {{month.civy}} / {{month.tivy * 9}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{month.ciris}} / {{month.tiris * 9}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td v-for="contact in month.contacts " style=" min-width: 140px ">
                                        <div >
                                            <span class="bg-new">{{ contact.sumd }}</span>
                                            <span class="border-left">{{ contact.sumc }}</span>

                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="month.sm == 0">
                                                <div class=" text-dark" v-bind:style="{width: (contact.c / contact.t) * 100 + '%'}">
                                                    <span class="border-left">{{ (formatPrice((contact.c / contact.t) * 100 )) + '%'}} </span>
                                                </div>
                                            </div>
                                            <span class="border-left pull-right">{{ contact.t }}</span>
                                            <span class="border-left pull-right" v-bind:class="{ 'bg-danger': (contact.c >= contact.t) , 'bg-new' : (contact.c < contact.t)}">{{ contact.c }}</span>
                                        </div>
                                        <div>
                                            <span class="border-left fz111">{{ contact.monday }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ contact.tuesday }}</span>
                                            <span class="border-left fz111">{{ contact.wednesday }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ contact.thursday }}</span>
                                            <span class="border-left fz111">{{ contact.friday }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ contact.saturday }}</span>
                                            <span class="border-left fz111">{{ contact.sunday }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{contact.civy}} / {{contact.tivy * 9}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{contact.ciris}} / {{contact.tiris * 9}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
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
                            <template v-for="(sale, index) in sales">
                                <tr class="table-info">
                                    <td style=" max-width: 60px;">{{sale.costcenter}}</td>
                                    <td>
                                        <span v-bind:class="{ 'bg-danger': (sale.sumc >= sale.sumt) , 'bg-new' : (sale.sumc < sale.sumt)}">{{ sale.sumc }}</span>
                                        <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                            <div class="text-dark" v-bind:style="{width: (sale.sumc / sale.sumt) * 100 + '%'}">
                                                {{ (formatPrice((sale.sumc / sale.sumt) * 100 )) + '%'}}
                                            </div>
                                        </div>
                                        <span class="pull-right" style="padding-right: 3px;">{{ sale.sumt }}</span>
                                        <br/>
                                        <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                            <div class="text-dark" v-bind:style="{width: (sale.sumc / sale.sumtsr) * 100 + '%'}">
                                                {{ (formatPrice((sale.sumc / sale.sumtsr) * 100 )) + '%'}}
                                            </div>
                                        </div>
                                        <span v-if="sale.hienthi == 1" class="pull-right" style="padding-right: 3px;">{{ sale.sumtsr }}</span><br>
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy}} / {{sale.tivy}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris}} / {{sale.tiris}} &nbsp;&nbsp;&nbsp; </span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="border-left" v-bind:class="{ 'bg-danger': (sale.c1 >= sale.t1) , 'bg-new' : (sale.c1 < sale.t1)}">{{ sale.c1 }}</span>
                                            <span class="border-left ">{{ sale.sumamount1 }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark" v-bind:style="{width: (sale.c1 / sale.t1) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c1 / sale.t1) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-right:3px;">{{ sale.t1 }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c1 / sale.tsr1) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c1 / sale.tsr1) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" style="padding-right:3px;" v-if="sale.hienthi == 1">{{ sale.tsr1 }}</span>
                                        </div>
                                        <div style="clear: both">
                                            <span class="border-left fz111">{{ sale.sumMt1 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt1 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt1 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt1 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt1 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt1 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt1 }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy1}} / {{sale.tivy1}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris1}} / {{sale.tiris1}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="border-left" v-bind:class="{ 'bg-danger': (sale.c2 >= sale.t2) , 'bg-new' : (sale.c2 < sale.t2)}">{{ sale.c2 }}</span>
                                            <span class="border-left">{{ sale.sumamount2 }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c2 / sale.t2) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c2 / sale.t2) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-right:3px;">{{ sale.t2 }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c2 / sale.tsr2) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c2 / sale.tsr2) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-right:3px;" v-if="sale.hienthi == 1">{{ sale.tsr2 }}</span>
                                        </div>
                                        <div style="clear: both">
                                            <span class="border-left fz111">{{ sale.sumMt2 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt2 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt2 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt2 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt2 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt2 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt2 }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy2}} / {{sale.tivy2}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris2}} / {{sale.tiris2}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="border-left" v-bind:class="{ 'bg-danger': (sale.c3 >= sale.t3) , 'bg-new' : (sale.c3 < sale.t3)}">{{ sale.c3 }}</span>
                                            <span class="border-left">{{ sale.sumamount3 }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c3 / sale.t3) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c3 / sale.t3) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-right:3px;">{{ sale.t3 }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c3 / sale.tsr3) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c3/ sale.tsr3) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-right:3px;" v-if="sale.hienthi == 1">{{ sale.tsr3 }}</span>
                                        </div>
                                        <div style="clear: both">
                                            <span class="border-left fz111">{{ sale.sumMt3 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt3 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt3 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt3 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt3 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt3 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt3 }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy3}} / {{sale.tivy3}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris3}} / {{sale.tiris3}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="border-left" v-bind:class="{ 'bg-danger': (sale.c4 >= sale.t4) , 'bg-new' : (sale.c4 < sale.t4)}">{{ sale.c4 }}</span>
                                            <span class="border-left">{{ sale.sumamount4 }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark" v-bind:style="{width: (sale.c4 / sale.t4) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c4 / sale.t4) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-right:3px;">{{ sale.t4 }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c4 / sale.tsr4) * 100 + '%'}">
                                                    {{ (formatPrice((sale.c4 / sale.tsr4) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-right:3px;" v-if="sale.hienthi == 1">{{ sale.tsr4 }}</span>
                                        </div>
                                        <div style="clear: both">
                                            <span class="border-left fz111">{{ sale.sumMt4 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTUt4 }}</span>
                                            <span class="border-left fz111">{{ sale.sumWt4 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumTHt4 }}</span>
                                            <span class="border-left fz111">{{ sale.sumFt4 }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ sale.sumSAt4 }}</span>
                                            <span class="border-left fz111">{{ sale.sumSUt4 }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy4}} / {{sale.tivy4}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris4}} / {{sale.tiris4}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(month, key) in sale.name">
                                    <td style=" max-width: 60px;">{{ month.name}}</td>
                                    <td>
                                        <span v-bind:class="{ 'bg-danger': (month.c >= month.t) , 'bg-new' : (month.c < month.t)}">{{ month.c}}</span>
                                        <span class=" pull-right">{{ month.t}}</span><br/>
                                        <div class="progress progress-xs" style="display: inline-table;">
                                            <div class="text-dark" v-bind:style="{width: (month.c / month.t) * 100 + '%'}">
                                                {{ (formatPrice((month.c / month.t) * 100 ))+ '%'}}
                                            </div>
                                        </div>
                                        <div >
                                            <span class="fz11" title="SL Ivy / Target">IV: {{month.civy}} / {{month.tivy}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{month.ciris}} / {{month.tiris}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td v-for="contact in month.contacts " style=" min-width: 140px ">
                                        <div >
                                            <span class="border-left" v-bind:class="{ 'bg-danger': (contact.c >= contact.t) , 'bg-new' : (contact.c < contact.t)}">{{ contact.c }}</span>
                                            <span class="border-left">{{ contact.amount }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;" v-if="month.sm == 0">
                                                <div class=" text-dark"  v-bind:style="{width: (contact.c / contact.t) * 100 + '%'}">
                                                    <span class="border-left">{{ (formatPrice((contact.c / contact.t) * 100 )) + '%'}} </span>
                                                </div>
                                            </div>
                                            <span class="border-left pull-right">{{ contact.t }}</span>
                                        </div>
                                        <div>
                                            <span class="border-left fz111">{{ contact.monday }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ contact.tuesday }}</span>
                                            <span class="border-left fz111">{{ contact.wednesday }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ contact.thursday }}</span>
                                            <span class="border-left fz111" >{{ contact.friday }}</span>
                                            <span class="border-left badge badge-pill badge-light">{{ contact.saturday }}</span>
                                            <span class="border-left fz111">{{ contact.sunday }}</span>
                                        </div> 
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{contact.civy}} / {{contact.tivy}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{contact.ciris}} / {{contact.tiris}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="ds" class=" tab-pane ml-0 mr-0 pr-0 pl-0" style="min-width:680px">
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
                            <template v-for="(sale, index) in sales">
                                <tr class="table-info">
                                    <td style=" min-width: 40px;">{{sale.costcenter}}</td>
                                    <td>
                                        <span>{{ formatPrice1(sale.sumc) }}</span>
                                        <div class="progress progress-xs pull-right" style="display: inline-table; position: relative;top: 3px;">
                                            <div class=" text-dark" v-bind:style="{width: (sale.sumc / sale.sumt) * 100 + '%'}">
                                                {{ (formatdiv((sale.sumc / sale.sumt) * 100 )) + '%'}}
                                            </div>
                                        </div>
                                        <span class=" pull-right " style="padding-right: 3px;">{{ formatPrice1(sale.sumt) }}</span>
                                        <br/>
                                        <div class="progress progress-xs pull-right" style="display: inline-table; position: relative;top: 3px;" v-if="sale.hienthi == 1">
                                            <div class=" text-dark" v-bind:style="{width: (sale.sumc / sale.sumtsr) * 100 + '%'}">
                                                {{ (formatdiv((sale.sumc / sale.sumtsr) * 100 )) + '%'}}
                                            </div>
                                        </div>
                                        <span v-if="sale.hienthi == 1" style="padding-right: 3px;" class="pull-right">{{ formatPrice1(sale.sumtsr) }}</span><br>
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy}} / {{sale.tivy}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris}} / {{sale.tiris}}  &nbsp;&nbsp;&nbsp; </span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="border-right">{{ formatPrice1(sale.c1) }}</span>
                                            <span class="border-left bg-new">{{ sale.sumamount1 }}</span>
                                            <div class="progress progress-xs  pull-right" style="display: inline-table;position:relative;top:3px;">
                                                <div class=" text-dark" v-bind:style="{width: (sale.c1 / sale.t1) * 100 + '%'}">
                                                    {{ (formatdiv((sale.c1 / sale.t1) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-right:3px">{{ formatPrice1(sale.t1) }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position:relative;top:3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark" v-bind:style="{width: (sale.c1 / sale.tsr1) * 100 + '%'}">
                                                    {{ (formatdiv((sale.c1 / sale.tsr1) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" style="padding-right:3px" v-if="sale.hienthi == 1">{{ formatPrice1(sale.tsr1) }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy1}} / {{sale.tivy1}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris1}} / {{sale.tiris1}} &nbsp;&nbsp;&nbsp; </span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="border-right">{{ formatPrice1(sale.c2) }}</span>
                                            <span class="border-left bg-new">{{ sale.sumamount2 }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position:relative;top:3px;">
                                                <div class=" text-dark" v-bind:style="{width: (sale.c2 / sale.t2) * 100 + '%'}">
                                                    {{ (formatdiv((sale.c2 / sale.t2) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-right:3px">{{ formatPrice1(sale.t2) }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position:relative;top:3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark" v-bind:style="{width: (sale.c2 / sale.tsr2) * 100 + '%'}">
                                                    {{ (formatdiv((sale.c2 / sale.tsr2) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" style="padding-right:3px" v-if="sale.hienthi == 1">{{ formatPrice1(sale.tsr2) }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy2}} / {{sale.tivy2}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris2}} / {{sale.tiris2}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="border-right">{{ formatPrice1(sale.c3) }}</span>
                                            <span class="border-left bg-new">{{ sale.sumamount3 }}</span>

                                            <div class="progress progress-xs pull-right" style="display: inline-table;position:relative;top:3px;">
                                                <div class=" text-dark"  v-bind:style="{width: (sale.c3 / sale.t3) * 100 + '%'}">
                                                    {{ (formatdiv((sale.c3 / sale.t3) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-right:3px;">{{ formatPrice1(sale.t3) }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position:relative;top:3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark" v-bind:style="{width: (sale.c3 / sale.tsr3) * 100 + '%'}">
                                                    {{ (formatdiv((sale.c3 / sale.tsr3) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" style="padding-right:3px;" v-if="sale.hienthi == 1">{{ formatPrice1(sale.tsr3) }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy3}} / {{sale.tivy3}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris3}} / {{sale.tiris3}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td style=" min-width: 140px ">
                                        <div>
                                            <span class="border-right">{{ formatPrice1(sale.c4) }}</span>
                                            <span class="border-left bg-new">{{ sale.sumamount4 }}</span>

                                            <div class="progress progress-xs pull-right" style="display: inline-table;position:relative;top:3px;">
                                                <div class=" text-dark" v-bind:style="{width: (sale.c4 / sale.t4) * 100 + '%'}">
                                                    {{ (formatdiv((sale.c4 / sale.t4) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="border-left pull-right" style="padding-right:3px;">{{ formatPrice1(sale.t4) }}</span>
                                            <br/>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position:relative;top:3px;" v-if="sale.hienthi == 1">
                                                <div class=" text-dark" v-bind:style="{width: (sale.c4 / sale.tsr4) * 100 + '%'}">
                                                    {{ (formatdiv((sale.c4 / sale.tsr4) * 100 )) + '%'}}
                                                </div>
                                            </div>
                                            <span class="pull-right" style="padding-right:3px;" v-if="sale.hienthi == 1">{{ formatPrice1(sale.tsr4) }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{sale.civy4}} / {{sale.tivy4}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{sale.ciris4}} / {{sale.tiris4}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="(month, key) in sale.name">
                                    <td>{{ month.name}}</td>
                                    <td style=" min-width: 80px;">
                                        <span>{{ formatPrice1(month.c)}}</span>
                                        <span class=" pull-right">{{ formatPrice1(month.t) }}</span><br/>
                                        <div class="progress progress-xs" style="display: inline-table;">
                                            <div class=" text-dark" v-bind:style="{width: (month.c / month.t) * 100 + '%'}">
                                                {{ (formatdiv((month.c / month.t) * 100 ))+ '%'}}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="fz11" title="SL Ivy / Target">IV: {{month.civy}} / {{month.tivy}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{month.ciris}} / {{month.tiris}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                    <td v-for="contact in month.contacts " style=" min-width: 140px ">
                                        <div >
                                            <span class="border-right bg-new">{{ formatPrice1(contact.c) }}</span>
                                            <span class="border-left">{{ contact.amount }}</span>
                                            <div class="progress progress-xs pull-right" style="display: inline-table;position: relative;top: 3px;">
                                                <div class=" text-dark" v-bind:style="{width: (contact.c / contact.t) * 100 + '%'}">
                                                    <span class="border-left">{{ (formatdiv((contact.c / contact.t) * 100)) + '%'}} </span>
                                                </div>
                                            </div>
                                            <span class="border-right pull-right">{{ formatPrice1(contact.t) }}</span>
                                        </div>
                                        <div style="clear: both;">
                                            <span class="fz11" title="SL Ivy / Target">IV: {{contact.civy}} / {{contact.tivy}} - </span> 
                                            <span class="fz11" title="SL Iris / Target">IR: {{contact.ciris}} / {{contact.tiris}} &nbsp;&nbsp;&nbsp;</span>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            messageText: '',
            checked : '',
            sales: [],
            showroom_ids: [],
            costcenters: [],
            costcenter: [],
            topCount: 0,
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
            uri: '/api/users-target-sr',
        }
    },
    props: {
        userName: String ,
    },
    methods: {
        searchdata() {
            this.loadSales();
        },
        check(){
            this.$parent.showroomsSelected = [];
            if(this.checked) {
                this.$parent.showroomsSelected = this.costcenter ;
            }
        },
        reSelect() {
            this.checked = '';
            this.$parent.showroomsSelected = [];
            this.loadSales();
        },
        sendMessage(){
                this.$emit('messagesent', {
                    message:this.messageText,
                    user: {
                    name: this.userName
                            }
                });
                this.messageText = '';
            },
        loadSales() {
            this.showroom_ids = this.$parent.showroomsSelected.map(sale => {
                return sale.id
            });
            this.$Progress.start();
            let check = this.checked;
            if(check) {
                var all = 1;
            }else{
                var all = 0;
            }
            
            let dates_para = queryString.stringify({ dates: [this.startDate.slice(0, 10), this.endDate.slice(0, 10)] }, { arrayFormat: 'bracket' });
            let showroomIds = queryString.stringify({ costcenter: this.showroom_ids }, { arrayFormat: 'bracket' });
            axios.get(this.uri + '?' + dates_para + '&' + showroomIds + '&all=' + all)
                .then(responsive => {
                    this.sales = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
        },
        updateValues(values) {
            this.startDate = values.startDate.toISOString().slice(0, 10);
            this.startDate = moment(this.startDate).add(1, 'day').format().slice(0,10);
            this.endDate = values.endDate.toISOString().slice(0, 10);
            this.endDate = moment(this.endDate).endOf('week').format().slice(0,10);
        },
        updateByKHTN() {
            this.uri = 'api/users-target-sr'
        },
        updateByKH15() {
            this.uri = 'api/users-target-leads';
        },
        updateByDH() {
            this.uri = 'api/users-target-order';
        },
        updateByDS() {
            this.uri = 'api/users-target-amount';
        },
        timTheoShowroom() {
        },
        formatPrice(value) {
            let val = (value).toFixed(1).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        formatPrice1(value) {
            let val = (value/1000000).toFixed(1).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        },
        formatdiv(value) {
            let val = (value/1).toFixed(0).replace(',', '.')
            return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".")
        }
    },
    async mounted() {
        this.loadSales();
    },
    created() {
        this.loadSales();
        axios.get('api/users-group-costcenters')
            .then(res => this.costcenters = res.data);
        axios.get('/api/picklists/costcenter-picklists')
            .then(res => this.costcenter = res.data);
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