<?php


declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice as InventoryPriceResourceModel;
use Iqmosaic\InventoryPrice\Api\Data\InventoryPriceInterface;

class InventoryPrice extends AbstractExtensibleModel implements InventoryPriceInterface
{

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(InventoryPriceResourceModel::class);
    }

    /**
     * @inheritdoc
     */
    public function getSku(): ?string
    {
        return $this->getData(self::SKU) === null ?
            null:
            (string)$this->getData(self::SKU);
    }

    /**
     * @inheritdoc
     */
    public function setSku(?string $sku): void
    {
        $this->setData(self::SKU, $sku);
    }

    /**
     * @inheritdoc
     */
    public function getSourceCode(): ?string
    {
        return $this->getData(self::SOURCE_CODE) === null ?
            null:
            (string)$this->getData(self::SOURCE_CODE);
    }

    /**
     * @inheritdoc
     */
    public function setSourceCode(?string $sourceCode): void
    {
        $this->setData(self::SOURCE_CODE, $sourceCode);
    }

    /**
     * @inheritdoc
     */
    public function getPrice(): ?float
    {
        return $this->getData(self::PRICE) === null ?
            null:
            (float)$this->getData(self::PRICE);
    }

    /**
     * @inheritdoc
     */
    public function setPrice(?float $price): void
    {
        $this->setData(self::PRICE, $price);
    }

}