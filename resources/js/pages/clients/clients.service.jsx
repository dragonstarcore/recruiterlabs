import { apiService } from "../../app.service";

export const clientApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchClients: builder.query({
            query: () => ({
                url: "/users",
            }),
        }),
        fetchClient: builder.query({
            query: (data) => ({
                url: "/users/" + data + "/edit",
            }),
        }),
        fetchDoc: builder.query({
            query: (data) => ({
                url: "/client_doc_search/" + data + "/edit",
            }),
        }),
        addClient: builder.mutation({
            query: (data) => ({
                url: "/users",
                method: "POST",
                body: data,
            }),
        }),
        createClient: builder.mutation({
            query: (data) => ({
                url: "/users/",
                method: "POST",
                body: data,
            }),
        }),
        delete: builder.mutation({
            query: (data) => ({
                url: "/users/" + data,
                method: "DELETE",
            }),
        }),
    }),
});

export const {
    useFetchClientsQuery,
    useFetchDocQuery,
    useFetchClientQuery,
    useAddClientMutation,
    useCreateClientMutation,
    useDeleteMutation,
} = clientApi;
