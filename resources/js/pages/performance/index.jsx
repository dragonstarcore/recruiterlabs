import React, { useEffect, useState } from "react";

const CLIENT_ID = "lhytzr73qs5ublobvwumnd5vnu";
const REDIRECT_URI = "http://localhost/recruiterlabs/jobadder";
const AUTHORIZATION_ENDPOINT = "https://id.jobadder.com/connect/authorize";
const SCOPE = "read offline_access";

export default function Performance() {
    const [token, setToken] = useState(null);

    useEffect(() => {
        const urlParams = new URLSearchParams(window.location.search);
        const authorizationCode = urlParams.get("code");

        if (authorizationCode) {
            getAccessToken(authorizationCode);
        } else getAccessToken();
    }, []);

    const authorize = () => {
        window.location.href = `${AUTHORIZATION_ENDPOINT}?response_type=code&client_id=${CLIENT_ID}&redirect_uri=${encodeURIComponent(
            REDIRECT_URI
        )}&scope=${SCOPE}`;
    };

    const getAccessToken = async (authorizationCode) => {
        try {
            const res = await fetch(`/api/jobadder?code=${authorizationCode}`, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem("token")}`,
                },
            });

            const data = await res.json();

            if (data.ok === "ok") setToken("ok");
            else authorize();

            console.log("@@@@@@@@@@@@@@@@@@@@@@@@@@@", data);
        } catch (error) {
            console.error("Failed to get access token", error);
        }
    };

    return (
        <div>
            <h1>JobAdder API Integration</h1>
            {!token && <button onClick={authorize}>Authorize</button>}
        </div>
    );
}
