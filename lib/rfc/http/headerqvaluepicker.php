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

namespace Octarine\Rfc\Http;

class HeaderQValuePicker
{
	private $supported_values = array();

	public function addSupportedValue($value)
	{
		$this->supported_values[] = $value;
	}

	public function getSupportedValues()
	{
		return $this->supported_values;
	}

	public function isValueSupported($value)
	{
		return in_array($value, $this->supported_values);
	}

	public function chooseBest($qvalue_string)
	{
		$qvalues = explode(',', $qvalue_string);

		// Find a supported value with highest priority ("q")
		$max_q = -1;
		$max_q_value = null;
		foreach ($qvalues as $qvalue)
		{
			// Extract value and priority
			// Note that the "q" ("quality") parameter is optional and defaults to 1.0 (by RFC 2616)
			list($value, $q) = array_pad(explode(';', $qvalue, 2), 2, 'q=1');

			// Extract priority
			if (preg_match_all('#^q\\s*=\\s*(?<value>0?\\.[0-9]+|1(\\.0*)?)$#', trim($q), $q))
				$q = (float)$q['value'][0];
			else
				$q = 0; // parameter is invalid

			// Check if this value is supported and has higher priority
			if ($q > $max_q)
			{
				// Turn value into regex so that '*' matches arbitrary characters
				$regex = '/' . implode('.*', array_map(function ($x) { return preg_quote($x, '/'); }, explode('*', $value))) . '/i';

				foreach ($this->supported_values as $supported_value)
				{
					if (preg_match($regex, $supported_value))
					{
						$max_q = $q;
						$max_q_value = $supported_value;
						break;
					}
				}
			}
		}
		return $max_q_value;
	}

	public function chooseBestLanguage($accept_language_string, $major_only = true, $value_if_none = null)
	{
		$best = $this->chooseBest($accept_language_string);
		if ($best === null)
			return $value_if_none;
		elseif (!$major_only)
			return $best;
		else
		{
			$tokens = explode('-', $best);
			return $tokens[0];
		}
	}

	public function chooseBestContentType($accept_string, $value_if_none = null)
	{
		$best = $this->chooseBest($accept_string);
		if ($best === null)
			return $value_if_none;
		else
			return $best;
	}
}

?>