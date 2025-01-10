import React, { useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { useDispatch } from "react-redux";
import {
    Layout,
    Button,
    Row,
    Col,
    Typography,
    Form,
    Input,
    message,
} from "antd";
import { toast } from "react-toastify";

import signinbg from "./../../../imgs/2.webp";

import { login } from "./../../app.slice";
import { useLoginUserMutation } from "./login.service";

const { Title } = Typography;
const { Header, Content } = Layout;

export default function Login() {
    const dispatch = useDispatch();
    const navigate = useNavigate();

    const [loginUser, { isLoading, isSuccess, error }] = useLoginUserMutation();

    const onFinish = async (values) => {
        try {
            const user = await loginUser(values).unwrap();

            dispatch(login(user));
        } catch (err) {
            console.log(err);
        }
    };

    useEffect(() => {
        if (isSuccess) {
            navigate("/home");
            toast.success("Login successful");
        }

        if (error) toast.error(`Login failed`);
    }, [isSuccess, error]);

    return (
        <>
            <Layout className="layout-default layout-signin">
                <Header>
                    <div className="header-col header-brand">
                        <h5>RECRUITER LABS</h5>
                    </div>
                </Header>
                <Content className="signin">
                    <Row gutter={[24, 0]} justify="space-around">
                        <Col
                            xs={{ span: 24, offset: 0 }}
                            lg={{ span: 6, offset: 2 }}
                            md={{ span: 12 }}
                        >
                            <Title className="mb-15">Sign In</Title>
                            <Title
                                className="font-regular text-muted"
                                level={5}
                            >
                                Enter your email and password to sign in
                            </Title>
                            <Form
                                onFinish={onFinish}
                                layout="vertical"
                                className="row-col"
                            >
                                <Form.Item
                                    className="username"
                                    label="Email"
                                    name="email"
                                    rules={[
                                        {
                                            required: true,
                                            message: "Please input your email!",
                                        },
                                    ]}
                                >
                                    <Input placeholder="Email" />
                                </Form.Item>

                                <Form.Item
                                    className="username"
                                    label="Password"
                                    name="password"
                                    rules={[
                                        {
                                            required: true,
                                            message:
                                                "Please input your password!",
                                        },
                                    ]}
                                >
                                    <Input.Password placeholder="Password" />
                                </Form.Item>

                                <Form.Item>
                                    <Button
                                        type="primary"
                                        htmlType="submit"
                                        style={{ width: "100%" }}
                                        loading={isLoading}
                                    >
                                        SIGN IN
                                    </Button>
                                </Form.Item>
                            </Form>
                        </Col>
                        <Col
                            className="sign-img"
                            style={{ padding: 12 }}
                            xs={{ span: 24 }}
                            lg={{ span: 12 }}
                            md={{ span: 12 }}
                        >
                            <img src={signinbg} alt="" />
                        </Col>
                    </Row>
                </Content>
            </Layout>
        </>
    );
}
