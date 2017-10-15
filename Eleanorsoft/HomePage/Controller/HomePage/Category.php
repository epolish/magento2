<?php

namespace Eleanorsoft\HomePage\Controller\HomePage;

class Category extends \Magento\Framework\App\Action\Action
{
    protected $_homePageCategory;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Eleanorsoft\HomePage\Model\Category $homePageCategory
    ) {
        $this->_homePageCategory = $homePageCategory;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->resultFactory->create(
            \Magento\Framework\Controller\ResultFactory::TYPE_JSON
        );
        $resultJson->setData($this->_homePageCategory->getProductsInJSON(
            $this->getRequest()->getParam('id')
        ));

        return $resultJson;
    }
}
