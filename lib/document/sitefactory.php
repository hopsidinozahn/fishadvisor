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

namespace Octarine\Document;

require_once SYS_LIB_ROOT . 'routing/request/request.php';

interface SiteFactory
{
	/**
	 * Specifies the page which will be displayed by default after the site has been created.
	 * @param string $sitemap_id The id of the page.
	 * @return void
	 */
	public function setDefaultWhereabouts($sitemap_id);

	/**
	 * Creates and instantiates a site.
	 * @param \Octarine\Routing\Request\Request $raw_request The raw (i.e., unhandled) request made by the client.
	 * @return \Octarine\Document\Site
	 */
	public function create(\Octarine\Routing\Request\Request $raw_request);
}

?>