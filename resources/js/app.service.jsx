import { createApi, fetchBaseQuery } from "@reduxjs/toolkit/query/react";
import { URI } from "./app.config";
import { logout } from "./app.slice"; // Adjust path as necessary

const getToken = () => {
    return localStorage.getItem("token");
};

const getCsrfToken = () => {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
};

const customFetchBaseQuery = async (args, api, extraOptions) => {
    const baseResponse = await fetchBaseQuery({
        baseUrl: URI,
        prepareHeaders: (headers) => {
            const token = getToken();
            const csrfToken = getCsrfToken();
            if (token) headers.set("Authorization", `Bearer ${token}`);
            if (csrfToken) headers.set("X-CSRF-TOKEN", csrfToken);
            return headers;
        },
    })(args, api, extraOptions);

    if (baseResponse.error) {
        const status = baseResponse.error.status;

        if (status === 401) {
            localStorage.removeItem("token");
            api.dispatch(logout());
        }
    }

    return baseResponse;
};

export const apiService = createApi({
    reducerPath: "api",
    baseQuery: customFetchBaseQuery,
    endpoints: () => ({}),
});
