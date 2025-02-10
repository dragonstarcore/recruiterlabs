import { apiService } from "../../app.service";

export const staffApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchStaff: builder.query({
            query: () => ({
                url: "/employees",
            }),
        }),
        fetchFullEvents: builder.query({
            query: () => ({
                url: "/fullcalender",
            }),
        }),
        fetchEmployee: builder.query({
            query: (data) => ({
                url: `/employees/${data}/edit`,
            }),
        }),
    }),
});

export const {
    useFetchStaffQuery,
    useFetchFullEventsQuery,
    useFetchEmployeeQuery,
} = staffApi;
