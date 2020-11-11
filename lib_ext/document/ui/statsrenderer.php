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

require_once SYS_LIB_ROOT . 'document/ui/renderer.php';
require_once SYS_LIB_ROOT . 'document/dom/dommanipulation.php';
require_once SYS_LIB_ROOT . 'document/dom/element.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';
require_once SYS_LIBEXT_ROOT . 'toontown/helper.php';

abstract class StatsRenderer implements \Octarine\Document\UI\Renderer
{
	public function __construct(\Octarine\I18n\Localizer $localizer, $stats, $data_name_url, array $additional_data_attr = array())
	{
		$this->localizer = $localizer;
		$this->stats = $stats;
		$this->data_name_url = $data_name_url;
		$this->additional_data_attr = $additional_data_attr;
	}

	private $localizer;
	private $stats, $data_name_url;
	private $additional_data_attr;

	public function render(\Octarine\Document\Dom\DomManipulation $parent)
	{
		$table_root = $parent->appendElement('table')->setAttr('class', $this->getTableClass());
		foreach ($this->additional_data_attr as $key => $value)
			$table_root->setAttr('data-' . $key, $value);

		if (count($this->stats) > 0)
		{
			$colgroup = $table_root->appendElement('colgroup');
			$colgroup->appendElement('col', true);
			$colgroup->appendElement('col', true);
			$colgroup->appendElement('col', true);
		}
		$table = $table_root->appendElement('tbody');
		foreach ($this->stats as $data)
		{
			$tr = $table->appendElement('tr')->setAttr('id', $this->getRowId($data))->setAttr('data-id', $data->id);

			// Icon
			$tr->appendElement('td')->setAttr('class', 'icon ' . $this->getIconClass($data));

			// Name and advice
			$td = $tr->appendElement('td')->setAttr('class', 'name');
			$td->appendElement('strong')->appendElement('a')->setAttr('href', sprintf($this->data_name_url, $data->id))->appendText($this->localizer->get($data->name));
			if ($data->advice)
			{
				$this->createAdvice($td, $data->advice);
			}

			// Probability
			$td = $tr->appendElement('td')->setAttr('class', 'probability');
			$td->appendElement('strong')->appendText(\Octarine\Toontown\Helper::getProbabilityString($data->prob));
			$num_buckets = \Octarine\Toontown\Helper::getNumberOfRequiredBuckets($data->prob);
			$td->appendElement('small')->appendText($num_buckets <= 1 ? $this->localizer->get('needs 1 bucket') : sprintf($this->localizer->get('needs %d buckets'), $num_buckets));
		}
		if (count($this->stats) == 0)
		{
			$tr = $table->appendElement('tr')->setAttr('class', 'no-data');
			$tr->appendElement('td')->setAttr('colspan', '3')->appendText($this->getNoDataText());
		}
	}

	protected abstract function getTableClass();
	protected abstract function getNoDataText();
	protected abstract function getRowId($data);
	protected abstract function getIconClass($data);
	protected abstract function createAdvice(\Octarine\Document\Dom\Element $element, $advice);
}

?>