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

namespace Octarine\Document\Dom;

require_once SYS_LIB_ROOT . 'document/dom/node.php';

class RawNode implements Node
{
	/**
	 * @param string $content The raw HTML content.
	 */
	public function __construct($content)
	{
		$this->setContent($content);
	}

	private $content;

	/**
	 * Returns the raw HTML content.
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}

	/**
	 * Sets the raw HTML content for this node.
	 * Warning: you have to make sure the content is properly formatted and escaped.
	 * @param string $content The raw HTML content.
	 */
	public function setContent($content)
	{
		$this->content = "$content";
	}

	/**
	 * Serializes the node as HTML code.
	 * @param string $newLineChar The character sequence to be used for line breaks.
	 * @param string $paddingChar The character sequence to be used for padding.
	 * @return string The HTML code.
	 */
	public function serialize($newLineChar, $paddingChar)
	{
		return $this->content;
	}
}

?>