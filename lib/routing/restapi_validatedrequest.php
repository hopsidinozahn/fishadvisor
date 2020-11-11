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
require_once SYS_LIB_ROOT . 'routing/request/parameters.php';
require_once SYS_LIB_ROOT . 'routing/request/request.php';
require_once SYS_LIB_ROOT . 'routing/request/validatedrequest.php';
require_once SYS_LIB_ROOT . 'access/principal.php';

class RestApi_ValidatedRequest implements \Octarine\Routing\Request\ValidatedRequest
{
	public function __construct(\Octarine\Routing\Route $route, $path, $contentType, \Octarine\Routing\Request\Parameters $parameters, \Octarine\Access\Principal $requester, \Octarine\Routing\Request\Request $original_request)
	{
		$this->route = $route;
		$this->path = $path;
		$this->contentType = $contentType;
		$this->parameters = $parameters;
		$this->requester = $requester;
		$this->original_request = $original_request;
	}

	public function getRoute()
	{
		return $this->route;
	}

	public function getPath()
	{
		return $this->path;
	}

	public function getContentType()
	{
		return $this->contentType;
	}

	public function getParameters()
	{
		return $this->parameters;
	}

	public function getRequester()
	{
		return $this->requester;
	}

	public function getUnhandledRequest()
	{
		return $this->original_request;
	}
}

?>