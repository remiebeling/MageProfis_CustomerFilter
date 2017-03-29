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

    public function getShowPrice($_product)
    {
        if(!$_product->getAttributeText('rc_show_rrp'))
        {
            return true;
        }
        $options = array(
            'ja' => true,
            'yes' => true,
            'oui' => true,
            'si' => true,
        );

        $storeText = strtolower($_product->getAttributeText('rc_show_rrp'));

        if(isset($options[$storeText]))
        {
            return $options[$storeText];
        }
        else {
            return false;
        }
    }
}
