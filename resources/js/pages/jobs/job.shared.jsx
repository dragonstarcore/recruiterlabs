import React, { useState } from "react";

import {
    Card,
    Row,
    Col,
    Button,
    Drawer,
    Input,
    Space,
    Select,
    Pagination,
    Divider,
    Alert,
} from "antd";
import {
    AimOutlined,
    ArrowUpOutlined,
    CheckSquareOutlined,
    FileTextOutlined,
    SearchOutlined,
} from "@ant-design/icons";

import { useFetchSharedJobQuery } from "./jobs.service";
import JobCard from "../../components/JobCard";

const { Option } = Select;

export default function JobShare() {
    const { data = { jobs: [] }, isFetching } = useFetchSharedJobQuery();

    const [drawerVisible, setDrawerVisible] = useState(false);
    const [filters, setFilters] = useState({
        title: "",
        jobType: [],
        recruitmentType: [],
        industry: "",
        location: "",
    });
    const [sortOption, setSortOption] = useState("latest");
    const [currentPage, setCurrentPage] = useState(1);
    const [pageSize, setPageSize] = useState(10);

    const showDrawer = () => {
        setDrawerVisible(true);
    };

    const onClose = () => {
        setDrawerVisible(false);
    };

    const handleFilterChange = (key, value) => {
        setFilters((prevFilters) => ({
            ...prevFilters,
            [key]: value.toLowerCase(),
        }));
    };

    const handleSortChange = (value) => {
        setSortOption(value);
    };

    const handlePageChange = (page, pageSize) => {
        setCurrentPage(page);
        setPageSize(pageSize);
    };

    const sortJobs = (jobs) => {
        switch (sortOption) {
            case "startDay":
                return jobs.sort(
                    (a, b) => new Date(a.start_date) - new Date(b.start_date)
                );
            case "salary":
                return jobs.sort((a, b) => b.salary - a.salary);
            case "fee":
                return jobs.sort((a, b) => b.fee - a.fee);
            case "marginAgreed":
                return jobs.sort((a, b) => b.margin_agreed - a.margin_agreed);
            default:
                return jobs;
        }
    };

    const filteredJobs = data.jobs.filter((job) => {
        return (
            (filters.title === "" ||
                job.job_title?.toLowerCase().includes(filters.title)) &&
            (filters.jobType.length === 0 ||
                filters.jobType?.toLowerCase().includes(job.job_type)) &&
            (filters.recruitmentType.length === 0 ||
                filters.recruitmentType
                    ?.toLowerCase()
                    .includes(job.recruitment_type)) &&
            (filters.industry === "" ||
                job.industry?.toLowerCase().includes(filters.industry)) &&
            (filters.location === "" ||
                job.location?.toLowerCase().includes(filters.location))
        );
    });

    const sortedJobs = sortJobs(filteredJobs);

    const paginatedJobs = sortedJobs.slice(
        (currentPage - 1) * pageSize,
        currentPage * pageSize
    );

    return (
        <div>
            <Row gutter={16}>
                <Col xs={0} sm={0} md={0} lg={0} xl={6}>
                    <Card title="Filter Jobs" bordered={false}>
                        <Space direction="vertical">
                            <Input
                                placeholder="Title & Content"
                                addonBefore={<FileTextOutlined />}
                                value={filters.title}
                                onChange={(e) =>
                                    handleFilterChange("title", e.target.value)
                                }
                            />

                            <Select
                                size="large"
                                placeholder="Job Type"
                                style={{ width: "100%" }}
                                allowClear={true}
                                mode="tags"
                                value={filters.jobType}
                                onChange={(value) =>
                                    handleFilterChange("jobType", value)
                                }
                            >
                                <Option value="Permanent">Permanent</Option>
                                <Option value="Contract">Contract</Option>
                            </Select>

                            <Select
                                size="large"
                                mode="tags"
                                style={{ width: "100%" }}
                                placeholder="Recruitment Type"
                                allowClear={true}
                                value={filters.recruitmentType}
                                onChange={(value) =>
                                    handleFilterChange("recruitmentType", value)
                                }
                            >
                                <Option value="Contingent">Contingent</Option>
                                <Option value="Retained">Retained</Option>
                            </Select>

                            <Input
                                placeholder="Industry"
                                addonBefore={<CheckSquareOutlined />}
                                value={filters.industry}
                                onChange={(e) =>
                                    handleFilterChange(
                                        "industry",
                                        e.target.value
                                    )
                                }
                            />

                            <Input
                                placeholder="Location"
                                addonBefore={<AimOutlined />}
                                value={filters.location}
                                onChange={(e) =>
                                    handleFilterChange(
                                        "location",
                                        e.target.value
                                    )
                                }
                            />
                        </Space>
                    </Card>
                </Col>
                <Col xs={24} sm={24} md={24} lg={24} xl={18}>
                    <Card
                        loading={isFetching}
                        title="Shared Jobs List"
                        bordered={false}
                        extra={
                            <Row style={{ width: "400px" }} gutter={24}>
                                <Col xs={6} sm={6} md={6} lg={6} xl={0}></Col>
                                <Col xs={4} sm={4} md={4} lg={4} xl={0}>
                                    <Button type="primary" onClick={showDrawer}>
                                        <SearchOutlined />
                                        Filter
                                    </Button>
                                </Col>
                                <Col xs={2} sm={2} md={2} lg={2} xl={10}></Col>
                                <Col xs={12} sm={12} md={12} lg={12} xl={12}>
                                    <Select
                                        prefix={
                                            <>
                                                <ArrowUpOutlined />
                                            </>
                                        }
                                        size="large"
                                        style={{ width: "100%" }}
                                        value={sortOption}
                                        onChange={handleSortChange}
                                    >
                                        <Option value="latest">Latest</Option>
                                        <Option value="startDay">
                                            Start Day
                                        </Option>
                                        <Option value="salary">Salary</Option>
                                        <Option value="fee">Fee</Option>
                                        <Option value="marginAgreed">
                                            Margin Agreed
                                        </Option>
                                    </Select>
                                </Col>
                            </Row>
                        }
                    >
                        {paginatedJobs.length ? (
                            paginatedJobs.map((item) => (
                                <JobCard key={item.id} job={item} />
                            ))
                        ) : (
                            <Alert
                                message="There are no job openings matching your industry."
                                type="success"
                            />
                        )}

                        <Divider />

                        <Pagination
                            current={currentPage}
                            pageSize={pageSize}
                            total={sortedJobs.length}
                            onChange={handlePageChange}
                            showSizeChanger
                            pageSizeOptions={["10", "20", "50"]}
                        />
                    </Card>
                </Col>
            </Row>

            <Drawer
                title="Filter Jobs"
                placement="left"
                closable={true}
                onClose={onClose}
                open={drawerVisible}
                width={300}
            >
                <Space direction="vertical">
                    <Input
                        placeholder="Title & Content"
                        addonBefore={<FileTextOutlined />}
                        value={filters.title}
                        onChange={(e) =>
                            handleFilterChange("title", e.target.value)
                        }
                    />

                    <Select
                        size="large"
                        placeholder="Job Type"
                        style={{ width: "100%" }}
                        allowClear={true}
                        mode="tags"
                        value={filters.jobType}
                        onChange={(value) =>
                            handleFilterChange("jobType", value)
                        }
                    >
                        <Option value="Permanent">Permanent</Option>
                        <Option value="Contract">Contract</Option>
                    </Select>

                    <Select
                        size="large"
                        mode="tags"
                        style={{ width: "100%" }}
                        placeholder="Recruitment Type"
                        allowClear={true}
                        value={filters.recruitmentType}
                        onChange={(value) =>
                            handleFilterChange("recruitmentType", value)
                        }
                    >
                        <Option value="Contingent">Contingent</Option>
                        <Option value="Retained">Retained</Option>
                    </Select>

                    <Input
                        placeholder="Industry"
                        addonBefore={<CheckSquareOutlined />}
                        value={filters.industry}
                        onChange={(e) =>
                            handleFilterChange("industry", e.target.value)
                        }
                    />

                    <Input
                        placeholder="Location"
                        addonBefore={<AimOutlined />}
                        value={filters.location}
                        onChange={(e) =>
                            handleFilterChange("location", e.target.value)
                        }
                    />
                </Space>
            </Drawer>
        </div>
    );
}
