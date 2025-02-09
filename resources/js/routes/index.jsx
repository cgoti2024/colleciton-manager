import { createBrowserRouter } from "react-router-dom";

import Products from "../pages/products"
import Collections from "../pages/collections"
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
    },
    {
        path: '/collections',
        exact: true,
        page: {
            component: Collections,
            title: 'Orders',
        }
    }
];

const router = createBrowserRouter(routes);

export { router, routes };
