<?php
namespace Jvi\Elcc\Model;

/**
 * Class Plugin
 */
class ElccLayoutMerge
{
	public function __construct() {

	}

    /**
     * Around getDbUpdateString
     *
     * @param \Magento\Framework\View\Model\Layout\Merge $subject
     * @param callable $proceed
     * @param string $handle
     * @return string
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function aroundGetDbUpdateString(
        \Magento\Framework\View\Model\Layout\Merge $subject,
        \Closure $proceed,
        $handle
    ) {
		if($handle=='elcc_layout_handle'){
			return '<referenceContainer name="visual">
			    <referenceBlock name="visual_block"><!-- %%block-title: Visual %% -->
					<arguments>
			          <argument name="title" xsi:type="string">%%editable: text : Titel %%</argument>
			          <argument name="background_image" xsi:type="string">%%editable: image : Achtergrond afbeelding %%</argument>
			          <argument name="top_list_id" xsi:type="string">%%editable:text:Top lijst #ID:Kan je vinden op de overzichtspagina van de toplijsten%%1</argument>
			          <argument name="top_list_title" xsi:type="string">%%editable:text:Top lijst titel%%</argument>
			          <argument name="top_list_subtitle" xsi:type="string">%%editable:text:Top lijst subtitel%%</argument>
			        </arguments>
			    </referenceBlock>
			</referenceContainer>';
		}
        return '';
    }
}
