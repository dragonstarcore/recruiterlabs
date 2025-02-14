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
import { useDispatch, useSelector } from "react-redux";
import { NavLink, useLocation } from "react-router-dom";

function Sidenav({ color = "#555555" }) {
    const userData = useSelector((apps) => apps.app.user);
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
            role: [1, 2],
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
            role: [2],
            key: "business",
        },
        {
            label: (
                <NavLink to="/jobs">
                    <span
                        className="icon"
                        style={{
                            background: page === "jobs" ? color : "",
                        }}
                    >
                        {<ApartmentOutlined />}
                    </span>
                    <span className="label">My Job </span>
                </NavLink>
            ),
            role: [2],
            key: "jobs",
        },
        {
            label: (
                <NavLink to="/jobshared">
                    <span
                        className="icon"
                        style={{
                            background: page === "jobshared" ? color : "",
                        }}
                    >
                        {<ApartmentOutlined />}
                    </span>
                    <span className="label">Job sharing</span>
                </NavLink>
            ),
            role: [2],
            key: "jobshared",
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
            role: [2],
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
            role: [2],
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
            role: [2],
        },
        {
            label: (
                <NavLink to="/clients">
                    <span
                        className="icon"
                        style={{
                            background: page === "clients" ? color : "",
                        }}
                    >
                        {<DeploymentUnitOutlined />}
                    </span>
                    <span className="label"> Clients</span>
                </NavLink>
            ),
            key: "clients",
            role: [1],
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
            role: [2],
        },
        {
            label: (
                <NavLink to="/client_list">
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
            key: "client_list",
            role: [1],
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
            role: [1, 2],
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
            role: [1, 2],
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
            role: [1, 2],
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
            role: [2],
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
            role: [2],
        },
    ];
    const filteredMenuItems = menuItems.filter(
        (item) => item.role.includes(userData.role_type) // Show menu items if userRole matches the role defined in the item
    );
    return (
        <>
            <div className="brand">
                <span>Labs Dashboard</span>
            </div>
            <hr />
            <Menu theme="light" mode="inline" items={filteredMenuItems}></Menu>
        </>
    );
}

export default Sidenav;
