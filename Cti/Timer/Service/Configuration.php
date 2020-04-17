<?php
/**
 * Copyright © Cti Digital, LLC. All rights reserved.
 * See LICENSE.txt for license details.
 */
namespace Cti\Timer\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Configuration
 *
 * @package Cti\Timer\Service
 */
class Configuration
{
    const IS_ENABLED = 'timer/manage/general_enabling';

    const PATTERN_TIME = 'timer/day_settings/';

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Configuration Service constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed|void
     */
    public function getTimerCuttoff()
    {
        if (!$this->isEnabled()) {
            return;
        }

        $daySettings = self::PATTERN_TIME . strtolower(Date('l'));
        return $this->scopeConfig->getValue($daySettings, ScopeInterface::SCOPE_WEBSITE);
    }

    /**
     * @return int
     */
    public function isEnabled()
    {
        return (int)$this->scopeConfig
            ->getValue(self::IS_ENABLED, ScopeInterface::SCOPE_WEBSITE);
    }
}
