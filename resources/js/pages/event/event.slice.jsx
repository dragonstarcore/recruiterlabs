import { createSlice } from "@reduxjs/toolkit";

const initialState = {
    events: [],
};

export const eventSlice = createSlice({
    name: "event",
    initialState,
    reducers: {
        setEvent: (state, action) => {
            state.events = action.payload;
        },
    },
});

export const { setEvent } = eventSlice.actions;

export default eventSlice.reducer;
