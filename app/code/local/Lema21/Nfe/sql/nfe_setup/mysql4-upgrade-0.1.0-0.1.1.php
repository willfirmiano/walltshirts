<?php
 
$installer = $this;
 
$installer->startSetup();

//create column
$installer->getConnection()
    ->addColumn(
        $installer->getTable('sales_flat_order_grid'),
        'nfe_number',
        'varchar(255) NULL'
    );

$installer->endSetup();
