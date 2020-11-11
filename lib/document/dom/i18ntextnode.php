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

require_once SYS_LIB_ROOT . 'document/dom/simpletextnode.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';

class I18nTextNode extends SimpleTextNode
{
	/**
	 * @param string $text The node content.
	 * @param bool $convert_line_breaks Whether to convert line breaks (\n) into HTML breaks (<br/>).
	 */
	public function __construct(\Octarine\I18n\Localizer $localizer, $text, $format_args = null, $convert_line_breaks = false)
	{
		$this->localizer = $localizer;
		parent::__construct('x', null, $convert_line_breaks);
		$this->setText($text);
		$this->setFormatArgs($format_args);
	}

	private $localizer, $original_text;

	/**
	 * Gets the node text.
	 */
	public function getText()
	{
		return $this->original_text;
	}

	/**
	 * Sets the node text.
	 */
	public function setText($text)
	{
		$this->original_text = strval($text);
		parent::setText($this->localizer->get($text));
	}

	/**
	 * Gets the node formatting arguments, as supplied to sprintf().
	 */
	public function getFormatArgs()
	{
		return $this->format_args;
	}

	/**
	 * Sets the node formatting arguments, as supplied to sprintf().
	 * @param null|array $format_args An array of formatting arguments. Use null to disable sprintf().
	 */
	public function setFormatArgs($format_args)
	{
		if ($format_args !== null && !is_array($format_args))
			throw new \Exception('$format_args must be null or an array.');
		$this->original_format_args = $format_args;
		parent::setFormatArgs($format_args === null ? null : array_map(array($this->localizer, 'get'), $format_args));
	}

}

?>