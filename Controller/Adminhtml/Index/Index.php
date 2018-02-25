<?php

namespace Jvi\Elcc\Controller\Adminhtml\Index;

class Index extends \Magento\Framework\App\Action\Action {
	private $templates;

	/**
	 * @param \Magento\Backend\App\Action\Context $context
	 * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
	 */
	public function __construct(
		\Magento\Backend\App\Action\Context $context
		,\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
		,\Jvi\Elcc\Model\ElccLayoutInfo $layoutInfo
		,\Jvi\Elcc\Model\ElccDataFactory $elccData
	) {
		parent::__construct($context);
		$this->templates = $layoutInfo->get_templates();
		$this->resultJsonFactory = $resultJsonFactory;
		$this->elcc_data = $elccData;
	}

	/**
	 * @return \Magento\Framework\Controller\Result\Json
	 */
	public function execute() {
		/** @var \Magento\Framework\Controller\Result\Json $result */
		$result = $this->resultJsonFactory->create();

        $data = $this->elcc_data->create();

        // Load the item with ID is 1
        $item = $data->load(1);
        var_dump($item->getData());

        $dataCollection = $data->getCollection();
		
        var_dump($dataCollection->getData());
		die();
		return $result->setData(['templates' => $this->templates]);
	}
}
