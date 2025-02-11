import { configureStore } from "@reduxjs/toolkit";

import appReducer from "./app.slice";
import homeReducer from "./pages/home/home.slice";
import businessReducer from "./pages/business/business.slice";
import xeroReducer from "./pages/forecast/forecast.slice";
import staffReducer from "./pages/staff/staff.slice";
import eventReducer from "./pages/event/event.slice";
import ticketReducer from "./pages/tickets/tickets.slice";
import clientReducer from "./pages/clients/clients.slice";

import { apiService } from "./app.service";

export const store = configureStore({
    reducer: {
        app: appReducer,
        [apiService.reducerPath]: apiService.reducer,
        home: homeReducer,
        business: businessReducer,
        xero: xeroReducer,
        staff: staffReducer,
        event: eventReducer,
        ticket: ticketReducer,
        client: clientReducer,
    },
    middleware: (getDefaultMiddleware) =>
        getDefaultMiddleware().concat(apiService.middleware),
});
