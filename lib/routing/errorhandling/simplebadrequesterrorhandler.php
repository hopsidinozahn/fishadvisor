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
require_once SYS_LIB_ROOT . 'routing/request/_constants.php';
require_once SYS_LIB_ROOT . 'access/principal.php';
require_once SYS_LIB_ROOT . 'routing/response/htmlresponse.php';
require_once SYS_LIB_ROOT . 'routing/response/jsonresponse.php';
require_once SYS_LIB_ROOT . 'routing/response/textresponse.php';

class SimpleBadRequestErrorHandler implements RequestErrorHandler
{
	public function __construct(\Octarine\Document\SiteFactory $siteFactory)
	{
		$this->siteFactory = $siteFactory;
	}

	private $siteFactory;

	public function handle(\Octarine\Routing\Request\Request $request, \Octarine\Access\Principal $requester, $params)
	{
		$status = $params->reason >= \Octarine\Routing\Request\PARAM_ERROR_FORMAT ? 422 : 400;
		switch ($request->matchContentType(array('text/html', 'application/json', 'text/plain')))
		{
			case 'text/html':
				$site = $this->siteFactory->create($request);
				$site->setWhereabouts('400');
				$site->appendElement('h1')->appendText('Bad request');
				$site->appendElement('p')->appendText('The request was badly formed.');
				return new \Octarine\Routing\Response\HtmlResponse($status, $site->getDocument());
			case 'application/json':
				return new \Octarine\Routing\Response\JsonResponse($status, array('error_msg' => 'Bad request: invalid parameter.', 'error_parameter' => $params->parameter, 'error_reason' => $params->reason));
			default:
				$error_string = sprintf('Bad request: parameter "%s" is invalid (reason: %s).', $params->parameter, $params->reason);
				return new \Octarine\Routing\Response\TextResponse($status, $error_string);
		}
	}
}

?>