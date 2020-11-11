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

namespace Octarine\Document\UI;

require_once SYS_LIB_ROOT . 'document/ui/renderer.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';
require_once SYS_LIB_ROOT . 'document/dom/dommanipulation.php';
require_once SYS_LIBEXT_ROOT . 'toontown/fishdefinitions.php';

class RodListRenderer implements \Octarine\Document\UI\Renderer
{
	public function __construct(\Octarine\I18n\Localizer $localizer, \Octarine\Toontown\FishDefinitions $definitions, $current_rod, $url)
	{
		$this->localizer = $localizer;
		$this->definitions = $definitions;
		$this->current_rod = $current_rod;
		$this->url = $url;
	}

	private $localizer;
	private $definitions, $current_rod, $url;

	public function render(\Octarine\Document\Dom\DomManipulation $parent)
	{
		$ul = $parent->appendElement('ul')->setAttr('class', 'rodlist rod_' . $this->current_rod->id)->setAttr('x-name', $this->localizer->get($this->current_rod->name));
		foreach ($this->definitions->getRods() as $rod)
		{
			$li = $ul->appendElement('li');
			if ($rod->id == $this->current_rod->id)
				$li->setAttr('class', 'current');
			$li->appendElement('a')->setAttr('href', sprintf($this->url, $rod->id))->appendText($this->localizer->get($rod->name));
		}
	}
}

?>