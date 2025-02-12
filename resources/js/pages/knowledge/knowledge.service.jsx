import { apiService } from "./../../app.service";

export const knowledgeApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchKnowledges: builder.query({
            query: () => ({
                url: "/knowledges",
            }),
        }),
    }),
});

export const { useFetchKnowledgesQuery } = knowledgeApi;
