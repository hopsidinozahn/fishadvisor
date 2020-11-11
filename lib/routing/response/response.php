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

interface Response
{
	/**
	 * Gets the status of this response.
	 *
	 * @return int
	 */
	public function getStatus();
	/**
	 * Gets the response content type.
	 *
	 * @return null|string
	 */
	public function getContentType();
	/**
	 * Gets the content string of this response.
	 *
	 * @return null|string
	 */
	public function getContent();
	/**
	 * Gets additional headers which shall be specified.
	 *
	 * @return array An associative array of the form header name => value.
	 */
	public function getAdditionalHeaders();
	/**
	 * Indicates whether the cache control shall be enabled.
	 *
	 * Cache control prevents the content from being delivered if the client already has a cached version of it.
	 * Also note that cache control may override the status code, so if the status code is important
	 * to the meaning of the response, cache control should be disabled.
	 *
	 * @return bool True to enable cache control, false otherwise.
	 */
	public function enableCacheControl();
}

?>