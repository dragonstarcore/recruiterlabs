import { apiService } from "../../app.service";

export const jobApi = apiService.injectEndpoints({
    endpoints: (builder) => ({
        fetchJobs: builder.query({
            query: () => ({
                url: "/jobs",
            }),
            providesTags: ["Jobs"],
        }),
        fetchJob: builder.query({
            query: (id) => ({
                url: `/jobs/${id}`,
            }),
            providesTags: ["Job"],
        }),
        createJob: builder.mutation({
            query: (data) => ({
                url: "/jobs",
                method: "POST",
                body: data,
            }),
            invalidatesTags: ["Jobs"],
        }),
        editJob: builder.mutation({
            query: ({ id, data }) => ({
                url: `jobs/${id}`,
                method: "PUT",
                body: data,
            }),
            invalidatesTags: ["Job"],
        }),
        deleteJob: builder.mutation({
            query: (id) => ({
                url: `/jobs/${id}`,
                method: "DELETE",
            }),
            invalidatesTags: ["Jobs"],
        }),
    }),
});

export const {
    useFetchJobsQuery,
    useFetchJobQuery,
    useCreateJobMutation,
    useDeleteJobMutation,
    useEditJobMutation,
} = jobApi;
