<template>
    <div>
      <apexchart type="donut" :options="chartOptions" :series="chartSeries"></apexchart>
    </div>
  </template>
  
  <script>
  import { ref, watch } from 'vue';
  import VueApexCharts from 'vue3-apexcharts';
  
  export default {
    components: {
      apexchart: VueApexCharts,
    },
    props: {
      series: {
        type: Array,
        required: true
      },
      labels: {
        type: Array,
        required: true
      },
      colors: {
        type: Array,
        required: true
      }
    },
    setup(props) {
      const chartSeries = ref(props.series);
      const chartLabels = ref(props.labels);
      const chartColors = ref(props.colors);
  
      const chartOptions = ref({
        chart: {
          type: 'donut',
        },
        labels: chartLabels.value,
        colors: chartColors.value, 
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200,
            },
            legend: {
              position: 'bottom',
            },
          },
        }],
      });
  
      watch(() => props.series, (newValue) => {
        chartSeries.value = newValue;
      });
  
      watch(() => props.labels, (newValue) => {
        chartLabels.value = newValue;
      });
  
      watch(() => props.colors, (newValue) => {
        chartColors.value = newValue;
      });
  
      return {
        chartSeries,
        chartOptions,
      };
    },
  };
  </script>
  