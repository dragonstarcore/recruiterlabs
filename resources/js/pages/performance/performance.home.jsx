import React, { useState, useEffect } from "react";
import { Card, Col, DatePicker, Row, Select } from "antd";
import moment from "moment";

import "./styles.css";
import PerformanceCards from "./performance.cards";
import PerformanceJobs from "./performance.jobs";
import PerformanceContacts from "./performance.contacts";
import PerformanceInterviews from "./performance.interviews";
import PerformanceCandidates from "./performance.candidates";

const { RangePicker } = DatePicker;
const { Option } = Select;

export default function PerformanceHomeContainer({ data = {} }) {
    const [filteredData, setFilteredData] = useState({});

    const [dateRange, setDateRange] = useState([null, null]);

    const [filterType, setFilterType] = useState("month");

    const [graphData, setGraphData] = useState({
        jobs: [],
        contacts: [],
        placements: [],
        interviews: [],
        candidates: [],
    });

    const handleDateOptionChange = (value) => {
        setFilterType(value);
    };

    const handleDateRangeChange = (dates) => {
        setDateRange(dates || []);
    };

    const filterDataByDateRange = (items, start, end) => {
        return items.filter((item) =>
            moment(item.createdAt).isBetween(start, end)
        );
    };

    const generateGraphData = (filtered, start, end) => {
        const graphData = {
            jobs: [],
            contacts: [],
            placements: [],
            interviews: [],
            candidates: [],
        };

        const days = moment(end).diff(moment(start), "days");

        const addGraphData = (type, date, count) => {
            graphData[type].push({ name: date, count });
        };

        const types = [
            "jobs",
            "contacts",
            "placements",
            "interviews",
            "candidates",
        ];

        if (days <= 7) {
            for (let i = 0; i <= days; i++) {
                const date = moment(start).add(i, "days").format("D MMM");

                types.forEach((type) => {
                    const count = filtered[type].filter((item) =>
                        moment(item.createdAt).isSame(
                            moment(start).add(i, "days"),
                            "day"
                        )
                    ).length;

                    addGraphData(type, date, count);
                });
            }
        } else if (days <= 31) {
            const weeks = [];

            let currentWeekStart = moment(start).startOf("week");

            while (currentWeekStart.isBefore(end)) {
                weeks.push(currentWeekStart.clone());

                currentWeekStart.add(1, "week");
            }

            weeks.forEach((weekStart, index) => {
                const weekEnd = weekStart.clone().endOf("week");

                types.forEach((type) => {
                    const count = filtered[type].filter((item) =>
                        moment(item.createdAt).isBetween(weekStart, weekEnd)
                    ).length;

                    addGraphData(type, `Week ${index + 1}`, count);
                });
            });
        } else if (days <= 365) {
            for (let i = 0; i < 12; i++) {
                const month = moment().month(i).format("MMM");

                types.forEach((type) => {
                    const count = filtered[type].filter(
                        (item) => moment(item.createdAt).month() === i
                    ).length;

                    addGraphData(type, month, count);
                });
            }
        } else {
            const years = [
                ...new Set(
                    filtered.jobs.map((job) => moment(job.createdAt).year())
                ),
            ];

            years.forEach((year) => {
                types.forEach((type) => {
                    const count = filtered[type].filter(
                        (item) => moment(item.createdAt).year() === year
                    ).length;

                    addGraphData(type, year.toString(), count);
                });
            });
        }

        return graphData;
    };

    useEffect(() => {
        let filtered = {
            jobs: data.jobs?.items || [],
            contacts: data.contacts?.items || [],
            placements: data.placements?.items || [],
            interviews: data.interviews?.items || [],
            candidates: data.candidates?.items || [],
        };

        let graphData = {
            jobs: [],
            contacts: [],
            placements: [],
            interviews: [],
            candidates: [],
        };

        if (filterType === "custom" && dateRange[0] && dateRange[1]) {
            const [start, end] = dateRange.map((date) =>
                date.format("YYYY-MM-DD")
            );

            filtered = {
                jobs: filterDataByDateRange(data.jobs?.items, start, end),
                contacts: filterDataByDateRange(
                    data.contacts?.items,
                    start,
                    end
                ),
                placements: filterDataByDateRange(
                    data.placements?.items,
                    start,
                    end
                ),
                interviews: filterDataByDateRange(
                    data.interviews?.items,
                    start,
                    end
                ),
                candidates: filterDataByDateRange(
                    data.candidates?.items,
                    start,
                    end
                ),
            };

            graphData = generateGraphData(filtered, start, end);
        } else {
            let start, end;

            if (filterType === "year") {
                start = moment().startOf("year");
                end = moment().endOf("year");
            } else if (filterType === "month") {
                start = moment().startOf("month");
                end = moment().endOf("month");
            } else if (filterType === "week") {
                start = moment().startOf("week");
                end = moment().endOf("week");
            } else if (filterType === "all") {
                start = moment().startOf("year").subtract(10, "years");
                end = moment().endOf("year");
            }

            if (start && end) {
                filtered = {
                    jobs: filterDataByDateRange(data.jobs?.items, start, end),
                    contacts: filterDataByDateRange(
                        data.contacts?.items,
                        start,
                        end
                    ),
                    placements: filterDataByDateRange(
                        data.placements?.items,
                        start,
                        end
                    ),
                    interviews: filterDataByDateRange(
                        data.interviews?.items,
                        start,
                        end
                    ),
                    candidates: filterDataByDateRange(
                        data.candidates?.items,
                        start,
                        end
                    ),
                };

                graphData = generateGraphData(filtered, start, end);
            }
        }

        setFilteredData(filtered);
        setGraphData(graphData);
    }, [data, filterType, dateRange]);

    return (
        <Card
            title={
                <div className="jobadder-header">
                    <div className="jobadder-header-title">
                        <h2>Jobadder</h2>
                        <span>{data.name}</span>
                        <span>{data.email}</span>
                    </div>

                    {data?.name && (
                        <div className="jobadder-form">
                            {filterType === "custom" && (
                                <RangePicker
                                    className="jobadder-header-datapicker"
                                    onChange={handleDateRangeChange}
                                />
                            )}
                            <Select
                                placeholder="Filter By"
                                onChange={handleDateOptionChange}
                                className="jobadder-header-filter"
                                value={filterType}
                            >
                                <Option value="all">All</Option>
                                <Option value="year">This Year</Option>
                                <Option value="month">This Month</Option>
                                <Option value="week">This Week</Option>
                                <Option value="custom">Custom</Option>
                            </Select>
                        </div>
                    )}
                </div>
            }
        >
            <PerformanceCards
                jobs={filteredData.jobs?.length}
                contacts={filteredData.contacts?.length}
                placements={filteredData.placements?.length}
                interviews={filteredData.interviews?.length}
                candidates={filteredData.candidates?.length}
            />

            <Row
                gutter={[32, 32]}
                style={{
                    marginTop: "2rem",
                }}
            >
                <Col xs={24} sm={24} md={24} lg={24} xl={12}>
                    <PerformanceJobs data={graphData.jobs} />
                </Col>

                <Col xs={24} sm={24} md={24} lg={24} xl={12}>
                    <PerformanceContacts data={graphData.contacts} />
                </Col>

                <Col xs={24} sm={24} md={24} lg={24} xl={12}>
                    <PerformanceInterviews data={graphData.interviews} />
                </Col>

                <Col xs={24} sm={24} md={24} lg={24} xl={12}>
                    <PerformanceCandidates data={graphData.candidates} />
                </Col>
            </Row>
        </Card>
    );
}
