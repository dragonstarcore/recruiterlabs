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

import { setJobadder, setDashboard } from "./home.slice";

import {
    useFetchDataQuery,
    useFetchJobadderDataMutation,
} from "./home.service";

import "./style.css";

const { RangePicker } = DatePicker;
const { Option } = Select;
const { Paragraph } = Typography;

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
            console.log("@@@@@@@@@@@@@", formValues);
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

    if (isLoading)
        return (
            <div className="jobadder-body">
                <Spin size="large" />
            </div>
        );

    if (userData.role_type == 2)
        return (
            <div className="dash">
                <Card
                    title={
                        <div className="dash-jobadder-header">
                            <div className="jobadder-header-title">
                                <h2>Jobadder</h2>
                                <span>{jobadder.fullname}</span>
                                <span>{jobadder.account_email}</span>
                            </div>

                            <Form
                                form={form}
                                initialValues={{
                                    startDate: startDate
                                        ? moment(startDate)
                                        : null,
                                    endDate: endDate ? moment(endDate) : null,
                                    date_option: dateOption || "year",
                                }}
                                style={{ display: "flex" }}
                            >
                                {jobadder?.fullname && (
                                    <>
                                        {customDateVisible && (
                                            <>
                                                <Form.Item
                                                    name="startDate"
                                                    className="jobadder-header-datapicker"
                                                >
                                                    <RangePicker />
                                                </Form.Item>
                                            </>
                                        )}

                                        <Form.Item
                                            name="date_option"
                                            label="Filter By"
                                            className="jobadder-header-filter"
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
                                    </>
                                )}
                            </Form>
                        </div>
                    }
                >
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
