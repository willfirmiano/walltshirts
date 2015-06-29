<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of List
 *
 * @author om
 */
class Tatva_Catalogextensions_Block_Mostviewed_Home_List extends Tatva_Catalogextensions_Block_Mostviewed_List
{

		protected function _getProductCollection()
    	{
        	parent::__construct();
        	$storeId    = Mage::app()->getStore()->getId();
        	$products = Mage::getResourceModel('reports/product_collection')  
            	->addAttributeToSelect('*')
            	->addAttributeToSelect(array('name', 'price', 'small_image'))
            	->setStoreId($storeId)
            	->addStoreFilter($storeId)
            	->addViewsCount();

        	if(Mage::getStoreConfig('catalogextensions/config3/max_product'))
        	{
            	$products->setPageSize(Mage::getStoreConfig('catalogextensions/config3/max_product'));
        	}


	        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
    	    Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
        	Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($products);

        	$this->_productCollection = $products;

	        return $this->_productCollection;
    	}

    function get_prod_count()
	{
		//unset any saved limits
	    Mage::getSingleton('catalog/session')->unsLimitPage();
	    return (isset($_REQUEST['limit'])) ? intval($_REQUEST['limit']) : 9;
	}// get_prod_count

	function get_cur_page()
	{
		return (isset($_REQUEST['p'])) ? intval($_REQUEST['p']) : 1;
	}// get_cur_page

    function get_order()
	{
		return (isset($_REQUEST['order'])) ? ($_REQUEST['order']) : 'views';
	}// get_order

    function get_order_dir()
	{
		return (isset($_REQUEST['dir'])) ? ($_REQUEST['dir']) : 'desc';
	}// get_direction

	public function getToolbarHtml()
    {
        
    }
}