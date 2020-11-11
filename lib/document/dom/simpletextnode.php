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

class SimpleTextNode implements Node
{
	/**
	 * @param string $text The node content.
	 * @param bool $format_args Formatting arguments passed to sprintf(). If $format_args is null, sprintf() will not be used.
	 * @param bool $convert_line_breaks Whether to convert line breaks (\n) into HTML breaks (<br/>).
	 */
	public function __construct($text, $format_args = null, $convert_line_breaks = false)
	{
		if ($format_args !== null && !is_array($format_args))
			throw new \Exception('$format_args must be null or an array.');
		$this->text = strval($text);
		$this->format_args = $format_args;
		$this->convert_line_breaks = $convert_line_breaks;
	}

	private $text, $format_args, $convert_line_breaks;

	/**
	 * Gets the node text, as to be parsed by sprintf().
	 */
	public function getText()
	{
		return $this->text;
	}

	/**
	 * Sets the node text, as to be parsed by sprintf().
	 */
	public function setText($text)
	{
		$this->text = strval($text);
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
		$this->format_args = $format_args;
	}

	/**
	 * Serializes the node as HTML code.
	 * @param string $newLineChar The character sequence to be used for line breaks.
	 * @param string $paddingChar The character sequence to be used for padding.
	 * @return string The HTML code.
	 */
	public function serialize($newLineChar, $paddingChar)
	{
		$formatted_text = $this->text;
		if ($this->format_args !== null)
		{
			/* PHP 5.6 feature (variadic functions):
			 * $formatted_text = sprintf($this->text, ...$this->format_args);
			 */
			$formatted_text = call_user_func_array('sprintf', array_merge(array($this->text), $this->format_args));
		}
		if (!$this->convert_line_breaks)
		{
			return htmlspecialchars($formatted_text);
		}
		else
		{
			$split = explode("\n", str_replace("\r", "\n", str_replace("\r\n", "\n", $formatted_text)));
			$split = array_map('htmlspecialchars', $split);
			return implode('<br/>', $split);
		}
	}
}

?>