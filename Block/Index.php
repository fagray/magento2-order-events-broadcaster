<?php

namespace Rayms\OrderEventsBroadcaster\Block;

class Index extends \Magento\Framework\View\Element\Template
{

  	private $eventManager;

	public function __construct(
		\Magento\Framework\View\Element\Template\Context $context,
		\Magento\Framework\Event\Manager $eventManager
	
		)
	{
		parent::__construct($context);
		$this->eventManager = $eventManager;
	}

}