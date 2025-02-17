import React from "react";

import { Card } from "antd";
import ApexCharts from "react-apexcharts";

export default function PerformanceJobs({ data }) {
    return (
        <div>
            <Card className="bar-chart">
                <ApexCharts
                    options={{
                        chart: {
                            type: "bar",
                            zoom: {
                                enabled: true,
                            },
                        },
                        colors: ["white"],
                        title: {
                            text: "Jobs Data",
                            style: {
                                color: "#fff",
                            },
                        },
                        xaxis: {
                            categories: data.map((item) => item.name),
                            labels: {
                                style: {
                                    colors: "#fff",
                                },
                            },
                        },
                        yaxis: {
                            labels: {
                                style: {
                                    colors: "#fff",
                                },
                            },
                        },
                        tooltip: {
                            stickOnContact: true,
                        },

                        plotOptions: {
                            bar: {
                                horizontal: false,
                                columnWidth: "40%",
                                borderRadius: 4,
                            },
                        },
                        grid: {
                            show: true,
                            borderColor: "#ccc",
                            strokeDashArray: 2,
                        },
                    }}
                    series={[
                        {
                            name: "count",
                            data: data.map((item) => item.count),
                        },
                    ]}
                    type="bar"
                    height={350}
                />
            </Card>
        </div>
    );
}
