import React from "react";

import {
    BrowserRouter as Router,
    Route,
    Routes,
    useParams,
} from "react-router-dom";
import { useFetchEmployeeQuery } from "./staff.service";
const MyStaffPage = ({}) => {
    const { id } = useParams();
    console.log(id);
    const { data } = useFetchEmployeeQuery(id);
    return <>edit</>;
};

export default MyStaffPage;
