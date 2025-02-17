import {
    IndexTable,
    LegacyCard,
    Text,
    Badge,
    useBreakpoints,
    Page,
    Thumbnail, useSetIndexFiltersMode, Pagination, EmptyState
} from '@shopify/polaris';
import React, {useEffect, useState} from "react";
import moment from 'moment';

function Table() {
    const [items, setItems] = useState([])
    const [loading, setLoading] = useState([])
    const [currentPage, setCurrentPage] = useState(1);
    const [pageInfo, setPageInfo] = useState(null);

    const resourceName = {
        singular: 'Collection',
        plural: 'Collections',
    };

    const getOrders = async () => {
        setLoading(true)
        await axios.get('/api/collections?page='+currentPage).then((res) => {
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
        getOrders();
    }, [])

    useEffect(() => {
        getOrders();
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

    const financialTone = (status) => {
        switch (status) {
            case 'paid': return 'success'
            default: return ''
        }
    }

    const fulfillmentTone = (status) => {
        switch (status) {
            case 'fulfilled': return 'success'
            default: return ''
        }
    }

    const rowMarkup = items.map(
        (
            {id, published_at,title},
            index,
        ) => (
            <IndexTable.Row id={id} key={id + '-' + index} position={index}>
                <IndexTable.Cell>
                    <Text variant="bodyMd" fontWeight="bold" as="span" >
                        {title}
                    </Text>
                </IndexTable.Cell>
                <IndexTable.Cell alignment="end">
                    {moment(published_at).format('MMM DD YYYY')}
                </IndexTable.Cell>
            </IndexTable.Row>
        ),
    );

    const emptyStateMarkup = (
        <EmptyState
            heading={`No collections available`}
        >
            <img src="/images/empty.jpg" alt="empty records" width={250}/>
        </EmptyState>
    );

    return (
        <Page
            title="Manual Collections"
            fullWidth
        >
            <LegacyCard>
                <IndexTable
                    condensed={useBreakpoints().smDown}
                    resourceName={resourceName}
                    itemCount={items.length}
                    headings={[
                        {title: 'Title'},
                        {title: 'Published Date'},
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
                        label={`${getStartIndex()}-${getEndIndex()} of ${pageInfo?.total || 0} collections`}
                    /> : ''
                }
            </LegacyCard>
        </Page>
    );
}

export default Table;
