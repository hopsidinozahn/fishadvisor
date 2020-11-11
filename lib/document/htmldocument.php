<?php
/*
 * Copyright 2014 by Steve Muller <steve.muller@outlook.com>
 * 
 * This file is part of Fish Advisor.
 * 
 * Fish Advisor is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Fish Advisor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Fish Advisor.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Octarine\Document;

require_once SYS_LIB_ROOT . 'engine/urlresolver.php';
require_once SYS_LIB_ROOT . 'document/serializable.php';
require_once SYS_LIB_ROOT . 'document/dom/htmldocnode.php';
require_once SYS_LIB_ROOT . 'document/dom/simpleelement.php';
require_once SYS_LIB_ROOT . 'document/dom/rawnode.php';
require_once SYS_LIB_ROOT . 'document/dom/_constants.php';

class HtmlDocument implements \Octarine\Engine\UrlResolver, Serializable
{
	public function __construct(\Octarine\Engine\UrlResolver $urlResolver)
	{
		$this->urlResolver = $urlResolver;
		$this->_createDocument();
	}

	private $doc, $webRoot;
	private $nodeHtml, $nodeHead, $nodeTitle, $nodeBody;

	private function _createDocument()
	{
		$this->doc = new \Octarine\Document\Dom\HtmlDocNode();
		$this->doc->appendNode($this->nodeHtml = $this->createRootElement());
		$this->nodeHead = $this->nodeHtml->appendElement('head', false, false, \Octarine\Document\Dom\DOM_SORT_NAME);
		$this->nodeBody = $this->nodeHtml->appendElement('body');
		$this->nodeTitle = $this->nodeHead->appendElement('title')->appendText('');
		$this->nodeHead->appendElement('meta', true)->setAttr('charset', 'utf-8');
	}

	protected function createRootElement()
	{
		return new \Octarine\Document\Dom\SimpleElement('html');
	}

	public function getTitle()
	{
		return $this->nodeTitle->getText($title);
	}

	public function setTitle($title)
	{
		$this->nodeTitle->setText($title);
	}

	public function setLanguage($language_code)
	{
		$this->nodeHtml->setAttr('lang', $language_code);
	}

	public function addIcon($url, $type = 'image/vnd.microsoft.icon', $is_absolute_url = false)
	{
		if (!$is_absolute_url)
			$url = $this->urlResolver->getAbsoluteGenericUrl($url);
		$this->nodeHead->appendElement('link', true)->setAttr('rel', 'icon')->setAttr('type', $type)->setAttr('href', $url);
	}

	public function addStylesheet($url, $is_absolute_url = false, $media = 'all')
	{
		if (!$is_absolute_url)
			$url = $this->urlResolver->getAbsoluteGenericUrl($url);
		$this->nodeHead->appendElement('link', true)->setAttr('rel', 'stylesheet')->setAttr('type', 'text/css')->setAttr('media', $media)->setAttr('href', $url);
	}

	public function addScript($url, $is_absolute_url = false)
	{
		if (!$is_absolute_url)
			$url = $this->urlResolver->getAbsoluteGenericUrl($url);
		$this->nodeHead->appendElement('script')->setAttr('type', 'text/javascript')->setAttr('src', $url);
	}

	public function addInlineScript($script)
	{
		$script = '/*<![CDATA[*/' . $script . '/*]]>*/';
		$n = new \Octarine\Document\Dom\RawNode($script);
		$this->nodeHead->appendElement('script', false, true)->setAttr('type', 'text/javascript')->appendNode($n);
	}

	public function addAlternate($language_code, $url, $is_absolute_url = false)
	{
		if (!$is_absolute_url)
			$url = $this->urlResolver->getAbsoluteGenericUrl($url);
		$this->nodeHead->appendElement('link', true)->setAttr('rel', 'alternate')->setAttr('hreflang', $language_code)->setAttr('href', $url);
	}

	public function getAbsoluteGenericUrl($relative_url)
	{
		return $this->urlResolver->getAbsoluteGenericUrl($relative_url);
	}

	public function getAbsoluteDocumentUrl($relative_url)
	{
		return $this->urlResolver->getAbsoluteDocumentUrl($relative_url);
	}

	public function getBody()
	{
		return $this->nodeBody;
	}

	public function serialize($newLineChar, $paddingChar)
	{
		return $this->doc->serialize($newLineChar, $paddingChar);
	}
}

?>