<?php
namespace Jvi\Elcc\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Cms\Model\Page;
use Jvi\Elcc\Model\ElccDataFactory;
use Jvi\Elcc\Model\ElccGenerateXml;

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

	protected $layout_info;
	protected $page;
	protected $elcc_data;

    /**
     * LcSaveBefore constructor.
     *
     * @param RequestInterface $request
     */
    public function __construct(
        RequestInterface $_request,
		ElccDataFactory $elcc_data,
		ElccGenerateXml $xml_generator,
		Page $resourcePage
    ) {
        $this->request = $_request;
		$this->xml_generator = $xml_generator;
		$this->page = $resourcePage->getCollection();
		$this->elcc_data = $elcc_data->create()->getCollection();
    }

	// merge both fields and images to save in to data column
	public function merge_fields(){
		$field_data = $this->request->getParam('elcc');
		$images_data = $this->request->getParam('elccimage');

		return array_merge_recursive($field_data, $images_data);
	}

	protected function get_generated_xml($template_path, $template_data){
		$generated_xml = $this->xml_generator->create($template_path, $template_data);
		return $generated_xml;
	}

    /**
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
		$page_model = $observer->getData('page');
		$request = $observer->getData('request');

		$page_id = $request->getParam('page_id');
		$field_data = $request->getParam('elcc');
		
		if(empty($field_data)){
			return $this;
		}

		$content_type = 'page'; // for now just pages
		$combined_postfields = $this->merge_fields();
		$json_post_data = json_encode($combined_postfields);

		try {
			$elcc_data = $this->elcc_data;
			$current_page_data = $elcc_data->addFieldToFilter('target_id', $page_id);
			$current_cmspage_data = $this->page->addFieldToFilter('page_id', $page_id);

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
			$current_cmspage_data = $current_cmspage_data->getFirstItem();
			$generated_xml = $this->get_generated_xml($current_cmspage_data->getData('elcc_template'), $combined_postfields);

			$page_model->setData('elcc_generated', $generated_xml);
		} catch (Exception $e) {
		    echo $e->getMessage();
		}

        if ($customFieldValue = $this->request->getParam('elcc_data')) {
            $namespaceModel->setCustomField($customFieldValue);
        }

        return $this;
    }
}
