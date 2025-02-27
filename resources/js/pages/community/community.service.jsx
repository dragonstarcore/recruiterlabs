import { apiService } from "./../../app.service";

export const communityApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchCommunities: builder.query({
            query: () => ({
                url: "/communities",
            }),
        }),
        fetchCommunity: builder.query({
            query: (id) => ({
                url: `/communities/${id}`,
            }),
        }),
    }),
});

export const { useFetchCommunitiesQuery, useFetchCommunityQuery } =
    communityApi;
