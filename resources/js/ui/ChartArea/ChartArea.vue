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
                name: 'Total sale',
                data: [30, 40, 45, 30, 49, 60, 70, 91],
            },
            {
                name: 'Total Revenue',
                data: [10, 30, 45, 35, 55, 60, 80, 80],
            },
            {
                name: 'Total Order',
                data: [11, 31, 35, 25, 51, 64, 70, 82],
            },
        ]);

        const chartOptions = ref({
            chart: {
                height: 350,
                type: 'area',
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: 'smooth',
            },
            xaxis: {
                type: 'datetime',
                categories: [
                    '2024-09-19T00:00:00.000Z',
                    '2024-09-20T01:30:00.000Z',
                    '2024-09-21T02:30:00.000Z',
                    '2024-09-22T03:30:00.000Z',
                    '2024-09-23T04:30:00.000Z',
                    '2024-09-24T05:30:00.000Z',
                    '2024-09-25T06:30:00.000Z',
                    '2024-09-26T07:30:00.000Z',
                ],
            },

            tooltip: {
                x: {
                    format: 'dd/MM/yy HH:mm',
                },
            },
            colors: ['#FF0000', '#A0129D', '#00829B'],
        });

        return {
            series,
            chartOptions,
        };
    },
};
</script>
<template>
    <div>
        <apexchart style="max-height: 300px !important;" type="area" :options="chartOptions" :series="series">
        </apexchart>
    </div>
</template>
