import {AppProvider} from "@shopify/polaris";
import {useEffect, useState} from "react";
import enTranslations from "@shopify/polaris/locales/en.json";
import MissingApiKey from "./components/MissingApiKey";
import { useNavigate } from "react-router-dom";
import Routes from './components/Routes';
import { ErrorBoundary } from "react-error-boundary"
import ErrorFallback from "./components/ErrorFallback";
import axios from 'axios';

const App = () => {
    const [appBridgeConfig] = useState(() => {
        const host = new URLSearchParams(location.search).get("host") || window.__SHOPIFY_HOST;
        window.__SHOPIFY_HOST = host;
        return {
            host,
            apiKey: import.meta.env.VITE_SHOPIFY_API_KEY,
            forceRedirect: true,
        };
    });

    const [loading, setLoading] = useState(true);

    //to set shopify token during axios call
    const setHeaders = async () => {
        setLoading(true);
        window.axios.interceptors.request.use(async (config) => {
            try {
                let token = await shopify.idToken()
                config.headers['Authorization'] = `Bearer ${token}`;
                return config;
            } catch (e) {
                console.error('Failed to load session token', e);
            }
        });
        setLoading(false);
    }

    useEffect(() => {
        setHeaders();
    }, [])

    const logError = (error, info) => {
        console.log(error, '<== error')
        console.log(info, '<== info')
    };

    if (!appBridgeConfig.apiKey) {
        return (
            <AppProvider i18n={enTranslations}>
                <MissingApiKey/>
            </AppProvider>
        );
    }

    const navigate = useNavigate();
    const handleNavigation = (event, path) => {
        event.preventDefault();
        navigate(path, { replace: true });
    };


    return (
        <AppProvider i18n={enTranslations}>
            <ui-nav-menu>
                <a href="/" rel="home" onClick={(event) => handleNavigation(event, '/')}>App</a>
                <a href="/products" onClick={(event) => handleNavigation(event, '/products')}>Products</a>
                <a href="/collections" onClick={(event) => handleNavigation(event, '/collections')}>Collections</a>
            </ui-nav-menu>
            <ErrorBoundary FallbackComponent={ErrorFallback} onError={logError}>
                {!loading && <Routes />}
            </ErrorBoundary>
        </AppProvider>
    );
};

export default App;
