import { apiService } from "../../app.service";

export const ticketApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchTicket: builder.query({
            query: () => ({
                url: "/tickets",
            }),
        }),
        deleteTicket: builder.mutation({
            query: (data) => ({
                url: "/tickets/" + data,
                method: "DELETE",
            }),
        }),
        getTicket: builder.query({
            query: (data) => ({
                url: "/tickets/" + data + "/edit",
            }),
        }),
        editTicket: builder.mutation({
            query: (data) => ({
                url: "/tickets/" + data?.id,
                method: "PUT",
                body: data,
            }),
        }),
        addTicket: builder.mutation({
            query: (data) => ({
                url: "/tickets",
                method: "POST",
                body: data,
            }),
        }),
        searchTicket: builder.mutation({
            query: (data) => ({
                url: "/tickets/search",
                method: "POST",
                body: data,
            }),
        }),
    }),
});

export const {
    useFetchTicketQuery,
    useDeleteTicketMutation,
    useGetTicketQuery,
    useEditTicketMutation,
    useAddTicketMutation,
    useSearchTicketMutation,
} = ticketApi;
