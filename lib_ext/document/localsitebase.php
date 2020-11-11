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

require_once SYS_LIB_ROOT . 'document/site.php';
require_once SYS_LIB_ROOT . 'document/dom/node.php';
require_once SYS_LIB_ROOT . 'document/dom/domenumerable.php';
require_once SYS_LIB_ROOT . 'document/dom/_constants.php';
require_once SYS_LIB_ROOT . 'document/ui/control.php';
require_once SYS_LIB_ROOT . 'engine/engine.php';
require_once SYS_LIB_ROOT . 'engine/urlresolver.php';

abstract class LocalSiteBase implements Site, \Octarine\Engine\UrlResolver, \Octarine\Document\Dom\DomEnumerable
{
	public function __construct(\Octarine\Engine\Engine $engine)
	{
		$this->engine = $engine;
		$this->document = $this->createDocument($this->content_node);
	}

	private $engine;
	public $content_node, $document; // make this publicly available

	protected abstract function createDocument(&$content_node);

	public function setTitle($title)
	{
		$this->document->setTitle(strval($title));
	}

	public function setWhereabouts($sitemap_id)
	{
		// Do nothing. We don't have a sitemap.
	}

	public function getAbsoluteGenericUrl($relative_url)
	{
		return $this->engine->getAbsoluteGenericUrl($relative_url);
	}

	public function getAbsoluteDocumentUrl($relative_url, $language_code = null)
	{
		return $this->engine->getAbsoluteDocumentUrl($relative_url, $language_code);
	}

	public function appendControl(\Octarine\Document\UI\Control $control)
	{
		return $this->content_node->appendNode($control->render());
	}

	public function appendNode(\Octarine\Document\Dom\Node $node)
	{
		return $this->content_node->appendNode($node);
	}

	public function appendElement($name, $contractionAllowed = false, $forceOneLine = false, $sortChildren = \Octarine\Document\Dom\DOM_NO_SORT)
	{
		return $this->content_node->appendElement($name, $contractionAllowed, $forceOneLine, $sortChildren);
	}

	public function appendText($text, $convert_line_breaks = false)
	{
		return $this->content_node->appendText($text, $convert_line_breaks);
	}

	public function appendFormattedText($text, array $format_args, $convert_line_breaks = false)
	{
		return $this->content_node->appendFormattedText($text, $format_args, $convert_line_breaks);
	}

	public function removeNode(\Octarine\Document\Dom\Node $node)
	{
		return $this->content_node->removeNode($node);
	}

	public function removeNodes()
	{
		return $this->content_node->removeNodes();
	}

	public function getChildNodes()
	{
		return $this->content_node->getChildNodes();
	}

	public function getDocument()
	{
		return $this->document;
	}
}

?>