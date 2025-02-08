import { createSlice } from "@reduxjs/toolkit";

const initialState = {
    businessData: {
        user: {
            user_documents: [],
        },
        doc_types: [],
    },
};

export const businessSlice = createSlice({
    name: "business",
    initialState,
    reducers: {
        setBusiness: (state, action) => {
            state.businessData.user = action.payload;
        },
        setDoc_types: (state, action) => {
            state.businessData.doc_types = action.payload;
        },
    },
});

export const { setBusiness, setDoc_types } = businessSlice.actions;

export default businessSlice.reducer;
