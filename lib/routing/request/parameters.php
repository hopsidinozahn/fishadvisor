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

interface Parameters
{
	/*
	 * Checks whether the given parameter has been provided.
	 * @return bool
	 */
	public function has($param_name);

	/*
	 * Gets the provided value of the given parameter, or null if none has been furnished (and the parameter is optional).
	 * @throws Exception if the parameter is not defined.
	 * @return mixed
	 */
	public function get($param_name);

	/*
	 * Gets the provided value of given parameter, or the specified default value if none has been furnished (and the parameter is optional).
	 * @throws Exception if the parameter is not defined.
	 * @return mixed
	 */
	public function getOrDefault($param_name, $default_value);

	/*
	 * Gets the parameters as associative array.
	 * @return array
	 */
	public function asArray();
}

?>