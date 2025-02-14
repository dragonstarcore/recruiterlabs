import { createSlice } from "@reduxjs/toolkit";

const initialState = {
    jobs: [],
};

export const jobSlice = createSlice({
    name: "job",
    initialState,
    reducers: {
        setJob: (state, action) => {
            state.jobs = action.payload;
        },
        removeJob: (state, action) => {
            state.jobs = [];
        },
    },
});

export const { setJob, removeJob } = jobSlice.actions;

export default jobSlice.reducer;
