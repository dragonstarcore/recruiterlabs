import { createSlice } from "@reduxjs/toolkit";

const initialState = {
    clients: [],
};

export const clientSlice = createSlice({
    name: "client",
    initialState,
    reducers: {
        setClient: (state, action) => {
            state.clients = action.payload;
        },
    },
});

export const { setClient } = clientSlice.actions;

export default clientSlice.reducer;
