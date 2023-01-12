/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');
window.Vue = require('vue');

import moment from 'moment';
import { Form, HasError, AlertError } from 'vform'
import VueCurrencyFilter from 'vue-currency-filter'
import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
import VueRangedatePicker from 'vue-rangedate-picker'
import swal from 'sweetalert2'
import Chart from 'chart.js'
import Vue from 'vue';
import { Tabs, Tab } from 'vue-tabs-component';
import Multiselect from 'vue-multiselect'
import VueUploadComponent from 'vue-upload-component'
import queryString from 'query-string'
import DateRangePicker from 'vue2-daterange-picker'
import VueGallery from 'vue-gallery';
import VueLoadingButton from 'vue-loading-button'
import JsonExcel from 'vue-json-excel'
import CKEditor from '@ckeditor/ckeditor5-vue';
import { VueEditor } from 'vue2-editor'

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});


window.toast = toast;
window.Form = Form;
window.moment = moment;
window.Fire = new Vue();
window.swal = swal;
window.Chart = Chart;
window.queryString = queryString;
moment.locale('vi');
Vue.component('tabs', Tabs);
Vue.component('tab', Tab);
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('vue-rangedate-picker', VueRangedatePicker)
Vue.component('multiselect', Multiselect)
Vue.component('file-upload', VueUploadComponent)
Vue.component('date-range-picker', DateRangePicker)
Vue.component('gallery', VueGallery)
Vue.component('VueLoadingButton', VueLoadingButton)
Vue.component('downloadExcel', JsonExcel)
Vue.use(VueRouter)
Vue.use(VueRangedatePicker)
Vue.use(VueCurrencyFilter)
Vue.use(VueCurrencyFilter, {
    symbol: 'đ',
    thousandsSeparator: '.',
    fractionCount: 0,
    fractionSeparator: ',',
    symbolPosition: 'front',
    symbolSpacing: true
})
Vue.use(CKEditor);

Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '3px'
})

Vue.prototype.$user = window.user

const routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default },
    { path: '/profile', component: require('./components/Profile.vue').default },
    { path: '/users', component: require('./components/Users.vue').default },
    { name: 'taoDon', props: true, path: '/tao-don-hang', component: require('./components/Donhang.vue').default },
    { path: '/khach-hang', component: require('./components/Customers.vue').default },
    { name: 'saleOrder', path: '/don-hang', component: require('./components/Orders.vue').default },
    { path: '/san-pham', component: require('./components/Products.vue').default },
    { path: '/costcenter', component: require('./components/Costcenter.vue').default },
    { name: 'kh15', path: '/kh15', props: true, component: require('./components/lead/Kh15.vue').default },
    { name: 'kh15Show', path: '/thong-tin-kh15', props: true, component: require('./components/lead/Ttkh15.vue').default },
    { path: '/them-kh15', component: require('./components/lead/UpdateKh15.vue').default },
    { path: '/khach-tiem-nang', component: require('./components/contact/Khachtiemnang.vue').default },
    { name: 'ttkhachtiemnang', props: true, path: '/thong-tin-khach-tiem-nang', component: require('./components/contact/Ttkhachtiemnang.vue').default },
    { name: 'taobaogia', props: true, path: '/bao-gia', component: require('./components/Quotes.vue').default },
    { name: 'taobaogialead', props: true, path: '/bao-gia-lead', component: require('./components/LeadQuotes.vue').default },
 
    { path: '/activity-types', component: require('./components/ActivityTypes.vue').default },
    { name: 'createContact', props: true, path: '/them-khach-tiem-nang', component: require('./components/contact/ContactNew.vue').default },
    { name: 'createLead', props: true, path: '/them-khach-kh15', component: require('./components/lead/Kh15New.vue').default },
    { name: 'editContact', props: true, path: '/contacts-edit', component: require('./components/contact/ContactEdit.vue').default },
    { name: 'editLead', props: true, path: '/leads-edit', component: require('./components/lead/Kh15Edit.vue').default },
    { name: 'indexContact', path: '/contacts-index', component: require('./components/contact/ContactIndex.vue').default },
    { name: 'showContact', props: true, path: '/contacts-details', component: require('./components/contact/ContactShow.vue').default },
    { name: 'showQuote', props: true, path: '/quote-details', component: require('./components/quote/QuoteShow.vue').default },
    { path: '/kho-khu-vuc', component: require('./components/warehouse/WarehouseList.vue').default },
    { path: '/them-kho-khu-vuc', component: require('./components/warehouse/WarehouseNew.vue').default },
    { name: 'editWarehouse', path: '/sua-kho-khu-vuc', props: true, component: require('./components/warehouse/WarehouseEdit.vue').default },
    { path: '/khu-vuc', component: require('./components/salesarea/SalesAreaList.vue').default },
    { path: '/them-khu-vuc', component: require('./components/salesarea/SalesAreaNew.vue').default },
    { name: 'editSalesArea', path: '/sua-khu-vuc', props: true, component: require('./components/salesarea/SalesAreaEdit.vue').default },
    //Contact Comments
    { name: 'tasks', path: '/ke-hoach', props: true, component: require('./components/elements/Task.vue').default },
    { name: 'baoCao', path: '/bao-cao', props: true, component: require('./components/BaoCao.vue').default },
    { name: 'baoCao7', path: '/online', props: true, component: require('./components/BaoCao7.vue').default },
    { name: 'baoCao2', path: '/bang-xep-hang', props: true, component: require('./components/BaoCao2New.vue').default },
    { name: 'baoCao3', path: '/bang-xep-hang-user', props: true, component: require('./components/BaoCao3.vue').default },
    { name: 'baoCao4', path: '/bang-target-sales', props: true, component: require('./components/BaoCao4.vue').default },
    { name: 'baoCao5', path: '/bang-target-showroom', props: true, component: require('./components/BaoCao5.vue').default },
    { name: 'baoCao9', path: '/baocao-cham-cong', props: true, component: require('./components/BaoCao9.vue').default },
    { name: 'baoCao10', path: '/bao-cao-ngay', props: true, component: require('./components/BaoCao10.vue').default },
    { name: 'baoCao11', path: '/bao-cao-khtn', props: true, component: require('./components/BaoCao11.vue').default },
    { name: 'baoCao12', path: '/bao-cao-lich-giao', props: true, component: require('./components/BaoCao12.vue').default },
    { name: 'baoCao6', path: '/bao-cao-nam', component: require('./components/BaoCao6.vue').default },
    { name: 'salestarget', path: '/sales-target', props: true, component: require('./components/SalesTarget.vue').default },
    { name: 'costcenterstarget', path: '/costcenters-target', props: true, component: require('./components/CostcentersTarget.vue').default },
    { name: 'salestarget2', path: '/sales-target-2', props: true, component: require('./components/SalesTarget2.vue').default },
    { name: 'costcenterstarget2', path: '/costcenters-target-2', props: true, component: require('./components/CostcentersTarget2.vue').default },
    { name: 'ziptarget2', path: '/zip-target-2', props: true, component: require('./components/ZipTarget2.vue').default },

    { path: '/list-bang-tin', component: require('./components/news/NewsList.vue').default },
    { path: '/them-bang-tin', component: require('./components/news/NewsNew.vue').default },
    { path: '/sua-bang-tin', name: 'editNews', props: true, component: require('./components/news/NewsEdit.vue').default },

    { path: '/doc-thong-bao', name: 'readthongbao', props: true, component: require('./components/news/readthongbao.vue').default },
    { path: '/tin-tong-hop', component: require('./components/news/list/tintonghop.vue').default },
    { path: '/khuyen-mai', component: require('./components/news/list/khuyenmai.vue').default },
    { path: '/thanh-tich', component: require('./components/news/list/thanhtich.vue').default },
    { path: '/du-an', component: require('./components/news/list/duan.vue').default },
    { path: '/danh-gia-khach-hang', component: require('./components/news/list/danhgia.vue').default },
    { path: '/teambuilding', component: require('./components/news/list/team.vue').default },

    { name: 'xemkhuvuc', props: true, path: '/xem-khu-vuc', component: require('./components/khuvucnew.vue').default },

    { path: '/project', component: require('./components/project/Khachduan.vue').default },
    { path: '/dai-ly', component: require('./components/daily/DaiLy.vue').default },
    { name: 'editDaily', props: true, path: '/daily-edit', component: require('./components/daily/DailyEdit.vue').default },
    { name: 'taobaogiaDL', props: true, path: '/bao-gia-dai-ly', component: require('./components/daily/QuotesDaiLy.vue').default },
    { name: 'ttkhachdaily', props: true, path: '/thong-tin-dai-ly', component: require('./components/daily/Ttdaily.vue').default },
    { path: '/them-dai-ly', component: require('./components/daily/ThemDaiLy.vue').default },
    { name: 'createProject', props: true, path: '/them-du-an', component: require('./components/project/ProjectNew.vue').default },
    { name: 'ttkhachduan', props: true, path: '/thong-tin-du-an', component: require('./components/project/Ttkhachduan.vue').default },
    { name: 'editProject', props: true, path: '/project-edit', component: require('./components/project/ProjectEdit.vue').default },
    { name: 'taobaogiaDA', props: true, path: '/bao-gia-du-an', component: require('./components/QuotesProject.vue').default },
    { name: 'showQuoteDA', props: true, path: '/quote-project-details', component: require('./components/quote/QuoteShowDA.vue').default },

    { name: 'quyetdinh', path: '/quyet-dinh', component: require('./components/nhansu/QuyetDinhList.vue').default },
    { name: 'docquyetdinh', path: '/thong-bao', component: require('./components/nhansu/QuyetDinhDoc.vue').default },
    { name: 'previewbienban', path: '/preview-bien-ban', props: true, component: require('./components/nhansu/PreviewBienBan.vue').default },
    { name: 'previewnghiphep', path: '/preview-nghi-phep', props: true, component: require('./components/nhansu/PreviewNghiPhep.vue').default },
    { name: 'quytrinh', path: '/quy-trinh', component: require('./components/nhansu/QuyTrinh.vue').default },
    { name: 'chetai', path: '/che-tai', component: require('./components/nhansu/CheTai.vue').default },
    { name: 'taobienban', path: '/tao-bien-ban', component: require('./components/nhansu/TaoBienBan.vue').default },
    { name: 'suabienban', props: true, path: '/sua-bien-ban', component: require('./components/nhansu/SuaBienBan.vue').default },
    { name: 'vipham', path: '/vi-pham', component: require('./components/nhansu/ViPham.vue').default },
    { name: 'nghiphep', path: '/nghi-phep', component: require('./components/nhansu/NghiPhep.vue').default },
    { name: 'thuctrang', path: '/thuc-trang', component: require('./components/nhansu/ThucTrang.vue').default },

    { name: 'loaichiphi', path: '/loai-chi-phi', component: require('./components/chiphi/LoaiChiPhi.vue').default },
    { name: 'chiphi', path: '/chi-phi', component: require('./components/chiphi/Chi.vue').default },
    { name: 'duchi', path: '/du-chi', component: require('./components/chiphi/DuChi.vue').default },
    { name: 'thu', path: '/thu', component: require('./components/chiphi/Thu.vue').default },
    { name: 'detailquydinh', path: '/detail-quy-dinh', props: true, component: require('./components/nhansu/DetailQuyDinh.vue').default },
    { name: 'detailvipham', path: '/detail-vi-pham', props: true, component: require('./components/nhansu/DetailViPham.vue').default },
    { name: 'detailnghiphep', path: '/detail-nghi-phep', props: true, component: require('./components/nhansu/DetailNghiPhep.vue').default },

    { name: 'data', path: '/data', component: require('./components/data/data.vue').default },
    { name: 'blackfriday', path: '/blackfriday', component: require('./components/data/BlackFriday.vue').default },
    { path: '/khach-online', component: require('./components/data/KhachOnline.vue').default },
    { path: '/chien-dich', component: require('./components/data/ChienDich.vue').default },
    { path: '/tai-lieu', component: require('./components/data/TaiLieu.vue').default },
    { path: '/bang-gia', component: require('./components/data/BangGia.vue').default },

    { path: '/report-product', component: require('./components/ReportProducts.vue').default },
    { path: '/giao-hang', component: require('./components/GiaoHang.vue').default },
    { path: '/khach', component: require('./components/Khach.vue').default },

    { name: 'themmarketing', path: '/them-marketing', props: true, component: require('./components/marketing/them.vue').default },

    { name: 'ttkhachhang', props: true, path: '/thong-tin-khach-hang', component: require('./components/Ttkh.vue').default },
    { name: 'ttnhanvien', props: true, path: '/thong-tin-nhan-vien', component: require('./components/Ttnv.vue').default },

    { name: 'telepro', props: true, path: '/telepro', component: require('./components/data/Telepro.vue').default },
    { name: 'chamcong', props: true, path: '/cham-cong', component: require('./components/nhansu/ChamCong.vue').default },
    { name: 'luongsale', props: true, path: '/bang-luong-sales', component: require('./components/nhansu/LuongSale.vue').default },
    { name: 'luongkhac', props: true, path: '/bang-luong-khac', component: require('./components/nhansu/LuongCungKhac.vue').default },
    { name: 'phucap', props: true, path: '/luong-phu-cap', component: require('./components/nhansu/LuongPhuCap.vue').default },
    { name: 'tuyendung', props: true, path: '/tuyen-dung', component: require('./components/nhansu/TuyenDung.vue').default },
    { name: 'cv', props: true, path: '/cv', component: require('./components/nhansu/CV.vue').default },
    { name: 'baocaouv', props: true, path: '/baocaouv', component: require('./components/nhansu/BaoCao.vue').default },
    { name: 'csvc', props: true, path: '/csvc', component: require('./components/nhansu/CSVC.vue').default },
    { name: 'lichgiao', props: true, path: '/lich-giao', component: require('./components/thicong/LichGiao.vue').default },
    { name: 'baocaothicong', props: true, path: '/bao-cao-thi-cong', component: require('./components/thicong/BaoCao.vue').default },
    { name: 'sanxuat', props: true, path: '/san-xuat', component: require('./components/sanxuat/SanXuat.vue').default },
    { name: 'thongtinkhach', props: true, path: '/thong-tin-khach', component: require('./components/ThongTin.vue').default },
    { name: 'sanphamgiao', props: true, path: '/giao-hang-san-pham', component: require('./components/BaoCaoSanPhamGiao.vue').default },
]

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('comments', require('./components/elements/Comment.vue').default);
Vue.component('products', require('./components/elements/Products.vue').default);
Vue.component('category', require('./components/elements/Category.vue').default);
Vue.component('order-list', require('./components/elements/OrderLists.vue').default);
Vue.component('line-chart', require('./components/elements/LineChart.vue').default);
Vue.component('pie-chart', require('./components/elements/PieChart.vue').default);
Vue.component('bar-chart', require('./components/elements/HorizontalBarChart.vue').default);
Vue.filter('capitalize', function(value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
});

Vue.filter('myDate', function(created) {
    return moment(created).format('L');
});

Vue.filter('myNumber', function(number) {
    if (number == 0) { return '-' }
    return number;
});

Vue.filter('formatCN', function(text) {
    if (text.slice(0, 2) == 'CN') { return 'bg-cn' }
    
});

Vue.filter('myShortDate', function(created) {
    let myString = moment(created).format('llll');
    return myString.slice(0, 3);
});

Vue.filter('formatPhone', function(created) {
    return created.slice(0, 3) + 'xxx' + created.slice(-3);
});

Vue.filter('myDateN', function(created) {
    return moment(created).fromNow();
});

Vue.filter('myDateTime', function(created) {
    return moment(created).format('lll');
});

Vue.filter('myTime', function(created) {
    return moment(created, 'H').format('LT');;
});

Vue.filter('tinhPhiVC', function(created) {
    return created * 11000;
});

Vue.filter('numberamount', function(created) {
    return created / 1000000;
});

Vue.filter('day', function(created) {
    if(created == 0) {
        return '.';
    }else {
        return created;
    }
});

Vue.filter('formatDate', function(value) {
  if (value) {
    return moment(String(value)).format('MM/DD/YYYY hh:mm');
  }
});

Vue.filter('trangThaiSO', function(status) {

    if (status == 2) {
        return 'Đã duyệt'
    }
    return 'Đang duyệt';
});
Vue.filter('ContactStatus', function(status) {

    if (!status) {
        return "Đang chăm sóc"
    }
    switch (status) {
        case 1:
            return "Đang chăm sóc";
            break;
        case 2:
            return "Tạm dừng";
            break;
        case 3:
            return "Gần đơn hàng";
            break;
        case 4:
            return "Đơn hàng chờ";
            break;
        case 5:
            return "Đơn hàng";
            break;
        case 8:
            return "Tiềm Năng Xa";
            break;
        case 6:
            return "Gọi Điện";
            break;
        case 7:
            return "Có Cuộc Hẹn Tại Nhà";
            break;
        case 9:
            return "Có Cuộc Hẹn Tại Showroom";
            break;
        case 10:
            return "Cuộc Hẹn Tại Nhà";
            break;
        case 11:
            return "Cuộc Hẹn Tại Showroom";
    }
});



Vue.filter('completed', function(status) {

    if (status == 1) {
        return 'Hoàn thành'
    }
    return 'Mới tạo';
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    router,
    data: {
        search: '',
        Selectednhanvien: '',
        chiendich: '',
        chiendichsanpham: '',
        showroomsSelected: [],
        saleSelected: [],
        khuvucSelected:[],
        saleSelectednhanvien: '',
        saleSelectedbophan:'',
        testSelected:[],
        statuss:[
            {index:'chưa đánh giá', value: '0'},
            {index:'ghi nhận', value: '1'},
            {index:'chờ lập biên bản', value: '2'},
            {index:'chờ duyệt', value: '3'},
            {index:'chờ gửi mail', value: '4'},
            {index:'đã gửi mail', value: '5'},
            {index:'tất cả', value: ''},
        ],
        position:[
            {index:'Văn Phòng Miền Bắc', value: '1'},
            {index:'Văn Phòng Miền Nam', value: '2'},
            // {index:'Nhà Máy', value: '3'},
            {index:'Sales', value: '4'},
            {index:'CTV', value: '5'},
        ],
        warehouse:[
            {index:'Miền Bắc', value: 'A_MB'},
            {index:'Miền Nam', value: 'A_MN'},
            {index:'Miền Trung', value: 'A_MT'},
        ],
        year:[
            {nam:'Z9', value: '9'},
            {nam:'Z10', value: '10'},
            {nam:'Z11', value: '11'},
            {nam:'Z12', value: '12'},
            {nam:'Z13', value: '13'},
            {nam:'Z14', value: '14'},
            {nam:'Z15', value: '15'},
            {nam:'Z16', value: '16'},
            {nam:'Z17', value: '17'},
        ],

        i:[
            {quy:'1', value: '1'},
            {quy:'2', value: '2'},
            {quy:'3', value: '3'},
            {quy:'4', value: '4'},
        ],

        pt:[
            {index:'ALL', value: '0'},
            {index:'1', value: '1'},
            {index:'2', value: '2'},
            {index:'3', value: '3'},
            {index:'4', value: '4'},
            {index:'5', value: '5'},
            {index:'6', value: '6'},
            {index:'7', value: '7'},
            {index:'8', value: '8'},
            {index:'9', value: '9'},
            {index:'10', value: '10'},
            {index:'11', value: '11'},
            {index:'12', value: '12'},
            {index:'13', value: '13'},
        ],

        ttchiphi:[
            {index:'0', value: 'Kế Toán Đang Duyệt'},
            {index:'1', value: 'Kế Toán Đã Duyệt'},
            {index:'2', value: 'Chờ Chi'},
            {index:'3', value: 'Đã Chi'},
        ],

        status: [
            { text: 'Đang chăm sóc', value: '1' },
            { text: 'Tạm dừng', value: '2' },
            { text: 'Gần đơn hàng', value: '3' },
            { text: 'ĐH chờ', value: '4' },
            { text: 'Đơn hàng', value: '5' },
            { text: 'Gọi Điện', value: '6'},
            { text: 'Có Cuộc Hẹn Tại Nhà', value: '7'},
            { text: 'Tiềm Năng Xa', value: '8'},
            { text: 'Có Cuộc Hẹn Tại Showroom', value: '9'},
            { text: 'Cuộc Hẹn Tại Nhà', value: '10'},
            { text: 'Cuộc Hẹn Tại Showroom', value: '11'},
        ],

        nguon: [
            { text: 'Data', value: '1' },
            { text: 'Marketing', value: '2' },
        ],

        giaidoan: [
            { text: 'B1', value: '1' },
            { text: 'B2', value: '2' },
            { text: 'C1', value: '3' },
            { text: 'C2', value: '4' },
            { text: 'D', value: '5' },
            { text: 'Tạm Dừng', value: '6' },
            
        ],

        p: [
            { text: 'Ivy', value: '29'},
            { text: 'IRIS', value: '2' },
            { text: 'DAHLIA', value: '1' },
            { text: 'DAISY', value: '52' },
            { text: 'MELON', value: '46' },
            { text: 'LYCHEE', value: '40' },            
            { text: 'ZICO', value: '1000' },
            { text: 'BERRY', value: '45' },
            { text: 'WILLOW', value: '31' },
            { text: 'OLIVE', value: '32' },
            { text: 'TAB', value: '30' },
            { text: 'PANDA', value: '3' },
            { text: 'PANSY', value: '4' },
            { text: 'DOLPHIN', value: '5' },
            { text: 'DOUBLE', value: '6'},
            { text: 'LEMON', value: '7'},
            { text: 'KIWI', value: '8'},
            { text: 'WINDY', value: '9'},
            { text: 'TULIP', value: '10'},
            { text: 'ZITA', value: '11'},
            { text: 'DON', value: '12'},
            { text: 'APPLE', value: '13'},
            { text: 'CAMELIA', value: '14'},
            { text: 'GIASACH', value: '15'},
            { text: 'LOTUS', value: '16'},
            { text: 'SLIDING', value: '17'},
            { text: 'HOC_DI_DONG', value: '18'},
            { text: 'FUTON', value: '19'},
            { text: 'KTV', value: '20'},
            { text: 'LADER', value: '21'},
            { text: 'SINGLE', value: '22'},
            { text: 'SKY', value: '23'},
            { text: 'SUNFLOWER', value: '24'},
            { text: 'SUNNY', value: '25'},
            { text: 'GA', value: '26'},
            { text: 'Vỏ Gối', value: '27'},
            { text: 'Đệm', value: '28'},
        ],
        startDate: moment().subtract(30, 'days').format(),
        endDate: moment().format(),
        selectedDate: {
            startDate: moment().subtract(30, 'days').format(),
            endDate: moment().format()
        },
    },
    methods: {
        searchit: _.debounce(() => {
            Fire.$emit('searching');
        }, 1000),

        printme() {
            window.print();
        }
    }
});
