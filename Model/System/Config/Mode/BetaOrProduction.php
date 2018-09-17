<?php

namespace Rayms\OrderEventsBroadcaster\Model\System\Config\Mode;

use Magento\Framework\Option\ArrayInterface;

class BetaOrProduction implements ArrayInterface
{

    public function toOptionArray()
    {
        return [['value' => 'beta', 'label' => __('Beta Mode')], ['value' => 'production', 'label' => __('Production Mode')],];

    }
}
