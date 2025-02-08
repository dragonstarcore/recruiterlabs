import { apiService } from "../../app.service";

export const forecastApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchXero: builder.query({
            query: () => ({
                url: "/xero",
            }),
        }),
    }),
});

export const { useFetchXeroQuery } = forecastApi;
