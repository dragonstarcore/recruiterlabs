import React, { useEffect } from "react";

import { Card, Flex, Spin } from "antd";
import { toast } from "react-toastify";

import PerformanceHomeContainer from "../performance/performance.home";
import { useFetchJobAdderDataQuery } from "../performance/performance.service";
import { NavLink } from "react-router-dom";

export default function Performance() {
    const { data, error, isLoading } = useFetchJobAdderDataQuery();

    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const code = urlParams.get("code");

        if (code) {
            setAuthorizationCode(code);
            urlParams.delete("code");
            window.history.replaceState(
                {},
                document.title,
                window.location.pathname
            );
        }
    }, []);

    useEffect(() => {
        if (error) toast.error("Failed to get access token");
    }, [error]);

    return isLoading ? (
        <Flex justify="center" align="center">
            <Spin />
        </Flex>
    ) : data?.ok ? (
        <PerformanceHomeContainer data={data.data} />
    ) : (
        <Card.Meta
            description={
                <NavLink to="/performance">Connect Jobadder Account</NavLink>
            }
        />
    );
}
