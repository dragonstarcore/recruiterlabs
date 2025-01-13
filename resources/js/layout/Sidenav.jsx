import {
    ApartmentOutlined,
    AppstoreOutlined,
    BarChartOutlined,
    BookOutlined,
    CreditCardOutlined,
    DashboardOutlined,
    DeploymentUnitOutlined,
    PoundCircleOutlined,
    SettingOutlined,
    TeamOutlined,
    UserSwitchOutlined,
} from "@ant-design/icons";
import { Menu } from "antd";
import { NavLink, useLocation } from "react-router-dom";

function Sidenav({ color = "#555555" }) {
    const { pathname } = useLocation();
    const page = pathname.replace("/", "");

    const menuItems = [
        {
            label: (
                <NavLink to="/home">
                    <span
                        className="icon"
                        style={{
                            background: page === "dashboard" ? color : "",
                        }}
                    >
                        {<DashboardOutlined />}
                    </span>
                    <span className="label">Dashboard</span>
                </NavLink>
            ),
            key: "dash",
        },
        {
            label: (
                <NavLink to="/business">
                    <span
                        className="icon"
                        style={{
                            background: page === "business" ? color : "",
                        }}
                    >
                        {<ApartmentOutlined />}
                    </span>
                    <span className="label">My Business</span>
                </NavLink>
            ),
            key: "business",
        },
        {
            label: (
                <NavLink to="/performance">
                    <span
                        className="icon"
                        style={{
                            background: page === "performance" ? color : "",
                        }}
                    >
                        {<BarChartOutlined />}
                    </span>
                    <span className="label">Performance</span>
                </NavLink>
            ),
            key: "performance",
        },
        {
            label: (
                <NavLink to="/financeforecast">
                    <span
                        className="icon"
                        style={{
                            background: page === "financeforecast" ? color : "",
                        }}
                    >
                        {<PoundCircleOutlined />}
                    </span>
                    <span className="label">Finance and Forecast</span>
                </NavLink>
            ),
            key: "financeforecast",
        },
        {
            label: (
                <NavLink to="/googleanalytics">
                    <span
                        className="icon"
                        style={{
                            background: page === "googleanalytics" ? color : "",
                        }}
                    >
                        {<DeploymentUnitOutlined />}
                    </span>
                    <span className="label">Google Analytics</span>
                </NavLink>
            ),
            key: "googleanalytics",
        },
        {
            label: (
                <NavLink to="/staff">
                    <span
                        className="icon"
                        style={{
                            background: page === "staff" ? color : "",
                        }}
                    >
                        {<UserSwitchOutlined />}
                    </span>
                    <span className="label">My Staff</span>
                </NavLink>
            ),
            key: "staff",
        },
        {
            label: (
                <NavLink to="/tickets">
                    <span
                        className="icon"
                        style={{
                            background: page === "tickets" ? color : "",
                        }}
                    >
                        {<CreditCardOutlined />}
                    </span>
                    <span className="label">Tickets</span>
                </NavLink>
            ),
            key: "tickets",
        },
        {
            label: (
                <NavLink to="/knowledgebase">
                    <span
                        className="icon"
                        style={{
                            background: page === "knowledgebase" ? color : "",
                        }}
                    >
                        {<BookOutlined />}
                    </span>
                    <span className="label">Knowledge Base</span>
                </NavLink>
            ),
            key: "knowledgebase",
        },
        {
            label: (
                <NavLink to="/community">
                    <span
                        className="icon"
                        style={{
                            background: page === "community" ? color : "",
                        }}
                    >
                        {<TeamOutlined />}
                    </span>
                    <span className="label">Community</span>
                </NavLink>
            ),
            key: "community",
        },
        {
            label: (
                <NavLink to="/profile">
                    <span
                        className="icon"
                        style={{
                            background: page === "profile" ? color : "",
                        }}
                    >
                        {<SettingOutlined />}
                    </span>
                    <span className="label">Update Profile</span>
                </NavLink>
            ),
            key: "profile",
        },
        {
            label: (
                <NavLink to="/apps">
                    <span
                        className="icon"
                        style={{
                            background: page === "apps" ? color : "",
                        }}
                    >
                        {<AppstoreOutlined />}
                    </span>
                    <span className="label">My Apps</span>
                </NavLink>
            ),
            key: "apps",
        },
    ];

    return (
        <>
            <div className="brand">
                <span>Labs Dashboard</span>
            </div>
            <hr />
            <Menu theme="light" mode="inline" items={menuItems}></Menu>
        </>
    );
}

export default Sidenav;
