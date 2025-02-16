import { apiService } from "./../../app.service";

export const performanceApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchJobAdderData: builder.query({
            query: (query) => ({
                url: "/jobadder",
                query: query,
            }),
        }),
    }),
});

export const { useFetchJobAdderDataQuery } = performanceApi;
