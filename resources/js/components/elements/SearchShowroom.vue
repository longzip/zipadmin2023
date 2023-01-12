<template>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>Chọn ngày</label>
                <date-range-picker style="display: block;" :locale-data="locale" :opens="opens" :singleDatePicker="false" :autoApply="true" v-model="$parent.selectedDate" @update="updateValues" :ranges="ranges">
                    <div slot="input" slot-scope="picker">{{ $parent.startDate | myDate }} - {{ $parent.endDate | myDate }}</div>
                </date-range-picker>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Chọn Showroom</label>
                <multiselect class="form-control" style="padding: 0;" v-model="$parent.showroomsSelected" :options="costcenters" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" deselectLabel="Bỏ Chọn" selectLabel="Nhấn để chọn" @close="timTheoShowroom">
                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                </multiselect>
            </div>
        </div>
        <!-- /.col -->
        <div class="col-md-4">
            <div class="form-group">
                <label>Chọn sale</label>
                <multiselect class="form-control" style="padding: 0;" v-model="$parent.saleSelected" :options="resources" :multiple="true" :close-on-select="false" :clear-on-select="false" :preserve-search="true" placeholder="Chọn" label="name" track-by="id" :preselect-first="true" @close="timTheoSale">
                    <template slot="selection" slot-scope="{ values, search, isOpen }"><span class="multiselect__single" v-if="values.length &amp;&amp; !isOpen">{{ values.length }} options selected</span></template>
                </multiselect>
            </div>
        </div>
        <!-- /.col -->
    </div>
</template>
<script>
export default {
    data() {
        return {
            opens: "center", //which way the picker opens, default "center", can be "left"/"right"
            locale: {
                direction: 'ltr', //direction of text
                format: 'DD-MM-YYYY', //fomart of the dates displayed
                separator: ' - ', //separator between the two ranges
                applyLabel: 'Apply',
                cancelLabel: 'Cancel',
                weekLabel: 'W',
                customRangeLabel: 'Custom Range',
                daysOfWeek: moment.weekdaysMin(), //array of days - see moment documenations for details
                monthNames: moment.monthsShort(), //array of month names - see moment documenations for details
                firstDay: 1, //ISO first day of week - see moment documenations for details
                showWeekNumbers: true //show week numbers on each row of the calendar
            },
            ranges: { //default value for ranges object (if you set this to false ranges will no be rendered)
                'Tuần này': [moment().startOf('week'), moment()],
                'Tuần trước': [moment().subtract(1, 'week').startOf('week'), moment()],
                'Hai tuần trước': [moment().subtract(2, 'week').startOf('week'), moment()],
                'Ba tuần trước': [moment().subtract(3, 'week').startOf('week'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Năm nay': [moment().startOf('year'), moment().endOf('year')],
            },
            showroomsSelected: [],
            costcenters: [],
            saleSelected: {},
            resources: []
        }
    },
    mounted() {
        console.log('Component mounted.')
    },
    methods: {
        updateValues(values) {
            this.$parent.startDate = values.startDate.toISOString().slice(0, 10)
            this.$parent.endDate = values.endDate.toISOString().slice(0, 10)
            this.$emit('show');
        },
        timTheoShowroom() {
            let costcenter_ids = this.$parent.showroomsSelected.map(costcenter => {
                return costcenter.id;
            });
            if (costcenter_ids.length > 0) {
                let uri = 'api/find-user-by-costcenter?' + queryString.stringify({ costcenters: costcenter_ids }, { arrayFormat: 'bracket' });
                axios.get(uri)
                    .then(response => {
                        this.$parent.saleSelected = this.resources = response.data;
                        this.$emit('show');
                    });
            } else {
                this.$parent.saleSelected = [];
                this.$emit('show');
            }

        },
        timTheoSale() {
            this.$emit('show');
        },
    },
    created() {
        axios.get('api/users-costcenters')
            .then(res => this.costcenters = res.data);
        axios.get('/api/picklists/users')
            .then(res => this.resources = res.data);
    }
}

</script>
