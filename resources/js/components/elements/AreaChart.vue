<template>
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Area Chart</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>
        <div class="card-body">
            <div class="chart">
                <canvas id="areaChart" style="height: 137px; width: 274px;" width="274" height="137"></canvas>
            </div>
        </div>
        <!-- /.card-body -->
    </div>
</template>
<script>
export default {
    mounted() {
        console.log('Component mounted.')
    }
}

</script>
