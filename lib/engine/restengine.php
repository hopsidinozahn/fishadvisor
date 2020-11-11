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

require_once SYS_LIB_ROOT . 'engine/engine.php';
require_once SYS_LIB_ROOT . 'routing/errorhandling/requesterrorhandler.php';
require_once SYS_LIB_ROOT . 'routing/restapi.php';
require_once SYS_LIB_ROOT . 'routing/request/restrequest.php';
require_once SYS_LIB_ROOT . 'routing/response/response.php';
require_once SYS_LIB_ROOT . 'routing/response/fileresponse.php';
require_once SYS_LIB_ROOT . 'routing/response/cachecontrol.php';
require_once SYS_LIB_ROOT . 'access/principalmanager.php';
require_once SYS_LIB_ROOT . 'access/session.php';

class RestEngine implements Engine
{
	public function __construct($generic_web_root, $document_web_root, \Octarine\Access\PrincipalManager $principal_mgr)
	{
		$this->generic_web_root = rtrim(strval($generic_web_root), '/');
		$this->document_web_root = rtrim(strval($document_web_root), '/');
		$this->principal_mgr = $principal_mgr;
		$this->api = new \Octarine\Routing\RestApi();
	}

	private $generic_web_root, $document_web_root;
	private $principal_mgr, $api;

	public function getAbsoluteGenericUrl($relative_url)
	{
		if (strpos($relative_url, '/') !== 0)
			throw new \Exception('$relative_url must start with a slash.');
		return $this->generic_web_root . $relative_url;
	}

	public function getAbsoluteDocumentUrl($relative_url)
	{
		if (strpos($relative_url, '/') !== 0)
			throw new \Exception('$relative_url must start with a slash.');
		return $this->document_web_root . $relative_url;
	}

	public function getApi()
	{
		return $this->api;
	}

	public function run()
	{
		// Determine requesting principal
		$principalId = \Octarine\Access\Session::getPrincipalId();
		$principal = $this->principal_mgr->getOrDefault($principalId);

		// Fetch response from API
		$request = \Octarine\Routing\Request\RestRequest::fromEnvironment($this->document_web_root);
		$response = $this->getApi()->process($request, $principal);

		// Serve response
		$this->reply($response);
	}

	public function runWithErrorHandler(\Octarine\Routing\ErrorHandling\RequestErrorHandler $error_handler, $error_handler_params)
	{
		// Determine requesting principal
		$principalId = \Octarine\Access\Session::getPrincipalId();
		$principal = $this->principal_mgr->getOrDefault($principalId);

		// Fetch response from error handler
		$request = \Octarine\Routing\Request\RestRequest::fromEnvironment($this->document_web_root);
		$response = $error_handler->handle($request, $principal, array());

		// Serve response
		$this->reply($response);
	}

	protected function reply(\Octarine\Routing\Response\Response $response)
	{
		$this->reply_headers($response);
		$this->reply_content($response);
	}

	protected function reply_headers(\Octarine\Routing\Response\Response $response)
	{
		http_response_code($response->getStatus());
		$content_type = $response->getContentType();
		if ($content_type)
		{
			header('Content-Type: ' . $content_type);
		}
		foreach ($response->getAdditionalHeaders() as $h => $value)
		{
			$value = preg_replace('/[\\x0-\\x1F\\x7F]/', '', $value);
			header($h . ': ' . $value);
		}
	}

	protected function reply_content(\Octarine\Routing\Response\Response $response)
	{
		$responseContent = $response->getContent();
		if ($response instanceof \Octarine\Routing\Response\FileResponse)
		{
			$filepath = $response->filepath;
			header('Content-Length: ' . filesize($filepath));
			readfile($filepath);
		}
		elseif (!isset($responseContent))
		{
			// Don't do anything.
		}
		elseif ($response->enableCacheControl())
		{
			$cc = new \Octarine\Routing\Response\CacheControl($responseContent);
			$cc->deliver();
		}
		else
		{
			header('Content-Length: ' . strlen($responseContent)); // must use strlen(), which gives number of bytes
			echo $responseContent;
		}
	}
}

?>