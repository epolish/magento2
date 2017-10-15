<?php

namespace Eleanorsoft\BestSellers\Model\ResourceModel\Report\Bestsellers;

class Collection extends \Magento\Sales\Model\ResourceModel\Report\Bestsellers\Collection
{
    public function setRatingLimit($limit)
    {
        $this->_ratingLimit = $limit;

        return $this;
    }
}
