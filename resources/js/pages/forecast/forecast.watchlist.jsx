import React from "react";

import { Card } from "antd";
import Paragraph from "antd/es/typography/Paragraph";

export default function ForecastWatchlist({ accountWatchlist = [] }) {
    return (
        <div>
            <Card
                title={<h3>Account watchlist</h3>}
                bordered={false}
                className="criclebox cardbody h-full"
            >
                {accountWatchlist.length > 0 ? (
                    <div className="ant-list-box table-responsive">
                        <table className="width-100">
                            <thead>
                                <tr>
                                    <th>ACCOUNT</th>
                                    <th>THIS MONTH</th>
                                    <th>YTD</th>
                                </tr>
                            </thead>
                            <tbody>
                                {accountWatchlist.map((item, index) => (
                                    <tr key={index}>
                                        <td>
                                            <h6>{item[0]}</h6>
                                        </td>
                                        <td>{item[1]}</td>
                                        <td>{item[2]}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                ) : (
                    <div className="uploadfile shadow-none">
                        <Paragraph>
                            There is no data for this field as fetched from Xero
                        </Paragraph>
                    </div>
                )}
            </Card>
        </div>
    );
}
