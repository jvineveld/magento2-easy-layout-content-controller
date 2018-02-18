<?php

namespace Jvi\Elcc\Controller\Adminhtml\Index;

class Index extends \Magento\Backend\App\AbstractAction {

	/**
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	 */
	public function __construct(
		\Magento\Backend\App\Action\Context $context
		,\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
		,\Jvi\Elcc\Model\ElccLayoutInfo $layoutInfo
	) {
		parent::__construct($context);
		var_dump($layoutInfo->get_templates());
		$this->resultJsonFactory = $resultJsonFactory;
	}

	/**
	 * @return \Magento\Framework\Controller\Result\Json
	 */
	public function execute() {
		/** @var \Magento\Framework\Controller\Result\Json $result */
		$result = $this->resultJsonFactory->create();
		return $result->setData(['success' => false]);
	}
}
