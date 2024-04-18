 // ApexCharts options and config
 window.addEventListener("load", function() {
    const getChartOptions = () => {
        return {
          series: [1500, 3500, 400],
          colors: ["#00B7FF", "#64D48A", "#FFD66B"],
          chart: {
            height: 320,
            width: "100%",
            type: "donut",
          },
          stroke: {
            colors: ["transparent"],
            lineCap: "",
          },
          plotOptions: {
            pie: {
              donut: {
                labels: {
                  show: true,
                  name: {
                    show: true,
                    fontFamily: "Inter, sans-serif",
                    offsetY: 20,
                  },
                  total: {
                    showAlways: true,
                    show: true,
                    label: "Total User",
                    fontFamily: "Inter, sans-serif",
                    formatter: function (w) {
                      const sum = w.globals.seriesTotals.reduce((a, b) => {
                        return a + b
                      }, 0)
                      return `${sum}`
                    },
                  },
                  value: {
                    show: true,
                    fontFamily: "work-sans, sans-serif",
                    offsetY: -20,
                    formatter: function (value) {
                      return value + ""
                    },
                  },
                },
                size: "80%",
              },
            },
          },
          grid: {
            padding: {
              top: -2,
            },
          },
          labels: ["Funder (individual)", "Funder (institution)", "Beneficary",],
          dataLabels: {
            enabled: false,
          },
          legend: {
            position: "bottom",
            fontFamily: "work-sans, sans-serif",
          },
          yaxis: {
            labels: {
              formatter: function (value) {
                return value + ""
              },
            },
          },
          xaxis: {
            labels: {
              formatter: function (value) {
                return value  + "k"
              },
            },
            axisTicks: {
              show: false,
            },
            axisBorder: {
              show: false,
            },
          },
        }
      }

      if (document.getElementById("donut-chart") && typeof ApexCharts !== 'undefined') {
        const chart = new ApexCharts(document.getElementById("donut-chart"), getChartOptions());
        chart.render();

        // Get all the checkboxes by their class name
        const checkboxes = document.querySelectorAll('#devices input[type="checkbox"]');

        // Function to handle the checkbox change event
        function handleCheckboxChange(event, chart) {
            const checkbox = event.target;
            if (checkbox.checked) {
                switch(checkbox.value) {
                  case 'desktop':
                    chart.updateSeries([15.1, 22.5, 4.4, 8.4]);
                    break;
                  case 'tablet':
                    chart.updateSeries([25.1, 26.5, 1.4, 3.4]);
                    break;
                  case 'mobile':
                    chart.updateSeries([45.1, 27.5, 8.4, 2.4]);
                    break;
                  default:
                    chart.updateSeries([55.1, 28.5, 1.4, 5.4]);
                }

            } else {
                chart.updateSeries([35.1, 23.5, 2.4, 5.4]);
            }
        }

        // Attach the event listener to each checkbox
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', (event) => handleCheckboxChange(event, chart));
        });
      }
  });

  
        // ApexCharts options and config
        window.addEventListener("load", function () {
            let options = {
                // add data series via arrays, learn more here: https://apexcharts.com/docs/series/
                series: [
                    {
                        name: "Ongoing Campaign",
                        data: [45, 120, 104, 226, 340, 125, 95, 80, 104, 216, 340, 125],
                        color: "#64D48A",
                    },
                    {
                        name: "Finished Campaign",
                        data: [105, 140, 214, 326, 140, 105, 315, 210, 114, 326, 140, 175],
                        color: "#00B7FF",
                    },
                    {
                        name: "Funding",
                        data: [124, 230, 314, 226, 240, 165, 199, 310, 214, 326, 340, 325],
                        color: "#344CB7",
                    },
                ],
                legend: {
                    show: true,
                },
                chart: {
                    position: "relative",
                    height: "100%",
                    width: "100%",
                    type: "area",
                    fontFamily: "Inter, sans-serif",
                    dropShadow: {
                        enabled: false,
                    },
                    toolbar: {
                        show: false,
                    },
                },
                tooltip: {
                    enabled: true,
                    x: {
                        show: false,
                    },
                },

                fill: {
                    type: "gradient",
                    gradient: {
                        opacityFrom: 0.55,
                        opacityTo: 0,
                        shade: "#1C64F2",
                        gradientToColors: ["#1C64F2"],
                    },
                },
                dataLabels: {
                    enabled: false,
                },
                stroke: {
                    width: 2,
                },
                grid: {
                    show: true,
                    strokeDashArray: 4,
                    padding: {
                        left: 2,
                        right: 2,
                        top: 5
                    },
                },
                xaxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Juni', 'Juli', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'],
                    labels: {
                        show: true,
                    },
                    axisBorder: {
                        show: false,
                    },
                    axisTicks: {
                        show: false,
                    },
                },
                yaxis: {
                    show: true,
                    labels: {
                        show: false,
                        formatter: function (value) {
                            return value + " " + "jt";
                        }
                    }
                },
            }

            if (document.getElementById("labels-chart") && typeof ApexCharts !== 'undefined') {
                const chart = new ApexCharts(document.getElementById("labels-chart"), options);
                chart.render();
            }
        });