<template>
    <table class="table table-hover">
        <tbody>
            <tr>
                <th>Ngày tạo</th>
                <th>Showroom</th>
                <th>Tạo bởi</th>
                <th>Trạng thái</th>
                <th>Tên</th>
                <!-- <th>Phone</th> -->
                <th style="min-width: 120px;">Khu vực</th>
                <th>Sản phẩm quan tâm</th>
                <th>Thao tác</th>
            </tr>
            <tr v-for="lead in leads" :key="lead.id">
                <td>
                    <router-link :to="{ name: 'kh15Show', query: { id: lead.id }}">
                        {{ lead.created_at | myDateTime}}
                    </router-link><br>
                    <small>
                        {{ lead.start_date | myDate}} <br>
                        {{ lead.start_date | myShortDate}} {{lead.end_time | myTime}}
                    </small>
                </td>
                <td><span v-for="costcenter in getName(lead.costcenters)" class="badge badge-light">{{costcenter}}</span></td>
                <td>
                    <div v-if="lead.idContact == null">
                        {{ lead.username }}
                    </div>
                    <div v-if="lead.idContact != null">
                        <router-link :to="{ name: 'ttkhachtiemnang', query: { id: lead.idContact }}">
                        {{ lead.username }}
                        </router-link>
                    </div>
                </td>
                <!-- <td><span class="badge badge-info">{{ lead.status }}</span></td> -->
                <td>
                    <span class="badge badge-secondary" v-if="lead.statuskh == 2">Tạm Dừng</span>
                    <span class="badge badge-info" v-if="lead.statuskh == 1">Đang Chăm Sóc</span>
                    <router-link target= '_blank'  :to="{ name: 'ttkhachtiemnang', query: { id: lead.idContact }}" v-if=" lead.id_khtn == null && lead.idContact != '' ">
                        <i class="fa fa-user"></i>
                    </router-link>
                </td>
                <td>
                    <router-link :to="{ name: 'kh15Show', query: { id: lead.id }}">
                        {{ lead.first_name }} {{ lead.last_name }} <span v-if="lead.type == 1"> ( Data ) </span><span v-if="lead.type == 2"> ( Marketing ) </span>
                    </router-link>
                </td>
                <!-- <td>{{ lead.phone }}</td> -->
                <td><div v-for="salesarea in getKhuVuc(lead.khuvuc)"><span class="badge badge-light">{{salesarea}}</span></div><br></td>
                <td>
                    <products :items="lead.products"></products>
                    {{lead.sanpham}}
                </td>
                <td>
                    <router-link :to="{ name: 'editLead', params: { id: lead.id }}">
                        <i class="fa fa-edit blue"></i>
                    </router-link>
                    /
                    <a href="#" @click="deleteLead(lead.id)">
                        <i class="fa fa-trash red"></i>
                    </a>
                </td>
            </tr>
        </tbody>
    </table>
</template>
<script>
export default {
    props: ['leads'],
    methods: {
        ttkhachtiemnang(id) {
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
        deleteLead(id) {
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
                    axios.delete('api/leads/' + id)
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
                                footer: '<a href>Không được phép xóa liên hệ?</a>'
                            })
                        });
                }
            })
        },
        getName(values) {
            if (!values) return ''
            return values.map((ten, index, values) => {
                return ten.name
            })
        },
        getKhuVuc(values) {
            if (!values) return ''
            return values
        }
    }
}

</script>
