import React, { useState, useEffect } from "react";
import {
    Card,
    Row,
    Col,
    Table,
    Input,
    Button,
    Avatar,
    Typography,
    message,
    Flex,
    Form,
    Upload,
} from "antd";
import { useNavigate, useParams } from "react-router-dom";
import {
    EyeOutlined,
    SearchOutlined,
    FileAddOutlined,
    UploadOutlined,
    DeleteOutlined,
} from "@ant-design/icons";
import { Calendar, momentLocalizer } from "react-big-calendar"; // or fullcalendar-react
import "react-big-calendar/lib/css/react-big-calendar.css"; // for react-big-calendar
import moment from "moment";

import { useSelector, useDispatch } from "react-redux";
import {
    useFetchEmployeeListQuery,
    useFetchEventsQuery,
    useAddDocumentMutation,
    useSearchDocumentMutation,
    useDeleteStaffMutation,
    useSearchEmployeeMutation,
} from "./staff.service";
import { setEmployee } from "./staff.slice";
const localizer = momentLocalizer(moment);
import ShowIcon from "./showIcon";

const Employee_listPage = ({}) => {
    const dispatch = useDispatch();
    const { id: user_id } = useParams();
    const navigate = useNavigate();
    const [form] = Form.useForm();
    const employee_list = useSelector((apps) => apps.employee.employee_list);

    const {
        data: employeeData,
        isLoading,
        isSuccess,
    } = useFetchEmployeeListQuery(user_id, {
        refetchOnMountOrArgChange: true,
    });
    const { data: calendarData } = useFetchEventsQuery(user_id, {
        refetchOnMountOrArgChange: true,
    });
    const [addDocument] = useAddDocumentMutation();
    const [deleteStaff] = useDeleteStaffMutation();

    const [searchEmployee] = useSearchEmployeeMutation();
    const [searchDocument] = useSearchDocumentMutation();
    const [fileList, setFileList] = useState([]);
    const [str_search, setStr_search] = useState("");
    const [employee_search, setEmployee_search] = useState("");
    const OnSearchStr = async (e) => {
        setStr_search(e.target.value);
        const { data } = await searchDocument({
            str_search: e.target.value,
            id: user_id,
        });
        setFileList(data);
    };
    const OnSearchEmployee = async (e) => {
        setEmployee_search(e.target.value);
        const { data } = await searchEmployee({
            title: e.target.value,
            id: user_id,
        });
        dispatch(setEmployee(data?.employee_list));
    };

    const OnDeleteStaff = async (id) => {
        try {
            const result = await deleteStaff({ user_id, id });
            dispatch(setEmployee(employee_list.filter((e) => e.id != id)));
            message.success(`Employee deleted successfully`);
        } catch (err) {
            message.error(`Employee delete failed`);
        }
    };

    const columns = [
        {
            title: "#",
            dataIndex: "index",
            render: (_, record, index) => index + 1,
        },
        {
            title: "Profile Picture",
            dataIndex: "emp_picture",
            style: { textAlign: "center" },
            render: (emp_picture) => (
                <Avatar
                    src={
                        emp_picture
                            ? `/${emp_picture}`
                            : "/assets/images/default_user.jpg"
                    }
                    size={36}
                    shape="circle"
                />
            ),
        },
        {
            title: "Name",
            dataIndex: "name",
            style: { textAlign: "center" },
            render: (name) => <Typography.Text>{name}</Typography.Text>,
        },
        {
            title: "Email",
            dataIndex: "email",
            style: { textAlign: "center" },
        },
        {
            title: "Job Title",
            dataIndex: "job_title",
            style: { textAlign: "center" },
            render: (title) => <Typography.Text>{title}</Typography.Text>,
        },
        {
            title: "Action",
            dataIndex: "action",
            render: (text, record) => (
                <Flex justify="start">
                    <Button
                        type="primary"
                        style={{ width: "30%", marginRight: "10px" }}
                        icon={<EyeOutlined />}
                        size="small"
                        onClick={() =>
                            navigate(`/employee/${record.id}/edit/${user_id}`)
                        }
                    />
                    <Button
                        type="default"
                        variant="solid"
                        color="danger"
                        style={{ width: "30%" }}
                        icon={<DeleteOutlined />}
                        size="small"
                        onClick={() => OnDeleteStaff(record.id)}
                    />
                </Flex>
            ),
        },
    ];
    const handleDocumentRequest = async ({
        file,
        fileList,
        onSuccess,
        onError,
    }) => {
        try {
            onSuccess();
        } catch (err) {
            console.log(err);
        }
    };
    const handleDocumentChange = ({ fileList: newFileList, file, event }) => {
        // You can control when to remove the files here
        // For example, removing files after upload completion
        const updatedList = newFileList.map((file) => ({
            ...file,
            uid: file.uid || `new-${Date.now()}`, // Ensure unique UID
        }));
        setFileList(updatedList);
    };
    // Transform employee data for the table
    useEffect(() => {
        if (isSuccess) {
            setFileList(employeeData?.user?.user_hr_documents);
            dispatch(setEmployee(employeeData?.employee_list));
        }
    }, [isSuccess, setEmployee, employeeData]);
    const handleDeleteDocument = (file) => {
        setFileList(
            fileList.filter((f) => {
                const deleteIdentifier = file.id || file.uid;
                const fileIdentifier = f.id || f.uid;
                return fileIdentifier !== deleteIdentifier;
            })
        );
    };
    if (isLoading) return <>Loading</>;
    const totalCount = employeeData.employees?.length || 0;
    const onFinish = async (values, id) => {
        try {
            const formData = new FormData();
            fileList.map((file, index) => {
                if (file.id) {
                    formData.append("old_images[]", file.id);

                    formData.append(
                        "old_title[]",
                        values[`image_title_${file.id}`]
                    );
                    return;
                }
                if (file.uid) {
                    formData.append("images[]", file.originFileObj);

                    formData.append(
                        "image_title[]",
                        values[`image_title_${file.uid}`]
                    );
                }
            });
            formData.append("user_id", id);
            await addDocument(formData);
            message.success(`Document changed successfully`);
        } catch (err) {
            console.log(err);
        }
    };

    if (isLoading)
        return (
            <Flex justify="center" align="center">
                <Spin />
            </Flex>
        );

    return (
        <div className="content">
            <Card
                style={{ marginTop: "20px", height: "600px" }}
                title="Upcoming Events"
                extra={
                    <Row
                        justify="end"
                        align=""
                        style={{ marginBottom: "10px" }}
                    >
                        <Col>
                            <Button
                                type="primary"
                                href={"/client_events_list/" + user_id}
                                icon={<FileAddOutlined />}
                            >
                                Event list
                            </Button>
                        </Col>
                    </Row>
                }
            >
                <Calendar
                    localizer={localizer}
                    events={calendarData}
                    startAccessor="start"
                    endAccessor="end"
                    views={["month", "agenda"]}
                    defaultView="month" // Set the default view to "week"
                    style={{ height: "400px" }}
                    toolbar={true}
                />
            </Card>

            {/* Staff List Table */}
            <Card
                style={{ marginTop: "20px" }}
                title="My Staff List"
                extra={
                    <Flex justify="flex-end" style={{ marginBottom: "10px" }}>
                        <Col span={24} style={{ marginRight: "20px" }}>
                            <Input
                                placeholder="Search..."
                                addonBefore={<SearchOutlined />}
                                value={employee_search}
                                onChange={(e) => OnSearchEmployee(e)}
                            />
                        </Col>
                        <Button
                            type="primary"
                            onClick={() => navigate("/staff/create/" + user_id)}
                        >
                            Create
                        </Button>
                    </Flex>
                }
            >
                <Table
                    columns={columns}
                    dataSource={employee_list.map((employee) => ({
                        key: employee.id,
                        name: employee.name,
                        email: employee.email,
                        job_title: employee.employee_details?.title || "N/A",
                        emp_picture: employee.employee_details?.emp_picture,
                        id: employee.id,
                    }))}
                    pagination={false}
                    rowKey="id"
                />
            </Card>

            {/* HR Documents Table */}
            <Card
                style={{ marginTop: "20px" }}
                title="General Documents"
                extra={
                    <Flex justify="flex-end" style={{ marginBottom: "10px" }}>
                        <Col span={24}>
                            <Input
                                placeholder="Search..."
                                addonBefore={<SearchOutlined />}
                                value={str_search}
                                onChange={(e) => OnSearchStr(e)}
                            />
                        </Col>
                    </Flex>
                }
            >
                <Form
                    layout="vertical"
                    form={form}
                    initialValues={{}}
                    onFinish={(values) => onFinish(values, user_id)}
                >
                    <Upload
                        listType="picture"
                        customRequest={handleDocumentRequest}
                        multiple={true}
                        fileList={fileList}
                        showUploadList={false}
                        onChange={handleDocumentChange}
                    >
                        <Button icon={<UploadOutlined />}>Upload</Button>
                    </Upload>

                    {fileList &&
                        fileList.map((file) => (
                            <Row
                                gutter={[16, 16]}
                                key={file.id}
                                className="image_box_data"
                                style={{ marginTop: "10px" }}
                            >
                                <Col span={3}>{ShowIcon(file)}</Col>
                                <Col span={2}>
                                    {file?.file
                                        ? file?.file.split("/")[2]
                                        : file?.name}
                                </Col>
                                <Col span={6}>
                                    <Form.Item
                                        style={{ margin: 0 }}
                                        name={`image_title_${
                                            file?.id || file.uid
                                        }`}
                                        initialValue={
                                            file.id ? file.title : null
                                        }
                                    >
                                        <Input
                                            placeholder="Enter title"
                                            required
                                        />
                                    </Form.Item>
                                </Col>

                                {file.id && (
                                    <Col span={2}>
                                        <Button
                                            type="primary"
                                            icon={<EyeOutlined />}
                                        >
                                            View
                                        </Button>
                                    </Col>
                                )}
                                {!file.id && <Col span={2}></Col>}
                                <Col span={2}>
                                    <Button
                                        type="default"
                                        icon={<DeleteOutlined />}
                                        onClick={() =>
                                            handleDeleteDocument(file)
                                        }
                                        color="danger"
                                        variant="solid"
                                        className="upload__img-close3"
                                    >
                                        Delete
                                    </Button>
                                </Col>
                            </Row>
                        ))}
                    <Flex justify="flex-end">
                        <Button
                            type="default"
                            style={{ marginRight: "10px" }}
                            onClick={() => window.history.back()}
                        >
                            Discard
                        </Button>
                        <Button type="primary" htmlType="submit">
                            Save
                        </Button>
                    </Flex>
                </Form>
            </Card>
        </div>
    );
};

export default Employee_listPage;
