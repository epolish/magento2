<?php

namespace Eleanorsoft\Featured\Setup;

use Eleanorsoft\Featured\Helper\Config;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $_store;
    private $_category;
    private $_writerInterface;
    private $_categoryRepository;
    private $categoryName = 'Featured';

    public function __construct(
        \Magento\Store\Model\Store $store,
        \Magento\Catalog\Model\Category $category,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        \Magento\Framework\App\Config\Storage\WriterInterface $writerInterface
    ) {
        $this->_store = $store;
        $this->_category = $category;
        $this->_writerInterface = $writerInterface;
        $this->_categoryRepository = $categoryRepository;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->_category->setIsActive(true);
        $this->_category->setIncludeInMenu(false);
        $this->_category->setName($this->categoryName);
        $this->_category->setParentId($this->_store->getRootCategoryId());
        $this->_categoryRepository->save($this->_category);
        $this->_writerInterface->save(Config::CATEGORY_CONFIG_PATH, $this->_category->getId());
    }
}
