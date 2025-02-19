import React, { useState } from "react";
import { useParams } from "react-router-dom";

import {
    Form,
    Input,
    Button,
    Row,
    Col,
    Flex,
    Typography,
    Spin,
    Select,
    Checkbox,
    Upload,
    Tabs,
    message,
} from "antd";
import { useNavigate } from "react-router-dom";
import { UploadOutlined, DeleteOutlined } from "@ant-design/icons";
import { setClient } from "./clients.slice";
import { useSelector, useDispatch } from "react-redux";
const { Option } = Select;

import { useCreateClientMutation } from "./clients.service";
import ShowIcon from "../staff/showIcon";
import "./style.css";
const MyStaffPage = ({}) => {
    const clients = useSelector((apps) => apps.client.clients);
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const [form] = Form.useForm();
    const { id } = useParams();
    const [fileList, setFileList] = useState([]);
    const [createClient, { isLoading: isUploading }] =
        useCreateClientMutation();
    const [file, setFile] = useState(null);

    const onFinish = async (values) => {
        const formData = new FormData();
        for (const key in values) {
            formData.append(key, values[key]);
        }
        formData.append("logo", file);
        try {
            //if (docFile) docFile.forEach((f) => formData.append("images[]", f));
            fileList.forEach((file, index) => {
                formData.append("images[]", file.originFileObj);
                formData.append(
                    "document[]",
                    values[`document_type_${file.uid}`]
                );
                formData.append(
                    "image_title[]",
                    values[`image_title_${file.uid}`]
                );
            });

            const result = await createClient(formData);
            dispatch(setClient([...clients, result.user]));
            message.success(`Client added successfully`);
        } catch (err) {
            console.log(err);
        }
        navigate("/clients");
    };
    const [isCommunityChecked, setIsCommunityChecked] = useState(false);

    const handleCommunityChange = (e) => {
        setIsCommunityChecked(e.target.checked);
    };
    const handleFileChange = async (info) => {
        if (info.file.status === "done") {
            message.success(`${info.file.name} file uploaded successfully`);
        } else if (info.file.status === "error") {
            message.error(`${info.file.name} file upload failed.`);
        }
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
        if (file.status === "done") {
            // Successfully uploaded, remove it from the list
            //setFileList([]);
            message.success(`${file.name} file uploaded successfully.`);
        } else if (file.status === "error") {
            // Error while uploading, you may want to handle it
            message.error(`${file.name} file upload failed.`);
        } else {
            // Keep the files in the list for further operations
            setFileList(newFileList);
        }
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
    if (isUploading)
        return (
            <Flex justify="center">
                <Spin />
            </Flex>
        );
    return (
        <Form form={form} layout="vertical" onFinish={onFinish}>
            <Row gutter={16}>
                <Col span={12}>
                    <Form.Item
                        label="Name"
                        name="name"
                        rules={[
                            {
                                required: true,
                                message: "Please input your name!",
                            },
                        ]}
                    >
                        <Input placeholder="Eugene Kopyov" />
                    </Form.Item>
                </Col>
                <Col span={12}>
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
                        <Input placeholder="email@gmail.com" />
                    </Form.Item>
                </Col>
            </Row>

            <Row gutter={16}>
                <Col span={12}>
                    <Form.Item
                        label="Status"
                        name="status"
                        rules={[
                            {
                                required: true,
                                message: "Please select a status!",
                            },
                        ]}
                    >
                        <Select placeholder="Select Status">
                            <Option key="1" value="1">
                                Active
                            </Option>
                            <Option key="0" value="0">
                                Inactive
                            </Option>
                        </Select>
                    </Form.Item>
                </Col>

                <Col span={24}>
                    <Form.Item name="send_email" valuePropName="checked">
                        <Checkbox>Email confirmation</Checkbox>
                    </Form.Item>
                </Col>
            </Row>

            <Row gutter={16}>
                <Col span={12}>
                    <Form.Item name="community" valuePropName="checked">
                        <Checkbox onChange={handleCommunityChange}>
                            Add to community
                        </Checkbox>
                    </Form.Item>
                </Col>
            </Row>
            {isCommunityChecked && (
                <Row gutter={16}>
                    <Col span={12}>
                        <Form.Item label="Specialism" name="keywords">
                            <Input name="keywords" placeholder="Specialism" />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item label="Industry" name="industry">
                            <Input name="industry" placeholder="Industry" />
                        </Form.Item>
                    </Col>
                </Row>
            )}

            <Row gutter={16}>
                <Col span={12}>
                    <Form.Item label="Company Details"></Form.Item>
                </Col>
            </Row>

            <Row gutter={16}>
                <Col span={12}>
                    <Form.Item name="company_name" label="Company Name">
                        <Input placeholder="Company Name" />
                    </Form.Item>
                </Col>

                <Col span={12}>
                    <Form.Item name="company_number" label="Company Number">
                        <Input placeholder="Company Number" />
                    </Form.Item>
                </Col>
            </Row>

            <Row gutter={16}>
                <Col span={12}>
                    <Form.Item
                        name="registered_address"
                        label="Registered Address"
                    >
                        <Input placeholder="Registered Address" />
                    </Form.Item>
                </Col>

                <Col span={12}>
                    <Form.Item name="vat_number" label="VAT Number">
                        <Input placeholder="VAT Number" />
                    </Form.Item>
                </Col>
            </Row>

            <Row gutter={16}>
                <Col span={12}>
                    <Form.Item
                        name="authentication_code"
                        label="Authentication Code"
                    >
                        <Input placeholder="Authentication Code" />
                    </Form.Item>
                </Col>

                <Col span={12}>
                    <Form.Item name="company_utr" label="Company UTR">
                        <Input placeholder="Company UTR" />
                    </Form.Item>
                </Col>
            </Row>

            <Row gutter={16}>
                <Col span={12}>
                    <Form.Item label="Logo">
                        <Upload
                            customRequest={handleupload}
                            listType="picture-card"
                            onChange={handleFileChange}
                            maxCount={1}
                        >
                            <div>
                                <UploadOutlined />
                                <div>Upload</div>
                            </div>
                        </Upload>
                        <small>[ Preferable size 500 × 450 px ]</small>
                    </Form.Item>
                </Col>
            </Row>

            <Form.Item label="Bank Details"></Form.Item>

            <Row gutter={16}>
                <Col span={12}>
                    <Form.Item name="bank_name" label="Bank Name">
                        <Input placeholder="Bank Name" />
                    </Form.Item>
                </Col>

                <Col span={12}>
                    <Form.Item name="sort_code" label="Sort Code">
                        <Input placeholder="Sort Code" />
                    </Form.Item>
                </Col>
            </Row>

            <Row gutter={16}>
                <Col span={12}>
                    <Form.Item name="account_number" label="Account Number">
                        <Input placeholder="Account Number" />
                    </Form.Item>
                </Col>

                <Col span={12}>
                    <Form.Item name="iban" label="IBAN">
                        <Input placeholder="IBAN" />
                    </Form.Item>
                </Col>
            </Row>

            <Row gutter={16}>
                <Col span={12}>
                    <Form.Item name="swift_code" label="Swift Code">
                        <Input placeholder="Swift Code" />
                    </Form.Item>
                </Col>
            </Row>

            <Form.Item label="API Details">
                <Tabs
                    defaultActiveKey="1"
                    items={[
                        {
                            key: "1",
                            label: "Xero",
                            children: (
                                <Row gutter={16}>
                                    <Col span={12}>
                                        <Form.Item
                                            name="xero_client_id"
                                            label="Client Id"
                                        >
                                            <Input placeholder="Client Id" />
                                        </Form.Item>
                                    </Col>

                                    <Col span={12}>
                                        <Form.Item
                                            name="xero_client_secret"
                                            label="Client Secret"
                                        >
                                            <Input placeholder="Client Secret" />
                                        </Form.Item>
                                    </Col>
                                </Row>
                            ),
                        },
                        {
                            key: "2",
                            label: "Google Analytics",
                            children: (
                                <Row gutter={16}>
                                    <Col span={12}>
                                        <Form.Item
                                            name="analytics_view_id"
                                            label="Analytics Property Id"
                                        >
                                            <Input placeholder="Property Id" />
                                        </Form.Item>
                                    </Col>
                                </Row>
                            ),
                        },
                    ]}
                ></Tabs>
            </Form.Item>

            <Row gutter={16}>
                <Col span={24}>
                    <Form.Item label="Documents">
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
                    </Form.Item>
                </Col>
            </Row>
            {fileList.map((file, index) => (
                <Row gutter={8} key={file.id} style={{ margin: 10 }}>
                    <Col span={4}>
                        <Flex justify="center">{ShowIcon(file)}</Flex>
                    </Col>
                    <Col span={2}>{file.name}</Col>
                    <Col span={6}>
                        <Flex justify="center" align="center">
                            <Form.Item
                                name={`image_title_${file.uid}`}
                                style={{ margin: 0 }}
                            >
                                <Input placeholder="Enter title" required />
                            </Form.Item>
                        </Flex>
                    </Col>
                    <Col span={6}>
                        <Form.Item
                            name={`document_type_${file.uid}`}
                            style={{ margin: 0 }}
                        >
                            <Select
                                style={{ width: "100%", margin: 0 }}
                                placeholder="Select Document Type"
                            >
                                <Option key="4" value="4">
                                    Marketing & brand
                                </Option>
                                <Option key="5" value="5">
                                    Legal business documentation
                                </Option>
                                <Option key="6" value="6">
                                    Templates
                                </Option>
                            </Select>
                        </Form.Item>
                    </Col>

                    <Col span={4}>
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
            <Flex justify="flex-end">
                <Button
                    type="default"
                    style={{ marginRight: "10px" }}
                    onClick={() => navigate("./employee_list/" + id)}
                >
                    Discard
                </Button>
                <Button type="primary" htmlType="submit">
                    Save
                </Button>
            </Flex>
        </Form>
    );
};
export default MyStaffPage;
