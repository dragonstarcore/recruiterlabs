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
    Modal,
    Avatar,
    Pagination,
    List,
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

import { useDispatch, useSelector } from "react-redux";
const { TextArea } = Input;
const { Option } = Select;
import {
    useFetchSharedJobQuery,
    useDeleteJobMutation,
    useSearchJobMutation,
    useAddJobMutation,
    useApplyJobMutation,
} from "./jobs.service";
import { setJob, removeJob } from "./jobs.slice";
import moment from "moment";
import "./style.css";
export default function Jobs() {
    const navigate = useNavigate();
    const dispatch = useDispatch();
    const [form] = Form.useForm();
    const user_id = useSelector((apps) => apps.app.user.id);
    const [isModalOpen, setIsModalOpen] = useState(false);
    const [job_id, setJob_id] = useState(null);

    const [isEditDrawer, setIsEditDrawer] = useState(false);
    const { data, isLoading, isSuccess } = useFetchSharedJobQuery(user_id, {
        refetchOnMountOrArgChange: true,
    });
    const [applyJob] = useApplyJobMutation();
    const showModal = () => {
        setIsModalOpen(true);
    };

    // Function to handle modal close
    const handleCancel = () => {
        setIsModalOpen(false);
    };
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
    const [questions, setQuestions] = useState();
    const handleSubmit = async () => {
        await applyJob({ job_id, user_id, questions });

        setIsModalOpen(false); // Close the modal after submission
    };
    useEffect(() => {
        if (isSuccess) {
            dispatch(setJob(data?.jobs));
        }
    }, [isSuccess, data, dispatch]);

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
                isShared: true,
            };
            const { data } = await searchJob(filters);
            dispatch(setJob(data?.jobs));
        } catch (err) {
            console.log(err);
        }
    };
    const OnInterestedHandle = (id) => {
        showModal();
        setJob_id(id);
    };
    if (isLoading)
        return (
            <Flex justify="center" align="center">
                <Spin />
            </Flex>
        );
    return (
        <div>
            <Row>
                <Col
                    span={8}
                    xs={24}
                    sm={24}
                    md={24}
                    lg={4}
                    xl={4}
                    style={{ padding: "10px" }}
                >
                    <Flex justify="center">
                        <Row gutter={[8, 8]} style={{ marginBottom: "10px" }}>
                            <Col span={24}>
                                <Input
                                    value={search_title}
                                    onChange={OnSearchTitle}
                                    placeholder="title"
                                    addonBefore={<FileTextOutlined />}
                                />
                            </Col>
                            <Col span={24}>
                                <Input
                                    onChange={OnSearchLocation}
                                    addonBefore={<AimOutlined />}
                                    placeholder="location"
                                />
                            </Col>
                            <Col span={24}>
                                <Select
                                    placeholder="Select job type"
                                    style={{ width: "100%" }}
                                    onChange={OnJobTypeChange}
                                >
                                    <Option value="">Select Job Type</Option>
                                    <Option value="Permanent">Permanent</Option>
                                    <Option value="Contract">Contract</Option>
                                </Select>
                            </Col>
                            <Col span={24}>
                                <Select
                                    onChange={OnRecruitment_typeChange}
                                    style={{ width: "100%" }}
                                    placeholder="Select recruitment type"
                                >
                                    <Option value="">
                                        Select Recruitment Type
                                    </Option>
                                    <Option value="Contingent">
                                        Contingent
                                    </Option>
                                    <Option value="Retained">Retained</Option>
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
                                    <Option value={null}>Select Status</Option>
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
                    <div className="job-container">
                        {jobs.map((job) => (
                            <Card style={{ marginTop: "10px" }}>
                                <Row gutter={[8, 8]} justify={"space-between"}>
                                    <Col>
                                        <Avatar
                                            src={
                                                "/assets/images/default_user.jpg"
                                            }
                                            size={36}
                                            shape="circle"
                                        />
                                        By {job.user?.name}
                                    </Col>
                                    <Col>${job.salary}</Col>
                                </Row>
                                <Row>
                                    <Col>
                                        <h2>{job.job_title}</h2>
                                    </Col>
                                </Row>
                                <Row>
                                    <Col className="job-description" span={24}>
                                        {job.job_description}
                                    </Col>
                                </Row>
                                <Row gutter={[8, 8]} justify={"space-between"}>
                                    <Col>
                                        <strong>Industry-</strong>
                                        {job.industry}
                                    </Col>
                                    <Col>
                                        <span className="card-container-location">
                                            <AimOutlined />
                                            {job.location}
                                        </span>
                                    </Col>
                                </Row>
                                <Flex
                                    justify="flex-end"
                                    style={{ marginTop: "20px" }}
                                >
                                    <Button
                                        type="primary"
                                        onClick={() =>
                                            OnInterestedHandle(job.id)
                                        }
                                    >
                                        I'm Interested
                                    </Button>
                                </Flex>
                            </Card>
                        ))}
                    </div>
                    <Pagination />
                </Col>
                <Modal
                    title="I'm interested in working on this vacancy" // Modal title
                    open={isModalOpen} // Control modal visibility
                    onOk={handleSubmit} // Handle form submission
                    onCancel={handleCancel} // Handle modal close
                >
                    {/* Input field inside the modal */}
                    <Input
                        placeholder="Enter your questions"
                        onChange={(e) => {
                            setQuestions(e.target.value);
                        }}
                    />
                </Modal>
            </Row>
        </div>
    );
}
