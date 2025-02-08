import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { Card, Row, Col, Button, Alert, Typography } from "antd";
import { Link } from "react-router-dom"; // If you're using react-router
import ApexCharts from "react-apexcharts";
const { Title, Paragraph } = Typography;

import { useFetchMeQuery } from "../home/home.service";
import { useFetchXeroQuery } from "./forecast.service";
import ChartContainer from "../home/home.chart";
const XeroConnection = () => {
    const dispatch = useDispatch();
    const userData = useSelector((apps) => apps.app.user);

    const { data: xeroData } = useFetchXeroQuery();

    const error = null; // No error for this case
    const connected = true; // Assume the user is connected
    const organisationName = "ABC Corporation";
    const username = "john_doe";
    const accountWatchlist = [
        ["Account A", "$1,000", "$5,000"],
        ["Account B", "$2,000", "$10,000"],
        ["Account C", "$500", "$2,500"],
    ];
    const balance = ["Business Bank Account", "$15,000"];
    const data = {
        draft_count: 2,
        draft_amount: "$1,000",
        aw_count: 3,
        aw_amount: "$5,500",
        overdue_count: 1,
        overdue_amount: "$2,000",
    };
    const myData = {
        draft_count: 1,
        draft_amount: "$500",
        aw_count: 4,
        aw_amount: "$7,000",
        overdue_count: 0,
        overdue_amount: "$0",
    };
    let invoices_array = [
        { name: "Invoice 1", y: 150 },
        { name: "Invoice 2", y: 200 },
        { name: "Invoice 3", y: 50 },
        { name: "Invoice 4", y: 300 },
    ];

    let bills_array = [
        { name: "Bill 1", y: 120 },
        { name: "Bill 2", y: 180 },
        { name: "Bill 3", y: 75 },
        { name: "Bill 4", y: 250 },
    ];

    let total_cash = {
        name: ["Week 1", "Week 2", "Week 3", "Week 4"],
        y: {
            In: [500, 300, 450, 700],
            Out: [200, 250, 300, 150],
        },
    };

    return (
        <div className="content">
            <Row gutter={[16, 16]}>
                <Col span={24}>
                    <Card className="m-2">
                        <div className="card-header text-start">
                            <Title level={4}>Xero</Title>
                        </div>
                        <div className="card-body">
                            {error ? (
                                !connected ? (
                                    <Alert
                                        message="You have not connected any account yet, please connect your Xero account."
                                        type="error"
                                        showIcon
                                        action={
                                            <Link to="my_business#visit_link">
                                                <Button type="link">
                                                    Visit here
                                                </Button>
                                            </Link>
                                        }
                                    />
                                ) : (
                                    <>
                                        <Typography.Title level={6}>
                                            Your connection to Xero failed
                                        </Typography.Title>
                                        <Paragraph>{error}</Paragraph>
                                        <Button
                                            type="primary"
                                            size="large"
                                            href="/xero/auth/authorize"
                                        >
                                            Connect to Xero
                                        </Button>
                                    </>
                                )
                            ) : connected ? (
                                <>
                                    <Typography.Title level={6}>
                                        You are connected to Xero
                                    </Typography.Title>
                                    <Paragraph>
                                        {organisationName} via {username}
                                    </Paragraph>
                                </>
                            ) : (
                                <>
                                    <Typography.Title level={5}>
                                        You are not connected to Xero
                                    </Typography.Title>
                                    {!connected && !organisationName && (
                                        <Alert
                                            message="You have not connected any account yet, please connect your Xero account."
                                            type="error"
                                            showIcon
                                        />
                                    )}
                                    <Button
                                        type="primary"
                                        size="large"
                                        href="/xero/auth/authorize"
                                    >
                                        Connect to Xero
                                    </Button>
                                </>
                            )}
                        </div>
                    </Card>
                </Col>
                <Col span={24}>
                    {connected && (
                        <Row gutter={[16, 16]}>
                            <Col span={24}>
                                <Row gutter={[16, 16]}>
                                    <Col span={12}>
                                        <Card
                                            title="Account Watchlist"
                                            style={{ height: 240 }}
                                        >
                                            <Row justify="space-between">
                                                <Col span={12}>Account</Col>
                                                <Col span={6}>This month</Col>
                                                <Col span={6}>YTD</Col>
                                            </Row>
                                            <hr />
                                            {accountWatchlist &&
                                            accountWatchlist.length > 0 ? (
                                                accountWatchlist.map(
                                                    (item, key) => (
                                                        <Row
                                                            justify="space-between"
                                                            key={key}
                                                        >
                                                            <Col span={12}>
                                                                {item[0]}
                                                            </Col>
                                                            <Col span={6}>
                                                                {item[1]}
                                                            </Col>
                                                            <Col span={6}>
                                                                {item[2]}
                                                            </Col>
                                                        </Row>
                                                    )
                                                )
                                            ) : (
                                                <Paragraph>
                                                    There is no data for this
                                                    field as fetched from Xero
                                                </Paragraph>
                                            )}
                                        </Card>
                                    </Col>

                                    <Col span={12}>
                                        <Card
                                            title="Business Bank Account"
                                            style={{ height: 240 }}
                                        >
                                            <Row justify="space-between">
                                                <Col span={12}>
                                                    Balance in Xero
                                                </Col>
                                                <Col span={12}>
                                                    {balance && balance[1]
                                                        ? balance[1]
                                                        : "There is no data for this field as fetched from Xero"}
                                                </Col>
                                            </Row>
                                        </Card>
                                    </Col>
                                </Row>
                            </Col>
                            <Col span={24}>
                                <Row gutter={[16, 16]}>
                                    <Col span={12}>
                                        <Card title="Invoices Owed to You">
                                            {data && (
                                                <>
                                                    <Typography.Text>
                                                        {data.draft_count} Draft
                                                        invoices:{" "}
                                                        {data.draft_amount}
                                                    </Typography.Text>
                                                    <br />
                                                    <Typography.Text>
                                                        {data.aw_count} Awaiting
                                                        payment:{" "}
                                                        {data.aw_amount}
                                                    </Typography.Text>
                                                    <br />
                                                    <Typography.Text>
                                                        {data.overdue_count}{" "}
                                                        Overdue:{" "}
                                                        {data.overdue_amount}
                                                    </Typography.Text>
                                                </>
                                            )}
                                            <ChartContainer
                                                chartData={invoices_array}
                                                title="Invoice Data"
                                                chartType="line"
                                                chartId="container"
                                                color="#f35c86"
                                                seriesName="Amount Due"
                                                YaxisTitle="Amount (£)"
                                            />
                                        </Card>
                                    </Col>

                                    <Col span={12}>
                                        <Card title="Bills You Need to Pay">
                                            {myData && (
                                                <>
                                                    <Typography.Text>
                                                        {myData.draft_count}{" "}
                                                        Draft bills:{" "}
                                                        {myData.draft_amount}
                                                    </Typography.Text>
                                                    <br />
                                                    <Typography.Text>
                                                        {myData.aw_count}{" "}
                                                        Awaiting payment:{" "}
                                                        {myData.aw_amount}
                                                    </Typography.Text>
                                                    <br />
                                                    <Typography.Text>
                                                        {myData.overdue_count}{" "}
                                                        Overdue:{" "}
                                                        {myData.overdue_amount}
                                                    </Typography.Text>
                                                </>
                                            )}
                                            <ChartContainer
                                                chartData={bills_array}
                                                title="Bills to Pay"
                                                chartType="line"
                                                color="#f35c86"
                                                seriesName="Amount Due"
                                                YaxisTitle="Amount (£)"
                                            />
                                        </Card>
                                    </Col>
                                </Row>
                            </Col>
                            <Col span={24}>
                                <Row gutter={[16, 16]}>
                                    <Col span={24}>
                                        <Card title="Total Cash In/Out">
                                            <ApexCharts
                                                options={{
                                                    chart: {
                                                        type: "bar",
                                                        zoom: { enabled: true },
                                                    },
                                                    title: {
                                                        text: "Total Cash In and Out",
                                                    },
                                                    xaxis: {
                                                        categories:
                                                            total_cash.name,
                                                    },
                                                    yaxis: {
                                                        title: {
                                                            text: "Amount (£)",
                                                        },
                                                    },
                                                }}
                                                series={[
                                                    {
                                                        name: "In",
                                                        data: total_cash.y.In,
                                                    },
                                                    {
                                                        name: "Out",
                                                        data: total_cash.y.Out,
                                                    },
                                                ]}
                                            />
                                        </Card>
                                    </Col>
                                </Row>
                            </Col>
                        </Row>
                    )}
                </Col>
            </Row>
        </div>
    );
};

export default XeroConnection;
