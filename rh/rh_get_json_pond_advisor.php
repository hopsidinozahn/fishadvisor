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
require_once SYS_LIB_ROOT . 'routing/request/requesthandlerbase.php';
require_once SYS_LIB_ROOT . 'routing/response/jsonresponse.php';
require_once SYS_LIB_ROOT . 'i18n/localizer.php';
require_once SYS_LIBEXT_ROOT . 'toontown/ttrdefinitions.php';
require_once SYS_LIBEXT_ROOT . 'toontown/fishstatscomputer.php';
require_once SYS_LIBEXT_ROOT . 'toontown/helper.php';
require_once SYS_RH_ROOT . 'meta.php';

class rh_get_json_pond_advisor extends \Octarine\Routing\Request\RequestHandlerBase
{
	public function __construct(\Octarine\Engine\Engine $engine, \Octarine\I18n\Localizer $localizer)
	{
		$this->engine = $engine;
		$this->localizer = $localizer;
	}

	private $engine, $localizer;

	public function getParameterValidator()
	{
		$p = parent::getParameterValidator();
		$p->defineString('rid');
		$p->defineInt('fid', false, true); // array of fish identifiers
		return $p;
	}

	public function handle(\Octarine\Routing\Request\ValidatedRequest $request)
	{
		$def = new \Octarine\Toontown\TTRDefinitions();
		$stats_computer = new \Octarine\Toontown\FishStatsComputer($def, $def, $def);

		// Fetch stats

		$rod = $def->getRod($request->getParameters()->get('rid'));
		$interested_fish_list = $this->getInterestedFishList($def, $request->getParameters()->getOrDefault('fid', array()));
		$stats = $stats_computer->getPondsByProbability($rod->id, $interested_fish_list);

		// Prepare stats for output

		$data = array();
		foreach ($stats as $entry)
		{
			$fishes = array();
			$i = 0;
			foreach ($entry->advice as $advice_entry)
			{
				if (++$i > 3) break;
				$fishes[] = array(
					'id' => $advice_entry->fish->id,
					'name' => $advice_entry->fish->name,
					'prob' => \Octarine\Toontown\Helper::getProbabilityString($advice_entry->prob)
				);
			}
			$data[] = array(
				'id' => $entry->id,
				'name' => $entry->name,
				'prob' => \Octarine\Toontown\Helper::getProbabilityString($entry->prob),
				'num_buckets' => \Octarine\Toontown\Helper::getNumberOfRequiredBuckets($entry->prob),
				'num_total_samples' => count($entry->advice),
				'samples' => $fishes
			);
		}

		return new \Octarine\Routing\Response\JsonResponse(200, $data);
	}

	private function getInterestedFishList($def, $fish_ids)
	{
		$fishes = array();
		foreach ($def->getFishGroups() as $group)
			foreach ($def->getFishes($group->id) as $fish)
				if (!in_array($fish->id, $fish_ids))
					$fishes[] = $fish;
		return $fishes;
	}
}

?>