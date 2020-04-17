<?php
/**
 * Copyright © Cti Digital, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Cti\Timer\Model;

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Cti\Timer\Service\Configuration;

/**
 * Class Time
 *
 * @package Cti\Timer\Model\Config\Backend
 */
class RemainingTime extends \Magento\Framework\Model\AbstractModel
{
    const EXPIRED = 'expired';

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * @var Configuration
     */
    private $configuration;

    /**
     * RemainingTime constructor.
     * @param TimezoneInterface $timezone
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Configuration $configuration,
        TimezoneInterface $timezone,
        Context $context,
        \Magento\Framework\Registry $registry,
        AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->timezone = $timezone;
        $this->configuration = $configuration;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @return float|int|string|null
     */
    public function getRemainingTime()
    {
        $timeParts = [];
        $time = $this->configuration->getTimerCuttoff();
        if ($time) {
            $timeParts = explode(':', $time);
        }
        $todayDate = $this->timezone->date()->format('H:i');
        $todayTimeParts = explode(':', $todayDate);

        $result = $this->compare($todayTimeParts, $timeParts);
        if ($result != self::EXPIRED) {
            return $result;
        }
        return null;
    }

    /**
     * Calculation of difference
     *
     * @param array $todayTime
     * @param array $configTime
     * @return float|int|string
     */
    private function compare($todayTime, $configTime)
    {
        if (count($configTime)) {
            $configTimeRestriction = $configTime[0] * 60 + $configTime[1];
            $todayTimeRestriction = $todayTime[0] * 60 + $todayTime[1];
            $difference = $configTimeRestriction - $todayTimeRestriction;
            if ($difference > 0) {
                if ($difference > 60) {
                    $mins = $difference % 60;
                    $hours = ($difference - $mins) / 60;
                    return sprintf('Order in %sh %smins for the next dispatch period',
                        $hours,
                        $mins
                    );
                } else {
                    return sprintf('Order in %smins for the next dispatch period', $difference);
                }
            }
        }

        return self::EXPIRED;
    }
}
