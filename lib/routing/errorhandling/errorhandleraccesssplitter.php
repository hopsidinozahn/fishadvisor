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

namespace Octarine\Routing\ErrorHandling;
require_once SYS_LIB_ROOT . 'routing/errorhandling/requesterrorhandler.php';
require_once SYS_LIB_ROOT . 'routing/request/request.php';
require_once SYS_LIB_ROOT . 'access/principal.php';

class ErrorHandlerAccessSplitter implements RequestErrorHandler
{
	public function __construct($required_rights, RequestErrorHandler $handler_if_authenticated, RequestErrorHandler $handler_default)
	{
		$this->required_rights = $required_rights;
		$this->handler_if_authenticated = $handler_if_authenticated;
		$this->handler_default = $handler_default;
	}

	private $required_rights, $handler_if_authenticated, $handler_default;

	public function handle(\Octarine\Routing\Request\Request $request, \Octarine\Access\Principal $requester, $params)
	{
		if ($requester->canAccess($this->required_rights))
			return $this->handler_if_authenticated->handle($request, $requester, $params);
		else
			return $this->handler_default->handle($request, $requester, $params);
	}
}

?>