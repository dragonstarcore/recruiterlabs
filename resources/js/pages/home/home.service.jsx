import { apiService } from "./../../app.service";

export const homeApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchMe: builder.query({
            query: () => ({
                url: "/me",
            }),
            providesTags: ["Me"],
        }),
        fetchData: builder.query({
            query: () => ({
                url: "/home",
            }),
            providesTags: ["Dashboard"],
        }),
        fetchJobadderData: builder.mutation({
            query: (data) => ({
                url: "/dashboard_jobadder",
                method: "POST",
                body: data,
            }),
            providesTags: ["Jobadder"],
        }),
    }),
});

export const {
    useFetchMeQuery,
    useFetchDataQuery,
    useFetchJobadderDataMutation,
} = homeApi;
