import React, { useState } from "react";
import { Card, Input, Tag, Tooltip, Button, Select } from "antd";
import { MailOutlined, AimOutlined } from "@ant-design/icons";

import { useFetchCommunitiesQuery } from "./community.service";

import BgProfile from "./../../../imgs/card-bg.jpg";

import "./../../../css/community.css";

const tagColors = ["magenta", "blue", "green", "volcano", "gold"];

export default function Community() {
    const { data = { users: [], user_locations: [] }, isLoading } =
        useFetchCommunitiesQuery();

    const [specialismTags, setSpecialismTags] = useState([]);
    const [specialismFilter, setSpecialismFilter] = useState("");
    const [locationFilter, setLocationFilter] = useState("");
    const [keywordFilter, setKeywordFilter] = useState("");

    const handleSpecialismKeyPress = (e) => {
        if ((e.key === "Enter" || e.key === ",") && specialismFilter.trim()) {
            e.preventDefault();
            const trimmedValue = specialismFilter.trim();

            if (!specialismTags.includes(trimmedValue)) {
                setSpecialismTags([...specialismTags, trimmedValue]);
            }
            setSpecialismFilter("");
        } else if (e.key === "Backspace" && specialismFilter === "") {
            setSpecialismTags(specialismTags.slice(0, -1));
        }
    };

    const removeSpecialismTag = (removedTag) => {
        setSpecialismTags(specialismTags.filter((tag) => tag !== removedTag));
    };

    const handleLocationChange = (value) => {
        setLocationFilter(value);
    };

    const handleKeywordChange = (e) => {
        setKeywordFilter(e.target.value.toLowerCase());
    };

    const filteredUsers = data.users.filter((user) => {
        const specialismMatch = specialismTags.every((tag) =>
            user.keywords.toLowerCase().includes(tag.toLowerCase())
        );

        const locationMatch = locationFilter
            ? user.location.toLowerCase().includes(locationFilter)
            : true;

        const keywordMatch = keywordFilter
            ? user.name.toLowerCase().includes(keywordFilter) ||
              user.industry.toLowerCase().includes(keywordFilter)
            : true;

        return specialismMatch && locationMatch && keywordMatch;
    });

    return (
        <Card
            loading={isLoading}
            className="community"
            title={
                <>
                    <div className="community-title">
                        <Input
                            placeholder="Add tags"
                            value={specialismFilter}
                            onChange={(e) =>
                                setSpecialismFilter(e.target.value)
                            }
                            onKeyDown={handleSpecialismKeyPress}
                            className="community-title-search1"
                        />
                        <Select
                            className="community-title-search2"
                            size="large"
                            value={locationFilter}
                            onChange={handleLocationChange}
                            options={data.user_locations.map((item) => ({
                                value: item.toLowerCase(),
                                label: item,
                            }))}
                        />
                        <Input
                            placeholder="Keyword filter"
                            className="community-title-search3"
                            onChange={handleKeywordChange}
                        />
                    </div>
                    {specialismTags.length === 0 ? (
                        <p style={{ margin: "0.5rem" }}>
                            After each specialism keyword use ENTER OR COMMA.
                        </p>
                    ) : (
                        <p style={{ margin: "0.5rem" }}>
                            {specialismTags.map((tag, index) => (
                                <Tag
                                    key={index}
                                    closable
                                    onClose={() => removeSpecialismTag(tag)}
                                    color={tagColors[index % tagColors.length]}
                                >
                                    {tag}
                                </Tag>
                            ))}
                        </p>
                    )}
                </>
            }
            bordered={false}
        >
            <div className="community-card">
                {filteredUsers.map((item, index) => (
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
            </div>
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
        <div>
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
            </div>
            {keywords.length > 3 && (
                <Button
                    type="link"
                    onClick={toggleShowMore}
                    className="show-more-button"
                >
                    {showMore ? "Show Less" : "Show More"}
                </Button>
            )}
        </div>
    );
}
