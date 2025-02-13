import React from "react";
import Chart from "react-apexcharts";

export default function TotalViews({ items }) {
    const chartData = {
        series: [
            {
                name: "Views",
                data: items.map((item) => item.screenPageViews),
            },
        ],
        options: {
            chart: {
                id: "realtime",
                height: 350,
                type: "line",
                animations: {
                    enabled: true,
                    easing: "linear",
                    dynamicAnimation: {
                        speed: 1000,
                    },
                },
            },
            xaxis: {
                categories: items.map((item) =>
                    new Date(item.date).toLocaleDateString()
                ),
            },
            title: {
                text: "Total Screen Page Views",
                align: "left",
            },
            fill: {
                type: "gradient",
                gradient: {
                    shadeIntensity: 1,
                    inverseColors: false,
                    opacityFrom: 0.5,
                    opacityTo: 0,
                    stops: [0, 90, 100],
                },
            },
            tooltip: {
                stickOnContact: true,
            },
            legend: {
                enabled: false,
            },
        },
    };

    return (
        <Chart
            options={chartData.options}
            series={chartData.series}
            type="area"
            height={350}
            color="#26a69a"
        />
    );
}
