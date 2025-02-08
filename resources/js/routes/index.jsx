import { createBrowserRouter } from "react-router-dom";

import Products from "../pages/products"
import Orders from "../pages/orders"
import Customers from "../pages/customers"
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
        path: '/orders',
        exact: true,
        page: {
            component: Orders,
            title: 'Orders',
        }
    },
    {
        path: '/customers',
        exact: true,
        page: {
            component: Customers,
            title: 'Customers',
        }
    }
];

const router = createBrowserRouter(routes);

export { router, routes };
