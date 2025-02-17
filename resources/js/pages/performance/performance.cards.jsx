import React from "react";

import CountUp from "react-countup";
import { Card, Col, Row, Statistic } from "antd";
import {
    ContactsFilled,
    ProjectFilled,
    TeamOutlined,
    WechatWorkFilled,
} from "@ant-design/icons";

const formatter = (value) => <CountUp end={value} separator="," />;

export default function PerformanceCards({
    jobs,
    contacts,
    // placements,
    interviews,
    candidates,
}) {
    return (
        <Row gutter={[32, 64]}>
            <Col xs={24} sm={24} md={12} lg={12} xl={6}>
                <Card className="stastic-card" variant="borderless">
                    <Statistic
                        formatter={formatter}
                        title={<h4 className="stastic-title">Total Jobs</h4>}
                        value={jobs}
                        valueStyle={{
                            color: "#ffffff",
                            fontSize: "2rem",
                        }}
                        prefix={<ProjectFilled />}
                    />
                </Card>
            </Col>
            <Col xs={24} sm={24} md={12} lg={12} xl={6}>
                <Card className="stastic-card" variant="borderless">
                    <Statistic
                        formatter={formatter}
                        title={
                            <h4 className="stastic-title">Total Contacts</h4>
                        }
                        value={contacts}
                        valueStyle={{
                            color: "#ffffff",
                            fontSize: "2rem",
                        }}
                        prefix={<ContactsFilled />}
                    />
                </Card>
            </Col>
            <Col xs={24} sm={24} md={12} lg={12} xl={6}>
                <Card className="stastic-card" variant="borderless">
                    <Statistic
                        formatter={formatter}
                        title={
                            <h4 className="stastic-title">Total Interviews</h4>
                        }
                        value={interviews}
                        valueStyle={{
                            color: "#ffffff",
                            fontSize: "2rem",
                        }}
                        prefix={<WechatWorkFilled />}
                    />
                </Card>
            </Col>
            <Col xs={24} sm={24} md={12} lg={12} xl={6}>
                <Card className="stastic-card" variant="borderless">
                    <Statistic
                        formatter={formatter}
                        title={
                            <h4 className="stastic-title">Total Candidates</h4>
                        }
                        value={candidates}
                        valueStyle={{
                            color: "#ffffff",
                            fontSize: "2rem",
                        }}
                        prefix={<TeamOutlined />}
                    />
                </Card>
            </Col>
        </Row>
    );
}
