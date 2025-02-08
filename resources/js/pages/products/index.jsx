import {
    IndexTable,
    LegacyCard,
    Text,
    Badge,
    useBreakpoints,
    Page,
    Thumbnail, useSetIndexFiltersMode, Pagination, EmptyState, InlineStack
} from '@shopify/polaris';
import React, {useEffect, useState} from "react";

function Table() {
    const [items, setItems] = useState([])
    const [loading, setLoading] = useState([])
    const [currentPage, setCurrentPage] = useState(1);
    const [pageInfo, setPageInfo] = useState(null);

    const resourceName = {
        singular: 'Product',
        plural: 'Products',
    };

    const getProducts = async () => {
        setLoading(true)
        await axios.get('/api/products?page='+currentPage).then((res) => {
            console.log(res, 'res')
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
        getProducts();
    }, [])

    useEffect(() => {
        getProducts();  // Fetch the products after currentPage is updated
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
            {id, title, status, image_url, first_variant, supplier },
            index,
        ) => (
            <IndexTable.Row id={id} key={id + '-' + index} position={index}>
                <IndexTable.Cell>
                    <InlineStack wrap={false} blockAlign={"center"} gap="500">
                        <Thumbnail
                            source={image_url}
                            alt={title}
                        />
                        <Text variant="bodyMd" as="span">
                            {title}
                        </Text>
                    </InlineStack>
                </IndexTable.Cell>
                <IndexTable.Cell>{supplier}</IndexTable.Cell>
                <IndexTable.Cell>
                    <Badge tone={status === 'active' ? 'success' : ''}>
                        {status}
                    </Badge>
                </IndexTable.Cell>
                <IndexTable.Cell>{first_variant.price}</IndexTable.Cell>
            </IndexTable.Row>
        ),
    );

    const emptyStateMarkup = (
        <EmptyState
            heading={`No products available`}
        >
            <img src="/images/empty.jpg" alt="empty records" width={250}/>
        </EmptyState>
    );

    return (
        <Page
            title="Products"
            fullWidth
        >
            <LegacyCard>
                <IndexTable
                    condensed={useBreakpoints().smDown}
                    resourceName={resourceName}
                    itemCount={items.length}
                    headings={[
                        {title: 'Product'},
                        {title: 'Supplier'},
                        {title: 'Status'},
                        {title: 'Price'},
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
                        label={`${getStartIndex()}-${getEndIndex()} of ${pageInfo?.total || 0} products`}
                    /> : ''
                }
            </LegacyCard>
        </Page>
    );
}

export default Table;
