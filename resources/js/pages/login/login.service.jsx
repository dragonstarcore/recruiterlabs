import { apiService } from "./../../app.service";

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

export const authApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        loginUser: builder.mutation({
            query: (data) => ({
                url: "/login",
                method: "POST",
                body: data,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            }),
        }),
    }),
});

export const { useLoginUserMutation } = authApi;
