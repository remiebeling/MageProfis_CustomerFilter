<?php

class MageProfis_CustomerFilter_Model_Filter extends Mage_Core_Model_Abstract
{
    public function getIdsToRemove($scope = 'products')
    {
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $cat_ids = "";
        if($customer && $customer->getId())
        {
            $mappings = $this->getMappings();
            $idsToRemove = array();
            $cat_ids = array();
            foreach($mappings as $_mapping)
            {
                
                if($customer->getData($_mapping['attr_code']) == 0 || !$customer->getData($_mapping['attr_code']))
                {
                    /*$categories = Mage::getModel('catalog/category')->getCollection()
                        ->addAttributeToFilter('path', array('like' => '1/2/' . $_mapping['cat_id'] . '/%'))
                        ->getAllIds();    
                    
                    if(count($categories > 0))
                    {
                      $cat_ids = array_merge($cat_ids, $categories);
                    }*/
                    $cat_ids[] = $_mapping['cat_id'];
                }
            }
            $cat_ids = array_unique($cat_ids);
            if($scope == 'categories')
            {
                return $cat_ids;
            }
        }
        
        //get Product ids
        $product_ids = array();
        
	$resource = Mage::getSingleton('core/resource');
	$tableName = $resource->getTableName('catalog_category_product');
        
        $where = $this->_readConnection()->quoteInto('category_id IN (?)', $cat_ids);
        $_product_ids = $this->_readConnection()
                    ->select()->from($tableName, 'product_id')->where($where);
        
        return $this->_readConnection()->fetchCol($_product_ids);
    }
    
    public function getMappings()
    {
        $setting = Mage::getStoreConfig('customerfilter/hidden_categories/mapping');
        $cats = array();
        if ($setting)
        {
            $setting = unserialize($setting);
            if (is_array($setting))
            {
                foreach ($setting as $cat)
                {
                    $cats[] = $cat;
                }
                return $cats;
            }
            return false;
        }
    }
    
    public function isVisible($product)
    {
        if(in_array($product->getId(), $this->getIdsToRemove()))
        {
            return false;
        }
        return true;
    }
    
    public function isCategoryVisible($category)
    {
        if(in_array($category->getId(), $this->getIdsToRemove('categories')))
        {
            return false;
        }
        return true;
    }
    
    /**
     *
     * @return Mage_Core_Model_Resource
     */
    protected function _resource()
    {
        if (is_null($this->_resource)) {
            $this->_resource = Mage::getSingleton('core/resource');
        }
        return $this->_resource;
    }

    /**
     *
     * @param string $name
     * @return string
     */
    protected function getTableName($name)
    {
        return $this->_resource()->getTableName($name);
    }

    /**
     *
     * @return Varien_Db_Adapter_Interface
     */
    protected function _readConnection()
    {
        return $this->_resource()->getConnection('core_read');
    }

    /**
     *
     * @return Varien_Db_Adapter_Interface
     */
    protected function _writeConnection()
    {
        return $this->_resource()->getConnection('core_read');
    }
}

