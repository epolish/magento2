<?php

namespace Eleanorsoft\HomePage\Model;

class Category
{
    public static $product_page_limit = 3;
    public static $product_page_offset = 0;

    protected $_listProduct;
    protected $_storeManager;
    protected $_categoryHelper;
    protected $_categoryFactory;
    protected $_productCollectionFactory;

    public function __construct(
        \Magento\Catalog\Helper\Category $categoryHelper,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory
    ) {

        $this->_listProduct              = $listProduct;
        $this->_storeManager             = $storeManager;
        $this->_categoryHelper           = $categoryHelper;
        $this->_categoryFactory          = $categoryFactory;
        $this->_productCollectionFactory = $productCollectionFactory;
    }

    public function getProductImageUrl($product)
    {
        return $this->_storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        ).'catalog/product'.$product->getImage();
    }

    public function getProductsInJSON($categoryId)
    {
        $data = [];

        if ($categoryId) {
            self::$product_page_limit = 6;
            self::$product_page_offset = 3;
            $data = $this->getProductsByCategoryId($categoryId);
        } else {
            $categories = $this->_categoryHelper->getStoreCategories();

            foreach ($categories as $category) {
                $categoryId = $category->getId();
                $data[] = [
                    'id'       => $categoryId,
                    'name'     => $category->getName(),
                    'products' => $this->getProductsByCategoryId($categoryId)
                ];
            }
        }

        return json_encode($data);
    }

    public function getProductsByCategoryId($categoryId)
    {
        $data = [];
        $products = $this->_categoryFactory->create()->load($categoryId)
            ->getProductCollection()
            ->addAttributeToSelect('*');

        $products->getSelect()
            ->limit(self::$product_page_limit, self::$product_page_offset);

        foreach ($products as $product) {
            $data[] = [
                'id'           => $product->getId(),
                'name'         => $product->getName(),
                'sku'          => $product->getSku(),
                'msrp'         => $product->getMsrp(),
                'price'        => $product->getPrice(),
                'image'        => $this->getProductImageUrl($product),
                'productUrl'   => $product->getProductUrl(),
                'addToCartUrl' => $this->_listProduct->getAddToCartUrl($product)
            ];
        }

        return $data;
    }
}
