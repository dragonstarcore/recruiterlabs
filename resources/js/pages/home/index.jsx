import React, { useState, useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { NavLink, useNavigate } from "react-router-dom";
import ApexCharts from "react-apexcharts";
import { toast } from "react-toastify";
import {
    Card,
    Form,
    Row,
    Image,
    Col,
    Button,
    Select,
    Input,
    DatePicker,
    Alert,
    Typography,
    Spin,
} from "antd";
import moment from "moment";
import Icon, {
    ProjectFilled,
    ContactsFilled,
    WechatWorkFilled,
    TeamOutlined,
} from "@ant-design/icons";

import {
    useFetchDataQuery,
    useFetchJobadderDataMutation,
} from "./home.service";
import { setJobadder, setDashboard } from "./home.slice";

import "./style.css";

const { Option } = Select;
const { Title, Paragraph } = Typography;

const Dashboard = () => {
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const ChartContainer = ({
        chartData,
        title,
        chartType,
        chartId,
        color,
    }) => {
        const [options, setOptions] = useState({
            chart: {
                type: chartType,
                zoom: { enabled: true },
            },
            colors: [color],
            title: {
                text: title,
            },
            xaxis: {
                categories: chartData.names,
            },
            tooltip: {
                stickOnContact: true,
            },
            legend: {
                enabled: false,
            },
        });

        const [series, setSeries] = useState([
            {
                name: "Count",
                data: chartData.values,
            },
        ]);

        return (
            <ApexCharts
                options={options}
                series={series}
                type={chartType}
                height={350}
            />
        );
    };
    const profile = [
        <svg
            width="22"
            height="22"
            viewBox="0 0 20 20"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            key={0}
        >
            <path
                d="M9 6C9 7.65685 7.65685 9 6 9C4.34315 9 3 7.65685 3 6C3 4.34315 4.34315 3 6 3C7.65685 3 9 4.34315 9 6Z"
                fill="#fff"
            ></path>
            <path
                d="M17 6C17 7.65685 15.6569 9 14 9C12.3431 9 11 7.65685 11 6C11 4.34315 12.3431 3 14 3C15.6569 3 17 4.34315 17 6Z"
                fill="#fff"
            ></path>
            <path
                d="M12.9291 17C12.9758 16.6734 13 16.3395 13 16C13 14.3648 12.4393 12.8606 11.4998 11.6691C12.2352 11.2435 13.0892 11 14 11C16.7614 11 19 13.2386 19 16V17H12.9291Z"
                fill="#fff"
            ></path>
            <path
                d="M6 11C8.76142 11 11 13.2386 11 16V17H1V16C1 13.2386 3.23858 11 6 11Z"
                fill="#fff"
            ></path>
        </svg>,
    ];
    const apps = [
        { link: "https://www.xero.com/", imgSrc: "xero.jpg" },
        { link: "https://jobadder.com/", imgSrc: "jobadder.jpg" },
        { link: "https://www.linkedin.com/", imgSrc: "linkedin.jpg" },
        { link: "https://www.microsoft.com/", imgSrc: "msoffice.jpg" },
        { link: "https://www.dropbox.com/", imgSrc: "dropbox.jpg" },
        {
            link: "https://login.microsoftonline.com/",
            imgSrc: "sharepoint.jpg",
        },
        { link: "https://calendly.com/", imgSrc: "calendly.jpg" },
        { link: "https://www.vincere.io/", imgSrc: "vincere.jpg" },
        { link: "https://www.sourcebreaker.com/", imgSrc: "sourcebreaker.jpg" },
        { link: "https://www.sonovate.com/", imgSrc: "sonovate.jpg" },
        { link: "https://www.lastpass.com/", imgSrc: "lastpass.jpg" },
    ];
    const formatData = (data) => {
        if (!data) return;
        const names = [];
        const values = [];

        // Ensure data exists and contains 'name' and 'y'
        data.forEach((item) => {
            if (item && item.name && item.y !== undefined) {
                names.push(item.name);
                values.push(item.y);
            }
        });

        return { names, values };
    };
    const { data, isError, isSuccess, isLoading } = useFetchDataQuery();

    useEffect(() => {
        console.log(data);
        if (isSuccess) {
            //dispatch(setDashboard(data));
        }
    }, [isSuccess, data]);
    const [
        fetchJobadderData,
        { isLoading: isJobadderLoading, isSuccess: isJobadderSuccess, error },
    ] = useFetchJobadderDataMutation();

    const [form] = Form.useForm();
    const handleSubmit = async () => {
        try {
            const formValues = form.getFieldsValue();
            const jobadder = await fetchJobadderData(formValues).unwrap();
            dispatch(setJobadder(jobadder));
        } catch (err) {
            console.log(err);
        }
    };
    useEffect(() => {
        if (error) toast.error(`Get Job data failed`);
    }, [isJobadderSuccess, error]);

    const userData = useSelector((apps) => apps.app.user);
    const jobadder = useSelector((apps) => apps.home.dashboardData.jobadder);
    const pageViews = useSelector((apps) => apps.home.dashboardData.pageViews);
    const xero = useSelector((apps) => apps.home.dashboardData.xero);
    const totalVisitors = useSelector(
        (apps) => apps.home.dashboardData.totalVisitors
    );
    const GAError = useSelector((apps) => apps.home.dashboardData.GAError);

    const [dateOption, setDateOption] = useState("year");
    const [startDate, setStartDate] = useState(null);
    const [endDate, setEndDate] = useState(null);
    const [customDateVisible, setCustomDateVisible] = useState(false);
    const [loading, setLoading] = useState(false);
    useEffect(() => {
        if (!xero) return;
        const totalCash = xero["total_cash"];

        try {
            const inData = [];
            const outData = [];

            // Process 'In' data
            for (let i = 0; i < Object.keys(totalCash["y"]["In"]).length; i++) {
                inData.push(Math.round(totalCash["y"]["In"][i] * 100) / 100);
            }
            // Process 'Out' data
            for (
                let i = 0;
                i < Object.keys(totalCash["y"]["Out"]).length;
                i++
            ) {
                outData.push(Math.round(totalCash["y"]["Out"][i] * 100) / 100);
            }

            // Set the data to state variables
            setCashIn(inData);
            setCashOut(outData);
            setCategories(totalCash["name"]);
            setHasError(false);
        } catch (error) {
            setHasError(true);
            console.error("Error fetching or processing data:", error);
        }
    }, [xero]);

    const [cashIn, setCashIn] = useState([]);
    const [cashOut, setCashOut] = useState([]);
    const [categories, setCategories] = useState([]);
    const [hasError, setHasError] = useState(false);

    // Simulating fetching data

    const handleDateOptionChange = async (value) => {
        setCustomDateVisible(value === "custom");
        if (value != "custom") {
            try {
                await fetchJobadderData({
                    date_option: value,
                    startDate: null,
                    endDate: null,
                });
            } catch (err) {
                console.log(err);
            }
        }
    };
    if (isLoading) {
        return (
            <div className="jobadder-body">
                <Spin size="large" />
            </div>
        );
    }
    if (userData.role_type == 2)
        return (
            <div className="content">
                {/* Jobadder Section */}
                <Card
                    title="Jobadder"
                    headStyle={{
                        fontSize: "20px",
                        fontWeight: "bold",
                    }}
                >
                    <div className="card-header">
                        <Form
                            form={form}
                            id="filter_data"
                            action="/search_jobadder"
                            method="post"
                            initialValues={{
                                startDate: startDate ? moment(startDate) : null,
                                endDate: endDate ? moment(endDate) : null,
                                date_option: dateOption || "year",
                            }}
                        >
                            <Row>
                                {jobadder?.fullname && (
                                    <>
                                        <Col span={16}>
                                            {customDateVisible && (
                                                <Row className="custom-dates">
                                                    <Col span={5} offset={1}>
                                                        <Form.Item
                                                            name="startDate"
                                                            label="Start Date"
                                                        >
                                                            <DatePicker
                                                                style={{
                                                                    width: "100%",
                                                                }}
                                                                placeholder="Start Date"
                                                                format="YYYY-MM-DD"
                                                            />
                                                        </Form.Item>
                                                    </Col>
                                                    <Col span={5} offset={1}>
                                                        <Form.Item
                                                            name="endDate"
                                                            label="End Date"
                                                        >
                                                            <DatePicker
                                                                style={{
                                                                    width: "100%",
                                                                }}
                                                                placeholder="End Date"
                                                                format="YYYY-MM-DD"
                                                            />
                                                        </Form.Item>
                                                    </Col>

                                                    <Col span={5} offset={1}>
                                                        <Form.Item>
                                                            <Button
                                                                type="primary"
                                                                size="small"
                                                                style={{
                                                                    width: "100%",
                                                                    height: "30px",
                                                                    padding: 0,
                                                                    fontSize:
                                                                        "large",
                                                                }}
                                                                onClick={
                                                                    handleSubmit
                                                                }
                                                            >
                                                                <span
                                                                    style={{
                                                                        color: "white",
                                                                    }}
                                                                >
                                                                    Filter
                                                                </span>
                                                            </Button>
                                                        </Form.Item>
                                                    </Col>
                                                </Row>
                                            )}
                                        </Col>
                                        <Col span={5} offset={1}>
                                            <Form.Item
                                                name="date_option"
                                                label="Filter By"
                                            >
                                                <Select
                                                    placeholder="Filter By"
                                                    onChange={
                                                        handleDateOptionChange
                                                    }
                                                    style={{
                                                        width: "100%",
                                                    }}
                                                >
                                                    <Option value="year">
                                                        This Year
                                                    </Option>
                                                    <Option value="month">
                                                        This Month
                                                    </Option>
                                                    <Option value="week">
                                                        This Week
                                                    </Option>
                                                    <Option value="custom">
                                                        Custom
                                                    </Option>
                                                </Select>
                                            </Form.Item>
                                        </Col>
                                    </>
                                )}
                            </Row>
                        </Form>
                    </div>
                    <div>
                        {isJobadderLoading ? (
                            <Spin size="large" fullscreen />
                        ) : jobadder.fullname ? (
                            <>
                                <Card.Meta
                                    description={
                                        <>
                                            <p>
                                                {jobadder.fullname} -{" "}
                                                {jobadder.account_email}
                                            </p>
                                            <Row
                                                gutter={[32, 64]}
                                                style={{
                                                    marginTop: "50px",
                                                    marginBottom: "32px",
                                                }}
                                            >
                                                <Col
                                                    xs={24}
                                                    sm={24}
                                                    md={12}
                                                    lg={12}
                                                    xl={6}
                                                >
                                                    <Card>
                                                        <div className="job-card">
                                                            <div className="icon-box">
                                                                <ProjectFilled />
                                                            </div>
                                                            <div className="job-card-title">
                                                                <span className="dashboard-info">
                                                                    Total Jobs
                                                                </span>
                                                                <Title className="job-card-number">
                                                                    {"+" +
                                                                        jobadder.jobs}
                                                                </Title>
                                                            </div>
                                                        </div>
                                                    </Card>
                                                </Col>
                                                <Col
                                                    xs={24}
                                                    sm={24}
                                                    md={12}
                                                    lg={12}
                                                    xl={6}
                                                >
                                                    <Card>
                                                        <div className="job-card">
                                                            <div className="icon-box">
                                                                <ContactsFilled />
                                                            </div>
                                                            <div className="job-card-title">
                                                                <span className="dashboard-info">
                                                                    Total
                                                                    Contacts
                                                                </span>
                                                                <Title className="job-card-number">
                                                                    {"+" +
                                                                        jobadder.contacts}
                                                                </Title>
                                                            </div>
                                                        </div>
                                                    </Card>
                                                </Col>
                                                <Col
                                                    xs={24}
                                                    sm={24}
                                                    md={12}
                                                    lg={12}
                                                    xl={6}
                                                >
                                                    <Card>
                                                        <div className="job-card">
                                                            <div className="icon-box">
                                                                <WechatWorkFilled />
                                                            </div>
                                                            <div className="job-card-title">
                                                                <span className="dashboard-info">
                                                                    Total
                                                                    Interviews
                                                                </span>
                                                                <Title className="job-card-number">
                                                                    {"+" +
                                                                        jobadder.interviews}
                                                                </Title>
                                                            </div>
                                                        </div>
                                                    </Card>
                                                </Col>
                                                <Col
                                                    xs={24}
                                                    sm={24}
                                                    md={12}
                                                    lg={12}
                                                    xl={6}
                                                >
                                                    <Card>
                                                        <div className="job-card">
                                                            <div className="icon-box">
                                                                <span>
                                                                    <TeamOutlined />
                                                                </span>
                                                            </div>
                                                            <div className="job-card-title">
                                                                <span className="dashboard-info">
                                                                    Total
                                                                    Candidates
                                                                </span>
                                                                <Title className="job-card-number">
                                                                    {"+" +
                                                                        jobadder.candidates}
                                                                </Title>
                                                            </div>
                                                        </div>
                                                    </Card>
                                                </Col>
                                            </Row>
                                            {jobadder.jobs_graph && (
                                                <Row
                                                    gutter={[32, 32]}
                                                    style={{
                                                        marginTop: 10,
                                                    }}
                                                >
                                                    <Col
                                                        xs={24}
                                                        sm={24}
                                                        md={24}
                                                        lg={24}
                                                        xl={12}
                                                    >
                                                        <Card className="bar-chart">
                                                            <ApexCharts
                                                                options={{
                                                                    chart: {
                                                                        type: "bar",
                                                                        zoom: {
                                                                            enabled: true,
                                                                        },
                                                                    },
                                                                    colors: [
                                                                        "white",
                                                                    ],
                                                                    title: {
                                                                        text: "Jobs Data",
                                                                        style: {
                                                                            color: "#fff", // Set color for chart title
                                                                        },
                                                                    },
                                                                    xaxis: {
                                                                        categories:
                                                                            formatData(
                                                                                jobadder.jobs_graph
                                                                            )
                                                                                .names,
                                                                        labels: {
                                                                            style: {
                                                                                colors: "#fff", // Set color for X-axis labels
                                                                            },
                                                                        },
                                                                    },
                                                                    yaxis: {
                                                                        labels: {
                                                                            style: {
                                                                                colors: "#fff", // Set color for X-axis labels
                                                                            },
                                                                        },
                                                                    },
                                                                    tooltip: {
                                                                        stickOnContact: true,
                                                                    },

                                                                    plotOptions:
                                                                        {
                                                                            bar: {
                                                                                horizontal: false,
                                                                                columnWidth:
                                                                                    "40%",
                                                                                borderRadius: 7,
                                                                            },
                                                                        },
                                                                    grid: {
                                                                        show: true,
                                                                        borderColor:
                                                                            "#ccc",
                                                                        strokeDashArray: 2,
                                                                    },
                                                                }}
                                                                series={[
                                                                    {
                                                                        name: "count",
                                                                        data: formatData(
                                                                            jobadder.jobs_graph
                                                                        )
                                                                            .values,
                                                                    },
                                                                ]}
                                                                type="bar"
                                                                height={350}
                                                            />
                                                        </Card>
                                                    </Col>
                                                    <Col
                                                        xs={24}
                                                        sm={24}
                                                        md={24}
                                                        lg={24}
                                                        xl={12}
                                                    >
                                                        <Card>
                                                            {/* Candidates Data Chart */}
                                                            <ChartContainer
                                                                chartData={formatData(
                                                                    jobadder.candidates_graph
                                                                )}
                                                                title="Candidates Data"
                                                                chartType="line"
                                                                chartId="container6"
                                                                color="#FF9655"
                                                            />
                                                        </Card>
                                                    </Col>
                                                    {/* Contacts Data Chart */}
                                                    <Col
                                                        xs={24}
                                                        sm={24}
                                                        md={24}
                                                        lg={24}
                                                        xl={12}
                                                    >
                                                        <Card>
                                                            <ChartContainer
                                                                chartData={formatData(
                                                                    jobadder.contacts_graph
                                                                )}
                                                                title="Contacts Data"
                                                                chartType="line"
                                                                chartId="container7"
                                                                color="#f35c86"
                                                            />
                                                        </Card>
                                                    </Col>
                                                    <Col
                                                        xs={24}
                                                        sm={24}
                                                        md={24}
                                                        lg={24}
                                                        xl={12}
                                                    >
                                                        {/* Interviews Data Chart */}
                                                        <Card>
                                                            <ChartContainer
                                                                chartData={formatData(
                                                                    jobadder.interviews_graph
                                                                )}
                                                                title="Interviews Data"
                                                                chartType="area"
                                                                chartId="container8"
                                                                color="#26a69a"
                                                            />
                                                        </Card>
                                                    </Col>
                                                </Row>
                                            )}
                                        </>
                                    }
                                />
                            </>
                        ) : (
                            <Card.Meta
                                description={
                                    <Alert
                                        message="You have not connected any account yet, please connect your Jobadder account."
                                        type="error"
                                    />
                                }
                            />
                        )}
                    </div>
                </Card>

                {/* Google Analytics Section */}
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
                                        <div className="analytics-info">
                                            Page Views
                                        </div>
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
                {/* Xero Section */}
                <Card
                    style={{ marginTop: 10 }}
                    title="Xero Data"
                    headStyle={{
                        fontSize: "20px",
                        fontWeight: "bold",
                    }}
                >
                    <Card.Meta
                        description={
                            xero.organisationName ? (
                                <>
                                    <Paragraph>
                                        Organisation: {xero.organisationName}
                                    </Paragraph>
                                    <Row gutter={[32, 32]}>
                                        <Col
                                            xs={24}
                                            sm={24}
                                            md={24}
                                            lg={24}
                                            xl={12}
                                        >
                                            <Card
                                                style={{ width: "100%" }}
                                                title={
                                                    <>
                                                        <h6>
                                                            Invoices owed to you
                                                        </h6>
                                                        <Row gutter={[6, 6]}>
                                                            <Col span={6}>
                                                                <p>
                                                                    {
                                                                        xero
                                                                            .data
                                                                            .draft_count
                                                                    }
                                                                    Draft
                                                                    invoices:
                                                                    {
                                                                        xero
                                                                            .data
                                                                            .draft_amount
                                                                    }
                                                                </p>
                                                            </Col>
                                                            <Col span={6}>
                                                                <p>
                                                                    {
                                                                        xero
                                                                            .data
                                                                            .aw_count
                                                                    }{" "}
                                                                    Awaiting
                                                                    payment:{" "}
                                                                    {
                                                                        xero
                                                                            .data
                                                                            .aw_amount
                                                                    }
                                                                </p>
                                                            </Col>
                                                            <Col span={6}>
                                                                {" "}
                                                                <p>
                                                                    {
                                                                        xero
                                                                            .data
                                                                            .overdue_count
                                                                    }{" "}
                                                                    Overdue:{" "}
                                                                    {
                                                                        xero
                                                                            .data
                                                                            .overdue_amount
                                                                    }
                                                                </p>
                                                            </Col>
                                                        </Row>
                                                    </>
                                                }
                                            >
                                                <ChartContainer
                                                    chartData={formatData(
                                                        xero.invoices_array
                                                    )}
                                                    title="Invoice Data"
                                                    chartType="line"
                                                    chartId="container"
                                                    color="#f35c86"
                                                />
                                            </Card>
                                        </Col>
                                        <Col
                                            xs={24}
                                            sm={24}
                                            md={24}
                                            lg={24}
                                            xl={12}
                                        >
                                            <Card
                                                title={
                                                    <>
                                                        <h6>Balance in Xero</h6>
                                                        <p>
                                                            {xero.balance[1] ||
                                                                "No data"}
                                                        </p>{" "}
                                                    </>
                                                }
                                            >
                                                <ApexCharts
                                                    options={{
                                                        chart: {
                                                            type: "line",
                                                            zoom: {
                                                                enabled: true,
                                                                type: "y",
                                                            },
                                                        },
                                                        title: {
                                                            text: "Cash in and out Data",
                                                        },
                                                        stroke: {
                                                            curve: "smooth",
                                                        },
                                                        subtitle: {
                                                            text: undefined,
                                                        },
                                                        xaxis: {
                                                            categories:
                                                                categories,
                                                        },
                                                        plotOptions: {
                                                            bar: {
                                                                dataLabels: {
                                                                    enabled: true,
                                                                    formatter:
                                                                        function (
                                                                            val
                                                                        ) {
                                                                            return `£ ${val}`;
                                                                        },
                                                                },
                                                                borderRadius: 4,
                                                                borderRadiusApplication:
                                                                    "end",
                                                                horizontal: true,
                                                            },
                                                        },
                                                        tooltip: {
                                                            y: {
                                                                formatter:
                                                                    function (
                                                                        val
                                                                    ) {
                                                                        return `£ ${val}`;
                                                                    },
                                                            },
                                                        },
                                                        legend: {
                                                            show: false,
                                                        },
                                                    }}
                                                    series={[
                                                        {
                                                            name: "In",
                                                            data: cashIn,
                                                            color: "rgb(24, 144, 255)",
                                                        },
                                                        {
                                                            name: "Out",
                                                            data: cashOut,
                                                            color: "rgb(4, 175, 41)",
                                                        },
                                                    ]}
                                                    type="line"
                                                    height={350}
                                                />
                                            </Card>
                                        </Col>
                                    </Row>
                                </>
                            ) : (
                                <Alert
                                    message="You have not connected any account yet, please connect your Xero account."
                                    type="error"
                                />
                            )
                        }
                    />
                </Card>
                <Card
                    title="My Apps"
                    style={{ marginTop: 20 }}
                    headStyle={{
                        fontSize: "20px",
                        fontWeight: "bold",
                    }}
                    className="app_card_body"
                >
                    <Row gutter={[16, 16]}>
                        {apps.map((app, index) => (
                            <Col key={index} className="app_card">
                                <a
                                    href={app.link}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    <div className="card-img-body">
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
