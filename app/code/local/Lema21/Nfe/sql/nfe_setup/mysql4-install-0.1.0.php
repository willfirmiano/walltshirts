<?php

$installer = $this;
$installer->startSetup();

// add field nfe_number
$installer->getConnection()
    ->addColumn(
        $installer->getTable('sales/order'),
        'nfe_number',
        'varchar(255) NULL'
    );

// add field nfe_access_key
$installer->getConnection()
    ->addColumn(
        $installer->getTable('sales/order'),
        'nfe_access_key',
        'varchar(255) NULL'
    );

// add field nfe_status    
$installer->getConnection()
    ->addColumn(
        $installer->getTable('sales/order'),
        'nfe_status',
        'varchar(255) NULL'
    );
$installer->endSetup();
