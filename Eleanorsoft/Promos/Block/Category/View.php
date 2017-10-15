<?php

namespace Eleanorsoft\Promos\Block\Category;

class View extends \Magento\Catalog\Block\Category\View
{
    private $_tempCategory;

    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        array $data = []
    ) {
        $this->_tempCategory = $categoryFactory->create();

        parent::__construct($context, $layerResolver, $registry, $categoryHelper, $data);
    }

    public function getCurrentCategory()
    {
        return $this->_tempCategory;
    }
}
