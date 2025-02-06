import { configureStore } from "@reduxjs/toolkit";

import appReducer from "./app.slice";
import homeReducer from "./pages/home/home.slice";
import { apiService } from "./app.service";

export const store = configureStore({
    reducer: {
        app: appReducer,
        [apiService.reducerPath]: apiService.reducer,
        home: homeReducer,
    },
    middleware: (getDefaultMiddleware) =>
        getDefaultMiddleware().concat(apiService.middleware),
});
