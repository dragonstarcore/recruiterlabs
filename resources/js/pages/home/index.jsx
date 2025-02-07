import React, { useState, useEffect } from "react";
import { useDispatch, useSelector } from "react-redux";
import { toast } from "react-toastify";
import {
    Card,
    Form,
    Row,
    Col,
    Button,
    Select,
    DatePicker,
    Alert,
    Typography,
    Spin,
} from "antd";
import moment from "moment";
import JobContainer from "./home.job";
import GoogleAnalytics from "./home.google";
import XeroContainer from "./home.xero";
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
                            <JobContainer jobadder={jobadder} />
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

                <GoogleAnalytics
                    totalVisitors={totalVisitors}
                    GAError={GAError}
                    pageViews={pageViews}
                />
                <XeroContainer xero={xero} />
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
