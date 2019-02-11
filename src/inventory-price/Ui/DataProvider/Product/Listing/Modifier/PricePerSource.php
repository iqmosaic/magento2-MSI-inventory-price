<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Ui\DataProvider\Product\Listing\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Iqmosaic\InventoryPrice\Model\InventoryPrice\Command\GetListBySkuInterface;
use Magento\InventoryApi\Api\SourceRepositoryInterface;
use Magento\InventoryCatalogApi\Model\IsSingleSourceModeInterface;
use Magento\InventoryConfigurationApi\Model\IsSourceItemManagementAllowedForProductTypeInterface;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Listing\Columns\Column;

/**
 * Price Per Source modifier on CatalogInventory Product Grid
 */
class PricePerSource extends AbstractModifier
{
    /**
     * @var IsSingleSourceModeInterface
     */
    private $isSingleSourceMode;

    /**
     * @var IsSourceItemManagementAllowedForProductTypeInterface
     */
    private $isSourceItemManagementAllowedForProductType;

    /**
     * @var SourceRepositoryInterface
     */
    private $sourceRepository;

    /**
     * @var GetListBySkuInterface
     */
    private $getListBySku;

    /**
     * PricePerSource constructor.
     * @param IsSingleSourceModeInterface $isSingleSourceMode
     * @param IsSourceItemManagementAllowedForProductTypeInterface $isSourceItemManagementAllowedForProductType
     * @param SourceRepositoryInterface $sourceRepository
     * @param GetListBySkuInterface $getListBySku
     */
    public function __construct(
        IsSingleSourceModeInterface $isSingleSourceMode,
        IsSourceItemManagementAllowedForProductTypeInterface $isSourceItemManagementAllowedForProductType,
        SourceRepositoryInterface $sourceRepository,
        GetListBySkuInterface $getListBySku
    ) {
        $this->isSingleSourceMode = $isSingleSourceMode;
        $this->isSourceItemManagementAllowedForProductType = $isSourceItemManagementAllowedForProductType;
        $this->sourceRepository = $sourceRepository;
        $this->getListBySku = $getListBySku;
    }

    /**
     * @inheritdoc
     */
    public function modifyData(array $data)
    {
        if (0 === $data['totalRecords'] || true === $this->isSingleSourceMode->execute()) {
            return $data;
        }

        foreach ($data['items'] as &$item) {
            $item['price_per_source'] = $this->isSourceItemManagementAllowedForProductType->execute(
                $item['type_id']
            ) === true ? $this->getSourceItemsData($item['sku']) : [];
        }
        unset($item);

        return $data;
    }

    /**
     * @param string $sku
     * @return array
     */
    private function getSourceItemsData(string $sku): array
    {
        $sourceItems = $this->getListBySku->execute($sku);

        $sourceItemsData = [];

        foreach ($sourceItems->getItems() as $sourceItem) {

            $sourceItemsData[] = [
                'source_name' => $sourceItem->getName(),
                'price' => $sourceItem->getPrice(),
            ];
        }

        return $sourceItemsData;
    }

    /**
     * @inheritdoc
     */
    public function modifyMeta(array $meta)
    {
        if (true === $this->isSingleSourceMode->execute()) {
            return $meta;
        }

        $meta = array_replace_recursive($meta, [
            'product_columns' => [
                'children' => [
                    'price_per_source' => $this->getPricePerSourceMeta(),
                    'price' => [
                        'arguments' => null,
                    ],
                ],
            ],
        ]);
        return $meta;
    }

    /**
     * @return array
     */
    private function getPricePerSourceMeta(): array
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'sortOrder' => 78,
                        'filter' => false,
                        'sortable' => false,
                        'label' => __('Price per Source'),
                        'dataType' => Text::NAME,
                        'componentType' => Column::NAME,
                        'component' => 'Iqmosaic_InventoryPrice/js/product/grid/cell/price-per-source',
                    ]
                ],
            ],
        ];
    }
}
