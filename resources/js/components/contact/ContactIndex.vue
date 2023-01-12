<template>
    <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Chọn</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- <search-showroom></search-showroom> -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Chọn Showroom</label>
                            <multiselect v-model="showroomsSelected" :options="costcenters" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Pick some" label="name" track-by="id" :preselect-first="true" @select="loadContactbyShowroom">
                                <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} đã chọn</span></template>
                            </multiselect>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Chọn nhân viên kinh doanh</label>
                            <multiselect v-model="saleSelected" :options="resources" placeholder="Chọn KD" label="last_name" track-by="user_id"></multiselect>
                        </div>
                        <!-- /.form-group -->
                    </div>
                    <!-- /.col -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Khách hàng Tiềm Năng</div>
                <div class="card-tools">
                    <!-- <router-link :to="{name: 'createContact'}" class="btn btn-success">Tạo mới</router-link> -->
                </div>
            </div>
            <div class="card-body">
                <contact-list v-bind:contacts="contacts"></contact-list>
            </div>
            <div class="card-footer">
                <!-- <pagination :data="contacts" @pagination-change-page="loadContacts"></pagination> -->
            </div>
        </div>
    </div>
</template>
<script>
import ContactList from '../elements/ContactLists.vue';
import SearchShowroom from '../elements/SearchShowroom.vue';
export default {
    components: { ContactList, SearchShowroom },
    data() {
        return {
            contacts: {},
            showroomsSelected: [],
            costcenters: [],
            saleSelected: {},
            resources: []
        }
    },
    methods: {
        loadContacts(page = 1) {
            axios.get("/api/contacts?page=" + page)
                .then(response => {
                    this.contacts = response.data;
                })
        },
        loadContactbyShowroom(){
            axios.put("/api/searchcontacts", this.showroomsSelected)
            .then(response => this.contacts = response.data);
        }
    },
    mounted() {
        console.log(this.contacts.data)
    },
    computed: {
        getcontact() {
            return this.contacts.data;
        }
    },
    created() {
        this.loadContacts();
        axios.get('/api/picklists/costcenter-picklists')
        .then(res => this.costcenters = res.data);
        axios.get('/api/picklists/resource-picklists')
        .then(res => this.resources = res.data);
    }
}

</script>
