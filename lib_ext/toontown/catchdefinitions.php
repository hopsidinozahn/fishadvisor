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

interface CatchDefinitions
{
	/**
	 * Gets the catch probability of the given fish with the given rod at the given location.
	 * @param string $fish_id The fish identifier.
	 * @param string $rod_id The rod identifier.
	 * @param string $location_id The location identifier.
	 * @param bool $can_catch Indicates whether the species can be caught, i.e. if the catch probability is positive. Use this value rather than to check if probability is zero (to avoid issues with floating numbers).
	 * @return float Returns a value in [0.0,1.0] representing the catch probability.
	 */
	function getProbability($fish_id, $rod_id, $location_id, &$can_catch);
}

?>