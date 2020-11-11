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

class PngResponse implements Response
{
	public function __construct($status, $binary_data, $with_cache = true)
	{
		if ((int)$status <= 0)
			throw new \Exception('Status out of range: ' . (int)$status);
		$this->status = (int)$status;
		$this->binary_data = $binary_data;
		$this->with_cache = !!$with_cache;
	}

	private $status, $binary_data, $with_cache;

	public function getStatus()
	{
		return $this->status;
	}
	public function getContentType()
	{
		return 'image/png';
	}
	public function getContent()
	{
		return $this->binary_data;
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