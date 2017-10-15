<?php

namespace Eleanorsoft\MainSlider\Model;
use Magento\Framework\Model\AbstractModel;

/**
 * Class Item
 * @package Eleanorsoft\MainSlider\Model
 *
 *
 * @method getImage()
 * @method getName()
 * @method getText()
 * @method getSubtitle()
 */
class Item extends AbstractModel
{
    public function _construct() {
        $this->_init('Eleanorsoft\MainSlider\Model\Resource\Item');
    }
}