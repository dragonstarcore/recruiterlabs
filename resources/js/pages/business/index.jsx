import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import {
    Card,
    Input,
    Form,
    Table,
    Layout,
    Typography,
    Button,
    Row,
    Spin,
    Col,
    Select,
    Image,
    Collapse,
    Tabs,
} from "antd";
import { DownOutlined } from "@ant-design/icons";
const { Title, Text } = Typography;
const { Header } = Layout;
const { Option } = Select;

const { Panel } = Collapse;
const { TabPane } = Tabs;

import {
    useFetchBusinessQuery,
    useFetchDocumentQuery,
    useFetchBusinessSearchMutation,
} from "./business.service";
import { setBusiness, setDoc_types } from "./business.slice";
import "./style.css";
export default function Business() {
    const dispatch = useDispatch();
    const [form] = Form.useForm();
    const [type_search, setType_search] = useState("");
    const { data, isError, isSuccess, isLoading } = useFetchBusinessQuery();
    const {
        data: docData,
        isSuccess: isDocSucess,
        isLoading: isDocLoading,
    } = useFetchDocumentQuery();

    useEffect(() => {
        if (isSuccess) {
            dispatch(setBusiness(data.user));
        }
    }, [isSuccess, data]);
    useEffect(() => {
        if (isDocSucess) {
            dispatch(setDoc_types(docData.documents));
        }
    }, [isDocSucess, docData]);
    const [
        fetchBusinessSearch,
        { isLoading: isJobadderLoading, isSuccess: isJobadderSuccess, error },
    ] = useFetchBusinessSearchMutation();

    const handleSubmit = async () => {
        try {
            const formValues = form.getFieldsValue();
            const jobadder = await fetchBusinessSearch(formValues).unwrap();
            console.log(jobadder);
            dispatch(setBusiness(jobadder.user));
        } catch (err) {
            console.log(err);
        }
    };
    const user = useSelector((apps) => apps.business.businessData.user);
    const documents = useSelector(
        (apps) => apps.business.businessData.doc_types
    );
    const totalCount = user?.user_documents.length || 0;
    const [currentPage, setCurrentPage] = useState(1); // current page number
    const [pageSize, setPageSize] = useState(5); // number of items per page
    const onPageChange = (page, pageSize) => {
        setCurrentPage(page); // Update current page
        setPageSize(pageSize); // Update page size if the user changes it
    };
    if (isLoading || isDocLoading || isJobadderLoading)
        return (
            <div className="business-body">
                <Spin size="large" />
            </div>
        );
    return (
        <>
            <Card title="My Business Details">
                {/* Company Details Section */}
                <Row gutter={[0, 20]}>
                    <Col span={24}>
                        <Title level={5}>Company Details</Title>
                    </Col>
                    <Col span={6}>
                        <Text strong>Company Name:</Text>
                    </Col>
                    <Col span={18}>
                        <Text>{user?.user_details?.company_name}</Text>
                    </Col>
                    <Col span={6}>
                        <Text strong>Company Number:</Text>
                    </Col>
                    <Col span={18}>
                        <Text>{user?.user_details?.company_number}</Text>
                    </Col>
                    <Col span={6}>
                        <Text strong>Registered Address:</Text>
                    </Col>
                    <Col span={18}>
                        <Text>{user?.user_details?.registered_address}</Text>
                    </Col>
                    <Col span={6}>
                        <Text strong>VAT Number:</Text>
                    </Col>
                    <Col span={18}>
                        <Text>{user?.user_details?.vat_number}</Text>
                    </Col>
                    <Col span={6}>
                        <Text strong>Authentication Code:</Text>
                    </Col>
                    <Col span={18}>
                        <Text>{user?.user_details?.authentication_code}</Text>
                    </Col>
                    <Col span={6}>
                        <Text strong>Company UTR:</Text>
                    </Col>
                    <Col span={18}>
                        <Text>{user?.user_details?.company_utr}</Text>
                    </Col>
                </Row>

                {/* Bank Details Section */}
                <Row gutter={[0, 20]} style={{ marginTop: "20px" }}>
                    <Col span={24}>
                        <Title level={5}>Bank Details</Title>
                    </Col>
                    <Col span={6}>
                        <Text strong>Bank Name:</Text>
                    </Col>
                    <Col span={18}>
                        <Text>{user?.user_details?.bank_name}</Text>
                    </Col>
                    <Col span={6}>
                        <Text strong>Sort Code:</Text>
                    </Col>
                    <Col span={18}>
                        <Text>{user?.user_details?.sort_code}</Text>
                    </Col>
                    <Col span={6}>
                        <Text strong>Account Number:</Text>
                    </Col>
                    <Col span={18}>
                        <Text>{user?.user_details?.account_number}</Text>
                    </Col>
                    <Col span={6}>
                        <Text strong>IBAN:</Text>
                    </Col>
                    <Col span={18}>
                        <Text>{user?.user_details?.iban}</Text>
                    </Col>
                    <Col span={6}>
                        <Text strong>Swift Code:</Text>
                    </Col>
                    <Col span={18}>
                        <Text>{user?.user_details?.swift_code}</Text>
                    </Col>
                </Row>

                {/* Logo Section */}
                <Row gutter={[0, 20]} style={{ marginTop: "20px" }}>
                    <Col span={6}>
                        <Text strong>Logo:</Text>
                    </Col>
                    <Col span={18}>
                        {user?.user_details?.logo ? (
                            <img
                                src={
                                    "https://www.recstack.co/public/" +
                                    `${user?.user_details?.logo}`
                                }
                                alt="Business Logo"
                                style={{ width: "100px", height: "100px" }}
                            />
                        ) : (
                            <Text>No Logo Uploaded</Text>
                        )}
                    </Col>
                </Row>

                <Form form={form}>
                    {/* Company Documents Section */}
                    <Row gutter={16}>
                        <Col span={6}>
                            <h5>
                                <b>Company Documents</b>
                            </h5>
                        </Col>
                        <Col span={8}>
                            <Form.Item name="type_search">
                                <Select
                                    placeholder="Filter By Document Type"
                                    allowClear
                                    onChange={(value) => setType_search(value)}
                                >
                                    <Option value="">All</Option>
                                    {documents.map((doc) => (
                                        <Option value={doc.id}>
                                            {doc.title}
                                        </Option>
                                    ))}
                                </Select>
                            </Form.Item>
                        </Col>
                        <Col span={4}>
                            <Form.Item>
                                <Button
                                    type="primary"
                                    htmlType="submit"
                                    onClick={handleSubmit}
                                >
                                    Filter
                                </Button>
                            </Form.Item>
                        </Col>
                    </Row>
                </Form>

                <Table
                    className="employee_list"
                    columns={[
                        {
                            title: "#",
                            dataIndex: "index",
                            key: "index",
                            render: (text, record, index) => index + 1,
                        },
                        {
                            title: "Image",
                            dataIndex: "file",
                            key: "file",
                            render: (file) => {
                                let image;
                                if (file) {
                                    const extension = file.split(".").pop();
                                    if (extension === "pdf") {
                                        image = "/assets/images/pdf.png";
                                    } else if (
                                        ["doc", "docx"].includes(extension)
                                    ) {
                                        image = "/assets/images/doc.jpg";
                                    } else {
                                        image = `/public/${file}`;
                                    }
                                } else {
                                    image = "/assets/images/default_user.jpg";
                                }
                                return (
                                    <Image
                                        src={image}
                                        width={36}
                                        height={36}
                                        style={{ borderRadius: "50%" }}
                                        alt=""
                                        preview={false}
                                    />
                                );
                            },
                        },
                        {
                            title: "Title",
                            dataIndex: "title",
                            key: "title",
                            sorter: (a, b) => a.title.localeCompare(b.title),

                            filterDropdown: ({
                                setSelectedKeys,
                                selectedKeys,
                                confirm,
                                clearFilters,
                            }) => (
                                <div style={{ padding: 8 }}>
                                    <Input
                                        value={selectedKeys[0]}
                                        onChange={(e) =>
                                            setSelectedKeys(
                                                e.target.value
                                                    ? [e.target.value]
                                                    : []
                                            )
                                        }
                                        placeholder="Search by name"
                                        style={{
                                            width: 188,
                                            marginBottom: 8,
                                            display: "block",
                                        }}
                                    />
                                    <Button
                                        type="primary"
                                        size="small"
                                        onClick={() => confirm()}
                                        style={{ marginRight: 8 }}
                                    >
                                        Search
                                    </Button>
                                    <Button
                                        onClick={() => clearFilters()}
                                        size="small"
                                        style={{ marginRight: 8 }}
                                    >
                                        Reset
                                    </Button>
                                </div>
                            ),
                            onFilter: (value, record) =>
                                record.title
                                    .toLowerCase()
                                    .includes(value.toLowerCase()), // Filter function for name

                            render: (title) =>
                                title
                                    ? title.charAt(0).toUpperCase() +
                                      title.slice(1)
                                    : "",
                        },
                        {
                            title: "Type",
                            dataIndex: "type_id",
                            key: "type_id",
                            sorter: (a, b) => a.type_id - b.type_id,
                            render: (type_id) => {
                                if (type_id == "4") return "Marketing & brand";
                                if (type_id == "5")
                                    return "Legal business documentation";
                                if (type_id == "6") return "Templates";
                                return "";
                            },
                        },
                        {
                            title: "View",
                            dataIndex: "file",
                            key: "view",
                            render: (file) =>
                                file ? (
                                    <Button
                                        type="primary"
                                        size="small"
                                        href={`https://www.recstack.co/public/${file}`}
                                        target="_blank"
                                        icon={<i className="ph-eye"></i>}
                                    >
                                        View
                                    </Button>
                                ) : null,
                        },
                    ]}
                    dataSource={user?.user_documents}
                    pagination={{
                        current: currentPage, // Current page number
                        pageSize: pageSize, // Number of items per page
                        total: totalCount, // Total number of items
                        onChange: onPageChange,
                        showSizeChanger: true, // Show size changer dropdown (optional)
                        pageSizeOptions: ["5", "10", "20", "50"], // Page size options
                    }}
                    scroll={{ x: true }}
                />
            </Card>
            <div>
                <Collapse defaultActiveKey={["1"]}>
                    <Panel header="API Details" key="1">
                        <Card>
                            <Tabs defaultActiveKey="1">
                                <TabPane tab="Xero" key="1">
                                    <Row gutter={16}>
                                        <Col span={6}>
                                            <label>Client Id:</label>
                                        </Col>
                                        <Col span={18}>
                                            <Text>
                                                {user?.xero_details
                                                    ?.client_id || "N/A"}
                                            </Text>
                                        </Col>
                                    </Row>
                                    <Row
                                        gutter={16}
                                        style={{ marginTop: "10px" }}
                                    >
                                        <Col span={6}>
                                            <label>Client Secret:</label>
                                        </Col>
                                        <Col span={18}>
                                            <Text>
                                                {user?.xero_details
                                                    ?.client_secret || "N/A"}
                                            </Text>
                                        </Col>
                                    </Row>
                                </TabPane>

                                <TabPane tab="Google Analytics" key="2">
                                    <Row gutter={16}>
                                        <Col span={6}>
                                            <label>
                                                Analytics Property Id:
                                            </label>
                                        </Col>
                                        <Col span={18}>
                                            <Text>
                                                {user?.jobadder_details
                                                    ?.analytics_view_id ||
                                                    "N/A"}
                                            </Text>
                                        </Col>
                                    </Row>
                                </TabPane>
                            </Tabs>
                        </Card>
                    </Panel>
                </Collapse>
            </div>
        </>
    );
}
