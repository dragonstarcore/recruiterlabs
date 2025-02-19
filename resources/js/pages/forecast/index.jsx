import React, { useEffect } from "react";

import { Card, Alert, Spin, Flex } from "antd";

import {
    useFetchXeroQuery,
    useFetchXeroredirectMutation,
} from "./forecast.service";

import ForecastContainer from "./forecast.container.";

const XeroConnection = () => {
    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const authorizationCode = urlParams.get("code");
        if (authorizationCode) {
            getAccessToken(urlParams);
        }
    }, []);

    const getAccessToken = async (urlParams) => {
        try {
            const res = await fetch(`/api/xero/auth/callback?${urlParams}`, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            });
        } catch (error) {
            console.error("Failed to get access token", error);
        }
    };

    const { data: xeroData, isLoading } = useFetchXeroQuery();

    useEffect(() => {
        if (xeroData && !xeroData.connected) getXeroUrl();
    }, [xeroData]);

    const [fetchXeroredirect, {}] = useFetchXeroredirectMutation();

    const getXeroUrl = async () => {
        const { data } = await fetchXeroredirect();
        window.location.assign(data.url);
    };

    return isLoading ? (
        <Flex justify="center" align="center">
            <Spin />
        </Flex>
    ) : xeroData?.connected ? (
        <ForecastContainer xeroData={xeroData} />
    ) : (
        <Card.Meta
            description={
                <Alert
                    message="You have not connected any account yet, please connect your Xero account."
                    type="error"
                />
            }
        />
    );
};

export default XeroConnection;
