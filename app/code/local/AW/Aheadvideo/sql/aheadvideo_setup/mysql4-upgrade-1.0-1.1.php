<?php

/**
 * aheadWorks Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://ecommerce.aheadworks.com/LICENSE-M1.txt
 *
 * @category   AW
 * @package    AW_Featuredproducts
 * @copyright  Copyright (c) 2008-2009 aheadWorks Co. (http://www.aheadworks.com)
 * @license    http://ecommerce.aheadworks.com/LICENSE-M1.txt
 */
$installer = $this;

/* $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();


if (version_compare(Mage::getVersion(), '1.4.0.0', '>=')) {

$stmt = $installer->getConnection()->select()
    ->from($installer->getTable('eav/attribute'))
    ->where("attribute_code = 'aw_video'");


$result = $installer->getConnection()->fetchAll($stmt);

foreach ($result as $row) {

    $whereBind = $installer->getConnection()->quoteInto('attribute_id=?', $row['attribute_id']);
    $installer->getConnection()->update($installer->getTable('catalog/eav_attribute'),
        array('is_html_allowed_on_front'=> true,
                  'is_global' => true,
                  'is_visible' => true,
                  'is_visible_on_front' => true),
        $whereBind
    );
}

}

$installer->endSetup();
