<?php

namespace Jvi\Elcc\Model;

use Magento\Framework\Model\AbstractModel;

class ElccData extends AbstractModel
{
    protected function _construct()
    {
        $this->_init('Jvi\Elcc\Model\Resource\ElccData');
    }
}
