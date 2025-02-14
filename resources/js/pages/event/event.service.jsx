import { apiService } from "../../app.service";

export const eventApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchCalendar: builder.query({
            query: () => ({
                url: "/fullcalender",
            }),
        }),
        manageEvents: builder.mutation({
            query: (data) => ({
                url: "/fullcalenderAjax",
                method: "POST",
                body: data,
            }),
        }),
        fetchEvents: builder.query({
            query: (data) => ({
                url: "/client_events_list/" + data,
            }),
        }),
    }),
});

export const {
    useFetchCalendarQuery,
    useManageEventsMutation,
    useFetchEventsQuery,
} = eventApi;
