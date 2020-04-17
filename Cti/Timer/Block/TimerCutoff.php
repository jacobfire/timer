<?php
/**
 * Copyright © Cti Digital. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cti\Timer\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Magento\Catalog\Model\Product;
use Cti\Timer\Model\RemainingTime;

/**
 * Class TimerCutoff
 *
 * @package Cti\Timer\Block
 */
class TimerCutoff extends \Magento\Framework\View\Element\Template
{
    /**
     * @var RemainingTime
     */
    private $remainingTime;

    /**
     * @var
     */
    private $registry;

    /**
     * @var Product
     */
    public $product;

    /**
     * TimerCutoff constructor.
     *
     * @param Registry $registry
     * @param RemainingTime $remainingTime
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Registry $registry,
        RemainingTime $remainingTime,
        Template\Context $context,
        array $data = []
    ) {
        $this->registry = $registry;
        $this->remainingTime = $remainingTime;

        parent::__construct($context, $data);
    }

    /**
     * @return float|int|string|null
     */
    public function getRemainingTime()
    {
        if ($this->isTimerAllowed()) {
            return $this->remainingTime->getRemainingTime();
        }

        return '';
    }

    /**
     * @return Product
     */
    private function getProduct()
    {
        if (is_null($this->product)) {
            $this->product = $this->registry->registry('product');

            if (!$this->product->getId()) {
                throw new LocalizedException(__('Failed to initialize product'));
            }
        }

        return $this->product;
    }

    /**
     * @return int
     */
    private function isTimerAllowed()
    {
        return $this->getProduct()->getTimerEnabler();
    }
}
