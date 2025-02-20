import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";

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
    Drawer,
    Select,
    InputNumber,
    DatePicker,
    ConfigProvider,
} from "antd";
import {
    SearchOutlined,
    EditOutlined,
    SendOutlined,
    ShoppingOutlined,
    DeleteOutlined,
    AimOutlined,
    DollarOutlined,
    BankOutlined,
    FileTextOutlined,
} from "@ant-design/icons";
import { toast } from "react-toastify";
import { useDispatch, useSelector } from "react-redux";
const { TextArea } = Input;
const { Option } = Select;
import {
    useFetchJobQuery,
    useDeleteJobMutation,
    useSearchJobMutation,
    useAddJobMutation,
} from "./jobs.service";
import { setJob, removeJob } from "./jobs.slice";
import moment from "moment";
export default function Jobs() {
    const navigate = useNavigate();
    const dispatch = useDispatch();
    const [form] = Form.useForm();
    const user_id = useSelector((apps) => apps.app.user.id);

    const [isEditDrawer, setIsEditDrawer] = useState(false);
    const { data, isLoading, isSuccess } = useFetchJobQuery(user_id, {
        refetchOnMountOrArgChange: true,
    });

    const jobs = useSelector((apps) => apps.job.jobs);
    const [
        deleteJob,
        { isLoading: isDeleteLoading, isSuccess: isDeleteSuccess },
    ] = useDeleteJobMutation();
    const [
        searchJob,
        { isLoading: isSearchLoading, isSuccess: isSearchSuccess },
    ] = useSearchJobMutation();

    const [isDrawer, setIsDrawer] = useState(false);
    const [addJob] = useAddJobMutation();
    const [jobId, setJobId] = useState(null);
    const { search_title, setSearch_title } = useState();

    useEffect(() => {
        if (isSuccess) {
            dispatch(setJob(data?.jobs));
        }
    }, [isSuccess, data, dispatch]);

    const handleFormSubmit = async () => {
        const jobValue = form.getFieldsValue();

        const jobData = {
            ...jobValue,
            user_id,
            start_date: jobValue.start_date.format("YYYY-MM-DD"), // Replace the date with the formatted value
        };

        setIsDrawer(false);
        try {
            const { data } = await addJob(jobData);
            dispatch(setJob([...jobs, data?.job]));
            toast.success("job added successfully", {
                position: "top-right",
            });
            form.resetFields();
        } catch (err) {
            console.log(err);
        }
    };
    const handleEdit = (jobId) => {
        navigate("/jobs/" + jobId);
        // Handle the edit functionality here, for example navigate to the edit page
    };
    const handleDelete = async (jobId) => {
        // Perform delete logic here, e.g., call an API to delete the job
        try {
            await deleteJob(jobId);
            dispatch(setJob(jobs.filter((job) => job.id != jobId)));
            //dispatch(removeJob());
            toast.success("job deleted successfully", {
                position: "top-right",
            });
        } catch (err) {
            console.log(err);
        }
    };
    const OnOpenDrawer = () => {
        setIsDrawer(true);
    };
    const [searchTitle, setSearchTitle] = useState("");
    const [searchLocation, setSearchLocation] = useState("");
    const [searchIndustry, setSearchIndustry] = useState("");
    const [searchFee, setSearchFee] = useState("");
    const [salary, setSalary] = useState("");
    const [jobType, setJobType] = useState("");
    const [status, setStatus] = useState(null);
    const [startDate, setStartDate] = useState(moment("2023-10-01"));
    const [recruitment_type, setRecruitment_type] = useState("");
    // Handler for search inputs
    const OnSearchTitle = (e) => {
        setSearchTitle(e.target.value);
    };
    const OnRecruitment_typeChange = (value) => {
        setRecruitment_type(value);
    };
    const OnSearchLocation = (e) => {
        setSearchLocation(e.target.value);
    };

    const OnSearchIndustry = (e) => {
        setSearchIndustry(e.target.value);
    };

    const OnSearchFee = (e) => {
        setSearchFee(e.target.value);
    };
    const OnSearchSalary = (e) => {
        setSalary(e.target.value);
    };
    const OnSearchStartDate = (value) => {
        setStartDate(moment(value).format("YYYY-MM-DD"));
    };
    const OnJobTypeChange = (value) => {
        setJobType(value);
    };
    const OnStatusChange = (value) => {
        setStatus(value);
    };

    const columns = [
        {
            title: "Job Title",
            dataIndex: "job_title",
            key: "jobTitle",
            render: (value) => (
                <div className="table-job-description">{value}</div>
            ),
        },
        {
            title: "Job Type",
            dataIndex: "job_type",
            key: "jobType",
        },
        {
            title: "Recruitment Type",
            dataIndex: "recruitment_type",
            key: "recruitmentType",
        },
        {
            title: "Industry",
            dataIndex: "industry",
            key: "industry",
        },
        {
            title: "Location",
            dataIndex: "location",
            key: "location",
        },
        {
            title: "Salary",
            dataIndex: "salary",
            key: "salary",
            render: (value) => `$ ${value.toLocaleString()}`,
        },
        {
            title: "Start Date",
            dataIndex: "start_date",
            key: "startDate",
            render: (date) => date, // Format the date
        },
        {
            title: "Margin Agreed",
            dataIndex: "margin_agreed",
            key: "marginAgreed",
            render: (value) => `${value}%`,
        },
        {
            title: "Fee",
            dataIndex: "fee",
            key: "fee",
            render: (value) => `$ ${value.toLocaleString()}`,
        },
        {
            title: "Job Description",
            dataIndex: "job_description",
            key: "jobDescription",
            render: (value) => (
                <div className="table-job-description">{value}</div>
            ),
        },
        {
            title: "Action",
            key: "action",
            render: (text, record) => (
                <Space size="small">
                    <Button
                        type="primary"
                        icon={<EditOutlined />}
                        onClick={() => handleEdit(record.id)}
                        size="small"
                    ></Button>

                    <Popconfirm
                        title="Are you sure you want to delete this ticket?"
                        onConfirm={() => handleDelete(record.id)}
                        okText="Yes"
                        cancelText="No"
                    >
                        <Button
                            type="default"
                            color="danger"
                            variant="solid"
                            icon={<DeleteOutlined />}
                            size="small"
                        ></Button>
                    </Popconfirm>
                </Space>
            ),
        },
    ];

    const Onfilter = async () => {
        try {
            const filters = {
                job_title: searchTitle,
                location: searchLocation,
                industry: searchIndustry,
                fee: searchFee,
                salary: salary,
                jobType: jobType,
                status: status,
                recruitment_type: recruitment_type,
                start_date: startDate,
                user_id,
                isShared: false,
            };
            const { data } = await searchJob(filters);
            dispatch(setJob(data?.jobs));
        } catch (err) {
            console.log(err);
        }
    };
    const totalCount = jobs?.length || 0;
    const [currentPage, setCurrentPage] = useState(1); // current page number
    const [pageSize, setPageSize] = useState(5); // number of items per page
    const onPageChange = (page, pageSize) => {
        setCurrentPage(page); // Update current page
        setPageSize(pageSize); // Update page size if the user changes it
    };

    if (isLoading)
        return (
            <Flex justify="center" align="center">
                <Spin />
            </Flex>
        );
    return (
        <div>
            <Card
                title="Jobs List"
                extra={
                    <Button type="primary" onClick={OnOpenDrawer}>
                        Create
                    </Button>
                }
            >
                <Row>
                    <Col
                        span={8}
                        xs={24}
                        sm={24}
                        md={4}
                        lg={4}
                        xl={4}
                        style={{ padding: "10px" }}
                    >
                        <Flex justify="center">
                            <Row
                                gutter={[8, 8]}
                                style={{ marginBottom: "10px" }}
                            >
                                <Col span={24}>
                                    <Input
                                        value={search_title}
                                        onChange={OnSearchTitle}
                                        addonBefore={<FileTextOutlined />}
                                        placeholder="title"
                                    />
                                </Col>
                                <Col span={24}>
                                    <Input
                                        onChange={OnSearchLocation}
                                        placeholder="location"
                                        addonBefore={<AimOutlined />}
                                    />
                                </Col>
                                <Col span={24}>
                                    <Select
                                        placeholder="Select job type"
                                        style={{ width: "100%" }}
                                        onChange={OnJobTypeChange}
                                    >
                                        <Option value="">
                                            Select Job Type
                                        </Option>
                                        <Option value="Permanent">
                                            Permanent
                                        </Option>
                                        <Option value="Contract">
                                            Contract
                                        </Option>
                                    </Select>
                                </Col>
                                <Col span={24}>
                                    <Select
                                        onChange={OnRecruitment_typeChange}
                                        style={{ width: "100%" }}
                                        placeholder="Select recruitment type"
                                    >
                                        <Option value={null}>
                                            Select recruitment type
                                        </Option>
                                        <Option value="Contingent">
                                            Contingent
                                        </Option>
                                        <Option value="Retained">
                                            Retained
                                        </Option>
                                    </Select>
                                </Col>
                                <Col span={24}>
                                    <Input
                                        onChange={(e) => OnSearchIndustry(e)}
                                        placeholder="industry"
                                        addonBefore={<BankOutlined />}
                                    />
                                </Col>

                                <Col span={24}>
                                    <Input
                                        onChange={(e) => OnSearchSalary(e)}
                                        placeholder="salary"
                                        addonBefore={<DollarOutlined />}
                                    />
                                </Col>
                                <Col span={24}>
                                    <Select
                                        placeholder="Select status"
                                        onChange={OnStatusChange}
                                        style={{ width: "100%" }}
                                    >
                                        <Option value={null}>
                                            Select status
                                        </Option>
                                        <Option value={1}>inactive</Option>
                                        <Option value={2}>active</Option>
                                    </Select>
                                </Col>
                                <Col span={24}>
                                    <Button
                                        type="primary"
                                        style={{ width: "100%" }}
                                        onClick={Onfilter}
                                    >
                                        Filter
                                    </Button>
                                </Col>
                            </Row>
                        </Flex>
                    </Col>
                    <Col span={20}>
                        <Table
                            columns={columns}
                            dataSource={jobs}
                            rowKey="id"
                            pagination={{
                                current: currentPage, // Current page number
                                pageSize: pageSize, // Number of items per page
                                total: totalCount, // Total number of items
                                onChange: onPageChange,
                                showSizeChanger: true, // Show size changer dropdown (optional)
                                pageSizeOptions: ["5", "10", "20", "50"], // Page size options
                            }}
                            scroll={{ x: "max-content" }}
                            bordered
                        />
                    </Col>
                </Row>

                <Drawer
                    title="Create job"
                    open={isDrawer}
                    onClose={() => {
                        form.resetFields();
                        setIsDrawer(false);
                    }}
                >
                    <Form
                        form={form}
                        initialValues={{ team: "" }}
                        layout="vertical"
                        onFinish={handleFormSubmit}
                    >
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
                                <Option key="" value={null}>
                                    Select job type
                                </Option>
                                <Option key="Permanent" value="Permanent">
                                    Permanent
                                </Option>
                                <Option key="Contract" value="Contract">
                                    Contract
                                </Option>
                            </Select>
                        </Form.Item>

                        <Form.Item
                            name="recruitment_type"
                            label="Recruitment Type"
                            rules={[
                                {
                                    required: true,
                                    message:
                                        "Please select the recruitment type!",
                                },
                            ]}
                        >
                            <Select placeholder="Select recruitment type">
                                <Option key="" value={null}>
                                    Select recruitment type
                                </Option>
                                <Option key="Contingent" value="Contingent">
                                    Contingent
                                </Option>
                                <Option key="Retained" value="Retained">
                                    Retained
                                </Option>
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
                                    `$ ${value}`.replace(
                                        /\B(?=(\d{24})+(?!\d))/g,
                                        ","
                                    )
                                }
                                parser={(value) =>
                                    value.replace(/\$\s?|(,*)/g, "")
                                }
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
                                    `$ ${value}`.replace(
                                        /\B(?=(\d{3})+(?!\d))/g,
                                        ","
                                    )
                                }
                                parser={(value) =>
                                    value.replace(/\$\s?|(,*)/g, "")
                                }
                                placeholder="Enter fee"
                            />
                        </Form.Item>

                        <Form.Item
                            name="job_description"
                            label="Job Description"
                            rules={[
                                {
                                    required: true,
                                    message:
                                        "Please input the job description!",
                                },
                            ]}
                        >
                            <TextArea
                                rows={4}
                                placeholder="Enter job description"
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
                            <Select placeholder="Select status">
                                <Option key="" value={null}>
                                    select status
                                </Option>
                                <Option key={1} value={1}>
                                    inactive
                                </Option>
                                <Option key={2} value={2}>
                                    active
                                </Option>
                            </Select>
                        </Form.Item>

                        {/* Action buttons */}
                        <Flex justify="flex-end">
                            <Button
                                type="default"
                                style={{ marginRight: 8 }}
                                onClick={() => setIsDrawer(false)}
                            >
                                Discard
                            </Button>
                            <Button type="primary" htmlType="submit">
                                Save {<SendOutlined />}
                            </Button>
                        </Flex>
                    </Form>
                </Drawer>
                {/* <Drawer
                    open={isEditDrawer}
                    title="Edit job"
                    onClose={() => setIsEditDrawer(false)}
                >
                    <JobEdit
                        jobId={jobId}
                        OnCloseEdit={() => setIsEditDrawer(false)}
                    />
                </Drawer> */}
            </Card>
        </div>
    );
}
