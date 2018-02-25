<?php

namespace Jvi\Elcc\Model;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

class ElccData extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('Jvi\Elcc\Model\Resource\ElccData');
    }
}
