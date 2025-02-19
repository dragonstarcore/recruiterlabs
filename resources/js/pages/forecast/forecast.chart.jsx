import React, { useLayoutEffect } from "react";
import { Card } from "antd";
import * as am4core from "@amcharts/amcharts4/core";
import * as am4charts from "@amcharts/amcharts4/charts";
import am4themes_animated from "@amcharts/amcharts4/themes/animated";

am4core.useTheme(am4themes_animated);

export default function ForecastChart({
    invoices_array = {},
    bills_array = {},
}) {
    const formatData = (data = []) => {
        if (!data) return;
        const names = [];
        const values = [];
        // Ensure data exists and contains 'name' and 'y'
        Object.values(data).forEach((item) => {
            if (item && item.name && item.y !== undefined) {
                names.push(item.name);
                if (typeof item.y == "number")
                    values.push(parseFloat(item.y.toFixed(2)));
                else values.push(item.y);
            }
        });

        return { names, values };
    };

    const invoiceData = formatData(invoices_array);
    const billData = formatData(bills_array);

    useLayoutEffect(() => {
        let chart = am4core.create("xerochartdiv", am4charts.XYChart);

        chart.data = invoiceData.names.map((name, index) => ({
            category: name,
            invoices: invoiceData.values[index],
            bills: billData.values[index],
        }));

        let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
        categoryAxis.dataFields.category = "category";
        categoryAxis.title.text = "Categories";
        categoryAxis.renderer.grid.template.location = 0;
        categoryAxis.renderer.minGridDistance = 40;
        categoryAxis.fontSize = 11;
        categoryAxis.renderer.grid.template.strokeOpacity = 0.1;

        let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
        valueAxis.title.text = "Amount";
        valueAxis.renderer.grid.template.strokeOpacity = 0.1;

        let barSeries = chart.series.push(new am4charts.ColumnSeries());
        barSeries.name = "Bills";
        barSeries.dataFields.valueY = "bills";
        barSeries.dataFields.categoryX = "category";
        barSeries.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";
        barSeries.columns.template.fillOpacity = 0.8;

        let colorSet = new am4core.ColorSet();
        barSeries.columns.template.adapter.add("fill", (fill, target) => {
            return colorSet.next();
        });

        let lineSeries = chart.series.push(new am4charts.LineSeries());
        lineSeries.name = "Invoices";
        lineSeries.dataFields.valueY = "invoices";
        lineSeries.dataFields.categoryX = "category";
        lineSeries.strokeWidth = 3;
        lineSeries.stroke = am4core.color("#007BFF");
        lineSeries.fill = am4core.color("#007BFF");
        lineSeries.tooltipText = "{name}: [bold]{valueY}[/]";
        lineSeries.tooltip.background.cornerRadius = 20;
        lineSeries.tooltip.background.strokeOpacity = 0;
        lineSeries.tooltip.pointerOrientation = "vertical";
        lineSeries.tooltip.label.minWidth = 40;
        lineSeries.tooltip.label.minHeight = 40;
        lineSeries.tooltip.label.textAlign = "middle";
        lineSeries.tooltip.label.textValign = "middle";
        lineSeries.tensionX = 0.8; // Add this line to make the line flexible

        let bullet = lineSeries.bullets.push(new am4charts.CircleBullet());
        bullet.circle.radius = 4;
        bullet.circle.strokeWidth = 2;
        bullet.circle.fill = am4core.color("#fff");

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
    }, [invoiceData, billData]);

    return (
        <Card title={<h3>Invoices owed to you / Bills you need to pay</h3>}>
            <div
                id="xerochartdiv"
                style={{ width: "100%", height: "500px" }}
            ></div>
        </Card>
    );
}
