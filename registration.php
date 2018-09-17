<?php 

// register the module
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Rayms_OrderEventsBroadcaster',
    __DIR__
);
