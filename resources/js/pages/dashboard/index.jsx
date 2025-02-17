import {CalloutCard, Page, Text, Grid, LegacyCard,ProgressBar} from '@shopify/polaris';
import React, {useEffect, useState} from 'react';
import {useNavigate} from "react-router-dom";

function Dashboard() {
    const [items, setItems] = useState([]);
    const [totalProducts, setTotalProducts] = useState(0);
    const [syncStatus, setSyncStatus] = useState('');
    const [syncedProducts, setSyncedProducts] = useState(0);
    const [syncPercentage, setSyncPercentage] = useState(0);
    const [isSyncing, setIsSyncing] = useState(false);
    const navigate = useNavigate();
    const handleNavigation = (event, type) => {
        event.preventDefault();
        navigate(type, { replace: true });
    };

    const getCounts = async () => {
        await axios.get('/api/dashboard').then(async (res) => {
            setItems(res.data.data)
            setTotalProducts(res.data?.data?.totalProducts);
            setSyncStatus(res.data?.data?.productSyncStatus);
            setSyncedProducts(res.data?.data?.syncedProducts);
            await getProductSyncStatus();
            if (res.data?.data?.productSyncStatus === 'start') {
                await startProductSync();
            }
        }).catch((err) => {
            console.log(err)
        })
    }

    const getProductSyncStatus = async () => {
        await axios.get('/api/sync-product-status').then((res) => {
            setTotalProducts(res.data?.data?.totalProducts);
            setSyncStatus(res.data?.data?.productSyncStatus);
            setSyncedProducts(res.data?.data?.syncedProducts);
            let syncPd = parseInt(res.data?.data?.syncedProducts)
            let totalPd = parseInt(res.data?.data?.totalProducts)
            if (totalPd > 0) {
                setSyncPercentage(syncPd < totalPd ? ((syncPd / totalPd) * 100) : 100);
            } else {
                setSyncPercentage(0);
            }
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

    useEffect( () => {
         getCounts();
    }, [])

    const handleSyncClick = async () => {
        setSyncPercentage(1);
        await axios.get('/api/sync-product').then((res) => {
            setTotalProducts(res.data?.data?.totalProducts);
            setSyncStatus(res.data?.data?.productSyncStatus);
            setSyncedProducts(res.data?.data?.syncedProducts);
            if (res.data?.data?.totalProducts) {
                getProductSyncStatus();
                startProductSync();
            } else {
                setSyncPercentage(1);
            }
        }).catch((err) => {
            console.log(err)
        })
    };
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
                            {syncedProducts > items.products ? syncedProducts : items.products}
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
                <Grid.Cell columnSpan={{xs: 6, sm: 6, md: 6, lg: 12, xl: 12}}>
                    <CalloutCard
                        title="Sync All Products"
                        illustration="https://cdn.shopify.com/s/assets/admin/checkout/settings-customizecart-705f57c725ac05be5a34ec20c05b94298cb8afd10aac7bd9c7ad02030f48cfa0.svg"
                        primaryAction={{
                            content: 'Sync Products',
                            onAction: handleSyncClick,
                            disabled: syncStatus === 'end' || syncStatus === 'start' || parseInt(syncPercentage) > 0
                        }}
                    >
                        { parseInt(syncedProducts) > 0 && <p style={{margin: "8px 0"}}>Synced products: <b>{syncedProducts}</b></p> }
                        { parseInt(syncPercentage) > 0 && <ProgressBar style={{margin: "6px 0"}} progress={syncPercentage} tone="success" size="small" /> }
                    </CalloutCard>
                </Grid.Cell>
            </Grid>
        </Page>
    );
}

export default Dashboard;
