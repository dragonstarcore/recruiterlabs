import React from "react";
import { useSelector } from "react-redux";
import { Card, Typography } from "antd";

import JobContainer from "./home.job";
import HomeGoogle from "./home.google";
import XeroContainer from "./home.xero";
import HomeApps from "./home.apps";

const { Paragraph } = Typography;

const Dashboard = () => {
    const userData = useSelector((apps) => apps.app.user);

    if (userData.id === null) {
        return <></>;
    }

    if (userData.role_type === 1)
        return (
            <Card title="Dashboard" bordered={false}>
                <Typography>
                    <Paragraph>No data</Paragraph>
                </Typography>
            </Card>
        );

    return (
        <div className="dash">
            <JobContainer />
            <HomeGoogle />
            <XeroContainer />
            <HomeApps />
        </div>
    );
};

export default Dashboard;
