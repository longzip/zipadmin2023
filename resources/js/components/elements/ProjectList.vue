<template>
    <div>
        <table class="table table-hover">
            <tbody>
                <tr>
                    <th>Ngày tạo</th>
                    <th>Tên Dự Án</th>
                    <th>Khách Hàng</th>
                    <th>Showroom</th>
                    <th>Tạo bởi</th>
                    <th>Trạng thái</th>
                    <th style="min-width: 120px;">Khu vực</th>
                    <th>Sản phẩm quan tâm</th>
                    <th>Báo giá</th>
                    <th>Thao tác</th>
                </tr>
                <tr v-for="contact in contacts" :key="contact.id" v-bind:class="{ 'table-success': contact.isPublished }" >
                    <td>
                        <router-link :to="{ name: 'ttkhachduan', params: { id: contact.id }}" @click.native="read(contact.id)">
                            {{ contact.created_at | myDate}}<br></router-link>
                        <small>
                            {{ contact.start_date | myDate}} <br>
                            {{ contact.start_date | myShortDate}} {{contact.end_time | myTime}}
                        </small>
                        <div>
                            <span class="right badge badge-danger"  @click="show(contact)">{{contact.count}}</span>
                        </div>
                    </td>
                    <td>{{ contact.name_project }}</td>
                    <td>
                        <router-link :to="{ name: 'ttkhachduan', params: { id: contact.id }}" @click.native="read(contact.id)">
                            {{ getKhachHang1(contact.company) }} <span v-if="getKhachHang2(contact.company) != null">-</span> {{ getKhachHang2(contact.company) }}
                        </router-link>
                    </td>
                    <td><span v-for="costcenter in getName(contact.costcenters)" class="badge badge-light">{{costcenter}}</span></td>
                    <td>{{ contact.username }}</td>
                    <td><span class="badge" v-bind:class="getStatusClass(contact.status)">{{ contact.status | ContactStatus }}</span>
                        <br><i class="fa fa-file-video-o"></i> {{contact.videos.length}} <i class="fa fa-tags"></i> {{contact.kh15s.length}}/{{contact.losts.length}}
                        <br><i class="fa fa-comment-o"></i> {{contact.comments.length}} <i class="fa fa-tasks"></i> {{contact.tasks.length}}
                        <br><i class="fa fa-shopping-cart"></i> {{contact.quotes.length}}/{{contact.orders.length}}
                    </td>
                    <td>{{ getKhuVuc(contact.salesarea) }}</td>
                    <td>
                        <products :items="contact.products"></products>
                    </td>
                    <td>{{ contact.amount | currency }}</td>
                    <td>
                        <router-link :to="{ name: 'editProject', params: { id: contact.id }}">
                            <i class="fa fa-edit blue"></i>
                        </router-link>
                        /
                        <a href="#" @click="deleteContact(contact.id)">
                            <i class="fa fa-trash red"></i>
                        </a>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="modal fade" id="addNewImg" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Thông Báo Hoạt Động</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" >
                        <ul v-for="item in readnote" >
                            <li>{{item.description}}</li>
                        </ul>
                    </div>
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
            contact: {},
            readnote: {},
        }
    },
    methods: {
        // ttkhachtiemnang(id) {
        //     console.log(id);
        //     axios.put('/contacts/session/' + id)
        //         .then(response => {
        //             location.replace("/thong-tin-khach-tiem-nang")
        //         })
        // },
        show(contact) {
            $('#addNewImg').modal('show');
            this.contact = contact;
            this.readnote = contact.read;
        },
        getName(values) {
            if (!values) return ''
            return values.map((ten, index, values) => {
                return ten.name
            })
        },
        getKhuVuc(values) {
            if (!values) return ''
            return values.name
        },
        getKhachHang1(values) {
            if (!values) return ''
            return values.name_one
        },
        getKhachHang2(values) {
            if (!values) return ''
            return values.name_two
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
                    axios.delete('api/project/' + id)
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
        },
        read(id) { 
            axios.get('api/read/' + id)
            .then()
        }
    }
}

</script>
