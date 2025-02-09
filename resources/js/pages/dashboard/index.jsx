import {CalloutCard, Page, Text, Grid, LegacyCard} from '@shopify/polaris';
import React, {useEffect, useState} from 'react';
import {useNavigate} from "react-router-dom";

function Dashboard() {
    const [items, setItems] = useState([])
    const navigate = useNavigate();
    const handleNavigation = (event, type) => {
        event.preventDefault();
        navigate(type, { replace: true });
    };

    const getCounts = async () => {
        await axios.get('/api/dashboard').then((res) => {
            setItems(res.data.data)
            console.log(res.data.data)
        }).catch((err) => {
            console.log(err)
        })
    }

    useEffect(() => {
        getCounts();
    }, [])

    return (
        <Page>
            <Grid>
                <Grid.Cell columnSpan={{xs: 6, sm: 6, md: 6, lg: 6, xl: 6}}>
                    <CalloutCard
                        title="Products"
                        illustration="/images/box.png"
                        primaryAction={{
                            content: 'Products',
                            onAction: (event) => handleNavigation(event, '/products'),
                        }}
                    >
                        <Text variant="headingMd" as="h1">
                            {items.products}
                        </Text>
                    </CalloutCard>
                </Grid.Cell>
                <Grid.Cell columnSpan={{xs: 6, sm: 6, md: 6, lg: 6, xl: 6}}>
                    <CalloutCard
                        title="Collections"
                        illustration="/images/checklist.png"
                        primaryAction={{
                            content: 'Orders',
                            onAction: (event) => handleNavigation(event, '/collections'),
                        }}
                    >
                        <Text variant="headingMd" as="h1">
                            {items.orders}
                        </Text>
                    </CalloutCard>
                </Grid.Cell>
            </Grid>
        </Page>
    );
}

export default Dashboard;
