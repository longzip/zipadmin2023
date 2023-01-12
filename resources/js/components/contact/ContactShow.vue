<template>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <a class="" href="#" @click="editcontact(contact.id)">
                            <h3 class="profile-username text-center">{{ contact.first_name}} {{ contact.last_name}}</h3>
                        </a>
                        <p class="text-muted text-center">{{ contact.company}}</p>
                        <p class="text-muted text-center"><small class="badge badge-info">{{ contact.note}}</small></p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>{{ contact.address}}</b> <a class="float-right">{{ contact.city}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Phone</b> <a class="float-right">{{ contact.phone}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right">{{ contact.email}}</a>
                            </li>
                        </ul>
                        <a href="#" @click="editcontact(contact.id)" class="btn btn-primary btn-block">Sửa thông tin</a>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <!-- /.card -->
            </div>
            <div class="col-md-9">
                <tabs>
                    <tab name="Đặc điểm">

                    </tab>
                    <tab name="Lo lắng">

                    </tab>
                    <tab name="Tư vấn">

                    </tab>
                    <tab name="Bình luận">

                    </tab>
                    <tab name="Tài liệu">

                    </tab>
                    <!-- Quote -->
                    <tab name="Báo giá">

                    </tab>
                    <!-- Videos -->
                    <tab name="Videos">

                    </tab>
                    <tab name="Đơn hàng">

                    </tab>
                </tabs>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
</template>
<script>
export default {
    props: ['id'],
    data() {
        return {
            contact: {}
        }
    },
    created() {
        axios.get("/api/contacts/" + this.id)
            .then(response => {
                this.contact = response.data;
            })
    }
}

</script>
