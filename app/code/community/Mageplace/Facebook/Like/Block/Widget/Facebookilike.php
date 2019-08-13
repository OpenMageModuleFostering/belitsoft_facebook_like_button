<?php
/**
 * Mageplace Facebook "Like" Button
 *
 * @category	Mageplace_Facebook
 * @package		Mageplace_Facebook_Like
 * @copyright	Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license		http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Facebook_Like_Block_Widget_Facebookilike
	extends Mageplace_Facebook_Like_Block_Abstract
	implements Mage_Widget_Block_Interface
{
	protected function _prepareLayout()
	{
		parent::_prepareLayout();

		if (Mage::helper('facebookilike')->getCfg(Mageplace_Facebook_Core_Helper_Data::VAR_SHOW_WIDGET) && ($head = $this->getLayout()->getBlock('head'))) {
			$head->setChild('widget_facebookilikemeta', $this->getLayout()->createBlock('facebookilike/widget_facebookilikemeta'));
		}

		return $this;
	}
	
	protected function _toHtml()
	{
		if(Mage::helper('facebookilike')->getCfg(Mageplace_Facebook_Core_Helper_Data::VAR_SHOW_WIDGET)
			&& ($facebook = Mage::helper('facebookilike')->getFacebook()))
		{
			return $facebook->getFacebookHtml().parent::_toHtml();
		} else {
			return '';
		}
    }
}