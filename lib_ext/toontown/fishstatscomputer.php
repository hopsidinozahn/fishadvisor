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

namespace Octarine\Toontown;

require_once SYS_LIBEXT_ROOT . 'toontown/fishdefinitions.php';
require_once SYS_LIBEXT_ROOT . 'toontown/ponddefinitions.php';
require_once SYS_LIBEXT_ROOT . 'toontown/catchdefinitions.php';

class FishStatsComputer
{
	public function __construct(\Octarine\Toontown\FishDefinitions $fishdef, \Octarine\Toontown\PondDefinitions $ponddef, \Octarine\Toontown\CatchDefinitions $catchdef)
	{
		$this->fishdef = $fishdef;
		$this->ponddef = $ponddef;
		$this->catchdef = $catchdef;
	}

	private $fishdef, $ponddef, $catchdef;

	public function aggregateByPond($rod_id, $pond_id)
	{
		$data = array();
		$sort = array();
		foreach ($this->fishdef->getFishGroups() as $fishgroup)
		{
			foreach ($this->fishdef->getFishes($fishgroup->id) as $fish)
			{
				$prob = $this->catchdef->getProbability($fish->id, $rod_id, $pond_id, $can_catch);
				if (!$can_catch) continue;

				// Find "better" ponds
				$bestprob = $prob;
				$bestpond = null;
				foreach ($this->ponddef->getPondGroups() as $pondgroup)
					foreach ($this->ponddef->getPonds($pondgroup->id) as $pond)
						if ($bestprob < ($p = $this->catchdef->getProbability($fish->id, $rod_id, $pond->id, $_)))
						{
							$bestprob = $p;
							$bestpond = $pond;
						}

				$data[] = (object)array(
					'id' => $fish->id,
					'name' => $fish->name,
					'group' => $fishgroup->id,
					'prob' => $prob,
					'min_weight' => $fish->min_weight,
					'max_weight' => $fish->max_weight,
					'advice' => !$bestpond ? null : (object)array('id' => $bestpond->id, 'name' => $bestpond->name, 'prob' => $bestprob)
				);
				$sort[] = $prob;
			}
		}
		array_multisort($sort, SORT_DESC, $data);
		return $data;
	}

	public function aggregateByFish($rod_id, $fish_id)
	{
		$data = array();
		$sort = array();
		foreach ($this->ponddef->getPondGroups() as $pondgroup)
		{
			foreach ($this->ponddef->getPonds($pondgroup->id) as $pond)
			{
				$prob = $this->catchdef->getProbability($fish_id, $rod_id, $pond->id, $can_catch);
				if (!$can_catch) continue;

				// Find "better" rods
				$bestprob = $prob;
				$bestrod = null;
				foreach ($this->fishdef->getRods() as $rod)
					if ($bestprob < ($p = $this->catchdef->getProbability($fish_id, $rod->id, $pond->id, $_)))
					{
						$bestprob = $p;
						$bestrod = $rod;
					}

				$data[] = (object)array(
					'id' => $pond->id,
					'name' => $pond->name,
					'group' => $pondgroup->id,
					'prob' => $prob,
					'advice' => !$bestrod ? null : (object)array('id' => $bestrod->id, 'name' => $bestrod->name, 'prob' => $bestprob)
				);
				$sort[] = $prob;
			}
		}
		array_multisort($sort, SORT_DESC, $data);
		return $data;
	}

	public function getPondsByProbability($rod_id, array $fishes)
	{
		$data = array();
		$sort = array();
		foreach ($this->ponddef->getPondGroups() as $pondgroup)
		{
			foreach ($this->ponddef->getPonds($pondgroup->id) as $pond)
			{
				$prob_new_species = 0;
				$new_species = array();
				$sort_inner = array();
				foreach ($fishes as $fish)
				{
					$p = $this->catchdef->getProbability($fish->id, $rod_id, $pond->id, $can_catch);
					if ($can_catch)
					{
						$prob_new_species += $p;
						$new_species[] = (object)array('fish' => $fish, 'prob' => $p);
						$sort_inner[] = $p;
					}
				}
				array_multisort($sort_inner, SORT_DESC, $new_species);

				$data[] = (object)array(
					'id' => $pond->id,
					'name' => $pond->name,
					'group' => $pondgroup->id,
					'prob' => $prob_new_species,
					'advice' => $new_species
				);
				$sort[] = $prob_new_species;
			}
		}
		array_multisort($sort, SORT_DESC, $data);
		return $data;
	}

	public function getFishesByProbability($rod_id, array $fishes)
	{
		$data = array();
		$sort = array();
		$my_rod = $this->fishdef->getRod($rod_id);
		foreach ($fishes as $fish)
		{
			$ponds = array();
			$sort_inner = array();
			$best_prob = -1;
			$best_rod = null;
			foreach ($this->ponddef->getPondGroups() as $pondgroup)
			{
				foreach ($this->ponddef->getPonds($pondgroup->id) as $pond)
				{
					$prob = $this->catchdef->getProbability($fish->id, $my_rod->id, $pond->id, $can_catch);
					if (!$can_catch) continue;

					// Keep track of the overall best rod
					if ($prob > $best_prob)
					{
						$best_prob = $prob;
						$best_rod = $my_rod;
					}

					// Check if it is more sensible to fish with a different rod here
					$there_is_better_rod = false;
					foreach ($this->fishdef->getRods() as $rod)
					{
						if ($rod->order > $my_rod->order)
						{
							$p = $this->catchdef->getProbability($fish->id, $rod->id, $pond->id, $can_catch);
							if ($can_catch && $p > $prob)
							{
								$there_is_better_rod = true;

								// Keep track of the overall best rod
								if ($p > $best_prob)
								{
									$best_prob = $p;
									$best_rod = $rod;
								}
							}
						}
					}
					// In case my rod is the best one here, keep track of the pond
					if (!$there_is_better_rod)
					{
						$ponds[] = (object)array('pond' => $pond, 'prob' => $prob);
						$sort_inner[] = $prob;
					}
				}
			}

			// Only output if the overall best catch probability can be achieved with my rod
			if ($best_rod !== null && $best_rod->id == $my_rod->id)
			{
				array_multisort($sort_inner, SORT_DESC, $ponds);
				$data[] = (object)array(
					'id' => $fish->id,
					'name' => $fish->name,
					'group' => $fish->group->id,
					'prob' => $best_prob,
					'advice' => $ponds
				);
				$sort[] = $best_prob;
			}
		}
		array_multisort($sort, SORT_DESC, $data);
		return $data;
	}
}

?>