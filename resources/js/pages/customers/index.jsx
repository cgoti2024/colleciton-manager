import {
    IndexTable,
    LegacyCard,
    Text,
    useBreakpoints,
    Page, Pagination, EmptyState
} from '@shopify/polaris';
import React, {useEffect, useState} from "react";

function Customer() {
    const [items, setItems] = useState([])
    const [loading, setLoading] = useState([])
    const [currentPage, setCurrentPage] = useState(1);
    const [pageInfo, setPageInfo] = useState(null);

    const resourceName = {
        singular: 'Customer',
        plural: 'Customers',
    };

    const getCustomer = async () => {
        setLoading(true)
        await axios.get('/api/customers?page='+currentPage).then((res) => {
            setItems(res.data.data)
            setCurrentPage(res.data.meta.current_page);
            setPageInfo(res.data.meta);
        }).catch((err) => {
            console.log(err)
        }).finally(() => {
            setLoading(false)
        })
    }

    useEffect(() => {
        getCustomer();
    }, [])

    useEffect(() => {
        getCustomer();
    }, [currentPage]);

    const handlePrevPage = () => {
        if (currentPage > 1) {
            setCurrentPage(currentPage - 1);
        }
    }

    const handleNextPage = () => {
        if (currentPage < pageInfo.last_page) {
            setCurrentPage(currentPage + 1);
        }
    }

    const getStartIndex = () => {
        return (currentPage - 1) * (pageInfo?.per_page || 0) + 1;
    };

    const getEndIndex = () => {
        const endIndex = currentPage * (pageInfo?.per_page || 0);
        return endIndex > (pageInfo?.total || 0) ? (pageInfo?.total || 0) : endIndex;
    };

    const rowMarkup = items.map(
        (
            {id, first_name, last_name, email, phone, orders_count},
            index,
        ) => (
            <IndexTable.Row id={id} key={id + '-' + index} position={index}>
                <IndexTable.Cell>
                    <Text variant="bodyMd" fontWeight="bold" as="span">
                        {first_name} {last_name}
                    </Text>
                </IndexTable.Cell>
                <IndexTable.Cell>
                    {email}
                </IndexTable.Cell>
                <IndexTable.Cell>
                    {phone}
                </IndexTable.Cell>
                <IndexTable.Cell>
                    {orders_count}
                </IndexTable.Cell>
            </IndexTable.Row>
        ),
    );

    const emptyStateMarkup = (
        <EmptyState
            heading={`No customer available`}
        >
            <img src="/images/empty.jpg" alt="empty records" width={250}/>
        </EmptyState>
    );

    return (
        <Page
            title="Customers"
            fullWidth
        >
            <LegacyCard>
                <IndexTable
                    condensed={useBreakpoints().smDown}
                    resourceName={resourceName}
                    itemCount={items.length}
                    headings={[
                        {title: 'Customer'},
                        {title: 'Email'},
                        {title: 'Phone'},
                        {title: 'Order count'},
                    ]}
                    selectable={false}
                    emptyState={!loading && emptyStateMarkup}
                >
                    {rowMarkup}
                </IndexTable>

                {
                    items.length ?
                    <Pagination
                        onPrevious={handlePrevPage}
                        onNext={handleNextPage}
                        type="table"
                        hasNext={currentPage < pageInfo?.last_page}
                        hasPrevious={currentPage > 1}
                        label={`${getStartIndex()}-${getEndIndex()} of ${pageInfo?.total || 0} customers`}
                    /> : ''
                }
            </LegacyCard>
        </Page>
    );
}

export default Customer;
