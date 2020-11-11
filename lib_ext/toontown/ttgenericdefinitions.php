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

class TTGenericDefinitions implements FishDefinitions, PondDefinitions, CatchDefinitions
{
	private $rod_list, $pond_dict, $fish_dict;
	private $prob_catch_fish, $prob_catch_jb, $prob_catch_boot;
	private $fishes_by_rarity_by_pond_by_rod;

	public function __construct()
	{
		$globalRarityDialBase = $this->getGlobalRarityDialBase();
		$max_rarity = 10;
		$this->prob_catch_fish = 0.93;
		$this->prob_catch_jb = 0.01;
		$this->prob_catch_boot = 0.06;

		$pg_anywhere = '*';
		$pg_ToontownCentral = 'tc';
		$pg_PunchlinePlace = 'tc_pp';
		$pg_SillyStreet = 'tc_ss';
		$pg_LoopyLane = 'tc_ll';
		$pg_DonaldsDock = 'dd';
		$pg_LighthouseLane = 'dd_ll';
		$pg_BarnacleBoulevard = 'dd_bb';
		$pg_SeaweedStreet = 'dd_ss';
		$pg_DaisyGardens = 'dg';
		$pg_ElmStreet = 'dg_es';
		$pg_MapleStreet = 'dg_ms';
		$pg_OakStreet = 'dg_os';
		$pg_MinniesMelodyland = 'mm';
		$pg_AltoAvenue = 'mm_aa';
		$pg_BaritoneBoulevard = 'mm_bb';
		$pg_TenorTerrace = 'mm_tt';
		$pg_TheBrrrgh = 'tb';
		$pg_SleetStreet = 'tb_ss';
		$pg_WalrusWay = 'tb_ww';
		$pg_PolarPlace = 'tb_pp';
		$pg_DonaldsDreamland = 'dl';
		$pg_LullabyLane = 'dl_ll';
		$pg_PajamaPlace = 'dl_pp';
		$pg_Estate = 'ee';
		$pg_OutdoorZone = 'oz';
		$pg_FunnyFarm = 'ff';

		$this->rod_list = array(
			// id => name, max weight, rarity factor
			// NOTE about rarity factor F: if U~UNIF[0,1], then a random
			// rarity is computed as R = max(1, ceil[ 10 - 10 * U^(1/F) ]).
			// In particular, R takes values between 1 and 10 incl.
			// This implies that   Prob[R=r] = (1-(r-1)/10)^F - (1-r/10)^F   for r=1..10.
			'tg' => array('Twig',     4,  $globalRarityDialBase * 1.0),
			'bm' => array('Bamboo',   8,  $globalRarityDialBase * 0.975),
			'hw' => array('Hardwood', 12, $globalRarityDialBase * 0.95),
			'st' => array('Steel',    16, $globalRarityDialBase * 0.9),
			'gd' => array('Gold',     20, $globalRarityDialBase * 0.85),
		);
		$this->pond_dict = array(
			// id, name
			'tc' => array(
				array($pg_ToontownCentral, 'Toontown Central'),
				array($pg_PunchlinePlace, 'Punchline Place'),
				array($pg_SillyStreet, 'Silly Street'),
				array($pg_LoopyLane, 'Loopy Lane'),
			),
			'dd' => array(
				array($pg_DonaldsDock, 'Donald\'s Dock'),
				array($pg_BarnacleBoulevard, 'Barnacle Boulevard'),
				array($pg_LighthouseLane, 'Lighthouse Lane'),
				array($pg_SeaweedStreet, 'Seaweed Street'),
			),
			'dg' => array(
				array($pg_DaisyGardens, 'Daisy Gardens'),
				array($pg_ElmStreet, 'Elm Street'),
				array($pg_MapleStreet, 'Maple Street'),
				array($pg_OakStreet, 'Oak Street'),
			),
			'mm' => array(
				array($pg_MinniesMelodyland, 'Minnie\'s Melodyland'),
				array($pg_AltoAvenue, 'Alto Avenue'),
				array($pg_BaritoneBoulevard, 'Baritone Boulevard'),
				array($pg_TenorTerrace, 'Tenor Terrace'),
			),
			'tb' => array(
				array($pg_TheBrrrgh, 'The Brrrgh'),
				array($pg_SleetStreet, 'Sleet Street'),
				array($pg_WalrusWay, 'Walrus Way'),
				array($pg_PolarPlace, 'Polar Place'),
			),
			'dl' => array(
				array($pg_DonaldsDreamland, 'Donald\'s Dreamland'),
				array($pg_LullabyLane, 'Lullaby Lane'),
				array($pg_PajamaPlace, 'Pajama Place'),
			),
			'ee' => array(
				array($pg_Estate, 'Estate'),
				array($pg_OutdoorZone, 'Toonfest Playground'),
				array($pg_FunnyFarm, 'Acorn Acres'),
			),
		);
		$subponds = array(
			$pg_anywhere => array(
				$pg_ToontownCentral, $pg_PunchlinePlace, $pg_SillyStreet, $pg_LoopyLane,
				$pg_DonaldsDock, $pg_LighthouseLane, $pg_BarnacleBoulevard, $pg_SeaweedStreet,
				$pg_DaisyGardens, $pg_ElmStreet, $pg_MapleStreet, $pg_OakStreet,
				$pg_MinniesMelodyland, $pg_AltoAvenue, $pg_BaritoneBoulevard, $pg_TenorTerrace,
				$pg_TheBrrrgh, $pg_SleetStreet, $pg_WalrusWay, $pg_PolarPlace,
				$pg_DonaldsDreamland, $pg_LullabyLane, $pg_PajamaPlace,
				$pg_Estate, $pg_OutdoorZone, $pg_FunnyFarm),
			$pg_ToontownCentral => array($pg_ToontownCentral, $pg_PunchlinePlace, $pg_SillyStreet, $pg_LoopyLane),
			$pg_PunchlinePlace => array($pg_PunchlinePlace),
			$pg_SillyStreet => array($pg_SillyStreet),
			$pg_LoopyLane => array($pg_LoopyLane),
			$pg_DonaldsDock => array($pg_DonaldsDock, $pg_LighthouseLane, $pg_BarnacleBoulevard, $pg_SeaweedStreet),
			$pg_LighthouseLane => array($pg_LighthouseLane),
			$pg_BarnacleBoulevard => array($pg_BarnacleBoulevard),
			$pg_SeaweedStreet => array($pg_SeaweedStreet),
			$pg_DaisyGardens => array($pg_DaisyGardens, $pg_ElmStreet, $pg_MapleStreet, $pg_OakStreet),
			$pg_ElmStreet => array($pg_ElmStreet),
			$pg_MapleStreet => array($pg_MapleStreet),
			$pg_OakStreet => array($pg_OakStreet),
			$pg_MinniesMelodyland => array($pg_MinniesMelodyland, $pg_AltoAvenue, $pg_BaritoneBoulevard, $pg_TenorTerrace),
			$pg_AltoAvenue => array($pg_AltoAvenue),
			$pg_BaritoneBoulevard => array($pg_BaritoneBoulevard),
			$pg_TenorTerrace => array($pg_TenorTerrace),
			$pg_TheBrrrgh => array($pg_TheBrrrgh, $pg_SleetStreet, $pg_WalrusWay, $pg_PolarPlace),
			$pg_SleetStreet => array($pg_SleetStreet),
			$pg_WalrusWay => array($pg_WalrusWay),
			$pg_PolarPlace => array($pg_PolarPlace),
			$pg_DonaldsDreamland => array($pg_DonaldsDreamland, $pg_LullabyLane, $pg_PajamaPlace),
			$pg_LullabyLane => array($pg_LullabyLane),
			$pg_PajamaPlace => array($pg_PajamaPlace),
			$pg_Estate => array($pg_Estate),
			$pg_OutdoorZone => array($pg_OutdoorZone),
			$pg_FunnyFarm => array($pg_FunnyFarm),
		);
		$this->fish_dict = $this->getFishDict();
		$names = array(0 => array('Balloon Fish', 'Hot Air Balloon Fish', 'Weather Balloon Fish', 'Water Balloon Fish', 'Red Balloon Fish'), 2 => array('Cat Fish', 'Siamese Cat Fish', 'Alley Cat Fish', 'Tabby Cat Fish', 'Tom Cat Fish'), 4 => array('Clown Fish', 'Sad Clown Fish', 'Party Clown Fish', 'Circus Clown Fish'), 6 => array('Frozen Fish'), 8 => array('Star Fish', 'Five Star Fish', 'Rock Star Fish', 'Shining Star Fish', 'All Star Fish'), 10 => array('Holey Mackerel'), 12 => array('Dog Fish', 'Bull Dog Fish', 'Hot Dog Fish', 'Dalmatian Dog Fish', 'Puppy Dog Fish'), 14 => array('Amore Eel', 'Electric Amore Eel'), 16 => array('Nurse Shark', 'Clara Nurse Shark', 'Florence Nurse Shark'), 18 => array('King Crab', 'Alaskan King Crab', 'Old King Crab'), 20 => array('Moon Fish', 'Full Moon Fish', 'Half Moon Fish', 'New Moon Fish', 'Crescent Moon Fish', 'Harvest Moon Fish'), 22 => array('Sea Horse', 'Rocking Sea Horse', 'Clydesdale Sea Horse', 'Arabian Sea Horse'), 24 => array('Pool Shark', 'Kiddie Pool Shark', 'Swimming Pool Shark', 'Olympic Pool Shark'), 26 => array('Brown Bear Acuda', 'Black Bear Acuda', 'Koala Bear Acuda', 'Honey Bear Acuda', 'Polar Bear Acuda', 'Panda Bear Acuda', 'Kodiac Bear Acuda', 'Grizzly Bear Acuda'), 28 => array('Cutthroat Trout', 'Captain Cutthroat Trout', 'Scurvy Cutthroat Trout'), 30 => array('Piano Tuna', 'Grand Piano Tuna', 'Baby Grand Piano Tuna', 'Upright Piano Tuna', 'Player Piano Tuna'), 32 => array('Peanut Butter & Jellyfish', 'Grape PB&J Fish', 'Crunchy PB&J Fish', 'Strawberry PB&J Fish', 'Concord Grape PB&J Fish'), 34 => array('Devil Ray'));
		foreach ($names as $gid => $names)
			foreach ($names as $i => $name)
				$this->fish_dict[$gid][$i][0] = $name;

		/*
		 * COLLECT DATA
		 */

		$this->fishes_by_rarity_by_pond_by_rod = array();
		foreach ($this->rod_list as $rod_id => $_)
		{
			list($rod_name, $rod_max_weight, $rod_rarity_factor) = $_;

			// Initialize dictionary
			$fishes_by_rarity_by_pond = array();
			foreach ($this->pond_dict as $ponds)
			foreach ($ponds as $_)
			{
				list($pond_id, $pond_name) = $_;

				$fishes_by_rarity_by_pond[$pond_id] = array();
				for ($rarity = 1; $rarity <= $max_rarity; $rarity++)
					$fishes_by_rarity_by_pond[$pond_id][$rarity] = array();
			}

			// Collect data
			foreach ($this->fish_dict as $genus => $species_list)
			{
				$species = array();
				foreach ($species_list as $index => $_)
				{
					list($name, $min_weight, $max_weight, $rarity, $zone_list) = $_;

					// Check if one can catch this species with this rod
					if ($min_weight > $rod_max_weight) continue;

					$id = $genus << 4 | $index;
					foreach ($zone_list as $zone_index => $zone)
					{
						// Fishes become more rare when the zone in the definition is not the main zone
						$effective_rarity = min($max_rarity, $rarity + $zone_index);

						// The specified zone is just a mask, actually (e.g. a playground includes all its streets)
						foreach ($subponds[$zone] as $effective_zone)
							$fishes_by_rarity_by_pond[$effective_zone][$effective_rarity][] = $id;
					}
				}
			}

			// Store data
			$this->fishes_by_rarity_by_pond_by_rod[$rod_id] = $fishes_by_rarity_by_pond;
		}
	}

	protected function getFishDict()
	{
		throw new \Exception('Pure virtual - please implement');
	}

	public function getFishGroups()
	{
		$groups = array();
		foreach (array_keys($this->fish_dict) as $order => $id)
		{
			$groups[] = (object)array('id' => $id, 'order' => $order);
		}
		return $groups;
	}

	function getFishes($group_id)
	{
		if (!isset($this->fish_dict[$group_id]))
			throw new \Exception('Undefined fish group id: ' . $group_id);

		$fishes = array();
		foreach ($this->fish_dict[$group_id] as $index => $_)
		{
			list($name, $min_weight, $max_weight, $rarity, $zone_list) = $_;
			$id = $group_id << 4 | $index;
			$fishes[] = (object)array('id' => $id, 'order' => $index, 'name' => $name, 'min_weight' => $min_weight, 'max_weight' => $max_weight, 'rarity' => $rarity);
		}
		return $fishes;
	}

	function getFish($fish_id)
	{
		$fish_id = intval($fish_id);
		$group_id = $fish_id >> 4;
		$index = $fish_id & 15;

		if (!isset($this->fish_dict[$group_id]) || $index < 0 || $index >= count($fishes = $this->fish_dict[$group_id]))
			throw new \Exception('Undefined fish id: ' . $fish_id);

		list($name, $min_weight, $max_weight, $rarity, $zone_list) = $this->fish_dict[$group_id][$index];
		return (object)array('id' => intval($fish_id), 'group' => $group_id, 'order' => $index, 'name' => $name, 'min_weight' => $min_weight, 'max_weight' => $max_weight, 'rarity' => $rarity);
	}

	function getNumberOfFishes($rod_id)
	{
		if (!isset($this->rod_list[$rod_id]))
			throw new \Exception('Undefined rod: ' . $rod_id);

		$rod_max_weight = $this->rod_list[$rod_id][1];
		$count = 0;
		foreach ($this->fish_dict as $species_list)
			foreach ($species_list as $_)
			{
				list($name, $min_weight, $max_weight, $rarity, $zone_list) = $_;
				if ($min_weight <= $rod_max_weight)
					$count++;
			}
		return $count;
	}

	function getRods()
	{
		$rods = array();
		$order = 0;
		foreach ($this->rod_list as $id => $_)
		{
			list($name, $max_weight, $rarity_factor) = $_;
			$rods[] = (object)array('id' => $id, 'order' => $order++, 'name' => $name, 'max_weight' => $max_weight);
		}
		return $rods;
	}

	function getRod($rod_id)
	{
		if (!isset($this->rod_list[$rod_id]))
			throw new \Exception('Undefined rod: ' . $rod_id);

		$orders = array_flip(array_keys($this->rod_list));
		list($name, $max_weight, $rarity_factor) = $this->rod_list[$rod_id];
		return (object)array('id' => $rod_id, 'order' => $orders[$rod_id], 'name' => $name, 'max_weight' => $max_weight);
	}

	function getPondGroups()
	{
		$groups = array();
		foreach (array_keys($this->pond_dict) as $order => $id)
		{
			$groups[] = (object)array('id' => $id, 'order' => $order);
		}
		return $groups;
	}

	function getPonds($group_id)
	{
		if (!isset($this->pond_dict[$group_id]))
			throw new \Exception('Undefined pond group id: ' . $group_id);

		$ponds = array();
		$order = 0;
		foreach ($this->pond_dict[$group_id] as $index => $_)
		{
			list($id, $name) = $_;
			$ponds[] = (object)array('id' => $id, 'order' => $order++, 'name' => $name);
		}
		return $ponds;
	}

	function getPond($pond_id)
	{
		foreach ($this->pond_dict as $group_id => $ponds)
		{
			foreach ($ponds as $index => $_)
			{
				list($id, $name) = $_;
				if ($id == $pond_id)
					return (object)array('id' => $id, 'group' => $group_id, 'order' => $index, 'name' => $name);
			}
		}
		throw new \Exception('Undefined pond: ' . $pond_id);
	}

	function getProbability($fish_id, $rod_id, $pond_id, &$can_catch)
	{
		if (!isset($this->rod_list[$rod_id]))
			throw new \Exception('Undefined rod: ' . $rod_id);

		$rod_rarity_factor = $this->rod_list[$rod_id][2];
		$fishes_by_rarity_by_pond = $this->fishes_by_rarity_by_pond_by_rod[$rod_id];

		if (!isset($fishes_by_rarity_by_pond[$pond_id]))
			throw new \Exception('Undefined pond: ' . $pond_id);
		$fishes_by_rarity = $fishes_by_rarity_by_pond[$pond_id];

		$total_probability = 0.0;
		$can_catch = false;

		// If a rarity list is empty, no fish can be picked and the rarity dice has to be rolled over again.
		// We obtain a random state machine where all rarities with empty fish lists point back to the origin:
		// Example:
		// [origin] --p1--> [rarity1]
		//          --p2--> [rarity2]
		//          --p3--> back to origin
		//          ...
		//          --p10-> [rarity10]
		// In this case all rarities with NON-EMPTY fish lists have an increased probability of being reached,
		// which is just the probability itself, conditioned on the event that we do not get back to the origin.
		// In other words, we multiply each probability with a constant such that the resulting probabilities sum up to 1.
		$empty_list_probability_constant = 1;
		foreach ($fishes_by_rarity as $rarity => $fishes)
			if (count($fishes) == 0)
				$empty_list_probability_constant -= $this->getProbabilityThatRarityIsHit($rarity, $rod_rarity_factor);

		foreach ($fishes_by_rarity as $rarity => $fishes)
		{
			// We used to use in_array($fish_id, $fishes) here, but it might be the case that a fish
			// occurs multiple times in this list! But we have to account for it each time it occurs.
			foreach ($fishes as $_)
			{
				if ($_ == $fish_id)
				{
					$probability_that_rarity_is_hit = $this->getProbabilityThatRarityIsHit($rarity, $rod_rarity_factor) / $empty_list_probability_constant;
					$probability_that_fish_is_hit = 1.0 / count($fishes);

					$can_catch = true;
					$p = $this->prob_catch_fish * $probability_that_fish_is_hit * $probability_that_rarity_is_hit;
					$total_probability += $p;
				}
			}
		}
		return $total_probability;
	}

	private function getProbabilityThatRarityIsHit($rarity, $rod_rarity_factor)
	{
		// See definition of $this->rod_list for a small justification.
		// The math has been done on a separate sheet.
		return pow(1 - ($rarity-1)/10, $rod_rarity_factor) - pow(1 - $rarity/10, $rod_rarity_factor);
	}

	/**
	 * This function is NOT part of any *Definition interface!
	 */
	public function getFishesByPondByRarity($rod_id)
	{
		return $this->fishes_by_rarity_by_pond_by_rod[$rod_id];
	}
}

?>