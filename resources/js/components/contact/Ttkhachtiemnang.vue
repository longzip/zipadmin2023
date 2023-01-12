<template>
    <div class="container-flush mt-3">
        <div class="row">
            <div class="col-md-3">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <a href="#" @click="editcontact(contact.id)">
                            <h3 class="profile-username text-center">{{ contact.title}} {{ contact.first_name}} {{ contact.last_name}} <i v-show="contact.isPublished" class="fa fa-check"></i></h3>
                        </a>
                        <p class="text-muted text-center">{{ contact.company}}</p>
                        <p class="text-muted text-center"><small class="badge badge-info">{{ contact.status | ContactStatus}}</small></p>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Phone</b> <a class="float-right" href="#" @click="callKH(contact)">{{contact.phone}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Email</b> <a class="float-right" v-bind:href="contact.email">{{ contact.email}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Địa Chỉ</b> <a class="float-right">{{ contact.address}}</a>
                            </li>
                            <li class="list-group-item">
                                <b>Zalo</b> <a class="float-right">{{ contact.zalo}}</a>
                            </li>
                        </ul>
                        <router-link class="btn btn-primary btn-block" :to="{ name: 'editContact', params: { id: contact.id }}">Sửa thông tin</router-link>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <!-- About Me Box -->
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="row card-body box-profile">
                        <div class="form-group col-md-12 col-12">
                            <label class="text-center">Trạng Thái</label>
                        </div>
                        <br>
                        <div class="form-group col-md-12 col-12">
                            <!-- <multiselect v-model="trangthai" :options="newstt" :searchable="true" :close-on-select="true" :show-labels="false" placeholder="Chọn trạng thái"></multiselect> -->
                            <select  class="form-control" v-model="trangthai"  @change="onChangestt($event)">
                                <option v-for="option in $parent.status" v-bind:value="option.value">
                                    {{ option.text }}
                                </option>
                            </select>
                        </div>
                         <div class="form-group col-md-6 col-12" v-show="mode">
                            <label>Ngày hẹn</label>
                            <input v-model="hen_date" type="date" class="form-control" placeholder="Trang phục">
                        </div>
                        <div class="form-group col-md-6 col-12" v-show="mode">
                            <label>Giờ hẹn</label>
                            <input class="form-control" type="time" v-model="hen_time">
                        </div>
                        <div class="form-group col-md-3">
                            <a class="btn btn-success" @click="updatestatus()" href="#">Gửi</a>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Trạng Thái</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(s,k) in sttnew">
                                <td>{{k}}</td>
                                <td>{{s.name}}</td>
                                <td>{{s.created_at | myDate}}
                                    <div> <small>{{s.time }} </small> </div>
                                    <div> <small>{{ s.date}}</small> </div> 
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card card-primary card-outline">
                    <div class="row card-body box-profile">
                        <div class="form-group col-md-12 col-12">
                            <label class="text-center">Giai Đoạn</label>
                        </div>
                        <br>
                        <div class="form-group col-md-12 col-12">
                            <select  class="form-control" v-model="giaidoan">
                                <option v-for="option in $parent.giaidoan" v-bind:value="option.value">
                                    {{ option.text }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <a class="btn btn-success" @click="updategiaidoan()" href="#">Gửi</a>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Giai Đoạn</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(g,k) in giaidoanlog">
                                <td>{{k}}</td>
                                <td>
                                    <span v-if="g.id_tt == 1">B1</span>
                                    <span v-if="g.id_tt == 2">B2</span>
                                    <span v-if="g.id_tt == 3">C1</span>
                                    <span v-if="g.id_tt == 4">C2</span>
                                    <span v-if="g.id_tt == 5">D</span>
                                    <span v-if="g.id_tt == 6">Tạm Dừng</span>
                                    <span v-if="g.id_tt == 7">Không Có</span>
                                </td>
                                <td>{{g.created_at | myDateTime}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="card card-primary card-outline">
                    <div class="row card-body box-profile">
                        <div class="form-group col-md-12 col-12">
                            <label class="text-center">Lịch sử </label>
                        </div>
                        <br>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Trạng Thái</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(g,k) in loailog">
                                <td>{{k}}</td>
                                <td>
                                    <span v-if="g.stt == 1">Hủy</span>
                                    <span v-if="g.stt == 0">KHTN</span>
                                </td>
                                <td>{{g.created_at | myDateTime}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- /.card -->
            </div>
            <div class="col-md-9">
                <tabs>
                    <tab name="Chung">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Thông tin</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <strong><i class="fa fa-book mr-1"></i> Đặc điểm</strong>
                                <blockquote class="blockquote">
                                    <p class="mb-0">{{contact.note}}</p>
                                    <footer class="blockquote-footer">Vào showroom {{getName(contact.coscenters)}} từ {{contact.start_time}} đến {{contact.end_time}} <cite title="Ngày">{{contact.start_date}}</cite> bởi {{contact.username}} - {{contact.user_id}} - <span v-for="costcenter in contact.costcenters">{{costcenter.name}}</span></footer>
                                    <footer class="blockquote-footer">Thời gian chuyển KH15->KHTN: {{ contact.created_at | myDateTime}}</footer>
                                    <footer class="blockquote-footer">Điểm rơi chăm: {{ contact.diemroi.t }}</footer>
                                </blockquote>
                                <p class="lead fz16">Đặc điểm</p>
                                <p class="text-muted fz16">
                                    <span class="badge badge-primary" v-for="kh15 in contact.kh15s">{{kh15.name}}: {{kh15.description}}</span>
                                </p>
                                <p class="lead fz16">Lo Lắng</p>
                                <p>
                                    <span class="badge badge-warning fz16" v-for="lost in contact.losts">{{lost.name}}: {{lost.description}}</span>
                                </p>
                                <a href="#" @click="editDK(contact.dieukien)"><p class="lead fz16">Điều Kiện</p></a>
                                <div v-if="contact.dieukien !== null" style="fz16">
                                    <p>Đối Tượng:<span class="badge badge-success fz16"> {{contact.dieukien.doituong}}</span></p>
                                    <p>Mặt Bằng:<span class="badge badge-success fz16"> {{contact.dieukien.matbang}}</span></p>
                                    <p>Ngân Sách:<span class="badge badge-success fz16"> {{contact.dieukien.ngansach}} triệu</span></p>
                                    <p>Điểm Rơi:<span class="badge badge-success fz16"> {{contact.dieukien.t}}</span></p>
                                </div>
                                <hr>
                                <strong><i class="fa fa-map-marker mr-1"></i> Địa chỉ</strong>
                                <p class="text-muted">{{ contact.address}}</p>
                                <hr>
                                <strong><i class="fa fa-pencil mr-1"></i> Sản phẩm quan tâm</strong>
                                <p class="text-muted">
                                    <span class="badge badge-success" v-for="product in contact.products">{{product.name}}</span>
                                </p>
                                <hr>
                                <strong><i class="fa fa-file-text-o mr-1"></i> Notes</strong>
                                <p class="text-muted">{{contact.description}}.</p>
                            </div>
                            <div class="card-footer clearfix">
                                <a v-show="!contact.isPublished" href="#" @click="publish(contact)" class="btn btn-sm btn-info float-left">Đạt KHTN</a>
                                <a v-show="contact.isPublished" href="#" @click="unpublish(contact)" class="btn btn-sm btn-secondary float-right">Hủy duyệt</a>
                            </div>
                        </div>
                        <contact-tasks :tasks="contact.tasks" @show-task="showTask"></contact-tasks>
                        <attachment :items="contact.attachs" @upload-image="uploadImage"></attachment>
                        <comments :comments="contact.comments" @create-comment="storeContactComment"></comments>
                        
                    </tab>
                    <tab name="Đặc điểm" v-bind:suffix=" '(' + contact.kh15s.length + ')'">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Các đặc điểm</div>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li v-for="kh15 in contact.kh15s" :key="kh15.id">
                                        <strong><i class="fas fa-check"></i> {{kh15.name}} <a href="#" @click="deleteKh15(kh15)">
                                                <i class="fa fa-trash red"></i>
                                            </a></strong>
                                        <p class="text-muted">{{kh15.description}}</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <form class="form-inline">
                                    <div class="form-group mb-2">
                                        <label for="seclectKh15" class="sr-only">Chọn</label>
                                        <multiselect id="seclectKh15" v-model="kh15.name" :options="kh15Types" placeholder="Chọn đặc điểm"></multiselect>
                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label for="inputDescription" class="sr-only">Nhập</label>
                                        <input v-model="kh15.description" type="text" class="form-control" id="inputDescription" placeholder="Mô tả">
                                    </div>
                                    <button @click.prevent="addKh15" type="submit" class="btn btn-primary mb-2">Thêm đặc điểm</button>
                                </form>
                            </div>
                        </div>
                    </tab>
                    <tab name="Lo lắng" v-bind:suffix=" '(' + contact.losts.length + ')'">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Các lo lắng</div>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li v-for="lost in contact.losts" :key="lost.id"><strong><i class="fas fa-check"></i> {{lost.name}} <a href="#" @click="deleteLost(lost)">
                                                <i class="fa fa-trash red"></i>
                                            </a></strong>
                                        <p class="text-muted">{{lost.description}}</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer">
                                <form class="form-inline">
                                    <div class="form-group mb-2">
                                        <label for="seclectLost" class="sr-only">Chọn</label>
                                        <multiselect id="seclectLost" v-model="lost.name" :options="lostTypes" placeholder="Chọn đặc điểm"></multiselect>
                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label for="inputDescription" class="sr-only">Nhập</label>
                                        <input v-model="lost.description" type="text" class="form-control" id="inputDescription" placeholder="Mô tả">
                                    </div>
                                    <button @click.prevent="addLost" type="submit" class="btn btn-primary mb-2">Thêm lo lắng</button>
                                </form>
                            </div>
                        </div>
                    </tab>
                    <tab name="Tư vấn" v-bind:suffix=" '(' + contact.tasks.length + ')'">
                        <div class="card">
                            <div class="card-footer">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Chọn loại hoạt động</label>
                                        <multiselect v-model="taskcontact.title" :options="activityTypes" :taggable="true" @tag="addActivityType" placeholder="Chọn hoạt động"></multiselect>
                                    </div>
                                    <div class="form-group">
                                        <label>Nội dung kế hoạch</label>
                                        <textarea v-model="taskcontact.task" class="form-control" rows="3" placeholder="Enter ..."></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Thực hiện</label>
                                        <multiselect v-model="taskcontact.user" :options="users" label="name" track-by="id" placeholder="Chọn người thực hiện"></multiselect>
                                    </div>
                                    <div class="form-group">
                                        <label>Ngày hoàn thành</label>
                                        <input v-model="taskcontact.duedate" type="date" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <button type="button" @click.prevent="taokehoach" class="btn btn-primary">Tạo kế hoạch</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <contact-tasks :tasks="contact.tasks" @show-task="showTask"></contact-tasks>
                    </tab>
                    <tab name="Bình luận" v-bind:suffix=" '(' + contact.comments.length + ')'">
                        <comments :comments="contact.comments" @create-comment="storeContactComment"></comments>
                    </tab>
                    <!-- Videos -->
                    <tab name="Videos" v-bind:suffix=" '(' + contact.videos.length + ')'">
                        <contact-videos :videos="contact.videos" @create-video="themvideo" @upload-video="uploadVideo"></contact-videos>
                    </tab>
                    <tab name="Tài liệu"  v-bind:suffix=" '(' + contact.attachs.length + ')'">
                        <attachment :items="contact.attachs" @upload-image="uploadImage" :upload="true"></attachment>
                    </tab>
                    <!-- Quote -->
                    <tab name="Báo giá" v-bind:suffix=" '(' + contact.quotes.length + ')'">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">Danh sách báo giá</div>
                                <div class="card-tools">
                                    <router-link :to="{ name: 'taobaogia', params: { contactId: this.contact.id }}" class="btn btn-primary"><i class="fas fa-plus-square"></i> Tạo báo giá</router-link>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="table-responsive">
                                    <quote-list :quotes='contact.quotes'></quote-list>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <!-- /.card-footer -->
                            <div class="card-footer">
                            </div>
                            <!-- /.card-footer -->
                        </div>
                    </tab>
                    <tab name="Đơn hàng"  v-bind:suffix=" '(' + contact.orders.length + ')'">
                        <table class="table table-hover">
                            <tbody>
                                <tr>
                                    <th>SDH</th>
                                    <th>Mã SR</th>
                                    <th>Tạo bởi ID</th>
                                    <th>Ngày đặt hàng</th>
                                    <th>Ngày giao</th>
                                    <th>Tổng tiền</th>
                                    <th>Đã thu</th>
                                    <th>Trạng thái</th>
                                </tr>
                                <tr v-for="order in contact.orders" :key="order.id">
                                    <td><a href="#" @click="showOrder(order)">{{order.so_number}}</a></td>
                                    <td>{{order.costcenter}}</td>
                                    <td>{{order.user_id}}</td>
                                    <td>{{order.start_date | myDate}}</td>
                                    <td>{{order.delivery_date | myDate}}</td>
                                    <td>{{order.amount}}</td>
                                    <td>{{order.pre_pay}}</td>
                                    <td>{{order.status_id | trangThaiSO}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </tab>
                </tabs>
            </div>
        </div>
        <div class="modal  fade" id="orderLineModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết đơn hàng</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row invoice-info">
                            <div class="col-sm-6 invoice-col">
                                <strong>Số đơn hàng #{{order.so_number}}</strong><br>
                                <strong>Tên Khách: </strong>{{customer.name}}<br>
                                <strong>Số: </strong>{{customer.phone}}<br>
                                <strong>Địa Chỉ: </strong>{{customer.address_line1}}<br>
                            </div>
                            <!-- /.col -->
                            <div class="col-sm-6 invoice-col">
                                <strong>ID Nhân viên: </strong>{{order.user_id}}<br>
                                <strong>Showroom: </strong>{{order.costcenter}}<br>
                                <strong>Ngày đơn hàng: </strong>{{order.start_date | myDate}}<br>
                                <strong>Ngày giao hàng: </strong>{{order.delivery_date | myDate}}<br>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-12 table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>SL</th>
                                            <th>Đơn giá</th>
                                            <th>CK</th>
                                            <th>Thành tiền</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in orderLine">
                                            <td>{{ item.item }}</td>
                                            <td>{{ item.quantity }}</td>
                                            <td>{{ item.price | currency }}</td>
                                            <td>{{ item.discount}} %</td>
                                            <td>{{ item.amount | currency }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.col -->
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th style="width:50%">Khách đặt cọc: </th>
                                                <td style="width:50%">{{order.pre_pay | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th style="width:50%">Cần phải thu: </th>
                                                <td style="width:50%">{{order.amount - order.pre_pay | currency}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th>Tổng tiền</th>
                                                <td>{{order.subtotal | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Giảm giá</th>
                                                <td>{{order.qgg | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Phí lắp đặt</th>
                                                <td>{{order.fee_ld | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Phí vận chuyển</th>
                                                <td>{{order.fee_vc | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>VAT</th>
                                                <td>{{order.vat | currency}}</td>
                                            </tr>
                                            <tr>
                                                <th>Thanh toán</th>
                                                <td>{{order.amount | currency}}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.col -->
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
        <div class="modal fade" id="taskModalDetail" tabindex="-1" role="dialog" aria-labelledby="taskModalDetailTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="taskModalDetailTitle">{{myTask.title}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4>Mô tả</h4>
                        <textarea disabled="true" v-model="myTask.task" class="form-control form-control"></textarea>
                        <h4 class="mt-2">Thêm bình luận</h4>
                        <comments :comments="myTask.comments" @create-comment="storeTaskComment"></comments>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="editdk" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nhập Điều Kiện Chuyển KHTN</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Đối tượng sử dụng</label>
                            <input v-model="create.doituong" type="text" class="form-control" placeholder="Không Được Bỏ Trống">
                        </div>
                        <div class="form-group">
                            <label>Mặt Bằng Nhà</label>
                            <input v-model="create.matbang" type="text" class="form-control" placeholder="Không Được Bỏ Trống">
                        </div>
                        <div class="form-group">
                            <label>Ngân Sách Đầu Tư (đv: Triệu)</label>
                            <input v-model="create.ngansach" type="text" class="form-control" placeholder="Không Được Bỏ Trống">
                        </div>
                        <div class="form-group">
                            <label>Điểm rơi DS</label>
                            <div class="input-group">
                                <multiselect v-model="create.diemroi" :options="diemroi" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn điểm rơi" label="t" :preselect-first="true">
                                </multiselect>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="button" @click.prevent="taodieukien" class="btn btn-primary">Tạo Điều Kiện</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="showData" tabindex="-1" role="dialog" aria-labelledby="userModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Cập Nhật Trạng Thái</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="UpdateTV()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input type="radio" id="chua-nghe-may" value="1" v-model="call">
                                <label for="nghe-may">Nghe Máy</label><br>
                                <input type="radio" id="quan-tam" value="2" v-model="call">
                                <label for="chua-nghe-may">Chưa Nghe Máy</label><br>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>      
    </div>
</template>
<script>
import QuoteList from '../elements/QuoteList.vue';
import ContactTasks from '../elements/Task.vue';
import kh15 from '../elements/Kh15.vue';
import ContactVideos from '../contact/ContactVideos.vue';
import Attachment from '../elements/Attachment.vue';
export default {
    components: { QuoteList, kh15, ContactTasks, ContactVideos, Attachment },
    // props: ['id'],
    data() {
        return {
            call: '',
            mode: false,
            newstt: ['Gọi Điện','Có Cuộc Hẹn Tại Nhà','Có Cuộc Hẹn Tại Showroom','Cuộc Hẹn Tại Nhà','Cuộc Hẹn Tại Showroom'],
            trangthai: '',
            hen_time: '',
            hen_date: '',               
            diemroi: [],
            contact: {},
            categories: {},
            create: {
                id: '',
                doituong: '',
                matbang: '',
                kichthuoc: '',
                ngansach: '',
                diemroi: '',
            },
            data: {
                fontSize: 16,
            },
            activities: {},
            quotes: {},
            quote: {},
            quoteitems: {},
            comments: {},
            comment: {
                body: ''
            },
            myTask: {},
            tasks: {},
            taskcontact: {
                contact_id: '',
                user: {
                    id: this.$user.id,
                    name: this.$user.name
                },
                title: '',
                task: '',
                priority: '',
                duedate: moment().format().split("T")[0],
            },
            activity: {
                subject: '',
                date_start: '',
                due_date: '',
                time_start: '',
                time_end: '',
                status: '',
                note: '',
            },

            file: '',
            attachmens: [],
            task: null,
            youtubes: [],
            youtube_id: '',
            kh15: {
                name: '',
                description: ''
            },
            kh15s: {},
            lost: {
                name: '',
                description: ''
            },
            losts: {},

            //picklist
            users: [],
            activityTypes: [],
            coscenters: [],
            kh15Types: [
                'Nhà chung cư',
                'Nhà riêng',
                'Bao nhiêu con',
                'Có ở cùng bố mẹ',
                'Con có chung phòng không',
                'Có mang việc về nhà không',
                'Hay tiếp khách ở nhà không'
            ],
            lostTypes: [
                'Giá',
                'Gỗ',
                'Cơ khí',
                'Màu sắc',
                'Kích thước',
                'Không gian phòng',
                'Bảo hành',
                'Lắp đặt',
                'Vận chuyển',
                'Khác'
            ],
            images: [],
            index: null,
            order: [],
            orderLine: [],
            customer: [],
            phone: '',
            sttnew: [],
            giaidoan: [],
            giaidoanlog: [],
            loailog: [],
            titlestatus:'',
        }
    },
    methods: {
        UpdateTV(){
            console.log(this.call);
            if (this.call == 1) {
                var titele = 'Gọi Điện Nghe';
            }else{
                var titele = 'Gọi Điện Không Trả Lời';
            }
            axios.put('api/contacts/' + this.contact.id + '/tasks', {
                title: titele,
                task: '',
                priority: '',
                duedate: this.today ,
                user_id: this.taskcontact.user.id
            })
            .then(response => {
                $('#showData').modal('hide');
                this.loadcontact();
            });
        },
        callKH(data){
            this.data = data;
            window.location = 'tel:'+data.phone;
            $('#showData').modal('show');
        },
        taodieukien(){
            let formData = new FormData();
            if (this.files) {
                for( var i = 0; i < this.files.length; i++ ){
                    let file = this.files[i];
                    formData.append('file[' + i + ']', file);
                }
            }
            formData.append('doituong', this.create.doituong);
            formData.append('matbang', this.create.matbang);
            formData.append('diemroi', this.create.diemroi.id_table);            
            formData.append('idkhtn', this.contact.id);
            formData.append('t', this.create.diemroi.t);
            formData.append('ngansach', this.create.ngansach);
            formData.append('phone', this.contact.phone);
            axios.post('/api/adddk',formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
            .then(() => {
                swal.fire({
                    type: 'success',
                    title: 'Chúc Mừng',
                    text: 'Thêm Thành Công',
                    footer: '<a href>Why do I have this issue?</a>'
                });
                this.loadcontact();
                $('#editdk').modal('hide');
            })
            .catch(error => {
                swal.fire({
                    type: 'error',
                    title: 'Oops...',
                    text: error.response.data.message,
                    footer: '<a href>Why do I have this issue?</a>'
                })
            });

        },
        editDK(data){
            this.create = data;
            $('#editdk').modal('show');
        },
        onChangestt(event) {
            console.log(event.target.value);
            console.log(this.mode);
            var check = event.target.value;
            if (check == 11 || check == 10 || check == 9 || check == 7) {
                this.mode = true;
            }else{
                this.mode = false;
            }
        },
        updatestatus(){
            // console.log(empty(this.hen_date));
            if (this.trangthai != '') {
                if (this.trangthai == 11 || this.trangthai == 10 || this.trangthai == 9 || this.trangthai == 7 ) {
                    if (this.hen_date != '' && this.time_date != '') {
                    axios.get("/api/updatestatuskhtn?id=" + this.contact.id + '&status=' + this.trangthai + '&date=' + this.hen_date + '&time=' + this.hen_time)
                        .then(response => {
                            this.loadcontact();
                        });
                    }else{
                        swal.fire({
                            type: 'error',
                            title: 'Oops...',
                            text: 'Chưa chọn ngày hoặc tháng',
                            footer: '<a href>Why do I have this issue?</a>'
                        })
                    }
                }else{
                    axios.get("/api/updatestatuskhtn?id=" + this.contact.id + '&status=' + this.trangthai + '&date=' + this.hen_date + '&time=' + this.hen_time)
                        .then(response => {
                            this.loadcontact();
                        });
                }
            }

            if (this.trangthai == 11) {
                this.titlestatus = 'Hẹn Khách Ra Showroom';
            }
            if (this.trangthai == 10) {
                this.titlestatus = 'Hẹn Đo Nhà';
            }
            if (this.trangthai == 1) {
                this.titlestatus = 'Đang Chăm Sóc';
            }
            if (this.trangthai == 2) {
                this.titlestatus = 'Tạm Dừng';
            }
            if (this.trangthai == 3) {
                this.titlestatus = 'Gần đơn hàng';
            }
            if (this.trangthai == 4) {
                this.titlestatus = 'Đơn Hàng Chờ';
            }
            if (this.trangthai == 6) {
                this.titlestatus = 'Gọi Điện';
            }
            if (this.trangthai == 8) {
                this.titlestatus = 'Tiềm năng xa';
            }
            if (this.trangthai == 7) {
                this.titlestatus = 'Có cuộc hẹn tại nhà';
            }
            if (this.trangthai == 9) {
                this.titlestatus = 'Có cuộc Hẹn Tại Showroom';
            }
            axios.put('api/contacts/' + this.contact.id + '/tasks', {
                    title: this.titlestatus,
                    task: '',
                    priority: '',
                    duedate: this.hen_date ,
                    user_id: this.taskcontact.user.id
                })
                .then(response => {
                    this.contact.tasks.push(response.data.data);
                    this.taskcontact.title = null;
                    this.taskcontact.task = '';
                    this.taskcontact.user = { id: this.$user.id, name: this.$user.name }
                    this.taskcontact.duedate = moment().format().split("T")[0];
                });
        },
        updategiaidoan(){
            axios.get("/api/updategiaidoan?id=" + this.contact.id + '&giaidoan=' + this.giaidoan)
                .then(response => {
                    this.loadcontact();
                });
        },
        showOrder(order) {
            this.order = order;
            this.orderLine = order.orderLine;
            this.customer = order.customer;
            $('#orderLineModal').modal('show');
            $('#print').attr("href", '/orders/print/' + order.id);
            $('#downloadExcel').attr("href", '/orders/pdf/' + order.id);
        },
        addMedia() {
            let formData = new FormData();
            formData.append('file', this.file);
            axios.post('api/contacts/' + this.contact.id + '/attachmens',
                formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                }
            ).then(response => {
                console.log(response.data);
            });
        },

        handleFileUpload() {
            this.file = this.$refs.file.files[0];
            console.log(this.file);
        },
        storeContactComment(comment) {
            axios.put('api/contacts/' + this.contact.id + '/comment', {
                    body: comment
                })
                .then(response => {
                    this.contact.comments.push(response.data);
                });
        },
        loadcontact() {
            this.$Progress.start();
            axios.get("/api/contacts/" + this.$route.query.id)
                .then(response => {
                    this.contact = response.data.data;
                    this.$Progress.finish();
                })
                .catch(() => {
                    // location.replace('/khach-tiem-nang');
                    this.$Progress.fail();
                });

            axios.get("/api/showstatuskhtn?id=" + this.$route.query.id)
                .then(response => {
                        this.sttnew = response.data;
                    });

            axios.get("/api/loadgiaidoankhtn?id=" + this.$route.query.id)
                .then(response => {
                    this.giaidoanlog = response.data;
                });
            axios.get("/api/loadloai?id=" + this.$route.query.id)
                .then(response => {
                    this.loailog = response.data;
                });
        },
        loadActivityType() {
            axios.get('/api/activities-list')
                .then(res => this.activityTypes = res.data);
        },
        loadCostcenter() {
            axios.get('api/users-costcenters')
                .then(res => this.coscenters = res.data);
        },
        loadUsers() {
            axios.get('api/users/' + this.$user.id + '/friends')
                .then(res => this.users = res.data)
        },
        getName(values) {
            if (!values) return ''
            return values.map((item, index, values) => {
                return item.name
            })
        },
        addKh15() {
            axios.put('api/contacts/' + this.contact.id + '/kh15s', {
                    name: this.kh15.name,
                    description: this.kh15.description
                })
                .then(response => {
                    this.lostTypes.name = '';
                    this.contact.kh15s.push(response.data);
                });
        },
        deleteKh15(kh15) {
            axios.delete('api/kh15s/' + kh15.id)
                .then(() => this.contact.kh15s.splice(this.contact.kh15s.indexOf(kh15), 1))
        },
        addLost() {
            axios.put('api/contacts/' + this.contact.id + '/losts', {
                    name: this.lost.name,
                    description: this.lost.description
                })
                .then(response => {
                    this.lostTypes.name = '';
                    this.contact.losts.push(response.data);
                });
        },
        deleteLost(lost) {
            axios.delete('api/losts/' + lost.id)
                .then(() => this.contact.losts.splice(this.contact.losts.indexOf(lost), 1))
        },
        addActivityType(newActivityType) {
            this.activityTypes.push(newActivityType);
        },
        themvideo(video_src) {
            axios.put('api/contacts/' + this.contact.id + '/videos', {
                    title: video_src,
                })
                .then(response => {
                    this.contact.videos.push(response.data);
                    video_src = '';
                });
        },
        taokehoach() {
            axios.put('api/contacts/' + this.contact.id + '/tasks', {
                    title: this.taskcontact.title,
                    task: this.taskcontact.task,
                    priority: this.taskcontact.priority,
                    duedate: this.taskcontact.duedate,
                    user_id: this.taskcontact.user.id
                })
                .then(response => {
                    this.contact.tasks.push(response.data.data);
                    this.taskcontact.title = null;
                    this.taskcontact.task = '';
                    this.taskcontact.user = { id: this.$user.id, name: this.$user.name }
                    this.taskcontact.duedate = moment().format().split("T")[0];
                });
        },
        showTask(task) {
            this.myTask = task;
            $('#taskModalDetail').modal('show');
        },
        storeTaskComment(comment) {
            axios.put('api/tasks/' + this.myTask.id + '/comment', {
                    body: comment
                })
                .then(response => {
                    this.myTask.comments.push(response.data);
                });
        },
        download($attachmen) {
            location.replace("/attachmens/" + $attachmen);
        },
        uploadVideo(file) {
            let formData = new FormData();
            formData.append('file', file);
            axios.post('api/contacts/' + this.contact.id + '/attachmens',
                    formData, {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }
                ).then(response => {
                    this.themvideo(response.data);
                })
                .catch(function() {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });
        },
        uploadImage(files) {
            // console.log(files[1]);
            let formData = new FormData();
            // formData.append('file', file);
            for( var i = 0; i < files.length; i++ ){
                let file = files[i];
                formData.append('files[' + i + ']', file);
            }
            axios.post('api/contacts/' + this.contact.id + '/image',
                    formData, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                ).then(response => {
                    this.loadcontact();
                })
                .catch(function() {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });
        },
        publish(contact) {
            axios.get('api/contacts/' + contact.id + '/publish')
                .then(() => contact.isPublished = true)
                .catch(() => {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });
        },
        unpublish(contact) {
            axios.get('api/contacts/' + contact.id + '/unpublish')
                .then(() => contact.isPublished = false)
                .catch(() => {
                    swal.fire({
                        type: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                        footer: '<a href>Why do I have this issue?</a>'
                    })
                });
        },
           
    },
    created() {
        this.loadcontact();
        this.loadActivityType();
        this.loadCostcenter();
        this.loadUsers();
        axios.get('api/diem-roi')
        .then(response => {
            this.diemroi = response.data;
        });
    }
}

</script>
<style scoped>
.image {
    float: left;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    border: 1px solid #ebebeb;
    margin: 5px;
}
.fz16{
    font-size: 18px;
}
</style>
