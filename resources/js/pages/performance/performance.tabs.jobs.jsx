import React from "react";
import { Table, Tag } from "antd";
import { NavLink } from "react-router-dom";

const columns = [
    {
        title: "Id",
        dataIndex: "jobId",
        key: "jobId",
        render: (_, { jobId, links }) => (
            <NavLink target="_blink" to={links?.self}>
                {jobId}
            </NavLink>
        ),
    },
    {
        title: "Title",
        dataIndex: "jobTitle",
        key: "jobTitle",
    },
    {
        title: "Company",
        dataIndex: "company",
        key: "company",
        render: (item) => (
            <NavLink target="_blink" to={item?.links?.self}>
                {item?.name}
            </NavLink>
        ),
    },
    {
        title: "Contact",
        dataIndex: "contact",
        key: "contact",
        render: (item) => (
            <NavLink target="_blink" to={item?.links?.self}>
                {item?.firstName} {item?.lastName}
            </NavLink>
        ),
    },
    {
        title: "Owner",
        dataIndex: "owner",
        key: "owner",
        render: (item) => (
            <>
                {item?.firstName} {item?.lastName}
            </>
        ),
    },
    {
        title: "Status",
        key: "status",
        dataIndex: "status",
        render: (item) => (
            <>
                <Tag color={item?.active ? "green" : "red"}>{item?.name}</Tag>
            </>
        ),
    },
    {
        title: "CreatedAt",
        key: "createdAt",
        dataIndex: "createdAt",
        render: (item) => <>{new Date(item).toLocaleDateString()} </>,
    },
];

export default function PerformanceJobsTable({ items }) {
    return <Table columns={columns} dataSource={items} />;
}
