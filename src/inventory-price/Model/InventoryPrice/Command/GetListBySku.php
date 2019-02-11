<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model\InventoryPrice\Command;

use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice\Collection;
use Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice\CollectionFactory;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceInterface;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceSearchResultsInterface;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceSearchResultsInterfaceFactory;
use Magento\Inventory\Model\ResourceModel\Source AS SourceResourceModel;


/**
 * Class GetListBySku
 * @package Iqmosaic\InventoryPrice\Model\InventoryPrice\Command
 */
class GetListBySku implements GetListBySkuInterface
{
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var CollectionFactory
     */
    private $inventoryPriceCollectionFactory;

    /**
     * @var InventoryPriceSearchResultsInterface
     */
    private $inventoryPriceSearchResultsInterfaceFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * GetListBySku constructor.
     * @param CollectionProcessorInterface $collectionProcessor
     * @param CollectionFactory $inventoryPriceCollectionFactory
     * @param InventoryPriceSearchResultsInterfaceFactory $inventoryPriceSearchResultsInterfaceFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $inventoryPriceCollectionFactory,
        InventoryPriceSearchResultsInterfaceFactory $inventoryPriceSearchResultsInterfaceFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->collectionProcessor = $collectionProcessor;
        $this->inventoryPriceCollectionFactory = $inventoryPriceCollectionFactory;
        $this->inventoryPriceSearchResultsInterfaceFactory = $inventoryPriceSearchResultsInterfaceFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     */
    public function execute(string $sku): InventoryPriceSearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->inventoryPriceCollectionFactory->create();

        $collection->getSelect()
            ->joinLeft(
                ['sc' => SourceResourceModel::TABLE_NAME_SOURCE],
                'main_table.source_code = sc.source_code',
                ['name']
            );

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(InventoryPriceInterface::SKU, $sku)
            ->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResult = $this->inventoryPriceSearchResultsInterfaceFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
