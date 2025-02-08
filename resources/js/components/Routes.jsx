import { Routes as ReactRoutes, Route } from 'react-router-dom';
import { routes as AppRoutes} from "../routes"

const Routes  = () => {

    return (
        <ReactRoutes>
            {AppRoutes.map((route,i) => {
                return (
                    <Route exact path={route.path} key={i} element={<route.page.component />}></Route>
                )})}
        </ReactRoutes>
    );
}

export default Routes;
