import {
    IndexTable,
    LegacyCard,
    Text,
    Badge,
    useBreakpoints,
    Page,
    Thumbnail,
    useSetIndexFiltersMode,
    Pagination,
    EmptyState,
    InlineStack,
    useIndexResourceState,
    TextField,
    Icon,
    Button,
    Select,
    Modal,
    TextContainer,
    Layout,
    Card,
    BlockStack,
    Divider,
    Grid,
    Box
} from '@shopify/polaris';
import React, {useEffect, useState, useCallback} from "react";
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
    const [active, setActive] = useState(false);
    const [newCollection, setNewCollection] = useState({
        title: '',
        description: '',
        image: '',
        products: []
    });

    const handleChange = () => {
        setActive(!active);
        handleSelectedProducts()
    };

    const handleSelectChange = (value) => {
        setSelected(value)
    };
    const handleTextFieldChange = (value) => {
        setTextFieldValue(value)
    }

    const options = [
        { label: 'Title', value: 'title' },
        { label: 'Supplier', value: 'supplier' },
        { label: 'Tags', value: 'tags' },
        { label: 'All', value: 'all' },
    ];

    const resourceName = {
        singular: 'Product',
        plural: 'Products',
    };

    const getProducts = async () => {
        setLoading(true)
        await axios.get('/api/products?page=' + currentPage + '&search=' + textFieldValue + '&type=' + selected).then((res) => {
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
    }, [currentPage], textFieldValue);

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

    const { selectedResources, allResourcesSelected, handleSelectionChange } =
        useIndexResourceState(items);


    const handleSelectedProducts = () => {
        const selectedProducts = items.filter((item) => selectedResources.includes(item.id));
        setNewCollection((prev) => {
            const existingIds = new Set(prev.products.map((product) => product.id));
            const newUniqueProducts = selectedProducts.filter((product) => !existingIds.has(product.id));
            return {
                ...prev,
                products: [...newUniqueProducts],
            };
        });

    };

    const handleClearButtonClick = useCallback(() => setTextFieldValue(''), []);

    const handleSearchClick = () => {
        getProducts();
    };

    const handleCollectionChange = (key, value) => {
        setNewCollection((prev) => ({
            ...prev,
            [key]: value
        }));
    };

    const handleCollectionCreate = () => {
        console.log(newCollection,"chirag")
        setActive(false)
    };


    const rowMarkup = items.map(
        (
            { id, title, status, image_url, first_variant, supplier },
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
            <div style={{margin:"15px 0"}}>
                <Card>
                    <Grid>
                        <Grid.Cell columnSpan={{ xs: 6, sm: 6, md: 6, lg: 8, xl: 8 }}>
                            <InlineStack wrap={false}>
                                <div style={{ borderInlineEnd: '1px solid #dbdbdb' }}>
                                    <Box paddingInlineEnd="400">
                                        <Text variant="headingXs">Products by sell-through rate</Text>
                                        <Text tone="subdued">0% —</Text>
                                    </Box>
                                </div>
                                <div style={{ borderInlineEnd: '1px solid #dbdbdb' }}>
                                    <Box paddingInlineEnd="400" paddingInlineStart="400">
                                        <Text variant="headingXs">Products by days of inventory remaining</Text>
                                        <Text tone="subdued">No data</Text>
                                    </Box>
                                </div>
                                <div>
                                    <Box paddingInlineEnd="400" paddingInlineStart="400">
                                        <Text variant="headingXs">ABC product analysis</Text>
                                        <Text tone="subdued">No data</Text>
                                    </Box>
                                </div>
                            </InlineStack>
                        </Grid.Cell>
                        <Grid.Cell columnSpan={{ xs: 6, sm: 6, md: 6, lg: 4, xl: 4 }}>
                            <div style={{ display: "flex", justifyContent: "end",alignItems:"center",height:"100%" }}>
                                <Button onClick={handleChange}>Create Manual Collection</Button>
                            </div>
                        </Grid.Cell>

                    </Grid>
                </Card>
            </div>

            <Modal
                open={active}
                onClose={handleChange}
                primaryAction={{
                    content: 'Create Manual Collection',
                    onAction: handleCollectionCreate,
                }}
                title="Create Manual Collection"
            >
                <Modal.Section>
                    <TextField
                        label="Title"
                        value={newCollection.title}
                        type="text"
                        onChange={(value) => handleCollectionChange('title', value)}
                        autoComplete="off"
                    />
                    <TextField
                        label="Description"
                        type="text"
                        value={newCollection.description}
                        onChange={(value) => handleCollectionChange('description',value)}
                        multiline={4}
                        autoComplete="off"
                    />

                    <div style={{"marginTop":"10px"}}>
                        <Layout>
                            <Layout.Section variant="oneThird">
                                <LegacyCard title="Selected Products" sectioned>
                                    <p>{newCollection.products.length ? newCollection.products.length :  "No Product Select"}</p>
                                </LegacyCard>
                            </Layout.Section>
                        </Layout>
                    </div>


                </Modal.Section>
            </Modal>
            <LegacyCard>
                <div style={{ "padding": "10px" }}>

                    <TextField
                        label=""
                        type="text"
                        value={textFieldValue}
                        onChange={handleTextFieldChange}
                        prefix={<Icon source={SearchIcon} tone="base"/>}
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
                    hasMoreItems
                    headings={[
                        { title: 'Product' },
                        { title: 'Supplier' },
                        { title: 'Status' },
                        { title: 'Price' },
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
