import { NavLink } from "react-router-dom";
import React from "react";

import moment from "moment";
import {
    Badge,
    Card,
    Col,
    Flex,
    Row,
    Space,
    Spin,
    Tag,
    Timeline,
    Tooltip,
} from "antd";
import {
    AimOutlined,
    BorderlessTableOutlined,
    CalendarOutlined,
    DollarOutlined,
    FieldTimeOutlined,
    ForkOutlined,
    MailOutlined,
    PoundOutlined,
} from "@ant-design/icons";

import BgProfile from "./../../../imgs/user-back.jpg";

import "./styles.css";

const tagColors = ["magenta", "blue", "green", "volcano", "gold"];

function TagsList({ keywords }) {
    return (
        <div>
            <div className="user-keywords-container">
                {keywords.map((specialism, i) => {
                    const trimmedSpecialism = specialism.trim();
                    const displayText =
                        trimmedSpecialism.length > 20
                            ? `${trimmedSpecialism.substring(0, 17)}...`
                            : trimmedSpecialism;

                    return trimmedSpecialism.length > 20 ? (
                        <Tooltip title={trimmedSpecialism} key={i}>
                            <Tag color={tagColors[i % tagColors.length]}>
                                {displayText}
                            </Tag>
                        </Tooltip>
                    ) : (
                        <Tag color={tagColors[i % tagColors.length]} key={i}>
                            {displayText}
                        </Tag>
                    );
                })}
            </div>
        </div>
    );
}

export default function UserShow({ user, jobs, loading }) {
    return loading ? (
        <Flex className="user-show" justify="center" align="center">
            <Spin />
        </Flex>
    ) : (
        user?.name && (
            <>
                <div
                    className="profile-sub-bg"
                    style={{
                        backgroundImage: "url(" + BgProfile + ")",
                    }}
                ></div>

                <Row className="user-profile" gutter={48}>
                    <Col
                        className="user-show-1"
                        span={24}
                        xs={24}
                        sm={24}
                        md={24}
                        lg={10}
                        xl={7}
                    >
                        <div className="user-detail">
                            <img
                                src={`/${user.logo}`}
                                className="user-show-avatar"
                                alt="Logo"
                            />

                            <div className="user-show-section">
                                <h2>{user.name}</h2>

                                <h3 className="user-show-email">
                                    <MailOutlined /> {user.email}
                                </h3>

                                <h3 className="user-show-location">
                                    <AimOutlined /> {user.location}
                                </h3>

                                <div style={{ marginTop: "1rem" }}>
                                    <strong>Industry</strong>
                                    <p>{user.industry}</p>
                                </div>

                                <strong>Specialism</strong>

                                <TagsList keywords={user.keywords.split(",")} />

                                <div style={{ marginTop: "1rem" }}>
                                    <strong>Latest Job Post</strong>
                                    <p>2025-01-01</p>
                                </div>
                            </div>
                        </div>
                    </Col>

                    <Col
                        className="user-show-2"
                        span={24}
                        xs={24}
                        sm={24}
                        md={24}
                        lg={14}
                        xl={17}
                    >
                        <Timeline>
                            {jobs.map((job) => (
                                <Timeline.Item key={job.id}>
                                    <Card
                                        title={
                                            job.status === 1 ? (
                                                <NavLink to={`/jobs/${job.id}`}>
                                                    {job.job_title}(
                                                    {job.industry})
                                                </NavLink>
                                            ) : (
                                                `${job.job_title} (${job.industry})`
                                            )
                                        }
                                        extra={
                                            job.status === 1 && (
                                                <Badge
                                                    status="processing"
                                                    text="Active"
                                                />
                                            )
                                        }
                                    >
                                        <Space>
                                            <Tooltip title={"Salary"}>
                                                <Tag
                                                    icon={<PoundOutlined />}
                                                    color="green"
                                                >
                                                    {Math.round(job.salary)}{" "}
                                                    {job.salary_currency}
                                                </Tag>
                                            </Tooltip>
                                            <Tooltip title={"Fee"}>
                                                <Tag
                                                    icon={<DollarOutlined />}
                                                    color="red"
                                                >
                                                    {Math.round(job.fee)}{" "}
                                                    {job.fee_currency}
                                                </Tag>
                                            </Tooltip>
                                            <Tooltip title={"Posted Date"}>
                                                <Tag
                                                    icon={<FieldTimeOutlined />}
                                                    color="cyan"
                                                >
                                                    Posted{" "}
                                                    {moment(
                                                        job.created_at
                                                    ).fromNow()}
                                                </Tag>
                                            </Tooltip>
                                            <Tooltip title={"Started Date"}>
                                                <Tag
                                                    icon={<CalendarOutlined />}
                                                    color="blue"
                                                >
                                                    {new Date(
                                                        job.start_date
                                                    ).toLocaleDateString()}
                                                </Tag>
                                            </Tooltip>
                                            <Tooltip title={"Job Type"}>
                                                <Tag
                                                    icon={
                                                        <BorderlessTableOutlined />
                                                    }
                                                    color="magenta"
                                                >
                                                    {job.job_type}
                                                </Tag>
                                            </Tooltip>
                                            <Tooltip title={"Recruitment Type"}>
                                                <Tag
                                                    icon={<ForkOutlined />}
                                                    color="purple"
                                                >
                                                    {job.recruitment_type}
                                                </Tag>
                                            </Tooltip>
                                        </Space>
                                    </Card>
                                </Timeline.Item>
                            ))}
                        </Timeline>
                    </Col>
                </Row>
            </>
        )
    );
}
