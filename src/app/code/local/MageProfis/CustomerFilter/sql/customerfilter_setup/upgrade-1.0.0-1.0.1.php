<?php

$installer = $this;
$installer->startSetup();

$entityTypeId = $installer->getEntityTypeId('customer');
$installer->addAttribute($entityTypeId, 'cyclus', array(
    'type'  => 'int',
    'label' => 'Cyclus Tools',
    'input' => 'select',
    'visible' => 1,
    'required' => 0,
    'default_value' => 0,
    'adminhtml_only' => 0,
    'position' => 100,
    'user_defined' => 1,
    'unique' => 0,
    'group' => 'General',
    'source' => 'eav/entity_attribute_source_boolean',
));
$priceListAttr = Mage::getSingleton('eav/config')->getAttribute($entityTypeId, 'cyclus');
$priceListAttr
    ->setData('used_in_forms', array('adminhtml_customer'))
    ->save();

$installer->endSetup();

