<?php
/**
 * Copyright © Cti Digital, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cti\Timer\Model\Config\Backend;

/**
 * Class Time
 * @package Cti\Timer\Model\Config\Backend
 */
class Time extends \Magento\Framework\App\Config\Value
{
    /**
     * Check if time value is correct
     */
    const VALIDATION_PATTERN = '/^(?:2[0-4]|[01][1-9]|10):([0-5][0-9])$/';

    /**
     * Validate input
     */
    public function afterSave()
    {
        if ($this->isValueChanged() && !empty($this->getValue())) {
            $validatedValue = [];
            $validated = preg_match(self::VALIDATION_PATTERN, $this->getValue(), $validatedValue);
            //var_dump($validatedValue);die;

            if (!count($validatedValue) || (!isset($validatedValue[0]) && $validated)) {
                throw new \Exception('Your time is not in correct format');
            }
        }

        return parent::afterSave();
    }
}
