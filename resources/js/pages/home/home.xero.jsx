import React from "react";
import { NavLink } from "react-router-dom";

import { Card, Spin, Flex } from "antd";

import { useFetchXeroQuery } from "../forecast/forecast.service";
import ForecastHome from "../forecast/forecast.home";

const XeroContainer = () => {
    const { data: xeroData, isLoading } = useFetchXeroQuery();

    return isLoading ? (
        <Flex justify="center" align="center" style={{ marginTop: "1rem" }}>
            <Spin />
        </Flex>
    ) : xeroData?.connected ? (
        <ForecastHome xeroData={xeroData} />
    ) : (
        <Card.Meta
            description={
                <NavLink to="/financeforecast">Connect Xero Account</NavLink>
            }
        />
    );
};

export default XeroContainer;
