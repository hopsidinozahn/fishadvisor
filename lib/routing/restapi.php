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

require_once SYS_LIB_ROOT . 'routing/api.php';
require_once SYS_LIB_ROOT . 'routing/restapi_validatedrequest.php';
require_once SYS_LIB_ROOT . 'routing/route.php';
require_once SYS_LIB_ROOT . 'routing/_constants.php';
require_once SYS_LIB_ROOT . 'routing/request/_constants.php';
require_once SYS_LIB_ROOT . 'routing/request/request.php';
require_once SYS_LIB_ROOT . 'routing/errorhandling/requesterrorhandler.php';
require_once SYS_LIB_ROOT . 'routing/response/nocontentresponse.php';
require_once SYS_LIB_ROOT . 'access/principal.php';

class RestApi implements Api
{
	private $routes = array();
	private $errorhandler_nohandler = null;
	private $errorhandler_noaccess = null;
	private $errorhandler_badrequest = null;
	private $errorhandler_notacceptable = null;

	public function setNoHandlerErrorHandler(\Octarine\Routing\ErrorHandling\RequestErrorHandler $handler)
	{
		$this->errorhandler_nohandler = $handler;
	}

	public function setNoAccessErrorHandler(\Octarine\Routing\ErrorHandling\RequestErrorHandler $handler)
	{
		$this->errorhandler_noaccess = $handler;
	}

	public function setBadRequestErrorHandler(\Octarine\Routing\ErrorHandling\RequestErrorHandler $handler)
	{
		$this->errorhandler_badrequest = $handler;
	}

	public function setNotAcceptableErrorHandler(\Octarine\Routing\ErrorHandling\RequestErrorHandler $handler)
	{
		$this->errorhandler_notacceptable = $handler;
	}

	public function addRoute(\Octarine\Routing\Route $route)
	{
		// Add route to beginning of array
		array_unshift($this->routes, $route);
	}

	public function process(\Octarine\Routing\Request\Request $request, \Octarine\Access\Principal $requester)
	{
		switch ($request->getMethod())
		{
			case \Octarine\Routing\METHOD_OPTIONS:
				$allowed = array(\Octarine\Routing\METHOD_OPTIONS);
				foreach ($this->routes as $route)
				{
					if ($route->matches($request))
						$allowed[] = $route->getMethod();
				}
				$allowed = array_unique($allowed);
				return new \Octarine\Routing\Response\NoContentResponse(204, array('Allow' => implode(', ', $allowed)));
			case \Octarine\Routing\METHOD_UNKNOWN:
				return $this->respondNoHandler($request, $requester);
			default:
				return $this->route($request->getMethod(), $request, $requester);
		}
	}

	private function route($method, \Octarine\Routing\Request\Request $request, \Octarine\Access\Principal $requester)
	{
		foreach ($this->routes as $route)
		{
			// Route request
			if ($routeMatch = $route->matches($request))
			{
				$handler = $route->createHandler();

				// Check access rights
				if ($requester->canAccess($handler->getRequiredAccessFlags()))
				{
					// Validate content type
					$content_type = $request->matchContentType($route->getAvailableContentTypes());
					if ($content_type === null)
					{
						return $this->respondNotAcceptable($request, $requester, $route);
					}

					// Validate parameters
					$erroneous_parameter;
					$error_reason;
					$newRequest = $routeMatch->appendParameters($request);
					if ($validatedParameters = $handler->getParameterValidator()->validate($newRequest->getParameters(), $erroneous_parameter, $error_reason))
					{
						// Handle request
						$validatedRequest = new RestApi_ValidatedRequest($route, $request->getPath(), $content_type, $validatedParameters, $requester, $request);
						$response = $handler->handle($validatedRequest);
						if (!isset($response))
							throw new \Exception(sprintf('Handler of route %s %s did not return a response.', $routeMatch->getMethod(), $route->getIdentifier()));
						return $response;
					}
					else
					{
						return $this->respondBadRequest($newRequest, $requester, $route, $erroneous_parameter, $error_reason);
					}
				}
				else
				{
					return $this->respondNoAccess($newRequest, $requester, $route);
				}
			}
		}
		return $this->respondNoHandler($request, $requester);
	}

	private function respondNoHandler(\Octarine\Routing\Request\Request $request, \Octarine\Access\Principal $requester)
	{
		if ($this->errorhandler_nohandler === null)
			throw new \Exception('No error handler defined for "no handler exception".');
		return $this->errorhandler_nohandler->handle($request, $requester, (object)array());
	}

	private function respondNoAccess(\Octarine\Routing\Request\Request $request, \Octarine\Access\Principal $requester, \Octarine\Routing\Route $route)
	{
		if ($this->errorhandler_nohandler === null)
			throw new \Exception('No error handler defined for "no access exception".');
		return $this->errorhandler_noaccess->handle($request, $requester, (object)array('route' => $route));
	}

	private function respondBadRequest(\Octarine\Routing\Request\Request $request, \Octarine\Access\Principal $requester, \Octarine\Routing\Route $route, $erroneous_parameter, $error_reason)
	{
		if ($this->errorhandler_nohandler === null)
			throw new \Exception('No error handler defined for "bad request exception".');
		return $this->errorhandler_badrequest->handle($request, $requester, (object)array('route' => $route, 'parameter' => $erroneous_parameter, 'reason' => $error_reason));
	}

	private function respondNotAcceptable(\Octarine\Routing\Request\Request $request, \Octarine\Access\Principal $requester, \Octarine\Routing\Route $route)
	{
		if ($this->errorhandler_notacceptable === null)
			throw new \Exception('No error handler defined for "bad request exception".');
		return $this->errorhandler_notacceptable->handle($request, $requester, (object)array('route' => $route));
	}
}

?>