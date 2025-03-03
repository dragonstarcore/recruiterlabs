import React, { useEffect, useState } from "react";
import { NavLink, useNavigate } from "react-router-dom";

import moment from "moment";
import { toast } from "react-toastify";
import {
    Avatar,
    Button,
    Col,
    Descriptions,
    Divider,
    Flex,
    Row,
    Space,
    Spin,
    Tag,
    Tooltip,
    Input,
} from "antd";

import {
    ArrowLeftOutlined,
    BorderlessTableOutlined,
    CalendarOutlined,
    CheckSquareOutlined,
    DollarOutlined,
    EnvironmentOutlined,
    FieldTimeOutlined,
    ForkOutlined,
    MailOutlined,
    PoundOutlined,
    UserOutlined,
} from "@ant-design/icons";

import "./styles.css";
import { useSelector } from "react-redux";

const { TextArea } = Input;

export default function JobShowComponent({ job, loading }) {
    const navigate = useNavigate();

    const userData = useSelector((apps) => apps.app.user);

    const [isInterested, setIsInterested] = useState(false);
    const [message, setMessage] = useState("");

    useEffect(() => {
        if (isInterested) {
            setTimeout(() => {
                window.scrollTo({
                    top: document.body.scrollHeight,
                    behavior: "smooth",
                });
            }, 400);
        }
    }, [isInterested]);

    const handleButtonClick = () => {
        setIsInterested(!isInterested);
    };

    const submit = () => {
        toast.success("Mail sent successfully!");
        navigate("/jobshared");
    };

    return loading ? (
        <Flex className="job-show" justify="center" align="center">
            <Spin />
        </Flex>
    ) : (
        <Row className="job-show">
            <Col
                className="job-show-1"
                span={24}
                xs={24}
                sm={24}
                md={18}
                lg={18}
                xl={18}
            >
                <h1 className="job-show-title">{job.job_title}</h1>

                <Space>
                    <Tooltip title={"Salary"}>
                        <Tag icon={<PoundOutlined />} color="green">
                            {Math.round(job.salary)} {job.salary_currency}
                        </Tag>
                    </Tooltip>
                    <Tooltip title={"Fee"}>
                        <Tag icon={<DollarOutlined />} color="red">
                            {Math.round(job.fee)} {job.fee_currency}
                        </Tag>
                    </Tooltip>
                    <Tooltip title={"Margin Agreed"}>
                        <Tag icon={<CheckSquareOutlined />} color="blue">
                            {Math.round(job.margin_agreed)} %
                        </Tag>
                    </Tooltip>
                </Space>

                <Divider className="job-show-hr" />

                <Space className="job-show-tags">
                    <Tooltip title={"Posted Date"}>
                        <Tag icon={<FieldTimeOutlined />} color="cyan">
                            Posted {moment(job.created_at).fromNow()}
                        </Tag>
                    </Tooltip>
                    <Tooltip title={"Started Date"}>
                        <Tag icon={<CalendarOutlined />} color="blue">
                            {new Date(job.start_date).toLocaleDateString()}
                        </Tag>
                    </Tooltip>
                    <Tooltip title={"Location"}>
                        <Tag icon={<EnvironmentOutlined />} color="volcano">
                            {job.location}
                        </Tag>
                    </Tooltip>
                    <Tooltip title={"Job Type"}>
                        <Tag icon={<BorderlessTableOutlined />} color="magenta">
                            {job.job_type}
                        </Tag>
                    </Tooltip>
                    <Tooltip title={"Recruitment Type"}>
                        <Tag icon={<ForkOutlined />} color="purple">
                            {job.recruitment_type}
                        </Tag>
                    </Tooltip>
                </Space>

                <h3 className="job-show-desc">Description</h3>
                <div className="job-show-content">{job.job_description}</div>

                <div
                    className={`interest-message ${
                        isInterested ? "active" : ""
                    }`}
                >
                    {isInterested && (
                        <>
                            <h3 className="job-interest-title">
                                I'm interested in working on this vacancy
                            </h3>

                            <TextArea
                                rows={4}
                                value={message}
                                onChange={(e) => setMessage(e.target.value)}
                                placeholder="Write your message here..."
                            />

                            <Button
                                type="primary"
                                onClick={() => submit()}
                                className="job-card-submit"
                            >
                                <MailOutlined /> Send Email
                            </Button>
                        </>
                    )}
                </div>
            </Col>
            <Col
                className="job-show-2"
                span={24}
                xs={24}
                sm={24}
                md={6}
                lg={6}
                xl={6}
            >
                <div className="job-show-btns">
                    <Button
                        className="job-show-inter-button"
                        onClick={() => {
                            navigate(-1);
                        }}
                    >
                        <ArrowLeftOutlined />
                        GO BACK
                    </Button>

                    <Button
                        type="primary"
                        className="job-show-inter-button"
                        onClick={handleButtonClick}
                    >
                        I AM INTERESTED
                    </Button>
                </div>

                <Avatar
                    shape="square"
                    src={`/${job?.user?.user_details?.logo}`}
                    icon={<UserOutlined />}
                    className="job-show-user-img"
                />

                <Descriptions
                    className="job-show-user-detail"
                    bordered
                    column={1}
                >
                    <Descriptions.Item label="Name">
                        {job.user.user_details.company_name}
                    </Descriptions.Item>
                    <Descriptions.Item label="Email">
                        {job.user.email}
                    </Descriptions.Item>
                    <Descriptions.Item label="Address">
                        {job.user.user_details.registered_address}
                    </Descriptions.Item>
                </Descriptions>
            </Col>
        </Row>
    );
}
