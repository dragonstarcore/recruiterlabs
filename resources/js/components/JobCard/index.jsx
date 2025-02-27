import React from "react";
import { NavLink } from "react-router-dom";

import moment from "moment";
import { Card, Row, Col, Tag, Space, Typography, Badge, Avatar } from "antd";
import {
    DollarOutlined,
    CalendarOutlined,
    EnvironmentOutlined,
    FieldTimeOutlined,
    BorderlessTableOutlined,
    ForkOutlined,
    UserOutlined,
} from "@ant-design/icons";

import "./styles.css";

const { Paragraph } = Typography;

export default function JobCard({ job = {} }) {
    const isNew = () => {
        const createdAt = moment(job.created_at);
        const now = moment();
        return now.diff(createdAt, "days") <= 7;
    };

    const JobDetails = () => (
        <Row>
            <Col span={18}>
                <Avatar
                    size={64}
                    src={`/${job?.user?.user_details?.logo}`}
                    icon={<UserOutlined />}
                />
                <NavLink to={`/community/${job.user_id}`}>
                    <span className="job-card-user">
                        by <strong>{job.user?.name}</strong>
                    </span>
                </NavLink>

                <NavLink to={`/jobshared/${job.id}`}>
                    <h2 className="job-card-title">{job.job_title}</h2>
                </NavLink>

                <Paragraph ellipsis={{ rows: 3 }}>
                    {job.job_description}
                </Paragraph>
                <Space size="middle">
                    <Tag icon={<FieldTimeOutlined />} color="cyan">
                        {moment(job.created_at).fromNow()}
                    </Tag>
                    <Tag icon={<CalendarOutlined />} color="blue">
                        {new Date(job.start_date).toLocaleDateString()}
                    </Tag>
                    <Tag icon={<EnvironmentOutlined />} color="volcano">
                        {job.location}
                    </Tag>
                    <Tag icon={<BorderlessTableOutlined />} color="magenta">
                        {job.job_type}
                    </Tag>
                    <Tag icon={<ForkOutlined />} color="purple">
                        {job.recruitment_type}
                    </Tag>
                </Space>
            </Col>

            <Col span={6} style={{ textAlign: "right" }}>
                <Space direction="vertical" size="small">
                    <Tag icon={<DollarOutlined />} color="green">
                        {Math.round(job.salary)} {job.salary_currency}
                    </Tag>
                    <Tag icon={<DollarOutlined />} color="blue">
                        {Math.round(job.fee)} {job.fee_currency}
                    </Tag>
                </Space>
            </Col>
        </Row>
    );

    const CardContent = (
        <Card className="job-card">
            <JobDetails />
        </Card>
    );

    return isNew() ? (
        <Badge.Ribbon text="New" color="pink">
            {CardContent}
        </Badge.Ribbon>
    ) : (
        CardContent
    );
}
