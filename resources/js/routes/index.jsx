import { createBrowserRouter } from "react-router-dom";

import Themes from "../pages/themes"
import Dashboard from "../pages/dashboard"

const routes = [
    {
        path: '/',
        exact: true,
        page: {
            component: Dashboard,
            title: 'Dashboard',
        }
    },
    {
        path: '/themes',
        exact: true,
        page: {
            component: Themes,
            title: 'Products',
        }
    }
];

const router = createBrowserRouter(routes);

export { router, routes };
