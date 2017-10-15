<?php

namespace Eleanorsoft\MainSlider\Block\Adminhtml;

class Item extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_item';
        $this->_blockGroup = 'Eleanorsoft_MainSlider';
        $this->_headerText = __('Slides');
        $this->_addButtonLabel = __('Create New Slide');
        parent::_construct();
    }
}