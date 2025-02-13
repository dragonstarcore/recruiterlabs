import React from "react";
import { Card, Tooltip } from "antd";
import { NavLink } from "react-router-dom";

const truncateTitle = (title) => {
    return title.length > 24 ? `${title.substring(0, 24)}...` : title;
};

// Function to extract the video ID from the embedded link
const getYouTubeVideoId = (url) => {
    const urlParts = url.split("/");
    return urlParts[urlParts.length - 1];
};

const Video = ({ items, keyword }) => (
    <div className="video-container">
        {items
            .filter((item) =>
                item.title.toLowerCase().includes(keyword.toLowerCase())
            )
            .map((item, index) => {
                const videoId = getYouTubeVideoId(item.embedded_link);
                const thumbnailUrl = `https://img.youtube.com/vi/${videoId}/0.jpg`;

                return (
                    <NavLink
                        target="_blank"
                        to={item.embedded_link}
                        key={index}
                        className="video-card"
                    >
                        <Card
                            hoverable
                            cover={
                                <img alt="video thumbnail" src={thumbnailUrl} />
                            }
                            className="video-card"
                        >
                            <Tooltip title={item.title}>
                                <div className="video-title">
                                    {truncateTitle(item.title)}
                                </div>
                            </Tooltip>
                        </Card>
                    </NavLink>
                );
            })}
    </div>
);

export default Video;
