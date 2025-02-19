import React, { useLayoutEffect } from "react";
import { Card } from "antd";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

export default function ForecastCash({ data }) {
    useLayoutEffect(() => {
        let chart = am4core.create("cashchartdiv", am4charts.XYChart);

        chart.data = Object.keys(data.name).map((key) => ({
            category: data.name[key],
            in: parseFloat(data.y.In[key]) || 0,
            out: parseFloat(data.y.Out[key]) || 0,
        }));

        let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "category";
        categoryAxis.title.text = "Months";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 30;
        categoryAxis.renderer.grid.template.strokeOpacity = 0.1;

        let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Amount";
        valueAxis.min = 0;
        valueAxis.renderer.grid.template.strokeOpacity = 0.1;


        let inSeries = chart.series.push(new am4charts.ColumnSeries());
        inSeries.name = "In";
        inSeries.dataFields.valueY = "in";
        inSeries.dataFields.categoryX = "category";
        inSeries.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";
        inSeries.columns.template.fill = am4core.color("#69d698"); // Green color for "In"

        let outSeries = chart.series.push(new am4charts.ColumnSeries());
        outSeries.name = "Out";
        outSeries.dataFields.valueY = "out";
        outSeries.dataFields.categoryX = "category";
        outSeries.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";
        outSeries.columns.template.fill = am4core.color("#dc6967"); // Red color for "Out"

        chart.legend = new am4charts.Legend();
        chart.cursor = new am4charts.XYCursor();
        chart.cursor.lineX.stroke = am4core.color("#000");
        chart.cursor.lineX.strokeWidth = 1;
        chart.cursor.lineX.strokeOpacity = 0.5;
        chart.cursor.lineY.stroke = am4core.color("#000");
        chart.cursor.lineY.strokeWidth = 1;
        chart.cursor.lineY.strokeOpacity = 0.5;

        return () => {
            chart.dispose();
        };
    }, [data]);

    return (
        <Card title={<h3>Cash Flow Forecast</h3>}>
            <div
                id="cashchartdiv"
                style={{ width: "100%", height: "500px" }}
            ></div>
        </Card>
    );
}
