import React, { useEffect, useState } from "react";
import { Button, message, Form, Input, Spin, Flex, Select, List } from "antd";
import { SendOutlined } from "@ant-design/icons";
import { setTicket } from "./tickets.slice";

import { useParams, useNavigate } from "react-router-dom";
import { useDispatch, useSelector } from "react-redux";
const { TextArea } = Input;
const { Option } = Select;

import { useGetTicketQuery, useEditTicketMutation } from "./tickets.service";
import { toast } from "react-toastify";
export default function Tickets({}) {
    const navigate = useNavigate();
    const dispatch = useDispatch();
    const { id } = useParams();
    const [ticketData, setTicketData] = useState(null);
    const [form] = Form.useForm();

    const { data, isLoading, isSuccess } = useGetTicketQuery(id, {
        refetchOnMountOrArgChange: true,
    });

    const [editTicket] = useEditTicketMutation();

    const tickets = useSelector((apps) => apps.ticket.tickets);
    const name = useSelector((apps) => apps.app.user.name);

    const handleFormSubmit = async () => {
        const ticketValue = form.getFieldsValue();

        try {
            const { data } = await editTicket({ ...ticketValue, id });
            dispatch(
                setTicket(
                    tickets.map((e) => (e.id != id ? e : { ...e, ticketValue }))
                )
            );
            form.resetFields();

            toast.success("Ticket updated successfully", {
                position: "top-right",
            });
            navigate("/tickets");
        } catch (err) {
            console.log(err);
        }
    };

    useEffect(() => {
        if (isSuccess) {
            form.setFieldsValue({
                ...data.ticket,
                message: "",
            });
        }
    }, [isSuccess, form, data]);

    if (isLoading)
        return (
            <Flex justify="center" align="center">
                <Spin />
            </Flex>
        );
    return (
        <div>
            <Form form={form} layout="vertical" onFinish={handleFormSubmit}>
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
                    <Select placeholder="Choose Team" disabled>
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
                    <Input placeholder="Title" disabled />
                </Form.Item>

                {/* Description */}
                <Form.Item
                    label="Description"
                    name="description"
                    disabled
                    rules={[
                        {
                            required: true,
                            message: "Please enter a description",
                        },
                    ]}
                >
                    <TextArea
                        disabled
                        rows={3}
                        placeholder="Enter description here"
                    />
                </Form.Item>

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
                    <TextArea rows={3} placeholder="Enter your message here" />
                </Form.Item>

                <Form.Item label="History">
                    <List
                        size="small"
                        bordered
                        dataSource={data.history}
                        renderItem={(item) => (
                            <List.Item>
                                <h3>{item.user}</h3>
                                {item.message}
                            </List.Item>
                        )}
                    />
                </Form.Item>

                {/* Priority */}

                {/* Action buttons */}
                <Flex justify="flex-end">
                    <Button
                        type="default"
                        style={{ marginRight: 8 }}
                        onClick={() => window.history.back()}
                    >
                        Discard
                    </Button>
                    <Button type="primary" htmlType="submit">
                        Save {<SendOutlined />}
                    </Button>
                </Flex>
            </Form>
        </div>
    );
}
