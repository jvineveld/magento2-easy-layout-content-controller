<?php

namespace Jvi\Elcc\Model;

use \Magento\Framework\App\Utility\Files;

class ElccLayoutInfo  {
	public $elcc_templates;

	public function __construct(Files $files){
		$elcc_templates = $this->getElccTemplates($files);
		var_dump($elcc_templates);
	}

	private function get_template_data($file_path){
		$raw_xml = file_get_contents($file_path);

		return [
			'raw' => $raw_xml,
			'file_path' => $file_path
		]
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

	public function test (){
		return 'haha';
	}
}
