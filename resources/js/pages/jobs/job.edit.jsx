import React, { useEffect, useState } from "react";
import {
    Card,
    Table,
    Button,
    Space,
    Popconfirm,
    message,
    Form,
    Input,
    Spin,
    Col,
    Flex,
    Row,
    InputNumber,
    Drawer,
    Select,
    DatePicker,
    List,
} from "antd";
import {
    SearchOutlined,
    EditOutlined,
    SendOutlined,
    DeleteOutlined,
} from "@ant-design/icons";
import moment from "moment";
import dayjs from "dayjs";

import { useParams, useNavigate } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
const { TextArea } = Input;
const { Option } = Select;

import {
    useGetJobQuery,
    useEditJobMutation,
    useSearchJobMutation,
} from "./jobs.service";

import { setJob } from "./jobs.slice";
export default function Jobs({}) {
    const navigate = useNavigate();
    const dispatch = useDispatch();
    const { id } = useParams();
    const [jobData, setJobData] = useState(null);
    const [form] = Form.useForm();

    const { data, isLoading, isSuccess } = useGetJobQuery(id, {
        refetchOnMountOrArgChange: true,
    });

    const [editJob, { isLoading: isLoadingJob, isSuccess: isSuccessJob }] =
        useEditJobMutation();

    const jobs = useSelector((apps) => apps.job.jobs);
    const name = useSelector((apps) => apps.app.user.name);

    const handleFormSubmit = async () => {
        const jobValue = form.getFieldsValue();
        console.log(jobValue);

        try {
            const { data } = await editJob({
                ...jobValue,
                id,
                start_date: jobValue.start_date.format("YYYY-MM-DD"),
            });
            // console.log(data?.job);
            dispatch(
                setJob(
                    jobs.map((e) => (e.id != id ? e : { ...e, ...data.job }))
                )
            );

            //navigate("/jobs");
            //OnCloseEdit();
            message.success("Job updated successfully!");
        } catch (err) {
            console.log(err);
        }
    };
    const handleDateChange = (date, dateString) => {
        console.log("Selected Date:", date);
        console.log("Formatted Date:", dateString);
    };

    useEffect(() => {
        if (isSuccess) {
            form.setFieldsValue({
                ...data?.job,
                start_date: dayjs(data?.job.start_date, "YYYY-MM-DD"),
            });
        }
    }, [isSuccess, form, data]);

    if (isLoading)
        return (
            <Flex justify="center" align="center">
                <Spin />
            </Flex>
        );
    return (
        <div>
            <Form form={form} layout="vertical" onFinish={handleFormSubmit}>
                <Form.Item
                    name="job_title"
                    label="Job Title"
                    rules={[
                        {
                            required: true,
                            message: "Please input the job title!",
                        },
                    ]}
                >
                    <Input placeholder="Enter job title" />
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
                    <Select placeholder="Select job type">
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
                    <Select placeholder="Select recruitment type">
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
                    <Input placeholder="Enter industry" />
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
                    <Input placeholder="Enter location" />
                </Form.Item>

                <Form.Item
                    name="salary"
                    label="Salary"
                    rules={[
                        {
                            required: true,
                            message: "Please input the salary!",
                        },
                    ]}
                >
                    <InputNumber
                        style={{ width: "100%" }}
                        formatter={(value) =>
                            `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }
                        parser={(value) => value.replace(/\$\s?|(,*)/g, "")}
                        placeholder="Enter salary"
                    />
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
                    <DatePicker style={{ width: "100%" }} />
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
                        style={{ width: "100%" }}
                        formatter={(value) => `${value}%`}
                        parser={(value) => value.replace("%", "")}
                        placeholder="Enter margin agreed"
                    />
                </Form.Item>

                <Form.Item
                    name="fee"
                    label="Fee"
                    rules={[
                        {
                            required: true,
                            message: "Please input the fee!",
                        },
                    ]}
                >
                    <InputNumber
                        style={{ width: "100%" }}
                        formatter={(value) =>
                            `$ ${value}`.replace(/\B(?=(\d{3})+(?!\d))/g, ",")
                        }
                        parser={(value) => value.replace(/\$\s?|(,*)/g, "")}
                        placeholder="Enter fee"
                    />
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
                    <TextArea rows={4} placeholder="Enter job description" />
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
                    <Select placeholder="Select status">
                        <Option value={1}>inactive</Option>
                        <Option value={2}>active</Option>
                    </Select>
                </Form.Item>

                {/* Action buttons */}
                <Flex justify="flex-end">
                    <Button
                        type="default"
                        style={{ marginRight: 8 }}
                        onClick={() => window.history.back()}
                    >
                        Discard
                    </Button>
                    <Button type="primary" htmlType="submit">
                        Save {<SendOutlined />}
                    </Button>
                </Flex>
            </Form>
        </div>
    );
}
