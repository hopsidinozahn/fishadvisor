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

class RedirectResponse implements Response
{
	public function __construct($status, $url)
	{
		if ((int)$status <= 0)
			throw new \Exception('Status out of range: ' . (int)$status);
		$this->status = (int)$status;
		$this->url = strval($url);
	}
	public function getStatus()
	{
		return $this->status;
	}
	public function getContentType()
	{
		return null;
	}
	public function getContent()
	{
		return null;
	}
	public function getAdditionalHeaders()
	{
		return array( 'Location' => $this->url );
	}
	public function enableCacheControl()
	{
		// Redirects do not have a response content, anyway.
		return false;
	}
}

?>