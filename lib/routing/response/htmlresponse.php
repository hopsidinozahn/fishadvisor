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

namespace Octarine\Routing\Response;
require_once SYS_LIB_ROOT . 'routing/response/response.php';
require_once SYS_LIB_ROOT . 'document/htmldocument.php';

class HtmlResponse implements Response
{
	public function __construct($status, \Octarine\Document\HtmlDocument $document, $with_cache = true)
	{
		if ((int)$status <= 0)
			throw new \Exception('Status out of range: ' . (int)$status);
		$this->status = (int)$status;
		$this->document = $document;
		$this->with_cache = !!$with_cache;
	}

	private $status, $document, $with_cache;

	public static $shallIndentHtml = true;

	public function getStatus()
	{
		return $this->status;
	}
	public function getContentType()
	{
		return 'text/html;charset=utf-8';
	}
	public function getContent()
	{
		if (self::$shallIndentHtml)
			return $this->document->serialize("\r\n", "\t");
		else
			return $this->document->serialize('', '');
	}
	public function getAdditionalHeaders()
	{
		return array();
	}
	public function enableCacheControl()
	{
		return $this->with_cache;
	}
}

?>