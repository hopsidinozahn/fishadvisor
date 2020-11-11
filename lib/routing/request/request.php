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

interface Request
{
	/**
	 * Gets the parameters contained in this request.
	 * @return \Octarine\Routing\Request\Parameters
	 */
	public function getParameters();

	/**
	 * Gets the method of this request.
	 * @return string One of the \Octarine\Routing\METHOD_* constants.
	 */
	public function getMethod();

	/**
	 * Chooses the content type which best agrees with the ones accepted by this request.
	 * @param string[] $possible_content_types The possible content types available for a document.
	 * @return string|null One of the values in $possible_content_types, or null, if none was matched.
	 */
	public function matchContentType(array $possible_content_types);

	/**
	 * Gets the path of the document that is being requested.
	 * @return string|null The path that is relative to the absolute path of the site and always begins with a slash (/); or null, if no valid path has been requested.
	 */
	public function getPath();
}

?>