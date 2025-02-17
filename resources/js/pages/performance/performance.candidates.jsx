import React, { useLayoutEffect } from "react";
import { Card } from "antd";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

export default function PerformanceCandidates({ data }) {
    useLayoutEffect(() => {
        let chart = am4core.create("candidatesdiv", am4charts.XYChart3D);
        chart.data = data;

        let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.dataFields.category = "name";
        categoryAxis.renderer.minGridDistance = 40;
        categoryAxis.fontSize = 11;

        let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.min = 0;

        let series = chart.series.push(new am4charts.ColumnSeries3D());
        series.dataFields.valueY = "count";
        series.dataFields.categoryX = "name";
        series.name = "Count";
        series.columns.template.tooltipText = "{categoryX}: [bold]{valueY}[/]";
        series.columns.template.column.cornerRadiusTopLeft = 4;
        series.columns.template.column.cornerRadiusTopRight = 4;

        // Apply color set
        let colorSet = new am4core.ColorSet();
        series.columns.template.adapter.add("fill", (fill, target) => {
            return colorSet.next();
        });

        chart.exporting.menu = new am4core.ExportMenu();

        chart.cursor = new am4charts.XYCursor();

        return () => {
            chart.dispose();
        };
    }, [data]);

    return (
        <div>
            <Card title={<h4>Candidates Data</h4>}>
                <div
                    id="candidatesdiv"
                    style={{ width: "100%", height: "350px" }}
                ></div>
            </Card>
        </div>
    );
}