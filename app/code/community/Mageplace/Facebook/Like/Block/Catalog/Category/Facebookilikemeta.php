<?php
/**
 * Mageplace Facebook "Like" Button
 *
 * @category	Mageplace_Facebook
 * @package		Mageplace_Facebook_Like
 * @copyright	Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license		http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Facebook_Like_Block_Catalog_Category_Facebookilikemeta extends Mageplace_Facebook_Like_Block_Abstract
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->setTemplate('facebookilike/catalog/category/facebookilikemeta.phtml');
	}

	protected function _getMetaTagsContent($property, $content)
	{
		switch($property) {
			case 'type':
				return $content ? $content : 'product';

			case 'image':
				$image = null;
				if($current_category = Mage::registry('current_category')) {
					$image = $current_category->getImageUrl();
				}
				return $image ? $image : $content;

			case 'admins':
			case 'app_id':
			default:
				return $content;
		}
	}
}
