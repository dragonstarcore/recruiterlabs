import React, { useState, useEffect } from "react";
import { Card, Table, Button, Spin, message, Flex } from "antd";
import { DeleteOutlined } from "@ant-design/icons";
import "react-big-calendar/lib/css/react-big-calendar.css"; // for react-big-calendar
import { useDispatch, useSelector } from "react-redux";
import { useNavigate, useParams } from "react-router-dom";
import { useFetchEventsQuery, useManageEventsMutation } from "./event.service";
import { setEvent } from "./event.slice";
import "./style.css";

const EventList = ({}) => {
    const { user_id } = useParams();
    const dispatch = useDispatch();
    const navigate = useNavigate();

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

    const handleDelete = async (id) => {
        const event = await manageEvents({
            id: id,
            type: "delete",
        }).unwrap();
        dispatch(setEvent(eventsData.filter((e) => e.id != id)));
        message.success("event deleted sucessfully");
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
    if (isLoading) return <Spin />;
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
                        rowKey="id"
                    />
                }
            </Card>
        </div>
    );
};

export default EventList;
