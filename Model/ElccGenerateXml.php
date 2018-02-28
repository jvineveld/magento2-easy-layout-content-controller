<?php
namespace Jvi\Elcc\Model;

use Jvi\Elcc\Model\ElccLayoutInfo;
use Magento\Framework\App\RequestInterface;

class ElccGenerateXml {
	private $_request;
	private $_layout_info;
	private $_template_data;
	private $_current_block;

	/**
     * @var array
     */
    protected $options;

	public function __construct(RequestInterface $request, ElccLayoutInfo $layout_info){
		$this->_request = $request;
		$this->_layout_info = $layout_info;
	}

	public function get_field_from_data($block_name, $field_name){
		if(!isset($this->_template_data[$block_name])) // block not found in template
			return '';

		if(!isset($this->_template_data[$block_name][$field_name])) // field not found in block
			return '';

		return $this->_template_data[$block_name][$field_name];
	}

	public function _merge_field($matches){
		$block_name = str_replace('.', '----', $this->_current_block);
		$field_name = str_replace('.', '----', $matches[1]);
		$field_value = $this->get_field_from_data($block_name, $field_name);

		// is image field
		if(gettype($field_value)=='array')
		{
			$field_value = $field_value[0]['url'];
		}

		$line = preg_replace('/%%.*?\\</um', $field_value.'<', $matches[0]);

		return $line;
	}

	public function _merge_block($matches){
		$block = $matches[0];
		$this->_current_block = $matches[1]; // sets current block name to match options within theire scopes

		$xml = preg_replace_callback('/name="(.*?)".*?%%editable:(.*?)%%.*?\</um', array($this, '_merge_field'), $block);

		return $xml;
	}

	protected function merge_fields_with_rawxml($raw_xml){
		$xml = preg_replace_callback('/<referenceBlock.*?name="(.*?)".*?>(.*?)<\/referenceBlock>/usm', array($this, '_merge_block'), $raw_xml);

		return $xml;
	}

    public function create($template_path, $template_data)
    {
		$this->_template_data = $template_data;

		$template = $this->_layout_info->get_template_by_path($template_path);
		$xml = $this->merge_fields_with_rawxml($template['raw']);

		return $xml;
    }
}
