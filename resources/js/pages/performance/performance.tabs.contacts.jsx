import React from "react";
import { Table, Tag } from "antd";
import { NavLink } from "react-router-dom";

const columns = [
    {
        title: "Id",
        dataIndex: "contactId",
        key: "contactId",
        render: (_, { contactId, links }) => (
            <NavLink target="_blink" to={links?.self}>
                {contactId}
            </NavLink>
        ),
    },
    {
        title: "Position",
        dataIndex: "position",
        key: "position",
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

export default function PerformanceContactsTable({ items }) {
    return <Table columns={columns} dataSource={items} />;
}
