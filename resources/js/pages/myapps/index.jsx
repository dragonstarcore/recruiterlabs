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

export default function MyApps() {
    return (
        <div className="apps">
            <NavLink target="_blank" to="https://www.xero.com/">
                <Card className="apps-item">
                    <img src={xeroImg} alt="mastercard" />
                    <div>
                        <h6 className="apps-item-name">Xero</h6>
                        <h6 className="apps-item-url">xero.com</h6>
                    </div>
                </Card>
            </NavLink>
            <NavLink target="_blank" to="https://www.jobadder.com/">
                <Card className="apps-item">
                    <img src={jobadderImg} alt="mastercard" />
                    <div>
                        <h6 className="apps-item-name">Jobadder</h6>
                        <h6 className="apps-item-url">jobadder.com</h6>
                    </div>
                </Card>
            </NavLink>
            <NavLink target="_blank" to="https://www.linkedin.com/">
                <Card className="apps-item">
                    <img src={linkedinImg} alt="mastercard" />
                    <div>
                        <h6 className="apps-item-name">Linkedin</h6>
                        <h6 className="apps-item-url">linkedin.com</h6>
                    </div>
                </Card>
            </NavLink>
            <NavLink target="_blank" to="https://www.microsoft.com/">
                <Card className="apps-item">
                    <img src={msofficeImg} alt="mastercard" />
                    <div>
                        <h6 className="apps-item-name">Microsoft</h6>
                        <h6 className="apps-item-url">microsoft.com</h6>
                    </div>
                </Card>
            </NavLink>
            <NavLink target="_blank" to="https://www.dropbox.com/">
                <Card className="apps-item">
                    <img src={dropboxImg} alt="mastercard" />
                    <div>
                        <h6 className="apps-item-name">Dropbox</h6>
                        <h6 className="apps-item-url">dropbox.com</h6>
                    </div>
                </Card>
            </NavLink>
            <NavLink target="_blank" to="https://login.microsoftonline.com/">
                <Card className="apps-item">
                    <img src={sharepointImg} alt="mastercard" />
                    <div>
                        <h6 className="apps-item-name">Sharepoint</h6>
                        <h6 className="apps-item-url">microsoftonline.com</h6>
                    </div>
                </Card>
            </NavLink>
            <NavLink target="_blank" to="https://www.calendly.com/">
                <Card className="apps-item">
                    <img src={calendlyImg} alt="mastercard" />
                    <div>
                        <h6 className="apps-item-name">Calendly</h6>
                        <h6 className="apps-item-url">calendly.com</h6>
                    </div>
                </Card>
            </NavLink>
            <NavLink target="_blank" to="https://www.vincere.io/">
                <Card className="apps-item">
                    <img src={vincereImg} alt="mastercard" />
                    <div>
                        <h6 className="apps-item-name">Vincere</h6>
                        <h6 className="apps-item-url">vincere.io</h6>
                    </div>
                </Card>
            </NavLink>
            <NavLink target="_blank" to="https://www.sourcebreaker.com/">
                <Card className="apps-item">
                    <img src={sourcebreakerImg} alt="mastercard" />
                    <div>
                        <h6 className="apps-item-name">Sourcebreaker</h6>
                        <h6 className="apps-item-url">sourcebreaker.com</h6>
                    </div>
                </Card>
            </NavLink>
            <NavLink target="_blank" to="https://www.sonovate.com/">
                <Card className="apps-item">
                    <img src={sonovateImg} alt="mastercard" />
                    <div>
                        <h6 className="apps-item-name">Sonovate</h6>
                        <h6 className="apps-item-url">sonovate.com</h6>
                    </div>
                </Card>
            </NavLink>
            <NavLink target="_blank" to="https://www.lastpass.com/">
                <Card className="apps-item">
                    <img src={lastpassImg} alt="mastercard" />
                    <div>
                        <h6 className="apps-item-name">Lastpass</h6>
                        <h6 className="apps-item-url">lastpass.com</h6>
                    </div>
                </Card>
            </NavLink>
        </div>
    );
}
