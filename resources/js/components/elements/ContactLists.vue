<template>
    <div class="tableFixHead">
        <table class="table table-hover ">
            <thead>
                <tr>
                    <th style="padding-left: 2px; padding-right: 2px;text-align: center;">Ngày tạo</th>
                    <th style="padding-left: 2px; padding-right: 2px;text-align: center;">Showroom</th>
                    <th style="padding-left: 2px; padding-right: 2px;text-align: center;">Tạo bởi</th>
                    <th style="padding-left: 2px; padding-right: 2px;text-align: center;">Trạng thái</th>
                    <th style="padding-left: 2px; padding-right: 2px;text-align: center;">Tên</th>
                    <th style="min-width: 120px;text-align: center;">Khu vực</th>
                    <th style="min-width: 137px;padding-left: 0; padding-right: 0;">Sản phẩm quan tâm</th>
                    <th style="padding-left: 2px; padding-right: 2px;text-align: center;">Báo giá</th>
                    <th style="padding-left: 2px; padding-right: 10px !important;min-width: 72px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="contact in contacts" :key="contact.id" v-bind:class="{ 'table-success': contact.isPublished }" >
                    <td>
                        <router-link :to="{ name: 'ttkhachtiemnang', query: { id: contact.id }}" v-b-tooltip.hover title="ngày chuyển KH15 sang KHTN">
                            <p>{{ contact.created_at | myDateTime}}</p>
                        </router-link>
                        <small>
                            <p v-b-tooltip.hover title="ngày khách hàng vào">{{ contact.start_date | myDate}} </p>
                            <p v-b-tooltip.hover title="giờ khách hàng ra">{{ contact.start_date | myShortDate}} {{contact.end_time | myTime}}</p>
                        </small>
                    </td>
                    <td>
                        <span v-for="costcenter in getName(contact.costcenters)" class="badge badge-light">{{costcenter}}</span><br>
                        <a href="#" @click="moveContact(contact.id)" v-if="contact.sm == 1">
                            <i class="fa fa-user-plus green"></i>
                        </a>
                        <a href="#" @click="loaiContact(contact.id)" v-if="contact.loai == 0 && contact.sales == 0">
                            <i class="fa fa-times red"></i>
                        </a>
                        <a href="#" @click="addContact(contact.id)" v-if="contact.loai == 1 ">
                            <i class="fa fa-plus blue"></i>
                        </a>
                    </td>
                    <td>
                        {{ contact.username }}<span v-if="contact.type == 1"> ( Data ) </span><span v-if="contact.type == 2"> ( Marketing ) </span></td>
                    <td><span class="badge" v-bind:class="getStatusClass(contact.status)">{{ contact.status | ContactStatus }}</span>
                        <br><i class="fa fa-file-video-o"></i> {{contact.videos.length}} <i class="fa fa-tags"></i> {{contact.kh15s.length}}/{{contact.losts.length}}
                        <br><i class="fa fa-comment-o"></i> {{contact.comments.length}} <i class="fa fa-tasks"></i> {{contact.tasks.length}}
                        <br><i class="fa fa-shopping-cart"></i> {{contact.quotes.length}}/{{contact.orders.length}}
                    </td>
                    <td>
                        <router-link :to="{ name: 'ttkhachtiemnang', query: { id: contact.id }}">
                            <i v-show="contact.isPublished" class="fa fa-check-square-o"></i> {{ contact.first_name }} {{ contact.last_name }}
                        </router-link>
                    </td>
                    <td>
                        <div v-for="salesarea in getKhuVuc(contact.khuvuc)">
                            <span class="badge badge-light">{{salesarea}}</span>
                        </div><br>
                    </td>
                    <td>
                        <products :items="contact.products"></products>
                    </td>
                    <td>{{ contact.amount | currency }}</td>
                    <td>
                        <router-link :to="{ name: 'editContact', params: { id: contact.id }}">
                            <i class="fa fa-edit blue"></i>
                        </router-link>
                        /
                        <a href="#" @click="deleteContact(contact.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                        /
                        <a href="#" v-if="contact.duyet == 0 && contact.type == null" @click="move(contact.id)">
                            <i class="fa fa-exchange green"></i>
                        </a>
                        <span v-if="contact.duyet == 1">
                            <i class="fa fa-check-square" aria-hidden="true"></i>
                        </span>
                        <span v-if="contact.duyet == 0 && contact.type == 4">
                            Đang Chờ Duyệt
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="modal fade" id="userModalCenter" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewLabel">Update User's</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="updateUser(id)">
                        <div class="modal-body">
                            <div>
                                <label>Nhân Viên</label>
                                <multiselect v-model="form.name" label="name" track-by="id" :options="usersSR" :taggable="true"></multiselect>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: ['contacts'],
    data() {
        return {
            usersSR: [],
            form: new Form({
                id: '',
                name: '',
            }),
        }
    },
    methods: {
        moveContact(id) {
            this.id = id;
            $('#userModalCenter').modal('show');
        },
        loaiContact(id) {
            this.id = id;
            axios.get("/api/loaikh?id=" + id)
            .then(response => {
                swal.fire(
                    'Updated!',
                    'Information has been updated.',
                    'success'
                )
                this.loadcontacts();
            });
        },
        addContact(id) {
            this.id = id;
            axios.get("/api/addkh?id=" + id)
            .then(response => {
                swal.fire(
                    'Updated!',
                    'Information has been updated.',
                    'success'
                )
                this.loadcontacts();
            });
        },
        ttkhachtiemnang(id) {
            console.log(id);
            axios.put('/contacts/session/' + id)
                .then(response => {
                    location.replace("/thong-tin-khach-tiem-nang")
                })
        },
        getName(values) {
            if (!values) return ''
            return values.map((ten, index, values) => {
                return ten.name
            })
        },
        updateUser(id) {
            this.form.post('api/moveContact/' + id + '/' + this.form.name.id)
                .then(() => {
                    $('#userModalCenter').modal('hide');
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )
                    this.$Progress.finish();
                    Fire.$emit('searching');
                })
                .catch(() => {
                    this.$Progress.fail();
                });
        },
        move(id){
            axios.get("/api/updatelistsr?id=" + id)
                .then(response => {
                    swal.fire(
                        'Updated!',
                        'Information has been updated.',
                        'success'
                    )
                    this.loadcontacts();
                });
        },
        getKhuVuc(values) {
            if (!values) return ''
            return values
        },
        getStatusClass(status) {
            if (!status) return 'badge-primary'
            switch (status) {
                case 1:
                    return 'badge-primary';
                    break;
                case 2:
                    return 'badge-secondary';
                    break;
                case 3:
                    return 'badge-warning';
                    break;
                case 4:
                    return 'badge-dark';
                    break;
                case 5:
                    return 'badge-danger';
                    break;
                case 6:
                    return 'badge-success';
                    break;
                case 7:
                    return 'badge-info';
                case 8:
                    return 'badge-info';
            }
        },
        deleteContact(id) {
            swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    axios.delete('api/contacts/' + id)
                        .then(response => {
                            this.$emit('deleted');
                            swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                        })
                        .catch(response => {
                            swal.fire({
                                type: 'error',
                                title: 'Oops...',
                                text: 'Something went wrong!',
                                footer: '<a href>Không được phép xóa khách hàng tiềm năng?</a>'
                            })
                        });
                }
            })
        }
    },
    created() {
        axios.get("/api/UserSR").then(({ data }) => (this.usersSR = data));
    }
}

</script>

<style>
.tableFixHead          { overflow-y: auto; height: 700px; }
.tableFixHead thead th { position: sticky; top: 0; z-index: 1;}

table  { border-collapse: collapse; width: 100%; }
th, td { padding: 8px 16px; }
th     { background:#eee; }
tbody tr td { z-index: 0}
</style>