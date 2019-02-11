<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model;

use Magento\Framework\Api\SearchCriteriaInterface;

use Iqmosaic\InventoryPrice\Model\InventoryPrice\Command\GetListBySkuInterface;
use Iqmosaic\InventoryPrice\Model\InventoryPrice\Command\GetListInterface;
use Iqmosaic\InventoryPrice\Model\InventoryPrice\Command\SaveMultiple;
use Iqmosaic\InventoryPrice\Model\InventoryPrice\Command\DeleteMultiple;


use Iqmosaic\InventoryPrice\Api\InventoryPriceRepositoryInterface;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceSearchResultsInterface;

/**
 * @inheritdoc
 */
class InventoryPriceRepository implements InventoryPriceRepositoryInterface
{

    /**
     * @var GetListBySkuInterface
     */
    private $commandGetListBySku;

    /**
     * @var GetListInterface
     */
    private $commandGetList;

    /**
     * @var SaveMultiple
     */
    private $commandSaveMultiple;

    /**
     * @var DeleteMultiple
     */
    private $commandDeleteMultiple;

    /**
     * InventoryPriceRepository constructor.
     * @param GetListBySkuInterface $commandGetListBySku
     * @param GetListInterface $commandGetList
     * @param SaveMultiple $commandSaveMultiple
     * @param DeleteMultiple $commandDeleteMultiple
     */
    public function __construct(
        GetListBySkuInterface $commandGetListBySku,
        GetListInterface $commandGetList,
        SaveMultiple $commandSaveMultiple,
        DeleteMultiple $commandDeleteMultiple
    ) {
        $this->commandGetListBySku = $commandGetListBySku;
        $this->commandGetList = $commandGetList;
        $this->commandSaveMultiple = $commandSaveMultiple;
        $this->commandDeleteMultiple = $commandDeleteMultiple;
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria = null): InventoryPriceSearchResultsInterface
    {
        return $this->commandGetList->execute($searchCriteria);
    }

    /**
     * @inheritdoc
     */
    public function getListBySku(string $sku): InventoryPriceSearchResultsInterface
    {
        return $this->commandGetListBySku->execute($sku);
    }

    /**
     * @inheritdoc
     */
    public function saveMultiple(array $priceItems): void
    {
        $this->commandSaveMultiple->execute($priceItems);
    }

    /**
     * @inheritdoc
     */
    public function deleteMultiple(array $priceItems): void
    {
        $this->commandDeleteMultiple->execute($priceItems);
    }
}
