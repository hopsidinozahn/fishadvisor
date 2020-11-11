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

interface ValidatedRequest
{
	/**
	 * Gets the route matching this request.
	 *
	 * @return \Octarine\Routing\Route
	 */
	public function getRoute();

	/**
	 * Gets the path of the document that is being requested.
	 * The path is relative to the absolute path of the site and always begins with a slash (/).
	 *
	 * @return string
	 */
	public function getPath();

	/**
	 * Gets the requested and accepted content type.
	 *
	 * @return string A MIME type.
	 */
	public function getContentType();

	/**
	 * Gets the (validated) parameters contained in this request.
	 *
	 * @return \Octarine\Routing\Request\Parameters
	 */
	public function getParameters();

	/**
	 * Gets the requesting principal.
	 *
	 * @return \Octarine\Access\Principal
	 */
	public function getRequester();

	/**
	 * Gets the original (i.e., unhandled) request that lead to this validated request instance.
	 *
	 * @return \Octarine\Routing\Request\Request
	 */
	public function getUnhandledRequest();
}

?>