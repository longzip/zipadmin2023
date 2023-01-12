<template>
    <div style="width:75%;">
        <canvas id="canvas"></canvas>
    </div>
</template>
<script>
var config = {
    type: 'line',
    data: {
        labels: [],
        datasets: []
    },
    options: {
        responsive: true,
        title: {
            display: true,
            text: 'Báo cáo khách hàng KH15'
        },
        tooltips: {
            mode: 'index',
            intersect: false,
        },
        hover: {
            mode: 'nearest',
            intersect: true
        },
        scales: {
            xAxes: [{
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: 'Ngày'
                }
            }],
            yAxes: [{
                display: true,
                scaleLabel: {
                    display: true,
                    labelString: 'Số khách'
                }
            }]
        }
    }
};
window.onload = function() {
    var ctx = document.getElementById('canvas').getContext('2d');
    window.myLine = new Chart(ctx, config);
};
export default {
    data() {
        return {
            counted: []
        }
    },
    created() {

        this.loadCounted();

    },
    methods: {
        loadCounted() {
            axios.get('api/leads-count-by-date')
                .then(res => {
                    this.counted = res.data;
                    config.data.labels = Object.keys(this.counted);
                    let contacts = {
                        label: 'KH15',

                        data: Object.values(this.counted),
                        fill: false,
                    }
                    config.data.datasets.push(contacts);

                    //
                    window.myLine.update();
                });
        }
    }
}

</script>
