import React from "react";
import { NavLink, useParams } from "react-router-dom";

import { Card, Descriptions, Badge, Button } from "antd";

import { useFetchJobQuery } from "./jobs.service";
import { ArrowLeftOutlined } from "@ant-design/icons";

export default function JobShow() {
    const { id } = useParams();

    const { data = { job: {} }, isFetching } = useFetchJobQuery(id);

    return (
        <Card
            title={
                <h2 style={{ marginBottom: "0px" }}>{data.job.job_title}</h2>
            }
            bordered={false}
            extra={
                <NavLink to="/jobs">
                    <Button type="primary">
                        <ArrowLeftOutlined />
                        Back
                    </Button>
                </NavLink>
            }
            loading={isFetching}
        >
            <Descriptions bordered column={1}>
                <Descriptions.Item label="Job Type">
                    {data.job.job_type}
                </Descriptions.Item>
                <Descriptions.Item label="Recruitment Type">
                    {data.job.recruitment_type}
                </Descriptions.Item>
                <Descriptions.Item label="Location">
                    {data.job.location}
                </Descriptions.Item>
                <Descriptions.Item label="Industry">
                    {data.job.industry}
                </Descriptions.Item>
                <Descriptions.Item label="Salary">{`${data.job.salary} ${data.job.salary_currency}`}</Descriptions.Item>
                <Descriptions.Item label="Fee">{`${data.job.fee} ${data.job.fee_currency}`}</Descriptions.Item>
                <Descriptions.Item label="Margin Agreed">
                    {data.job.margin_agreed}
                </Descriptions.Item>
                <Descriptions.Item label="Start Date">
                    {data.job.start_date}
                </Descriptions.Item>
                <Descriptions.Item label="Status">
                    <Badge
                        status={data.job.status === 1 ? "processing" : "error"}
                        text={data.job.status === 1 ? "Active" : "Inactive"}
                    />
                </Descriptions.Item>
                <Descriptions.Item label="Job Description">
                    <p>{data.job.job_description}</p>
                </Descriptions.Item>
            </Descriptions>
        </Card>
    );
}
