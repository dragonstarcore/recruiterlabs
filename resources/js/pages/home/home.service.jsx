import { apiService } from "./../../app.service";

export const homeApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchMe: builder.query({
            query: () => ({
                url: "/me",
            }),
            providesTags: ["Me"],
        }),
    }),
});

export const { useFetchMeQuery } = homeApi;
