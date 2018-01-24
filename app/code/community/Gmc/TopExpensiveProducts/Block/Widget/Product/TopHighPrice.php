<?php
/**
 * High Price Products widget module
 *
 * Widget block
 *
 * @category    Gmc
 * @package     Gmc_TopExpensiveProducts
 * @copyright   Copyright (c) 2018 Marius Grad
 */
class Gmc_TopExpensiveProducts_Block_Widget_Product_TopHighPrice 
    extends Mage_Catalog_Block_Product_New
    implements Mage_Widget_Block_Interface
{
    /**
     * Initialize block's cache and template settings
     */
    protected function _construct()
    {
        parent::_construct();
        $this->addPriceBlockType('bundle', 'bundle/catalog_product_price', 'bundle/catalog/product/price.phtml');
    }
    
    /**
     * Product collection initialize process
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection|Object|Varien_Data_Collection
     */
    protected function _getProductCollection()
    {
        /** @var $collection Mage_Catalog_Model_Resource_Product_Collection */
        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection
                ->setVisibility(Mage::getSingleton('catalog/product_visibility')
                ->getVisibleInCatalogIds());
        
        $this->_setCategoryFilter($collection);
        
        $this->_addProductAttributesAndPrices($collection)
            ->addStoreFilter()
            ->addAttributeToSort('price', 'desc')
            ->setPageSize($this->getProductsCount())
            ->setCurPage(1)
        ;
        return $collection;
    }
    
    /**
     * Set the category chosen from widget to product collection
     * 
     * @param Mage_Catalog_Model_Resource_Product_Collection $collection
     * @return $this
     */
    private function _setCategoryFilter(
            Mage_Catalog_Model_Resource_Product_Collection $collection)
    {
        if (!$this->hasData('category_id')) {
            return $this;
        }
        
        $category = Mage::getModel('catalog/category')
                ->load($this->getData('category_id'));
        
        if ($category->getId()) {
            $collection->addCategoryFilter($category);
        }
        
        return $this;
    }
    
    /**
     * Get key pieces for caching block content
     *
     * @return array
     */
    public function getCacheKeyInfo()
    {
        return array_merge(parent::getCacheKeyInfo(), array(
            $this->getDisplayType(),
            $this->getProductsPerPage(),
            intval($this->getRequest()->getParam(self::PAGE_VAR_NAME))
        ));
    }
    
    /**
     * Retrieve how much products should be displayed
     *
     * @return int
     */
    public function getProductsCount()
    {
        if (!$this->hasData('products_count')) {
            return parent::getProductsCount();
        }
        
        return $this->getData('products_count');
    }
}
