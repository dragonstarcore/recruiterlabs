import React, { useState } from "react";
import { Card, Input, Tabs } from "antd";

import PerformanceJobsTable from "./performance.tabs.jobs";
import PerformanceContactsTable from "./performance.tabs.contacts";
import PerformanceInterviewsTable from "./performance.tabs.interviews";
import PerformanceCandidatesTable from "./performance.tabs.candidates";

export default function PerformanceTabs({ data = { jobs: [] } }) {
    const [keyword, setKeyword] = useState("");

    const items = [
        {
            key: "1",
            label: "Jobs",
            children: (
                <PerformanceJobsTable keyword={keyword} items={data.jobs} />
            ),
        },
        {
            key: "2",
            label: "Contacts",
            children: (
                <PerformanceContactsTable
                    keyword={keyword}
                    items={data.contacts}
                />
            ),
        },
        {
            key: "3",
            label: "Interviews",
            children: (
                <PerformanceInterviewsTable
                    keyword={keyword}
                    items={data.interviews}
                />
            ),
        },
        {
            key: "4",
            label: "Candidates",
            children: (
                <PerformanceCandidatesTable
                    keyword={keyword}
                    items={data.candidates}
                />
            ),
        },
    ];

    const onChange = (e) => {
        setKeyword(e.target.value);
    };

    const operations = (
        <Input value={keyword} onChange={onChange} placeholder="Search" />
    );

    return (
        <Card>
            <Tabs
                onChange={() => {
                    setKeyword("");
                }}
                defaultActiveKey="1"
                items={items}
                tabBarExtraContent={operations}
            />
        </Card>
    );
}
