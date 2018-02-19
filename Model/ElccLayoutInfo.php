<?php

namespace Jvi\Elcc\Model;

use \Magento\Framework\App\Utility\Files;

class ElccLayoutInfo  {
	public $elcc_templates;
	private $files;

	public function __construct(Files $files){
		$this->files = $files->getPageLayoutFiles();
	}

	private function get_template_data($file_path){
		$raw_xml = file_get_contents($file_path);

		preg_match_all('/^(.*?%%(editable|block-title):(.*?)%%.*?)$/um', $raw_xml, $editables, PREG_SET_ORDER);
		preg_match_all('/^\\* layout_info:(.*?):(.*?)$/um', $raw_xml, $layout_info, PREG_SET_ORDER);

		foreach ($editables as &$_editable) {
			preg_match('/name="(.*?)"/um', $_editable[1], $name);

			$type_parts = explode(':', $_editable[3]);

			$editable = [
				'line' => trim($_editable[1]),
				'type' => $type_parts[0],
				'name' => isset($type_parts[1]) ? $type_parts[1] : $name[1]
			];

			if($_editable[2]=='block-title'){
				$editable['type'] = 'block-title';
				$editable['name'] = $_editable[3];
			}
			else
			{
				preg_match('/xsi\:type="(.*?)"/um', $_editable[1], $xsi_type);
				$editable['xsi_type'] = $xsi_type[1];
			}

			$_editable = $editable;
		}

		foreach ($layout_info as &$info_line) {
			$info_line = [
				'value' => trim($info_line[2]),
				'type' => $info_line[1]
			];
		}

		return [
			'file_path' => $file_path,
			'editables' => $editables,
			'raw' => $raw_xml,
			'info' => $layout_info
		];
	}

	private function getElccTemplates(){
		$this->elcc_templates = [];

		foreach ($this->files as $i => $file) {

			if(preg_match('/layout_update_xml_templates/u', $file[0]))
			{
				$this->elcc_templates[] = $this->get_template_data($file[0]);
			}
		}

		return $this->elcc_templates;
	}

	public function get_templates(){
		$this->getElccTemplates();

		return $this->elcc_templates;
	}
}
