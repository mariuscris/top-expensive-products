<?php
/**
 * High Price Products widget module
 *
 * Category List
 *
 * @category    Gmc
 * @package     Gmc_TopExpensiveProducts
 * @copyright   Copyright (c) 2018 Marius Grad
 */
class Gmc_TopExpensiveProducts_Model_System_Config_Source_Category
{
    const PREFIX = "--";

    /**
     * Select options
     * 
     * @return array
     */
    public function toOptionArray()
    {
        $rootCategory = Mage::getModel('catalog/category')
                ->loadByAttribute('parent_id', 0);

        $options = $this->_getCategoryTreeAsOptionArray($rootCategory);

        array_unshift($options, array(
            "label" => Mage::helper("Gmc_TopExpensiveProducts")->__("-- Please Select a Category --"),
            "value" => "",
        ));

        return $options;
    }

    /**
     * Recursively traverse the category tree and return all categories 
     *
     * @param Mage_Catalog_Model_Category $category
     * @param int                         $level
     *
     * @return array
     */
    private function _getCategoryTreeAsOptionArray(
        Mage_Catalog_Model_Category $category, 
        $level = 0)
    {
        $options = array();

        if (!$category->hasChildren()) {
            return $options;
        }
        
        foreach ($category->getChildrenCategories() as $child) {
            $options[] = array(
                'label' => sprintf(
                        "%s %s", 
                        str_repeat(self::PREFIX, $level), 
                        $child->getName()),
                'value' => $child->getId()
            );

            $options = array_merge(
                    $options, 
                    $this->_getCategoryTreeAsOptionArray($child, $level + 1)
            );
        }
        
        return $options;
    }
}
