import React from "react";
import { useParams } from "react-router-dom";

import { useFetchJobQuery } from "./jobs.service";

import JobShowCompoment from "../../components/JobShow";

export default function JobSharedJob() {
    const { id } = useParams();

    const { data = { job: {} }, isFetching } = useFetchJobQuery(id);

    return <JobShowCompoment job={data.job} loading={isFetching} />;
}
