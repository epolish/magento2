<?php

namespace Eleanorsoft\Promos\Block\Product;

class ListProduct extends \Magento\Catalog\Block\Product\ListProduct
{
    protected $_productCollection;

    public function __construct(
        \Magento\Framework\Url\Helper\Data $urlHelper,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Eleanorsoft\Promos\Block\Products $productCollection,
        \Magento\Framework\Data\Helper\PostHelper $postDataHelper,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        array $data = []
    ) {
        $this->_productCollection = $productCollection->getProductCollection();

        parent::__construct(
            $context,
            $postDataHelper,
            $layerResolver,
            $categoryRepository,
            $urlHelper,
            $data
        );
    }

    protected function _getProductCollection()
    {
        return $this->_productCollection;
    }
}
