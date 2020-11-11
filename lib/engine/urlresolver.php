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

interface UrlResolver
{
	/*
	 * Converts a generic relative URL to an absolute URL.
	 * @param string $relative_url The relative URL, which must be relative to the (generic) site root and begin with a slash (/).
	 * @return string The absolute URL, starting with a slash, ready to be appended to the site host name.
	 */
	public function getAbsoluteGenericUrl($relative_url);

	/*
	 * Converts a relative URL of a document to an absolute URL.
	 * @param string $relative_url The relative URL, which must be relative to the (document) site root and begin with a slash (/).
	 * @return string The absolute URL, starting with a slash, ready to be appended to the site host name.
	 */
	public function getAbsoluteDocumentUrl($relative_url);
}

?>