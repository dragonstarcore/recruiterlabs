import React from "react";
import ReactDOM from "react-dom/client";
import "@ant-design/v5-patch-for-react-19";
import {
    BrowserRouter as Router,
    Routes,
    Route,
    Navigate,
} from "react-router-dom";
import { Provider, useSelector } from "react-redux";
import { ConfigProvider } from "antd";
import { ToastContainer } from "react-toastify";

import { store } from "./app.store";

import Layout from "./layout";
import Login from "./pages/login";
import Home from "./pages/home";
import Apps from "./pages/myapps";
import Business from "./pages/business";
import Community from "./pages/community";
import GoogleAnalytics from "./pages/google";
import KnowledgeBase from "./pages/knowledge";
import Performance from "./pages/performance";
import Staff from "./pages/staff";
import StaffCreate from "./pages/staff/staff.create";
import Tickets from "./pages/tickets";
import FinanceForecast from "./pages/forecast";
import Profile from "./pages/profile";
import Event from "./pages/event";
import Client from "./pages/clients";
import ClientList from "./pages/clients/clients.list";
import ClientCreate from "./pages/clients/clients.create";
import ClientEdit from "./pages/clients/clients.edit";

import TicketEdit from "./pages/staff/staff.detail";
import StaffEdit from "./pages/staff/staff.edit";
import StaffDetail from "./pages/staff/staff.detail";

import EmployeeList from "./pages/staff/employee_list";
import "antd/dist/reset.css";
import "react-toastify/dist/ReactToastify.css";

import "../css/app.css";
import "../css/responsive.css";

const PrivateRoute = ({ children }) => {
    const isAuth = useSelector((state) => state.app.isAuth);
    return isAuth ? children : <Navigate to="/login" />;
};

const App = () => {
    return (
        <Provider store={store}>
            <ConfigProvider>
                <Router>
                    <Routes>
                        <Route path="/login" element={<Login />} />
                        <Route
                            path="*"
                            element={
                                <PrivateRoute>
                                    <Layout>
                                        <Routes>
                                            <Route
                                                path="home"
                                                element={<Home />}
                                            />
                                            <Route
                                                path="business"
                                                element={<Business />}
                                            />
                                            <Route
                                                path="performance"
                                                element={<Performance />}
                                            />
                                            <Route
                                                path="financeforecast"
                                                element={<FinanceForecast />}
                                            />
                                            <Route
                                                path="googleanalytics"
                                                element={<GoogleAnalytics />}
                                            />
                                            <Route
                                                path="events"
                                                element={<Event />}
                                            />
                                            <Route
                                                path="staff"
                                                element={<Staff />}
                                            />
                                            <Route
                                                path="/staff/create/:user_id?"
                                                element={<StaffCreate />}
                                            />
                                            <Route
                                                path="client_list"
                                                element={<ClientList />}
                                            />
                                            <Route
                                                path="clients"
                                                element={<Client />}
                                            />
                                            <Route
                                                path="clients/create/"
                                                element={<ClientCreate />}
                                            />
                                            <Route
                                                path="clients/edit/:id?"
                                                element={<ClientEdit />}
                                            />
                                            <Route
                                                path="staff/:id?"
                                                element={<StaffDetail />}
                                            />
                                            <Route
                                                path="employee/:employee_id/edit/:user_id"
                                                element={<StaffEdit />}
                                            />
                                            <Route
                                                path="employee_list/:id?"
                                                element={<EmployeeList />}
                                            />
                                            <Route
                                                path="tickets"
                                                element={<Tickets />}
                                            />
                                            <Route
                                                path="tickets/:id?"
                                                element={<TicketEdit />}
                                            />
                                            <Route
                                                path="knowledgebase"
                                                element={<KnowledgeBase />}
                                            />
                                            <Route
                                                path="community"
                                                element={<Community />}
                                            />
                                            <Route
                                                path="apps"
                                                element={<Apps />}
                                            />
                                            <Route
                                                path="profile"
                                                element={<Profile />}
                                            />
                                            {/* Redirect any unknown routes to home */}
                                            <Route
                                                path="*"
                                                element={
                                                    <Navigate to="/home" />
                                                }
                                            />
                                        </Routes>
                                    </Layout>
                                </PrivateRoute>
                            }
                        />
                    </Routes>
                </Router>
                <ToastContainer />
            </ConfigProvider>
        </Provider>
    );
};

ReactDOM.createRoot(document.getElementById("app")).render(<App />);
