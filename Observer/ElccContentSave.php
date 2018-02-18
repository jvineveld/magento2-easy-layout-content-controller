<?php
namespace Jvi\Elcc\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;

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
        RequestInterface $request
    ) {
        $this->request = $request;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     */
    public function execute(Observer $observer)
    {
        if ($customFieldValue = $this->request->getParam('elcc_data')) {
            $namespaceModel->setCustomField($customFieldValue);
        }

        return $this;
    }
}
