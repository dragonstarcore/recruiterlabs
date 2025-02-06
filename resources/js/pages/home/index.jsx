import React, { useState } from 'react';
import { Card, Row,Image, Col, Button, Select, Input, Alert, Typography, DatePicker, Spin } from 'antd';
import { useEffect } from 'react';
import moment from 'moment';
import { useDispatch, useSelector } from "react-redux";
import { NavLink, useNavigate } from "react-router-dom";
import { useFetchDataQuery } from "./home.service";
import './style.css'
const { Option } = Select;
const { Title, Paragraph } = Typography;
const apps = [
    { link: 'https://www.xero.com/', imgSrc: 'xero.jpg' },
    { link: 'https://jobadder.com/', imgSrc: 'jobadder.jpg' },
    { link: 'https://www.linkedin.com/', imgSrc: 'linkedin.jpg' },
    { link: 'https://www.microsoft.com/', imgSrc: 'msoffice.jpg' },
    { link: 'https://www.dropbox.com/', imgSrc: 'dropbox.jpg' },
    { link: 'https://login.microsoftonline.com/', imgSrc: 'sharepoint.jpg' },
    { link: 'https://calendly.com/', imgSrc: 'calendly.jpg' },
    { link: 'https://www.vincere.io/', imgSrc: 'vincere.jpg' },
    { link: 'https://www.sourcebreaker.com/', imgSrc: 'sourcebreaker.jpg' },
    { link: 'https://www.sonovate.com/', imgSrc: 'sonovate.jpg' },
    { link: 'https://www.lastpass.com/', imgSrc: 'lastpass.jpg' },
  ];
const Dashboard = () => {
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const { dashboardData, isError, isSuccess ,isLoading} = useFetchDataQuery();
   

    const userData = useSelector((apps) => apps.app.user);
    console.log(userData)
    const [jobadder, setJobadder] = useState({
        fullname: "",
        account_email: "",
        jobs: 50,
        contacts: 200,
        interviews: 30,
        candidates: 150,
        jobs_graph: true,
    });
    const [xero, setXero] = useState({});
    const [pageViews, setPageViews] = useState(null);
    const [totalVisitors, setTotalVisitors] = useState(null);
    const [GAError, setGAError] = useState(null);
    const [dateOption, setDateOption] = useState('');
    const [startDate, setStartDate] = useState(null);
    const [endDate, setEndDate] = useState(null);
    const [loading, setLoading] = useState(false);
     
    useEffect(()=>{
        if(!dashboardData)
            return;
        const {jobadder,page_views,total_visitors,GA_error,xero}=dashboardData
        setJobadder(jobadder)
        setTotalVisitors(total_visitors)
        setPageViews(page_views)
        setGAError(GA_error)
        setXero(xero)
    },[dashboardData])
    // Simulating fetching data
    useEffect(() => {
        // Simulate fetching data
         
        setXero({
            organisationName: "",
            username: "",
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
        setPageViews(0);
        setTotalVisitors(0);
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

    if (isLoading) {
        return <Spin size="large" />;
    }
    if(userData.role_type==2)
        return (
            <div className="content">
                {/* Jobadder Section */}
                <Card title="Jobadder">
                {jobadder.fullname ? (
                    <>
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
                    </>

                         
                ):(
                    
                        <Card.Meta
                             
                            description={
                                <Alert
                                message="You have not connected any account yet, please connect your Jobadder account."
                                type="error"
                              />
                            }
                        />
                )}
                  </Card>

                {/* Google Analytics Section */}
                <Card  style={{marginTop:10}} title="Google Analytics Data">
                    <Card.Meta
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
                                GAError?(
                                    <Alert message={GAError} type="error" />
                                ):
                                <Alert message="You have not connected any account yet, please connect your Google Analytics account." type="error" />
                            )
                        }
                    />
                </Card>
                {/* Xero Section */}
                <Card style={{marginTop:10}}  title="Xero Data">
                    <Card.Meta
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
                <Card title="My Apps" style={{ marginTop: 20 }} className='app_card_body'>
                    <Row gutter={[16, 16]}>
                        {apps.map((app, index) => (
                        <Col key={index}   className='app_card'>
                            <a href={app.link} target="_blank" rel="noopener noreferrer">
                            <div className='card-img-body'  >
                                <div className="card-img-actions mx-1 mt-1">
                                <Image
                                    className="card-img img-fluid"
                                    src={`./assets/images/${app.imgSrc}`}
                                    alt=""
                                    preview={false}
                                />
                                </div>
                            </div>
                            </a>
                        </Col>
                        ))}
                    </Row>
                </Card>
            </div>

        );
    return (
        <Card title="Dashboard" bordered={false}>
        <Typography>
            <Paragraph>No data</Paragraph>
        </Typography>
        </Card>

    );    
};

export default Dashboard;
