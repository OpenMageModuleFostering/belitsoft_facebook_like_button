<?php
/**
 * Mageplace Facebook "Like" Button
 *
 * @category	Mageplace_Facebook
 * @package		Mageplace_Facebook_Like
 * @copyright	Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license		http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Facebook_Like_Model_System_Config_Source_Plugincode
{
	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
	{
		return array(
			array('value' => 'xfbml',	'label' => Mage::helper('facebookilike')->__('XFBML')),
			array('value' => 'iframe',	'label' => Mage::helper('facebookilike')->__('iframe')),
			array('value' => 'html5', 'label' => Mage::helper('facebookilike')->__('HTML5')),
		);
	}

}
