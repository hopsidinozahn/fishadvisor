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

namespace Octarine\Document\UI;

require_once SYS_LIB_ROOT . 'document/dom/element.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';
require_once SYS_LIBEXT_ROOT . 'document/ui/statsrenderer.php';
require_once SYS_LIBEXT_ROOT . 'toontown/helper.php';

class FishStatsRenderer extends StatsRenderer
{
	public function __construct(\Octarine\I18n\Localizer $localizer, $stats, $data_name_url, $data_advice_url, array $additional_data_attr = array())
	{
		$this->localizer = $localizer;
		parent::__construct($localizer, $stats, $data_name_url, $additional_data_attr);
		$this->data_advice_url = $data_advice_url;
	}

	private $localizer;
	private $data_advice_url;

	protected function getTableClass()
	{
		return 'fishlist pondorfishlist';
	}

	protected function getNoDataText()
	{
		return $this->localizer->get('No further species can be caught at this pond.');
	}

	protected function getRowId($data)
	{
		return 'fish_' . $data->id;
	}

	protected function getIconClass($data)
	{
		return 'fishgroup_' . $data->group;
	}

	protected function createAdvice(\Octarine\Document\Dom\Element $element, $advice)
	{
		$small = $element->appendElement('small');
		$small->appendText(sprintf($this->localizer->get('most common (%s) in '), \Octarine\Toontown\Helper::getProbabilityString($advice->prob)));
		$small->appendElement('a')->setAttr('href', sprintf($this->data_advice_url, $advice->id))->appendText($this->localizer->get($advice->name));
		return $small;
	}
}

?>