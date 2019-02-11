<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model\InventoryPrice\Command;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceSearchResultsInterface;

/**
 * Interface GetListBySkuInterface
 * @package Iqmosaic\InventoryPrice\Model\InventoryPrice\Command
 */
interface GetListBySkuInterface
{
    /**
     * @param string $sku
     * @return InventoryPriceSearchResultsInterface
     */
    public function execute(string $sku): InventoryPriceSearchResultsInterface;
}
