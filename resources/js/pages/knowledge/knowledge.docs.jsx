import React from "react";
import { NavLink } from "react-router-dom";
import { Card, Tooltip } from "antd";

import pdfIcon from "../../../imgs/pdf.webp";
import docsIcon from "../../../imgs/docs.png";
import xlsxIcon from "../../../imgs/xlsx.png";

const truncateTitle = (title) => {
    return title.length > 20 ? `${title.substring(0, 17)}...` : title;
};

const getFileIcon = (file) => {
    const extension = file.split(".").pop();
    switch (extension) {
        case "pdf":
            return pdfIcon;
        case "doc":
            return docsIcon;
        case "docx":
            return docsIcon;
        case "xlsx":
            return xlsxIcon;
        case "xls":
            return xlsxIcon;
        default:
            return pdfIcon; // default icon
    }
};

const Document = ({ items, keyword }) => (
    <div className="pdf-container">
        {items
            .filter((item) =>
                item.title.toLowerCase().includes(keyword.toLowerCase())
            )
            .map((item, index) => (
                <NavLink
                    target="blank"
                    to={`/${item.file}`}
                    key={index}
                    className="pdf-card"
                >
                    <Card
                        hoverable
                        cover={
                            <img alt={item.file} src={getFileIcon(item.file)} />
                        }
                        className="pdf-card"
                    >
                        <Tooltip title={item.title}>
                            <div className="pdf-title">
                                {truncateTitle(item.title)}
                            </div>
                        </Tooltip>
                    </Card>
                </NavLink>
            ))}
    </div>
);

export default Document;
