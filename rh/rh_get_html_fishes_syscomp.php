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
require_once SYS_LIBEXT_ROOT . 'toontown/ttodefinitions.php';
require_once SYS_LIBEXT_ROOT . 'toontown/ttrdefinitions.php';
require_once SYS_LIBEXT_ROOT . 'toontown/fishstatscomputer.php';
require_once SYS_LIBEXT_ROOT . 'document/ui/rodlistrenderer.php';
require_once SYS_LIBEXT_ROOT . 'document/ui/pondstatsrenderer.php';
require_once SYS_RH_ROOT . 'meta.php';

class rh_get_html_fishes_syscomp extends \Octarine\Routing\Request\RequestHandlerBase
{
	public function __construct(\Octarine\Engine\Engine $engine, \Octarine\I18n\Localizer $localizer, \Octarine\Document\SiteFactory $site_factory)
	{
		$this->engine = $engine;
		$this->localizer = $localizer;
		$this->site_factory = $site_factory;
	}

	private $engine, $localizer, $site_factory;

	private function getCommonRodIds($definitions)
	{
		$ids_by_def = array();
		foreach ($definitions as $definition)
		{
			$ids = array();
			foreach ($definition->getRods() as $rod)
				$ids[] = $rod->id;
			$ids_by_def[] = $ids;
		}
		return call_user_func_array('array_intersect', $ids_by_def);
	}

	private function getCommonPondIds($definitions)
	{
		$ids_by_def = array();
		foreach ($definitions as $definition)
		{
			$ids = array();
			foreach ($definition->getPondGroups() as $group)
				foreach ($definition->getPonds($group->id) as $pond)
					$ids[] = $pond->id;
			$ids_by_def[] = $ids;
		}
		return call_user_func_array('array_intersect', $ids_by_def);
	}

	private function getCommonFishIds($definitions)
	{
		$ids_by_def = array();
		foreach ($definitions as $definition)
		{
			$ids = array();
			foreach ($definition->getFishGroups() as $group)
				foreach ($definition->getFishes($group->id) as $fish)
					$ids[] = $fish->id;
			$ids_by_def[] = $ids;
		}
		return call_user_func_array('array_intersect', $ids_by_def);
	}

	public function handle(\Octarine\Routing\Request\ValidatedRequest $request)
	{
		$definitions = array(
			'Toontown Online' => new \Octarine\Toontown\TTODefinitions(),
			'Toontown Rewritten' => new \Octarine\Toontown\TTRDefinitions(),
		);

		$site = $this->site_factory->create($request->getUnhandledRequest());
		$site->setTitle('Probability space comparison'); // already i18n

		// Render site navigation

		renderNavigation($this->engine, $this->localizer, $site, '', 'fishes');

		// Gather common rod/pond/fish lists
		$rods = $this->getCommonRodIds($definitions);
		$ponds = $this->getCommonPondIds($definitions);
		$fishes = $this->getCommonFishIds($definitions);

		// Render table

		$table = $site->appendElement('table')->setAttr('class', 'fishtable');
		$thead = $table->appendElement('thead')->appendElement('tr');
		$thead->appendElement('th')->appendText($this->localizer->get('Rod'));
		$thead->appendElement('th')->appendText($this->localizer->get('Pond'));
		$thead->appendElement('th')->appendText($this->localizer->get('Fish'));
		foreach (array_keys($definitions) as $name)
			$thead->appendElement('th')->appendText($this->localizer->get($name));
		$tbody = $table->appendElement('tbody');
		foreach ($rods as $rod_id)
		foreach ($ponds as $pond_id)
		foreach ($fishes as $fish_id)
		{
			$i = -1;
			$print = false;
			$fish_name = '';
			foreach ($definitions as $definition)
			{
				$fish_name = $definition->getFish($fish_id)->name;
				$ii = $definition->getProbability($fish_id, $rod_id, $pond_id, $can_catch);
				if ($i < 0)
					$i = $ii;
				elseif (abs($i - $ii) > $i * 0.2)
					$print = true;
			}

			if (!$print) continue;

			$tr = $tbody->appendElement('tr');
			$tr->appendElement('td')->appendText($this->localizer->get($rod_id));
			$tr->appendElement('td')->appendText($this->localizer->get($pond_id));
			$tr->appendElement('td')->appendText($this->localizer->get($fish_name));

			foreach ($definitions as $definition)
			{
				$can_catch;
				$prob = $definition->getProbability($fish_id, $rod_id, $pond_id, $can_catch);
				if (!$can_catch)
					$tr->appendElement('td')->appendText('-');
				else
					$tr->appendElement('td')->appendText(sprintf('%.8f', $prob));
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