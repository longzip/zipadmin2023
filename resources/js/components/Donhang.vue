<template>
    <div class="container">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tạo đơn hàng</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">Tạo đơn hàng</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="callout callout-info">
                            <h5><i class="fa fa-info"></i> Ghi chú:</h5>
                            Nhân viên kinh doanh cần nhập đầy đủ chính xác thông tin đơn hàng trước khi lưu đơn hàng lên hệ thống.
                        </div>
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>
                        <!-- Main content -->
                        <div class="invoice p-3 mb-3">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-12">
                                    <h4>
                                        <i class="fa fa-globe"></i> Nội thất ZIP.
                                        <small class="float-right">Date:</small>
                                    </h4>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-6 invoice-col">
                                    <div class="form-group form-group-sm">
                                        <label>Tên khách hàng</label>
                                        <input type="text" v-model="name" class="form-control" placeholder="Tên khách hàng">
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label>Điện thoại</label>
                                        <input type="text" v-model="phone" @keyup="searchCustomer" class="form-control" placeholder="Số điện thoại">
                                        <span>Nhập số điện thoại chính của khách hàng</span>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label>Địa chỉ</label>
                                        <input type="text" v-model="address" class="form-control" placeholder="Địa chỉ">
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label>Đơn Ivy</label>
                                        <input  type="checkbox" v-model="order.ivy">
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label>Đơn thường</label>
                                        <input  type="checkbox" v-model="order.black">
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6 invoice-col">
                                    <div class="form-group form-group-sm">
                                        <label>Chọn loại đơn hàng:</label>
                                        <select v-model="order.type">
                                            <option disabled value="">Loại đơn hàng</option>
                                            <option>TC</option>
                                            <option>TK</option>
                                            <option>TL</option>
                                            <option>TIKI</option>
                                        </select>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label>Số đơn hàng (SO Number)</label>
                                        <input type="number" v-model="order.so_number" @keyup="searchOrder" class="form-control" placeholder="Nhập số đơn hàng">
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label>Chọn showroom <a @click="loadCostcenter" href="#">(tải lại)</a></label></label>
                                        <select v-model="order.costcenter_id" class="form-control">
                                            <option v-for="costcenter in costcenters" :key="costcenter.id" :value="costcenter.id">
                                                @{{ costcenter.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label>Chọn showroom Chia<a @click="loadCostcenter" href="#">(tải lại)</a></label></label>
                                        <select v-model="order.costcenter_chia" class="form-control">
                                            <option v-for="costcenter in costcenters" :key="costcenter.id" :value="costcenter.id">
                                                @{{ costcenter.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Chọn tỉnh thành giao hàng <a @click="loadWarehouse" href="#">(tải lại)</a></label>
                                        <multiselect v-model="order.warehouse" deselect-label="Can't remove this value" track-by="id" label="city" placeholder="Select one" :options="warehouses" :searchable="true" :allow-empty="false">
                                        </multiselect>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <add-product @update="loadItems"></add-product>
                            
                            <cart-item :items="items.data" @remove="removeItem"></cart-item>

                            <div class="row">
                                <!-- accepted payments column -->
                                <div class="col-6">
                                    <div class="margin">
                                        <div class="btn-group ">
                                            <button type="button" class="btn btn-info btn-flat">Thanh toán</button>
                                            <button type="button" class="btn btn-info btn-flat dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(67px, 38px, 0px);">
                                                <a @click="addVat('10%')" class="dropdown-item">
                                                    Thêm VAT <i class="fa fa-plus"></i>
                                                </a>
                                                <a @click="addVat('+0')" class="dropdown-item">
                                                    Xóa VAT <i class="fa fa-plus"></i>
                                                </a>
                                                <a @click='clearCartCondition()' class="dropdown-item">
                                                    Xóa phí <i class="fa fa-minus"></i>
                                                </a>
                                                <a @click='clearCart()' class="dropdown-item">
                                                    Clear Cart <i class="fa fa-trash"></i>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#maGiamGiaModal" data-whatever="@mdo">Mã giảm giá</button>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#phiVCModal" data-whatever="@fat">Phí VC</button>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#phiLDModal" data-whatever="@getbootstrap">Phí LĐ</button>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                        Nhấn vào nút trên để thêm phí vận chuyển, phí lắp đặt, mã giảm giá trên đơn hàng; thêm VAT.
                                    </p>
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td>Khách đặt cọc số tiền: <br>{{ order.pre_pay | currency }} Đồng</td>
                                                <td><input type="number" v-model="v" class="form-control" placeholder="Khách đặt cọc"></td>
                                            </tr>
                                            <tr>
                                                <td>Khách đặt cọc (50%): <br>{{ p }} %</td>
                                                <td><input disabled="true" type="number" v-model="p" class="form-control" placeholder="Khách đặt cọc"></td>
                                            </tr>
                                            <tr>
                                                <td>Còn lại:</td>
                                                <td><strong>{{ s | currency }} Đồng</strong></td>
                                            </tr>
                                            <tr>
                                                <td>Ngày đơn hàng:{{ order.start_date | myDate }}</td>
                                                <td><input type="date" v-model="order.start_date"></td>
                                            </tr>
                                            <tr>
                                                <td>Ngày giao hàng:{{ order.delivery_date | myDate }}</td>
                                                <td><input type="date" v-model="order.delivery_date"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <cart-detail :details="details"></cart-detail>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <div class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        Ghi chú
                                        <small>về đơn hàng</small>
                                    </h3>
                                    <!-- tools box -->
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fa fa-minus"></i></button>
                                        <button type="button" class="btn btn-tool btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                                            <i class="fa fa-times"></i></button>
                                    </div>
                                    <!-- /. tools -->
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body pad">
                                    <div class="mb-3">
                                        <textarea class="textarea" v-model="order.note" placeholder="Nhập ghi chú tại đây" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                                    </div>
                                    <p class="text-sm mb-0">
                                        <a href="#">Tại sao phải ghi chú cho đơn hàng.</a>
                                    </p>
                                </div>
                            </div>
                            <!-- this row will not appear when printing -->
                            <div class="row no-print">
                                <div class="col-12">
                                    <VueLoadingButton style="margin-right: 5px;" aria-label="Post message" class="btn btn-primary float-right" @click.native="taoDonHang" :loading="isLoading" :styled="false"><i class="fa fa-download"></i> Lưu đơn hàng</VueLoadingButton>
                                </div>
                            </div>
                        </div>
                        <!-- /.invoice -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </section>
        <div class="row justify-content-center">
            <div class="modal fade" id="maGiamGiaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Mã giảm giá</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Số tiền giảm giá:</label>
                                    <input v-model="order.qgg" type="text" class="form-control">
                                    <small>Nhập số tiền giảm giá hoặc % giảm giá cho đơn hàng. Vd: -5% hoặc -500000</small>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button @click='addMaGiamGia()' type="button" class="btn btn-primary">Thêm</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="phiVCModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Phí vận chuyển</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Số tiền vận chuyển: {{order.fee_vc | currency}}</label>
                                    <input v-model="order.fee_vc" type="text" class="form-control">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button @click='addPhiVC()' type="button" class="btn btn-primary">Thêm</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="phiLDModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Phí lắp đặt</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label for="recipient-name" class="col-form-label">Phí lắp đặt: {{order.fee_ld | currency}}</label>
                                    <input v-model="order.fee_ld" type="text" class="form-control">
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button @click='addPhiLD()' type="button" class="btn btn-primary">Thêm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style type="text/css" lang="scss">
    .trangthai{
        display: flex;
        select{
            margin-left: 10px;
        }
    }
</style>
<script>
import CartDetail from './cart/Detail.vue';
import CartItem from './cart/Item.vue';
import AddProduct from './cart/AddProduct.vue';
export default {
    components: { CartDetail, CartItem, AddProduct },
    props: ['name', 'phone', 'address'],
    data() {
        return {
            isLoading: false,
            itemCount: 0,
            items: [],
            products: [],
            customer: {
                address_line1: '',
                name: '',
                phone: '',
            },
            profile: {},
            details: {},
            ckLabel: 'Chiết khấu:',
            isDiscount: true,
            item: {
                id: '',
                name: '',
                price: 0,
                qty: 1,
                discount: 0
            },
            order: {
                ivy: false,
                black: false,
                address: '',
                costcenter_id: '',
                costcenter_chia: '',
                start_date: '',
                delivery_date: '',
                fee_ld: '',
                fee_vc: '',
                journal_id: '',
                name: '',
                pre_pay: '',
                qgg: '',
                so_number: '',
                type: 'TC',
                warehouse: [],
                note: '',
            },
            cartCondition: {
                name: '',
                type: '',
                target: '',
                value: '',
                attributes: {
                    description: 'Value Added Tax'
                }
            },
            t: 0,
            p: 50,
            v: 0,
            s: 0,
            errors: [],
            selectproduct: {
                id: '',
                price: '',
                text: ''
            },
            options: {
                journals: [
                    { label: 'Tiền mặt', key: '600' },
                    { label: 'Chuyển khoản', key: '601' }
                ],
                target: [
                    { label: 'Apply to SubTotal', key: 'subtotal' },
                    { label: 'Apply to Total', key: 'total' }
                ],
                soType: [
                    { label: 'Đơn hàng tiêu chuẩn', key: '1' },
                ]
            },
            costcenters: [],
            warehouses: []
        }
    },
    watch: {
        p: function(nv, ov) {
            //this.v = this.t * nv / 100;
        },
        t: function(nv, ov) {
            this.order.pre_pay = this.v = Math.round(nv * this.p / 100);

        },
        v: function(nv, ov) {
            //this.t = Math.round(nv / this.p * 100);
            this.s = this.t - nv;
            this.order.pre_pay = nv;
            this.p = (nv / this.t) * 100;
        },
        selectproduct: function(nv, ov) {
            if (nv.id == 359) {
                this.isDiscount = false;
            } else {
                this.item.price = nv.price;
                this.isDiscount = true;
            }
        }
    },

    methods: {
        loadItems() {
            var _this = this;
            axios.get('cart')
                .then(function(data) {
                    _this.items = data.data;
                    _this.loadCartDetails();
                })
                .catch(function(error) {
                    // handle error
                    console.log(error);
                })
                .then(function() {
                    // always executed
                });
        },
        loadCustomer() {
            axios.get('/cart/customer')
                .then(response => {
                    this.phone = response.data.phone;
                    this.name = response.data.name;
                    this.address = response.data.address_line1;
                })
                .catch(function(error) {
                    // handle error
                    console.log(error);
                })
                .then(function() {
                    // always executed
                });
        },
        loadOrder() {
            var _this = this;
            axios.get('/cart/order')
                .then(response => {
                    this.order.type = response.data.type;
                })
                .catch(function(error) {
                    // handle error
                    console.log(error);
                })
                .then(function() {
                    // always executed
                });
        },
        loadProfile() {
            var _this = this;
            axios.get('/api/profile')
                .then(function(data) {
                    _this.profile = data.data;
                })
                .catch(function(error) {
                    // handle error
                    console.log(error);
                })
                .then(function() {
                    // always executed
                });
        },
        searchCustomer: _.debounce(() => {
            Fire.$emit('searchphone');
        }, 1000),
        searchOrder: _.debounce(() => {
            Fire.$emit('searchso');
        }, 1000),

        showCustomer() {
            $('#customerModal').modal('show');
        },

        showOrder() {
            $('#orderModal').modal('show');
        },

        xemDonHang() {
            $('#checkoutModal').modal('hide');
            $('#SOModalLong').modal('show');
        },

        taoDonHang() {
            this.isLoading = true;
            this.errors = [];
            setTimeout(() => (this.isLoading = false), 3000);
            let check = this.order.black;
            let ivy = this.order.ivy;
            console.log(check);
            console.log(ivy);
            console.log(this.order);
            var _this = this;
            var giao = 0;
            if(check) {
                var giao = 4;
            }
            if(ivy) {
                var giao = 5;
            }
            console.log(giao);
            axios.post('cart/checkout', {
                    so_number: _this.order.so_number,
                    phone: _this.phone,
                    name: _this.name,
                    address: _this.address,                    
                    black: giao,
                    delivery: _this.order.delivery_date,
                    start_date: _this.order.start_date,
                    customer_id: _this.customer.id,
                    soType: _this.order.type,
                    pre_pay: _this.order.pre_pay,
                    journal_id: _this.order.journal_id,
                    qgg: _this.details.qgg,
                    fee_vc: _this.details.fee_vc,
                    fee_ld: _this.details.fee_ld,
                    costcenter_id: _this.order.costcenter_id,
                    costcenter_chia: _this.order.costcenter_chia,
                    warehouse_id: this.order.warehouse.id,
                    note: _this.order.note,
                })
                .then(function(response) {
                    $('#checkoutModal').modal('hide');
                    $('#SOModalLong').modal('hide');
                    axios.get('/quotes/session');
                    location.replace("/don-hang")

                })
                .catch(error => {
                    if (typeof error.response.data === 'object') {
                        this.errors = _.flatten(_.toArray(error.response.data.message));
                    } else {
                        this.errors = ['Something went wrong. Please try again.'];
                    }
                    swal.fire("Failed!", "Thiếu thông tin đơn hàng hoặc số điện thoại khách hàng chưa chính xác", "warning");
                });
        },

        removeItem(item) {
            var _this = this;
            axios.delete('cart/' + item.id)
                .then(function(data) {
                    _this.loadItems();
                })
                .catch(function(error) {
                    // handle error
                    console.log(error);
                })
                .then(function() {
                    // always executed
                });
        },

        clearCart() {
            axios.get("cart/clear").then(({ data }) => (this.loadItems()));
            //location.replace("/don-hang")
        },

        loadProducts() {
            axios.get("api/getallproducts").then(({ data }) => (this.products = data));
        },

        loadCartDetails() {
            var _this = this;
            axios.get("cart/details").then(response => {
                _this.details = response.data.data;
                this.t = response.data.data.total;
            });
        },

        addVat(vat) {
            var _this = this;
            axios.post("cart/conditions", {
                    name: 'VAT 10%',
                    type: 'VAT',
                    target: 'total',
                    value: vat,
                    order: 2
                })
                .then(response => {
                    _this.loadItems();
                });
        },

        addMaGiamGia() {
            var _this = this;
            axios.post("cart/conditions", {
                    name: 'Mã giảm giá',
                    type: 'QGG',
                    target: 'subtotal',
                    value: this.order.qgg,
                    order: 1
                })
                .then(response => {
                    _this.loadItems();
                    $('#maGiamGiaModal').modal('hide')
                });
        },
        addPhiVC() {
            var _this = this;
            axios.post("cart/conditions", {
                    name: 'Phí vận chuyển',
                    type: 'FEE_VC',
                    target: 'subtotal',
                    value: this.order.fee_vc,
                    order: 1
                })
                .then(response => {
                    _this.loadItems();
                    $('#phiVCModal').modal('hide');
                });
        },
        addPhiLD() {
            var _this = this;
            axios.post("cart/conditions", {
                    name: 'Phí lắp đặt',
                    type: 'FEE_LD',
                    target: 'subtotal',
                    value: this.order.fee_ld,
                    order: 1
                })
                .then(response => {
                    _this.loadItems();
                    $('#phiLDModal').modal('hide')
                });
        },

        clearCartCondition() {
            var _this = this;
            axios.delete("cart/conditions")
                .then(response => {
                    _this.loadItems();
                });
        },

        addItem(products) {
            axios.post("cart", {
                    id: _this.selectproduct.id,
                    discount: _this.item.discount,
                    qty: _this.item.qty,
                    isDiscount: this.isDiscount,
                    price: this.item.price
                })
                .then(response => {
                    _this.loadItems();
                    _this.selectproduct = null;
                    _this.item.price = '';
                    _this.item.discount = 0;
                    _this.item.qty = 1;
                });

        },

        loadWarehouse() {
            axios.get('/api/cities-list')
                .then(res => this.warehouses = res.data);
        },
        loadCostcenter() {
            axios.get('/api/picklists/costcenter-picklistss')
                .then(res => {
                    this.costcenters = res.data;
                    this.order.costcenter_id = this.costcenters[0]['id'];
                });
        }
    },

    created() {
        this.order.delivery_date = moment().add(10, 'days').format().slice(0, 10);
        this.order.start_date = moment().format().slice(0, 10);
        this.loadCostcenter();
        this.loadWarehouse();
        this.$Progress.start()
        Fire.$on('searchphone', () => {
            let phone = this.phone;
            axios.get('api/findCustomer?phone=' + phone)
                .then(response => {
                    this.name = response.data.name;
                    this.address = response.data.address_line1;
                })
                .catch(() => {

                })
        });
        Fire.$on('searchso', () => {
            let so = this.order.so_number;
            axios.get('api/findorder?so=' + so)
                .then((data) => {
                    console.log(data);
                })
                .catch(() => {

                })
        });
    },

    mounted() {
        this.loadItems()
        this.loadProducts()
        this.loadCustomer()
        this.loadOrder()
        this.loadProfile()
        this.$Progress.finish()
    }
}

</script>
