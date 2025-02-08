import { createSlice } from "@reduxjs/toolkit";

const initialState = {
    xeroData: {},
};

export const forecastSlice = createSlice({
    name: "forecast",
    initialState,
    reducers: {
        setXeroData: (state, action) => {
            state.xeroData = action.payload;
        },
    },
});

export const { setXeroData } = forecastSlice.actions;

export default forecastSlice.reducer;
