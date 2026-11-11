    <script>
        $(function() {
            const $chartEl = $("#chart-pie-donut");
            if (!$chartEl.length) return;
            if (typeof ApexCharts === "undefined") return;

            const submitted = parseInt($chartEl.attr("data-submitted"), 10) || 0;
            const notSubmitted = parseInt($chartEl.attr("data-not-submitted"), 10) || 0;

            const total = submitted + notSubmitted;

            $chartEl.empty();

            const options = {
                series: [notSubmitted, submitted],
                labels: ["Belum Mengerjakan", "Sudah Mengerjakan"],
                chart: {
                    type: "donut",
                    height: 90,
                    width: 310,
                    fontFamily: '"Nunito Sans", sans-serif',
                },

                plotOptions: {
                    pie: {
                        expandOnClick: false,
                        dataLabels: {
                            offset: -8
                        },
                        donut: {
                            size: "65%"
                        }
                    }
                },

                stroke: {
                    width: 1
                },

                colors: ["var(--bs-primary)", "var(--bs-success)"],

                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        if (total === 0) return "0%";
                        return val.toFixed(1) + "%";
                    },
                    style: {
                        fontSize: "11px",
                        fontWeight: 600
                    }
                },

                legend: {
                    position: "right",
                    fontSize: "12px",
                    markers: {
                        width: 10,
                        height: 10
                    },
                    itemMargin: {
                        vertical: 6
                    }
                },

                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + " siswa";
                        }
                    }
                },

                responsive: [{
                    breakpoint: 768,
                    options: {
                        chart: {
                            width: "100%",
                            height: 170
                        },
                        legend: {
                            position: "bottom"
                        }
                    }
                }]
            };

            new ApexCharts(document.querySelector("#chart-pie-donut"), options).render();
        });
    </script>
