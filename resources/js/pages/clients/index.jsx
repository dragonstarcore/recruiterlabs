import React, { useState, useEffect } from "react";
import {
    Card,
    Row,
    Col,
    Table,
    Input,
    Button,
    Switch,
    Avatar,
    Typography,
    Space,
    message,
    Flex,
    Alert,
    Popconfirm,
} from "antd";
import { useNavigate } from "react-router-dom";
import {
    EyeOutlined,
    FileOutlined,
    SearchOutlined,
    FileAddOutlined,
    EditOutlined,
    DeleteOutlined,
    MailOutlined,
} from "@ant-design/icons";
import { useSelector, useDispatch } from "react-redux";
import { useFetchClientsQuery, useDeleteMutation } from "./clients.service";

import { setClient } from "./clients.slice";
const MyClientPage = ({}) => {
    const dispatch = useDispatch();
    const navigate = useNavigate();

    const [deleteClient, { isLoading: isDeleteLoading }] = useDeleteMutation();
    const OnhandleDelete = async (id) => {
        try {
            await deleteClient(id);
            dispatch(setClient(clients.filter((f) => f.id != id)));
        } catch (err) {
            console.log(err);
        }
    };
    const columns = [
        {
            title: "#",
            dataIndex: "index",
            key: "index",
            render: (text, record, index) => index + 1,
        },
        {
            title: "Logo",
            dataIndex: "logo",
            key: "logo",
            render: (logo) => (
                <img
                    src={logo || "path/to/default_user.jpg"}
                    alt="Logo"
                    className="rounded-pill"
                    width="36"
                    height="36"
                />
            ),
        },
        {
            title: "Name",
            dataIndex: "name",
            key: "name",
            render: (text) => <span className="fw-semibold">{text}</span>,
        },
        {
            title: "Email",
            dataIndex: "email",
            key: "email",
        },
        {
            title: "Status",
            dataIndex: "status",
            key: "status",
            render: (status) => (
                <span
                    className={`badge ${
                        status === 1 ? "bg-success" : "bg-danger"
                    }`}
                >
                    {status === 1 ? "Active" : "Inactive"}
                </span>
            ),
        },
        {
            title: "Action",
            key: "action",
            render: (text, record) => (
                <Flex justify="center" align="center">
                    <Row gutter={[16, 16]}>
                        <Col>
                            <Button
                                icon={<EditOutlined />}
                                size="large"
                                type="primary"
                                color="danger"
                                onClick={() =>
                                    navigate(`/clients/edit/${record.id}`)
                                }
                            />
                        </Col>
                        <Col>
                            <Popconfirm
                                title="Are you sure to delete this user?"
                                onConfirm={() => OnhandleDelete(record.id)}
                                okText="Yes"
                                cancelText="No"
                            >
                                <Button
                                    icon={<DeleteOutlined />}
                                    size="middle"
                                    type="default"
                                    color="danger"
                                    variant="solid"
                                />
                            </Popconfirm>{" "}
                        </Col>
                        <Col>
                            <Button
                                icon={<MailOutlined />}
                                size="middle"
                                type="default"
                                color="green"
                                variant="solid"
                                onClick={() => handleResendEmail(record.id)}
                            >
                                Resend Email
                            </Button>
                        </Col>
                    </Row>
                </Flex>
            ),
        },
    ];

    // Transform employee data for the table
    const [currentPage, setCurrentPage] = useState(1); // current page number
    const [pageSize, setPageSize] = useState(5); // number of items per page

    const { data, isLoading, isSuccess } = useFetchClientsQuery(undefined, {
        refetchOnMountOrArgChange: true,
    });
    useEffect(() => {
        if (isSuccess) {
            console.log(data?.users);
            dispatch(setClient(data?.users));
        }
    }, [isSuccess, data]);

    const clients = useSelector((apps) => apps.client.clients);
    console.log(clients);
    const totalCount = clients?.length || 0;
    const onPageChange = (page, pageSize) => {
        setCurrentPage(page); // Update current page
        setPageSize(pageSize); // Update page size if the user changes it
    };

    if (isLoading) return <>Loading</>;
    return (
        <div className="content">
            <Card
                title={"Clients List"}
                extra={
                    <Button
                        type="primary"
                        onClick={() => navigate("/clients/create")}
                    >
                        Create
                    </Button>
                }
            >
                <Flex justify="flex-end" style={{ marginBottom: "10px" }}>
                    <Col span={6}>
                        <Input
                            placeholder="Search..."
                            addonBefore={<SearchOutlined />}
                        />
                    </Col>
                </Flex>
                <Table
                    columns={columns}
                    dataSource={clients}
                    pagination={{
                        current: currentPage, // Current page number
                        pageSize: pageSize, // Number of items per page
                        total: totalCount, // Total number of items
                        onChange: onPageChange,
                        showSizeChanger: true, // Show size changer dropdown (optional)
                        pageSizeOptions: ["5", "10", "20", "50"], // Page size options
                    }}
                    rowKey="key"
                />
            </Card>
        </div>
    );
};

export default MyClientPage;
