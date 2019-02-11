<?php

declare(strict_types=1);

namespace Iqmosaic\InventoryPrice\Model\InventoryPrice\Command;

use Exception;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Iqmosaic\InventoryPrice\Model\ResourceModel\InventoryPrice\DeleteMultiple AS DeleteMultipleModel;
use Psr\Log\LoggerInterface;

/**
 * Class DeleteMultiple
 * @package Iqmosaic\InventoryPrice\Model\InventoryPrice\Command
 */
class DeleteMultiple implements DeleteMultipleInterface
{
    /**
     * @var DeleteMultiple
     */
    private $saveMultiple;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DeleteMultiple constructor.
     * @param DeleteMultipleModel $saveMultiple
     * @param LoggerInterface $logger
     */
    public function __construct(
        DeleteMultipleModel $saveMultiple,
        LoggerInterface $logger
    ) {
        $this->saveMultiple = $saveMultiple;
        $this->logger = $logger;
    }

    /**
     * @param array $priceItems
     * @throws CouldNotSaveException
     * @throws InputException
     */
    public function execute(array $priceItems): void
    {
        if (empty($priceItems)) {
            throw new InputException(__('Input data is empty'));
        }

        try {
            $this->saveMultiple->execute($priceItems);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            throw new CouldNotSaveException(__('Could not save InventoryPrice'), $e);
        }
    }
}
