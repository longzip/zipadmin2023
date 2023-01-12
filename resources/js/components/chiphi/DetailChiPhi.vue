<template>
    <div class="">
<!-- detail chi phí thông báo -->
        <div class="modal  fade" id="chiphidetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Chi tiết Chi Phí</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                          <div class="form-row">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Nội Dung</th>
                                        <th>Số Lượng</th>
                                        <th>Gía Tiền</th>
                                        <th>Mục đích</th>
                                        <th>Ghi chú</th>
                                        <th>Tổng tiền</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="detail in chiphidetail">
                                        <td>{{detail.NoiDung}} </td>
                                        <td>{{detail.SoLuong}} </td>
                                        <td>{{detail.GiaTien}} </td>
                                        <td>{{detail.MucDich}} </td>
                                        <td>{{detail.GhiChu}} </td>
                                        <td>{{detail.TongTien}} </td>
                                    </tr>
                                </tbody>
                            </table>
                          </div>

                    </div>
                    <div class="modal-footer">
                        <div class="form-row col-md-12">
                            <label>Ảnh:</label>
                            <input type="file" id="file" ref="file" multiple/>
                            <has-error :form="form" field="file"></has-error>
                        </div>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>
    <style type="text/css" lang="scss" scoped>
      
    }
    </style>
<script>
export default {
    data() {
        return {
        }
    },
    methods: {
       
}

</script>
