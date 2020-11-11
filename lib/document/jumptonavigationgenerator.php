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

require_once SYS_LIB_ROOT . 'document/dom/dommanipulation.php';
require_once SYS_LIB_ROOT . 'document/dom/domenumerable.php';
require_once SYS_LIB_ROOT . 'document/dom/element.php';

class JumpToNavigationGenerator
{
	/**
	 * Creates a new "jump to" navigation generator, which browses the given body for headers and creates a DOM element for the navigation.
	 */
	public function __construct()
	{
	}

	private $node = null;

	/**
	 * Appends the (possibly empty) navigation element to the parent node. Must be called before generate().
	 * @param \Octarine\Document\Dom\DomManipulation $parent_node The node to which the navigation shall be appended to.
	 */
	public function create(\Octarine\Document\Dom\DomManipulation $parent_node)
	{
		if ($this->node !== null)
			throw new \Exception('Called JumpToNavigationGenerator::create() twice on same instance.');
		$this->node = $parent_node->appendElement('nav')->setAttr('id', 'jumpto')->appendElement('ul');
	}

	/**
	 * Appends the (possibly empty) navigation element to the parent node. Must be called after create().
	 * @param \Octarine\Document\Dom\DomEnumerable $body The body node which shall be searched for headers.
	 */
	public function generate(\Octarine\Document\Dom\DomEnumerable $body)
	{
		if ($this->node === null)
			throw new \Exception('Please call JumpToNavigationGenerator::create() first.');
		$this->search($body);
	}

	private function search(\Octarine\Document\Dom\DomEnumerable $node)
	{
		foreach ($node->getChildNodes() as $child)
		{
			if ($child instanceof \Octarine\Document\Dom\Element)
			{
				if (strcasecmp($child->getName(), 'h1') === 0)
					$this->index($child);
				else
					$this->search($child);
			}
		}
	}

	private function index(\Octarine\Document\Dom\Element $element)
	{
		$id;
		$text = strip_tags($element->serialize('', ''));
		if (!$element->getAttr('id'))
		{
			$id = trim(preg_replace('#[^\\w]+#i', '-', $text), '-');
			$element->setAttr('id', $id);
		}
		else
		{
			$id = $element->getAttr('id');
		}
		$this->node->appendElement('li')->appendElement('a')->setAttr('href', '#' . $id)->appendText($text);
	}
}

?>