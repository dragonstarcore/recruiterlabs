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
} from "antd";
import {
    SearchOutlined,
    EditOutlined,
    SendOutlined,
    DeleteOutlined,
} from "@ant-design/icons";

import { useDispatch, useSelector } from "react-redux";
const { TextArea } = Input;
const { Option } = Select;

import {
    useFetchTicketQuery,
    useDeleteTicketMutation,
    useSearchTicketMutation,
    useAddTicketMutation,
} from "./tickets.service";
import { setTicket, removeTicket } from "./tickets.slice";
import TicketEdit from "./ticket.edit";
export default function Tickets() {
    const navigate = useNavigate();
    const dispatch = useDispatch();
    const [form] = Form.useForm();
    const [isEditDrawer, setIsEditDrawer] = useState(false);
    const { data, isLoading, isSuccess } = useFetchTicketQuery(undefined, {
        refetchOnMountOrArgChange: true,
    });

    const tickets = useSelector((apps) => apps.ticket.tickets);
    const name = useSelector((apps) => apps.app.user.name);
    const [
        deleteTicket,
        { isLoading: isDeleteLoading, isSuccess: isDeleteSuccess },
    ] = useDeleteTicketMutation();
    const [
        searchTicket,
        { isLoading: isSearchLoading, isSuccess: isSearchSuccess },
    ] = useSearchTicketMutation();

    const [isDrawer, setIsDrawer] = useState(false);
    const [addTicket] = useAddTicketMutation();
    const [ticketId, setTicketId] = useState(null);
    const { search_title, setSearch_title } = useState();

    console.log("###");
    console.log("BisSuccess", isSuccess);
    console.log("BisLoading", isLoading);
    console.log("Bdata", data);
    useEffect(() => {
        if (isSuccess) {
            console.log("###");
            console.log("isSuccess", isSuccess);
            console.log("isLoading", isLoading);
            console.log("data", data);
            dispatch(setTicket(data?.tickets));
        }
    }, [isSuccess, data, dispatch]);
    const handleFormSubmit = async () => {
        const ticketValue = form.getFieldsValue();
        console.log(ticketValue);
        setIsDrawer(false);
        try {
            const { data } = await addTicket(ticketValue);
            console.log(data?.ticket);
            dispatch(setTicket([...tickets, data?.ticket]));
            message.success("Ticket added successfully!");
            form.resetFields();
        } catch (err) {
            console.log(err);
        }
    };
    const handleEdit = (ticketId) => {
        console.log(ticketId);
        //setTicketId(ticketId);
        //setIsEditDrawer(true);
        navigate("/tickets/" + ticketId);
        // Handle the edit functionality here, for example navigate to the edit page
    };
    const handleDelete = async (ticketId) => {
        // Perform delete logic here, e.g., call an API to delete the ticket
        try {
            await deleteTicket(ticketId);
            dispatch(
                setTicket(tickets.filter((ticket) => ticket.id != ticketId))
            );
            //dispatch(removeTicket());
            message.success("Ticket deleted successfully!");
        } catch (err) {
            console.log(err);
        }
    };
    const OnOpenDrawer = () => {
        setIsDrawer(true);
    };

    const columns = [
        {
            title: "#",
            dataIndex: "key",
            render: (text, record, index) => index + 1,
            width: "5%",
        },
        {
            title: "Title",
            dataIndex: "title",
            render: (text) => (
                <span>{text.charAt(0).toUpperCase() + text.slice(1)}</span>
            ),
        },
        {
            title: "Created By",
            render: (record) => <span>{name}</span>,
        },
        {
            title: "Team",
            dataIndex: "team",
            render: (text) => (
                <span>{text.charAt(0).toUpperCase() + text.slice(1)}</span>
            ),
        },
        {
            title: "Created At",
            dataIndex: "created_at",
            render: (text) => <span>{new Date(text).toLocaleString()}</span>, // format the date as needed
        },
        {
            title: "Action",
            key: "action",
            render: (text, record) => (
                <Space size="middle">
                    <Button
                        type="primary"
                        icon={<EditOutlined />}
                        onClick={() => handleEdit(record.id)}
                        size="small"
                    >
                        Edit
                    </Button>

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
                        >
                            Delete
                        </Button>
                    </Popconfirm>
                </Space>
            ),
            width: "15%",
        },
    ];

    const OnSearchTitle = async (e) => {
        console.log(e.target.value);
        try {
            const { data } = await searchTicket({
                search_title: e.target.value,
            });
            console.log(data);
            dispatch(setTicket(data?.tickets));
        } catch (err) {
            console.log(err);
        }
    };
    const totalCount = tickets?.length || 0;
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
                title="Tickets List"
                extra={
                    <Button type="primary" onClick={OnOpenDrawer}>
                        Create
                    </Button>
                }
            >
                <Flex justify="flex-end" style={{ marginBottom: "10px" }}>
                    <Col span={6}>
                        <Input
                            value={search_title}
                            onChange={(e) => OnSearchTitle(e)}
                            placeholder="Search..."
                            addonBefore={<SearchOutlined />}
                        />
                    </Col>
                </Flex>
                <Table
                    columns={columns}
                    dataSource={tickets}
                    rowKey="key"
                    pagination={{
                        current: currentPage, // Current page number
                        pageSize: pageSize, // Number of items per page
                        total: totalCount, // Total number of items
                        onChange: onPageChange,
                        showSizeChanger: true, // Show size changer dropdown (optional)
                        pageSizeOptions: ["5", "10", "20", "50"], // Page size options
                    }}
                />

                <Drawer
                    title="Create Ticket"
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
                            label="Team"
                            name="team"
                            rules={[
                                {
                                    required: true,
                                    message: "Please select a team",
                                },
                            ]}
                        >
                            <Select placeholder="Choose Team">
                                <Option value="Operations">Operations</Option>
                                <Option value="Marketing">Marketing</Option>
                                <Option value="Finance">Finance</Option>
                            </Select>
                        </Form.Item>

                        {/* Title */}
                        <Form.Item
                            label="Title"
                            name="title"
                            rules={[
                                {
                                    required: true,
                                    message: "Please enter a title",
                                },
                                { max: 255, message: "Title is too long!" },
                            ]}
                        >
                            <Input placeholder="Title" />
                        </Form.Item>

                        {/* Description */}
                        <Form.Item
                            label="Description"
                            name="description"
                            rules={[
                                {
                                    required: true,
                                    message: "Please enter a description",
                                },
                            ]}
                        >
                            <TextArea
                                rows={3}
                                placeholder="Enter description here"
                            />
                        </Form.Item>

                        {/* Message */}
                        <Form.Item
                            label="Message"
                            name="message"
                            rules={[
                                {
                                    required: true,
                                    message: "Please enter a message",
                                },
                            ]}
                        >
                            <TextArea
                                rows={3}
                                placeholder="Enter your message here"
                            />
                        </Form.Item>

                        {/* Priority */}
                        <Form.Item
                            label="Priority"
                            name="priority"
                            rules={[
                                {
                                    required: true,
                                    message: "Please select a priority",
                                },
                            ]}
                        >
                            <Select placeholder="Select Priority">
                                <Option value="Low">Low</Option>
                                <Option value="Medium">Medium</Option>
                                <Option value="High">High</Option>
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
                    title="Edit Ticket"
                    onClose={() => setIsEditDrawer(false)}
                >
                    <TicketEdit
                        ticketId={ticketId}
                        OnCloseEdit={() => setIsEditDrawer(false)}
                    />
                </Drawer> */}
            </Card>
        </div>
    );
}
