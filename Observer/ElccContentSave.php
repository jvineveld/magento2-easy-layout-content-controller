<?php
namespace Jvi\Elcc\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Jvi\Elcc\Model\ElccData;

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
		ElccData $elcc_data
    ) {
        $this->request = $request;
		$this->elcc_data = $elcc_data->create();
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
		foreach ($_POST['elcc'] as $num => $section) {
			foreach($section as $name => $field){
				foreach($field as $type => $value){
				//	$name =  str_replace('----', '.', $name); // fix for . breaking forms
				//	echo '------'.'New row!'."\n";
				//	var_dump($num, $name, $type, $value);
				}
			}
		}

		$page_id = $_POST['page_id'];
		$content_type = 'page'; // for now just pages
		$elcc_data = json_encode($_POST['elcc']);

		$rowData = [
			'target_id' => $page_id,
			'type'	=> $content_type,
			'data'	=> $elcc_data
		];

		try {
			$collection = $this->elcc_data->getCollection();
			//var_dump($collection);
		     $collection->setData($rowData)
		     ->save();
		} catch (Exception $e) {
		    echo $e->getMessage();
		}

		var_dump();
		exit();
        if ($customFieldValue = $this->request->getParam('elcc_data')) {
            $namespaceModel->setCustomField($customFieldValue);
        }

        return $this;
    }
}
