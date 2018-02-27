<?php
namespace Jvi\Elcc\Model;

use Jvi\Elcc\Model\ElccLayoutInfo;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\OptionSourceInterface;

class ElccTemplateSelectDataprovider implements OptionSourceInterface {
	private $_request;
	private $_layout_info;
	private $loadedData;

	/**
     * @var array
     */
    protected $options;

	public function __construct(RequestInterface $request, ElccLayoutInfo $layout_info){
		$this->_request = $request;
		$this->_layout_info = $layout_info;
	}

	private function add_templates_to_loadedData($templates){
		foreach ($templates as $template) {
			$this->options[] = [
                'label' => $template['info'][0]['value'],
                'value' => $template['file_path'],
            ];

		}
	}

	/**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options !== null) {
            return $this->options;
        }

		$templates = $this->_layout_info->get_templates();

		$this->options = [];
		$this->add_templates_to_loadedData($templates);

        return $this->options;
    }
}
