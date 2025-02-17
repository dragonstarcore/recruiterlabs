import React, { useLayoutEffect } from "react";
import { Card } from "antd";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

export default function PerformanceInterviews({ data }) {
    useLayoutEffect(() => {
        let chart = am4core.create("interviewschartdiv", am4charts.XYChart);
        chart.data = data;

        let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.dataFields.category = "name";
        categoryAxis.renderer.minGridDistance = 40;
        categoryAxis.fontSize = 11;
        categoryAxis.renderer.grid.template.strokeOpacity = 0.1;

        let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.min = 0;
        valueAxis.renderer.grid.template.strokeOpacity = 0.1;

        let series = chart.series.push(new am4charts.LineSeries());
        series.dataFields.valueY = "count";
        series.dataFields.categoryX = "name";
        series.name = "Count";
        series.strokeWidth = 3;
        series.stroke = am4core.color("#007BFF"); // Change line color here
        series.fill = am4core.color("#007BFF");
        series.tooltipText = "{categoryX}: [bold]{valueY}[/]";
        series.tooltip.background.cornerRadius = 20;
        series.tooltip.background.strokeOpacity = 0;
        series.tooltip.pointerOrientation = "vertical";
        series.tooltip.label.minWidth = 40;
        series.tooltip.label.minHeight = 40;
        series.tooltip.label.textAlign = "middle";
        series.tooltip.label.textValign = "middle";

        let bullet = series.bullets.push(new am4charts.CircleBullet());
        bullet.circle.radius = 4;
        bullet.circle.strokeWidth = 2;
        bullet.circle.fill = am4core.color("#fff");

        let range = valueAxis.axisRanges.create();
        range.value = 90.4;
        range.grid.stroke = am4core.color("#396478");
        range.grid.strokeWidth = 1;
        range.grid.strokeOpacity = 1;
        range.grid.strokeDasharray = "3,3";
        range.label.inside = true;
        range.label.text = "Average";
        range.label.fill = range.grid.stroke;
        range.label.verticalCenter = "bottom";

        chart.exporting.menu = new am4core.ExportMenu();

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
        <div>
            <Card title={<h4>Interviews Data</h4>}>
                <div
                    id="interviewschartdiv"
                    style={{ width: "100%", height: "350px" }}
                ></div>
            </Card>
        </div>
    );
}