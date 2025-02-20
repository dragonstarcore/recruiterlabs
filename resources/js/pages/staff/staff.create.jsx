import React, { useState, useEffect } from "react";
import {
    Card,
    Row,
    Col,
    Input,
    Button,
    Upload,
    message,
    Flex,
    Spin,
    Form,
    DatePicker,
    Select,
} from "antd";
import { useNavigate, useParams } from "react-router-dom";
import {
    UploadOutlined,
    EyeOutlined,
    InboxOutlined,
    DeleteOutlined,
} from "@ant-design/icons";
import "react-big-calendar/lib/css/react-big-calendar.css"; // for react-big-calendar
import { toast } from "react-toastify";
import { useAddStaffMutation, useGetStaffQuery } from "./staff.service";
import ShowIcon from "./showIcon";
const MyStaffPage = ({}) => {
    const { user_id } = useParams();
    const navigate = useNavigate();
    const [form] = Form.useForm();
    const [logofileList, setLogofileList] = useState([
        {
            uid: "-1", // Unique ID for the file
            name: "logo", // File name
            status: "done", // File status
            url: "", // Full URL of the image
        },
    ]);
    const { data, isLoading } = useGetStaffQuery(user_id);
    const [addStaff] = useAddStaffMutation();
    const [file, setFile] = useState(null);
    const onFinish = async (values, id) => {
        // Handle the form submission logic here
        const formData = new FormData();

        for (const key in values) {
            formData.append(key, values[key]);
        }
        formData.append("emp_picture", file);
        fileList.map((file, index) => {
            if (file.uid) {
                formData.append("images[]", file.originFileObj);

                formData.append(
                    "image_title[]",
                    values[`image_title_${file.uid}`]
                );
            }
        });
        formData.append("user_id", id);
        try {
            await addStaff(formData);
            toast.success("Employee added successfully", {
                position: "top-right",
            });
            navigate("/employee_list/" + id);
        } catch (err) {
            toast.error("Employee add failed", {
                position: "top-right",
            });
            message.error(`employee add failed`);
        }
    };
    const [fileList, setFileList] = useState([]);
    const handleFileChange = async ({ fileList }) => {
        setLogofileList(fileList);
    };
    const handleupload = async ({ file, onSuccess, onError }) => {
        try {
            setFile(file);
            onSuccess(); // Call onSuccess if the upload succeeds
        } catch (err) {
            console.log(err);
        }
    };
    const handleDocumentRequest = async ({ onSuccess }) => {
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
    if (isLoading)
        return (
            <Flex justify="center" align="center">
                <Spin />
            </Flex>
        );
    return (
        <Card title="Staff Details">
            <Form
                form={form}
                onFinish={(values) => onFinish(values, user_id)}
                initialValues={{}}
                labelCol={{ span: 6 }}
                wrapperCol={{ span: 18 }}
                layout="horizontal"
                encType="multipart/form-data"
            >
                {/* Name Field */}
                <Form.Item
                    label="Name"
                    name="name"
                    rules={[
                        { required: true, message: "Please input the name!" },
                    ]}
                >
                    <Input placeholder="Eugene Kopyov" />
                </Form.Item>

                {/* Email Field */}
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
                    <Input type="email" placeholder="email@gmail.com" />
                </Form.Item>

                {/* Address Field */}
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
                    <Input placeholder="Address" />
                </Form.Item>

                {/* Phone Number Field */}
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
                    <Input placeholder="Phone Number" />
                </Form.Item>

                {/* Date of Birth Field */}
                <Form.Item
                    label="Date of Birth"
                    name="date_of_birth"
                    rules={[
                        {
                            required: true,
                            message: "Please select your date of birth!",
                        },
                    ]}
                >
                    <DatePicker
                        style={{ width: "100%" }}
                        placeholder="Date of Birth"
                    />
                </Form.Item>

                {/* Profile Picture Field */}
                <Form.Item label="Profile Picture" name="emp_picture">
                    <Upload
                        maxCount={1}
                        customRequest={handleupload}
                        fileList={logofileList}
                        listType="picture-card"
                        onChange={handleFileChange}
                    >
                        <div>
                            <UploadOutlined />
                            <div>Upload</div>
                        </div>
                    </Upload>
                </Form.Item>

                {/* Other Details */}

                {/* Bank Name Field */}
                <Form.Item label="Bank Name" name="bank_name">
                    <Input placeholder="Bank Name" />
                </Form.Item>

                {/* Sort Code Field */}
                <Form.Item label="Sort Code" name="sort_code">
                    <Input placeholder="Sort Code" />
                </Form.Item>

                {/* Account Number Field */}
                <Form.Item label="Account Number" name="account_number">
                    <Input placeholder="Account Number" />
                </Form.Item>

                {/* Title Field */}
                <Form.Item
                    label="Title"
                    name="title"
                    rules={[
                        { required: true, message: "Please input the title!" },
                    ]}
                >
                    <Input placeholder="Title" />
                </Form.Item>

                {/* Salary Field */}
                <Form.Item
                    label="Salary"
                    name="salary"
                    rules={[
                        { required: true, message: "Please input the salary!" },
                    ]}
                >
                    <Input placeholder="Salary" />
                </Form.Item>

                {/* Direct Reports Field */}
                <Form.Item label="Direct Reports" name="direct_reports">
                    <Select placeholder="Select Direct Reports">
                        {data?.employees.map((employee) => (
                            <Select.Option
                                key={employee.id}
                                value={employee.id}
                            >
                                {employee.name}
                            </Select.Option>
                        ))}
                    </Select>
                </Form.Item>

                {/* Date of Joining Field */}
                <Form.Item
                    label="Date of Joining"
                    name="date_of_joining"
                    rules={[
                        {
                            required: true,
                            message: "Please select the date of joining!",
                        },
                    ]}
                >
                    <DatePicker
                        style={{ width: "100%" }}
                        placeholder="Date of Joining"
                    />
                </Form.Item>

                {/* Documents Upload */}
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
                            Click to Upload Documents
                        </Button>
                    </Upload>
                </Form.Item>
                {fileList.map((file, i) => (
                    <Row
                        gutter={[16, 16]}
                        key={i}
                        className="image_box_data"
                        style={{ margin: "10px" }}
                    >
                        <Col span={3}>{ShowIcon(file)}</Col>
                        <Col span={2}>
                            {file?.file ? file?.file.split("/")[2] : file?.name}
                        </Col>
                        <Col span={6}>
                            <Form.Item
                                style={{ margin: 0 }}
                                name={`image_title_${file.uid}`}
                            >
                                <Input placeholder="Enter title" required />
                            </Form.Item>
                        </Col>

                        {file.id && (
                            <Col span={2}>
                                <Button type="primary" icon={<EyeOutlined />}>
                                    View
                                </Button>
                            </Col>
                        )}
                        {!file.id && <Col span={2}></Col>}
                        <Col span={2}>
                            <Button
                                type="default"
                                icon={<DeleteOutlined />}
                                onClick={() => handleDeleteDocument(file)}
                                color="danger"
                                variant="solid"
                                className="upload__img-close3"
                            >
                                Delete
                            </Button>
                        </Col>
                    </Row>
                ))}

                {/* Form Buttons */}
                <Form.Item wrapperCol={{ span: 18, offset: 6 }}>
                    <Flex justify="flex-end">
                        <Button
                            type="default"
                            onClick={() =>
                                navigate("/employee_list/" + user_id)
                            }
                            style={{ marginRight: "10px" }}
                        >
                            Discard
                        </Button>
                        <Button type="primary" htmlType="submit">
                            Save <i className="ph-paper-plane-tilt ms-2"></i>
                        </Button>
                    </Flex>
                </Form.Item>
            </Form>
        </Card>
    );
};

export default MyStaffPage;
