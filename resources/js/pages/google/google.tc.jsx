// import React from "react";
// import Chart from "react-apexcharts";

// export default function TomCountry({ items }) {
//     const chartData = {
//         series: items.map((item) => item.screenPageViews),
//         options: {
//             chart: {
//                 type: "pie",
//                 height: 350,
//             },
//             labels: items.map((item) => item.country),
//             title: {
//                 text: "Screen Page Views by Country",
//                 align: "left",
//             },
//             legend: {
//                 position: "bottom",
//             },
//             tooltip: {
//                 y: {
//                     formatter: function (val) {
//                         return `${val} views`;
//                     },
//                 },
//             },
//         },
//     };

//     return (
//         <Chart
//             options={chartData.options}
//             series={chartData.series}
//             type="pie"
//             height={350}
//         />
//     );
// }

import React, { useLayoutEffect } from "react";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

const COLORS = [
    "#5470c6", // Custom color 1
    "#91cc75", // Custom color 2
    "#fac858", // Custom color 3
    "#ee6666", // Custom color 4
    "#73c0de", // Custom color 5
    "#3ba272", // Custom color 6
    "#fc8452", // Custom color 7
    "#9a60b4", // Custom color 8
];

export default function TomCountry({ items }) {
    useLayoutEffect(() => {
        let chart = am4core.create("chartdiv", am4charts.PieChart3D);

        chart.data = items.map((item, index) => ({
            country: item.country,
            screenPageViews: item.screenPageViews,
            color: COLORS[index % COLORS.length],
        }));

        let pieSeries = chart.series.push(new am4charts.PieSeries3D());

        pieSeries.dataFields.value = "screenPageViews";
        pieSeries.dataFields.category = "country";
        pieSeries.slices.template.propertyFields.fill = "color"; // Set custom colors

        pieSeries.slices.template.tooltipText =
            "{category}: {value} views ({value.percent.formatNumber('#.0')}%)";

        chart.legend = new am4charts.Legend();

        return () => {
            chart.dispose();
        };
    }, [items]);

    return <div id="chartdiv" style={{ width: "100%", height: "500px" }}></div>;
}
