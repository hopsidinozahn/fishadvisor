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

namespace Octarine\Engine;

class UrlBuilder
{
	public static function buildQueryString($assoc_params)
	{
		/*
		$str = '?';
		foreach ($assoc_params as $key => $value)
		{
			if (strlen($str) > 1)
				$str .= '&';
			$str .= sprintf('%s=%s', urlencode($key), urlencode($key));
		}
		return $str;
		*/
		return '?' . http_build_query($assoc_params);
	}

	public static function getCurrentQueryParams()
	{
		parse_str($_SERVER['QUERY_STRING'], $result);
		foreach (array_keys($result) as $key)
			if (strlen($key) > 0 && $key[0] == '_')
				unset($result[$key]);
		return $result;
	}

	public static function appendToCurrentQueryString($assoc_params)
	{
		$query = array_merge(self::getCurrentQueryParams(), $assoc_params);
		return self::buildQueryString($query);
	}
}

?>