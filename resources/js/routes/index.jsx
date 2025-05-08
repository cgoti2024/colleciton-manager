import { createBrowserRouter } from "react-router-dom";

import Products from "../pages/products"
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
        path: '/products',
        exact: true,
        page: {
            component: Products,
            title: 'Products',
        }
    }
];

const router = createBrowserRouter(routes);

export { router, routes };
