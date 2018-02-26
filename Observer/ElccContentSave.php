<?php
namespace Jvi\Elcc\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Jvi\Elcc\Model\ElccDataFactory;

/**
 * Class LcSaveBefore
 *
 * @package Jvi\elcc\Observer
 */
class ElccContentSave implements ObserverInterface
{
    /**
     * @var RequestInterface|null
     */
    protected $request = null;

    /**
     * LcSaveBefore constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $request,
		ElccDataFactory $elcc_data
    ) {
        $this->request = $request;
		$this->elcc_data = $elcc_data->create()->getCollection();
    }

	// merge both fields and images to save in to data column
	public function merge_fields(){

		$field_data = $this->request->getParam('elcc');
		$images_data = $this->request->getParam('elccimage');

		return array_merge_recursive($field_data, $images_data);
	}

    /**
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
		$page_id = $this->request->getParam('page_id');
		$content_type = 'page'; // for now just pages
		$json_post_data = json_encode($this->merge_fields());

		try {
			$elcc_data = $this->elcc_data;
			$current_page_data = $elcc_data->addFieldToFilter('target_id', $page_id);

			if(!count($current_page_data->getSize())){
				$data = $elcc_data->getNewEmptyItem();
		     	$data->setData('target_id', $page_id);
				$data->setData('type', $content_type);
				$data->setData('data', $json_post_data);

			    $data->save();
			}
			else
			{
				$current_page_data = $current_page_data->getFirstItem();
		     	$current_page_data->setData('target_id', $page_id);
				$current_page_data->setData('type', $content_type);
				$current_page_data->setData('data', $json_post_data);

				$current_page_data->save();
			}
		} catch (Exception $e) {
		    echo $e->getMessage();
		}

        if ($customFieldValue = $this->request->getParam('elcc_data')) {
            $namespaceModel->setCustomField($customFieldValue);
        }

        return $this;
    }
}
