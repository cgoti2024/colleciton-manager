import {Page, MediaCard, Grid, CalloutCard, Text} from '@shopify/polaris';
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
                <Grid.Cell columnSpan={{xs: 12, sm: 12, md: 8, lg: 8, xl: 8}}>
                    <MediaCard
                        title="Theme content management"
                        description="Discover how theme customization can power up your entrepreneurial journey."
                        size="small"
                    >
                        <img
                            alt=""
                            width="100%"
                            height="100%"
                            style={{
                                objectFit: 'cover',
                                objectPosition: 'center',
                            }}
                            src="https://burst.shopifycdn.com/photos/business-woman-smiling-in-office.jpg?width=1850"
                        />
                    </MediaCard>
                </Grid.Cell>
                <Grid.Cell columnSpan={{xs: 12, sm: 12, md: 4, lg: 4, xl: 4}}>
                    <CalloutCard
                        title="Total Themes"
                        illustration="/images/box.png"
                        primaryAction={{
                            content: 'Themes',
                            onAction: (event) => handleNavigation(event, '/themes'),
                        }}
                    >
                        <Text variant="headingMd" as="h1">
                            {items.themes}
                        </Text>
                    </CalloutCard>
                </Grid.Cell>
            </Grid>

        </Page>
    );
}

export default Dashboard;
