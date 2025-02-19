import React from "react";

import { Card } from "antd";
import Paragraph from "antd/es/typography/Paragraph";

export default function ForecasInvoiceBillTable({ data = [] }) {
    return (
        <div>
            <Card
                title={
                    <h3>
                        Invoice & Bill &nbsp;&nbsp;
                        <span
                            style={{ fontWeight: "initial", fontSize: "1rem" }}
                        >
                            (count / amount)
                        </span>
                    </h3>
                }
                bordered={false}
                className="criclebox cardbody h-full"
            >
                {data.length > 0 ? (
                    <div className="ant-list-box table-responsive">
                        <table className="width-100">
                            <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Draft</th>
                                    <th>Awaiting</th>
                                    <th>Overdue</th>
                                </tr>
                            </thead>
                            <tbody>
                                {data.map((item, index) => (
                                    <tr key={index}>
                                        <td>{item.name}</td>
                                        <td>{item.draft}</td>
                                        <td>{item.aw}</td>
                                        <td>{item.od}</td>
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
