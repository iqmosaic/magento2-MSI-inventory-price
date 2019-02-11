<?php
declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Api;

/**
 * Interface InventoryPriceRepositoryInterface
 * @package Iqmosaic\InventoryPrice\Api
 */
interface InventoryPriceRepositoryInterface
{

    /**
     * @param \Iqmosaic\InventoryPrice\Api\Data\InventoryPriceInterface[] $priceItems
     * @return void
     */
    public function saveMultiple(
        array $priceItems
    ): void;

    /**
     * @param \Iqmosaic\InventoryPrice\Api\Data\InventoryPriceInterface[] $priceItems
     * @return void
     */
    public function deleteMultiple(
        array $priceItems
    ): void;

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface|null $searchCriteria
     * @return \Iqmosaic\InventoryPrice\Api\Data\InventoryPriceSearchResultsInterface
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria = null
    ): Data\InventoryPriceSearchResultsInterface;

    /**
     * @param string $sku
     * @return \Iqmosaic\InventoryPrice\Api\Data\InventoryPriceSearchResultsInterface
     */
    public function getListBySku(
        string $sku
    ): Data\InventoryPriceSearchResultsInterface;
}