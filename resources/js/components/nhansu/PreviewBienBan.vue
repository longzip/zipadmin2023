<template>
    <div>
        <div class="container" style="margin-top: 20px; margin-left: 10px">
                    <div class="row">
                        <div style="width: 30%; float: left;">
                            <p style="text-align: center;font-weight: 1000;font-size: 20px; margin-bottom: 0.5rem;">CÔNG TY TNHH</p> 
                            <p style="text-align: center;font-weight: 1000;font-size: 20px; margin-bottom: 0.5rem; margin-top:10px">NỘI THẤT ZIP</p> 
                            <p style="text-align: left;font-size: 18px;font-style: italic; margin-left: 20px"> Số: BB/{{vipham.p}}{{vipham.year}}-{{vipham.stt}}</p>
                            
                         </div>
                        <div style="width: 70%; float: left;">
                                <p style="text-align: center;font-weight: 900;font-size: 20px; margin-bottom: 5px;"> CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM </p>
                                <p style="text-align: center;font-weight: 900;font-size: 20px; margin-top: 5px"> Độc Lập - Tự Do - Hạnh Phúc</p>
                                <p style="text-align: right;font-size: 18px;font-style: italic;"> Hà Nội,ngày {{new Date().getDate()}} tháng {{new Date().getMonth() + 1}} năm {{new Date().getFullYear()}}</p>
                         </div>
                  </div>
                     <div style="margin-top: 0px;">
                            <p style="text-align: center;font-weight: 1000;font-size: 20px; clear: both;margin-bottom: 5px;"> BIÊN BẢN </p>
                            <p style="text-align: center;font-weight: 900;font-size: 18px;margin-top: 5px;"> CỦA PHÒNG QUẢN LÝ CHẤT LƯỢNG CÔNG TY TNHH NỘI THẤT ZIP </p>
                            <p style="text-align: center; font-style: italic;font-size: 18px"> V/v: Xử phạt vi phạm {{vipham.tenvipham}}</p>
                    </div>
                    <div style="margin-left: 30px">
                            <p style="font-size: 14px"> - Căn cứ vào Điều lệ,Nội quy Công ty TNHH Nội thất zip;</p>
                            <p style="font-size: 14px"> - Căn cứ vào chức năng,quyền hạn của Phòng quản lý chất lượng của Công ty;</p>
                            <p style="font-size: 14px"> - Căn cứ vào yêu cầu kinh doanh,</p>
                            <p style="font-weight: 800;font-size: 14px"> Phòng QA tiến hành lập biên bản phạt tiền với nội dung sau:</p>
                            <p style="font-size: 14px"> 1.Đối tượng vị phạm: <span v-if="user.name !=null">{{user.name}}</span>
                                                                            <span v-if="user.name ==null"> <span v-for="bp in role">{{bp.name}}/</span></span></p>
                            <p style="font-size: 14px"> 2.Ngày vi phạm: {{vipham.dateformated}}</p>
                            <p style="font-size: 14px"> 3.Lý do phạt tiền : </p>
                            <p style="font-size: 14px; margin-left: 10px; white-space: pre-line;"> {{vipham.tuongtrinh}}</p>
                            <p style="font-size: 14px"> 4.Hình thức: </p>
                            <div v-if="vipham.tienphat != null">
                                <p style="font-size: 14px"> + Phạt tiền: {{vipham.tienphat}} đồng trừ vào lương {{vipham.timeapply}}</p>
                            </div>

                            <div v-if="vipham.tienphat == null && vipham.chetai == 'Cảnh cáo' || vipham.tienphat == null && vipham.chetai == 'Cảnh Cáo' || vipham.tienphat == null && vipham.chetai == 'cảnh cáo'" >
                                <p style="font-size: 14px"> + Cảnh cáo</p>
                            </div>
                            <div v-if="vipham.tienphat == null && vipham.chetai == 'Đình chỉ' || vipham.tienphat == null && vipham.chetai == 'Đình Chỉ' || vipham.tienphat == null && vipham.chetai == 'đình chỉ'" >
                                <p style="font-size: 14px"> + Đình chỉ</p>
                                <p style="font-size: 14px"> + Thời gian áp dụng: {{vipham.timeapply}}</p>
                            </div>

                            <div v-if="vipham.tienphat == null && vipham.chetai == 'Đuổi việc' || vipham.tienphat == null && vipham.chetai == 'Đuổi Việc' || vipham.tienphat == null && vipham.chetai == 'đuổi việc'" >
                                <p style="font-size: 14px"> + Đuổi việc</p>
                                <p style="font-size: 14px"> + Thời gian áp dụng: Thời gian áp dụng: Bắt đầu từ {{vipham.timeapply}}</p>
                            </div>

                            <p style="font-size: 14px"> + Thực hiện đọc kĩ và chấp hành đúng quy định đơn hàng mà Công ty đã ban hành.</p>
                    </div>
                    <div class="row" style="margin-top: 30px; display:flex; margin-left: 10px">
                       <div class="col-md-6" style="width:50%; float: left;">
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> Người vi phạm </p>
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> (Ký,họ tên) </p>
                       </div>
                       <div class="col-md-6" style="padding-left: 160px; width:50%; float: right;">
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> QA-Nội Thất Zip </p>
                            <p style="text-align: center; font-weight: 800;font-size: 16px"> (Ký,họ tên) </p>
                       </div>
                    </div>
                </div>
        <button type="button" class="btn btn-danger" @click="generatePDF(vipham)">Generate</button>
        <!-- <a href="#" @click="show(vipham)">show</a> -->
    </div>
</template>
<script>
export default {
   
    props: ['vipham_id'],
    data() {
        return {
            vipham: [],
            user: [],
            role:[],
        }
    },
    methods: {
        show(vipham) {
            console.log(this.vipham);
        },
        generatePDF(vipham) {
            // console.log(vipham.id);
            // axios.get('api/exportViPhamPdf/' + vipham.data.id)
            // .then();
            console.log(vipham);
            location.replace('/exportpdf/'+vipham.id);
        },
    },
    created() {
        axios.get('api/vipham/' + this.vipham_id)
            .then(res => this.vipham = res.data.data)
            .then(() => {
                console.log(this.vipham);
                this.user = this.vipham.user;
                this.role = this.vipham.roleid;
                console.log(this.user);
            });
        // console.log(this.vipham);
    }
}

</script>
