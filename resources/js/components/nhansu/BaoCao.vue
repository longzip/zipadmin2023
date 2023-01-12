<template>
    <div class="">
        <div class="card card-default">
            <!-- /.card-header -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Chọn T</label>
                            <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="calendar" :multiple="false" :close-on-select="true" :clear-on-select="true" :preserve-search="true" placeholder="Chọn" label="t" track-by="id_table" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheot">
                            </multiselect>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title pull-left">Kết Qủa Tuyền Dụng</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th :rowspan="2" class="text-center">Vị Trí</th>
                            <th :rowspan="2" class="text-center">Cộng Tác Viên</th>
                            <th :colspan="4" class="text-center">T</th>
                            <th :colspan="4" class="text-center">Thứ 2</th>
                            <th :colspan="4" class="text-center">Thứ 3</th>
                            <th :colspan="4" class="text-center">Thứ 4</th>
                            <th :colspan="4" class="text-center">Thứ 5</th>
                            <th :colspan="4" class="text-center">Thứ 6</th>
                            <th :colspan="4" class="text-center">Thứ 7</th>
                            <th :colspan="4" class="text-center">CN</th>
                        </tr>
                            <tr>
                                <template v-for="n in 8">
                                    <th>CV</th>
                                    <th>K</th>
                                    <th>Đ</th>
                                    <th>Hẹn</th>
                                </template>
                            </tr>
                    </thead>
                    <tbody>
                        <template v-for="(value,key) in cv">
                            <template v-for="(v,k, i) in value">
                                <tr>
                                    <td class="text-center" :rowspan="Object.keys(value).length * 2" v-if="i==0">{{key}}</td>
                                    <td class="text-center" :rowspan="2">{{k}}</td>
                                    <td class="text-center">{{formatzezo(v[0].cv)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].khongdat)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].dat)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].hen)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].cv2)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].khongdat2)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].dat2)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].hen2)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].cv3)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].khongdat3)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].dat3)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].hen3)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].cv4)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].khongdat4)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].dat4)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].hen4)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].cv5)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].khongdat5)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].dat5)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].hen5)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].cv6)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].khongdat6)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].dat6)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].hen6)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].cv7)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].khongdat7)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].dat7)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].hen7)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].cvcn)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].khongdatcn)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].datcn)}}</td>
                                    <td class="text-center">{{formatzezo(v[0].hencn)}}</td>
                                </tr>
                                <tr>
                                    <td class="text-center" :colspan="4">{{formatzezo(v[0].dilam)}} / {{formatzezo(v[0].nghilam)}}</td>
                                    <td class="text-center" :colspan="4">{{formatzezo(v[0].dilam2)}} / {{formatzezo(v[0].nghilam)}}</td>
                                    <td class="text-center" :colspan="4">{{formatzezo(v[0].dilam3)}} / {{formatzezo(v[0].nghilam)}}</td>
                                    <td class="text-center" :colspan="4">{{formatzezo(v[0].dilam4)}} / {{formatzezo(v[0].nghilam)}}</td>
                                    <td class="text-center" :colspan="4">{{formatzezo(v[0].dilam5)}} / {{formatzezo(v[0].nghilam)}}</td>
                                    <td class="text-center" :colspan="4">{{formatzezo(v[0].dilam6)}} / {{formatzezo(v[0].nghilam)}}</td>
                                    <td class="text-center" :colspan="4">{{formatzezo(v[0].dilam7)}} / {{formatzezo(v[0].nghilam)}}</td>
                                    <td class="text-center" :colspan="4">{{formatzezo(v[0].dilamcn)}} / {{formatzezo(v[0].nghilam)}}</td>
                                    
                                </tr>
                            </template>
                        </template>
                       
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>
<script>
export default {
    data() {
        return {
            calendar: [],
            cv:[],
        }
    },
    methods: {
        timTheot(){
            this.loadcv();
        },
        loadcv() {
            this.$Progress.start();
            axios.get('api/baocaocv' + '?' + this.getPara())
                .then(responsive => {
                    this.cv = responsive.data;
                    this.$Progress.finish();
                })
                .catch(() => this.$Progress.fail());
        },
        
        getPara() {
            if (this.$parent.saleSelected.length == 0) {
               var week = 0;
            }else{
               var week = this.$parent.saleSelected.id_table;
            }
            let queryName = queryString.stringify({ name: this.$parent.search });
            return  'week=' + week + '&' + queryName;
        },

        formatzezo(number){
            if (number == 0) {
                return '-';
            }else{
                return number;
            }
        }

    },
    created() {
        this.loadcv();
        Fire.$on('searching', () => {
            this.loadcv();
        });
        axios.get('api/select-week')
            .then(res => this.calendar = res.data);
    },
}

</script>
