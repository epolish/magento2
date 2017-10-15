<?php

namespace Eleanorsoft\Promos\Block;

class Products extends \Magento\Framework\View\Element\Template
{
    protected $_date;
    protected $_filterBuilder;
    protected $_productStatus;
    protected $_searchCriteria;
    protected $_productVisibility;
    protected $_productRepository;
    protected $_filterGroupBuilder;

    protected $_collectionFactory;

    public function __construct(
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Api\FilterBuilder $filterBuilder,
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Data\CollectionFactory $collectionFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Api\Search\FilterGroupBuilder $filterGroupBuilder,
        \Magento\Catalog\Model\Product\Attribute\Source\Status $productStatus
    ) {
        $this->_date               = $date;
        $this->_filterBuilder      = $filterBuilder;
        $this->_productStatus      = $productStatus;
        $this->_searchCriteria     = $searchCriteria;
        $this->_collectionFactory  = $collectionFactory;
        $this->_productVisibility  = $productVisibility;
        $this->_productRepository  = $productRepository;
        $this->_filterGroupBuilder = $filterGroupBuilder;

        parent::__construct($context);
    }

    public function getProducts()
    {
        $currentDate = $this->_date->gmtDate('Y-m-d');

        $withSpecialPrice = $this->_filterGroupBuilder->create();
        $withSpecialPrice->setFilters(
            [
                $this->_filterBuilder
                    ->setField('special_price')
                    ->setConditionType('gt')
                    ->setValue(0)
                    ->create()
            ]
        );

        $withVisibleStatus = $this->_filterGroupBuilder->create();
        $withVisibleStatus->setFilters(
            [
                $this->_filterBuilder
                    ->setField('status')
                    ->setConditionType('in')
                    ->setValue($this->_productStatus->getVisibleStatusIds())
                    ->create()
            ]
        );

        $visibleInCatalog = $this->_filterGroupBuilder->create();
        $visibleInCatalog->setFilters(
            [
                $this->_filterBuilder
                    ->setField('visibility')
                    ->setConditionType('in')
                    ->setValue($this->_productVisibility->getVisibleInSiteIds())
                    ->create()
            ]
        );

        $specialPriceFromDate = $this->_filterGroupBuilder->create();
        $specialPriceFromDate->setFilters(
            [
                $this->_filterBuilder
                    ->setField('special_from_date')
                    ->setConditionType('null')
                    ->setValue('')
                    ->create(),
                $this->_filterBuilder
                    ->setField('special_from_date')
                    ->setConditionType('lteq')
                    ->setValue($currentDate)
                    ->create()
            ]
        );

        $specialPriceToDate = $this->_filterGroupBuilder->create();
        $specialPriceToDate->setFilters(
            [
                $this->_filterBuilder
                    ->setField('special_to_date')
                    ->setConditionType('null')
                    ->setValue('')
                    ->create(),
                $this->_filterBuilder
                    ->setField('special_to_date')
                    ->setConditionType('gteq')
                    ->setValue($currentDate)
                    ->create()
            ]
        );

        $this->_searchCriteria->setFilterGroups([
            $withSpecialPrice,
            $withVisibleStatus,
            $visibleInCatalog,
            $specialPriceFromDate,
            $specialPriceToDate
        ]);

        return $this->_productRepository->getList($this->_searchCriteria)->getItems();
    }

    public function getProductCollection()
    {
        $products = $this->getProducts();
        $collection = $this->_collectionFactory->create();

        foreach ($products as $product) {
            $collection->addItem($product);
        }

        return $collection;
    }
}
