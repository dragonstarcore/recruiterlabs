import React from "react";
import { Divider, List, Badge } from "antd";
import { NavLink } from "react-router-dom";

const MostVisitedPage = ({ items }) => (
    <>
        <Divider orientation="left">Most Visited Pages</Divider>
        <List
            bordered
            dataSource={items}
            renderItem={(item) => (
                <List.Item>
                    <div
                        style={{
                            display: "flex",
                            justifyContent: "space-between",
                            width: "100%",
                        }}
                    >
                        <NavLink
                            to={`https://${item.fullPageUrl}`}
                            target="_blank"
                            rel="noopener noreferrer"
                        >
                            {item.pageTitle}
                        </NavLink>
                        <Badge
                            count={item.screenPageViews}
                            overflowCount={100000}
                            style={{ backgroundColor: "#52c41a" }}
                        />
                    </div>
                </List.Item>
            )}
        />
    </>
);

export default MostVisitedPage;
