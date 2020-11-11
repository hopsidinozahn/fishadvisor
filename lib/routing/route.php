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

interface Route
{
	/**
	 * Gets a string which uniquely identifies this route.
	 * This is used for backtracking and debugging purposes.
	 * Correlating this with a path is probably a good idea.
	 *
	 * @return string
	 */
	public function getIdentifier();

	/**
	 * Checks whether the given request applies to this handler and extracts additional information.
	 *
	 * @param \Octarine\Routing\Request\Request $request The request.
	 * @return false|\Octarine\Routing\RouteMatch False if the URI does not match, a RouteMatch instance containing additional information otherwise.
	 */
	public function matches(\Octarine\Routing\Request\Request $request);

	/**
	 * Gets a collection of content types available at this route.
	 * @return string[] An array of available MIME types.
	 */
	public function getAvailableContentTypes();

	/**
	 * Creates a RequestHandler instance for this route.
	 *
	 * @return \Octarine\Routing\Request\RequestHandler
	 */
	public function createHandler();
}

?>