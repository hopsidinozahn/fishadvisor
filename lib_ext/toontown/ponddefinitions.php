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

interface PondDefinitions
{
	/**
	 * Returns a collection of stdclass instances, each representing a pond group, with the following properties:
	 * - id: an internal identifier
	 * - order: an integer determining the sort order of the groups (smallest is first)
	 * @return stdclass[]
	 */
	function getPondGroups();

	/**
	 * Returns a collection of stdclass instances, each representing a pond, with the following properties:
	 * - id: an internal identifier (which is unique within the whole system, not only within the group)
	 * - order: an integer determining the sort order of the ponds (smallest is first) within the group
	 * - name: the English name of the pond
	 * @param string $group_id The identifier of the pond group.
	 * @return stdclass[]
	 */
	function getPonds($group_id);

	/**
	 * Returns a stdclass instance representing a pond, with the following properties:
	 * - id: an internal identifier
	 * - group: the identifer of the group the pond is part of
	 * - name: the English name of the pond
	 * @param string $pond_id The identifier of the pond.
	 * @return stdclass[]
	 */
	function getPond($pond_id);
}

?>