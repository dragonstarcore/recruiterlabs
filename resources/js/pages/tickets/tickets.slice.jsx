import { createSlice } from "@reduxjs/toolkit";

const initialState = {
    tickets: [],
};

export const ticketSlice = createSlice({
    name: "ticket",
    initialState,
    reducers: {
        setTicket: (state, action) => {
            state.tickets = action.payload;
        },
        removeTicket: (state, action) => {
            state.tickets = [];
        },
    },
});

export const { setTicket, removeTicket } = ticketSlice.actions;

export default ticketSlice.reducer;
