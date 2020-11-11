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
require_once SYS_LIB_ROOT . 'document/dom/dommanipulation.php';
require_once SYS_LIB_ROOT . 'document/dom/domenumerable.php';

interface Element extends Node, DomManipulation, DomEnumerable
{
	/**
	 * Gets the element name.
	 * @return string
	 */
	public function getName();

	/**
	 * Gets the value of an attribute.
	 * @param string $name The name of the attribute.
	 * @return string|null The attribute value or null, if no attribute has been sent.
	 */
	public function getAttr($name);

	/**
	 * Sets an attribute. If an attribute with the same name has already been set, it will be overwritten.
	 * @param string $name The attribute name.
	 * @param string $value The attribute value.
	 */
	public function setAttr($name, $value);

	/**
	 * Removes the given attribute.
	 * @param string $name The attribute name.
	 */
	public function removeAttr($name);
}

?>