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

class HtmlDocNode implements Node
{
	private $children = array();

	/**
	 * Appends the given child node to the DOM tree.
	 * @param Node $node The node to append.
	 */
	public function appendNode(Node $node)
	{
		$this->children[] = $node;
	}

	/**
	 * Removes the given child node from the DOM tree.
	 * @param Node $node The node to remove.
	 */
	public function removeNode(Node $node)
	{
		foreach ($this->children as $i => $c)
		{
			if ($c === $node)
			{
				unset($this->children[$i]);
				break;
			}
		}
	}

	/**
	 * Serializes the node as HTML code.
	 * @param string $newLineChar The character sequence to be used for line breaks.
	 * @param string $paddingChar The character sequence to be used for padding.
	 * @return string The HTML code.
	 */
	public function serialize($newLineChar, $paddingChar)
	{
		// Write doctype
		$str = '<!DOCTYPE html>';

		// Write children
		foreach ($this->children as $c)
			$str .= $newLineChar . $c->serialize($newLineChar, $paddingChar);

		// Hack: explicit line-breaks in <textarea>
		$str = preg_replace_callback('#<textarea[^>]*>.*?</textarea>#i', function($r) use($newLineChar)
		{
			return str_replace('<br/>', $newLineChar, $r[0]);
		}, $str);

		return $str;
	}
}

?>