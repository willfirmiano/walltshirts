<?php
class Lema21_Nfe_Block_Adminhtml_Sales_Order_Grid
extends Mage_Adminhtml_Block_Sales_Order_Grid
{
	protected function _prepareMassaction()
	{
		parent::_prepareMassaction();
		$this->getMassactionBlock()->addItem(
                'nfe',
		array('label' => $this->__('Emitir Nf-e'),
                      'url'   => $this->getUrl('nfe/adminhtml_nfe/sendMass')
		)
		);
	}
}