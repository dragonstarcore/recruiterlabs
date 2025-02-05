import { useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { NavLink, useNavigate } from "react-router-dom";

import { Row, Col, Breadcrumb, Input, Dropdown, Space } from "antd";
import {
    LogoutOutlined,
    SearchOutlined,
    SettingOutlined,
    UserOutlined,
} from "@ant-design/icons";

import { logout, storeMe } from "./../app.slice";

import { useFetchMeQuery } from "./../pages/home/home.service";
import { toast } from "react-toastify";

function Header({ name, subName }) {
    const dispatch = useDispatch();
    const navigate = useNavigate();

    useEffect(() => window.scrollTo(0, 0));

    const userData = useSelector((apps) => apps.app.user);

    const { data: user, isError, isSuccess } = useFetchMeQuery();

    useEffect(() => {
        if (isSuccess) dispatch(storeMe(user));
    }, [isSuccess, user]);

    useEffect(() => {
        if (isError) {
            toast.error("Authentication failed!");
            dispatch(logout());
        }
    }, [isError]);

    const items = [
        {
            key: "1",
            label: (
                <Space onClick={() => navigate("/profile")}>
                    <SettingOutlined />
                    <span>Update Profile</span>
                </Space>
            ),
        },
        {
            key: "2",
            label: (
                <Space onClick={() => dispatch(logout())}>
                    <LogoutOutlined />
                    <span>Logout</span>
                </Space>
            ),
        },
    ];

    return (
        <>
            <Row gutter={[24, 0]}>
                <Col span={24} md={6}>
                    <Breadcrumb
                        items={[
                            { title: <NavLink to="/">Pages</NavLink> },
                            { title: name.replace("/", "") },
                        ]}
                    ></Breadcrumb>
                    <div className="ant-page-header-heading">
                        <span
                            className="ant-page-header-heading-title"
                            style={{ textTransform: "capitalize" }}
                        >
                            {subName.replace("/", "")}
                        </span>
                    </div>
                </Col>
                <Col span={24} md={18} className="header-control">
                    <Dropdown menu={{ items }} placement="bottomLeft">
                        <Space style={{ cursor: "pointer" }}>
                            <UserOutlined />
                            <span className="btn-sign-in">{userData.name}</span>
                        </Space>
                    </Dropdown>
                    <Input
                        className="header-search"
                        placeholder="Type here..."
                        prefix={<SearchOutlined />}
                    />
                </Col>
            </Row>
        </>
    );
}

export default Header;
