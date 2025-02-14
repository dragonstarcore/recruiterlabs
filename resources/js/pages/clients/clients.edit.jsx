import React, { useEffect, useState } from "react";
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
    Typography,
    Spin,
    Select,
    Checkbox,
    Upload,
    Image,
    Tabs,
    message,
} from "antd";

import {
    UploadOutlined,
    EyeOutlined,
    SearchOutlined,
    DeleteOutlined,
} from "@ant-design/icons";

import {
    useFetchClientQuery,
    useUpdateClientMutation,
} from "./clients.service";
import "./style.css";
import ShowIcon from "../staff/showIcon";
const { Option } = Select;
const { Title } = Typography;
const { TabPane } = Tabs;
const MyStaffPage = ({}) => {
    const { id } = useParams();
    const { data, isLoading, isSuccess } = useFetchClientQuery(id, {
        refetchOnMountOrArgChange: true,
    });
    const user = data?.user;
    const [isCommunityChecked, setIsCommunityChecked] = useState(false);

    const [
        updateClient,
        { isLoading: isLoadingUpdate, isSuccess: isUpdatingSuccess },
    ] = useUpdateClientMutation();

    const [form] = Form.useForm();
    const [file, setFile] = useState(null);
    const [docFile, setDocFile] = useState([]);
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

    const handleDocumentRequest = async ({
        file,
        fileList,
        onSuccess,
        onError,
    }) => {
        try {
            setDocFile([...docFile, file]);
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
    const [fileList, setFileList] = useState([]);
    const [initialValue, setInitialValue] = useState({});
    useEffect(() => {
        if (isSuccess) {
            setFileList(data?.user?.user_documents);
            console.log(data?.user?.user_documents);
        }
    }, [isSuccess, data]);
    const onFinish = async (values, id) => {
        try {
            const formData = new FormData();
            for (const key in values) {
                formData.append(key, values[key]);
            }
            formData.append("logo", file);
            console.log(fileList);
            console.log(values);

            fileList.map((file, index) => {
                if (file.id) {
                    formData.append("old_images[]", file.id);
                    formData.append(
                        "old_document[]",
                        values[`document_type_${file.id}`]
                    );
                    formData.append(
                        "old_title[]",
                        values[`image_title_${file.id}`]
                    );
                    return;
                }
                if (file.uid) {
                    formData.append("images[]", file.originFileObj);
                    formData.append(
                        "document[]",
                        values[`document_type_${file.uid}`]
                    );
                    formData.append(
                        "title[]",
                        values[`image_title_${file.uid}`]
                    );
                }
            });
            formData.append("id", id);
            const result = await updateClient(formData);
        } catch (err) {
            console.log(err);
        }
    };
    const showIcon = (file) => {
        console.log("file", file);
        if (file.file_ext == "pdf" || file.file_ext == "doc")
            return (
                <>
                    <Image
                        src={"/assets/images/" + file.file_ext + ".png"}
                        className="rounded-pill"
                        width={70}
                        height={50}
                        alt="File Thumbnail"
                    />
                    <>{file.file}</>
                </>
            );
        if (file?.type == "application/pdf") {
            return (
                <>
                    <Image
                        src={"/assets/images/pdf.png"}
                        className="rounded-pill"
                        width={70}
                        height={50}
                        alt="File Thumbnail"
                    />
                    <>{file.file}</>
                </>
            );
        }
        if (file?.type == "application/doc") {
            return (
                <>
                    <Image
                        src={"/assets/images/doc.png"}
                        className="rounded-pill"
                        width={70}
                        height={50}
                        alt="File Thumbnail"
                    />
                    <>{file.file}</>
                </>
            );
        }
        if (file?.id)
            return (
                <Image
                    src={
                        "/" + file?.file ||
                        URL.createObjectURL(file?.originFileObj)
                    }
                    className="rounded-pill"
                    width={70}
                    height={50}
                    alt="File Thumbnail"
                />
            );
        if (file?.uid)
            return (
                <Image
                    src={URL.createObjectURL(file?.originFileObj)}
                    className="rounded-pill"
                    width={70}
                    height={50}
                    alt="File Thumbnail"
                />
            );
    };
    if (isLoading) return <Spin />;
    return (
        <Card title="Staff Details">
            <Form
                layout="vertical"
                form={form}
                initialValues={{
                    ...user,
                    ...user.user_details,
                    xero_client_id: user.xero_details.client_id,
                    xero_client_secret: user.xero_details.client_secret,
                    analytics_view_id: user?.jobadder_details.analytics_view_id,
                }}
                onFinish={(values) => onFinish(values, id)}
            >
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
                            label="Password"
                            name="password"
                            rules={[
                                {
                                    required: true,
                                    type: "password",
                                    message: "Please input a password!",
                                },
                            ]}
                        >
                            <Input />
                        </Form.Item>
                    </Col>
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
                                <Option value={1}>Active</Option>
                                <Option value={0}>Inactive</Option>
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
                                <Input
                                    name="keywords"
                                    placeholder="Specialism"
                                />
                            </Form.Item>
                        </Col>
                        <Col span={12}>
                            <Form.Item label="Industry" name="industry">
                                <Input name="industry" placeholder="Industry" />
                            </Form.Item>
                        </Col>
                    </Row>
                )}
                <Row>
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
                        <Form.Item label="Logo" name="logo">
                            <Upload
                                listType="picture-card"
                                maxCount={1}
                                defaultFileList={[
                                    {
                                        status: "done",
                                        url: "/" + user?.user_details.logo, // URL of the default logo
                                    },
                                ]}
                                customRequest={handleupload}
                                onChange={handleFileChange}
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
                    <Tabs defaultActiveKey="1">
                        <TabPane tab="Xero" key="1">
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
                        </TabPane>

                        <TabPane tab="Google Analytics" key="2">
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
                        </TabPane>
                    </Tabs>
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
                                <Button icon={<UploadOutlined />}>
                                    Upload
                                </Button>
                            </Upload>
                        </Form.Item>
                    </Col>
                </Row>
                {fileList &&
                    fileList.map((file) => (
                        <Row
                            gutter={[16, 16]}
                            key={file.id}
                            className="image_box_data"
                        >
                            <Col span={4}>{ShowIcon(file)}</Col>
                            <Col span={2}>
                                {file?.file
                                    ? file?.file.split("/")[2]
                                    : file?.name}
                            </Col>
                            <Col span={6}>
                                <Form.Item
                                    style={{ margin: 0 }}
                                    name={`image_title_${file?.id || file.uid}`}
                                    initialValue={file.id ? file.title : null}
                                >
                                    <Input placeholder="Enter title" required />
                                </Form.Item>
                            </Col>
                            <Col span={6}>
                                <Form.Item
                                    style={{ margin: 0 }}
                                    name={`document_type_${
                                        file.id || file.uid
                                    }`}
                                    initialValue={file.id ? file.type_id : null}
                                >
                                    <Select
                                        style={{ width: "100%" }}
                                        placeholder="Select Document Type"
                                    >
                                        <Option value={4}>
                                            Marketing & brand
                                        </Option>
                                        <Option value={5}>
                                            Legal business documentation
                                        </Option>
                                        <Option value={6}>Templates</Option>
                                    </Select>
                                </Form.Item>
                            </Col>

                            <Col span={2}>
                                <Button type="primary" icon={<EyeOutlined />}>
                                    View
                                </Button>
                            </Col>
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
