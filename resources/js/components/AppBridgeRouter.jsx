import { useMemo } from "react";
import { useClientRouting, useNavigationHistory } from '@shopify/app-bridge-react';
import { useLocation, useNavigate, useSearchParams } from "react-router-dom";

const AppBridgeRouter = () => {

    const navigate = useNavigate();
    const location = useLocation();
    const { replace:propagateRoute } = useNavigationHistory();
    const [searchParams] = useSearchParams();

    const history = useMemo(
        () => ({
            replace: (path) => navigate(path, { replace: true })
        }),
        [navigate],
    );

    const prepareCustomUrl = () => {
        let ignoreParameters = ['appLoadId', 'embedded', 'host', 'shop', 'token'];
        ignoreParameters.forEach((parameter) => {
            searchParams.delete(parameter);
        });

        let queryString = searchParams.toString();
        queryString = queryString && queryString.length ? queryString : null;
        return queryString ? `${location.pathname}?${queryString}` : location.pathname;
    }

    useClientRouting(history);

    propagateRoute({
        pathname: prepareCustomUrl(),
    });

    return null;
}

export default AppBridgeRouter;

