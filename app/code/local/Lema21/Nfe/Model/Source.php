<?php
class Lema21_Nfe_Model_Source
{
	public function toOptionArray($selector=false)
	{
		$states = Mage::getSingleton('sales/order_config')->getStates();
		$options = array();
		foreach( $states as $_key => $_val)
		{
			$options[] = array('value' => $_key, 'label' => $_val . " ($_key)");
		}

		if($isMultiSelect)
		{
			array_unshift($options, array('value'=>'', 'label'=> Mage::helper('adminhtml')->__('--Please Select--')));
		}

		return $options;
	}
}