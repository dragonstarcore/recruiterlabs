import { useEffect } from "react";
import { useSelector } from "react-redux";
import { Card, Button, Avatar, Input, Form } from "antd";
import { toast } from "react-toastify";
import { UserOutlined } from "@ant-design/icons";

import { useEditProfileMutation } from "./profile.service";

import BgProfile from "./../../../imgs/bg-profile.jpg";

function Profile() {
    const [form] = Form.useForm();

    const userData = useSelector((apps) => apps.app.user);

    useEffect(() => {
        form.setFieldsValue({
            name: userData.name,
            email: userData.email,
        });
    }, [userData, form]);

    const [editProfile, { isLoading, isSuccess, error }] =
        useEditProfileMutation();

    const onFinish = async (values) => {
        try {
            const user = await editProfile(values).unwrap();
        } catch (err) {
            console.log(err);
        }
    };

    useEffect(() => {
        if (isSuccess) {
            toast.success("Profile edit successful", {
                position: "top-right",
            });
        }

        if (error) toast.error(`Profile edit failed`);
    }, [isSuccess, error]);

    return (
        <>
            <div
                className="profile-nav-bg"
                style={{
                    backgroundImage: "url(" + BgProfile + ")",
                    height: "30%",
                }}
            ></div>

            <Card
                className="card-profile-head"
                style={{ padding: "0.5rem" }}
                title={
                    <Avatar.Group>
                        <Avatar size={74} icon={<UserOutlined />} />

                        <div className="avatar-info">
                            <h4 className="font-semibold m-0">
                                {userData.name}
                            </h4>
                        </div>
                    </Avatar.Group>
                }
            >
                <Form
                    form={form}
                    onFinish={onFinish}
                    labelCol={{ span: 4 }}
                    wrapperCol={{ span: 20 }}
                    style={{ maxWidth: 800, marginTop: "2rem" }}
                    className="row-col"
                >
                    <Form.Item
                        className="name"
                        label="Name"
                        name="name"
                        rules={[
                            {
                                required: true,
                                message: "Please input your name!",
                            },
                        ]}
                    >
                        <Input size="large" />
                    </Form.Item>

                    <Form.Item
                        className="email"
                        label="Email"
                        name="email"
                        rules={[
                            {
                                required: true,
                                message: "Please input your email!",
                            },
                        ]}
                    >
                        <Input size="large" />
                    </Form.Item>

                    <Form.Item
                        className="password"
                        label="Password"
                        name="password"
                    >
                        <Input.Password size="small" />
                    </Form.Item>

                    <Form.Item
                        className="password"
                        label="Confirm"
                        name="password_confirmation"
                        rules={[
                            ({ getFieldValue }) => ({
                                validator(_, value) {
                                    if (
                                        !value ||
                                        getFieldValue("password") === value
                                    ) {
                                        return Promise.resolve();
                                    }
                                    return Promise.reject(
                                        new Error(
                                            "The new password that you entered do not match!"
                                        )
                                    );
                                },
                            }),
                        ]}
                    >
                        <Input.Password size="small" />
                    </Form.Item>

                    <Form.Item label={null}>
                        <Button
                            type="primary"
                            htmlType="submit"
                            style={{ width: "100%" }}
                            loading={isLoading}
                        >
                            Submit
                        </Button>
                    </Form.Item>
                </Form>
            </Card>
        </>
    );
}

export default Profile;
