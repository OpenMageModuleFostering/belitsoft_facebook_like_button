<?php
/**
 * Mageplace Facebook "Like" Button
 *
 * @category	Mageplace_Facebook
 * @package		Mageplace_Facebook_Like
 * @copyright	Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license		http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Facebook_Like_Model_System_Config_Source_Font
{
	/**
	 * Options getter
	 *
	 * @return array
	 */
	public function toOptionArray()
	{
		return array(
			array('value' => '',				'label' => ''),
			array('value' => 'arial',			'label' => 'arial'),
			array('value' => 'lucida grande',	'label' => 'lucida grande'),
			array('value' => 'segoe ui',		'label' => 'segoe ui'),
			array('value' => 'tahoma',			'label' => 'tahoma'),
			array('value' => 'trebuchet ms',	'label' => 'trebuchet ms'),
			array('value' => 'verdana',			'label' => 'verdana'),
		);
	}

}
