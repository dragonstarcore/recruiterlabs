import React, { useState, useEffect } from "react";
import ApexCharts from "react-apexcharts";
import { Card, Row, Image, Col, Alert, Typography } from "antd";

import ChartContainer from "./home.chart";
import apps from "./home.const";

const { Paragraph } = Typography;

const XeroContainer = ({ xero }) => {
    const [cashIn, setCashIn] = useState([]);
    const [cashOut, setCashOut] = useState([]);
    const [categories, setCategories] = useState([]);

    useEffect(() => {
        if (!xero) return;
        const totalCash = xero["total_cash"];
        try {
            const inData = [];
            const outData = [];

            // Process 'In' data
            for (let i = 0; i < Object.keys(totalCash["y"]["In"]).length; i++) {
                inData.push(Math.round(totalCash["y"]["In"][i] * 100) / 100);
            }
            // Process 'Out' data
            for (
                let i = 0;
                i < Object.keys(totalCash["y"]["Out"]).length;
                i++
            ) {
                outData.push(Math.round(totalCash["y"]["Out"][i] * 100) / 100);
            }

            setCashIn(inData);
            setCashOut(outData);
            setCategories(totalCash["name"]);
        } catch (error) {
            console.error("Error fetching or processing data:", error);
        }
    }, [xero]);

    return (
        <div>
            <Card style={{ marginTop: 10 }} title={<h2>Xero Data</h2>}>
                <Card.Meta
                    description={
                        xero.organisationName ? (
                            <>
                                <Paragraph>
                                    Organisation: {xero.organisationName}
                                </Paragraph>
                                <Row gutter={[32, 32]}>
                                    <Col
                                        xs={24}
                                        sm={24}
                                        md={24}
                                        lg={24}
                                        xl={12}
                                    >
                                        <Card
                                            style={{ width: "100%" }}
                                            title={
                                                <>
                                                    <h6>
                                                        Invoices owed to you
                                                    </h6>
                                                    <Row gutter={[6, 6]}>
                                                        <Col span={6}>
                                                            <p>
                                                                {
                                                                    xero.data
                                                                        .draft_count
                                                                }
                                                                Draft invoices:
                                                                {
                                                                    xero.data
                                                                        .draft_amount
                                                                }
                                                            </p>
                                                        </Col>
                                                        <Col span={6}>
                                                            <p>
                                                                {
                                                                    xero.data
                                                                        .aw_count
                                                                }{" "}
                                                                Awaiting
                                                                payment:{" "}
                                                                {
                                                                    xero.data
                                                                        .aw_amount
                                                                }
                                                            </p>
                                                        </Col>
                                                        <Col span={6}>
                                                            {" "}
                                                            <p>
                                                                {
                                                                    xero.data
                                                                        .overdue_count
                                                                }{" "}
                                                                Overdue:{" "}
                                                                {
                                                                    xero.data
                                                                        .overdue_amount
                                                                }
                                                            </p>
                                                        </Col>
                                                    </Row>
                                                </>
                                            }
                                        >
                                            <ChartContainer
                                                chartData={xero.invoices_array}
                                                title="Invoice Data"
                                                chartType="line"
                                                color="#f35c86"
                                                seriesName="Count"
                                            />
                                        </Card>
                                    </Col>
                                    <Col
                                        xs={24}
                                        sm={24}
                                        md={24}
                                        lg={24}
                                        xl={12}
                                    >
                                        <Card
                                            title={
                                                <>
                                                    <h6>Balance in Xero</h6>
                                                    <p>
                                                        {xero.balance[1] ||
                                                            "No data"}
                                                    </p>{" "}
                                                </>
                                            }
                                        >
                                            <ApexCharts
                                                options={{
                                                    chart: {
                                                        type: "line",
                                                        zoom: {
                                                            enabled: true,
                                                            type: "y",
                                                        },
                                                    },
                                                    title: {
                                                        text: "Cash in and out Data",
                                                    },
                                                    stroke: {
                                                        curve: "smooth",
                                                    },
                                                    subtitle: {
                                                        text: undefined,
                                                    },
                                                    xaxis: {
                                                        categories: categories,
                                                    },
                                                    plotOptions: {
                                                        bar: {
                                                            dataLabels: {
                                                                enabled: true,
                                                                formatter:
                                                                    function (
                                                                        val
                                                                    ) {
                                                                        return `£ ${val}`;
                                                                    },
                                                            },
                                                            borderRadius: 4,
                                                            borderRadiusApplication:
                                                                "end",
                                                            horizontal: true,
                                                        },
                                                    },
                                                    tooltip: {
                                                        y: {
                                                            formatter:
                                                                function (val) {
                                                                    return `£ ${val}`;
                                                                },
                                                        },
                                                    },
                                                    legend: {
                                                        show: false,
                                                    },
                                                }}
                                                series={[
                                                    {
                                                        name: "In",
                                                        data: cashIn,
                                                        color: "rgb(24, 144, 255)",
                                                    },
                                                    {
                                                        name: "Out",
                                                        data: cashOut,
                                                        color: "rgb(4, 175, 41)",
                                                    },
                                                ]}
                                                type="line"
                                                height={350}
                                            />
                                        </Card>
                                    </Col>
                                </Row>
                            </>
                        ) : (
                            <Alert
                                message="You have not connected any account yet, please connect your Xero account."
                                type="error"
                            />
                        )
                    }
                />
            </Card>

            <Card title={<h2>My Apps</h2>} style={{ marginTop: 20 }}>
                <div className="app_card_body">
                    {apps.map((app, index) => (
                        <a
                            href={app.link}
                            target="_blank"
                            rel="noopener noreferrer"
                            key={index}
                        >
                            <div className="card-img-body">
                                <div className="card-img-actions mx-1 mt-1">
                                    <Image
                                        className="card-img img-fluid"
                                        src={`./assets/images/${app.imgSrc}`}
                                        alt=""
                                        preview={false}
                                    />
                                </div>
                            </div>
                        </a>
                    ))}
                </div>
            </Card>
        </div>
    );
};
export default XeroContainer;
