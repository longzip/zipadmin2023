<template>
    <div class="container">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Tạo báo giá</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/">Home</a></li>
                            <li class="breadcrumb-item active">Tạo báo giá</li>
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
                            Nhân viên kinh doanh cần nhập đầy đủ chính xác thông tin đơn báo giá trước khi lưu đơn hàng lên hệ thống. Nếu không muốn lưu báo giá nhấn <a href="#" @click="quayLai" class="text-success">vào đây</a>
                        </div>
                        <!-- Main content -->
                        <div class="invoice p-3 mb-3">
                            <!-- title row -->
                            <div class="row">
                                <div class="col-12">
                                    <h4>
                                        <i class="fa fa-globe"></i> Nội thất ZIP.
                                        <small class="float-right">Date: {{ homnay }}</small>
                                    </h4>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- info row -->
                            <div class="row invoice-info">
                                <div class="col-sm-6 invoice-col">
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6 invoice-col">
                                    Khách hàng
                                    <address>
                                        <strong>{{lead.first_name}} {{lead.last_name}}</strong><br>
                                        Địa chỉ:{{lead.address}} {{lead.city}}<br>
                                        Điện thoại: <strong>{{lead.phone}}</strong>
                                    </address>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <div class="row">
                                <div class="margin">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addItemModal">
                                        Thêm sản phẩm <i class="fas fa-cart-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- Table row -->
                            <div class="row">
                                <div class="col-12 table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Code</th>
                                                <th>Tên sản phẩm</th>
                                                <th>Số lượng</th>
                                                <th>Đơn giá</th>
                                                <th>Chiết khấu</th>
                                                <th>Thành tiền</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in items.data">
                                                <td>{{ item.code }}</td>
                                                <td>{{ item.name }}</td>
                                                <td>{{ item.quantity }}</td>
                                                <td>{{ item.price | currency }}</td>
                                                <td>{{ item.discount | currency }}</td>
                                                <td>{{ item.thanh_tien | currency }}</td>
                                                <td>
                                                    <button v-on:click="removeItem(item.id)" class="btn btn-sm btn-danger">Xóa</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
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
                                                    Thêm VAT <i class="fas fa-plus-square"></i>
                                                </a>
                                                <a @click="addVat('+0')" class="dropdown-item">
                                                    Xóa VAT <i class="fas fa-plus-square"></i>
                                                </a>
                                                <a @click='clearCartCondition()' class="dropdown-item">
                                                    Xóa phí <i class="fas fa-minus-square"></i>
                                                </a>
                                                <a @click='clearCart()' class="dropdown-item">
                                                    Clear Cart <i class="fas fa-trash-restore"></i>
                                                </a>
                                                <div class="dropdown-divider"></div>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#maGiamGiaModal" data-whatever="@mdo" disabled>Mã giảm giá</button>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#phiVCModal" data-whatever="@fat">Phí VC</button>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#phiLDModal" data-whatever="@getbootstrap">Phí LĐ</button>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                        Nhấn vào nút trên để thêm phí vận chuyển, phí lắp đặt, mã giảm giá trên đơn hàng; thêm VAT.
                                    </p>
                                    <div class="table-responsive">
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-6">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <tr>
                                                <td>Số lượng sản phẩm:</td>
                                                <td>{{ details.total_quantity }}</td>
                                            </tr>
                                            <tr>
                                                <td>Giảm giá:</td>
                                                <td>-{{ details.qgg | currency }}</td>
                                            </tr>
                                            <tr>
                                                <td>Phí vận chuyển:</td>
                                                <td>{{ details.fee_vc | currency }}</td>
                                            </tr>
                                            <tr>
                                                <td>Phí lắp đặt:</td>
                                                <td>{{ details.fee_ld | currency }}</td>
                                            </tr>
                                            <tr>
                                                <td>Thành tiền:</td>
                                                <td>{{ details.sub_total | currency }}</td>
                                            </tr>
                                            <tr>
                                                <td>VAT:</td>
                                                <td>{{ details.vat | currency }}</td>
                                            </tr>
                                            <tr>
                                                <td>Thanh toán:</td>
                                                <td><strong>{{ details.total | currency }}</strong></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <!-- this row will not appear when printing -->
                            <div class="row no-print">
                                <div class="col-12">
                                    <!-- <i class="fas fa-arrow-left"></i> -->
                                    <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                                    <VueLoadingButton aria-label="Tạo báo giá" class="btn btn-primary float-right" @click.native="taobaogia" :loading="isLoading" :styled="false"><i class="fas fa-save"></i> Lưu báo giá</VueLoadingButton>
                                    <VueLoadingButton aria-label="Tạo báo giá" class="btn btn-primary float-right" style="margin-right: 5px;" @click.native="taobaogia" :loading="isLoading" :styled="false"><i class="fa fa-download"></i> Tải về</VueLoadingButton>
                                </div>
                            </div>
                        </div>
                        <!-- /.invoice -->
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
            <div>
            </div>
        </section>
        <div class="row justify-content-center">
            <!-- Modal -->
            <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Thêm sản phẩm vào đơn hàng</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group form-group-sm">
                                <label>Target*</label>
                                <multiselect v-model="selectproduct" :options="products" label="text" track-by="id" placeholder="Chọn sản phẩm"></multiselect>
                            </div>
                            <div class="form-group form-group-sm">
                                <label>Số lượng</label>
                                <input type="number" v-model="item.qty" class="form-control" placeholder="Quantity">
                            </div>
                            <div class="form-group form-group-sm">
                                <label>Chiết khẩu</label>
                                <input v-model="item.discount" class="form-control" placeholder="Quantity">
                                <small class="form-text text-muted">Tính phí vận chuyển, lắp đặt<br>Nhập dấu - để tính giảm giá. Vd: -5%</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button v-on:click="addItem()" type="button" class="btn btn-primary">Thêm</button>
                        </div>
                    </div>
                </div>
            </div>
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
                                    <input v-model="qgg" type="text" class="form-control">
                                    <small>Nhập số tiền giảm giá hoặc % giảm giá cho đơn hàng. Vd: -5%</small>
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
                                    <label for="recipient-name" class="col-form-label">Số tiền vận chuyển: {{fee_vc | currency}}</label>
                                    <input v-model="fee_vc" type="text" class="form-control">
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
                                    <label for="recipient-name" class="col-form-label">Phí lắp đặt: {{fee_ld | currency}}</label>
                                    <input v-model="fee_ld" type="text" class="form-control">
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
<script>
export default {
    props: ['leadId'],
    data() {
        return {
            isLoading: false,
            homnay: moment().format('L'),
            itemCount: 0,
            items: [],
            products: [],
            lead: {},
            details: {},
            item: {
                id: '',
                name: '',
                price: 0,
                qty: 1,
                discount: 0
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
            selectproduct: null,
            qgg: 0,
            fee_ld: 0,
            fee_vc: 0,
            fee_kh: 0
        }
    },

    methods: {
        quayLai (){
            this.$router.go(-1);
        },
        loadItems() {
            axios.get('cart')
                .then(response => {
                    this.loadCartDetails();
                    this.items = response.data;

                })
        },
        loadlead() {
            axios.get('/api/leads/' + this.leadId)
                .then(response => {
                    this.lead = response.data.data;
                })
                .catch(() => this.$router.go(-1));
        },

        taobaogia() {
            this.isLoading = true;
            setTimeout(() => (this.isLoading = false), 3000);
            axios.put('/api/leads/' + this.lead.id + '/quotes', {
                    subject: 'Báo giá ' + moment().format('L'),
                    subtotal: this.details.sub_total,
                    total: this.details.total,
                    qgg: this.details.qgg,
                    fee_vc: this.details.fee_vc,
                    fee_ld: this.details.fee_ld,
                    vat: this.details.vat,
                    productLines: this.items.data
                })
                .then(response => {
                    this.$router.push({ name: 'kh15Show', query: { id: this.leadId } });
                })
                .catch(error => {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });

        },

        removeItem(id) {
            axios.delete('cart/' + id)
                .then(() => {
                    this.loadItems();
                });
        },

        clearCart() {
            axios.get("cart/clear").then(({ data }) => (this.loadItems()));
        },

        loadProducts() {
            axios.get("api/getallproducts").then(({ data }) => (this.products = data));
        },

        loadCartDetails() {
            axios.get("cart/details").then(({ data }) => (this.details = data.data));
        },

        addVat(vat) {
            axios.post("cart/conditions", {
                    name: 'VAT 10%',
                    type: 'VAT',
                    target: 'total',
                    value: vat,
                    order: 2
                })
                .then(response => {
                    this.loadItems();
                });
        },

        addMaGiamGia() {
            axios.post("cart/conditions", {
                    name: 'Mã giảm giá',
                    type: 'QGG',
                    target: 'subtotal',
                    value: '-0' + this.qgg,
                    order: 1
                })
                .then(response => {
                    this.loadItems();
                    $('#maGiamGiaModal').modal('hide')
                });
        },
        addPhiVC() {
            axios.post("cart/conditions", {
                    name: 'Phí vận chuyển',
                    type: 'FEE_VC',
                    target: 'subtotal',
                    value: this.fee_vc,
                    order: 1
                })
                .then(response => {
                    this.loadItems();
                    $('#phiVCModal').modal('hide');
                });
        },
        addPhiLD() {
            axios.post("cart/conditions", {
                    name: 'Phí lắp đặt',
                    type: 'FEE_LD',
                    target: 'subtotal',
                    value: this.fee_ld,
                    order: 1
                })
                .then(response => {
                    this.loadItems();
                    $('#phiLDModal').modal('hide')
                });
        },

        clearCartCondition() {
            axios.delete("cart/conditions")
                .then(response => {
                    this.loadItems();
                });
        },

        addItem() {
            axios.post("cart", {
                    id: this.selectproduct.id,
                    discount: '-0' + this.item.discount,
                    qty: this.item.qty,
                    isDiscount: true,
                    price: this.selectproduct.price
                })
                .then(response => {
                    this.loadItems();
                    $('#addItemModal').modal('hide');
                });
        }
    },

    created() {
        this.$Progress.start()
        this.loadItems()
        this.loadProducts()
        this.loadlead()
        this.$Progress.finish()
    }
}

</script>
