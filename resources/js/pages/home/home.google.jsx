import React, { useState, useEffect } from "react";
import { Card, Form, Row, Image, Col, Alert, Typography, Spin } from "antd";
const GoogleAnalytics = ({ pageViews, GAError, totalVisitors }) => {
    return (
        <Card
            style={{ marginTop: 10 }}
            title="Google Analytics Data"
            headStyle={{
                fontSize: "20px",
                fontWeight: "bold",
            }}
        >
            <Card.Meta
                description={
                    pageViews ? (
                        <Row gutter={16}>
                            <Col span={12}>
                                <div className="analytics-info">Page Views</div>
                                <h3>{pageViews}</h3>
                            </Col>
                            <Col span={12}>
                                <div className="analytics-info">
                                    Unique Users
                                </div>
                                <h3>{totalVisitors}</h3>
                            </Col>
                        </Row>
                    ) : GAError ? (
                        <Alert message={GAError} type="error" />
                    ) : (
                        <Alert
                            message="You have not connected any account yet, please connect your Google Analytics account."
                            type="error"
                        />
                    )
                }
            />
        </Card>
    );
};
export default GoogleAnalytics;
