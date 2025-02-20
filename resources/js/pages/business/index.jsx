import React, { useEffect, useState } from "react";
import { useDispatch, useSelector } from "react-redux";
import {
    Card,
    Input,
    Form,
    Table,
    Typography,
    Button,
    Row,
    Spin,
    Col,
    Select,
    Image,
    Collapse,
    Tabs,
    Flex,
} from "antd";

import { EyeOutlined, SearchOutlined } from "@ant-design/icons";

import {
    useFetchBusinessQuery,
    useFetchDocumentQuery,
    useFetchBusinessSearchMutation,
} from "./business.service";

import { setBusiness, setDoc_types } from "./business.slice";

import pdfIcon from "../../../imgs/pdf.webp";
import docsIcon from "../../../imgs/docs.png";
import xlsxIcon from "../../../imgs/xlsx.png";

import "./style.css";
const { Text } = Typography;
const { Option } = Select;

export default function Business() {
    const dispatch = useDispatch();

    const [form] = Form.useForm();

    const [type_search, setType_search] = useState("");
    const [search_title, setSearch_title] = useState("");

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

    const OnChangeSelect = async (value) => {
        setType_search(value);
        try {
            const formValues = form.getFieldsValue();
            const jobadder = await fetchBusinessSearch(formValues).unwrap();
            dispatch(setBusiness(jobadder.user));
        } catch (err) {
            console.log(err);
        }
    };

    const OnChangeTitle = async (e) => {
        setSearch_title(e.target.value);
        try {
            const search = { search_doc: e.target.value };
            const jobadder = await fetchBusinessSearch(search).unwrap();
            dispatch(setBusiness(jobadder.user));
        } catch (err) {
            console.log(err);
        }
    };

    if (isLoading || isDocLoading)
        return (
            <div className="business-body">
                <Spin size="large" />
            </div>
        );

    return (
        <>
            <Card
                title="My Business Details"
                style={{
                    boxShadow: "0 4px 8px rgba(0, 0, 0, 0.1)", // Light shadow for depth
                    borderRadius: "10px", // Rounded corners for the card
                }}
            >
                <Row>
                    <Col span={6}>
                        <Row
                            gutter={[0, 20]}
                            style={{ marginTop: "20px", padding: "20px" }}
                        >
                            <Col span={24}>
                                {user?.user_details?.logo ? (
                                    <img
                                        src={`./${user?.user_details?.logo}`}
                                        alt="Business Logo"
                                        style={{
                                            width: "100%",
                                            height: "100%",
                                            borderRadius: "10px", // Rounded corners for the logo
                                            border: "2px solid #e0e0e0", // Light border around the logo
                                        }}
                                    />
                                ) : (
                                    <Text>No Logo Uploaded</Text>
                                )}
                            </Col>
                        </Row>
                    </Col>
                    <Col span={18}>
                        <Tabs
                            defaultActiveKey="1"
                            items={[
                                {
                                    key: "1",
                                    label: "Company Details",
                                    children: (
                                        <Row
                                            gutter={[10, 20]}
                                            style={{
                                                borderRadius: "8px",
                                            }}
                                        >
                                            <Col span={6}>
                                                <Text>Company Name:</Text>
                                            </Col>
                                            <Col span={18}>
                                                <Text strong>
                                                    {
                                                        user?.user_details
                                                            ?.company_name
                                                    }
                                                </Text>
                                            </Col>
                                            <Col span={6}>
                                                <Text>Company Number:</Text>
                                            </Col>
                                            <Col span={18}>
                                                <Text strong>
                                                    {
                                                        user?.user_details
                                                            ?.company_number
                                                    }
                                                </Text>
                                            </Col>
                                            <Col span={6}>
                                                <Text>Registered Address:</Text>
                                            </Col>
                                            <Col span={18}>
                                                <Text strong>
                                                    {
                                                        user?.user_details
                                                            ?.registered_address
                                                    }
                                                </Text>
                                            </Col>
                                            <Col span={6}>
                                                <Text>VAT Number:</Text>
                                            </Col>
                                            <Col span={18}>
                                                <Text strong>
                                                    {
                                                        user?.user_details
                                                            ?.vat_number
                                                    }
                                                </Text>
                                            </Col>
                                            <Col span={6}>
                                                <Text>
                                                    Authentication Code:
                                                </Text>
                                            </Col>
                                            <Col span={18}>
                                                <Text strong>
                                                    {
                                                        user?.user_details
                                                            ?.authentication_code
                                                    }
                                                </Text>
                                            </Col>
                                            <Col span={6}>
                                                <Text>Company UTR:</Text>
                                            </Col>
                                            <Col span={18}>
                                                <Text strong>
                                                    {
                                                        user?.user_details
                                                            ?.company_utr
                                                    }
                                                </Text>
                                            </Col>
                                        </Row>
                                    ),
                                },
                                {
                                    key: 2,
                                    label: "Bank Details",
                                    children: (
                                        <Row
                                            gutter={[10, 20]}
                                            style={{
                                                borderRadius: "8px",
                                                marginTop: "20px",
                                            }}
                                        >
                                            <Col span={6}>
                                                <Text>Bank Name:</Text>
                                            </Col>
                                            <Col span={18}>
                                                <Text strong>
                                                    {
                                                        user?.user_details
                                                            ?.bank_name
                                                    }
                                                </Text>
                                            </Col>
                                            <Col span={6}>
                                                <Text>Sort Code:</Text>
                                            </Col>
                                            <Col span={18}>
                                                <Text strong>
                                                    {
                                                        user?.user_details
                                                            ?.sort_code
                                                    }
                                                </Text>
                                            </Col>
                                            <Col span={6}>
                                                <Text>Account Number:</Text>
                                            </Col>
                                            <Col span={18}>
                                                <Text strong>
                                                    {
                                                        user?.user_details
                                                            ?.account_number
                                                    }
                                                </Text>
                                            </Col>
                                            <Col span={6}>
                                                <Text>IBAN:</Text>
                                            </Col>
                                            <Col span={18}>
                                                <Text strong>
                                                    {user?.user_details?.iban}
                                                </Text>
                                            </Col>
                                            <Col span={6}>
                                                <Text>Swift Code:</Text>
                                            </Col>
                                            <Col span={18}>
                                                <Text strong>
                                                    {
                                                        user?.user_details
                                                            ?.swift_code
                                                    }
                                                </Text>
                                            </Col>
                                        </Row>
                                    ),
                                },
                            ]}
                        ></Tabs>
                    </Col>
                </Row>

                {/* Company Details Section */}

                {/* Bank Details Section */}

                {/* Logo Section */}

                {/* Company Documents Section */}
                <Row gutter={16} style={{ marginTop: "20px", padding: "1rem" }}>
                    <Col span={6}>
                        <h3>Company Documents Filter:</h3>
                    </Col>
                    <Col span={6}>
                        <Form form={form}>
                            <Form.Item name="type_search">
                                <Select
                                    size="large"
                                    placeholder="Filter By Document Type"
                                    allowClear
                                    onChange={(value) => OnChangeSelect(value)}
                                >
                                    <Option key="" value="">
                                        All
                                    </Option>
                                    {documents.map((doc) => (
                                        <Option key={doc.id} value={doc.id}>
                                            {doc.title}
                                        </Option>
                                    ))}
                                </Select>
                            </Form.Item>
                        </Form>
                    </Col>
                    <Col span={10}>
                        <Form form={form}>
                            <Form.Item name="title_search">
                                <Input
                                    value={search_title}
                                    onChange={(e) => OnChangeTitle(e)}
                                    placeholder="Search..."
                                    addonBefore={<SearchOutlined />}
                                />
                            </Form.Item>
                        </Form>
                    </Col>
                </Row>

                {!isJobadderLoading ? (
                    <Table
                        className="employee_list"
                        bordered
                        rowClassName="table-row-hover"
                        rowKey="id"
                        columns={[
                            {
                                title: "#",
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
                                            image = pdfIcon;
                                        } else if (
                                            ["doc", "docx"].includes(extension)
                                        ) {
                                            docsIcon;
                                        } else if (
                                            ["xls", "xlsx"].includes(extension)
                                        ) {
                                            xlsxIcon;
                                        } else {
                                            image = `/public/${file}`;
                                        }
                                    } else {
                                        image =
                                            "/assets/images/default_user.jpg";
                                    }
                                    return (
                                        <Image
                                            src={pdfIcon}
                                            width={48}
                                            height={48}
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
                                sorter: (a, b) =>
                                    a.title.localeCompare(b.title),

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
                                    if (type_id == "4")
                                        return "Marketing & brand";
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
                                            href={`./${file}`}
                                            target="_blank"
                                            icon={<EyeOutlined />}
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
                ) : (
                    <Flex justify="center">
                        <Spin size="large" />
                    </Flex>
                )}
            </Card>
            <div style={{ marginTop: "10px" }}>
                <Collapse
                    defaultActiveKey={["1"]}
                    items={[
                        {
                            key: 1,
                            label: "API Details",
                            children: (
                                <Card>
                                    <Tabs
                                        defaultActiveKey="1"
                                        items={[
                                            {
                                                key: "1",
                                                label: "Xero",
                                                children: (
                                                    <>
                                                        <Row gutter={16}>
                                                            <Col span={6}>
                                                                <label>
                                                                    Client Id:
                                                                </label>
                                                            </Col>
                                                            <Col span={18}>
                                                                <Text>
                                                                    {user
                                                                        ?.xero_details
                                                                        ?.client_id ||
                                                                        "N/A"}
                                                                </Text>
                                                            </Col>
                                                        </Row>
                                                        <Row
                                                            gutter={16}
                                                            style={{
                                                                marginTop:
                                                                    "10px",
                                                            }}
                                                        >
                                                            <Col span={6}>
                                                                <label>
                                                                    Client
                                                                    Secret:
                                                                </label>
                                                            </Col>
                                                            <Col span={18}>
                                                                <Text>
                                                                    {user
                                                                        ?.xero_details
                                                                        ?.client_secret ||
                                                                        "N/A"}
                                                                </Text>
                                                            </Col>
                                                        </Row>
                                                    </>
                                                ),
                                            },
                                            {
                                                key: "2",
                                                label: "Google Analytics",
                                                children: (
                                                    <Row gutter={16}>
                                                        <Col span={6}>
                                                            <label>
                                                                Analytics
                                                                Property Id:
                                                            </label>
                                                        </Col>
                                                        <Col span={18}>
                                                            <Text>
                                                                {user
                                                                    ?.jobadder_details
                                                                    ?.analytics_view_id ||
                                                                    "N/A"}
                                                            </Text>
                                                        </Col>
                                                    </Row>
                                                ),
                                            },
                                        ]}
                                    ></Tabs>
                                </Card>
                            ),
                        },
                    ]}
                ></Collapse>
            </div>
        </>
    );
}
