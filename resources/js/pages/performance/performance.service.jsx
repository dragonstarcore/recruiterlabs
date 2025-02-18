import { apiService } from "./../../app.service";

export const performanceApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchJobAdderData: builder.query({
            query: () => ({
                url: `/jobadder`,
            }),
        }),
    }),
});

export const { useFetchJobAdderDataQuery } = performanceApi;
