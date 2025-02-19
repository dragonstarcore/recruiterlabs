import React from "react";
import { NavLink } from "react-router-dom";

import { Card } from "antd";

import xeroImg from "./../../../imgs/xero.jpg";
import jobadderImg from "./../../../imgs/jobadder.jpg";
import linkedinImg from "./../../../imgs/linkedin.jpg";
import msofficeImg from "./../../../imgs/msoffice.jpg";
import dropboxImg from "./../../../imgs/dropbox.jpg";
import sharepointImg from "./../../../imgs/sharepoint.jpg";
import calendlyImg from "./../../../imgs/calendly.jpg";
import vincereImg from "./../../../imgs/vincere.jpg";
import sourcebreakerImg from "./../../../imgs/sourcebreaker.jpg";
import sonovateImg from "./../../../imgs/sonovate.jpg";
import lastpassImg from "./../../../imgs/lastpass.jpg";

export default function HomeApps() {
    return (
        <Card style={{ marginTop: "1rem" }} title={<h2>Apps</h2>}>
            <div className="home-apps">
                <NavLink target="_blank" to="https://www.xero.com/">
                    <Card className="home-apps-item">
                        <img src={xeroImg} alt="mastercard" />
                    </Card>
                </NavLink>
                <NavLink target="_blank" to="https://www.jobadder.com/">
                    <Card className="home-apps-item">
                        <img src={jobadderImg} alt="mastercard" />
                    </Card>
                </NavLink>
                <NavLink target="_blank" to="https://www.linkedin.com/">
                    <Card className="home-apps-item">
                        <img src={linkedinImg} alt="mastercard" />
                    </Card>
                </NavLink>
                <NavLink target="_blank" to="https://www.microsoft.com/">
                    <Card className="home-apps-item">
                        <img src={msofficeImg} alt="mastercard" />
                    </Card>
                </NavLink>
                <NavLink target="_blank" to="https://www.dropbox.com/">
                    <Card className="home-apps-item">
                        <img src={dropboxImg} alt="mastercard" />
                    </Card>
                </NavLink>
                <NavLink
                    target="_blank"
                    to="https://login.microsoftonline.com/"
                >
                    <Card className="home-apps-item">
                        <img src={sharepointImg} alt="mastercard" />
                    </Card>
                </NavLink>
                <NavLink target="_blank" to="https://www.calendly.com/">
                    <Card className="home-apps-item">
                        <img src={calendlyImg} alt="mastercard" />
                    </Card>
                </NavLink>
                <NavLink target="_blank" to="https://www.vincere.io/">
                    <Card className="home-apps-item">
                        <img src={vincereImg} alt="mastercard" />
                    </Card>
                </NavLink>
                <NavLink target="_blank" to="https://www.sourcebreaker.com/">
                    <Card className="home-apps-item">
                        <img src={sourcebreakerImg} alt="mastercard" />
                    </Card>
                </NavLink>
                <NavLink target="_blank" to="https://www.sonovate.com/">
                    <Card className="home-apps-item">
                        <img src={sonovateImg} alt="mastercard" />
                    </Card>
                </NavLink>
                <NavLink target="_blank" to="https://www.lastpass.com/">
                    <Card className="home-apps-item">
                        <img src={lastpassImg} alt="mastercard" />
                    </Card>
                </NavLink>
            </div>
        </Card>
    );
}
