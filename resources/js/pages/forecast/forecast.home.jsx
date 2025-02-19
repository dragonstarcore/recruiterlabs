import { Card, Col, Row } from "antd";
import React from "react";

import "./styles.css";
import ForecastChart from "./forecast.chart";
import ForecastCash from "./forecast.cash";

export default function ForecastHome({ xeroData }) {
    const username = xeroData?.username;
    const balance = xeroData?.balance || [];
    let invoices_array = xeroData?.invoices_array || [];

    let bills_array = xeroData?.bills_array || [];

    let total_cash = xeroData?.total_cash || [];

    return (
        <Card
            style={{ marginTop: "1rem" }}
            title={
                <div className="forecast-header">
                    <div className="forecast-header-title">
                        <h2>Xero</h2>
                        <span>{username}</span>
                        <span>
                            Balance in Xero:{" "}
                            {balance && balance[1] ? balance[1] : "N/A"}
                        </span>
                    </div>
                </div>
            }
        >
            <Row gutter={[32, 32]}>
                <Col xs={24} sm={24} md={24} lg={24} xl={24}>
                    <ForecastChart
                        invoices_array={invoices_array}
                        bills_array={bills_array}
                    />
                </Col>
                <Col xs={24} sm={24} md={24} lg={24} xl={24}>
                    <ForecastCash data={total_cash} />
                </Col>
            </Row>
        </Card>
    );
}
