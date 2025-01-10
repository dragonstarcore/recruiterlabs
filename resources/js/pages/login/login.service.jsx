import { apiService } from "./../../app.service";

export const authApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        loginUser: builder.mutation({
            query: (data) => ({
                url: "/login",
                method: "POST",
                body: data,
            }),
        }),
    }),
});

export const { useLoginUserMutation } = authApi;
