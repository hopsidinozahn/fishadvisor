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
require_once SYS_LIB_ROOT . 'routing/response/redirectresponse.php';
require_once SYS_LIB_ROOT . 'access/principal.php';

class RedirectRequestErrorHandler implements RequestErrorHandler
{
	public function __construct($redirect_to_url, $referer_parameter = 'referer')
	{
		$this->redirect_to_url = $redirect_to_url;
		$this->referer_parameter = $referer_parameter;
	}

	private $redirect_to_url, $referer_parameter;

	public function handle(\Octarine\Routing\Request\Request $request, \Octarine\Access\Principal $requester, $params)
	{
		$referer_url = $request->getPath();
		if ($referer_url === null)
		{
			return $this->redirect_to_url;
		}
		if ($_SERVER['QUERY_STRING'])
		{
			$referer_url .= '?' . $_SERVER['QUERY_STRING'];
		}
		$redirect_url = sprintf('%s?%s=%s', $this->redirect_to_url, urlencode($this->referer_parameter), urlencode($referer_url));
		return new \Octarine\Routing\Response\RedirectResponse(403, $redirect_url);
	}
}

?>