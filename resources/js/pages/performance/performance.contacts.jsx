import { Card } from "antd";
import React from "react";
import Chart from "react-apexcharts";

export default function PerformanceContacts({ data }) {
    const chartData = {
        series: [
            {
                name: "Views",
                data: data.map((item) => item.count),
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
                categories: data.map((item) => item.name),
            },
            title: {
                text: "Contacts Data",
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
        <Card>
            <Chart
                options={chartData.options}
                series={chartData.series}
                type="area"
                height={350}
                color="#26a69a"
            />
        </Card>
    );
}
