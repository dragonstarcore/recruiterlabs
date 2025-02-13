import { apiService } from "../../app.service";

export const staffApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchStaff: builder.query({
            query: () => ({
                url: "/employees",
            }),
        }),
        addStaff: builder.mutation({
            query: (data) => ({
                url: "/employees",
                method: "POST",
                body: data,
            }),
        }),
        deleteStaff: builder.mutation({
            query: (data) => ({
                url: "/employees/" + data.id,
                method: "DELETE",
                body: data,
            }),
        }),
        updateStaff: builder.mutation({
            query: (data) => ({
                url: "/employees/" + data?.employee_id || null,
                //  url: "/employees/" + data?.get("employee_id") || null,
                method: "PUT",
                body: data,
            }),
        }),
        getStaff: builder.query({
            query: (data) => ({
                url: "/get_employee/?user_id=" + data,
            }),
        }),
        fetchEmployeeList: builder.query({
            query: (data) => ({
                url: "/employee_list/" + data,
            }),
        }),
        fetchFullEvents: builder.query({
            query: () => ({
                url: "/fullcalender",
            }),
        }),
        fetchEvents: builder.query({
            query: (data) => ({
                url: "/fullcalender/" + data,
            }),
        }),
        fetchEmployee: builder.query({
            query: (data) => ({
                url: `/employees/${data.employee_id}/edit?user_id=${data.user_id}`,
            }),
        }),
        addDocument: builder.mutation({
            query: (data) => ({
                url: "/hr_docs",
                method: "POST",
                body: data,
            }),
        }),
        searchDocument: builder.mutation({
            query: (data) => ({
                url: "/client_hrdoc_search/" + data.id,
                method: "POST",
                body: data,
            }),
        }),
    }),
});

export const {
    useFetchEmployeeListQuery,
    useAddStaffMutation,
    useFetchStaffQuery,
    useDeleteStaffMutation,
    useUpdateStaffMutation,
    useGetStaffQuery,
    useFetchEventsQuery,
    useFetchFullEventsQuery,
    useFetchEmployeeQuery,
    useAddDocumentMutation,
    useSearchDocumentMutation,
} = staffApi;
