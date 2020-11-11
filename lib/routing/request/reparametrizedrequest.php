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

require_once SYS_LIB_ROOT . 'routing/request/request.php';
require_once SYS_LIB_ROOT . 'routing/request/parameters.php';

class ReparametrizedRequest implements Request
{
	public function __construct(Request $base_request, Parameters $new_parameters)
	{
		$this->base_request = $base_request;
		$this->parameters = $new_parameters;
	}

	private $base_request, $parameters;

	public function getParameters()
	{
		return $this->parameters;
	}

	public function getMethod()
	{
		return $this->base_request->getMethod();
	}

	public function matchContentType(array $possible_content_types)
	{
		return $this->base_request->matchContentType($possible_content_types);
	}

	public function getPath()
	{
		return $this->base_request->getPath();
	}
}

?>