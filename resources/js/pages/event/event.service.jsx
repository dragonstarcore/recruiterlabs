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
    }),
});

export const { useFetchCalendarQuery, useManageEventsMutation } = eventApi;
