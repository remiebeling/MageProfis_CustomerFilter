<?php

class MageProfis_CustomerFilter_Model_Observer
{
    /**
     * Add the Attribute Filter to Product collections
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function catalogProductCollectionLoadBefore(Varien_Event_Observer $observer)
    {
        if(!Mage::app()->getStore()->isAdmin())
        {
            $remove = Mage::getModel('customerfilter/filter')->getIdsToRemove('products');
            if(count($remove) > 0)
            {
                $collection = $observer->getCollection()
                    ->addAttributeToFilter('entity_id', array('nin' => $remove));
            }
        }
    }
    
    /**
     * Add the groupscatalog filter sql to category collections
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function catalogCategoryCollectionLoadBefore(Varien_Event_Observer $observer)
    {
        if(!Mage::app()->getStore()->isAdmin())
        {
            $remove = Mage::getModel('customerfilter/filter')->getIdsToRemove('categories');
            if(count($remove) > 0)
            {
                $collection = $observer->getCategoryCollection();
                if($collection)
                {
                    $collection->addAttributeToFilter('entity_id', array('nin' => $remove));
                }
                    
            }
        }
        
    }
    
    /**
     * "Unload" a loaded product if the customer is not allowed to view it
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function catalogProductLoadAfter(Varien_Event_Observer $observer)
    {
        if (Mage::app()->getRequest()->getControllerName() == 'product' && !Mage::app()->getStore()->isAdmin()) 
        {
            $product = $observer->getProduct();
            if(!Mage::getModel('customerfilter/filter')->isVisible($product))
            {
                Mage::getSingleton('core/session')->addNotice(Mage::helper('customerfilter')->__('this product is not longer available'));
                Mage::app()->getResponse()
                ->setRedirect(Mage::getUrl(''))
                ->sendResponse();
                exit;
            }
        }
    }
        
    
    /**
     * "Unload" a loaded category if the customer is not allowed to view it
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function catalogCategoryLoadAfter(Varien_Event_Observer $observer)
    {
        if (Mage::app()->getRequest()->getControllerName() == 'category' && !Mage::app()->getStore()->isAdmin()) 
        {
        $category = $observer->getCategory();
        if(!Mage::getModel('customerfilter/filter')->isCategoryVisible($category))
        {
            Mage::getSingleton('core/session')->addNotice(Mage::helper('customerfilter')->__('this category is not longer available'));
            Mage::app()->getResponse()
            ->setRedirect(Mage::getUrl(''))
            ->sendResponse();
            exit;
        }
        }
        
    }
    
    
    
    /**
     * Remove products that are in the cart that where not hidden while logged out
     * but are hidden to the customer once logged in.
     *
     * @param Varien_Event_Observer $observer
     */
    public function salesQuoteMergeBefore(Varien_Event_Observer $observer)
    {
        /** @var Mage_Sales_Model_Quote $guestQuote */
        $guestQuote = $observer->getSource();
        foreach ($guestQuote->getItemsCollection() as $quoteItem) {
            if(in_array($quoteItem->getProductId(), Mage::getModel('customerfilter/filter')->getIdsToRemove('categories')))
            {
                $quoteItem->isDeleted(true);
            }
            
        }
    }
    
      /**
     * Add the groupscatalog filter to the wishlist item collection.
     *
     * This event only exists because of the rewrite of the wishlist item collection. The event
     * prefix and object properties are not set in the core. A contribution patch is on its way, though.
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function wishlistItemCollectionLoadBefore(Varien_Event_Observer $observer)
    {
        /* @var $collection Mage_Wishlist_Model_Resource_Item_Collection */
        $collection = $observer->getCollection();
      
        $remove = Mage::getModel('customerfilter/filter')->getIdsToRemove('products');
        if(count($remove) > 0)
        {
            if($collection)
            {
                $collection->addFieldToFilter('product_id', array('nin' => $remove));
            }
        }
    }

}
