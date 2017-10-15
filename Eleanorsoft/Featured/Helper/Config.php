<?php

namespace Eleanorsoft\Featured\Helper;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CATEGORY_CONFIG_PATH = 'eleanorsoft/featured/category_id';

    public function getCategoryId()
    {
        return $this->scopeConfig->getValue(
            self::CATEGORY_CONFIG_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    public function isInCategory($product)
    {
        return in_array($this->getCategoryId(), $product->getCategoryIds());
    }
}
