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

require_once SYS_LIB_ROOT . 'engine/simplesitemap.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';

class I18nSitemap extends SimpleSitemap
{
	public function __construct(\Octarine\I18n\Localizer $localizer)
	{
		$this->localizer = $localizer;
	}

	private $localizer;

	protected function makePageObject($internal_data)
	{
		$page = parent::makePageObject($internal_data);
		$page->title = $this->localizer->format($internal_data->title, $internal_data->title_format_args);
		$page->description = $this->localizer->get($internal_data->description);
		return $page;
	}
}

?>