import React from "react";
import { useParams } from "react-router-dom";

import { useApplyJobMutation, useFetchJobQuery } from "./jobs.service";

import JobShowCompoment from "../../components/JobShow";

export default function JobSharedJob() {
    const { id } = useParams();

    const { data = { job: {} }, isFetching } = useFetchJobQuery(id);
    const [applyJob, { isSuccess, isError, isLoading }] =
        useApplyJobMutation(id);

    return (
        <JobShowCompoment
            job={data.job}
            loading={isFetching || isLoading}
            applyJob={applyJob}
            isSuccess={isSuccess}
            isError={isError}
        />
    );
}
