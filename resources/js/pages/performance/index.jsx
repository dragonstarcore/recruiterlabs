import React, { useEffect, useState } from "react";

import { Alert, Card, Flex, Spin } from "antd";
import { toast } from "react-toastify";
import PerformanceContainer from "./performance.container";

export default function Performance() {
    const [data, setData] = useState({});
    const [isLoading, setIsLoading] = useState(false);

    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const authorizationCode = urlParams.get("code");

        if (authorizationCode) {
            getAccessToken(authorizationCode);
        } else getAccessToken();
    }, []);

    const authorize = (uri) => {
        window.location.href = `${uri}`;
    };

    const getAccessToken = async (authorizationCode) => {
        try {
            setIsLoading(true);

            const res = await fetch(`/api/jobadder?code=${authorizationCode}`, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            });

            const data = await res.json();

            if (data.err === "redirect") authorize(data.uri);

            setIsLoading(false);
            setData(data.data);
        } catch (error) {
            toast.error("Failed to get access token");
            setIsLoading(false);
        }
    };

    return isLoading ? (
        <Flex justify="center" align="center" >
            <Spin />
        </Flex>
    ) : data?.name ? (
        <PerformanceContainer data={data} />
    ) : (
        <Card.Meta
            description={
                <Alert
                    message="You have not connected any account yet, please connect your Jobadder account."
                    type="error"
                />
            }
        />
    );
}
