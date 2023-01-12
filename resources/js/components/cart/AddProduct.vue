<template>
    <div class="card card-info">
        <div class="card-body">
            <p>Thêm sản phẩm vào dòng đơn hàng ở hàng dưới. <span class="lead">Phần chiết khấu các bạn nhập thêm dấu - Ví dụ: -15% hoặc nhập số tiền giảm giá Ví dụ: -5000000 </span> </p>
            <div class="form-inline">
                <label>Sản phẩm <a href="#" @click="loadProducts">tải lại</a></label>
                <multiselect v-model="selectproduct" :options="products" label="text" track-by="id" placeholder="Chọn sản phẩm"></multiselect>
                <label>Số lượng</label>
                <input type="number" v-model="item.qty" class="form-control" placeholder="Số lượng">
                <label>Đơn giá</label>
                <input :disabled="item.isDiscount ? '' : disabled" type="number" v-model="item.price" class="form-control" placeholder="Đơn giá">
                <label>Chiết khấu</label>
                <input v-model="item.discount" class="form-control" placeholder="Chiết khấu">
                <button @click.prevent="addItem()" type="button" class="btn btn-primary">Thêm</button>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            item: {
                id: '',
                name: '',
                price: 0,
                qty: 1,
                discount: 0,
                isDiscount: true
            },
            products: [],
            selectproduct: {
                id: '',
                price: '',
                text: ''
            },
        }
    },
    watch: {
        selectproduct: function(nv, ov) {
            this.item.id = nv.id;
            if (nv.id == 359 || nv.id == 422 || nv.id == 423 || nv.id == 424 || nv.id == 425 || nv.id == 426 || nv.id == 427 || nv.id == 478 || nv.id == 479 || nv.id == 489 || nv.id == 490 || nv.id == 491 || nv.id == 492 || nv.id == 481  || nv.id == 496) {
                this.item.isDiscount = false;
            } else {
                this.item.price = nv.price;
                this.this.isDiscount = true;
            }
        }
    },
    methods: {
        loadProducts() {
            axios.get("api/getallproducts").then(({ data }) => (this.products = data));
        },
        addItem() {
            axios.post("cart", this.item)
                .then(response => {
                    this.selectproduct = null;
                    this.resetItem();
                    this.$emit('update');
                });
        }, 
        resetItem(){
            this.item.price = 0;
            this.item.discount = 0;
            this.item.qty = 1;
        }
    },
    created(){
        this.loadProducts();
    }
}

</script>
