$(function () {

  // =====================================
  // Profit
  // =====================================

  
  $('#periode_laporan').on('change', function() {
    var tahun_laporan = $(this).val();
    $('#chart').html('');
    $('#earning').html('');

    $.get("getreport/"+tahun_laporan, function( response ) {
        console.log(response['data']['omset_penjualan']);
        var omset_penjualan = response['data']['omset_penjualan'];
        var jumlah_penjualan = response['data']['jumlah_penjualan'];
        var periode_penjualan = response['data']['periode_penjualan'];
        var max = response['data']['omset_tertinggi'];
        var profit = response['data']['profit'];

        $('#jumlah_profit_bulan_ini').html(response['data']['profit_bulan_ini']);

        if(response['data']['status_profit'] == '1') {
          $('#profit_turun').css('display', 'none');
          $('#profit_naik').css('display', 'block');
          $('#persentase_bulanan').html('+'+response['data']['persentase_profit']+'%');
        }

        if(response['data']['status_profit'] == '0') {
          $('#profit_naik').css('display', 'none');
          $('#profit_turun').css('display', 'block');
          $('#persentase_bulanan').html('-'+response['data']['persentase_profit']+'%');
        }

        var chart = {
          series: [
            { name: "Omset Penjualan:", data: omset_penjualan },
            // { name: "Jumlah Penjualan:", data: jumlah_penjualan },
          ],

          chart: {
            type: "bar",
            height: 345,
            offsetX: -15,
            toolbar: { show: true },
            foreColor: "#adb0bb",
            fontFamily: 'inherit',
            sparkline: { enabled: false },
          },

          colors: ["#5D87FF", "#49BEFF"],

          plotOptions: {
            bar: {
              horizontal: false,
              columnWidth: "35%",
              borderRadius: [6],
              borderRadiusApplication: 'end',
              borderRadiusWhenStacked: 'all'
            },
          },
          markers: { size: 0 },

          dataLabels: {
            enabled: false,
          },


          legend: {
            show: false,
          },

          grid: {
            borderColor: "rgba(0,0,0,0.1)",
            strokeDashArray: 3,
            xaxis: {
              lines: {
                show: false,
              },
            },
          },

          xaxis: {
            type: "category",
            categories: periode_penjualan,
            labels: {
              style: { cssClass: "grey--text lighten-2--text fill-color" },
            },
          },

          yaxis: {
            show: true,
            min: 0,
            max: max,
            tickAmount: 4,
            labels: {
              style: {
                cssClass: "grey--text lighten-2--text fill-color",
              },
            },
          },
          stroke: {
            show: true,
            width: 3,
            lineCap: "butt",
            colors: ["transparent"],
          },

          tooltip: { theme: "light" },

          responsive: [
            {
              breakpoint: 600,
              options: {
                plotOptions: {
                  bar: {
                    borderRadius: 3,
                  }
                },
              }
            }
          ]
      };

      var chart = new ApexCharts(document.querySelector("#chart"), chart);
      chart.render();

      // =====================================
      // Earning
      // =====================================
      var earning = {
        chart: {
          id: "sparkline3",
          type: "area",
          height: 60,
          sparkline: {
            enabled: true,
          },
          group: "sparklines",
          fontFamily: "Plus Jakarta Sans', sans-serif",
          foreColor: "#adb0bb",
        },
        series: [
          {
            name: "Earnings",
            color: "#49BEFF",
            data: profit,
          },
        ],
        stroke: {
          curve: "smooth",
          width: 2,
        },
        fill: {
          colors: ["#f3feff"],
          type: "solid",
          opacity: 0.05,
        },

        markers: {
          size: 0,
        },
        tooltip: {
          theme: "dark",
          fixed: {
            enabled: true,
            position: "right",
          },
          x: {
            show: false,
          },
        },
      };
      new ApexCharts(document.querySelector("#earning"), earning).render();
    });

  });

  $('#periode_laporan').change();
      
})