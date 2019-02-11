<?php
declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model\InventoryPrice\Command;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice\Collection;
use Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice\CollectionFactory;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceSearchResultsInterface;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceSearchResultsInterfaceFactory;



/**
 * @inheritdoc
 */
class GetList implements GetListInterface
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
    public function execute(SearchCriteriaInterface $searchCriteria = null): InventoryPriceSearchResultsInterface
    {
        /** @var Collection $collection */
        $collection = $this->inventoryPriceCollectionFactory->create();

        $this->collectionProcessor->process($searchCriteria, $collection);

        $searchResult = $this->inventoryPriceSearchResultsInterfaceFactory->create();
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
