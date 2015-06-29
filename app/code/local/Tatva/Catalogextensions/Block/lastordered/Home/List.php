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
	class Tatva_Catalogextensions_Block_Lastordered_Home_List extends Tatva_Catalogextensions_Block_Promotional_List
	{
		
		protected function _getProductCollection()
    	{
        	parent::__construct();
        	$storeId    = Mage::app()->getStore()->getId();
			$resource = Mage::getSingleton('core/resource');
			$read = Mage::getSingleton('core/resource')->getConnection('core_read');
			$orderItemTable = $resource->getTableName('sales/order_item');
			$demotb = $read->select()->from(array('coi'=>$orderItemTable),array('product_id','item_id'))->group('product_id');
			$products = Mage::getModel('catalog/product')->getCollection()
							->setStoreId($storeId)
            				->addStoreFilter($storeId)
							->addAttributeToSelect(array('name', 'price', 'small_image'));
							
			
			Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($products);
       		Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($products);
			
			$products->getSelect()
					->join(array('coi'=>$orderItemTable),'e.entity_id=coi.product_id',array('item_id'))
					->order($this->get_order().' '.$this->get_order_dir());
					
					
			if(Mage::getStoreConfig('catalogextensions/config8/max_product'))
	        {
	            $products->setPageSize(Mage::getStoreConfig('catalogextensions/config8/max_product'));
	        }
			
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
			return (isset($_REQUEST['order'])) ? ($_REQUEST['order']) : 'item_id';
		}// get_order

    	function get_order_dir()
		{
			return (isset($_REQUEST['dir'])) ? ($_REQUEST['dir']) : 'desc';
		}// get_direction

		public function getToolbarHtml()
    	{
        
    	}
}