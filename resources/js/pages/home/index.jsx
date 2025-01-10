import React from "react";
import { useGetUserQuery } from "./home.service";

export default function Home() {
    const { data: user, error, isLoading } = useGetUserQuery();

    console.log("@@@@@@@@@@@@@@@@@@", user);

    if (isLoading) return <div>Loading...</div>;
    if (error) return <div>Error: {error.message}</div>;

    return (
        <div>
            <h1>User Profile</h1>
            <p>Name: {user.name}</p>
            <p>Email: {user.email}</p>
        </div>
    );
}
