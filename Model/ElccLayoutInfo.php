<?php

namespace Jvi\Elcc\Model;

use \Magento\Framework\App\Utility\Files;

class ElccLayoutInfo  {
	public $elcc_templates;
	private $files;

	public function __construct(Files $files){
		$this->files = $files;
	}

	private function get_template_data($file_path){
		$raw_xml = file_get_contents($file_path);

		preg_match_all('/^(.*?%%editable:(.*?)%%.*?)$/um', $raw_xml, $editables, PREG_SET_ORDER);
		preg_match_all('/^\\* layout_info:(.*?):(.*?)$/um', $raw_xml, $layout_info, PREG_SET_ORDER);

		foreach ($editables as &$editable) {
			preg_match('/name="(.*?)"/um', $editable[1], $name);
			preg_match('/xsi\:type="(.*?)"/um', $editable[1], $xsi_type);

			$editable = [
				'line' => trim($editable[1]),
				'type' => $editable[2],
				'name' => $name[1],
				'xsi_type' => $xsi_type[1]
			];
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

	private function getElccTemplates($files){
		$this->elcc_templates = [];

		foreach ($files->getPageLayoutFiles() as $i => $file) {

			if(preg_match('/layout_update_xml_templates/u', $file[0]))
			{
				$this->elcc_templates[] = $this->get_template_data($file[0]);
			}
		}

		return $this->elcc_templates;
	}

	public function get_templates(){
		$this->getElccTemplates($this->files);

		return $this->elcc_templates;
	}
}
