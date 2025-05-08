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
        singular: 'Theme',
        plural: 'Themes',
    };

    const getProducts = async () => {
        setLoading(true)
        await axios.get('/api/themes?page='+currentPage).then((res) => {
            console.log(res, 'res')
            setItems(res.data.data)
            setCurrentPage(res.data.meta.current_page);
            setPageInfo(res.data.meta);
        }).catch((err) => {
            console.log(err.response)
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
            {id, name, role, has_file },
            index,
        ) => (
            <IndexTable.Row id={id} key={id + '-' + index} position={index}>
                <IndexTable.Cell>
                    <Text variant="bodyMd" as="span">
                        {name}
                    </Text>
                </IndexTable.Cell>
                <IndexTable.Cell>
                    <Badge tone={role === 'main' ? 'success' : ''}>
                        {role}
                    </Badge>
                </IndexTable.Cell>
                <IndexTable.Cell>
                    <Badge tone={has_file === 'active' ? 'success' : ''}>
                        {has_file ? 'File added' : 'NA'}
                    </Badge>
                </IndexTable.Cell>
            </IndexTable.Row>
        ),
    );

    const emptyStateMarkup = (
        <EmptyState
            heading={`No themes available`}
        >
            <img src="/images/empty.jpg" alt="empty records" width={250}/>
        </EmptyState>
    );

    return (
        <Page
            title="Themes"
            fullWidth
        >
            <LegacyCard>
                <IndexTable
                    condensed={useBreakpoints().smDown}
                    resourceName={resourceName}
                    itemCount={items.length}
                    headings={[
                        {title: 'Theme'},
                        {title: 'Role'},
                        {title: 'Has file?'}
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
