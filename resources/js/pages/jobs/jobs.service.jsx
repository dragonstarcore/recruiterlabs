import { apiService } from "../../app.service";

export const jobApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchJob: builder.query({
            query: (data) => ({
                url: "/jobs?user_id=" + data,
            }),
        }),
        fetchSharedJob: builder.query({
            query: (data) => ({
                url: "/jobshared?user_id=" + data,
            }),
        }),
        deleteJob: builder.mutation({
            query: (data) => ({
                url: "/jobs/" + data,
                method: "DELETE",
            }),
        }),
        getJob: builder.query({
            query: (data) => ({
                url: "/jobs/" + data + "/edit",
            }),
        }),
        editJob: builder.mutation({
            query: (data) => ({
                url: "/jobs/" + data?.id,
                method: "PUT",
                body: data,
            }),
        }),
        addJob: builder.mutation({
            query: (data) => ({
                url: "/jobs",
                method: "POST",
                body: data,
            }),
        }),
        searchJob: builder.mutation({
            query: (data) => ({
                url: "/jobs/search",
                method: "POST",
                body: data,
            }),
        }),
        applyJob: builder.mutation({
            query: (data) => ({
                url: "/jobs/apply",
                method: "POST",
                body: data,
            }),
        }),
    }),
});

export const {
    useFetchJobQuery,
    useDeleteJobMutation,
    useGetJobQuery,
    useEditJobMutation,
    useAddJobMutation,
    useSearchJobMutation,
    useFetchSharedJobQuery,
    useApplyJobMutation,
} = jobApi;
