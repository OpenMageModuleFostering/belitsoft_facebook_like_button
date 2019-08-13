<?php
/**
 * Mageplace Facebook "Like" Button
 *
 * @category	Mageplace_Facebook
 * @package		Mageplace_Facebook_Like
 * @copyright	Copyright (c) 2011 Mageplace. (http://www.mageplace.com)
 * @license		http://www.mageplace.com/disclaimer.html
 */

class Mageplace_Facebook_Like_Model_Observer
{
	private $_observer;
	private $_isVisible = array();
	private $_acceptedBlocks = array(
				'category.products'	=> array(
					'html'		=> 'Button',
					'visible'	=> 'Catalog',
					'cfg'		=> Mageplace_Facebook_Core_Helper_Data::VAR_SHOW_IN_CATEGORY
				),
				'product.info'		=> array(
					'html'		=> 'Button',
					'visible'	=> 'Catalog',
					'cfg'		=> Mageplace_Facebook_Core_Helper_Data::VAR_SHOW_IN_PRODUCT
				),
				'cms_page'			=> array(
					'html'		=> 'Button',
					'visible'	=> 'CmsPage',
					'cfg'		=> ''
				),
			);

	public function processCoreBlockAbstractToHtmlAfter($observer)
	{
		$nameInLayout = $observer->getBlock()->getNameInLayout();
		if(($nameInLayout == 'root') && ($facebook = Mage::helper('facebookilike')->getFacebook())) {
			$html = $facebook->insertXmlnsParams($observer->getTransport()->getHtml());
			$observer->getTransport()->setHtml($html);

			return $this;
		}

		if(array_key_exists($nameInLayout, $this->_acceptedBlocks)
			&& ($method_visible_name = '_is'.$this->_acceptedBlocks[$nameInLayout]['visible'].'Visible')
			&& $this->$method_visible_name($nameInLayout, $observer)
			&& !Mage::registry('mageplace_facebookilike'))
		{
			Mage::register('mageplace_facebookilike', true);

			$method_name = '_get'.$this->_acceptedBlocks[$nameInLayout]['html'].'Html';
			$html = $this->$method_name($nameInLayout, $observer);

			$observer->getTransport()->setHtml($html);
		}
	}

	private function _isCatalogVisible($nameInLayout, $observer)
	{
		if(!array_key_exists($nameInLayout, $this->_isVisible)) {
			$this->_isVisible[$nameInLayout] = Mage::helper('facebookilike')->getCfg($this->_acceptedBlocks[$nameInLayout]['cfg']);
		}

		return $this->_isVisible[$nameInLayout];
	}

	private function _isCmsPageVisible($nameInLayout, $observer)
	{
		if(!array_key_exists($nameInLayout, $this->_isVisible)) {
			$controller_name = Mage::app()->getFrontController()->getRequest()->getControllerName();

			if($controller_name == 'index') {
				$this->_isVisible[$nameInLayout] = Mage::helper('facebookilike')->getCfg(Mageplace_Facebook_Core_Helper_Data::VAR_SHOW_IN_HOME);
			} else if($controller_name == 'page') {
				$this->_isVisible[$nameInLayout] = Mage::helper('facebookilike')->getCfg(Mageplace_Facebook_Core_Helper_Data::VAR_SHOW_IN_CMS);
			} else {
				$this->_isVisible[$nameInLayout] = 0;
			}
		}

		return $this->_isVisible[$nameInLayout];
	}

	private function _getButtonHtml($nameInLayout, $observer)
	{
		$html = $observer->getTransport()->getHtml();
		if(($facebook = Mage::helper('facebookilike')->getFacebook())
			&& ($block = $observer->getBlock()->getLayout()->getBlock($nameInLayout.'.facebookilike')))
		{
			$html = $block->toHtml() . $facebook->getFacebookHtml() . $html;
		}

		return $html;
	}

	public function processCoreBlockAbstractToHtmlBefore($observer)
	{
		if(!($head = $observer->getBlock()) || !($head instanceof Mage_Page_Block_Html_Head)) {
			return $this;
		}

		$request = Mage::app()->getFrontController()->getRequest();
		$module_name = $request->getModuleName();
		$controller_name = $request->getControllerName();

		if(($module_name == 'catalog') && ($controller_name == 'category')) {
			if($this->_isCatalogVisible('category.products', $observer) && !Mage::registry('mageplace_facebookilikemeta_inserted')) {
				$head->setChild(
					'category.products.facebookilike.meta',
					$observer->getBlock()
						->getLayout()
						->createBlock(
							'facebookilike/catalog_category_facebookilikemeta',
							'category.products.facebookilikemeta'
						)
				);

				Mage::register('mageplace_facebookilikemeta_inserted', true);
			}

		} else if(($module_name == 'catalog') && ($controller_name == 'product')) {
			if($this->_isCatalogVisible('product.info', $observer) && !Mage::registry('mageplace_facebookilikemeta_inserted')) {
				$head->setChild(
					'product.info.facebookilike.meta',
					$observer->getBlock()
						->getLayout()
						->createBlock(
							'facebookilike/catalog_product_facebookilikemeta',
							'product.info.facebookilike.meta'
						)
				);

				Mage::register('mageplace_facebookilikemeta_inserted', true);
			}

		} else if($module_name == 'cms') {
			if($this->_isCmsPageVisible('cms_page', $observer) && !Mage::registry('mageplace_facebookilikemeta_inserted')) {
				$head->setChild(
					'cms.facebookilike.meta',
					$observer->getBlock()
						->getLayout()
						->createBlock(
							'facebookilike/cms_facebookilikemeta',
							'cms.facebookilike.meta'
						)
				);

				Mage::register('mageplace_facebookilikemeta_inserted', true);
			}

		} 
	}
}