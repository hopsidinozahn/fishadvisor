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

require_once SYS_LIB_ROOT . 'document/domloader.php';
require_once SYS_LIB_ROOT . 'document/dom/dommanipulation.php';
require_once SYS_LIB_ROOT . 'document/dom/element.php';

class I18nXmlDomLoader implements DomLoader
{
	/*
	 * @param string $xml_file The path to the XML file containing the DOM data.
	 * @param string $language_code The ISO 639-1 code of the language to use.
	 */
	public function __construct($xml_file, $language_code)
	{
		$this->xml_file = $xml_file;
		$this->language_code = $language_code;
	}

	private $xml_file, $language_code;

	/*
	 * Loads the document from the given XML element.
	 * @param \Octarine\Document\Dom\DomManipulation $body The document body element.
	 * @return void
	 */
	public function load(\Octarine\Document\Dom\DomManipulation $body)
	{
		$ns = sprintf('lang:%s', $this->language_code);
		$xml = new \XmlReader();
		$xml->xml(file_get_contents($this->xml_file), 'utf-8', \LIBXML_NOWARNING | \LIBXML_NOERROR);
		if ($xml->read())
			$this->load_element($xml, $body, $ns);
		else
			throw new \Exception(sprintf('Localized page content file "%s" contains invalid XML data.', basename($this->xml_file)));
	}

	private function load_element(\XmlReader $xml, \Octarine\Document\Dom\DomManipulation $body, $ns)
	{
		if ($xml->nodeType != \XmlReader::ELEMENT)
			throw new \Exception('Unexpected non-element node.');
		$initial_element_name = $xml->name;

		while ($xml->read())
		{
			switch ($xml->nodeType)
			{
				case \XmlReader::ELEMENT:
					if ($xml->namespaceURI == $ns || $xml->namespaceURI == '')
					{
						$shall_read_children = !$xml->isEmptyElement;
						$element;
						if ($xml->localName == '_')
						{
							// <_> ... </_> denote hidden containers: their unique purpose is to group
							// several tags in order to assign them the same language.
							$element = $body;
						}
						else
						{
							$element = $this->createElement($body, $xml->localName, $xml->isEmptyElement);
							while ($xml->moveToNextAttribute())
							{
								if ($xml->namespaceURI == $ns || $xml->namespaceURI == '')
									$this->setAttribute($element, $xml->localName, $xml->value);
							}
							$xml->moveToElement();
						}
						if ($shall_read_children)
							$this->load_element($xml, $element, $ns);
					}
					else
					{
						$xml->next();
					}
					break;
				case \XmlReader::END_ELEMENT:
					return;
				case \XmlReader::TEXT:
					$body->appendText($this->processText(strval($xml->value)));
					break;
			}
		}
		throw new \Exception('Unexpected end of XML document (<'.$initial_element_name.'> was not closed).');
	}

	protected function createElement(\Octarine\Document\Dom\DomManipulation $body, $name, $is_empty)
	{
		return $body->appendElement($name, $is_empty);
	}

	protected function setAttribute(\Octarine\Document\Dom\Element $element, $attribute, $value)
	{
		$element->setAttr($attribute, $this->processText(strval($value)));
	}

	protected function processText($text)
	{
		return $text;
	}
}

?>