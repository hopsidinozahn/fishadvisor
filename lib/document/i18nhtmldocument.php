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

require_once SYS_LIB_ROOT . 'document/htmldocument.php';
require_once SYS_LIB_ROOT . 'document/dom/i18nelement.php';
require_once SYS_LIB_ROOT . 'engine/urlresolver.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';

class I18nHtmlDocument extends HtmlDocument
{
	public function __construct(\Octarine\Engine\UrlResolver $urlResolver, \Octarine\I18n\Localizer $localizer)
	{
		$this->localizer = $localizer;
		parent::__construct($urlResolver);
	}

	private $localizer;

	protected function createRootElement()
	{
		return new \Octarine\Document\Dom\I18nElement($this->localizer, 'html');
	}
}

?>