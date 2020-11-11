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

interface RouteMatch
{
	/**
	 * Gets the request method (HTTP verb).
	 *
	 * @return string One of the \Octarine\Routing\METHOD_* constants.
	 */
	public function getMethod();

	/**
	 * Gets the parameters contained in a route.
	 *
	 * @return \Octarine\Routing\Request\Parameters
	 */
	public function getAdditionalParameters();

	/**
	 * Appends the additional parameters extracted from the route to the given request.
	 *
	 * @param \Octarine\Routing\Request\Request $request The existing request.
	 * @return \Octarine\Routing\Request\Request A new Request instance containing the additional parameters.
	 */
	public function appendParameters(\Octarine\Routing\Request\Request $request);
}

?>