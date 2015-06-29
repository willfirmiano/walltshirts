<?php
class Lema21_Nfe_Model_Observer
{
        public function addMassAction($observer)
        {
                $block = $observer->getEvent()->getBlock();
                if(get_class($block) =='Mage_Adminhtml_Block_Widget_Grid_Massaction'
                && $block->getRequest()->getControllerName() == 'sales_order')
                {
                        $block->addItem('nfe', array(
                'label' => 'Gera���o de Nf-e',
                'url' => Mage::app()->getStore()->getUrl('nfe/adminhtml_nfe/sendMass'),
                        ));
                }
        }
}