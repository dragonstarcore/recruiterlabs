import { apiService } from "./../../app.service";

export const profileApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        editProfile: builder.mutation({
            query: (data) => ({
                url: "/me",
                method: "PUT",
                body: data,
            }),
            invalidatesTags: ["Me"],
        }),
    }),
});

export const { useEditProfileMutation } = profileApi;
