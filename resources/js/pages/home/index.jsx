import React, { useState } from 'react';
import { Card, Row, Col, Button, Select, Input, Alert, Typography, DatePicker, Spin } from 'antd';
import { useEffect } from 'react';
import moment from 'moment';
import { useDispatch, useSelector } from "react-redux";
import { NavLink, useNavigate } from "react-router-dom";
import { useFetchDataQuery } from "./home.service";

const { Option } = Select;
const { Title, Paragraph } = Typography;

const Dashboard = () => {
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const { data: user, isError, isSuccess } = useFetchDataQuery();
    const userData = useSelector((apps) => apps.app.user);
    console.log(userData)
    const [jobadder, setJobadder] = useState({});
    const [xero, setXero] = useState({});
    const [pageViews, setPageViews] = useState(null);
    const [totalVisitors, setTotalVisitors] = useState(null);
    const [GAError, setGAError] = useState(null);
    const [dateOption, setDateOption] = useState('');
    const [startDate, setStartDate] = useState(null);
    const [endDate, setEndDate] = useState(null);
    const [loading, setLoading] = useState(false);
    const [apps, setApps] = useState([
        { name: "Jobadder", connected: true },
        { name: "Google Analytics", connected: false },
        { name: "Xero", connected: true },
    ]);

    // Simulating fetching data
    useEffect(() => {
        // Simulate fetching data
        setJobadder({
            fullname: "John Doe",
            account_email: "john.doe@example.com",
            jobs: 50,
            contacts: 200,
            interviews: 30,
            candidates: 150,
            jobs_graph: true,
        });
        setXero({
            organisationName: "Test Org",
            username: "admin",
            balance: [null, 5000],
            data: {
                draft_count: 5,
                draft_amount: 1000,
                aw_count: 3,
                aw_amount: 2000,
                overdue_count: 1,
                overdue_amount: 500,
            }
        });
        setPageViews(1200);
        setTotalVisitors(300);
    }, []);

    const handleDateChange = (value) => {
        setDateOption(value);
        if (value === 'custom') {
            setStartDate(null);
            setEndDate(null);
        }
    };
    const handleConnect = (appName) => {
        // Handle connection logic here
        setLoading(true);
        setTimeout(() => {
            setLoading(false);
            setApps(apps.map(app => app.name === appName ? { ...app, connected: true } : app));
        }, 1000);  // Simulate API call
    };
    const handleFilter = () => {
        // Handle filter logic (could make API request here)
        console.log('Filter applied');
    };

    const handleStartDateChange = (date, dateString) => {
        setStartDate(dateString);
    };

    const handleEndDateChange = (date, dateString) => {
        setEndDate(dateString);
    };

    if (loading) {
        return <Spin size="large" />;
    }
    if(userData.role_type==2)
        return (
            <div className="content">
                {/* Jobadder Section */}
                {jobadder.fullname && (
                    <Card>
                        <Card.Meta
                            title="Jobadder"
                            description={
                                <>
                                    <p>{jobadder.fullname} - {jobadder.account_email}</p>
                                    <Row gutter={16}>
                                        <Col span={6}>
                                            <Card>
                                                <h3>{jobadder.jobs}</h3>
                                                Total Jobs
                                            </Card>
                                        </Col>
                                        <Col span={6}>
                                            <Card>
                                                <h3>{jobadder.contacts}</h3>
                                                Total Contacts
                                            </Card>
                                        </Col>
                                        <Col span={6}>
                                            <Card>
                                                <h3>{jobadder.interviews}</h3>
                                                Total Interviews
                                            </Card>
                                        </Col>
                                        <Col span={6}>
                                            <Card>
                                                <h3>{jobadder.candidates}</h3>
                                                Total Candidates
                                            </Card>
                                        </Col>
                                    </Row>
                                    <Row gutter={16}>
                                        <Col span={12}>
                                            <Card>
                                                {/* Graph for Jobs (can replace with your chart component) */}
                                                <div>Jobs Graph</div>
                                            </Card>
                                        </Col>
                                        <Col span={12}>
                                            <Card>
                                                {/* Graph for Contacts */}
                                                <div>Contacts Graph</div>
                                            </Card>
                                        </Col>
                                    </Row>
                                </>
                            }
                        />
                        <Row gutter={16}>
                            <Col span={8}>
                                <Select
                                    value={dateOption}
                                    onChange={handleDateChange}
                                    placeholder="Filter By"
                                    style={{ width: '100%' }}
                                >
                                    <Option value="year">This Year</Option>
                                    <Option value="month">This Month</Option>
                                    <Option value="week">This Week</Option>
                                    <Option value="custom">Custom</Option>
                                </Select>
                            </Col>
                            {dateOption === 'custom' && (
                                <>
                                    <Col span={8}>
                                        <DatePicker
                                            value={startDate ? moment(startDate) : null}
                                            onChange={handleStartDateChange}
                                            style={{ width: '100%' }}
                                            placeholder="Start Date"
                                        />
                                    </Col>
                                    <Col span={8}>
                                        <DatePicker
                                            value={endDate ? moment(endDate) : null}
                                            onChange={handleEndDateChange}
                                            style={{ width: '100%' }}
                                            placeholder="End Date"
                                        />
                                    </Col>
                                </>
                            )}
                        </Row>
                        {dateOption === 'custom' && (
                            <Button type="primary" onClick={handleFilter}>
                                Filter
                            </Button>
                        )}
                    </Card>
                )}
                {/* Google Analytics Section */}
                <Card>
                    <Card.Meta
                        title="Google Analytics Data"
                        description={
                            pageViews ? (
                                <Row gutter={16}>
                                    <Col span={12}>
                                        <h6>Page Views</h6>
                                        <p>{pageViews}</p>
                                    </Col>
                                    <Col span={12}>
                                        <h6>Unique Users</h6>
                                        <p>{totalVisitors}</p>
                                    </Col>
                                </Row>
                            ) : (
                                <Alert message="You have not connected any account yet, please connect your Google Analytics account." type="error" />
                            )
                        }
                    />
                </Card>
                {/* Xero Section */}
                <Card>
                    <Card.Meta
                        title="Xero Data"
                        description={
                            xero.organisationName ? (
                                <>
                                    <Paragraph>Organisation: {xero.organisationName}</Paragraph>
                                    <Row gutter={16}>
                                        <Col span={12}>
                                            <Card>
                                                <h6>Invoices owed to you</h6>
                                                <p>{xero.data.draft_count} Draft invoices: {xero.data.draft_amount}</p>
                                                <p>{xero.data.aw_count} Awaiting payment: {xero.data.aw_amount}</p>
                                                <p>{xero.data.overdue_count} Overdue: {xero.data.overdue_amount}</p>
                                            </Card>
                                        </Col>
                                        <Col span={12}>
                                            <Card>
                                                <h6>Balance in Xero</h6>
                                                <p>{xero.balance[1] || 'No data'}</p>
                                            </Card>
                                        </Col>
                                    </Row>
                                </>
                            ) : (
                                <Alert message="You have not connected any account yet, please connect your Xero account." type="error" />
                            )
                        }
                    />
                </Card>
                <Card>
                    <Title level={3}>My Apps</Title>
                    <Row gutter={16}>
                        {apps.map((app) => (
                            <Col span={8} key={app.name}>
                                <Card
                                    title={app.name}
                                    extra={app.connected ? <span style={{ color: 'green' }}>Connected</span> : <span style={{ color: 'red' }}>Not Connected</span>}
                                    bordered={false}
                                    style={{ width: '100%' }}
                                >
                                    {app.connected ? (
                                        <Button type="primary" disabled>
                                            Connected
                                        </Button>
                                    ) : (
                                        <Button
                                            type="primary"
                                            onClick={() => handleConnect(app.name)}
                                            loading={loading && app.name === "Jobadder"}
                                        >
                                            Connect
                                        </Button>
                                    )}
                                </Card>
                            </Col>
                        ))}
                    </Row>
                    {!apps.some(app => app.connected) && (
                        <Alert
                            message="You have not connected any apps yet. Please connect at least one."
                            type="warning"
                            showIcon
                            style={{ marginTop: 16 }}
                        />
                    )}
                </Card>
            </div>

        );
        return (
            <Card title="Dashboard" bordered={false}>
            <Typography>
                <Paragraph>No data</Paragraph>
                {/* If you need to add a link to another page */}
                {/* <Link to="/dates_data" target="_blank">
                data
                </Link> */}
                {/* If you want a button instead of a link */}
                <Button type="link" href="/dates_data" target="_blank">
                data
                </Button>
            </Typography>
            </Card>

        );    
};

export default Dashboard;
