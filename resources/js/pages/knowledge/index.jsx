import React, { useState } from "react";
import { Card, Input, Tabs } from "antd";

import Document from "./knowledge.docs";
import Video from "./knowledge.video";
import Faqs from "./knowledge.faqs"; // corrected import statement

import "./styles.css";

import { useFetchKnowledgesQuery } from "./knowledge.service";

export default function KnowledgeBase() {
    const { data = { documents: [], faqs: [], videos: [] }, isLoading } =
        useFetchKnowledgesQuery();

    const [keyword, setKeyword] = useState("");

    const items = [
        {
            key: "1",
            label: "Document Library",
            children: <Document keyword={keyword} items={data.documents} />,
        },
        {
            key: "2",
            label: "Video Library",
            children: <Video keyword={keyword} items={data.videos} />,
        },
        {
            key: "3",
            label: "FAQS",
            children: <Faqs items={data.faqs} keyword={keyword} />,
        },
    ];

    const onChange = (e) => {
        setKeyword(e.target.value);
    };

    const operations = (
        <Input value={keyword} onChange={onChange} placeholder="Search" />
    );

    return (
        <Card loading={isLoading}>
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
