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

require_once SYS_LIB_ROOT . 'document/dom/simpleelement.php';
require_once SYS_LIB_ROOT . 'document/dom/i18ntextnode.php';
require_once SYS_LIB_ROOT . 'document/dom/_constants.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';

class I18nElement extends SimpleElement
{
	public function __construct(\Octarine\I18n\Localizer $localizer, $name, $contractionAllowed = false, $sortChildren = DOM_NO_SORT, $forceOneLine = false)
	{
		$this->localizer = $localizer;
		parent::__construct($name, $contractionAllowed, $sortChildren, $forceOneLine);
	}

	private $localizer;

	public function appendElement($name, $contractionAllowed = false, $forceOneLine = false, $sortChildren = DOM_NO_SORT)
	{
		$node = new self($this->localizer, $name, $contractionAllowed, $sortChildren, $forceOneLine);
		return $this->appendNode($node);
	}

	public function appendText($text, $convert_line_breaks = false)
	{
		$node = new I18nTextNode($this->localizer, strval($text), null, $convert_line_breaks);
		return $this->appendNode($node);
	}

	public function appendFormattedText($text, array $format_args, $convert_line_breaks = false)
	{
		$node = new I18nTextNode($this->localizer, strval($text), $format_args, $convert_line_breaks);
		return $this->appendNode($node);
	}
}

?>