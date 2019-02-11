<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model\InventoryPrice;

use Iqmosaic\InventoryPrice\Api\InventoryPriceRepositoryInterface;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceInterface;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class SourceItemPriceProcessor
 * @package Iqmosaic\InventoryPrice\Model\InventoryPrice
 */
class SourceItemPriceProcessor
{
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;

    /**
     * @var InventoryPriceRepositoryInterface
     */
    private $inventoryPriceRepository;

    /**
     * @var InventoryPriceInterfaceFactory
     */
    private $inventoryPriceInterfaceFactory;


    /**
     * SourceItemPriceProcessor constructor.
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param InventoryPriceRepositoryInterface $inventoryPriceRepository
     * @param InventoryPriceInterfaceFactory $inventoryPriceInterfaceFactory
     * @param DataObjectHelper $dataObjectHelper$inventoryPriceInterfaceFactory
     */
    public function __construct(
        SearchCriteriaBuilder $searchCriteriaBuilder,
        InventoryPriceRepositoryInterface $inventoryPriceRepository,
        InventoryPriceInterfaceFactory $inventoryPriceInterfaceFactory,
        DataObjectHelper $dataObjectHelper
    ) {
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->inventoryPriceRepository = $inventoryPriceRepository;
        $this->inventoryPriceInterfaceFactory = $inventoryPriceInterfaceFactory;
        $this->dataObjectHelper = $dataObjectHelper;
    }

    /**
     * @param string $sku
     * @param array $priceItems
     */
    public function process(string $sku, array $priceItems)
    {
        $pricesForDelete = $this->getAssignedLinks($sku);
        $pricesForSave = [];
        foreach ($priceItems as $priceItem) {

            if ( (float) $priceItem[InventoryPriceInterface::PRICE] <= 0) {
                continue;
            }

            $sourceCode = $priceItem[InventoryPriceInterface::SOURCE_CODE];

            if (isset($pricesForDelete[$sourceCode])) {
                $price = $pricesForDelete[$sourceCode];
            } else {
                /** @var @var InventoryPriceInterface  */
                $price = $this->inventoryPriceInterfaceFactory->create();
            }

            $priceItem[InventoryPriceInterface::SKU] = $sku;

            $this->dataObjectHelper->populateWithArray($price, $priceItem, InventoryPriceInterface::class);

            $pricesForSave[] = $price;
            unset($pricesForDelete[$sourceCode]);
        }

        if (count($pricesForSave) > 0) {
            $this->inventoryPriceRepository->saveMultiple($pricesForSave);
        }

        if (count($pricesForDelete) > 0) {
            $this->inventoryPriceRepository->deleteMultiple($pricesForDelete);
        }
    }

    /**
     * @param string $sku
     * @return array
     */
    private function getAssignedLinks(string $sku): array
    {
        $result = [];
        foreach ($this->inventoryPriceRepository->getListBySku($sku)->getItems() as $priceItem) {
            $result[$priceItem->getSourceCode()] = $priceItem;
        }

        return $result;
    }
}
