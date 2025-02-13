import React from "react";
import Chart from "react-apexcharts";

export default function PageViews({ items }) {
    const chartData = {
        series: [
            {
                name: "Screen Page Views",
                data: items.map((item) => item.screenPageViews),
            },
            {
                name: "Active Users",
                data: items.map((item) => item.activeUsers),
            },
        ],
        options: {
            chart: {
                id: "pageViewsAndActiveUsers",
                height: 350,
                type: "bar",
                animations: {
                    enabled: true,
                    easing: "linear",
                    dynamicAnimation: {
                        speed: 1000,
                    },
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: "80%",
                    endingShape: "rounded",
                },
            },
            colors: ["#1E90FF", "#FF6347"],
            xaxis: {
                categories: items.map((item) => item.pageTitle.split(" ")[0]),
                label: items.map((item) => item.pageTitle),
            },
            title: {
                text: "Total Screen Page Views and Active Users",
                align: "left",
            },
            dataLabels: {
                enabled: false,
            },
            stroke: {
                show: true,
                width: 2,
                colors: ["transparent"],
            },
            tooltip: {
                y: {
                    formatter: function (val, { series, seriesIndex, dataPointIndex, w }) {
                        const item = items[dataPointIndex];
                        return `${item.pageTitle} - ${new Date(item.date).toLocaleDateString()}`;
                    }
                }
            }
        },
    };

    return (
        <div>
            <Chart
                options={chartData.options}
                series={chartData.series}
                type="bar"
                height={350}
            />
        </div>
    );
}