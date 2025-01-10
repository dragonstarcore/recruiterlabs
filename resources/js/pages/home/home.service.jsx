import { apiService } from "./../../app.service";

export const homeApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        getUser: builder.query({
            query: () => ({
                url: "/user",
            }),
        }),
    }),
});

export const { useGetUserQuery } = homeApi;
