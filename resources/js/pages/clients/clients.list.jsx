import React, { useState, useEffect } from "react";
import {
    Card,
    Row,
    Col,
    Table,
    Input,
    Image,
    Button,
    Switch,
    Avatar,
    Typography,
    Space,
    Spin,
    message,
    Flex,
    Alert,
    Popconfirm,
} from "antd";
import { useNavigate, Link } from "react-router-dom";
import {
    EyeOutlined,
    FileOutlined,
    SearchOutlined,
    FileAddOutlined,
    EditOutlined,
    DeleteOutlined,
    MailOutlined,
} from "@ant-design/icons";
import { useSelector, useDispatch } from "react-redux";
import { useFetchClientListQuery } from "./clients.service";

import { setClient } from "./clients.slice";
const MyClientPage = ({}) => {
    const dispatch = useDispatch();
    const navigate = useNavigate();

    const { data, isLoading, isSuccess } = useFetchClientListQuery(undefined, {
        refetchOnMountOrArgChange: true,
    });
    useEffect(() => {
        if (isSuccess) {
            dispatch(setClient(data?.users));
        }
    }, [isSuccess, data]);

    const clients = useSelector((apps) => apps.client.clients);

    if (isLoading) return <Spin />;
    return (
        <Row gutter={[16, 16]}>
            {clients.map((user) => (
                <Col xs={24} sm={12} xl={6} key={user.id}>
                    <Link to={`/employee_list/${user.id}`}>
                        <Card
                            hoverable
                            style={{
                                height: 268,
                                /* Non-hover state */
                                body: { background: "#fff" },
                                /* Hover state */
                                hoverable: {
                                    boxShadow:
                                        "0 8px 16px rgba(24, 144, 255, 0.2)",
                                    borderColor: "#1890ff",
                                    backgroundColor: "#f0faff",
                                },
                            }}
                            cover={
                                <div style={{ marginBottom: "10" }}>
                                    {user.user_details?.logo ? (
                                        <Image
                                            src={`/${user.user_details.logo}`}
                                            className="rounded-pill"
                                            width={"100%"}
                                            height={150}
                                            preview={false}
                                        />
                                    ) : (
                                        <Image
                                            src="/assets/images/nologo.jpg"
                                            width={"100%"}
                                            height={150}
                                            preview={false}
                                        />
                                    )}
                                </div>
                            }
                        >
                            <div className="card-body">
                                <Row>
                                    <Col span={20}>
                                        <div className="fw-semibold">
                                            <h4>{user.name}</h4>
                                        </div>
                                        <div className="fw-semibold">
                                            <h4>
                                                {
                                                    user.user_details
                                                        ?.company_name
                                                }
                                            </h4>
                                        </div>
                                        <div className="fw-semibold">
                                            <h4>
                                                {
                                                    user.user_details
                                                        ?.company_number
                                                }
                                            </h4>
                                        </div>
                                    </Col>
                                    <Col span={4}>
                                        <span className="fs-sm">
                                            <h4>{user.user_people?.length}</h4>
                                        </span>
                                    </Col>
                                </Row>
                            </div>
                        </Card>
                    </Link>
                </Col>
            ))}
        </Row>
    );
};

export default MyClientPage;
