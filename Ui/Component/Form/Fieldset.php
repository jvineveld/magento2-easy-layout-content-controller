<?php
namespace Jvi\Elcc\Ui\Component\Form;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Ui\Component\Form\FieldFactory;
use Magento\Ui\Component\Form\Fieldset as BaseFieldset;
use \Jvi\Elcc\Model\ElccLayoutInfo as layoutInfo;

class Fieldset extends BaseFieldset
{
    /**
     * @var FieldFactory
     */
    private $fieldFactory;
	private $enabled;
	private $current_layout;

    public function __construct(
        ContextInterface $context,
        array $components = [],
        array $data = [],
        FieldFactory $fieldFactory,
		layoutInfo $layout)
    {
        parent::__construct($context, $components, $data);
		$this->templates = $layout->get_templates();
        $this->fieldFactory = $fieldFactory;

		// tmp hardcoded
		$this->enabled = true;
		$this->current_layout = 0;
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

		if(!empty($layout['info'])){
			$fields[] = [
				'label' => ' ',
				'value' => $layout['info'][0]['value'],
				'formElement' => 'input',
				'elementTmpl' => 'Jvi_Elcc/form/elements/head'
			];
		}

		foreach ($layout['editables'] as $i => $editable) {
			$field = [
                'label' => $editable['name'],
                'value' => '',
                'formElement' => 'input',
				'required' => true,
				'additionalClasses' => "elcc-field"
            ];

			if($editable['type']=='block-title'){
				$field['elementTmpl'] = 'Jvi_Elcc/form/elements/title';
				$field['value'] = $editable['name'];
				$field['text'] = $editable['name'];
				$field['required'] = false;
				$field['additionalClasses'] = "elcc-header";
			}

			if($editable['type']=='image'){
				$field['formElement'] = 'fileUploader';
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
