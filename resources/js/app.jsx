import React from "react";
import ReactDOM from "react-dom/client";
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

import Layout from "./pages/layout";
import Login from "./pages/login";
import Home from "./pages/home";

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
                            path="/home"
                            element={
                                <PrivateRoute>
                                    <Home />
                                </PrivateRoute>
                            }
                        />
                        <Route path="/" element={<Navigate to="/home" />} />
                    </Routes>
                </Router>
                <ToastContainer />
            </ConfigProvider>
        </Provider>
    );
};

ReactDOM.createRoot(document.getElementById("app")).render(<App />);
