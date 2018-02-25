<?php

namespace Jvi\Elcc\Model\Resource;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ElccData extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('elcc_data', 'id');
    }
}
