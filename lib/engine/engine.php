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

namespace Octarine\Engine;
require_once SYS_LIB_ROOT . 'engine/urlresolver.php';
require_once SYS_LIB_ROOT . 'routing/errorhandling/requesterrorhandler.php';

interface Engine extends UrlResolver
{
	/**
	 * Gets the associated API.
	 * @return \Octarine\Routing\Api
	 */
	public function getApi();

	/**
	 * Runs the engine in "error handling" mode (that is, it ignores the client's request and invokes an error handler) and outputs any content.
	 * Useful in combination with server error pages (like the Apache ErrorDocument directive).
	 * @param \Octarine\Routing\ErrorHandling\RequestErrorHandler $error_handler The error handler which will provide the response.
	 * @param object $error_handler_params An stdclass object containing additional parameters passed to the error handler.
	 * @return void
	 */
	public function runWithErrorHandler(\Octarine\Routing\ErrorHandling\RequestErrorHandler $error_handler, $error_handler_params);

	/**
	 * Runs the engine and outputs any content.
	 * @return void
	 */
	public function run();
}

?>