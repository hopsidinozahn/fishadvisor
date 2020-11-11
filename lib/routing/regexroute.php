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

require_once SYS_LIB_ROOT . 'routing/route.php';
require_once SYS_LIB_ROOT . 'routing/request/request.php';
require_once SYS_LIB_ROOT . 'routing/request/requesthandler.php';
require_once SYS_LIB_ROOT . 'routing/simpleroutematch.php';

class RegexRoute implements Route
{
	/**
	 * Initializes a new regex route.
	 * @param string $identifier The route id.
	 * @param int $method One of the METHOD_* constants.
	 * @param string[] $available_content_types An array of available MIME content types at this route.
	 * @param \Octarine\Routing\Request\RequestHandler|callback $request_handler_or_create_request_handler_callback Either a request handler object, or a callback which creates such a request handler.
	 */
	public function __construct($identifier, $method, $path_regex, array $available_content_types, $request_handler_or_create_request_handler_callback)
	{
		$this->identifier = $identifier;
		$this->method = $method;
		$this->path_regex = $path_regex;
		$this->available_content_types = $available_content_types;
		$this->request_handler_or_create_request_handler_callback = $request_handler_or_create_request_handler_callback;
	}

	private $identifier, $method, $path_regex, $available_content_types, $request_handler_or_create_request_handler_callback;

	public function getIdentifier()
	{
		return $this->identifier;
	}

	public function getAvailableContentTypes()
	{
		return $this->available_content_types;
	}

	public function matches(\Octarine\Routing\Request\Request $request)
	{
		// First match method
		if ($request->getMethod() != $this->method) return false;
		if ($request->getPath() === null) return false;

		$matches;
		if (!preg_match_all($this->path_regex, $request->getPath(), $matches, \PREG_PATTERN_ORDER))
		{
			// Route does not match
			return false;
		}

		// Fetch all additional parameters
		$additional_parameters = array();
		foreach ($matches as $id => $m)
		{
			if (is_string($id) && count($m) > 0)
				$additional_parameters[$id] = $m[0];
		}

		return new SimpleRouteMatch($this->method, $additional_parameters);
	}

	public function createHandler()
	{
		if ($this->request_handler_or_create_request_handler_callback instanceof \Octarine\Routing\Request\RequestHandler)
		{
			return $this->request_handler_or_create_request_handler_callback;
		}
		else
		{
			$f = $this->request_handler_or_create_request_handler_callback;
			return $f();
		}
	}
}

?>