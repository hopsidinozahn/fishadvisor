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

class StaticRoute implements Route
{
	/**
	 * Initializes a new static route.
	 * @param string $identifier The route id.
	 * @param int $method One of the METHOD_* constants.
	 * @param string[] $available_content_types An array of available MIME content types at this route.
	 * @param \Octarine\Routing\Request\RequestHandler|callback $request_handler_or_create_request_handler_callback Either a request handler object, or a callback which creates such a request handler.
	 */
	public function __construct($identifier, $method, $path, array $available_content_types, $request_handler_or_create_request_handler_callback)
	{
		$this->identifier = $identifier;
		$this->method = $method;
		$this->path = $path;
		$this->available_content_types = $available_content_types;
		$this->request_handler_or_create_request_handler_callback = $request_handler_or_create_request_handler_callback;
	}

	private $identifier, $method, $path, $available_content_types, $request_handler_or_create_request_handler_callback;

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
		if ($request->getMethod() != $this->method) return false;
		if ($this->path != $request->getPath()) return false;
		return new SimpleRouteMatch($this->method, array());
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