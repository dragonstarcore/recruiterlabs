import { apiService } from "./../../app.service";

export const communityApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchCommunities: builder.query({
            query: () => ({
                url: "/communities",
            }),
        }),
    }),
});

export const { useFetchCommunitiesQuery } = communityApi;
