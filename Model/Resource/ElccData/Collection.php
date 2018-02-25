<?php

namespace Jvi\Elcc\Model\Resource\ElccData;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            'Jvi\Elcc\Model\ElccData',
            'Jvi\Elcc\Model\Resource\ElccData'
        );
    }
}
