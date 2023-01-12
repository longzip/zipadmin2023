<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 mt-5">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Khách hàng</h3>

            <div class="card-tools"></div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover">
              <tbody>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>User Name</th>
                  <th>Registered At</th>
                  <th>Modify</th>
                </tr>
                <tr v-for="customer in customers.data" :key="customer.customer_id">

                  <td>{{customer.id}}</td>
                  <td>{{customer.name}}</td>
                  <td>{{customer.phone}}</td>
                  <td>{{customer.address_line1}}</td>
                  <td>{{customer.created_at | myDate}}</td>

                  <td>
                    <a href="#" @click="editModal(user)">
                      <i class="fa fa-edit blue"></i>
                    </a>
                    /
                    <a href="#" @click="deleteUser(user.id)">
                      <i class="fa fa-trash red"></i>
                    </a>

                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
              <pagination :data="customers" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        customers : {}
      }
    },
    mounted() {
      // Fetch initial results
      this.getResults();
      this.$Progress.finish()
    },
    methods: {
      loadCustomers(){
        axios.get("api/customers").then(({ data }) => (this.customers = data));
      },
      // Our method to GET results from a Laravel endpoint
      getResults(page = 1) {
        axios.get('api/customers?page=' + page)
          .then(response => {
            this.customers = response.data;
          });
      },

    },
    created () {
      this.$Progress.start()
    }
  }
</script>
