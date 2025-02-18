import React, { useState } from "react";
import { Card, Col, Row, Select } from "antd";

import { useFetchGAQuery } from "./google.service";

import TotalViews from "./google.tv";
import PageViews from "./google.pv";
import TomCountry from "./google.tc";
import OperatingSystem from "./google.os";

const { Option } = Select;

export default function HomeGoogleAnalytics() {
    const [period, setPeriod] = useState("lastweek");

    const {
        data = {
            totalPageViews: [],
            pageViews: [],
            mostVisitedPages: [],
            topReferrers: [],
            topCountries: [],
            topOperatingSystems: [],
        },
        isFetching,
    } = useFetchGAQuery(period);

    const handlePeriodChange = (value) => {
        setPeriod(value);
    };

    return (
        <Card
            loading={isFetching}
            title={
                <div
                    style={{
                        display: "flex",
                        alignItems: "flex-end",
                        justifyContent: "space-between",
                    }}
                >
                    <h2>Google Analytics</h2>
                    <Select
                        defaultValue="lastweek"
                        onChange={handlePeriodChange}
                        style={{ marginBottom: 16, width: "200px" }}
                    >
                        <Option value="today">Today</Option>
                        <Option value="yesterday">Yesterday</Option>
                        <Option value="lastweek">Last Week</Option>
                        <Option value="lastmonth">Last Month</Option>
                        <Option value="lastyear">Last Year</Option>
                    </Select>
                </div>
            }
        >
            <Row gutter={[32, 32]}>
                <Col xs={24} sm={24} md={12} lg={12} xl={12}>
                    <Card variant="borderless">
                        <TotalViews items={data.totalPageViews} />
                    </Card>
                </Col>
                <Col xs={24} sm={24} md={12} lg={12} xl={12}>
                    <Card variant="borderless">
                        <PageViews items={data.pageViews} />
                    </Card>
                </Col>
                <Col xs={24} sm={24} md={12} lg={12} xl={12}>
                    <Card variant="borderless">
                        <TomCountry items={data.topCountries} />
                    </Card>
                </Col>
                <Col xs={24} sm={24} md={12} lg={12} xl={12}>
                    <Card variant="borderless">
                        <OperatingSystem items={data.topOperatingSystems} />
                    </Card>
                </Col>
            </Row>
        </Card>
    );
}
