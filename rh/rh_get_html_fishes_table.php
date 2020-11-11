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

require_once SYS_LIB_ROOT . 'engine/engine.php';
require_once SYS_LIB_ROOT . 'document/sitefactory.php';
require_once SYS_LIB_ROOT . 'routing/request/requesthandlerbase.php';
require_once SYS_LIB_ROOT . 'routing/response/htmlresponse.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';
require_once SYS_LIBEXT_ROOT . 'toontown/ttrdefinitions.php';
require_once SYS_LIBEXT_ROOT . 'toontown/fishstatscomputer.php';
require_once SYS_LIBEXT_ROOT . 'document/ui/rodlistrenderer.php';
require_once SYS_LIBEXT_ROOT . 'document/ui/pondstatsrenderer.php';
require_once SYS_RH_ROOT . 'meta.php';

class rh_get_html_fishes_table extends \Octarine\Routing\Request\RequestHandlerBase
{
	public function __construct(\Octarine\Engine\Engine $engine, \Octarine\I18n\Localizer $localizer, \Octarine\Document\SiteFactory $site_factory)
	{
		$this->engine = $engine;
		$this->localizer = $localizer;
		$this->site_factory = $site_factory;
	}

	private $engine, $localizer, $site_factory;

	public function getParameterValidator()
	{
		$p = parent::getParameterValidator();
		$p->defineString('rid', false); // not required, assume "twig" if none specified
		return $p;
	}

	public function handle(\Octarine\Routing\Request\ValidatedRequest $request)
	{
		$def = new \Octarine\Toontown\TTRDefinitions();
		$rod = $def->getRod($request->getParameters()->getOrDefault('rid', 'tg'));
		$fishes_by_pond_by_rarity = $def->getFishesByPondByRarity($rod->id);

		$site = $this->site_factory->create($request->getUnhandledRequest());
		$site->setTitle('Table of fishes'); // already i18n
		$site->document->addStylesheet('/style/all-fishtable.css');

		// Render site navigation

		renderNavigation($this->engine, $this->localizer, $site, $rod->id, 'fishes');

		// Gather meta information

		$num_fishes_by_pond = array();
		foreach ($def->getPondGroups() as $pond_group)
		{
			foreach ($def->getPonds($pond_group->id) as $pond)
			{
				$num_fishes_by_pond[$pond->id] = array();
				for ($rarity = 1; $rarity <= 10; $rarity++)
				{
					foreach ($fishes_by_pond_by_rarity[$pond->id][$rarity] as $fish_id)
					{
						if (!isset($num_fishes_by_pond[$pond->id][$fish_id]))
							$num_fishes_by_pond[$pond->id][$fish_id] = 1;
						else
							$num_fishes_by_pond[$pond->id][$fish_id]++;
					}
				}
			}
		}

		// Render table

		$table = $site->appendElement('table')->setAttr('class', 'fishtable');
		$thead = $table->appendElement('thead')->appendElement('tr');
		$thead->appendElement('th')->appendText($this->localizer->get('Pond'));
		for ($rarity = 1; $rarity <= 10; $rarity++)
			$thead->appendElement('th')->appendText(sprintf($this->localizer->get('Rarity %s'), $rarity));
		$tbody = $table->appendElement('tbody');
		foreach ($def->getPondGroups() as $pond_group)
		{
			foreach ($def->getPonds($pond_group->id) as $pond)
			{
				$tr = $tbody->appendElement('tr');
				$tr->appendElement('td')->appendText($this->localizer->get($pond->name));
				for ($rarity = 1; $rarity <= 10; $rarity++)
				{
					$td = $tr->appendElement('td');
					foreach ($fishes_by_pond_by_rarity[$pond->id][$rarity] as $fish_id)
					{
						$fish = $def->getFish($fish_id);
						$class = 'fish fishgroup_' . $fish->group;
						if (($cnt = $num_fishes_by_pond[$pond->id][$fish_id]) > 1)
							$class .= ' more more' . $cnt;
						$td->appendElement('span')->setAttr('class', $class)->appendText($fish->name);
					}
				}
			}
		}

		return new \Octarine\Routing\Response\HtmlResponse(200, $site->getDocument());
	}

	private function getRarityText($stats)
	{
		$maxprob = 0;
		foreach ($stats as $entry)
			$maxprob = max($maxprob, $entry->prob);

		if ($maxprob < .001)
			return $this->localizer->get('very rare');
		elseif ($maxprob < .01)
			return $this->localizer->get('rare');
		else
			return $this->localizer->get('common');
	}

	private function getMinRod($def, $fish)
	{
		foreach ($def->getRods() as $rod)
			if ($rod->max_weight >= $fish->min_weight)
				return $rod;
	}
}

?>