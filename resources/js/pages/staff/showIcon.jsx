import React, { useState, useEffect } from "react";
import { Image } from "antd";
import { useNavigate, useParams } from "react-router-dom";

const ShowIcon = (file) => {
    console.log("file", file);
    if (file.file_ext == "pdf")
        return (
            <>
                <Image
                    src={"/assets/images/" + file.file_ext + ".png"}
                    className="rounded-pill"
                    width={70}
                    height={50}
                    alt="File Thumbnail"
                />
            </>
        );
    if (file.file_ext == "doc" || file.file_ext == "docx")
        return (
            <>
                <Image
                    src={"/assets/images/doc.jpg"}
                    className="rounded-pill"
                    width={70}
                    height={50}
                    alt="File Thumbnail"
                />
            </>
        );
    if (file?.type == "application/pdf") {
        return (
            <>
                <Image
                    src={"/assets/images/pdf.png"}
                    className="rounded-pill"
                    width={70}
                    height={50}
                    alt="File Thumbnail"
                />
            </>
        );
    }
    if (file?.type == "application/doc") {
        return (
            <>
                <Image
                    src={"/assets/images/doc.png"}
                    className="rounded-pill"
                    width={70}
                    height={50}
                    alt="File application/doc"
                />
            </>
        );
    }
    if (file?.id)
        return (
            <Image
                src={
                    "/" + file?.file || URL.createObjectURL(file?.originFileObj)
                }
                className="rounded-pill"
                width={70}
                height={50}
                alt="File id"
            />
        );
    if (file?.uid)
        return (
            <Image
                src={URL.createObjectURL(file?.originFileObj)}
                className="rounded-pill"
                width={70}
                height={50}
                alt="File uid"
            />
        );
};
export default ShowIcon;
