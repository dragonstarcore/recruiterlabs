import React, { useState } from "react";
import {
    BrowserRouter as Router,
    Route,
    Routes,
    useParams,
} from "react-router-dom";

import {
    Form,
    Input,
    DatePicker,
    Button,
    Table,
    Row,
    Col,
    Flex,
    Card,
    Upload,
    Typography,
    Spin,
} from "antd";
import { UploadOutlined, EyeOutlined, SearchOutlined } from "@ant-design/icons";
import moment from "moment";
import dayjs from "dayjs";
const { Title } = Typography;

import { useFetchEmployeeQuery } from "./staff.service";

//this page is for client
const MyStaffPage = ({}) => {
    const { id } = useParams();
    //id is employeeID
    const { data, isLoading, isSuccess } = useFetchEmployeeQuery(
        { employee_id: id },
        {
            refetchOnMountOrArgChange: true,
        }
    );
    const [currentPage, setCurrentPage] = useState(1); // current page number
    const [pageSize, setPageSize] = useState(5); // number of items per page

    if (isLoading) return <Spin />;
    const employee = data?.employee;
    const employee_details = data?.employee.employee_details;
    const employeeDocuments = data?.employee.employee_documents;
    const columns = [
        {
            title: "#",
            dataIndex: "index",
            key: "index",
            render: (text, record, index) => index + 1,
        },
        {
            title: "Image",
            dataIndex: "image",
            key: "image",
            render: (text, record) => {
                const fileExtension = record.file
                    ? record.file.split(".").pop()
                    : "";
                const image =
                    fileExtension === "pdf"
                        ? "/assets/images/pdf.png"
                        : fileExtension === "doc" || fileExtension === "docx"
                        ? "/assets/images/doc.jpg"
                        : `/${record.file}`;
                return (
                    <img
                        src={image}
                        alt="document"
                        className="rounded-pill"
                        width="36"
                        height="36"
                    />
                );
            },
        },
        {
            title: "Title",
            dataIndex: "title",
            key: "title",
            render: (text) => (
                <span>
                    {text ? text.charAt(0).toUpperCase() + text.slice(1) : ""}
                </span>
            ),
        },
        {
            title: "View",
            key: "view",
            render: (text, record) =>
                record.file ? (
                    <a
                        href={`public/${record.file}`}
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <Button
                            icon={<EyeOutlined />}
                            size="large"
                            type="primary"
                        />
                    </a>
                ) : null,
        },
    ];
    const totalCount = employeeDocuments?.length || 0;

    const onPageChange = (page, pageSize) => {
        setCurrentPage(page); // Update current page
        setPageSize(pageSize); // Update page size if the user changes it
    };
    return (
        <Card title="Staff Details">
            <Form
                layout="vertical"
                initialValues={{
                    ...employee,
                    ...employee_details,
                    date_of_birth: moment(employee.date_of_birth),
                    date_of_joining: moment(employee_details.date_of_joining),
                }}
                onFinish={() => {}}
            >
                {/* Name */}
                <Row gutter={[16, 16]}>
                    <Col span={24}>
                        <Form.Item label="Name" name="name">
                            <Input disabled value={employee.name} />
                        </Form.Item>
                    </Col>

                    {/* Email */}
                    <Col span={24}>
                        <Form.Item label="Email" name="email">
                            <Input disabled value={employee.email} />
                        </Form.Item>
                    </Col>

                    {/* Address */}
                    <Col span={24}>
                        <Form.Item label="Address" name="address">
                            <Input disabled value={employee.address} />
                        </Form.Item>
                    </Col>

                    {/* Phone Number */}
                    <Col span={24}>
                        <Form.Item label="Phone Number" name="phone_number">
                            <Input disabled value={employee.phone_number} />
                        </Form.Item>
                    </Col>

                    {/* Date of Birth */}
                    <Col span={24}>
                        <Form.Item label="Date of Birth" name="date_of_birth">
                            <DatePicker
                                disabled
                                value={
                                    employee.date_of_birth
                                        ? dayjs(
                                              employee.date_of_birth,
                                              "YYYY-MM-DD"
                                          )
                                        : null
                                }
                            />
                        </Form.Item>
                    </Col>

                    {/* Profile Picture */}
                    <Col span={24}>
                        <Form.Item label="Profile Picture">
                            {employee.employee_details?.emp_picture ? (
                                <img
                                    src={`/${employee.employee_details.emp_picture}`}
                                    alt="Profile"
                                    width="100px"
                                    height="100px"
                                />
                            ) : (
                                <div>No Profile Picture</div>
                            )}
                        </Form.Item>
                    </Col>

                    {/* Other Details */}
                    <Col span={24}>
                        <Title level={5}>Other Details</Title>
                    </Col>

                    {/* Bank Name */}
                    <Col span={24}>
                        <Form.Item label="Bank Name" name="bank_name">
                            <Input
                                disabled
                                value={employee.employee_details.bank_name}
                            />
                        </Form.Item>
                    </Col>

                    {/* Sort Code */}
                    <Col span={24}>
                        <Form.Item label="Sort Code" name="sort_code">
                            <Input
                                disabled
                                value={employee.employee_details?.sort_code}
                            />
                        </Form.Item>
                    </Col>

                    {/* Account Number */}
                    <Col span={24}>
                        <Form.Item label="Account Number" name="account_number">
                            <Input
                                disabled
                                value={
                                    employee.employee_details?.account_number
                                }
                            />
                        </Form.Item>
                    </Col>

                    {/* Title */}
                    <Col span={24}>
                        <Form.Item label="Title" name="title">
                            <Input
                                disabled
                                value={employee.employee_details?.title}
                            />
                        </Form.Item>
                    </Col>

                    {/* Salary */}
                    <Col span={24}>
                        <Form.Item label="Salary" name="salary">
                            <Input
                                disabled
                                value={employee.employee_details?.salary}
                            />
                        </Form.Item>
                    </Col>

                    {/* Direct Reports */}
                    <Col span={24}>
                        <Form.Item label="Direct Reports" name="direct_reports">
                            <Input
                                disabled
                                value={
                                    employee.employee_details?.direct_reports
                                }
                            />
                        </Form.Item>
                    </Col>

                    {/* Date of Joining */}
                    <Col span={24}>
                        <Form.Item
                            label="Date of Joining"
                            name="date_of_joining"
                        >
                            <DatePicker
                                disabled
                                value={
                                    employee.employee_details?.date_of_joining
                                        ? moment(
                                              employee.employee_details
                                                  .date_of_joining
                                          )
                                        : null
                                }
                            />
                        </Form.Item>
                    </Col>

                    {/* Documents */}
                    <Col span={24}>
                        <Title level={5}>Documents</Title>
                        <Flex
                            justify="flex-end"
                            style={{ marginBottom: "10px" }}
                        >
                            <Col span={6}>
                                <Input
                                    placeholder="Search..."
                                    addonBefore={<SearchOutlined />}
                                />
                            </Col>
                        </Flex>
                        <Table
                            columns={columns}
                            dataSource={employeeDocuments}
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
                    </Col>
                </Row>
            </Form>
        </Card>
    );
};
export default MyStaffPage;
