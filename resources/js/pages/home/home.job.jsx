import React, { useState, useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { NavLink, useNavigate } from "react-router-dom";
import ApexCharts from "react-apexcharts";
import { toast } from "react-toastify";
import { Card, Row, Col, Select, Typography } from "antd";
import Icon, {
    ProjectFilled,
    ContactsFilled,
    WechatWorkFilled,
    TeamOutlined,
} from "@ant-design/icons";

import ChartContainer from "./home.chart";

const { Option } = Select;
const { Title, Paragraph } = Typography;
const formatData = (data) => {
    if (!data) return;
    const names = [];
    const values = [];

    // Ensure data exists and contains 'name' and 'y'
    data.forEach((item) => {
        if (item && item.name && item.y !== undefined) {
            names.push(item.name);
            values.push(item.y);
        }
    });

    return { names, values };
};
const JobContainer = ({ jobadder }) => {
    return (
        <Card.Meta
            description={
                <>
                    <p>
                        {jobadder.fullname} - {jobadder.account_email}
                    </p>
                    <Row
                        gutter={[32, 64]}
                        style={{
                            marginTop: "50px",
                            marginBottom: "32px",
                        }}
                    >
                        <Col xs={24} sm={24} md={12} lg={12} xl={6}>
                            <Card>
                                <div className="job-card">
                                    <div className="icon-box">
                                        <ProjectFilled />
                                    </div>
                                    <div className="job-card-title">
                                        <span className="dashboard-info">
                                            Total Jobs
                                        </span>
                                        <Title className="job-card-number">
                                            {"+" + jobadder.jobs}
                                        </Title>
                                    </div>
                                </div>
                            </Card>
                        </Col>
                        <Col xs={24} sm={24} md={12} lg={12} xl={6}>
                            <Card>
                                <div className="job-card">
                                    <div className="icon-box">
                                        <ContactsFilled />
                                    </div>
                                    <div className="job-card-title">
                                        <span className="dashboard-info">
                                            Total Contacts
                                        </span>
                                        <Title className="job-card-number">
                                            {"+" + jobadder.contacts}
                                        </Title>
                                    </div>
                                </div>
                            </Card>
                        </Col>
                        <Col xs={24} sm={24} md={12} lg={12} xl={6}>
                            <Card>
                                <div className="job-card">
                                    <div className="icon-box">
                                        <WechatWorkFilled />
                                    </div>
                                    <div className="job-card-title">
                                        <span className="dashboard-info">
                                            Total Interviews
                                        </span>
                                        <Title className="job-card-number">
                                            {"+" + jobadder.interviews}
                                        </Title>
                                    </div>
                                </div>
                            </Card>
                        </Col>
                        <Col xs={24} sm={24} md={12} lg={12} xl={6}>
                            <Card>
                                <div className="job-card">
                                    <div className="icon-box">
                                        <span>
                                            <TeamOutlined />
                                        </span>
                                    </div>
                                    <div className="job-card-title">
                                        <span className="dashboard-info">
                                            Total Candidates
                                        </span>
                                        <Title className="job-card-number">
                                            {"+" + jobadder.candidates}
                                        </Title>
                                    </div>
                                </div>
                            </Card>
                        </Col>
                    </Row>
                    {jobadder.jobs_graph && (
                        <Row
                            gutter={[32, 32]}
                            style={{
                                marginTop: 10,
                            }}
                        >
                            <Col xs={24} sm={24} md={24} lg={24} xl={12}>
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
                                                    color: "#fff", // Set color for chart title
                                                },
                                            },
                                            xaxis: {
                                                categories: formatData(
                                                    jobadder.jobs_graph
                                                ).names,
                                                labels: {
                                                    style: {
                                                        colors: "#fff", // Set color for X-axis labels
                                                    },
                                                },
                                            },
                                            yaxis: {
                                                labels: {
                                                    style: {
                                                        colors: "#fff", // Set color for X-axis labels
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
                                                    borderRadius: 7,
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
                                                data: formatData(
                                                    jobadder.jobs_graph
                                                ).values,
                                            },
                                        ]}
                                        type="bar"
                                        height={350}
                                    />
                                </Card>
                            </Col>
                            <Col xs={24} sm={24} md={24} lg={24} xl={12}>
                                <Card>
                                    {/* Candidates Data Chart */}
                                    <ChartContainer
                                        chartData={jobadder.candidates_graph}
                                        title="Candidates Data"
                                        chartType="line"
                                        color="#FF9655"
                                        seriesName="Count"
                                    />
                                </Card>
                            </Col>
                            {/* Contacts Data Chart */}
                            <Col xs={24} sm={24} md={24} lg={24} xl={12}>
                                <Card>
                                    <ChartContainer
                                        chartData={jobadder.contacts_graph}
                                        title="Contacts Data"
                                        chartType="line"
                                        color="#f35c86"
                                        seriesName="Count"
                                    />
                                </Card>
                            </Col>
                            <Col xs={24} sm={24} md={24} lg={24} xl={12}>
                                {/* Interviews Data Chart */}
                                <Card>
                                    <ChartContainer
                                        chartData={jobadder.interviews_graph}
                                        title="Interviews Data"
                                        chartType="area"
                                        color="#26a69a"
                                        seriesName="Count"
                                    />
                                </Card>
                            </Col>
                        </Row>
                    )}
                </>
            }
        />
    );
};
export default JobContainer;
