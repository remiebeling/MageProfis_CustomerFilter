<?php

class MageProfis_CustomerFilter_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getMapping()
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
}
