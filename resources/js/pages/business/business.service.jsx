import { apiService } from "../../app.service";

export const businessApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchBusiness: builder.query({
            query: () => ({
                url: "/my_business",
            }),
        }),
        fetchDocument: builder.query({
            query: () => ({
                url: "/documents",
            }),
        }),
        fetchBusinessSearch: builder.mutation({
            query: (data) => ({
                url: "/my_business_search",
                method: "POST",
                body: data,
            }),
        }),
    }),
});

export const {
    useFetchBusinessQuery,
    useFetchDocumentQuery,
    useFetchBusinessSearchMutation,
} = businessApi;
