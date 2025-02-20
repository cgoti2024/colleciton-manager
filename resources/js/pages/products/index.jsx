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
    Box,
    Toast,
    Frame,
    InlineError
} from '@shopify/polaris';
import React, {useEffect, useState, useCallback} from "react";
import {
    SearchIcon
} from '@shopify/polaris-icons';

function Table() {
    const [items, setItems] = useState([])
    const [loading, setLoading] = useState([])
    const [currentPage, setCurrentPage] = useState(1);
    const [totalProductCount, setTotalProductCount] = useState(0);
    const [pageInfo, setPageInfo] = useState(null);
    const [textFieldValue, setTextFieldValue] = useState('');
    const [selected, setSelected] = useState('all');
    const [active, setActive] = useState(false);
    const [isCreating, setIsCreating] = useState(false);
    const [toastActive, setToastActive] = useState(false);
    const [totalProductConfirm, setTotalProductConfirm] = useState(false);
    const [toggleToastMessage, setToggleToastMessage] = useState(false);
    const { selectedResources, allResourcesSelected, handleSelectionChange } = useIndexResourceState(items);

    const [newCollection, setNewCollection] = useState({
        title: '',
        description: '',
        products: []
    });

    const handleChange = () => {
        const selectedProducts = items.filter((item) => selectedResources.includes(item.id));
        if (selectedProducts.length === 0) {
            setToastActive(true);
        } else {
            setActive(!active);
            setTotalProductConfirm(false)
            handleSelectedProducts();
        }
    };

    const handleSelectChange = (value) => {
        setSelected(value)
    };
    const handleTextFieldChange = (value) => {
        setTextFieldValue(value)
    }

    const options = [
        { label: 'Title', value: 'title' },
        { label: 'SKU', value: 'sku' },
        { label: 'Supplier', value: 'supplier' },
        { label: 'Tags', value: 'tags' },
        { label: 'Product Type', value: 'product_type' },
        { label: 'Metafields', value: 'metafields' },
        { label: 'All', value: 'all' },
    ];

    const resourceName = {
        singular: 'Product',
        plural: 'Products',
    };

    const getProducts = async (page = '') => {
        setLoading(true)
        let CurrPage = page || currentPage;
        await axios.get('/api/products?page=' + CurrPage + '&search=' + textFieldValue + '&type=' + selected).then((res) => {
            console.log(res, 'res')
            setItems(res.data.data)
            setCurrentPage(res.data.meta.current_page);
            setPageInfo(res.data.meta);
            setTotalProductCount(res.data.meta.total)
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
        getProducts();
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
        getProducts(1);
    };

    const handleCollectionChange = (key, value) => {
        setNewCollection((prev) => ({
            ...prev,
            [key]: value
        }));
    };

    const handleCollectionCreate = async () => {
        setIsCreating(true);
        let params = {
            'title' : newCollection?.title,
            'description' : newCollection?.description,
            'products': newCollection?.products?.map(v => v.shopify_product_id),
            'allSelected': allResourcesSelected ? 1 : 0
        };
        if (allResourcesSelected) {
            params['query'] = textFieldValue;
            params['type'] = selected;
        }
        try {
            const res = await axios.post('/api/create-collections', params);
            setToggleToastMessage({ message: 'Collection created successfully!', error: false });
        } catch (err) {
            console.log(err.response.data.message);
            setToggleToastMessage({ message: err.response.data.message, error: true });
        } finally {
            setActive(false);
            setIsCreating(false);
        }
    };
    const toastMarkup = toastActive ? (
        <Toast content="Please select a product" error onDismiss={() => setToastActive(false)} duration={3000}/>
    ) : null;

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
                    <Badge tone={status === 'active' || status === 'ACTIVE' ? 'success' : ''}>
                        {status.replace('active', 'ACTIVE')}
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
        <Frame>

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
                                        <Text variant="headingXs">Step 1</Text>
                                        <Text tone="subdued">Select products after search</Text>
                                    </Box>
                                </div>
                                <div style={{ borderInlineEnd: '1px solid #dbdbdb' }}>
                                    <Box paddingInlineEnd="400" paddingInlineStart="400">
                                        <Text variant="headingXs">Step 2</Text>
                                        <Text tone="subdued">Open manual collection modal by clicking button.</Text>
                                    </Box>
                                </div>
                                <div>
                                    <Box paddingInlineEnd="400" paddingInlineStart="400">
                                        <Text variant="headingXs">Step 3</Text>
                                        <Text tone="subdued">Enter title and description and hit create button.</Text>
                                    </Box>
                                </div>
                            </InlineStack>
                        </Grid.Cell>
                        <Grid.Cell columnSpan={{ xs: 6, sm: 6, md: 6, lg: 4, xl: 4 }}>
                            <div style={{ display: "flex", justifyContent: "end",alignItems:"center",height:"100%" }}>
                                <Button onClick={handleChange}>Create Manual Collection</Button>
                                {toastMarkup}
                                {toggleToastMessage && (
                                    <Toast
                                        content={toggleToastMessage.message}
                                        error={toggleToastMessage.error}
                                        onDismiss={() => setToggleToastMessage(null)}
                                        duration={3000}
                                    />
                                )}
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
                    disabled:!totalProductConfirm,
                    loading:isCreating
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

                    <div style={{ "marginTop": "10px" }}>
                        <Layout>
                            <Layout.Section variant="oneThird">
                                <LegacyCard title="Selected Products" sectioned>
                                    <div style={{display:"flex",justifyContent:"space-between",alignItems:"center"}}>
                                        <p>
                                            {
                                                allResourcesSelected
                                                    ? `${totalProductCount} products selected`
                                                    : newCollection.products.length
                                                    ? `${newCollection.products.length} ${newCollection.products.length > 1 ? 'products' : 'product'} selected`
                                                    : "No products selected"
                                            }
                                        </p>
                                        <Button onClick={() => setTotalProductConfirm(!totalProductConfirm)} disabled={totalProductConfirm}>{totalProductConfirm ? "Confirmed" : "Confirm"}</Button>
                                    </div>
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
                        helpText="To search in multiple tags or queries at once, use a comma-separated query."
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
        </Frame>
    );
}

export default Table;
