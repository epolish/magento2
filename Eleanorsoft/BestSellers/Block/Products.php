<?php

namespace Eleanorsoft\BestSellers\Block;

class Products extends \Magento\Framework\View\Element\Template
{
    protected $_productRepository;
    protected $_bestSellersCollectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Eleanorsoft\BestSellers\Model\ResourceModel\Report\Bestsellers\CollectionFactory $bestSellersCollectionFactory
    ) {
        $this->_productRepository            = $productRepository;
        $this->_bestSellersCollectionFactory = $bestSellersCollectionFactory;

        parent::__construct($context);
    }

    public function getProductById($id)
    {
        return $this->_productRepository->getById($id);
    }

    public function getProducts()
    {
        $products = [];
        $bestSellersCollection = $this->_bestSellersCollectionFactory->create()
            ->setRatingLimit(12)
            ->setModel('Magento\Catalog\Model\Product')
            ->addStoreFilter($this->_storeManager->getStore()->getId());

        foreach ($bestSellersCollection as $item) {
            $products[] = $this->getProductById($item->getProductId());
        }

        return $products;
    }
}
