<?php
class BIS2BIS_Gmerchant_Model_Resource_Eav_Mysql4_Setup extends Mage_Catalog_Model_Resource_Eav_Mysql4_Setup
{
    public function getDefaultEntities()
    {
        return array(
            'catalog_product' => array(
                'entity_model'      => 'catalog/product',
                'attribute_model'   => 'catalog/resource_eav_attribute',
                'table'             => 'catalog/product',
                'additional_attribute_table' => 'catalog/eav_attribute',
                'entity_attribute_collection' => 'catalog/product_attribute_collection',
                'attributes'        => array(
                    'google_categoria' => array(
                        'group'             => 'General',
                        'label'             => 'Google Categoria',
                        'type'              => 'int',
                        'input'             => 'text',
                        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
                        'visible'           => false,
                        'required'          => false,
                        'user_defined'      => false,
                        'searchable'        => false,
                        'filterable'        => false,
                        'comparable'        => false,
                        'visible_on_front'  => false,
                        'visible_in_advanced_search' => false,
                        'unique'            => false,
                        'note'              => ''
                    ),
                ),
            ),
        );
    }
}