<script>
import { ref } from 'vue';
import VueApexCharts from 'vue3-apexcharts';

export default {
    components: {
        apexchart: VueApexCharts,
    },
    setup() {
        const series = ref([
            {
                name: 'Weekly Sales',
                data: [3000, 4500, 6000, 3500, 4800, 5500, 9200],
            },
        ]);

        const chartOptions = ref({
            chart: {
                type: 'bar',
                height: 350,
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded',
                },
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent'],
            },
            xaxis: {
                categories: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            },
            fill: {
                colors: ['#A0129D'],
                opacity: 1,
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return 'Rp' + val + ' Rupiah';
                    },
                },
            },
        });

        return {
            series,
            chartOptions,
        };
    },
};
</script>

<template>
    <div class="h-full ">
        <apexchart style="height: 100% !important;" type="bar" :options="chartOptions" :series="series"></apexchart>
    </div>
</template>