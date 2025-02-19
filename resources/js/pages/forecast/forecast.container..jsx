import { Card, Col, Row } from "antd";
import React from "react";

import "./styles.css";
import ForecastWatchlist from "./forecast.watchlist";
import ForecasInvoiceBillTable from "./forecast.table";
import ForecastChart from "./forecast.chart";
import ForecastCash from "./forecast.cash";

export default function ForecastContainer({ xeroData }) {
    const username = xeroData?.username;
    const accountWatchlist = xeroData?.account_watchlist || [];
    const balance = xeroData?.balance || [];
    let invoices_array = xeroData?.invoices_array || [];

    let bills_array = xeroData?.bills_array || [];

    let total_cash = xeroData?.total_cash || [];

    const invoiceDraftCount = xeroData?.data?.draft_count || "";
    const invoiceDraftAmount = xeroData?.data?.draft_amount;
    const invoiceAwCount = xeroData?.data?.aw_count || "";
    const invoiceAwAmount = xeroData?.data?.aw_amount.toFixed(2);
    const invoiceOdCount = xeroData?.data?.overdue_count || "";
    const invoiceOdAmount = xeroData?.data?.overdue_count.toFixed(2);

    const billDraftCount = xeroData?.my_data?.draft_count || "";
    const billDraftAmount = xeroData?.my_data?.draft_amount;
    const billAwCount = xeroData?.my_data?.aw_count || "";
    const billAwAmount = xeroData?.my_data?.aw_amount.toFixed(2);
    const billOdCount = xeroData?.my_data?.overdue_count || "";
    const billOdAmount = xeroData?.my_data?.overdue_amount.toFixed(2);

    let invoiceAndBillTable = [
        {
            name: "Invoice",
            draft: `${invoiceDraftCount} ( ${invoiceDraftAmount} )`,
            aw: `${invoiceAwCount} ( ${invoiceAwAmount} )`,
            od: `${invoiceOdCount} ( ${invoiceOdAmount} )`,
        },
        {
            name: "Bill",
            draft: `${billDraftCount} ( ${billDraftAmount} )`,
            aw: `${billAwCount} ( ${billAwAmount} )`,
            od: `${billOdCount} ( ${billOdAmount} )`,
        },
    ];

    return (
        <Card
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
                <Col xs={24} sm={24} md={24} lg={24} xl={12}>
                    <ForecastWatchlist accountWatchlist={accountWatchlist} />
                </Col>
                <Col xs={24} sm={24} md={24} lg={24} xl={12}>
                    <ForecasInvoiceBillTable data={invoiceAndBillTable} />
                </Col>
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
