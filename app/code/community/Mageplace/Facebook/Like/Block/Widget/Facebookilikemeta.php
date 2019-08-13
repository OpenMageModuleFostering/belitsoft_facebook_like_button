<?php
/**
 * Mageplace Facebook "Like" Button
 *
 * @category	Mageplace_Facebook
 * @package		Mageplace_Facebook_Like
 * @copyright	Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license		http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Facebook_Like_Block_Widget_Facebookilikemeta extends Mageplace_Facebook_Like_Block_Abstract
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		$this->setTemplate('facebookilike/widget/facebookilikemeta.phtml');
	}

	protected function _getMetaTagsContent($property, $content)
	{
		switch($property) {
			case 'type':
				return $content ? $content : 'article';

			case 'admins':
			case 'app_id':
			default:
				return $content;
		}
	}
}