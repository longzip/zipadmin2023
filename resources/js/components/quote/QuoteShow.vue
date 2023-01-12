<template>
    <div class="container-flush">
        <div class="row">
            <div class="col-12">
                <div class="callout callout-info">
                    <h5><i class="fa fa-info"></i> Note:</h5>
                    This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
                </div>
                <!-- Main content -->
                <div class="invoice p-3 mb-3">
                    <!-- title row -->
                    <div class="row">
                        <div class="col-12">
                            <h4>
                                <i class="fa fa-globe"></i> Nội Thất Zip.
                                <small class="float-right">Ngày báo giá: {{ quote.created_at | myDate }}</small>
                            </h4>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            Từ
                            <address>
                                <strong>ZIP.</strong><br>
                                Số 5, lô A, ngõ 172 phố Vũ Hữu<br>
                                quận Thanh Xuân, Hà Nội<br>
                                Phone: (84) 4 3543 0021<br>
                                Email: info@noithatzip.com
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            Đến
                            <address>
                                <strong>{{ quote.contact_name }}</strong><br>
                                {{ quote.contact_address }}<br>
                                <br>
                                Phone: {{ quote.contact_phone }}<br>
                                Email: {{ quote.contact_email }}
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b>Báo giá: #{{ quote.id }}</b><br>
                            <br>
                            {{ quote.subject }}<br>
                            <b>Giá trị đến:</b> {{ quote.validtill}}<br>
                            <b>Nhân viên:</b> {{ quote.creator_name}} - {{ quote.creator_id}}
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                    <!-- Table row -->
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tên sản phẩm</th>
                                        <th>Mã</th>
                                        <th>Đơn giá</th>
                                        <th>Số lượng</th>
                                        <th>Chiết khấu</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="productLine in productLines.data">
                                        <td>{{productLine.name}}</td>
                                        <td>{{productLine.code}}</td>
                                        <td>{{productLine.price | currency}}</td>
                                        <td>{{productLine.quantity}}</td>
                                        <td>{{productLine.discount | currency}}</td>
                                        <td>{{productLine.amount | currency}}</td>
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
                            <p class="lead">Payment Methods:</p>
                            <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                                Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                                plugg
                                dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
                            </p>
                        </div>
                        <!-- /.col -->
                        <div class="col-6">
                            <p class="lead">Giá trị đến {{ quote.validtill}}</p>
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th style="width:50%">Tổng tiền:</th>
                                        <td>{{ quote.subtotal | currency}}</td>
                                    </tr>
                                    <tr>
                                        <th>Quỹ giảm giá</th>
                                        <td>{{ quote.qgg | currency }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phí vận chuyển</th>
                                        <td>{{ quote.fee_vc | currency }}</td>
                                    </tr>
                                    <tr>
                                        <th>Phí lắp đặt</th>
                                        <td>{{ quote.fee_ld | currency }}</td>
                                    </tr>
                                    <tr>
                                        <th>VAT (10%)</th>
                                        <td>{{ quote.vat | currency }}</td>
                                    </tr>
                                    <tr>
                                        <th>Thanh toán:</th>
                                        <td>{{ quote.total | currency }}</td>
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
                            <a v-bind:href="print(quote)" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a>
                            <!-- 
                            <button type="button" class="btn btn-success float-right"><i class="fa fa-credit-card"></i> Submit
                                Payment
                            </button>
                            <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                                <i class="fa fa-download"></i> Generate PDF
                            </button> -->
                            <button @click="convertToOrder(quote)" type="button" class="btn btn-success float-right"><i class="fa fa-credit-card"></i> Chuyển đơn hàng
                                </button>
                        </div>
                    </div>
                </div>
                <!-- /.invoice -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div>
</template>
<script>
export default {
    props: ['quoteId'],
    data() {

        return {
            quote: {
                contact_address: '',
                contact_city: '',
                contact_email: '',
                contact_id: '',
                contact_name: '',
                contact_phone: '',
                creator_id: '',
                creator_name: '',
                qgg: '',
                fee_vc: '',
                fee_ld: '',
                vat: '',
                id: '',
                pre_tax_total: '',
                subject: '',
                subtotal: '',
                total: ''
            },
            productLines: []
        }
    },
    methods: {
        convertToOrder(quote) {
            axios.put('/quotes/session/' + quote.id)
                .then(() => {
                    this.$router.replace({ name: 'taoDon', params: { name: this.quote.contact_name, phone: this.quote.contact_phone, address: this.quote.contact_address } })
                    //location.replace('/tao-don-hang');
                    
                });
        },
        print(quote){
            if (!quote) return "#"
                return '/quotes/' + quote.id +'/print';
        }
    },
    created() {
        axios.get('api/quotes/' + this.quoteId)
            .then(res => {
                this.quote = res.data.data;
            })
            .catch(() => this.$router.go(-1));
        axios.get('api/quotes/' + this.quoteId + '/product-lines')
            .then(res => {
                this.productLines = res.data;
            });
    }
}

</script>
