<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model;

use Magento\Framework\Api\SearchResults;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceSearchResultsInterface;

/**
 * Class InventoryPriceSearchResults
 * @package Iqmosaic\InventoryPrice\Model
 */
class InventoryPriceSearchResults extends SearchResults implements InventoryPriceSearchResultsInterface
{
}
