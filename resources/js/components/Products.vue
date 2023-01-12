<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Sản phẩm</h3>
                <div class="card-tools">
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Mã Sản phẩm</th>
                        <th>Tên Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Điểm thưởng</th>
                        <th>Sửa ngày</th>
                    </tr>


                    <tr v-for="product in products.data" :key="product.id">

                        <td>{{product.id}}</td>
                        <td>{{product.code}}</td>
                        <td>{{product.name}}</td>
                        <td>{{product.price | currency}}</td>
                        <td>{{product.point | currency}}</td>
                        <td>{{product.updated_at | myDate}}</td>

                    </tr>
                </tbody></table>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <pagination :data="products" @pagination-change-page="getResults"></pagination>
          </div>
      </div>
      <!-- /.card -->
  </div>
</div>
</div>
</template>

<script>
    export default {
        data() {
            return {
                products : {}
            }
        },

        methods: {
            getResults(page = 1) {
                axios.get('api/products?page=' + page)
                    .then(response => {
                        this.products = response.data;
                });
            },
        },

        mounted() {
            this.getResults();
        }
    }
</script>
