import React, { useState, useEffect } from "react";
import {
    Card,
    Row,
    Col,
    Modal,
    Input,
    Table,
    DatePicker,
    Button,
    Switch,
    Avatar,
    Typography,
    Space,
    Spin,
    message,
    Form,
    Flex,
} from "antd";
import { EyeOutlined, FileOutlined } from "@ant-design/icons";
import { Calendar, momentLocalizer } from "react-big-calendar"; // or fullcalendar-react
import "react-big-calendar/lib/css/react-big-calendar.css"; // for react-big-calendar
import moment from "moment";
import dayjs from "dayjs";
import { format, parse, startOfWeek, getDay } from "date-fns";
import { useDispatch, useSelector } from "react-redux";
import { useNavigate } from "react-router-dom";
import {
    useFetchCalendarQuery,
    useManageEventsMutation,
} from "./event.service";
import { setEvent } from "./event.slice";
import "./style.css";
const localizer = momentLocalizer(moment);
const { Title } = Typography;

const MyEventPage = ({}) => {
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const [form] = Form.useForm();
    const { data: calendarData, isSuccess: isCalendarSucess } =
        useFetchCalendarQuery(undefined, {
            refetchOnMountOrArgChange: true,
        });
    useEffect(() => {
        if (isCalendarSucess) {
            dispatch(setEvent(calendarData));
        }
    }, [isCalendarSucess, calendarData]);

    const eventsData = useSelector((apps) => apps.event.events);
    const [manageEvents, { isLoading, isSuccess, error }] =
        useManageEventsMutation();

    const [selectedEvent, setSelectedEvent] = useState(null);
    const [isCreateModalOpen, setCreateModalOpen] = useState(false);
    const [isEditModalOpen, setEditModalOpen] = useState(false);
    const [eventData, setEventData] = useState({
        id: "",
        title: "",
        description: "",
        location: "",
        start: "2020-02-02",
        end: null,
    });
    const handleSelectSlot = ({ start, end }) => {
        setEventData({
            ...eventData,
            start: moment(start).format("YYYY-MM-DD"),
        });
        setCreateModalOpen(true);
    };
    const handleSelectEvent = (event) => {
        setSelectedEvent(event);
        setEventData({
            id: event.id,
            title: event.title,
            location: event.location || "",
            start: dayjs(event.start, "YYYY-MM-DD"),
            end: dayjs(event.end, "YYYY-MM-DD"),
        });
        setEditModalOpen(true);
    };
    const handleUpdateEvent = async () => {
        const event = await manageEvents({
            ...eventData,
            start: dayjs(eventData.start, "YYYY-MM-DD"),
            end: dayjs(eventData.end, "YYYY-MM-DD"),
            type: "update",
        }).unwrap();
        dispatch(
            setEvent(
                eventsData.map((e) =>
                    e.id != eventData.id
                        ? e
                        : {
                              ...eventData,
                              start: eventData.start.format("YYYY-MM-DD"),
                              end: eventData.end.format("YYYY-MM-DD"),
                          }
                )
            )
        );
        handleCancel();
    };
    const handleSaveNewEvent = async (values) => {
        // Add event saving logic here
        try {
            const event = await manageEvents({
                ...values,
                start: values.start.format("YYYY-MM-DD"),
                end: values.end.format("YYYY-MM-DD"),
                type: "add",
            }).unwrap();
            dispatch(setEvent([...eventsData, event]));
        } catch (err) {
            console.log(err);
        } finally {
            handleCancel();
        }
    };

    // Handle canceling the event creation
    const handleCancel = () => {
        form.resetFields();
        setEventData({
            id: null,
            title: "",
            location: "",
            start: null,
            end: null,
        });
        setCreateModalOpen(false);
        setEditModalOpen(false);
    };
    const handleDelete = async () => {
        const event = await manageEvents({
            id: eventData.id,
            type: "delete",
        }).unwrap();
        dispatch(setEvent(eventsData.filter((e) => e.id != eventData.id)));
        handleCancel();
    };
    // Transform employee data for the table

    if (isLoading) return <>Loading</>;
    return (
        <div>
            <Card
                style={{ marginTop: "20px", height: "600px" }}
                title="Upcoming Events"
                extra={
                    <Button type="primary" onClick={() => navigate("/staff")}>
                        Back
                    </Button>
                }
            >
                <Calendar
                    localizer={localizer}
                    events={eventsData}
                    startAccessor="start"
                    selectable={true}
                    endAccessor="end"
                    onSelectSlot={handleSelectSlot}
                    onSelectEvent={handleSelectEvent}
                    views={["month"]}
                    defaultView="month" // Set the default view to "week"
                    style={{ height: "400px" }}
                    toolbar={true}
                />
            </Card>

            <Modal
                title="Create Event"
                visible={isCreateModalOpen}
                onCancel={handleCancel}
                footer={[]}
            >
                <Form
                    form={form}
                    initialValues={{
                        start: dayjs(eventData.start, "YYYY-MM-DD"),
                    }}
                    layout="horizontal"
                    onFinish={handleSaveNewEvent}
                    labelCol={{ span: 6 }}
                    wrapperCol={{ span: 18 }}
                >
                    <Row className="inputLayout">
                        <Col span={24}>
                            <Form.Item
                                name="title"
                                label="Title"
                                rules={[
                                    {
                                        required: true,
                                        message:
                                            "Please select the start date!",
                                    },
                                ]}
                            >
                                <Input
                                    placeholder="Title"
                                    style={{
                                        marginBottom: "10px",
                                        width: "100%",
                                    }}
                                />
                            </Form.Item>
                        </Col>
                    </Row>

                    <Row className="inputLayout">
                        <Col span={24}>
                            {" "}
                            <Form.Item
                                name="location"
                                label="Location"
                                rules={[
                                    {
                                        required: true,
                                        message: "Please input location",
                                    },
                                ]}
                            >
                                <Input
                                    placeholder="Location"
                                    style={{
                                        marginBottom: "10px",
                                        width: "100%",
                                    }}
                                />
                            </Form.Item>
                        </Col>
                    </Row>

                    <Row gutter={[16, 16]} className="inputLayout">
                        <Col span={24}>
                            {" "}
                            <Form.Item
                                name="start"
                                label="Start Date"
                                rules={[
                                    {
                                        required: true,
                                        message:
                                            "Please select the start date!",
                                    },
                                ]}
                            >
                                <DatePicker style={{ width: "100%" }} />
                            </Form.Item>{" "}
                        </Col>
                    </Row>

                    <Row gutter={[16, 16]} className="inputLayout">
                        <Col span={24}>
                            {" "}
                            <Form.Item
                                name="end"
                                label="End date"
                                rules={[
                                    {
                                        required: true,
                                        message:
                                            "Please select the start date!",
                                    },
                                ]}
                            >
                                <DatePicker style={{ width: "100%" }} />
                            </Form.Item>
                        </Col>
                    </Row>
                    <Flex justify="space-between">
                        <Button key="cancel" onClick={handleCancel}>
                            Cancel
                        </Button>
                        <Button htmlType="submit" key="save" type="primary">
                            Save
                        </Button>
                    </Flex>
                </Form>
            </Modal>
            <Modal
                title="Edit Event"
                visible={isEditModalOpen}
                onCancel={handleCancel}
                footer={[
                    <Button
                        type="default"
                        color="orange"
                        variant="solid"
                        onClick={handleCancel}
                    >
                        Cancel
                    </Button>,
                    <Button
                        type="default"
                        color="danger"
                        variant="solid"
                        onClick={handleDelete}
                    >
                        Delete
                    </Button>,

                    <Button
                        key="save"
                        type="primary"
                        onClick={handleUpdateEvent}
                    >
                        Save
                    </Button>,
                ]}
            >
                <Row gutter={[16, 16]} className="inputLayout">
                    <Col span={8}>Title:</Col>
                    <Col span={16}>
                        <Input
                            placeholder="Title"
                            value={eventData.title}
                            onChange={(e) =>
                                setEventData({
                                    ...eventData,
                                    title: e.target.value,
                                })
                            }
                            required
                            style={{ marginBottom: "10px" }}
                        />
                    </Col>{" "}
                </Row>

                <Row gutter={[16, 16]} className="inputLayout">
                    <Col span={8}>Location:</Col>
                    <Col span={16}>
                        <Input
                            placeholder="Location"
                            value={eventData.location}
                            onChange={(e) =>
                                setEventData({
                                    ...eventData,
                                    location: e.target.value,
                                })
                            }
                            style={{ marginBottom: "10px" }}
                            required
                        />
                    </Col>{" "}
                </Row>

                <Row gutter={[16, 16]} className="inputLayout">
                    <Col span={8}>
                        <label>Start date:</label>
                    </Col>
                    <Col span={16}>
                        <DatePicker
                            style={{ width: "100%", marginBottom: "10px" }}
                            value={eventData.start}
                            onChange={(date) =>
                                setEventData({
                                    ...eventData,
                                    start: date,
                                })
                            }
                            format="YYYY-MM-DD"
                            required
                        />
                    </Col>
                </Row>

                <Row gutter={[16, 16]} className="inputLayout">
                    <Col span={8}>
                        <label>End date:</label>
                    </Col>
                    <Col span={16}>
                        <DatePicker
                            style={{ width: "100%" }}
                            value={eventData.end}
                            onChange={(date) =>
                                setEventData({
                                    ...eventData,
                                    end: date.format("YYYY-MM-DD"),
                                })
                            }
                        />
                    </Col>
                </Row>
            </Modal>
        </div>
    );
};

export default MyEventPage;
