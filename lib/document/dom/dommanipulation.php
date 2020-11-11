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
require_once SYS_LIB_ROOT . 'document/dom/_constants.php';

interface DomManipulation
{
	/**
	 * Appends the given node to the DOM tree.
	 * @return \Octarine\Document\Dom\Node The appended node.
	 */
	public function appendNode(Node $node);

	/**
	 * Creates and appends a DOM element.
	 * @param string $name The DOM element name.
	 * @param bool $contractionAllowed Whether the element can be contracted when it has no content (<input/> instead of <input></input>).
	 * @param bool $forceOneLine Whether to write the element and its children on a single line.
	 * @param int $sortChildren How to sort children. Must be one of DOM_NO_SORT and DOM_SORT_CHILDREN.
	 * @return \Octarine\Document\Dom\Element The appended element.
	 */
	public function appendElement($name, $contractionAllowed = false, $forceOneLine = false, $sortChildren = DOM_NO_SORT);

	/**
	 * Creates and appends a text node.
	 * @param string $text The text node content, which can be an arbitrary string (no need to escape this).
	 * @param bool $convert_line_breaks Whether to convert line breaks (\n) into HTML breaks (<br/>).
	 * @return \Octarine\Document\Dom\Node The appended node.
	 */
	public function appendText($text, $convert_line_breaks = false);

	/**
	 * Creates and appends a text node with formatted text.
	 * @param string $text The text node content, which can be an arbitrary string (no need to escape this). It is passed to sprintf().
	 * @param string $format_args Formatting arguments passed to sprintf().
	 * @param bool $convert_line_breaks Whether to convert line breaks (\n) into HTML breaks (<br/>).
	 * @return \Octarine\Document\Dom\Node The appended node.
	 */
	public function appendFormattedText($text, array $format_args, $convert_line_breaks = false);

	/**
	 * Removes the given child node.
	 * @param \Octarine\Document\Dom\Node $node The node to remove.
	 */
	public function removeNode(Node $node);

	/*
	 * Removes all child nodes.
	 */
	public function removeNodes();
}

?>