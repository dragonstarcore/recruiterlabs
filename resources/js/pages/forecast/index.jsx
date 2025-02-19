import React, { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { Card, Row, Col, Button, Alert, Typography, Spin, Flex } from "antd";
import { Link, useNavigate } from "react-router-dom"; // If you're using react-router
import ApexCharts from "react-apexcharts";
const { Title, Paragraph } = Typography;

import { useFetchMeQuery } from "../home/home.service";
import {
    useFetchXeroQuery,
    useFetchXeroredirectMutation,
} from "./forecast.service";
import ChartContainer from "../home/home.chart";
const XeroConnection = () => {
    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const authorizationCode = urlParams.get("code");
        if (authorizationCode) {
            getAccessToken(urlParams);
        }
    }, []);
    const getAccessToken = async (urlParams) => {
        try {
            const res = await fetch(`/api/xero/auth/callback?${urlParams}`, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            });

            // if (data.ok === "ok") setToken("ok");
            // else authorize();
        } catch (error) {
            console.error("Failed to get access token", error);
        }
    };

    const dispatch = useDispatch();
    const navigate = useNavigate();
    const userData = useSelector((apps) => apps.app.user);

    const { data: xeroData, isLoading } = useFetchXeroQuery();
    const [fetchXeroredirect, {}] = useFetchXeroredirectMutation();
    const getXeroUrl = async () => {
        const { data } = await fetchXeroredirect();
        // Open a new tab and navigate to the desired URL

        window.location.assign(data.url);
        //window.history.go(data.redirectUrl);
    };

    const error = xeroData?.error; // No error for this case
    const connected = xeroData?.connected; // Assume the user is connected
    const organisationName = xeroData?.organisationName;
    const username = xeroData?.username;
    // const accountWatchlist = [
    //     ["Account A", "$1,000", "$5,000"],
    //     ["Account B", "$2,000", "$10,000"],
    //     ["Account C", "$500", "$2,500"],
    // ];
    const accountWatchlist = xeroData?.account_watchlist || [];
    //const balance = ["Business Bank Account", "$15,000"];
    const balance = xeroData?.balance || [];
    const data = xeroData?.data || {};
    const myData = xeroData?.my_data || {};
    let invoices_array = xeroData?.invoices_array || [];

    let bills_array = xeroData?.bills_array || [];

    let total_cash = xeroData?.total_cash || [];
    if (isLoading)
        return (
            <Flex justify="center">
                <Spin />
            </Flex>
        );
    return (
        <div className="content">
            <Row gutter={[16, 16]}>
                <Col span={24}>
                    <Card className="m-2">
                        <div className="card-header text-start">
                            <Title level={4}>Xero</Title>
                        </div>
                        <div className="card-body">
                            {connected ? (
                                <>
                                    <Typography.Title level={3}>
                                        You are connected to Xero
                                    </Typography.Title>
                                    <Paragraph>
                                        {organisationName} via {username}
                                    </Paragraph>
                                </>
                            ) : (
                                <>
                                    {!connected && !organisationName && (
                                        <Alert
                                            message="You have not connected any account yet, please connect your Xero account."
                                            type="error"
                                            showIcon
                                            style={{ marginBottom: "10px" }}
                                        />
                                    )}
                                    <Button
                                        type="primary"
                                        size="large"
                                        onClick={() => getXeroUrl()}
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
                                                <Flex
                                                    justify="flex-end"
                                                    style={{ color: "#0078c8" }}
                                                >
                                                    <Row>
                                                        <Col span={24}>
                                                            <Row>
                                                                <Col span={10}>
                                                                    <strong
                                                                        style={{
                                                                            margin: 5,
                                                                        }}
                                                                    >
                                                                        {data.draft_count
                                                                            ? myData.draft_count
                                                                            : ""}
                                                                        Draft
                                                                        payment:
                                                                    </strong>
                                                                </Col>

                                                                <Col
                                                                    span={6}
                                                                    style={{
                                                                        textAlign:
                                                                            "end",
                                                                    }}
                                                                >
                                                                    <Typography.Text>
                                                                        {data.draft_amount.toFixed(
                                                                            2
                                                                        )}
                                                                    </Typography.Text>
                                                                </Col>
                                                            </Row>
                                                        </Col>
                                                        <Col span={24}>
                                                            <Row>
                                                                <Col span={10}>
                                                                    <strong
                                                                        style={{
                                                                            margin: 5,
                                                                        }}
                                                                    >
                                                                        {data.aw_count
                                                                            ? myData.aw_count
                                                                            : ""}
                                                                        Awaiting
                                                                        payment:
                                                                    </strong>
                                                                </Col>
                                                                <Col
                                                                    span={6}
                                                                    style={{
                                                                        textAlign:
                                                                            "end",
                                                                    }}
                                                                >
                                                                    <Typography.Text>
                                                                        {data.aw_amount.toFixed(
                                                                            2
                                                                        )}
                                                                    </Typography.Text>{" "}
                                                                </Col>
                                                            </Row>
                                                        </Col>
                                                        <Col span={24}>
                                                            <Row>
                                                                <Col span={10}>
                                                                    <strong
                                                                        style={{
                                                                            margin: 5,
                                                                        }}
                                                                    >
                                                                        {data.overdue_count
                                                                            ? data.overdue_count
                                                                            : ""}
                                                                        Overdue:
                                                                    </strong>
                                                                </Col>
                                                                <Col
                                                                    span={6}
                                                                    style={{
                                                                        textAlign:
                                                                            "end",
                                                                    }}
                                                                >
                                                                    <Typography.Text>
                                                                        {data.overdue_amount.toFixed(
                                                                            2
                                                                        )}
                                                                    </Typography.Text>
                                                                </Col>
                                                            </Row>
                                                        </Col>
                                                    </Row>
                                                </Flex>
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
                                                <Flex
                                                    justify="flex-end"
                                                    style={{ color: "#0078c8" }}
                                                >
                                                    <Row>
                                                        <Col span={24}>
                                                            <Row>
                                                                <Col span={10}>
                                                                    <strong
                                                                        style={{
                                                                            margin: 5,
                                                                        }}
                                                                    >
                                                                        {myData.draft_count
                                                                            ? myData.draft_count
                                                                            : ""}
                                                                        Draft
                                                                        bills:
                                                                    </strong>
                                                                </Col>

                                                                <Col
                                                                    span={6}
                                                                    style={{
                                                                        textAlign:
                                                                            "end",
                                                                    }}
                                                                >
                                                                    <Typography.Text>
                                                                        {myData.draft_amount.toFixed(
                                                                            2
                                                                        )}
                                                                    </Typography.Text>
                                                                </Col>
                                                            </Row>
                                                        </Col>
                                                        <Col span={24}>
                                                            <Row>
                                                                <Col span={10}>
                                                                    <strong
                                                                        style={{
                                                                            margin: 5,
                                                                        }}
                                                                    >
                                                                        {myData.aw_count
                                                                            ? myData.aw_count
                                                                            : ""}
                                                                        Awaiting
                                                                        payment:
                                                                    </strong>
                                                                </Col>
                                                                <Col
                                                                    span={6}
                                                                    style={{
                                                                        textAlign:
                                                                            "end",
                                                                    }}
                                                                >
                                                                    <Typography.Text>
                                                                        {myData.aw_amount.toFixed(
                                                                            2
                                                                        )}
                                                                    </Typography.Text>{" "}
                                                                </Col>
                                                            </Row>
                                                        </Col>
                                                        <Col span={24}>
                                                            <Row>
                                                                <Col span={10}>
                                                                    <strong
                                                                        style={{
                                                                            margin: 5,
                                                                        }}
                                                                    >
                                                                        {myData.overdue_count
                                                                            ? myData.overdue_count
                                                                            : ""}
                                                                        Overdue:
                                                                    </strong>
                                                                </Col>
                                                                <Col
                                                                    span={6}
                                                                    style={{
                                                                        textAlign:
                                                                            "end",
                                                                    }}
                                                                >
                                                                    <Typography.Text>
                                                                        {myData.overdue_amount.toFixed(
                                                                            2
                                                                        )}
                                                                    </Typography.Text>
                                                                </Col>
                                                            </Row>
                                                        </Col>
                                                    </Row>
                                                </Flex>
                                            )}
                                            <ChartContainer
                                                chartData={bills_array}
                                                title="Bills to Pay"
                                                chartType="bar"
                                                color="#0078c8"
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
