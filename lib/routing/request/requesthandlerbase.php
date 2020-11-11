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

require_once SYS_LIB_ROOT . 'routing/request/requesthandler.php';
require_once SYS_LIB_ROOT . 'routing/request/validatedrequest.php';
require_once SYS_LIB_ROOT . 'routing/request/genericparametervalidator.php';
require_once SYS_LIB_ROOT . 'routing/response/textresponse.php';

abstract class RequestHandlerBase implements RequestHandler
{
	public function getParameterValidator()
	{
		return new GenericParameterValidator();
	}

	public function getRequiredAccessFlags()
	{
		return 0; // by default no access flags are required
	}

	public function handle(ValidatedRequest $request)
	{
		$debug = sprintf("This interface is not implemented yet.\nThese are the parameters passed to the API:\n\n%s %s?%s",
			$request->getUnhandledRequest()->getMethod(),
			$request->getPath(),
			$_SERVER['QUERY_STRING']);
		return new \Octarine\Routing\Response\TextResponse(501, $debug);
	}

}

?>