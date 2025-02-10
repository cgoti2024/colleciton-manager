import {
    IndexTable,
    LegacyCard,
    Text,
    Badge,
    useBreakpoints,
    Page,
    Thumbnail, useSetIndexFiltersMode, Pagination, EmptyState, InlineStack,useIndexResourceState,TextField,Icon,Button,Select
} from '@shopify/polaris';
import React, {useEffect, useState,useCallback} from "react";
import {
    SearchIcon
} from '@shopify/polaris-icons';

function Table() {
    const [items, setItems] = useState([])
    const [loading, setLoading] = useState([])
    const [currentPage, setCurrentPage] = useState(1);
    const [pageInfo, setPageInfo] = useState(null);
    const [textFieldValue, setTextFieldValue] = useState(null);
    const [selected, setSelected] = useState('newestUpdate');

    const handleSelectChange = (value)=> {
        setSelected(value)
    };
    const handleTextFieldChange = (value)=>{
        setTextFieldValue(value)
    }

    const options = [
        {label: 'Title', value: 'title'},
        {label: 'Supplier', value: 'supplier'},
        {label: 'Tags', value: 'tags'},
        {label: 'All', value: 'all'},
    ];

    const resourceName = {
        singular: 'Product',
        plural: 'Products',
    };

    const getProducts = async () => {
        setLoading(true)
        await axios.get('/api/products?page='+currentPage+'&search='+textFieldValue+'&type='+selected).then((res) => {
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
    }, [currentPage],textFieldValue);

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

    const {selectedResources, allResourcesSelected, handleSelectionChange} =
        useIndexResourceState(items);
    const promotedBulkActions = [
        {
            content: 'Selected items',
            onAction: () => console.log(items),
        }
    ];

    const handleClearButtonClick = useCallback(() => setTextFieldValue(''), []);

    const handleSearchClick = () => {
        getProducts();
    };

    const rowMarkup = items.map(
        (
            {id, title, status, image_url, first_variant, supplier },
            index,
        ) => (
            <IndexTable.Row id={id} key={id} selected={selectedResources.includes(id)} position={index}>
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
                <div  style={{"padding":"10px"}}>

                    <TextField
                        label=""
                        type="text"
                        value={textFieldValue}
                        onChange={handleTextFieldChange}
                        prefix={<Icon source={SearchIcon} tone="base" />}
                        autoComplete="off"
                        placeholder="Search Item"
                        clearButton
                        onClearButtonClick={handleClearButtonClick}
                        connectedLeft={<Select
                                            label="Search by"
                                            labelInline
                                            options={options}
                                            onChange={handleSelectChange}
                                            value={selected}
                                        />}
                        connectedRight={<Button onClick={handleSearchClick}>Search</Button>}
                    />

                </div>
                <IndexTable
                    condensed={useBreakpoints().smDown}
                    resourceName={resourceName}
                    itemCount={items.length}
                    selectedItemsCount={
                        allResourcesSelected ? 'All' : selectedResources.length
                    }
                    onSelectionChange={handleSelectionChange}
                    promotedBulkActions={promotedBulkActions}
                    hasMoreItems
                    headings={[
                        {title: 'Product'},
                        {title: 'Supplier'},
                        {title: 'Status'},
                        {title: 'Price'},
                    ]}
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
