import React from "react";
import { useParams } from "react-router-dom";

import { useFetchCommunityQuery } from "./community.service";

import UserShow from "../../components/UserShow";

export default function CommunityShow() {
    const { id } = useParams();

    const { data = { user: {}, jobs: [] }, isFetching } =
        useFetchCommunityQuery(id);

    return <UserShow user={data.user} jobs={data.jobs} loading={isFetching} />;
}
