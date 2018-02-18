<?php
namespace Jvi\Elcc\Observer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;

class ElccContentSave implements ObserverInterface {
    public function execute(Observer $observer) {
        $event = $observer->getEvent();
        $quote = $event->getQuote();
            $myfile = fopen("var/log/debug.log", "a+") or die("Unable to open file!");
fwrite($myfile, print_r($quote->getData(),true));
fclose($myfile);
    }
}
