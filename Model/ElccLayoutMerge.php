<?php
namespace Jvi\Elcc\Model;

/**
 * Class Plugin
 */
class ElccLayoutMerge
{
	public function __construct(\Magento\Cms\Model\Page $resourcePage) {
		$this->page = $resourcePage;
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
		if($handle!='elcc_layout_handle') return "";

		$elcc_active = $this->page->getData('elcc_active');
		$elcc_generated = $this->page->getData('elcc_generated');

		// is elcc active for current page and is elcc xml generated?
		if($elcc_active=='1' && !empty($elcc_generated)){
			return $elcc_generated;
		}
        return '';
    }
}
