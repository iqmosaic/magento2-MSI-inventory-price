<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice;

use Magento\Framework\App\ResourceConnection;

use Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice as InventoryPriceResourceModel;
use Iqmosaic\SupplierApi\Api\Data\InventoryPriceInterface;
use Iqmosaic\InventoryPrice\Model\InventoryPrice;

/**
 * Class SaveMultiple
 * @package Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice
 */
class SaveMultiple
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
     * Multiple save InventoryPrice
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

        $columnsSql = $this->buildColumnsSqlPart([
            InventoryPrice::SOURCE_CODE,
            InventoryPrice::SKU,
            InventoryPrice::PRICE,
        ]);
        $valuesSql = $this->buildValuesSqlPart($priceItems);

        $onDuplicateSql = $this->buildOnDuplicateSqlPart([
            InventoryPrice::PRICE
        ]);

        $bind = $this->getSqlBindData($priceItems);

        $insertSql = sprintf(
            'INSERT INTO %s (%s) VALUES %s %s',
            $tableName,
            $columnsSql,
            $valuesSql,
            $onDuplicateSql
        );
        $connection->query($insertSql, $bind);
    }

    /**
     * @param array $columns
     * @return string
     */
    private function buildColumnsSqlPart(array $columns): string
    {
        $connection = $this->resourceConnection->getConnection();
        $processedColumns = array_map([$connection, 'quoteIdentifier'], $columns);
        return implode(', ', $processedColumns);
    }

    /**
     * @param InventoryPriceInterface[] $priceItems
     * @return string
     */
    private function buildValuesSqlPart(array $priceItems): string
    {
        $sql = rtrim(str_repeat('(?, ?, ?), ', count($priceItems)), ', ');
        return $sql;
    }

    /**
     * @param InventoryPriceInterface[] $priceItems
     * @return array
     */
    private function getSqlBindData(array $priceItems): array
    {
        $bind = [];
        foreach ($priceItems as $priceItem) {
            $bind = array_merge($bind, [
                $priceItem->getSourceCode(),
                $priceItem->getSku(),
                $priceItem->getPrice()
            ]);
        }
        return $bind;
    }

    /**
     * @param array $fields
     * @return string
     */
    private function buildOnDuplicateSqlPart(array $fields): string
    {
        $connection = $this->resourceConnection->getConnection();
        $processedFields = [];

        foreach ($fields as $field) {
            $processedFields[] = sprintf('%1$s = VALUES(%1$s)', $connection->quoteIdentifier($field));
        }
        return 'ON DUPLICATE KEY UPDATE ' . implode(', ', $processedFields);
    }
}
