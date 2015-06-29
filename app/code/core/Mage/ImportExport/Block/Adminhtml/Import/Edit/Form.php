<?php
    class Mage_ImportExport_Block_Adminhtml_Import_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
    {
        protected function _prepareForm()
        {
            $helper = Mage::helper('importexport');
            $form = new Varien_Data_Form(array(
                'id'      => 'edit_form',
                'action'  => $this->getUrl('*/*/validate'),
                'method'  => 'post',
                'enctype' => 'multipart/form-data'
            ));
            $fieldset = $form->addFieldset('base_fieldset', array('legend' => $helper->__('Import Settings')));
            $fieldset->addField('entity', 'select', array(
                'name'     => 'entity',
                'title'    => $helper->__('Entity Type'),
                'label'    => $helper->__('Entity Type'),
                'required' => true,
                'values'   => Mage::getModel('importexport/source_import_entity')->toOptionArray()
            ));
            $fieldset->addField('behavior', 'select', array(
                'name'     => 'behavior',
                'title'    => $helper->__('Import Behavior'),
                'label'    => $helper->__('Import Behavior'),
                'required' => true,
                'values'   => Mage::getModel('importexport/source_import_behavior')->toOptionArray()
            ));
            $fieldset->addField(Mage_ImportExport_Model_Import::FIELD_NAME_SOURCE_FILE, 'file', array(
                'name'     => Mage_ImportExport_Model_Import::FIELD_NAME_SOURCE_FILE,
                'label'    => $helper->__('Select File to Import'),
                'title'    => $helper->__('Select File to Import'),
                'required' => true
            ));
            /*$fieldset->addField(Mage_ImportExport_Model_Import::FIELD_NAME_IMG_ARCHIVE_FILE, 'file', array(
                'name'     => Mage_ImportExport_Model_Import::FIELD_NAME_IMG_ARCHIVE_FILE,
                'label'    => $helper->__('Select Image Archive File to Import'),
                'title'    => $helper->__('Select Image Archive File to Import'),
                'required' => false
            ));*/
    
            $form->setUseContainer(true);
            $this->setForm($form);
    
            return parent::_prepareForm();
        }
    }