import React, { useState } from "react";
import { Card, Input, Tag, Tooltip, Button } from "antd";
import { MailOutlined, AimOutlined } from "@ant-design/icons";

import { useFetchCommunitiesQuery } from "./community.service";

import BgProfile from "./../../../imgs/card-bg.jpg";

import "./../../../css/community.css";

const tagColors = ["magenta", "blue", "green", "volcano", "gold"];

export default function Community() {
    const {
        data = { users: [], user_locations: [] },
        isLoading,
        isSuccess,
    } = useFetchCommunitiesQuery();

    console.log("@@@@@@@@@@@@@@@", data);

    return (
        <Card
            loading={isLoading}
            className="community"
            title={
                <>
                    <div className="community-title">
                        <Input
                            placeholder="Specialism filter"
                            className="community-title-search1"
                        />
                        <Input
                            placeholder="Location filter"
                            className="community-title-search2"
                        />
                        <Input
                            placeholder="Keyword filter"
                            className="community-title-search3"
                        />
                    </div>
                    <p style={{ marginLeft: "0.5rem" }}>
                        After each specialism keyword use ENTER OR COMMA (eg :
                        sales, manager).
                    </p>
                </>
            }
            bordered={false}
        >
            {data.users &&
                data.users.map((item, index) => (
                    <div key={index} className="card-container">
                        <div className="header-container">
                            <div className="full-width-image">
                                <img
                                    src={BgProfile}
                                    alt="Subimage"
                                    className="header-image"
                                />
                            </div>
                            <img
                                src={`./${item.logo}`}
                                className="avatar-style"
                                alt="Logo"
                            />
                        </div>

                        <div className="content-section">
                            <h2>{item.name}</h2>

                            <span className="card-container-email">
                                <MailOutlined /> {item.email}
                            </span>
                            <br />
                            <span className="card-container-location">
                                <AimOutlined /> {item.location}
                            </span>

                            <div style={{ marginTop: "1rem" }}>
                                <strong>Industry</strong>
                                <p>{item.industry}</p>
                            </div>

                            <strong>Specialism</strong>
                            <TagsList keywords={item.keywords.split(",")} />
                        </div>
                    </div>
                ))}
        </Card>
    );
}

function TagsList({ keywords }) {
    const [showMore, setShowMore] = useState(false);
    const displayCount = showMore ? keywords.length : 3;

    const toggleShowMore = () => {
        setShowMore((prev) => !prev);
    };

    return (
        <div className="keywords-container">
            {keywords.slice(0, displayCount).map((specialism, i) => {
                const trimmedSpecialism = specialism.trim();
                const displayText =
                    trimmedSpecialism.length > 20
                        ? `${trimmedSpecialism.substring(0, 17)}...`
                        : trimmedSpecialism;

                return trimmedSpecialism.length > 20 ? (
                    <Tooltip title={trimmedSpecialism} key={i}>
                        <Tag color={tagColors[i % tagColors.length]}>
                            {displayText}
                        </Tag>
                    </Tooltip>
                ) : (
                    <Tag color={tagColors[i % tagColors.length]} key={i}>
                        {displayText}
                    </Tag>
                );
            })}
            {keywords.length > 3 && (
                <Button type="link" onClick={toggleShowMore}>
                    {showMore ? "Show Less" : "Show More"}
                </Button>
            )}
        </div>
    );
}
