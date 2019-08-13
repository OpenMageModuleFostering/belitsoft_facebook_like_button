<?php
/**
 * Mageplace Facebook "Like" Button
 *
 * @category	Mageplace_Facebook
 * @package		Mageplace_Facebook_Like
 * @copyright	Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license		http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Facebook_Like_Block_Custom_Facebookilike extends Mageplace_Facebook_Like_Block_Abstract
{
	protected function _prepareLayout()
	{
		parent::_prepareLayout();

		if (Mage::helper('facebookilike')->getCfg(Mageplace_Facebook_Core_Helper_Data::VAR_SHOW_CUSTOM)
			&& (!Mage::registry('mageplace_facebookilikemeta_inserted'))
			&& ($head = $this->getLayout()->getBlock('head')))
		{
			$head->setChild('custom_facebookilikemeta', $this->getLayout()->createBlock('facebookilike/custom_facebookilikemeta'));
			
			Mage::register('mageplace_facebookilikemeta_inserted', true);
		}

		return $this;
	}
	
	protected function _toHtml()
	{
		if(Mage::helper('facebookilike')->getCfg(Mageplace_Facebook_Core_Helper_Data::VAR_SHOW_CUSTOM)
			&& ($facebook = Mage::helper('facebookilike')->getFacebook()))
		{
			return $facebook->getFacebookHtml() . parent::_toHtml();
		} else {
			return '';
		}
    }
}
