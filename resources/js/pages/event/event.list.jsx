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
import { EyeOutlined, FileOutlined, DeleteOutlined } from "@ant-design/icons";
import { Calendar, momentLocalizer } from "react-big-calendar"; // or fullcalendar-react
import "react-big-calendar/lib/css/react-big-calendar.css"; // for react-big-calendar
import moment from "moment";
import { useDispatch, useSelector } from "react-redux";
import { useNavigate, useParams } from "react-router-dom";
import { useFetchEventsQuery, useManageEventsMutation } from "./event.service";
import { setEvent } from "./event.slice";
import "./style.css";
const localizer = momentLocalizer(moment);
const { Title } = Typography;

const EventList = ({}) => {
    const { user_id } = useParams();
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const [form] = Form.useForm();
    console.log("sdf");

    const {
        data: calendarData,
        isLoading,
        isSuccess: isCalendarSucess,
    } = useFetchEventsQuery(user_id, {
        refetchOnMountOrArgChange: true,
    });
    useEffect(() => {
        if (isCalendarSucess) {
            dispatch(setEvent(calendarData));
        }
    }, [isCalendarSucess, calendarData]);

    const eventsData = useSelector((apps) => apps.event.events);
    const [manageEvents] = useManageEventsMutation();

    // const [selectedEvent, setSelectedEvent] = useState(null);
    // const [isCreateModalOpen, setCreateModalOpen] = useState(false);
    // const [isEditModalOpen, setEditModalOpen] = useState(false);
    // const [eventData, setEventData] = useState({
    //     id: "",
    //     title: "",
    //     description: "",
    //     location: "",
    //     start: "2020-02-02",
    //     end: null,
    // });
    // const handleSelectSlot = ({ start, end }) => {
    //     setEventData({
    //         ...eventData,
    //         start: moment(start).format("YYYY-MM-DD"),
    //     });
    //     setCreateModalOpen(true);
    // };
    // const handleSelectEvent = (event) => {
    //     setSelectedEvent(event);
    //     setEventData({
    //         id: event.id,
    //         title: event.title,
    //         location: event.location || "",
    //         start: dayjs(event.start, "YYYY-MM-DD"),
    //         end: dayjs(event.end, "YYYY-MM-DD"),
    //     });
    //     setEditModalOpen(true);
    // };
    // const handleUpdateEvent = async () => {
    //     const event = await manageEvents({
    //         ...eventData,
    //         start: dayjs(eventData.start, "YYYY-MM-DD"),
    //         end: dayjs(eventData.end, "YYYY-MM-DD"),
    //         type: "update",
    //     }).unwrap();
    //     dispatch(
    //         setEvent(
    //             eventsData.map((e) =>
    //                 e.id != eventData.id
    //                     ? e
    //                     : {
    //                           ...eventData,
    //                           start: eventData.start.format("YYYY-MM-DD"),
    //                           end: eventData.end.format("YYYY-MM-DD"),
    //                       }
    //             )
    //         )
    //     );
    //     handleCancel();
    // };
    // const handleSaveNewEvent = async (values) => {
    //     // Add event saving logic here
    //     try {
    //         const event = await manageEvents({
    //             ...values,
    //             start: values.start.format("YYYY-MM-DD"),
    //             end: values.end.format("YYYY-MM-DD"),
    //             type: "add",
    //         }).unwrap();
    //         dispatch(setEvent([...eventsData, event]));
    //     } catch (err) {
    //         console.log(err);
    //     } finally {
    //         handleCancel();
    //     }
    // };

    // // Handle canceling the event creation
    // const handleCancel = () => {
    //     form.resetFields();
    //     setEventData({
    //         id: null,
    //         title: "",
    //         location: "",
    //         start: null,
    //         end: null,
    //     });
    //     setCreateModalOpen(false);
    //     setEditModalOpen(false);
    // };
    const handleDelete = async (id) => {
        const event = await manageEvents({
            id: id,
            type: "delete",
        }).unwrap();
        dispatch(setEvent(eventsData.filter((e) => e.id != id)));
        handleCancel();
    };
    const totalCount = eventsData?.length || 0;
    const [currentPage, setCurrentPage] = useState(1); // current page number
    const [pageSize, setPageSize] = useState(5); // number of items per page
    const onPageChange = (page, pageSize) => {
        setCurrentPage(page); // Update current page
        setPageSize(pageSize); // Update page size if the user changes it
    };
    // Transform employee data for the table
    const columns = [
        {
            title: "#",
            dataIndex: "index",
            render: (_, record, index) => index + 1,
        },
        {
            title: "Title",
            dataIndex: "title",
            style: { textAlign: "center" },
        },
        {
            title: "Location",
            dataIndex: "location",
            style: { textAlign: "center" },
        },
        {
            title: "Start Date",
            dataIndex: "start",
        },
        {
            title: "End Date",
            dataIndex: "end",
        },
        {
            title: "Action",
            dataIndex: "action",
            render: (text, record) => (
                <Flex justify="start">
                    <Button
                        type="default"
                        variant="solid"
                        color="danger"
                        style={{ width: "30%" }}
                        icon={<DeleteOutlined />}
                        size="small"
                        onClick={() => handleDelete(record.id)}
                    />
                </Flex>
            ),
        },
    ];
    if (isLoading) return <>Loading</>;
    return (
        <div>
            <Card
                style={{ marginTop: "20px", height: "600px" }}
                title=" Events List"
                extra={
                    <Button
                        type="primary"
                        onClick={() => navigate("/employee_list/" + user_id)}
                    >
                        Back
                    </Button>
                }
            >
                {
                    <Table
                        columns={columns}
                        dataSource={eventsData}
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
                }
            </Card>
        </div>
    );
};

export default EventList;
