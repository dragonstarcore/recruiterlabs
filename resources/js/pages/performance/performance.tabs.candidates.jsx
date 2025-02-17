import React from "react";
import { Table, Tag } from "antd";
import { NavLink } from "react-router-dom";

const handleGetCV = async (candidateId) => {
    const response = await fetch(`/api/get_cv?candidate_id=${candidateId}`, {
        headers: {
            Authorization: `Bearer ${localStorage.getItem("token")}`,
        },
    });

    if (response.ok) {
        const blob = await response.blob();
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = `cv_${candidateId}.pdf`;
        document.body.appendChild(a);
        a.click();
        a.remove();
    } else {
        console.error("Failed to fetch CV");
    }
};

const columns = [
    {
        title: "Id",
        dataIndex: "candidateId",
        key: "candidateId",
        render: (_, { candidateId, links }) => (
            <NavLink target="_blink" to={links?.self}>
                {candidateId}
            </NavLink>
        ),
    },
    {
        title: "Name",
        dataIndex: "name",
        key: "name",
        render: (_, { firstName, lastName }) => (
            <>
                {firstName} {lastName}
            </>
        ),
    },
    {
        title: "Email",
        dataIndex: "email",
        key: "email",
    },
    {
        title: "Phone",
        dataIndex: "phone",
        key: "phone",
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
    {
        title: "Action",
        key: "action",
        render: (_, { candidateId }) => (
            <a onClick={() => handleGetCV(candidateId)}>Get CV</a>
        ),
    },
];

export default function PerformanceCandidatesTable({ items }) {
    return <Table columns={columns} dataSource={items} />;
}
