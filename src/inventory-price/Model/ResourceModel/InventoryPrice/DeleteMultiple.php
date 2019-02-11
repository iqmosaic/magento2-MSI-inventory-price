<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice;

use Magento\Framework\App\ResourceConnection;
use Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice as InventoryPriceResourceModel;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceInterface;

/**
 * Class DeleteMultiple
 * @package Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice
 */
class DeleteMultiple
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * Multiple delete stock Inventory Price Items
     *
     * @param InventoryPriceInterface[] $priceItems
     * @return void
     */
    public function execute(array $priceItems)
    {
        if (!count($priceItems)) {
            return;
        }

        $connection = $this->resourceConnection->getConnection();
        $tableName = $this->resourceConnection->getTableName(
            InventoryPriceResourceModel::TABLE_NAME_INVENTORY_PRICE
        );

        $whereSql = $this->buildWhereSqlPart($priceItems);
        $connection->delete($tableName, $whereSql);
    }

    /**
     * Build WHERE part of the delete SQL query
     *
     * @param InventoryPriceInterface[] $links
     * @return string
     */
    private function buildWhereSqlPart(array $links): string
    {
        $connection = $this->resourceConnection->getConnection();

        $condition = [];

        foreach ($links as $link) {
            $sourceCodeCondition = $connection->quoteInto(
                InventoryPriceInterface::SOURCE_CODE . ' = ?',
                $link->getSourceCode()
            );
            $supplierIdCondition = $connection->quoteInto(
                InventoryPriceInterface::SKU . ' = ?',
                $link->getSku()
            );
            $condition[] = '(' . $sourceCodeCondition . ' AND ' . $supplierIdCondition . ')';
        }

        return implode(' OR ', $condition);
    }
}
