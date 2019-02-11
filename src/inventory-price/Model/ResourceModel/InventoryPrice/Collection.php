<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice as InventoryPriceResourceModel;
use Iqmosaic\InventoryPrice\Model\InventoryPrice as InventoryPriceModel;

/**
 * Resource Collection of SourceItemPrice entities
 *
 * @api
 */
class Collection extends AbstractCollection
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init(InventoryPriceModel::class, InventoryPriceResourceModel::class);
    }
}
