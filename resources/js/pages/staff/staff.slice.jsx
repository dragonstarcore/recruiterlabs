import { createSlice } from "@reduxjs/toolkit";

const initialState = {
    employee_list: [],
};

export const employeeSlice = createSlice({
    name: "employee",
    initialState,
    reducers: {
        setEmployee: (state, action) => {
            state.employee_list = action.payload;
        },
    },
});

export const { setEmployee } = employeeSlice.actions;

export default employeeSlice.reducer;
