<?php

namespace Eleanorsoft\MainSlider\Controller\Adminhtml\Item;

class NewAction extends Index
{
    /**
     * Create new news action
     *
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}