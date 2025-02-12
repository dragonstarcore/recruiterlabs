import React from "react";
import CountUp from "react-countup";
import { Card, Alert, Statistic, Row, Col } from "antd";
import { ArrowDownOutlined, ArrowUpOutlined } from "@ant-design/icons";

const formatter = (value) => <CountUp end={value} separator="," />;

const GoogleAnalytics = ({ pageViews, GAError, totalVisitors }) => {
    return (
        <Card style={{ marginTop: 10 }} title={<h2>Google Analytics Data</h2>}>
            <Card.Meta
                description={
                    pageViews ? (
                        <Row gutter={[32, 64]}>
                            <Col xs={24} sm={24} md={12} lg={12} xl={6}>
                                <Card
                                    className="google-card"
                                    variant="borderless"
                                >
                                    <Statistic
                                        formatter={formatter}
                                        title={
                                            <h4 className="google-card-title">
                                                Page Views
                                            </h4>
                                        }
                                        value={pageViews}
                                        valueStyle={{
                                            color: "#3f8600",
                                            fontSize: "2rem",
                                        }}
                                        prefix={<ArrowUpOutlined />}
                                    />
                                </Card>
                            </Col>
                            <Col xs={24} sm={24} md={12} lg={12} xl={6}>
                                <Card
                                    className="google-card"
                                    variant="borderless"
                                >
                                    <Statistic
                                        formatter={formatter}
                                        title={
                                            <h4 className="google-card-title">
                                                Unique Users
                                            </h4>
                                        }
                                        value={totalVisitors}
                                        valueStyle={{
                                            color: "#cf1322",
                                            fontSize: "2rem",
                                        }}
                                        prefix={<ArrowDownOutlined />}
                                    />
                                </Card>
                            </Col>
                        </Row>
                    ) : GAError ? (
                        <Alert message={GAError} type="error" />
                    ) : (
                        <Alert
                            message="You have not connected any account yet, please connect your Google Analytics account."
                            type="error"
                        />
                    )
                }
            />
        </Card>
    );
};
export default GoogleAnalytics;
