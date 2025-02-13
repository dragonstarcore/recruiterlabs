import React from "react";
import { Divider, List, Badge } from "antd";
import { NavLink } from "react-router-dom";

const PageReferrer = ({ items }) => (
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
                        {item.pageReferrer || "None"}
                        <Badge
                            count={item.screenPageViews}
                            style={{ backgroundColor: "#52c41a" }}
                            overflowCount={100000}
                        />
                    </div>
                </List.Item>
            )}
        />
    </>
);

export default PageReferrer;
