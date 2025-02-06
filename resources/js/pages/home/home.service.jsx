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
    }),
});

export const { useFetchMeQuery,useFetchDataQuery  } = homeApi;
