<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\InventoryCatalogApi\Model\IsSingleSourceModeInterface;
use Magento\InventoryConfigurationApi\Model\IsSourceItemManagementAllowedForProductTypeInterface;
use Magento\InventoryCatalogAdminUi\Model\GetSourceItemsDataBySku;
use Iqmosaic\InventoryPrice\Model\InventoryPrice\Command\GetListBySkuInterface;

/**
 * Product form modifier. Add to form price data
 */
class SourcePriceItems extends AbstractModifier
{
    /**
     * @var IsSourceItemManagementAllowedForProductTypeInterface
     */
    private $isSourceItemManagementAllowedForProductType;

    /**
     * @var IsSingleSourceModeInterface
     */
    private $isSingleSourceMode;

    /**
     * @var LocatorInterface
     */
    private $locator;

    /**
     * @var GetSourceItemsDataBySku
     */
    private $getSourceItemsDataBySku;

    /**
     * @var GetSourceItemsDataBySku
     */
    private $getListBySku;

    /**
     * SourcePriceItems constructor.
     * @param IsSourceItemManagementAllowedForProductTypeInterface $isSourceItemManagementAllowedForProductType
     * @param IsSingleSourceModeInterface $isSingleSourceMode
     * @param LocatorInterface $locator
     * @param GetSourceItemsDataBySku $getSourceItemsDataBySku
     * @param GetListBySkuInterface $getListBySku
     */
    public function __construct(
        IsSourceItemManagementAllowedForProductTypeInterface $isSourceItemManagementAllowedForProductType,
        IsSingleSourceModeInterface $isSingleSourceMode,
        LocatorInterface $locator,
        GetSourceItemsDataBySku $getSourceItemsDataBySku,
        GetListBySkuInterface $getListBySku
    ) {
        $this->isSourceItemManagementAllowedForProductType = $isSourceItemManagementAllowedForProductType;
        $this->isSingleSourceMode = $isSingleSourceMode;
        $this->locator = $locator;
        $this->getSourceItemsDataBySku = $getSourceItemsDataBySku;
        $this->getListBySku = $getListBySku;
    }

    /**
     * @inheritdoc
     */
    public function modifyData(array $data)
    {
        $product = $this->locator->getProduct();

        if ($this->isSingleSourceMode->execute() === true
            || $this->isSourceItemManagementAllowedForProductType->execute($product->getTypeId()) === false
            || null === $product->getId()
        ) {
            return $data;
        }

        $data[$product->getId()]['sources']['assigned_sources'] = $this->getSourceItemsDataBySku->execute(
            $product->getSku()
        );

        $inventoryPrices = $this->getSourceItemsData($product->getSku());

        foreach ($data[$product->getId()]['sources']['assigned_sources'] AS &$item) {

            if (key_exists($item['source_code'], $inventoryPrices)) {
                $item['price'] = $inventoryPrices[$item['source_code']];
            } else {
                $item['price'] = 0;
            }

        }

        return $data;
    }

    private function getSourceItemsData(string $sku): array
    {
        $sourceItems = $this->getListBySku->execute($sku);

        $sourceItemsData = [];

        foreach ($sourceItems->getItems() as $sourceItem) {

            $sourceItemsData[$sourceItem->getSourceCode()] = $sourceItem->getPrice();
        }

        return $sourceItemsData;
    }

    /**
     * @inheritdoc
     */
    public function modifyMeta(array $meta)
    {
        $product = $this->locator->getProduct();

        $meta['sources'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'visible' => !$this->isSingleSourceMode->execute() &&
                            $this->isSourceItemManagementAllowedForProductType->execute($product->getTypeId()),
                    ],
                ],
            ]
        ];

        return $meta;
    }
}
