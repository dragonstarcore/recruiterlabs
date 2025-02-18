import { apiService } from "../../app.service";

export const forecastApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchXero: builder.query({
            query: () => ({
                url: "/xero",
            }),
        }),
        fetchXeroredirect: builder.mutation({
            query: () => ({
                url: "/xero/auth/authorize",
            }),
        }),
    }),
});

export const { useFetchXeroQuery, useFetchXeroredirectMutation } = forecastApi;
