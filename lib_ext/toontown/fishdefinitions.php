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

interface FishDefinitions
{
	/**
	 * Returns a collection of stdclass instances, each representing a fish group, with the following properties:
	 * - id: an internal identifier
	 * - order: an integer determining the sort order of the groups (smallest is first)
	 * @return stdclass[]
	 */
	function getFishGroups();

	/**
	 * Returns a collection of stdclass instances, each representing a fish, with the following properties:
	 * - id: an internal identifier (which is unique within the whole system, not only within the group)
	 * - order: an integer determining the sort order of the fishes within the group (smallest is first)
	 * - name: the English name of the fish
	 * - min_weight: the minimum weight in some unit
	 * - max_weight: the maximum weight in some unit
	 * @param string $group_id The identifier of the fish group.
	 * @return stdclass[]
	 */
	function getFishes($group_id);

	/**
	 * Returns a stdclass instance representing the given fish, with the following properties:
	 * - id: an internal identifier
	 * - name: the English name of the fish
	 * - group: the identifer of the group the fish is part of
	 * - min_weight: the minimum weight in some unit
	 * - max_weight: the maximum weight in some unit
	 * @param string $fish_id The identifier of the fish.
	 * @return stdclass
	 */
	function getFish($fish_id);

	/**
	 * Gets the maximum number of fishes one can getch using the given rod.
	 * @param string $rod_id The identifier of the rod.
	 * @return int
	 */
	function getNumberOfFishes($rod_id);

	/**
	 * Returns a collection of stdclass instances, each representing a rod, with the following properties:
	 * - id: an internal identifier
	 * - order: an integer determining the sort order of the rods (smallest is first)
	 * - max_weight: the maximum weight this rod can catch
	 * - name: the English name of the rod
	 * @return stdclass[]
	 */
	function getRods();

	/**
	 * Returns a stdclass instance representing a rod, with the following properties:
	 * - id: an internal identifier
	 * - max_weight: the maximum weight this rod can catch
	 * - name: the English name of the rod
	 * @param string $rod_id The identifier of the rod.
	 * @return stdclass[]
	 */
	function getRod($rod_id);
}

?>