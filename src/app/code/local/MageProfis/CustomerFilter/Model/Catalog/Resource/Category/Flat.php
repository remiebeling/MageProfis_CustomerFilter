<?php
class MageProfis_CustomerFilter_Model_Catalog_Resource_Category_Flat
    extends Mage_Catalog_Model_Resource_Category_Flat
{
    /**
     * We need to rewrite this class to be able to filter hidden categories if the
     * flat catalog category is enabled.
     * 
     * @param Mage_Catalog_Model_Category|int $parentNode
     * @param integer $recursionLevel
     * @param integer $storeId
     * @param bool $onlyActive
     * @return Mage_Catalog_Model_Resource_Category_Flat
     */
    protected function _loadNodes($parentNode = null, $recursionLevel = 0, $storeId = 0, $onlyActive = true)
    {
        if(!Mage::app()->getStore()->isAdmin())
        {
            $nodes = parent::_loadNodes($parentNode, $recursionLevel, $storeId, $onlyActive);
            $remove = Mage::getModel('customerfilter/filter')->getIdsToRemove('categories');
            if(count($remove > 0))
            {
                foreach($nodes as $key => $node)
                {
                    if(in_array($node['entity_id'], $remove))
                    {
                        unset($nodes[$key]);
                    }
                }   
        
            }
            
        }
        return $nodes;
    }
    
    
}