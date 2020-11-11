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

require_once SYS_LIB_ROOT . 'document/sitefactory.php';
require_once SYS_LIB_ROOT . 'engine/engine.php';
require_once SYS_LIB_ROOT . 'routing/request/request.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';
require_once SYS_LIBEXT_ROOT . 'document/localsite.php';

class LocalSiteFactory implements SiteFactory
{
	public function __construct(\Octarine\Engine\Engine $engine, \Octarine\I18n\Localizer $localizer)
	{
		$this->engine = $engine;
		$this->localizer = $localizer;
	}

	private $engine, $localizer;
	private $whereabouts = null;

	public function setDefaultWhereabouts($sitemap_id)
	{
		$this->whereabouts = $sitemap_id;
	}

	public function create(\Octarine\Routing\Request\Request $raw_request)
	{
		$site = new LocalSite($this->engine, $this->localizer, $raw_request);
		if ($this->whereabouts !== null)
			$site->setWhereabouts($this->whereabouts);
		return $site;
	}
}

?>