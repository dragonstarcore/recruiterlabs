import React from "react";
import { Table, Tag } from "antd";
import { NavLink } from "react-router-dom";

const columns = [
    {
        title: "Id",
        dataIndex: "interviewId",
        key: "interviewId",
        render: (_, { interviewId, links }) => (
            <NavLink target="_blank" to={links?.self}>
                {interviewId}
            </NavLink>
        ),
    },
    {
        title: "Location",
        dataIndex: "location",
        key: "location",
    },
    {
        title: "Interviewee",
        dataIndex: "interviewee",
        key: "interviewee",
        render: (item) => <>{item?.jobTitle}</>,
    },
    {
        title: "Type",
        key: "type",
        dataIndex: "type",
        render: (_, { type }) => (
            <>
                {Array.isArray(type) &&
                    type.map((tag) => (
                        <Tag color="blue" key={tag}>
                            {tag.toUpperCase()}
                        </Tag>
                    ))}
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

export default function PerformanceInterviewsTable({ items }) {
    return <Table columns={columns} dataSource={items} />;
}
