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

namespace Octarine\Routing;

require_once SYS_LIB_ROOT . 'routing/request/request.php';
require_once SYS_LIB_ROOT . 'routing/routematch.php';
require_once SYS_LIB_ROOT . 'routing/request/arrayparameters.php';
require_once SYS_LIB_ROOT . 'routing/request/reparametrizedrequest.php';

class SimpleRouteMatch implements RouteMatch
{
	public function __construct($method, array $additional_parameters)
	{
		$this->method = $method;
		$this->additional_parameters = $additional_parameters;
	}

	private $method, $additional_parameters;

	public function getMethod()
	{
		return $this->method;
	}

	public function getAdditionalParameters()
	{
		return $this->additional_parameters;
	}

	public function appendParameters(\Octarine\Routing\Request\Request $request)
	{
		// Additional parameters (from route) must override the ones specified by the user
		$parameters = array_merge($request->getParameters()->asArray(), $this->additional_parameters);
		$parameters = new \Octarine\Routing\Request\ArrayParameters($parameters);

		// Replace parameters from old request
		return new \Octarine\Routing\Request\ReparametrizedRequest($request, $parameters);
	}
}

?>