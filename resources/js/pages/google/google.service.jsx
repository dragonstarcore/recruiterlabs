import { apiService } from "./../../app.service";

export const googleApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchGA: builder.query({
            query: (period) => ({
                url: `/ga?period=${period}`,
            }),
        }),
    }),
});

export const { useFetchGAQuery } = googleApi;