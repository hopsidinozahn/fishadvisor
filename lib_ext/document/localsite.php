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

require_once SYS_LIBEXT_ROOT . 'document/localsitebase.php';
require_once SYS_LIB_ROOT . 'document/htmldocument.php';
require_once SYS_LIB_ROOT . 'engine/engine.php';
require_once SYS_LIB_ROOT . 'routing/request/request.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';

class LocalSite extends LocalSiteBase
{
	public function __construct(\Octarine\Engine\Engine $engine, \Octarine\I18n\Localizer $localizer, \Octarine\Routing\Request\Request $raw_request)
	{
		$this->engine = $engine;
		$this->localizer = $localizer;
		$this->raw_request = $raw_request;
		parent::__construct($engine);
	}

	private $engine, $localizer, $raw_request;
	private $body;

	public function noMargin()
	{
		$this->body->setAttr('class', 'full');
	}

	public function setTitle($title)
	{
		$this->document->setTitle(sprintf('%s \\\\ %s',
			$this->localizer->get('Fish Advisor'),
			$this->localizer->get(strval($title))));
	}

	protected function createDocument(&$content_node)
	{
		$doc = new \Octarine\Document\HtmlDocument($this);

		// Meta
		$doc->addIcon('/favicon.ico', 'image/vnd.microsoft.icon');
		$doc->addStylesheet('//fonts.googleapis.com/css?family=Lato:400,300', true);
		$doc->addStylesheet('/style/all.min.css?20150213');
		$doc->addScript('//code.jquery.com/jquery-1.11.1.min.js', true);
		$doc->addScript('/js/main.min.js?20150213');
		$doc->addScript('/lang/' . $this->localizer->getCurrentLanguage() . '.js?20150213');

		$this->body = $doc->getBody();
		$content_node = $doc->getBody()->appendElement("main");

		$footer = $doc->getBody()->appendElement('footer', false, true)->setAttr('id', 'copyright');
		$footer->appendText($this->localizer->get('Service provided by: '));
		$footer->appendElement('a')->setAttr('href', 'http://siggen.toontown-click.de')->appendText($this->localizer->get('SigGen'));
		$footer->appendElement('br', true);
		$footer->appendText($this->localizer->get('Copyright © 2014 by Steve Muller. All rights reserved.'));
		$footer->appendElement('br', true);
		$footer->appendText($this->localizer->get('All rights related to fish and playground icons belong to Disney.'));

		return $doc;
	}
}

?>