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
require_once SYS_LIB_ROOT . 'engine/sitemap.php';
require_once SYS_LIB_ROOT . 'engine/urlresolver.php';

abstract class SiteBase implements Site, \Octarine\Engine\UrlResolver, \Octarine\Document\Dom\DomEnumerable
{
	public function __construct(\Octarine\Engine\Engine $engine, \Octarine\Engine\Sitemap $sitemap)
	{
		$this->engine = $engine;
		$this->sitemap = $sitemap;
		$this->document = $this->createDocument($this->content_node);
	}

	private $engine, $sitemap, $document, $content_node;

	/**
	 * Creates the HTML document.
	 * @param \Octarine\Document\Dom\Node $content_node The node which will hold the site content.
	 * @return \Octarine\Document\HtmlDocument
	 */
	protected abstract function createDocument(&$content_node);

	/**
	 * Sets the breadcrumbs of the currently displayed page.
	 * @param object[] $breadcrumbs An array of page objects, where the first entry is the top most page. Each object consists of the fields 'title' and 'url' (which is absolute).
	 * @return void
	 */
	protected abstract function setBreadcrumbs($breadcrumbs);

	/**
	 * Sets the title of the currently displayed page.
	 * @param string[] $titles An array of page titles, where the first entry is the current page and succeeding entries represent parent pages.
	 * @return void
	 */
	protected abstract function setTitle(array $titles);

	/**
	 * Specifies the currently displayed page.
	 * @param string $sitemap_id The id of the page.
	 * @return void
	 */
	public function setWhereabouts($sitemap_id)
	{
		$page = $this->sitemap->getPage($sitemap_id);

		$pages = array($page);
		$titles = array($page->title);
		while (null !== ($page = $this->sitemap->getParentPage($page->id)))
		{
			array_unshift($pages, (object)array('title' => $page->title, 'url' => $page->url));
			$titles[] = $page->title;
		}
		$this->setBreadcrumbs($pages);
		$this->setTitle($titles);
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