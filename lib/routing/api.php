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
require_once SYS_LIB_ROOT . 'routing/errorhandling/requesterrorhandler.php';
require_once SYS_LIB_ROOT . 'access/principal.php';

interface Api
{
	/**
	 * Sets the error handler which is invoked if no handler could be matches to the request.
	 * Corresponds to HTTP 404 Not Found.
	 * @param \Octarine\Routing\ErrorHandling\RequestErrorHandler $handler
	 */
	public function setNoHandlerErrorHandler(\Octarine\Routing\ErrorHandling\RequestErrorHandler $handler);

	/**
	 * Sets the error handler which is invoked if the requesting principal has not enough rights to access the resource.
	 * Corresponds to HTTP 401 Authentication Required.
	 * @param \Octarine\Routing\ErrorHandling\RequestErrorHandler $handler
	 */
	public function setNoAccessErrorHandler(\Octarine\Routing\ErrorHandling\RequestErrorHandler $handler);

	/**
	 * Sets the error handler which is invoked if the client made a bad request due to invalid parameters.
	 * Corresponds to HTTP 400 Bad Request.
	 * @param \Octarine\Routing\ErrorHandling\RequestErrorHandler $handler
	 */
	public function setBadRequestErrorHandler(\Octarine\Routing\ErrorHandling\RequestErrorHandler $handler);

	/**
	 * Sets the error handler which is invoked if none of the content types accepted by the client matches the ones available at the requested resource.
	 * Corresponds to HTTP 406 Not Acceptable.
	 * @param \Octarine\Routing\ErrorHandling\RequestErrorHandler $handler
	 */
	public function setNotAcceptableErrorHandler(\Octarine\Routing\ErrorHandling\RequestErrorHandler $handler);

	/**
	 * Adds a route which will be invoked when process() is called.
	 * @param \Octarine\Routing\Route $route The route to be added.
	 * @return void
	 */
	public function addRoute(\Octarine\Routing\Route $route);

	/**
	 * Processes the request by applying an appropriate handler to it.
	 * @param \Octarine\Routing\Request\Request $request The request that has been made.
	 * @param \Octarine\Access\Principal $requester The principal which made the request.
	 * @return \Octarine\Routing\Response\Response
	 */
	public function process(\Octarine\Routing\Request\Request $request, \Octarine\Access\Principal $requester);
}

?>