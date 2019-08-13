<?php
/**
 * Mageplace Facebook "Like" Button
 *
 * @category	Mageplace_Facebook
 * @package		Mageplace_Facebook_Like
 * @copyright	Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license		http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Facebook_Like_Helper_Data extends Mageplace_Facebook_Core_Helper_Data
{
	const VAR_PLUGIN_CODE		= 'plugin_code';
	const VAR_SEND				= 'send';
	const VAR_SITE_NAME			= 'site_name';
	const VAR_LAYOUT			= 'layout';
	const VAR_SHOW_FACES		= 'show_faces';
	const VAR_ACTION			= 'action';
	const VAR_FONT				= 'font';
	const VAR_COLORSCHEME		= 'colorscheme';
	const VAR_META_TITLE		= 'title';
	const VAR_META_TYPE			= 'type';
	const VAR_META_URL			= 'url';
	const VAR_META_IMAGE		= 'image';
	const VAR_META_SITE_NAME	= 'site_name';
	const VAR_HREF				= 'href';
	const VAR_META_ADMINS		= 'admins';


	public function __construct()
	{
		$this->setExtension(Mageplace_Facebook_Core_Helper_Data::EXTENSION_LIKE);
	}

	public function getCfg($config, $default=null, $tab=null, $extension=null)
	{
		$value = parent::getCfg($config, $default);

		switch($config) {
			case (self::VAR_HREF):	
				$value = rawurldecode($value);
			break;
		
			case (self::VAR_SEND):
				$value = intval($value);
			break;

			case (self::VAR_SITE_NAME):
				$value = $value ? $value : Mage::getStoreConfig('general/store_information/name', Mage::app()->getStore());
			break;
		}

		if(is_null($value)) {
			$value = '';
		}

		return $value;
	}
}
