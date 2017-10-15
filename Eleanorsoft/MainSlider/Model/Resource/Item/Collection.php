<?php
namespace Eleanorsoft\MainSlider\Model\Resource\Item;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Eleanorsoft\MainSlider\Model\Item', 'Eleanorsoft\MainSlider\Model\Resource\Item');
    }

    public function getActiveOrderedSlides()
    {
        return $this->addFieldToFilter('is_enabled', 1)
            ->setOrder('order_number', 'ASC');
    }
}
