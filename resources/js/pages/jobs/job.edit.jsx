import React, { useEffect } from "react";
import { useParams, NavLink, useNavigate } from "react-router-dom";

import { toast } from "react-toastify";
import dayjs from "dayjs";
import {
    Form,
    Input,
    Select,
    DatePicker,
    InputNumber,
    Button,
    Card,
    Tag,
} from "antd";
import { ArrowLeftOutlined } from "@ant-design/icons";

import { useEditJobMutation, useFetchJobQuery } from "./jobs.service";

const { Option } = Select;
const { TextArea } = Input;

export default function JobEdit() {
    const [form] = Form.useForm();

    const navigate = useNavigate();

    const { id } = useParams();

    const { data = { job: {} }, isLoading } = useFetchJobQuery(id);

    const [editJob, { isLoading: editJobIsLoading, isSuccess, isError }] =
        useEditJobMutation();

    useEffect(() => {
        form.setFieldsValue({
            ...data?.job,
            start_date: data.job.start_date ? dayjs(data.job.start_date) : null,
        });
    }, [form, data]);

    const onFinish = (values) => {
        editJob({
            id,
            data: {
                ...values,
                start_date: values.start_date.format("YYYY-MM-DD"),
            },
        });
    };

    useEffect(() => {
        if (isSuccess) {
            toast.success("Job updated successfully!");
            form.resetFields();
            navigate("/jobs");
        }
    }, [isSuccess, form]);

    useEffect(() => {
        if (isError) toast.error("Job updated failed!");
    }, [isError]);

    return (
        <Card
            loading={isLoading}
            title={<h2 style={{ marginBottom: "0px" }}>Edit a Job</h2>}
            extra={
                <NavLink to="/jobs">
                    <Button type="primary">
                        <ArrowLeftOutlined />
                        Back
                    </Button>
                </NavLink>
            }
            style={{ padding: "2rem 4rem" }}
        >
            <Form
                form={form}
                disabled={editJobIsLoading}
                name="editJob"
                onFinish={onFinish}
                labelCol={{
                    span: 6,
                }}
                wrapperCol={{
                    span: 18,
                }}
            >
                <Form.Item
                    name="job_title"
                    label="Title"
                    rules={[
                        {
                            required: true,
                            message: "Please input the job title!",
                        },
                    ]}
                >
                    <Input />
                </Form.Item>

                <Form.Item
                    name="job_type"
                    label="Job Type"
                    rules={[
                        {
                            required: true,
                            message: "Please select the job type!",
                        },
                    ]}
                >
                    <Select size="large">
                        <Option value="Permanent">Permanent</Option>
                        <Option value="Contract">Contract</Option>
                    </Select>
                </Form.Item>

                <Form.Item
                    name="recruitment_type"
                    label="Recruitment Type"
                    rules={[
                        {
                            required: true,
                            message: "Please select the recruitment type!",
                        },
                    ]}
                >
                    <Select size="large">
                        <Option value="Contingent">Contingent</Option>
                        <Option value="Retained">Retained</Option>
                    </Select>
                </Form.Item>

                <Form.Item
                    name="industry"
                    label="Industry"
                    rules={[
                        {
                            required: true,
                            message: "Please input the industry!",
                        },
                    ]}
                >
                    <Input />
                </Form.Item>

                <Form.Item
                    name="job_description"
                    label="Job Description"
                    rules={[
                        {
                            required: true,
                            message: "Please input the job description!",
                        },
                    ]}
                >
                    <TextArea rows={4} />
                </Form.Item>

                <Form.Item
                    name="location"
                    label="Location"
                    rules={[
                        {
                            required: true,
                            message: "Please input the location!",
                        },
                    ]}
                >
                    <Input />
                </Form.Item>

                <Form.Item label="Salary" style={{ marginBottom: 0 }}>
                    <Form.Item
                        name="salary_currency"
                        rules={[
                            {
                                required: true,
                                message: "Please select a currency!",
                            },
                        ]}
                        style={{
                            display: "inline-block",
                            width: "calc(20% - 8px)",
                        }}
                    >
                        <Select size="large">
                            <Option value="GBP">GBP</Option>
                            <Option value="EUR">EUR</Option>
                            <Option value="USD">USD</Option>
                        </Select>
                    </Form.Item>
                    <Form.Item
                        name="salary"
                        rules={[
                            {
                                required: true,
                                message: "Please input the salary!",
                            },
                        ]}
                        style={{
                            display: "inline-block",
                            width: "calc(80% - 8px)",
                            margin: "0 8px",
                        }}
                    >
                        <InputNumber size="large" style={{ width: "100%" }} />
                    </Form.Item>
                </Form.Item>

                <Form.Item label="Fee" style={{ marginBottom: 0 }}>
                    <Form.Item
                        name="fee_currency"
                        rules={[
                            {
                                required: true,
                                message: "Please select a currency!",
                            },
                        ]}
                        style={{
                            display: "inline-block",
                            width: "calc(20% - 8px)",
                        }}
                    >
                        <Select size="large">
                            <Option value="GBP">GBP</Option>
                            <Option value="EUR">EUR</Option>
                            <Option value="USD">USD</Option>
                        </Select>
                    </Form.Item>
                    <Form.Item
                        name="fee"
                        rules={[
                            {
                                required: true,
                                message: "Please input the fee!",
                            },
                        ]}
                        style={{
                            display: "inline-block",
                            width: "calc(80% - 8px)",
                            margin: "0 8px",
                        }}
                    >
                        <InputNumber size="large" style={{ width: "100%" }} />
                    </Form.Item>
                </Form.Item>

                <Form.Item
                    name="start_date"
                    label="Start Date"
                    rules={[
                        {
                            required: true,
                            message: "Please select the start date!",
                        },
                    ]}
                >
                    <DatePicker size="large" style={{ width: "100%" }} />
                </Form.Item>

                <Form.Item
                    name="margin_agreed"
                    label="Margin Agreed"
                    rules={[
                        {
                            required: true,
                            message: "Please input the margin agreed!",
                        },
                    ]}
                >
                    <InputNumber
                        size="large"
                        style={{ width: "100%" }}
                        formatter={(value) => `${value}%`}
                        parser={(value) => value.replace("%", "")}
                    />
                </Form.Item>

                <Form.Item
                    name="status"
                    label="Job Status"
                    rules={[
                        {
                            required: true,
                            message: "Please select the status!",
                        },
                    ]}
                >
                    <Select size="large">
                        <Option key={1} value={1}>
                            <Tag color="green">Active</Tag>
                        </Option>
                        <Option key={2} value={2}>
                            <Tag color="red">Inactive</Tag>
                        </Option>
                    </Select>
                </Form.Item>

                <Button
                    type="primary"
                    htmlType="submit"
                    style={{ float: "right" }}
                >
                    Submit
                </Button>
            </Form>
        </Card>
    );
}
