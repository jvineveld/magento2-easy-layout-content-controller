<?php
namespace Jvi\Elcc\Ui\Component\Form;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Ui\Component\Form\FieldFactory;
use Magento\Ui\Component\Form\Fieldset as BaseFieldset;
use Jvi\Elcc\Model\ElccLayoutInfo as layoutInfo;
use Jvi\Elcc\Model\ElccDataFactory;

class Fieldset extends BaseFieldset
{
    /**
     * @var FieldFactory
     */
    private $fieldFactory;
	private $enabled;
	private $current_layout;
	private $request;
	private $elcc_data;
	private $page_data;

    public function __construct(
        ContextInterface $context,
        array $components = [],
        array $data = [],
        FieldFactory $fieldFactory,
		layoutInfo $layout,
		RequestInterface $request,
		ElccDataFactory $elcc_data)
    {
        parent::__construct($context, $components, $data);
		$this->templates = $layout->get_templates();
        $this->fieldFactory = $fieldFactory;
		$this->request = $request;
		$this->elcc_data = $elcc_data->create();
		$this->page_data = [];

		// tmp hardcoded
		$this->enabled = true;
		$this->current_layout = 0;
    }

	private function get_page_data(){
		if(!empty($this->page_data))
			return $this->page_data;

		$page_id = $this->request->getParam('page_id', $this->request->getParam('id', false));

		$data = $this->elcc_data->getCollection();
		$page_data = $data->addFieldToFilter('target_id', $page_id)->getFirstItem();

		return json_decode($page_data->getData()['data']);
	}

	private function get_value($section, $field){
		$page_data = $this->get_page_data();

		if(!isset($page_data->$section))
		{
			// fallback Todo: just find the first occurrence of the name

			return "";
		}

		if(!isset($page_data->$section->$field))
		{
			// no match found in data

			return "";
		}

		// return match
		return $page_data->$section->$field;
	}

    /**
     * Get components
     *
     * @return UiComponentInterface[]
     */
    public function getChildComponents()
    {
		if(!$this->enabled)
			return parent::getChildComponents();

		$layout = $this->templates[$this->current_layout];
		$fields = [];
		$current_block = 0;
		$current_block_name = '';

		if(!empty($layout['info'])){
			$fields[] = [
				'label' => '',
				'value' => $layout['info'][0]['value'],
				'formElement' => 'input',
				'elementTmpl' => 'Jvi_Elcc/form/elements/head'
			];
		}

		foreach ($layout['editables'] as $i => $editable) {

			$scope_name = str_replace('.', '----',$editable['tag_name']); // because . will break form post variables names, and ---- wont be used to often i think..

			if($editable['type']=='block-title'){
				$current_block++;
				$current_block_name = $scope_name;
			}

			$field = [
                'label' => $editable['name'],
                'value' => $this->get_value($current_block_name, $editable['tag_name']),
                'formElement' => 'input',
				'required' => true,
				'additionalClasses' => "elcc-field",
				'dataScope'		=> 'elcc['.$current_block_name.']['.$scope_name.']'
            ];

			if($editable['type']=='block-title'){
				$field['elementTmpl'] = 'Jvi_Elcc/form/elements/title';
				$field['value'] = $editable['name'];
				$field['text'] = $editable['name'];
				$field['required'] = false;
				$field['additionalClasses'] = "elcc-header";
			}

			if($editable['type']=='image'){
				//var_dump($this->get_value($current_block_name, $editable['tag_name']),$current_block_name, $editable['tag_name']);
				$field['formElement'] = 'fileUploader';
				$field['isMultipleFiles'] = false;
				$field['uploaderConfig'] = [
					'url' => 'elcc/index/save/'
				];
        		$field['note'] = __('Allowed image types: jpg,png');
				$field['dataScope'] = 'elccimage['.$current_block_name.']['.$scope_name.']';
			}

			$fields[] = $field;
		}

        foreach ($fields as $k => $fieldConfig) {
            $fieldInstance = $this->fieldFactory->create();
            $name = 'elcc_' . $k;

            $fieldInstance->setData(
                [
                    'config' => $fieldConfig,
                    'name' => $name
                ]
            );

            $fieldInstance->prepare();
            $this->addComponent($name, $fieldInstance);
        }

        return parent::getChildComponents();
    }
}
