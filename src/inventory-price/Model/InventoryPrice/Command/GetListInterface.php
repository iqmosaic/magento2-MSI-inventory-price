<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model\InventoryPrice\Command;

use Magento\Framework\Api\SearchCriteriaInterface;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceSearchResultsInterface;

/**
 * Interface GetListInterface
 * @package Iqmosaic\InventoryPrice\Model\InventoryPrice\Command
 */
interface GetListInterface
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return InventoryPriceSearchResultsInterface
     */
    public function execute(SearchCriteriaInterface $searchCriteria): InventoryPriceSearchResultsInterface;
}
