import React, { useState, useEffect } from "react";
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
    Select,
    message,
} from "antd";
import {
    UploadOutlined,
    EyeOutlined,
    SearchOutlined,
    InboxOutlined,
    DeleteOutlined,
} from "@ant-design/icons";
import moment from "moment";
const { Title } = Typography;

import { useFetchEmployeeQuery, useUpdateStaffMutation } from "./staff.service";
import ShowIcon from "./showIcon";
import dayjs from "dayjs";
const MyStaffPage = ({}) => {
    const [form] = Form.useForm();
    const { employee_id, user_id } = useParams();
    const { data, isLoading, isSuccess } = useFetchEmployeeQuery(
        { employee_id, user_id },
        {
            refetchOnMountOrArgChange: true,
        }
    );

    const [
        updateStaff,
        { isLoading: isLoadingUpdate, isSuccess: isUpdatingSuccess },
    ] = useUpdateStaffMutation();

    const [currentPage, setCurrentPage] = useState(1); // current page number
    const [pageSize, setPageSize] = useState(5); // number of items per page

    const employee = data?.employee;
    const employee_details = data?.employee?.employee_details;
    const employeeDocuments = data?.employee?.employee_documents;

    const [logofilelist, setLogofilelist] = useState([]);
    const onPageChange = (page, pageSize) => {
        setCurrentPage(page); // Update current page
        setPageSize(pageSize); // Update page size if the user changes it
    };
    const handleFileChange = async ({ fileList }) => {
        setLogofilelist(fileList);
        console.log(fileList);
    };
    const handleupload = async ({ file, onSuccess, onError }) => {
        try {
            onSuccess(); // Call onSuccess if the upload succeeds
        } catch (err) {
            console.log(err);
        }
    };
    const [fileList, setFileList] = useState([]);
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
    const handleDeleteDocument = (file) => {
        setFileList(
            fileList.filter((f) => {
                const deleteIdentifier = file.id || file.uid;
                const fileIdentifier = f.id || f.uid;
                return fileIdentifier !== deleteIdentifier;
            })
        );
    };
    useEffect(() => {
        if (isSuccess) {
            setFileList(data?.employee.employee_documents);
            setLogofilelist([
                {
                    uid: "-1", // Unique ID for the file
                    name: "logo", // File name
                    status: "done", // File status
                    url: "/" + data?.employee?.employee_details?.emp_picture, // Full URL of the image
                },
            ]);
            form.setFieldsValue({
                ...data?.employee,
                ...data?.employee.employee_details,
                date_of_birth: dayjs(
                    data?.employee?.date_of_birth,
                    "YYYY-MM-DD"
                ),
                date_of_joining: dayjs(
                    data?.employee.employee_details?.date_of_joining,
                    "YYYY-MM-DD"
                ),
            });
        }
    }, [isSuccess, form, data]);

    const onFinish = async (values, user_id, employee_id) => {
        // Handle the form submission logic here
        const formData = new FormData();

        for (const key in values) {
            if (key == "date_of_joining") {
                formData.append(key, values[key].format("YYYY-MM-DD"));
                continue;
            }
            if (key == "date_of_birth") {
                formData.append(key, values[key].format("YYYY-MM-DD"));
                continue;
            }
            formData.append(key, values[key]);
        }
        if (logofilelist[0]?.originFileObj)
            formData.append(
                "emp_picture",
                logofilelist[0]?.originFileObj || null
            );

        formData.append("employee_id", employee_id);
        formData.append("user_id", user_id);
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

        try {
            //const { error } = await updateStaff({
            //     ...values,
            //     employee_id,
            //     user_id,
            // });
            const { aserror } = await updateStaff(formData);
            // if (error) {
            //     message.error(`employee failed successfully`);
            //     return;
            // }
            message.success(`employee updated successfully`);
            // navigate("/employee_list/" + user_id);
        } catch (err) {
            message.error(`employee update failed`);
        }
    };
    if (isLoading) return <Spin />;

    return (
        <Card title="Staff Details">
            <Form
                labelCol={{ span: 6 }}
                wrapperCol={{ span: 18 }}
                layout="horizontal"
                form={form}
                onFinish={(values) => onFinish(values, user_id, employee_id)}
            >
                {/* Name */}
                <Row gutter={[16, 16]}>
                    <Col span={24}>
                        <Form.Item
                            label="Name"
                            name="name"
                            rules={[
                                {
                                    required: true,
                                    message: "Please input the name!",
                                },
                            ]}
                        >
                            <Input />
                        </Form.Item>
                    </Col>

                    {/* Email */}
                    <Col span={24}>
                        <Form.Item
                            label="Email"
                            name="email"
                            rules={[
                                {
                                    required: true,
                                    type: "email",
                                    message: "Please input a valid email!",
                                },
                            ]}
                        >
                            <Input />
                        </Form.Item>
                    </Col>

                    {/* Address */}
                    <Col span={24}>
                        <Form.Item
                            label="Address"
                            name="address"
                            rules={[
                                {
                                    required: true,
                                    message: "Please input the address!",
                                },
                            ]}
                        >
                            <Input value={employee.address} />
                        </Form.Item>
                    </Col>

                    {/* Phone Number */}
                    <Col span={24}>
                        <Form.Item
                            label="Phone Number"
                            name="phone_number"
                            rules={[
                                {
                                    required: true,
                                    message: "Please input the phone number!",
                                },
                            ]}
                        >
                            <Input value={employee.phone_number} />
                        </Form.Item>
                    </Col>

                    {/* Date of Birth */}
                    <Col span={24}>
                        <Form.Item
                            label="Date of Birth"
                            name="date_of_birth"
                            rules={[
                                {
                                    required: true,
                                    message:
                                        "Please select your date of birth!",
                                },
                            ]}
                        >
                            <DatePicker />
                        </Form.Item>
                    </Col>

                    {/* Profile Picture */}
                    <Col span={24}>
                        <Form.Item label="Profile Picture">
                            <Upload
                                maxCount={1}
                                customRequest={handleupload}
                                listType="picture-card"
                                onChange={handleFileChange}
                                fileList={logofilelist}
                            >
                                <div>
                                    <UploadOutlined />
                                    <div>Upload</div>
                                </div>
                            </Upload>
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
                                value={employee?.employee_details?.bank_name}
                            />
                        </Form.Item>
                    </Col>

                    {/* Sort Code */}
                    <Col span={24}>
                        <Form.Item label="Sort Code" name="sort_code">
                            <Input
                                value={employee?.employee_details?.sort_code}
                            />
                        </Form.Item>
                    </Col>

                    {/* Account Number */}
                    <Col span={24}>
                        <Form.Item label="Account Number" name="account_number">
                            <Input
                                value={
                                    employee.employee_details?.account_number
                                }
                            />
                        </Form.Item>
                    </Col>

                    {/* Title */}
                    <Col span={24}>
                        <Form.Item label="Title" name="title">
                            <Input value={employee.employee_details?.title} />
                        </Form.Item>
                    </Col>

                    {/* Salary */}
                    <Col span={24}>
                        <Form.Item label="Salary" name="salary">
                            <Input value={employee.employee_details?.salary} />
                        </Form.Item>
                    </Col>

                    {/* Direct Reports */}
                    <Col span={24}>
                        <Form.Item label="Direct Reports" name="direct_reports">
                            <Select placeholder="Select Direct Reports">
                                {data?.employee_list.map((employee) => (
                                    <Select.Option
                                        key={employee.id}
                                        value={employee.id}
                                    >
                                        {employee.name}
                                    </Select.Option>
                                ))}
                            </Select>
                        </Form.Item>
                    </Col>

                    {/* Date of Joining */}
                    <Col span={24}>
                        <Form.Item
                            label="Date of Joining"
                            name="date_of_joining"
                        >
                            <DatePicker />
                        </Form.Item>
                    </Col>
                    <Col span={24}>
                        {/* Documents */}
                        <Form.Item label="Documents" name="documents">
                            <Upload
                                listType="picture"
                                customRequest={handleDocumentRequest}
                                multiple={true}
                                fileList={fileList}
                                showUploadList={false}
                                onChange={handleDocumentChange}
                            >
                                <Button icon={<InboxOutlined />}>
                                    Upload Documents
                                </Button>
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
                        </Form.Item>
                    </Col>
                </Row>
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
    );
};
export default MyStaffPage;
