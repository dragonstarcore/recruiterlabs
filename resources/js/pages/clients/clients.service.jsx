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
        createClient: builder.mutation({
            query: (data) => ({
                url: "/users/",
                method: "POST",
                body: data,
            }),
        }),
        updateClient: builder.mutation({
            query: (data) => ({
                url: "/users/" + data.get("id"),
                method: "PUT",
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
    useUpdateClientMutation,
    useCreateClientMutation,
    useDeleteMutation,
} = clientApi;
