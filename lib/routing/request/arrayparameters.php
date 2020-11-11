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

namespace Octarine\Routing\Request;

require_once SYS_LIB_ROOT . 'routing/request/parameters.php';

class ArrayParameters implements Parameters
{
	public function __construct(array $args)
	{
		$this->args = $args;
	}

	private $args;

	public function asArray()
	{
		return $this->args;
	}

	public function has($param_name)
	{
		return isset($this->args[$param_name]);
	}

	public function get($param_name)
	{
		if (!isset($this->args[$param_name]))
			throw new \Exception('No such argument: ' . $param_name);
		return $this->args[$param_name];
	}

	public function getOrDefault($param_name, $default_value)
	{
		if (!isset($this->args[$param_name]))
			return $default_value;
		$v = $this->args[$param_name];
		return $v === null ? $default_value : $v;
	}
}

?>