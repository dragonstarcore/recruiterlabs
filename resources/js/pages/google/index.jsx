import React, { useState } from "react";
import { Card, Col, Row, Select } from "antd";

import { useFetchGAQuery } from "./google.service";

import TotalViews from "./google.tv";
import PageViews from "./google.pv";
import MostVisitedPage from "./google.mv";
import PageReferrer from "./google.tr";
import TomCountry from "./google.tc";
import OperatingSystem from "./google.os";

const { Option } = Select;

export default function GoogleAnalytics() {
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
        <Card loading={isFetching}>
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

            <Row gutter={[32, 64]}>
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
                <Col xs={24} sm={24} md={12} lg={12} xl={12}>
                    <Card variant="borderless">
                        <MostVisitedPage items={data.mostVisitedPages} />
                    </Card>
                </Col>
                <Col xs={24} sm={24} md={12} lg={12} xl={12}>
                    <Card variant="borderless">
                        <PageReferrer items={data.topReferrers} />
                    </Card>
                </Col>
            </Row>
        </Card>
    );
}
