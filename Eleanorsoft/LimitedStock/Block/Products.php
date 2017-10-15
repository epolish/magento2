<?php

namespace Eleanorsoft\LimitedStock\Block;

class Products extends \Magento\Framework\View\Element\Template
{
    protected $_helper;
    protected $_categoryFactory;
    protected $_productCollectionFactory;

    public function __construct(
        \Eleanorsoft\LimitedStock\Helper\Config $helper,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {
        $this->_helper                   = $helper;
        $this->_categoryFactory          = $categoryFactory;
        $this->_productCollectionFactory = $productCollectionFactory;

        parent::__construct($context);
    }

    public function getHelper()
    {
        return $this->_helper;
    }

    public function getProducts()
    {
        return $this->_categoryFactory->create()
            ->load($this->_helper->getCategoryId())
            ->getProductCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter(
                'visibility', \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH
            )
            ->addAttributeToFilter(
                'status', \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED
            )
            ->setOrder('position', 'ASC');
    }
}
