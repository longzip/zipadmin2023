<template>
    <div class="container">
        <div class="card">
            <div class="card-header p-2">
                <div class="card-title">{{ category.name }}</div>
            </div><!-- /.card-header -->
            <div class="card-body">
                <tabs>
                    <tab name="KH Tiềm Năng">
                        <contact-lists :contacts="contacts.data"></contact-lists>
                    </tab>
                    <tab name="KH15">
                        <lead-list :leads="leads.data"></lead-list>
                    </tab>
                </tabs>
            </div><!-- /.card-body -->
        </div>
        <!-- /.nav-tabs-custom -->
    </div>
</template>
<script>
import LeadList from '../elements/LeadLists.vue';
import ContactLists from '../elements/ContactLists.vue';
export default {
    components: { LeadList, ContactLists },
    data() {
        return {
            contacts: null,
            leads: {},
            category: {
                id: '',
                name: '',
                slug: '',
                website_url: '',
                facebook_url: '',
                google_map: '',
                phone: '',
                contact_count: '',
                type: '',
                description: '',
            }
        }
    },

    methods: {
        createCategory() {
            axios.post("api/categories", {
                    name: this.category.name,
                    slug: this.category.slug,
                    type: this.category.type
                })
                .then(response => {
                    this.category = response.data;
                    this.loadCategories();
                    $('#categoryModal').modal('hide');
                })
        },
        loadContacts() {
            axios.get('api/categories/contacts/' + this.category.id)
                .then(response => {
                    this.contacts = response.data;
                });
        },
        loadLeads() {
            axios.get('api/categories/leads/' + this.category.id)
                .then(response => {
                    this.leads = response.data;
                });
        },
        loadCategory() {
            axios.get("/category/session")
                .then(response => {
                    this.category = response.data;
                    this.loadContacts();
                    this.loadLeads();
                })
        },
        ttkhachtiemnang(id) {
            console.log(id);
            axios.put('/contacts/session/' + id)
                .then(response => {
                    location.replace("/thong-tin-khach-tiem-nang")
                })
        }
    },
    created() {
        this.loadCategory();
    }
}

</script>
