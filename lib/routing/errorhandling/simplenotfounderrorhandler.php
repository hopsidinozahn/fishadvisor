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
require_once SYS_LIB_ROOT . 'document/sitefactory.php';
require_once SYS_LIB_ROOT . 'routing/request/request.php';
require_once SYS_LIB_ROOT . 'access/principal.php';
require_once SYS_LIB_ROOT . 'routing/response/htmlresponse.php';
require_once SYS_LIB_ROOT . 'routing/response/jsonresponse.php';
require_once SYS_LIB_ROOT . 'routing/response/textresponse.php';

class SimpleNotFoundErrorHandler implements RequestErrorHandler
{
	public function __construct(\Octarine\Document\SiteFactory $siteFactory)
	{
		$this->siteFactory = $siteFactory;
	}

	private $siteFactory;

	public function handle(\Octarine\Routing\Request\Request $request, \Octarine\Access\Principal $requester, $params)
	{
		$error_string = sprintf('No handler defined for request %s %s.', $request->getMethod(), $request->getPath());
		switch ($request->matchContentType(array('text/html', 'application/json', 'text/plain')))
		{
			case 'text/html':
				$site = $this->siteFactory->create($request);
				$site->setWhereabouts('404');
				$site->appendElement('h1')->appendText('Not Found');
				$site->appendElement('p')->appendText('The page or document you requested cannot be found.');
				return new \Octarine\Routing\Response\HtmlResponse(404, $site->getDocument());
			case 'application/json':
				return new \Octarine\Routing\Response\JsonResponse(404, array('error_msg' => $error_string));
			default:
				return new \Octarine\Routing\Response\TextResponse(404, $error_string);
		}
	}
}

?>