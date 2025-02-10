import {CalloutCard, Page, Text, Grid, LegacyCard} from '@shopify/polaris';
import React, {useEffect, useState} from 'react';
import {useNavigate} from "react-router-dom";

function Dashboard() {
    const [items, setItems] = useState([]);
    const [totalProducts, setTotalProducts] = useState(0);
    const [syncStatus, setSyncStatus] = useState('');
    const navigate = useNavigate();
    const handleNavigation = (event, type) => {
        event.preventDefault();
        navigate(type, { replace: true });
    };

    const getCounts = async () => {
        await axios.get('/api/dashboard').then((res) => {
            setItems(res.data.data)
        }).catch((err) => {
            console.log(err)
        })
    }

    const getProductSyncStatus = async () => {
        await axios.get('/api/sync-product-status').then((res) => {
            setTotalProducts(res.data?.data?.totalProducts);
            setSyncStatus(res.data?.data?.productSyncStatus);
            console.log(res.data, 'sync-product-status')
        }).catch((err) => {
            console.log(err)
        })
    }

    const startProductSync = async () => {
        const intervalId = setInterval(async () => {
            await getProductSyncStatus();
            if (syncStatus === 'end' || syncStatus === 'failed') {
                clearInterval(intervalId);
            }
        }, 10000);
    }

    return () => clearInterval(intervalId);

    useEffect( () => {
         getCounts();
         getProductSyncStatus();
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
                        title="Manual Collections"
                        illustration="/images/checklist.png"
                        primaryAction={{
                            content: 'Collections',
                            onAction: (event) => handleNavigation(event, '/collections'),
                        }}
                    >
                        <Text variant="headingMd" as="h1">
                            {items.collections}
                        </Text>
                    </CalloutCard>
                </Grid.Cell>
            </Grid>
        </Page>
    );
}

export default Dashboard;
