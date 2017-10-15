<?php

namespace Eleanorsoft\Promos\Controller\Index;

class Index extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_productCollection;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Eleanorsoft\Promos\Block\Products $productsCollection,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    )
    {
        $this->_pageFactory       = $pageFactory;
        $this->_productCollection = $productsCollection;

        parent::__construct($context);
    }

    public function execute()
    {
        $result = $this->_pageFactory->create();

        $result->getConfig()->getTitle()->set(__('Promos Product List'));

        return $result;
    }
}
