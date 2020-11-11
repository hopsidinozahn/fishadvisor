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
require_once SYS_LIB_ROOT . 'document/i18nxmldomloader.php';
require_once SYS_LIB_ROOT . 'document/dom/element.php';

class I18nUrlResolvingXmlDomLoader extends I18nXmlDomLoader
{
	/*
	 * @param string $xml_file The path to the XML file containing the DOM data.
	 * @param string $language_code The ISO 639-1 code of the language to use.
	 */
	public function __construct($xml_file, $language_code, \Octarine\Engine\UrlResolver $url_resolver)
	{
		parent::__construct($xml_file, $language_code);
		$this->url_resolver = $url_resolver;
	}

	private $url_resolver;

	protected function setAttribute(\Octarine\Document\Dom\Element $element, $attribute, $value)
	{
		if ($attribute == 'href' || $attribute == 'src')
		{
			if (strpos($value, '//') === 0)
				; // URLs starting with // shall not be rewritten; they refer to the top-most URL level (e.g. //google.com).
			elseif (strpos($value, '/*/') === 0)
				// Rewrite "/*/..." to the actual document root. The notation comes from the common case
				// where the asterisk (*) is just replaced by the language code.
				$value = $this->url_resolver->getAbsoluteDocumentUrl(substr($value, 2));
			elseif (strpos($value, '/') === 0)
				// Any other absolute URL (relative to the current domain) is rewritten to
				// the (generic) root of the web site. This is generally used for including images
				// and other non-localized assets.
				$value = $this->url_resolver->getAbsoluteGenericUrl($value);
		}

		parent::setAttribute($element, $attribute, $value);
	}
}

?>