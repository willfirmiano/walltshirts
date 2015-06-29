<?php
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();

$setup->addAttributeGroup('catalog_product', 'Default', 'Nf-e', 1000);

$options['value']['option1'][0] = 'PÇ';
$options['value']['option2'][0] = 'KG';
$setup->addAttribute(
    'catalog_product',
    'operation_unit',
array(
  'group'         => 'Nf-e',
  'label'                   => 'Nf-e Unidade / operation_unit',
  'type'                       => 'varchar',
  'input'                     => 'select',
  'backend'                   => 'eav/entity_attribute_backend_array',
  'frontend'                   => '',
  'global'                    => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
  'visible'                 => true,
  'required'                 => false,
  'user_defined'             => true,
  'unique'                => false,
  'searchable'               => true,
  'default'                    => '',
  'filterable'                => false,
  'comparable'                 => false,
  'visible_on_front'           => true,
  'filterable_in_search'       => false,
  'used_in_product_listing'  => true,
  'visible_in_advanced_search' => false,
   'sort_order'                         => 100,
  'position'                             => 1,
  'apply_to'                            => array('bundle,virtual,simple,configurable'),
                'option'                              => $options
)
);

$setup->addAttribute('catalog_product', 'operation_name', array(
    'group'         => 'Nf-e',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Nf-e Descrição / operation_name',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'searchable' => 0,
    'filterable' => 0,
    'comparable'    => 0,
    'visible_on_front' => 0,
    'visible_in_advanced_search'  => 0,
    'is_html_allowed_on_front' => 0,
    'sort_order'                         => 101,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$setup->addAttribute('catalog_product', 'codigo_ncm', array(
    'group'         => 'Nf-e',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Nf-e Classificação fiscal / codigo_ncm',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'searchable' => 0,
    'filterable' => 0,
    'comparable'    => 0,
    'visible_on_front' => 0,
    'visible_in_advanced_search'  => 0,
    'is_html_allowed_on_front' => 0,
    'sort_order'                         => 102,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$setup->addAttribute('catalog_product', 'codigo_origem', array(
    'group'         => 'Nf-e',
    'input'         => 'text',
    'type'          => 'text',
    'label'         => 'Nf-e Código Origem / codigo_origem',
    'backend'       => '',
    'visible'       => 1,
    'required'      => 0,
    'user_defined' => 1,
    'searchable' => 0,
    'filterable' => 0,
    'comparable'    => 0,
    'visible_on_front' => 0,
    'visible_in_advanced_search'  => 0,
    'is_html_allowed_on_front' => 0,
    'sort_order'                         => 103,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
));

$installer->endSetup();