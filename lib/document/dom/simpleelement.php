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
require_once SYS_LIB_ROOT . 'document/dom/element.php';
require_once SYS_LIB_ROOT . 'document/dom/simpletextnode.php';
require_once SYS_LIB_ROOT . 'document/dom/_constants.php';

class SimpleElement implements Element
{
	/**
	 * @param string $name The DOM element name.
	 * @param bool $contractionAllowed Whether the element can be contracted when it has no content (<input/> instead of <input></input>).
	 * @param int $sortChildren How to sort children. Must be one of DOM_NO_SORT and DOM_SORT_CHILDREN.
	 * @param bool $forceOneLine Whether to write the element and its children on a single line.
	 */
	public function __construct($name, $contractionAllowed = false, $sortChildren = DOM_NO_SORT, $forceOneLine = false)
	{
		if (!preg_match('/^[a-z0-9-]+$/i', $name))
			throw new \Exception('Badly formatted DOM element name: ' . $name);
		$this->name = strtolower(strval($name));
		$this->contractionAllowed = !!$contractionAllowed;
		$this->sortChildren = $sortChildren;
		$this->forceOneLine = !!$forceOneLine;
	}

	private $name, $contractionAllowed, $sortChildren, $forceOneLine;
	private $children = array();
	private $attr = array();

	/**
	 * Gets the element name.
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Appends the given node to the DOM tree.
	 */
	public function appendNode(Node $node)
	{
		$this->children[] = $node;
		return $node;
	}

	/**
	 * Removes the given child node.
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
	 * Removes all child nodes.
	 */
	public function removeNodes()
	{
		$this->children = array();
	}

	public function getChildNodes()
	{
		return $this->children;
	}

	/**
	 * Creates and appends a DOM node.
	 * @param string $name The DOM element name.
	 * @param bool $contractionAllowed Whether the element can be contracted when it has no content (<input/> instead of <input></input>).
	 * @param bool $forceOneLine Whether to write the element and its children on a single line.
	 * @param int $sortChildren How to sort children. Must be one of DOM_NO_SORT and DOM_SORT_CHILDREN.
	 */
	public function appendElement($name, $contractionAllowed = false, $forceOneLine = false, $sortChildren = DOM_NO_SORT)
	{
		$elem = new SimpleElement($name, $contractionAllowed, $sortChildren, $forceOneLine);
		$this->children[] = $elem;
		return $elem;
	}

	/**
	 * Creates and appends a text node.
	 * @param string $text The text node content, which can be an arbitrary string (no need to escape this).
	 * @param bool $convert_line_breaks Whether to convert line breaks (\n) into HTML breaks (<br/>).
	 * @return \Octarine\Document\Dom\Node The appended node.
	 */
	public function appendText($text, $convert_line_breaks = false)
	{
		$node = new SimpleTextNode(strval($text), null, $convert_line_breaks);
		$this->children[] = $node;
		return $node;
	}

	/**
	 * Creates and appends a text node with formatted text.
	 * @param string $text The text node content, which can be an arbitrary string (no need to escape this). It is passed to sprintf().
	 * @param array $format_args Formatting arguments passed to sprintf().
	 * @param bool $convert_line_breaks Whether to convert line breaks (\n) into HTML breaks (<br/>).
	 * @return \Octarine\Document\Dom\Node The appended node.
	 */
	public function appendFormattedText($text, array $format_args, $convert_line_breaks = false)
	{
		$node = new SimpleTextNode(strval($text), $format_args, $convert_line_breaks);
		$this->children[] = $node;
		return $node;
	}

	/**
	 * Gets the value of an attribute.
	 * @param string $name The name of the attribute.
	 * @return string|null The attribute value or null, if no attribute has been sent.
	 */
	public function getAttr($name)
	{
		if (isset($this->attr[$name]))
			return $this->attr[$name];
		else
			return null;
	}

	/**
	 * Sets an attribute. If an attribute with the same name has already been set, it will be overwritten.
	 * @param string $name The attribute name.
	 * @param string $value The attribute value.
	 */
	public function setAttr($name, $value)
	{
		if (!preg_match('/^[a-z0-9-]+$/i', $name))
			throw new \Exception('Badly formatted DOM attribute name: ' . $name);
		$this->attr[$name] = $value;
		return $this;
	}

	/**
	 * Removes the given attribute.
	 * @param string $name The attribute name.
	 */
	public function removeAttr($name)
	{
		if (isset($this->attr[$name]))
		{
			unset($this->attr[$name]);
			return true;
		}
		return false;
	}

	/**
	 * Serializes the node as HTML code.
	 * @param string $newLineChar The character sequence to be used for line breaks.
	 * @param string $paddingChar The character sequence to be used for padding.
	 * @return string The HTML code.
	 */
	public function serialize($newLineChar, $paddingChar)
	{
		// Serialize children
		$strChildren = array();
		$sortOrder = array();
		foreach ($this->children as $index => $c)
		{
			$strChildren[] = $c->serialize($newLineChar, $paddingChar);
			$sortOrder[] = ($this->sortChildren == DOM_SORT_NAME && $c instanceof self ? $c->getName() : '' ) . sprintf('_%09d', $index);
		}
		array_multisort($sortOrder, $strChildren);

		// Find out whether to contract this element
		$isContracted = $this->contractionAllowed && count($this->children) == 0;

		// Open tag and write attributes
		$str = sprintf('<%s', $this->name);
		foreach ($this->attr as $key => $value)
		{
			$str .= sprintf(' %s="%s"', $key, htmlspecialchars($value));
		}
		$str .= $isContracted ? '/>' : '>';

		// Write children
		if (count($this->children) == 1 && (!$newLineChar || strpos($strChildren[0], $newLineChar) === false))
		{
			$str .= $strChildren[0];
		}
		elseif (count($this->children) > 0)
		{
			if (!$this->forceOneLine) $str .= $newLineChar;
			foreach ($strChildren as $c)
			{
				if (!$this->forceOneLine)
					$str .= $paddingChar . str_replace($newLineChar, $newLineChar . $paddingChar, $c) . $newLineChar;
				else
					$str .= str_replace($newLineChar, $newLineChar . $paddingChar, $c);
			}
		}

		// Close tag
		if (!$isContracted)
			$str .= sprintf('</%s>', $this->name);
		return $str;
	}
}

?>