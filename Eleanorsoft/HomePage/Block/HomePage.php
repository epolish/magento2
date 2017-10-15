<?php

namespace Eleanorsoft\HomePage\Block;

class HomePage extends \Magento\Framework\View\Element\Template
{
    protected $_promos;
    protected $_featured;
    protected $_mediaUrl;
    protected $_bestSellers;
    protected $_listProduct;
    protected $_limitedStock;
    protected $_monthlySpecials;
    protected $_slidesCollection;

    public function __construct(
        \Eleanorsoft\Promos\Block\Products $promos,
        \Eleanorsoft\Featured\Block\Products $featured,
        \Eleanorsoft\BestSellers\Block\Products $bestSellers,
        \Eleanorsoft\LimitedStock\Block\Products $limitedStock,
        \Magento\Catalog\Block\Product\ListProduct $listProduct,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Element\Template\Context $context,
        \Eleanorsoft\MonthlySpecials\Block\Products $monthlySpecials,
        \Eleanorsoft\MainSlider\Model\Resource\Item\Collection $slidesCollection
    ) {
        $this->_promos           = $promos;
        $this->_featured         = $featured;
        $this->_bestSellers      = $bestSellers;
        $this->_listProduct      = $listProduct;
        $this->_limitedStock     = $limitedStock;
        $this->_monthlySpecials  = $monthlySpecials;
        $this->_slidesCollection = $slidesCollection;

        $this->_mediaUrl = $storeManager->getStore()->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );

        parent::__construct($context);
    }

    public function getMediaUrl($path = '')
    {
        return $this->_mediaUrl.$path;
    }

    public function getProducts($resourceName)
    {
        return $this->{'_'.$resourceName}->getProducts();
    }

    public function getProductAddToCartUrl($product)
    {
        return $this->_listProduct->getAddToCartUrl($product);
    }

    public function getSlidesCollection()
    {
        return $this->_slidesCollection->getActiveOrderedSlides();
    }

    public function getProductImage($product)
    {
        return $this->getUrl('pub/media/catalog').'product'.$product->getImage();
    }

    public function getFirmsCollection()
    {
        $collection = [];

        for ($i = 0; $i < 4; $i++) {
            $item = new \stdClass();
            $item->name = 'firm'.($i+1);
            $item->image = $this->getViewFileUrl('images/'.$item->name.'.png');
            $collection[$i] = $item;
        }

        return $collection;
    }
}
