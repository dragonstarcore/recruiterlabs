import React, { useEffect, useState } from "react";
import { NavLink } from "react-router-dom";

import { toast } from "react-toastify";
import { Table, Input, Button, Space, Popconfirm, Card, Tag } from "antd";
import {
    SearchOutlined,
    EditOutlined,
    DeleteOutlined,
    EyeOutlined,
    PlusOutlined,
} from "@ant-design/icons";

import { useDeleteJobMutation, useFetchJobsQuery } from "./jobs.service";

export default function Jobs() {
    const { data = { jobs: [] }, isFetching } = useFetchJobsQuery();
    const [deleteJob, { isSuccess: isDeleteSuccess, isError: isDeleteError }] =
        useDeleteJobMutation();

    const [searchedColumn, setSearchedColumn] = useState("");

    const handleSearch = (_, confirm, dataIndex) => {
        confirm();
        setSearchedColumn(dataIndex);
    };

    const handleReset = (clearFilters) => {
        clearFilters();
    };

    const getColumnSearchProps = (dataIndex) => ({
        filterDropdown: ({
            setSelectedKeys,
            selectedKeys,
            confirm,
            clearFilters,
        }) => (
            <div style={{ padding: 8 }}>
                <Input
                    placeholder={`Search ${dataIndex}`}
                    value={selectedKeys[0]}
                    onChange={(e) =>
                        setSelectedKeys(e.target.value ? [e.target.value] : [])
                    }
                    onPressEnter={() =>
                        handleSearch(selectedKeys, confirm, dataIndex)
                    }
                    style={{ marginBottom: 8, display: "block" }}
                />
                <Space>
                    <Button
                        type="primary"
                        onClick={() =>
                            handleSearch(selectedKeys, confirm, dataIndex)
                        }
                        icon={<SearchOutlined />}
                        size="small"
                        style={{ width: 90 }}
                    >
                        Search
                    </Button>
                    <Button
                        onClick={() => handleReset(clearFilters)}
                        size="small"
                        style={{ width: 90 }}
                    >
                        Reset
                    </Button>
                </Space>
            </div>
        ),
        filterIcon: (filtered) => (
            <SearchOutlined
                style={{ color: filtered ? "#1890ff" : undefined }}
            />
        ),
        onFilter: (value, record) =>
            record[dataIndex]
                .toString()
                .toLowerCase()
                .includes(value.toLowerCase()),
        render: (text) =>
            searchedColumn === dataIndex ? <span>{text}</span> : text,
    });

    const getColumnSelectProps = (dataIndex, options) => ({
        filters: options.map((option) => ({ text: option, value: option })),
        onFilter: (value, record) => record[dataIndex] === value,
    });

    const handleDelete = (jobId) => {
        deleteJob(jobId);
    };

    useEffect(() => {
        if (isDeleteSuccess) toast.success("Job deleted successfully!");
    }, [isDeleteSuccess]);

    useEffect(() => {
        if (isDeleteError) toast.error("Failed to delete job!");
    }, [isDeleteError]);

    const columns = [
        {
            title: "Job Title",
            dataIndex: "job_title",
            key: "jobTitle",
            ...getColumnSearchProps("job_title"),
            sorter: (a, b) => a.job_title.localeCompare(b.job_title),
        },
        {
            title: "Job Type",
            dataIndex: "job_type",
            key: "jobType",
            ...getColumnSelectProps("job_type", ["Permanent", "Contract"]),
            sorter: (a, b) => a.job_type.localeCompare(b.job_type),
        },
        {
            title: "Recruitment Type",
            dataIndex: "recruitment_type",
            key: "recruitmentType",
            ...getColumnSelectProps("recruitment_type", [
                "Contingent",
                "Retained",
            ]),
            sorter: (a, b) =>
                a.recruitment_type.localeCompare(b.recruitment_type),
        },
        {
            title: "Industry",
            dataIndex: "industry",
            key: "industry",
            ...getColumnSearchProps("industry"),
            sorter: (a, b) => a.industry.localeCompare(b.industry),
        },
        {
            title: "Location",
            dataIndex: "location",
            key: "location",
            ...getColumnSearchProps("location"),
            sorter: (a, b) => a.location.localeCompare(b.location),
        },
        {
            title: "Salary",
            dataIndex: "salary",
            key: "salary",
            render: (value, { salary_currency }) => {
                const currencySymbol = (() => {
                    switch (salary_currency) {
                        case "GBP":
                            return "£";
                        case "USD":
                            return "$";
                        case "EUR":
                            return "€";
                        default:
                            return ""; // Default to an empty string if no match is found
                    }
                })();

                return `${currencySymbol} ${value.toLocaleString()}`;
            },
            sorter: (a, b) => a.salary - b.salary,
        },
        {
            title: "Fee",
            dataIndex: "fee",
            key: "fee",
            render: (value, { fee_currency }) => {
                const currencySymbol = (() => {
                    switch (fee_currency) {
                        case "GBP":
                            return "£";
                        case "USD":
                            return "$";
                        case "EUR":
                            return "€";
                        default:
                            return ""; // Default to an empty string if no match is found
                    }
                })();

                return `${currencySymbol} ${value.toLocaleString()}`;
            },
            sorter: (a, b) => a.fee - b.fee,
        },
        {
            title: "Margin Agreed",
            dataIndex: "margin_agreed",
            key: "marginAgreed",
            render: (value) => `${value} %`,
            sorter: (a, b) => a.margin_agreed - b.margin_agreed,
        },
        {
            title: "Start Date",
            dataIndex: "start_date",
            key: "startDate",
            render: (date) => date, // Format the date
            sorter: (a, b) => new Date(a.start_date) - new Date(b.start_date),
        },
        {
            title: "Status",
            dataIndex: "status",
            key: "status",
            render: (status) => (
                <Tag color={status === 1 ? "green" : "red"}>
                    {status === 1 ? "Active" : "Inactive"}
                </Tag>
            ),
            sorter: (a, b) => a.status - b.status,
        },
        {
            title: "Action",
            key: "action",
            render: (_, { id }) => (
                <Space size="small">
                    <NavLink to={`/jobs/${id}`}>
                        <EyeOutlined
                            style={{ color: "green", cursor: "pointer" }}
                        />
                    </NavLink>
                    <NavLink to={`/jobs/edit/${id}`}>
                        <EditOutlined
                            style={{ color: "#1890ff", cursor: "pointer" }}
                        />
                    </NavLink>
                    <Popconfirm
                        title="Are you sure you want to delete this item?"
                        okText="Yes"
                        cancelText="No"
                        onConfirm={() => handleDelete(id)}
                    >
                        <DeleteOutlined
                            style={{ color: "red", cursor: "pointer" }}
                        />
                    </Popconfirm>
                </Space>
            ),
        },
    ];

    return (
        <Card
            title="Jobs List"
            extra={
                <NavLink to="/jobs/create">
                    <Button type="primary">
                        <PlusOutlined />
                        New Job
                    </Button>
                </NavLink>
            }
        >
            <Table
                columns={columns}
                dataSource={data.jobs}
                loading={isFetching}
                rowKey="id"
                scroll={{ x: "max-content" }}
            />
        </Card>
    );
}
