import { createSlice } from "@reduxjs/toolkit";

const initialState = {
    isAuth: !!localStorage.getItem("token"),
    user: {
        id: null,
        name: "",
        email: "",
        email_verified_at: null,
        role_type: 2,
        status: 1,
        xero_oauth: {},
        created_at: "",
        updated_at: "",
        deleted_at: null,
        user_details: {},
    },
};

export const appSlice = createSlice({
    name: "auth",
    initialState,
    reducers: {
        login: (state, action) => {
            state.isAuth = true;
            localStorage.setItem("token", action.payload.token);
        },
        logout: (state) => {
            state.isAuth = false;
            localStorage.removeItem("token");
        },
        storeMe: (state, action) => {
            state.user = action.payload;
        },
    },
});

export const { login, logout, storeMe } = appSlice.actions;

export default appSlice.reducer;
