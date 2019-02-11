<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Api\Data;

use \Magento\Framework\Api\ExtensibleDataInterface;


/**
 * Interface InventoryPriceInterface
 * @package Iqmosaic\InventoryPrice\Api\Data
 */
interface InventoryPriceInterface extends ExtensibleDataInterface
{
    /**
     * Constants for keys of data array.
     * Identical to the name of the getter in snake case
     */
    const SKU = 'sku';
    const SOURCE_CODE = 'source_code';
    const PRICE = 'price';

    /**
     * Get sku
     *
     * @return string|null
     */
    public function getSku(): ?string;

    /**
     * Set sku
     *
     * @param string|null $sku
     * @return void
     */
    public function setSku(?string $sku): void;

    /**
     * Get source_code
     *
     * @return string|null
     */
    public function getSourceCode(): ?string;

    /**
     * Set source_code
     *
     * @param string|null $sourceCode
     * @return void
     */
    public function setSourceCode(?string $sourceCode): void;

    /**
     * Get price
     *
     * @return float|null
     */
    public function getPrice(): ?float;

    /**
     * Set price
     *
     * @param float|null $price
     * @return void
     */
    public function setPrice(?float $price): void;
}