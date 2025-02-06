import { createSlice } from "@reduxjs/toolkit";

const initialState = {
    dashboardData: {
        jobadder: {
            fullname: "asdf",
            account_email: "asdf",
            jobs: 50,
            contacts: 200,
            interviews: 30,
            candidates: 150,
            jobs_graph: [
                { name: "Week 1", y: 10 },
                { name: "Week 2", y: 20 },
                { name: "Week 3", y: 30 },
                { name: "Week 4", y: 25 },
                { name: "Week 5", y: 40 },
                { name: "Week 6", y: 35 },
            ],
            candidates_graph: [
                { name: "Week 1", y: 10 },
                { name: "Week 2", y: 20 },
                { name: "Week 3", y: 30 },
                { name: "Week 4", y: 25 },
                { name: "Week 5", y: 40 },
                { name: "Week 6", y: 35 },
            ],
            interviews_graph: [
                { name: "Week 1", y: 10 },
                { name: "Week 2", y: 20 },
                { name: "Week 3", y: 30 },
                { name: "Week 4", y: 25 },
                { name: "Week 5", y: 40 },
                { name: "Week 6", y: 35 },
            ],
            contacts_graph: [
                { name: "Week 1", y: 10 },
                { name: "Week 2", y: 20 },
                { name: "Week 3", y: 30 },
                { name: "Week 4", y: 25 },
                { name: "Week 5", y: 40 },
                { name: "Week 6", y: 35 },
            ],
        },
        xero: {
            organisationName: "asdf",
            username: "asdf",
            balance: [null, 5000],
            invoices_array: [
                { name: "Week 1", y: 10 },
                { name: "Week 2", y: 20 },
                { name: "Week 3", y: 30 },
                { name: "Week 4", y: 25 },
                { name: "Week 5", y: 40 },
                { name: "Week 6", y: 35 },
            ],
            data: {
                draft_count: 5,
                draft_amount: 1000,
                aw_count: 3,
                aw_amount: 2000,
                overdue_count: 1,
                overdue_amount: 500,
            },
            total_cash: {
                y: {
                    In: [
                        4579, 2997, 4158, 3740, 4023, 2905, 4821, 4873, 3256,
                        4677, 3224, 4971,
                    ],
                    Out: [
                        1117, 2852, 2543, 1742, 1353, 1807, 2927, 1325, 2595,
                        2204, 2385, 2919,
                    ],
                },
                name: [
                    "Week 1",
                    "Week 2",
                    "Week 3",
                    "Week 4",
                    "Week 5",
                    "Week 6",
                    "Week 7",
                    "Week 8",
                    "Week 9",
                    "Week 10",
                    "Week 11",
                    "Week 12",
                ],
            },
        },
        pageViews: 10,
        totalVisitors: 10,
        GAError: "",
    },
};

export const homeSlice = createSlice({
    name: "home",
    initialState,
    reducers: {
        setJobadder: (state, action) => {
            state.dashboardData.jobadder = action.payload;
        },
        setDashboard: (state, action) => {
            state.dashboardData = action.payload;
        },
    },
});

export const { setJobadder, setDashboard } = homeSlice.actions;

export default homeSlice.reducer;
