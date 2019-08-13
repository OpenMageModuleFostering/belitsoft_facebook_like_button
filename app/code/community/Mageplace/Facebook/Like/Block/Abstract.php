<?php
/**
 * Mageplace Facebook "Like" Button
 *
 * @category	Mageplace_Facebook
 * @package		Mageplace_Facebook_Like
 * @copyright	Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license		http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Facebook_Like_Block_Abstract extends Mage_Core_Block_Template
{
	const LIKE_URL_SUFFIX	= 'like.php';
	const META_PREFIX_FB	= 'fb:';
	const META_PREFIX_OG	= 'og:';

	protected $_isIframe = null;
	protected $_isHTML5 = null;
	static protected $CONFIG_VARS = array(
		Mageplace_Facebook_Core_Helper_Data::VAR_LOCAL,
		Mageplace_Facebook_Core_Helper_Data::VAR_HREF,
		Mageplace_Facebook_Like_Helper_Data::VAR_SEND,
		Mageplace_Facebook_Like_Helper_Data::VAR_LAYOUT,
		Mageplace_Facebook_Like_Helper_Data::VAR_SHOW_FACES,
		Mageplace_Facebook_Core_Helper_Data::VAR_WIDTH,
		Mageplace_Facebook_Core_Helper_Data::VAR_HEIGHT,
		Mageplace_Facebook_Like_Helper_Data::VAR_ACTION,
		Mageplace_Facebook_Like_Helper_Data::VAR_FONT,
		Mageplace_Facebook_Like_Helper_Data::VAR_COLORSCHEME,
	);
	
	static protected $METAS = array(
		Mageplace_Facebook_Like_Helper_Data::VAR_META_TITLE,
		Mageplace_Facebook_Like_Helper_Data::VAR_META_TYPE,
		Mageplace_Facebook_Like_Helper_Data::VAR_META_URL,
		Mageplace_Facebook_Like_Helper_Data::VAR_META_IMAGE,
		Mageplace_Facebook_Like_Helper_Data::VAR_META_SITE_NAME,
		Mageplace_Facebook_Like_Helper_Data::VAR_META_ADMINS,
		Mageplace_Facebook_Core_Helper_Data::VAR_APP_ID
	);


	public function getAttribute($name)
	{
		$value = Mage::helper('facebookilike')->getCfg($name);

		return $value;
	}

	public function getWidth()
	{
		return $this->getAttribute(Mageplace_Facebook_Core_Helper_Data::VAR_WIDTH);
	}

	public function getHeight()
	{
		return $this->getAttribute(Mageplace_Facebook_Core_Helper_Data::VAR_HEIGHT);
	}

	public function isIframe()
	{
		if(is_null($this->_isIframe)) {
			$this->_isIframe = ($this->getAttribute(Mageplace_Facebook_Like_Helper_Data::VAR_PLUGIN_CODE) == 'iframe');
		}

		return $this->_isIframe;
	}
	
	public function isHTML5()
	{
		if (is_null($this->_isHTML5)) {
			$this->_isHTML5 = ($this->getAttribute(Mageplace_Facebook_Like_Helper_Data::VAR_PLUGIN_CODE) == 'html5');
		}
		return $this->_isHTML5;
	}
	

	public function getTag()
	{
		if($this->isIframe()) {
			return 'iframe';
		} elseif ($this->isHTML5()) {
			return 'div';
		} else {
			return Mageplace_Facebook_Core_Model_Facebook::LIKE_BUTTON_TAG;
		}
	}

	public function getTagParams()
	{
		if($this->isIframe()) {
			return $this->getIframeParams();
		} elseif ($this->isHTML5()) {
			return $this->getHTML5Params();	
		} else {
			return $this->getXfbmlParams();
			
		}
	}
	public function getHTML5Params()
	{
		$params = array();
		foreach(self::$CONFIG_VARS as $var) {
			if ($var != Mageplace_Facebook_Core_Helper_Data::VAR_LOCAL){
				$value = $this->_getButtonParamValue($var, $this->getAttribute($var));
				$params[] = 'data-'.$var.'="'.$value.'"';			
			}
		}
		array_unshift ($params,'class="fb-like"');	
		return implode(' ', $params);
		
	}
	

	public function getIframeParams()
	{
		$width = $this->getWidth();
		$height = $this->getHeight();

		$params_array = array(
			'src'				=> $this->getIframeSrc(),
			'scrolling'			=> 'no',
			'frameborder'		=> '0',
			'style'				=> 'border:none; overflow:hidden;'.($width ? ' width:'.$width.'px;' : '').($height ? ' height:'.$height.'px;' : ''),
			'allowTransparency'	=> 'true',
		);

		$params = array();
		foreach($params_array as $param_key=>$param_value) {
			$params[] = $param_key.'="'.$param_value.'"';
		}

		return implode(' ', $params);
	}

	public function getIframeSrc()
	{
		return
			Mage::helper('facebookilike')->getFacebook()->getPluginsUrl().
			self::LIKE_URL_SUFFIX.
			'?'.implode('&amp;', $this->_getParamsString());
	}

	public function getXfbmlParams()
	{
		return implode(' ', $this->_getParamsString());
	}

	protected function _getParamsString()
	{
		$isFrame = $this->isIframe();

		$vars = array();
		foreach(self::$CONFIG_VARS as $var) {
			$value = $this->_getButtonParamValue($var, $this->getAttribute($var));
			if($isFrame) {
				$vars[] = $var.'='.$value;
			} else {
				$vars[] = $var.'="'.$value.'"';
			}
		}

		return $vars;
	}

	protected function _getButtonParamValue($var, $value)
	{
		switch($var) {
			case (Mageplace_Facebook_Core_Helper_Data::VAR_LOCAL):
				$value = Mage::app()->getLocale()->getDefaultLocale();
			break;
			case (Mageplace_Facebook_Like_Helper_Data::VAR_SEND):
				$value = $value ? 'true' : 'false';
			break;
		}

		return $value;
	}

	protected function _getMetaTagsContent($property, $content)
	{
		return $content;
	}

	public function getMetaTagsParams()
	{
		$params = array();
		foreach(self::$METAS as $meta) {
			if(Mage::helper('facebookilike')->checkMetaTag($meta)) {
				continue;
			}

			$content = $this->_getMetaTagsContent($meta, $this->getAttribute($meta));

			switch($meta) {
				case (Mageplace_Facebook_Like_Helper_Data::VAR_META_ADMINS):
				case (Mageplace_Facebook_Core_Helper_Data::VAR_APP_ID):
					$params[self::META_PREFIX_FB.$meta] = $content;
				break;

				case (Mageplace_Facebook_Like_Helper_Data::VAR_META_TITLE):
					$content = $content ? $content : $this->getParentBlock()->getTitle();
				default:
					$params[self::META_PREFIX_OG.$meta] = $content;
			}
			
			Mage::helper('facebookilike')->addMetaTag($meta);
		}

		return $params;
	}

	protected function _setMetaChild()
	{
		if ($head = $this->getLayout()->getBlock('head')) {
			$meta_block_name = strval($this->getNameInLayout()).'.meta';
			$head->setChild($meta_block_name, $this->getLayout()->getBlock($meta_block_name));
		}
	}
}
