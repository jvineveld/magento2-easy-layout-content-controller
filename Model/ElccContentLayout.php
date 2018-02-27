<?php
/**
* Class LcSaveBefore
*
* @package Jvi\elcc\Observer
*/

namespace Jvi\Elcc\Model;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\View\Result\Page;

class ElccContentLayout implements ObserverInterface
{
	public function __construct()
	{
		//Observer initialization code...
		//You can use dependency injection to get any class this observer may need.
	}

	public function afterAddPageLayoutHandles(Page\Interceptor $subject)
	{
		$subject->addHandle('elcc_layout_handle');
		return $subject;
	}

	public function execute(Observer $observer){
		return;
	}
}
